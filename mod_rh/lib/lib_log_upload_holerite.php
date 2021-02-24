<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_HISTORICOS") {

    if (strpos($_SESSION["vs_array_access"], "T0025") > 0) {

        $v_pos = strpos($_SESSION["vs_array_access"], "T0025");
        $v_competencias_ler = substr($_SESSION["vs_array_access"], $v_pos + 8, 1);
        $v_competencias_criar = substr($_SESSION["vs_array_access"], $v_pos + 12, 1);
        $v_competencias_gravar = substr($_SESSION["vs_array_access"], $v_pos + 16, 1);
        $v_competencias_excluir = substr($_SESSION["vs_array_access"], $v_pos + 20, 1);
        // var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

    }
    // GERANDO A LISTA

    $v_sql = "SELECT  usu.nome nome_usu,
                hist.id id,
                hist.data_hora data_hora, 
                hist.competencia competencia, 
                hist.status status, 
                hist.excluido excluido,
                hist.tipo_folha tipo_folha
                            FROM db_adm_rh.t_rh_hist_holerite_upload hist
                            inner join db_adm.t_user usu
                            on usu.id = hist.id_user
                            where hist.id_emp = {$_SESSION["vs_id_empresa"]}; ";

    // var_dump($v_sql);

    $result = pg_query($conn, $v_sql);

    $v_dados = array();


    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "id" => $row["id"],
            "nome_usu" => $row["nome_usu"],
            "status" => $row["status"],
            "tipo_folha" => $row["tipo_folha"],
            "data_hora" => $row["data_hora"],
            "excluido" => $row["excluido"],
            "competencia" => $row["competencia"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// SELECIONANDO REGISTRO
if ($v_acao == "EV_SELECT") {

    $v_sql = "SELECT  usu.nome nome_usu,
    hist.id id,
    hist.data_hora data_hora, 
    hist.competencia competencia, 
    hist.status status, 
    hist.excluido excluido,
    hist.tipo_folha tipo_folha
                FROM db_adm_rh.t_rh_hist_holerite_upload hist
                inner join db_adm.t_user usu
                    on usu.id = hist.id_user
                    where hist.id_emp = {$_SESSION["vs_id_empresa"]};";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "id" => $row["id"],
            "nome_usu" => $row["nome_usu"],
            "data_hora" => $row["data_hora"],
            "tipo_folha" => $row["tipo_folha"],
            "excluido" => $row["excluido"],
            "status" => $row["status"],
            "competencia" => $row["competencia"]

        );
    }
    // var_dump($v_dados);
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// EXCLUINDO REGISTRO DE IMPORTAÇÃO
if ($v_acao == "EV_EXCLUIR") {

    $v_tipo_folha = addslashes($_POST["v_tipo_folha"]);
    $v_competencia = addslashes($_POST["v_competencia"]);


    $v_sql = "DELETE from db_adm_rh.t_rh_holerite
                    where tipo_folha = {$v_tipo_folha} 
                        and id_empresa = {$_SESSION["vs_id_empresa"]}
                        and competencia = '{$v_competencia}'";

    // var_dump($v_sql);
    $json_msg_ret = "N";

    if (pg_query($conn, $v_sql)) {
        $json_msg_ret = "S";
    }

    if ($json_msg_ret = "S") {

        $v_sql = "DELETE from db_adm_rh.t_rh_holerite_bases
                    where tipo_folha = {$v_tipo_folha} 
                       and id_empresa = {$_SESSION["vs_id_empresa"]}
                        and competencia = '{$v_competencia}';";
        // var_dump($v_sql);

        if (pg_query($conn, $v_sql)) {

            $v_sql = "UPDATE  db_adm_rh.t_rh_hist_holerite_upload
                        SET excluido = 'S' 
                          WHERE competencia = '{$v_competencia}' 
                            and tipo_folha = {$v_tipo_folha} 
                            and id_emp = {$_SESSION["vs_id_empresa"]};";
            // var_dump($v_sql);
            // die;
            pg_query($conn, $v_sql);


            // GRAVANDO O LOG DE IMPORTAÇÃO

            $timeZone = new DateTimeZone('America/Sao_Paulo');
            $v_data = new DateTime('now', $timeZone);
            $v_data = $v_data->format('Y-m-d H:i:s');

            $v_sql = "INSERT INTO db_adm_rh.t_rh_hist_holerite_upload 
                        (id_user, data_hora, competencia, status, tipo_folha, excluido, id_emp)
                        values ({$_SESSION["vs_id"]}, '{$v_data}', '{$v_competencia}', '0', {$v_tipo_folha}, 'S', {$_SESSION["vs_id_empresa"]})";
            $result = pg_query($conn, $v_sql);

            // GRAVANDO O LOG DE EXCLUSÃO
            $v_sql = "INSERT INTO db_adm_rh.t_log
             (id_user, data_hora, id_empresa, id_processo, descricao)
             VALUES({$_SESSION["vs_id"]}, '$v_data', {$_SESSION["vs_id_empresa"]}, 6, 'Exclusão de holerites da competerncia: {$v_competencia} e tipo folha: {$v_tipo_folha}.')
             ON CONFLICT DO NOTHING;";
            $result = pg_query($conn, $v_sql);

            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro excluído com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível realizar a exclusão da competência, favor verificar e tentar novamente."}';
        }
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível realizar a exclusão da competência, favor verificar e tentar novamente."}';
    }



    $v_json = json_encode($json_msg);
    echo $v_json;
}
