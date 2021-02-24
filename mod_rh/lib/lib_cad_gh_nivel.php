<?php
header("Content-Type: application/json; charset=utf-8");
include_once( "../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// GERANDO LISTA DE NIVEIS PARA GH
if ($v_acao == "LISTAR") {

    $v_grupo = addslashes($_POST["v_grupo"]);

    if (strpos($_SESSION["vs_array_access"], "T0021") > 0){

        $v_pos = strpos($_SESSION["vs_array_access"], "T0021");
        $v_nivel_gh_ler = substr($_SESSION["vs_array_access"],$v_pos+8,1);
        $v_nivel_gh_criar = substr($_SESSION["vs_array_access"],$v_pos+12,1);
        $v_nivel_gh_gravar = substr($_SESSION["vs_array_access"],$v_pos+16,1);
        $v_nivel_gh_excluir = substr($_SESSION["vs_array_access"],$v_pos+20,1);
        // var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

    }

    // GERANDO A LISTA
    $v_sql = "SELECT Nivel, Descricao FROM db_adm.t_rh_nivel_gh where id_grupo = {$v_grupo} ORDER BY nivel";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    while($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Nivel" => $row["nivel"],
            "Descricao" => $row["descricao"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}

// SELECIONANDO REGISTRO
if ($v_acao == "EV_SELECT") {

    $v_id = addslashes($_POST["v_id"]);

    $v_sql = "SELECT * FROM db_adm.t_gh00_config WHERE Id = ".$v_id;
    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while($row = pg_fetch_assoc($result)) {
        $v_dados[]=array("Id" => $row["id"], 
            "Nome_de" => $row["nome_de"], 
            "Nome_para" => $row["nome_para"], 
            "Visivel" => $row["visivel"]);
    }
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}

// SALVANDO REGISTRO
if ($v_acao == "EV_SALVAR") {

    $v_nivel = addslashes($_POST["v_nivel"]);
    $v_descricao = addslashes($_POST["v_descricao"]);
    $v_grupo = addslashes($_POST["v_grupo"]);

    $v_sql = "UPDATE db_adm.t_rh_nivel_gh SET \n".
    "nivel = ".$v_nivel.", \n".
    "descricao = '".$v_descricao."' \n".
    "WHERE nivel = ".$v_nivel." and id_grupo = {$v_grupo}";

    if (pg_query($conn, $v_sql)){
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}

if($v_acao == 'EV_NOVO'){

    $v_nivel = addslashes($_POST["v_nivel"]);
    $v_descricao = addslashes($_POST["v_descricao"]);
    $v_grupo = addslashes($_POST["v_grupo"]);

    $v_sql = "SELECT nivel from db_adm.t_rh_nivel_gh where nivel = {$v_nivel} and id_grupo = {$v_grupo}";

    if(pg_fetch_assoc(pg_query($conn, $v_sql))){
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Nível já cadastrado"}';
    } else {
        $v_sql = "INSERT INTO db_adm.t_rh_nivel_gh (nivel, descricao, id_grupo) values ({$v_nivel}, '{$v_descricao}', {$v_grupo})";
        pg_query($conn, $v_sql);
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
    }
    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

if($v_acao == 'EV_CARREGA_GRUPO'){
    
    $v_sql = "select * from db_adm.t_rh_grupo_gh grupo
    join db_adm.t_rh_adm_gh adm on adm.id_grupo = grupo.id 
    where adm.id_usuario = {$_SESSION["vs_id"]} order by nome";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while($row = pg_fetch_assoc($result)) {
        $v_dados[]=array( 
            "id_grupo" => $row["id_grupo"], 
            "nome" => $row["nome"]
        );
    }
    
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}