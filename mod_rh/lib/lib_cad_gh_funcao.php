<?php
header("Content-Type: application/json; charset=utf-8");
include_once( "../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// GERANDO LISTA DE FUNÇOES PARA GH
if ($v_acao == "LISTAR") {

    if (strpos($_SESSION["vs_array_access"], "T0020") > 0){

        $v_pos = strpos($_SESSION["vs_array_access"], "T0020");
        $v_func_gh_ler = substr($_SESSION["vs_array_access"],$v_pos+8,1);
        $v_func_gh_criar = substr($_SESSION["vs_array_access"],$v_pos+12,1);
        $v_func_gh_gravar = substr($_SESSION["vs_array_access"],$v_pos+16,1);
        $v_func_gh_excluir = substr($_SESSION["vs_array_access"],$v_pos+20,1);
        // var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

    }

    // GERANDO A LISTA DE FUNÇÕES
    $v_sql = "select trlg.id, trlg.descricao, trng.descricao desc_nivel, trng.nivel from db_emp_{$_SESSION["vs_db_empresa"]}.t_rh_funcao_gh trlg
    inner join db_emp_{$_SESSION["vs_db_empresa"]}.t_rh_nivel_gh trng on trng.nivel = trlg.id_nivel order by nivel";

    $result = pg_query($conn, $v_sql);

    $v_funcoes = array();

    while($row = pg_fetch_assoc($result)) {
        $v_funcoes[] = array(
            "Id" => $row["id"],
            "Desc_nivel" => $row["desc_nivel"],
            "Nivel" => $row["nivel"],
            "Descricao" => $row["descricao"]
        );
    }

    //GERANDO A LISTA DE NIVEIS
    $v_sql = "SELECT Nivel, Descricao FROM db_emp_" . $_SESSION["vs_db_empresa"] . ".t_rh_nivel_gh ORDER BY nivel";
    $result = pg_query($conn, $v_sql);

    $v_niveis = array();

    while($row = pg_fetch_assoc($result)) {
        $v_niveis[] = array(
            "Nivel" => $row["nivel"],
            "Descricao" => $row["descricao"]
        );
    }

    $v_dados = array(
        "funcoes" => $v_funcoes,
        "niveis" => $v_niveis
    );

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}



// SELECIONANDO REGISTRO
if ($v_acao == "EV_SELECT") {

    $v_id = addslashes($_POST["v_id"]);

    $v_sql = "SELECT * FROM db_emp_" . $_SESSION["vs_db_empresa"] . ".t_gh00_config WHERE Id = ".$v_id;
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
    $v_id = addslashes($_POST["v_id"]);

    $v_sql = "UPDATE db_emp_" . $_SESSION["vs_db_empresa"] . ".t_rh_funcao_gh SET \n".
    "id_nivel = ".$v_nivel.", \n".
    "descricao = '".$v_descricao."' \n".
    "WHERE id = ".$v_id;

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
    $v_id = addslashes($_POST["v_id"]);

    $v_sql = "SELECT descricao from db_emp_{$_SESSION["vs_db_empresa"]}.t_rh_funcao_gh where descricao = '{$v_descricao}' ";

    if(pg_fetch_assoc(pg_query($conn, $v_sql))){
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Descrição já cadastrada"}';
    } else {
        $v_sql = "INSERT INTO db_emp_{$_SESSION["vs_db_empresa"]}.t_rh_funcao_gh (id_nivel, descricao) values ({$v_nivel}, '{$v_descricao}')";
        pg_query($conn, $v_sql);
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
    }
    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}