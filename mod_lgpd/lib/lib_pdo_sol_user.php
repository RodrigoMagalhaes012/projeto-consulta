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

    // GERANDO A LISTA
    $v_sql = "select case when t_lgpd_dpo_config.acao = 1 then 'dados' else 'dados_bloq' end as tb, t_lgpd_dpo_config.id, t_lgpd_dpo_config.campo, t_lgpd_config_req_legais.nome as req_legal, t_lgpd_dpo_config.finalidade, tab_temp.id as id_sol, tab_temp.id_user, tab_temp.acao_executada, t_lgpd_dpo_config_campos.campo_tabela, t_lgpd_dpo_config_campos.campo_field, t_lgpd_dpo_config_campos.campo_tipo, campo_schema 
    from db_adm_lgpd.t_lgpd_dpo_config 
    join db_adm_lgpd.t_lgpd_dpo_config_campos on db_adm_lgpd.t_lgpd_dpo_config.campo_id = db_adm_lgpd.t_lgpd_dpo_config_campos.campo_id 
    join db_adm_lgpd.t_lgpd_config_req_legais on db_adm_lgpd.t_lgpd_dpo_config.req_legal = db_adm_lgpd.t_lgpd_config_req_legais.id 
    left join (select id, id_campo, id_user, acao_executada from db_adm_lgpd.t_lgpd_sol_exclusao_dados where id_user = " . $_SESSION["vs_id"] . " and id_empresa = " . $_SESSION["vs_id_empresa"] . " and acao_executada in ('S','N')) as tab_temp on db_adm_lgpd.t_lgpd_dpo_config.id = tab_temp.id_campo 
    where t_lgpd_dpo_config.id_empresa = " . $_SESSION["vs_id_empresa"] . " and t_lgpd_dpo_config.acao = " . $v_filtro . " order by t_lgpd_dpo_config.id";

    $result = pg_query($conn, $v_sql);
    $v_tabela = "";
    $v_tabela_sql = "";
    $v_id = "0";
    $v_dados[] = array();
    while ($row = pg_fetch_assoc($result)) {

        if ($v_id != $row["id"]) {
            $v_sql = "select " . $row["campo_field"] . " as valor from " . $row["campo_schema"] . "." . $row["campo_tabela"] . " where id_usuario = " . $_SESSION["vs_id"] . " and id_empresa = " . $_SESSION["vs_id_empresa"];

            $result_campo = pg_query($conn, $v_sql);
            if ($row_campo = pg_fetch_assoc($result_campo)) {
                if (($row["campo_tipo"] == "NUMERO" && $row_campo["valor"] > 0) ||
                    ($row["campo_tipo"] == "TEXTO" && strlen($row_campo["valor"]) > 0 && $row_campo["valor"] != "-") ||
                    ($row["campo_tipo"] == "DATA" && !empty($row_campo["valor"]))
                ) {
                    $v_dados[] = array("tb" => $row["tb"], "id" => $row["id"], "id_sol" => $row["id_sol"], "campo" => $row["campo"], "req_legal" => $row["req_legal"], "finalidade" => $row["finalidade"], "id_user" => $row["id_user"], "acao_executada" => $row["acao_executada"]);
                    $v_id = $row["id"];
                }
            }
        }
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
