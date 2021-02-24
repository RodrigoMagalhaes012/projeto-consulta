<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_DADOS") {

    $v_filtro = addslashes($_POST["v_filtro"]);

    // ###################################################### //
    // CARREGANDO ARRAY DE CAMPOS
    // ###################################################### //
    $v_array_campos = array();
    $v_sql = "select t_lgpd_sol_exclusao_dados.id_user, t_lgpd_dpo_config.campo 
    from db_adm_lgpd.t_lgpd_sol_exclusao_dados 
    join db_adm_lgpd.t_lgpd_dpo_config on db_adm_lgpd.t_lgpd_sol_exclusao_dados.id_campo = db_adm_lgpd.t_lgpd_dpo_config.id 
    where t_lgpd_sol_exclusao_dados.id_empresa = " . $_SESSION["vs_id_empresa"] . " and t_lgpd_sol_exclusao_dados.acao_executada = '" . $v_filtro . "' 
    order by t_lgpd_sol_exclusao_dados.id_user";
    $v_result_campos = pg_query($conn, $v_sql);

    $id_user = 0;
    $campos = "CAMPOS SELECIONADOS PARA ANONIMIZAÇÃO:";
    while ($v_row = pg_fetch_assoc($v_result_campos)) {
        if ($id_user == 0 || $id_user == $v_row["id_user"]) {
            $id_user = $v_row["id_user"];
            $campos .= "\n" . $v_row["campo"];
        } else {
            $v_array_campos[$id_user] = $campos;
            $campos = "CAMPOS SELECIONADOS PARA ANONIMIZAÇÃO:";
            $id_user = $v_row["id_user"];
            $campos .= "\n" . $v_row["campo"];
        }
    }
    $v_array_campos[$id_user] = $campos;
    $id_user = 0;
    $campos = "";





    $v_sql = "select 
    TO_CHAR(min(t_lgpd_sol_exclusao_dados.data_hora), 'DD-MM-YYYY HH24:II:SS') as data_hora, 
    t_lgpd_sol_exclusao_dados.id_user, 
    t_user.nome, 
    count(t_lgpd_sol_exclusao_dados.id) as tt_campos 
    from 
    db_adm_lgpd.t_lgpd_sol_exclusao_dados 
    join db_adm.t_user on db_adm_lgpd.t_lgpd_sol_exclusao_dados.id_user = db_adm.t_user.id 
    and db_adm_lgpd.t_lgpd_sol_exclusao_dados.id_empresa = " . $_SESSION["vs_id_empresa"] . " 
    where t_lgpd_sol_exclusao_dados.acao_executada = '" . $v_filtro . "' 
    group by t_lgpd_sol_exclusao_dados.id_user, t_user.nome";

    $result = pg_query($conn, $v_sql);
    $v_dados[] = array();
    $v_campos = "";
    while ($row = pg_fetch_assoc($result)) {
        $v_campos = $v_array_campos[(string)$row["id_user"]];
        $v_dados[] = array("data_hora" => $row["data_hora"], "id_user" => $row["id_user"], "nome" => $row["nome"], "tt_campos" => $row["tt_campos"], "campos" => $v_campos);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "SOL_EXCLUSAO") {

    $v_lista_ids = addslashes($_POST["v_lista_ids"]);
    $v_sql = "insert into db_adm_lgpd.t_lgpd_sol_exclusao_dados (id_user, id_empresa, id_campo) values\n";

    $array = explode('|', $v_lista_ids);
    for ($i = 0; $i < count($array); $i++) {
        $v_sql .= "(" . $_SESSION["vs_id"] . ", " . $_SESSION["vs_id_empresa"] . ", " . $array[$i] . "),";
    }

    $v_sql = substr($v_sql, 0, -1);

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"A sua solicitação foi cadastrada com sucesso, e está na fila de execução de tarefas do DPO."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível realizar a sua solicitação.  Tente mais tarde ou entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "EXECUTAR_EXCLUSOES") {

    $v_id_user = addslashes($_POST["v_id_user"]);

    $v_sql = "select t_lgpd_dpo_config_campos.campo_schema, t_lgpd_dpo_config_campos.campo_tabela, t_lgpd_dpo_config_campos.campo_tipo, t_lgpd_dpo_config_campos.campo_field 
    from db_adm_lgpd.t_lgpd_sol_exclusao_dados 
    join db_adm_lgpd.t_lgpd_dpo_config on db_adm_lgpd.t_lgpd_sol_exclusao_dados.id_campo = db_adm_lgpd.t_lgpd_dpo_config.id 
    join db_adm_lgpd.t_lgpd_dpo_config_campos on db_adm_lgpd.t_lgpd_dpo_config.campo_id = db_adm_lgpd.t_lgpd_dpo_config_campos.campo_id 
    where t_lgpd_sol_exclusao_dados.id_user = ".$v_id_user;

    $result = pg_query($conn, $v_sql);
    $v_valor_exec = "";
    while ($row = pg_fetch_assoc($result)) {

        if($row["campo_tipo"] == "NUMERO"){
            $v_valor_exec = 0;
        }

        if($row["campo_tipo"] == "DATA"){
            $v_valor_exec = "NULL";
        }

        if($row["campo_tipo"] == "TEXTO"){
            $v_valor_exec = "NULL";
        }

        $v_sql = "UPDATE ".$row["campo_schema"].".".$row["campo_tabela"]." set ".$row["campo_field"]." = ".$v_valor_exec." where id_usuario = " . $v_id_user . " and id_empresa = " . $_SESSION["vs_id_empresa"];
        pg_query($conn, $v_sql);

    }

    $v_sql = "update db_adm_lgpd.t_lgpd_sol_exclusao_dados set 
        acao_executada = 'S', 
        id_user_acao = " . $_SESSION["vs_id"] . ", 
        data_hora_acao = CURRENT_TIMESTAMP 
        where acao_executada = 'N' and id_user = " . $v_id_user . " and id_empresa = " . $_SESSION["vs_id_empresa"];
    pg_query($conn, $v_sql);

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"A solicitação de anonimização de dados foi executada com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível realizar a sua solicitação.  Tente mais tarde ou entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}
