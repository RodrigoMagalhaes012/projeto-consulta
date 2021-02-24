<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_MODULOS") {

    if (strpos($_SESSION["vs_array_access"], "T0008") > 0) {

        $v_pos = strpos($_SESSION["vs_array_access"], "T0008");
        $v_mod_perm_ler = substr($_SESSION["vs_array_access"], $v_pos + 8, 1);
        $v_mod_perm_criar = substr($_SESSION["vs_array_access"], $v_pos + 12, 1);
        $v_mod_perm_gravar = substr($_SESSION["vs_array_access"], $v_pos + 16, 1);
        $v_mod_perm_excluir = substr($_SESSION["vs_array_access"], $v_pos + 20, 1);
        // var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

    }

    // CONTANDO REGISTROS PARA GERAR OS BOTÕES DE NAVEGAÇÃO
    // $v_sql = "SELECT count(id) as linhas from db_adm.t_access_emp_02_cad_grupos ";
    // $_SESSION["database_adm"] = "S";
    // if ($result = pg_query($conn, $v_sql)) {
    //     $row = pg_fetch_assoc($result);
    //     $v_linhas = $row["linhas"];
    // }

    // GERANDO A LISTA
    $v_sql = "SELECT Id, Nome, Descricao, ativo FROM db_adm.t_access_emp_02_cad_grupos "; //. $v_filtro . " ORDER BY " . $v_tab_campo . " " . $v_tab_ordem . " OFFSET " . $v_tab_sql_limit_in . " LIMIT " . $v_limit;
    $_SESSION["database_adm"] = "S";
    $result = pg_query($conn, $v_sql);

    //  var_dump($v_sql);
    $v_grupos = array();

    // $v_dados[] = array("linhas" => $v_linhas);
    while ($row = pg_fetch_assoc($result)) {
        $v_grupos[] = array(
            "Id" => $row["id"],
            "Nome" => $row["nome"],
            "Descricao" => $row["descricao"],
            "ativo" => $row["ativo"]
        );
    }

    // $v_sql = "select * from db_adm.t_access_telas_04_cad_modulos modulos";

    // $result = pg_query($conn, $v_sql);

    // //  var_dump($v_sql);
    // $v_modulos = array();

    // // $v_dados[] = array("linhas" => $v_linhas);
    // while ($row = pg_fetch_assoc($result)) {
    //     $v_modulos[] = array(
    //         "Id" => $row["id"],
    //         "Nome" => $row["nome"]
    //     );
    // }

    $v_dados = array(
        "grupos" => $v_grupos,
        // "modulos" => $v_modulos
    );

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// SELECIONANDO REGISTRO
if ($v_acao == "EV_SELECT") {

    $v_id = addslashes($_POST["v_id"]);

    $v_sql = "SELECT Id,Nome,Descricao, ativo FROM db_adm.t_access_emp_02_cad_grupos WHERE Id = " . $v_id;
    $_SESSION["database_adm"] = "S";
    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Id" => $row["id"],
            "Nome" => $row["nome"],
            "Descricao" => $row["descricao"],
            "Ativo" => $row["ativo"]
        );
    }
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// SELECIONANDO REGISTRO
if ($v_acao == "EV_SELECT_EMPRESA") {


    $v_sql = "SELECT Id, Nome FROM db_adm.t_empresas ORDER BY Nome";
    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("Id" => $row["id"], "nome" => $row["nome"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}

// CADASTRANDO NOVO REGISTRO
if ($v_acao == "EV_NOVO") {

    $v_descricao = addslashes($_POST["v_descricao"]);
    $v_nome = strtoupper(addslashes($_POST["v_nome"]));
    $v_status_grupo = strtoupper(addslashes($_POST["v_status_grupo"]));

    $v_sql = "INSERT INTO db_adm.t_access_emp_02_cad_grupos (Nome, Descricao, ativo)" . "\n" .
        "VALUES('" . $v_nome . "','" . $v_descricao . "','" . $v_status_grupo . "')";
    $_SESSION["database_adm"] = "S";

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

    $v_id = addslashes($_POST["v_id"]);
    $v_descricao = strtoupper(addslashes($_POST["v_descricao"]));
    $v_nome = strtoupper(addslashes($_POST["v_nome"]));
    $v_status_grupo = strtoupper(addslashes($_POST["v_status_grupo"]));

    $v_sql = "UPDATE db_adm.t_access_emp_02_cad_grupos SET \n" .
        "Descricao = '" . $v_descricao . "', \n" .
        "Nome = '" . $v_nome . "' ,\n" .
        "ativo = '" . $v_status_grupo . "' \n" .
        "WHERE Id = " . $v_id;
    $_SESSION["database_adm"] = "S";
    // var_dump($v_sql);
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

    $v_id = addslashes($_POST["v_id"]);

    $v_sql = "DELETE FROM t_access_emp_02_cad_grupos WHERE Id = " . $v_id;
    $_SESSION["database_adm"] = "S";

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro excluído com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

if($v_acao == 'EV_EMPRESAS_LIBERADAS'){

    $v_id = addslashes($_POST["v_id"]);

    $v_sql = "select emp.id, emp.nome from db_adm.t_empresas emp order by nome";

    $v_empresas = array();

    $result = pg_query($conn, $v_sql);

    while ($row = pg_fetch_assoc($result)) {
        $v_empresas[] = array(
            "Id" => $row["id"],
            "nome" => $row["nome"]
        );
    }

    $v_sql = "select gp.id_emp from db_adm.t_access_emp_01_grupo_emp gp where gp.id_grupo_emp = {$v_id}";

    $v_grupos = array();

    $result = pg_query($conn, $v_sql);

    while($row = pg_fetch_assoc($result)){
        $v_grupos[] = array(
            "id_emp" => $row["id_emp"]
        );
    }

    $v_dados = array(
        "empresas" => $v_empresas,
        "grupos" => $v_grupos
    );

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}

if($v_acao == 'EV_SALVA_EMP_GRUPO'){

    $v_id_grupo = addslashes($_POST["v_id_grupo"]);
    $v_empresas = $_POST["v_empresas"];

    $v_sql = "delete from db_adm.t_access_emp_01_grupo_emp where id_grupo_emp = {$v_id_grupo}";

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Empresas salvas com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    if(gettype($v_empresas) == 'array'){
        $insert_line = 'insert into db_adm.t_access_emp_01_grupo_emp (id_emp, id_grupo_emp) values ';
        foreach ($v_empresas as $empresas) {
            $insert_line .= "({$empresas}, {$v_id_grupo}),";
        }
    
        if (pg_query($conn, substr($insert_line, 0, -1))) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Empresas salvas com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}