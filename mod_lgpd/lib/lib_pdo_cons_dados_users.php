<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);





// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_USUARIOS") {

    // GERANDO A LISTA
    $v_sql = "select us.id, us.nome from db_adm.t_user us order by nome";
    // $_SESSION["database_adm"] = "S";
    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    while($row = pg_fetch_assoc($result)) {
        $v_dados[]=array(
            "Id" => $row["id"],
            "Nome" => $row["nome"],
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_DADOS") {

    $v_id_user = addslashes($_POST["v_id_user"]);

    // CONSTRUINDO O SELECT
    $v_sql = "select t_lgpd_dpo_config.campo, t_lgpd_dpo_config_campos.campo_field, t_lgpd_dpo_config_campos.campo_label, t_lgpd_dpo_config.finalidade, t_lgpd_config_req_legais.nome as req_legal, case when t_lgpd_dpo_config.acao = 1 then 'S' else 'N' end as campo_liberado from db_adm_lgpd.t_lgpd_dpo_config_campos
    join db_adm_lgpd.t_lgpd_dpo_config on db_adm_lgpd.t_lgpd_dpo_config_campos.campo_id = db_adm_lgpd.t_lgpd_dpo_config.campo_id 
    and db_adm_lgpd.t_lgpd_dpo_config.id_empresa = " . $_SESSION["vs_id_empresa"] . " 
    join db_adm_lgpd.t_lgpd_config_req_legais on db_adm_lgpd.t_lgpd_dpo_config.req_legal = db_adm_lgpd.t_lgpd_config_req_legais.id
    and db_adm_lgpd.t_lgpd_config_req_legais.id_empresa = " . $_SESSION["vs_id_empresa"] . " 
    where t_lgpd_dpo_config.id_empresa = " . $_SESSION["vs_id_empresa"] . " and t_lgpd_dpo_config_campos.campo_tabela = 't_rh_colaborador' 
    order by campo_liberado desc";
    $result_cons = pg_query($conn, $v_sql);

    $v_sql = "select tab_temp.valor, tab_temp.campo, campo_field, tab_temp.campo_label, tab_temp.finalidade, tab_temp.req_legal, tab_temp.liberado from (";
    while ($row_cons = pg_fetch_assoc($result_cons)) {
        $v_sql .= "(select ";
        $v_sql .= "cast(" . $row_cons["campo_field"] . " as varchar) as valor";
        $v_sql .= ", '" . $row_cons["campo"] . "' as campo";
        $v_sql .= ", '" . $row_cons["campo_field"] . "' as campo_field";
        $v_sql .= ", '" . $row_cons["campo_label"] . "' as campo_label";
        $v_sql .= ", '" . $row_cons["finalidade"] . "' as finalidade";
        $v_sql .= ", '" . $row_cons["req_legal"] . "' as req_legal";
        $v_sql .= ", '" . $row_cons["campo_liberado"] . "' as liberado";
        $v_sql .= " from db_adm_rh.t_rh_colaborador where id_usuario = " . $v_id_user . ") union \n";
    }
    $v_sql = substr($v_sql, 0, -8) . ") tab_temp where valor <> '' and valor <> '0' and valor <> ' ' order by liberado desc, campo, campo_label asc";

    // GERANDO CONSULTA COM TODOS OS CAMPOS E DADOS
    $result = pg_query($conn, $v_sql);

    $v_dados[] = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("valor" => $row["valor"], "campo" => $row["campo"], "campo_field" => $row["campo_field"], "campo_label" => $row["campo_label"], "finalidade" => $row["finalidade"], "req_legal" => $row["req_legal"], "liberado" => $row["liberado"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "SOL_EXCLUSAO") {

    $v_lista_ids = addslashes($_POST["v_lista_ids"]);
    $v_just = addslashes($_POST["v_just"]);

    $v_sql = "insert into db_adm_lgpd.t_lgpd_sol_exclusao_dados (id_user, id_empresa, id_campo, justificativa) values\n";

    $array = explode('|', $v_lista_ids);
    for ($i = 0; $i < count($array); $i++) {
        $v_sql .= "(" . $_SESSION["vs_id"] . ", " . $_SESSION["vs_id_empresa"] . ", " . $array[$i] . ", '" . $v_just . "'),";
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
if ($v_acao == "CANCELAR_SOL") {

    $v_id_sol = addslashes($_POST["v_id_sol"]);

    $v_sql = "select id from db_adm_lgpd.t_lgpd_sol_exclusao_dados where acao_executada = 'N' and id = " . $v_id_sol;
    $result = pg_query($conn, $v_sql);
    if (pg_num_rows($result) > 0) {

        $v_sql = "update db_adm_lgpd.t_lgpd_sol_exclusao_dados set 
                  acao_executada = 'C', 
                  id_user_acao = " . $_SESSION["vs_id"] . ", 
                  data_hora_acao = CURRENT_TIMESTAMP 
                  where id = " . $v_id_sol;
        pg_query($conn, $v_sql);

        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Exclusão cancelada com sucesso, e está na fila de execução de tarefas do DPO."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível realizar a sua solicitação, pois o dado já foi removido."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}
