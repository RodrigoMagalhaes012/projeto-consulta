<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_BANCOS") {

    if (strpos($_SESSION["vs_array_access"], "T0017") > 0){

        $v_pos = strpos($_SESSION["vs_array_access"], "T0017");
        $v_bancos_ler = substr($_SESSION["vs_array_access"],$v_pos+8,1);
        $v_bancos_criar = substr($_SESSION["vs_array_access"],$v_pos+12,1);
        $v_bancos_gravar = substr($_SESSION["vs_array_access"],$v_pos+16,1);
        $v_bancos_excluir = substr($_SESSION["vs_array_access"],$v_pos+20,1);
        // var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

    }

    $v_tab_campo = addslashes($_POST["v_tab_campo"]);
    $v_tab_ordem = addslashes($_POST["v_tab_ordem"]);
    $v_tab_busca_campo = explode("|", addslashes($_POST["v_tab_busca_campo"]))[0];
    $v_tab_busca_campo_tipo = explode("|", addslashes($_POST["v_tab_busca_campo"]))[1];
    $v_tab_busca_texto = addslashes($_POST["v_tab_busca_texto"]);
    $v_tab_sql_limit_in = addslashes($_POST["v_tab_sql_limit_in"]);
    $v_limit = addslashes($_POST["v_limit"]);
    $v_linhas = 0;

    // CONSTRUINDO OS FILTROS
    $v_filtro = "";
    if (!empty($v_tab_busca_texto)) {

        if ($v_tab_busca_campo_tipo == "txt") {
            $v_filtro = "WHERE " . $v_tab_busca_campo . " like '%" . $v_tab_busca_texto . "%'";
        } else {
            $v_filtro = "WHERE " . $v_tab_busca_campo . " = " . $v_tab_busca_texto;
        }
       // var_dump("$v_filtro");
    }

    // CONTANDO REGISTROS PARA GERAR OS BOTÕES DE NAVEGAÇÃO
    $v_sql = "SELECT count(codigo) as linhas from db_adm.t_bancos " . $v_filtro;

    //var_dump($v_sql);
    if ($result = pg_query($conn, $v_sql)) {
        $row = pg_fetch_assoc($result);
        $v_linhas = $row["linhas"];
    }

    // GERANDO A LISTA
    $v_sql = "SELECT codigo, nome_extenso " .
        "FROM db_adm.t_bancos " . $v_filtro . " ORDER BY " . $v_tab_campo . " " . $v_tab_ordem . " OFFSET " . $v_tab_sql_limit_in . " LIMIT " . $v_limit;
    // var_dump($v_sql);
    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    $v_dados[] = array("linhas" => $v_linhas);
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("codigo" => $row["codigo"], "nome_extenso" => $row["nome_extenso"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// SELECIONANDO REGISTRO
if ($v_acao == "EV_SELECT") {

    $v_sql = "SELECT codigo, nome_extenso " .
        "FROM db_adm.t_bancos WHERE codigo = ".$_POST["v_codigo"];
       
    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    if ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "codigo" => $row["codigo"],
            "nome_extenso" => $row["nome_extenso"]
            //"Descricao" => $row["descricao"]       

        );
    }
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// CADASTRANDO NOVO REGISTRO
if ($v_acao == "EV_NOVO") {

    $v_nome_banco = strtoupper(addslashes($_POST["v_nome_banco"]));
    $v_codigo = addslashes($_POST["v_codigo"]);
    

    $v_sql = "INSERT INTO db_adm.t_bancos (codigo, nome_extenso) " . "\n" .
        "VALUES($v_codigo,'" . $v_nome_banco . "')";
   //var_dump($v_sql);
    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Cadastro realizado com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}



// SALVANDO REGISTRO
if ($v_acao == "EV_SALVAR") {

    $v_codigo = addslashes($_POST["v_codigo"]);
    $v_nome_banco = addslashes($_POST["v_nome_banco"]);
    $v_codigo = addslashes($_POST["v_codigo"]);
    

    $v_sql = "UPDATE db_adm.t_bancos SET \n" .
        "nome_extenso = '" . $v_nome_banco . "', \n" .
        "codigo = '" . $v_codigo . "', \n" .
        "WHERE codigo = " . $v_codigo;

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}



// EXCLUINDO REGISTRO
if ($v_acao == "EV_EXCLUIR") {

    $v_codigo = addslashes($_POST["v_codigo"]);

    $v_sql = "DELETE FROM db_adm.t_bancos WHERE codigo =  "."  ' $v_codigo ' "."" ;
   // var_dump($v_sql);
    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro excluído com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

