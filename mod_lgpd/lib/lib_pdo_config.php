<?php
header("Content-Type: application/json; charset=utf-8");
include_once( "../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);

// GERANDO LISTA DE EMPRESAS
if ($v_acao == "CARREGA_TAB") {

    // GERANDO A LISTA
    $v_sql = "select t_lgpd_dpo_config.id, t_lgpd_dpo_config.campo, t_lgpd_config_tipos.nome as tipo, t_lgpd_config_categorias.nome as categoria, t_lgpd_config_acoes.nome as acao from db_adm_lgpd.t_lgpd_dpo_config 
    join db_adm_lgpd.t_lgpd_config_tipos on db_adm_lgpd.t_lgpd_dpo_config.tipo = db_adm_lgpd.t_lgpd_config_tipos.id 
    join db_adm_lgpd.t_lgpd_config_categorias on db_adm_lgpd.t_lgpd_dpo_config.categoria = db_adm_lgpd.t_lgpd_config_categorias.id 
    join db_adm_lgpd.t_lgpd_config_acoes on db_adm_lgpd.t_lgpd_dpo_config.acao = db_adm_lgpd.t_lgpd_config_acoes.id 
    where t_lgpd_dpo_config.id_empresa = " . $_SESSION["vs_id_empresa"] . " 
    order by t_lgpd_dpo_config.campo";

    $result = pg_query($conn, $v_sql);
    $v_dados[] = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("id" => $row["id"], "campo" => $row["campo"], "tipo" => $row["tipo"], "categoria" => $row["categoria"], "acao" => $row["acao"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "CARREGA_COMBOS") {

    $v_sql = "select cb, valor, lab from (
        select 'acoes' as cb, id as valor, nome as lab from db_adm_lgpd.t_lgpd_config_acoes 
        union 
        select 'categorias' as cb, id as valor, nome as lab from db_adm_lgpd.t_lgpd_config_categorias where id_empresa = " . $_SESSION["vs_id_empresa"] . " 
        union 
        select 'req_legais' as cb, id as valor, nome as lab from db_adm_lgpd.t_lgpd_config_req_legais where id_empresa = " . $_SESSION["vs_id_empresa"] . " 
        union 
        select 'tipos' as cb, id as valor, nome as lab from db_adm_lgpd.t_lgpd_config_tipos where id_empresa = " . $_SESSION["vs_id_empresa"] . " ) tab_temp 
        order by cb, valor";

    $result = pg_query($conn, $v_sql);
    $v_dados[] = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("cb" => $row["cb"], "valor" => $row["valor"], "lab" => $row["lab"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_CAMPOS") {

    // GERANDO A LISTA
    $v_sql = "select t_lgpd_dpo_config.id, t_lgpd_dpo_config.campo, t_lgpd_config_tipos.nome as tipo, t_lgpd_config_categorias.nome as categoria, t_lgpd_config_acoes.nome as acao from db_adm_lgpd.t_lgpd_dpo_config 
    join db_adm_lgpd.t_lgpd_config_tipos on db_adm_lgpd.t_lgpd_dpo_config.tipo = db_adm_lgpd.t_lgpd_config_tipos.id 
    join db_adm_lgpd.t_lgpd_config_categorias on db_adm_lgpd.t_lgpd_dpo_config.categoria = db_adm_lgpd.t_lgpd_config_categorias.id 
    join db_adm_lgpd.t_lgpd_config_acoes on db_adm_lgpd.t_lgpd_dpo_config.acao = db_adm_lgpd.t_lgpd_config_acoes.id 
    where t_lgpd_dpo_config.id_empresa = " . $_SESSION["vs_id_empresa"] . " 
    order by t_lgpd_dpo_config.campo";

    $result = pg_query($conn, $v_sql);
    $v_dados[] = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("id" => $row["id"], "campo" => $row["campo"], "tipo" => $row["tipo"], "categoria" => $row["categoria"], "acao" => $row["acao"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}



// SELECIONANDO REGISTRO
if ($v_acao == "EV_SELECT") {

    $v_id = addslashes($_POST["v_id"]);

    $v_sql = "select id, campo, tipo, categoria, req_legal, acao, finalidade from db_adm_lgpd.t_lgpd_dpo_config WHERE Id = " . $v_id . " and id_empresa = " . $_SESSION["vs_id_empresa"];
    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "id" => $row["id"],
            "campo" => $row["campo"],
            "tipo" => $row["tipo"],
            "categoria" => $row["categoria"],
            "req_legal" => $row["req_legal"],
            "acao" => $row["acao"],
            "finalidade" => $row["finalidade"]
        );
    }
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// SELECIONANDO REGISTRO
if ($v_acao == "EV_SALVAR") {

    $v_conf_id = addslashes($_POST["v_conf_id"]);
    $v_conf_campo = addslashes($_POST["v_conf_campo"]);
    $v_conf_tipo = addslashes($_POST["v_conf_tipo"]);
    $v_conf_categoria = addslashes($_POST["v_conf_categoria"]);
    $v_conf_req_legal = addslashes($_POST["v_conf_req_legal"]);
    $v_conf_acao = addslashes($_POST["v_conf_acao"]);
    $v_conf_finalidade = addslashes($_POST["v_conf_finalidade"]);

    $v_sql = "UPDATE db_adm_lgpd.t_lgpd_dpo_config SET 
    campo = '".$v_conf_campo."', 
    tipo = ".$v_conf_tipo.", 
    categoria = ".$v_conf_categoria.", 
    req_legal = ".$v_conf_req_legal.", 
    acao = ".$v_conf_acao.", 
    finalidade = '".$v_conf_finalidade."' 
    where id = ".$v_conf_id." and id_empresa = " . $_SESSION["vs_id_empresa"];

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "CARREGA_CAMPO_EDIT") {

    $v_campo_edit = addslashes($_POST["v_campo_edit"]);

    if($v_campo_edit == "c_conf_tipo"){
        $v_sql = "select t_lgpd_config_tipos.id, t_lgpd_config_tipos.nome, case when COUNT(t_lgpd_dpo_config.tipo) > 0 then 'S' else 'N' end as disabled from db_adm_lgpd.t_lgpd_config_tipos
        left join db_adm_lgpd.t_lgpd_dpo_config on db_adm_lgpd.t_lgpd_config_tipos.id = db_adm_lgpd.t_lgpd_dpo_config.tipo 
        and db_adm_lgpd.t_lgpd_dpo_config.id_empresa = " . $_SESSION["vs_id_empresa"] . " 
        where t_lgpd_dpo_config.id_empresa = " . $_SESSION["vs_id_empresa"] . " 
        group by t_lgpd_config_tipos.id, t_lgpd_config_tipos.nome 
        order by t_lgpd_config_tipos.nome";
    } else if($v_campo_edit == "c_conf_categoria"){
        $v_sql = "select t_lgpd_config_categorias.id, t_lgpd_config_categorias.nome, case when COUNT(t_lgpd_dpo_config.tipo) > 0 then 'S' else 'N' end as disabled from db_adm_lgpd.t_lgpd_config_categorias
        left join db_adm_lgpd.t_lgpd_dpo_config on db_adm_lgpd.t_lgpd_config_categorias.id = db_adm_lgpd.t_lgpd_dpo_config.categoria 
        and db_adm_lgpd.t_lgpd_dpo_config.id_empresa = " . $_SESSION["vs_id_empresa"] . " 
        where t_lgpd_dpo_config.id_empresa = " . $_SESSION["vs_id_empresa"] . " 
        group by t_lgpd_config_categorias.id, t_lgpd_config_categorias.nome 
        order by t_lgpd_config_categorias.nome";
    } else {
        $v_sql = "select t_lgpd_config_req_legais.id, t_lgpd_config_req_legais.nome, case when COUNT(t_lgpd_dpo_config.tipo) > 0 then 'S' else 'N' end as disabled from db_adm_lgpd.t_lgpd_config_req_legais
        left join db_adm_lgpd.t_lgpd_dpo_config on db_adm_lgpd.t_lgpd_config_req_legais.id = db_adm_lgpd.t_lgpd_dpo_config.req_legal 
        and db_adm_lgpd.t_lgpd_dpo_config.id_empresa = " . $_SESSION["vs_id_empresa"] . " 
        where t_lgpd_dpo_config.id_empresa = " . $_SESSION["vs_id_empresa"] . " 
        group by t_lgpd_config_req_legais.id, t_lgpd_config_req_legais.nome 
        order by t_lgpd_config_req_legais.nome";
    }

    $result = pg_query($conn, $v_sql);
    $v_dados[] = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("id" => $row["id"], "campo" => $row["nome"], "disabled" => $row["disabled"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "ADICIONA_CAMPO_EDIT") {

    $v_novo_id = addslashes($_POST["v_novo_id"]);
    $v_campo = addslashes($_POST["v_campo"]);
    $v_valor = addslashes($_POST["v_valor"]);
    $v_sql = "";

    if($v_campo == "c_conf_tipo"){
        $v_sql = "insert into db_adm_lgpd.t_lgpd_config_tipos (id, id_empresa, nome) values(" . $v_novo_id . ", " . $_SESSION["vs_id_empresa"] . ", '" . $v_valor . "')";
    } else if($v_campo == "c_conf_categoria"){
        $v_sql = "insert into db_adm_lgpd.t_lgpd_config_categorias (id, id_empresa, nome) values(" . $v_novo_id . ", " . $_SESSION["vs_id_empresa"] . ", '" . $v_valor . "')";
    }else{
        $v_sql = "insert into db_adm_lgpd.t_lgpd_config_req_legais (id, id_empresa, nome) values(" . $v_novo_id . ", " . $_SESSION["vs_id_empresa"] . ", '" . $v_valor . "')";
    }

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "DEL_CAMPO_EDIT") {

    $v_id = addslashes($_POST["v_id"]);
    $v_campo = addslashes($_POST["v_campo"]);
    $v_sql = "";

    if($v_campo == "c_conf_tipo"){
        $v_sql = "delete from db_adm_lgpd.t_lgpd_config_tipos where id = " . $v_id . " and id_empresa = " . $_SESSION["vs_id_empresa"];
    } else if($v_campo == "c_conf_categoria"){
        $v_sql = "delete from db_adm_lgpd.t_lgpd_config_categorias where id = " . $v_id . " and id_empresa = " . $_SESSION["vs_id_empresa"];
    }else{
        $v_sql = "delete from db_adm_lgpd.t_lgpd_config_req_legais where id = " . $v_id . " and id_empresa = " . $_SESSION["vs_id_empresa"];
    }

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro deletado com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}