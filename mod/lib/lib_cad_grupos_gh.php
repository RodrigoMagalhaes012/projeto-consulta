<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_GRUPOS") {

    if (strpos($_SESSION["vs_array_access"], "T0043") > 0) {

        $v_pos = strpos($_SESSION["vs_array_access"], "T0043");
        $v_mod_perm_ler = substr($_SESSION["vs_array_access"], $v_pos + 8, 1);
        $v_mod_perm_criar = substr($_SESSION["vs_array_access"], $v_pos + 12, 1);
        $v_mod_perm_gravar = substr($_SESSION["vs_array_access"], $v_pos + 16, 1);
        $v_mod_perm_excluir = substr($_SESSION["vs_array_access"], $v_pos + 20, 1);
        // var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

    }

    // GERANDO A LISTA
    $v_sql = "SELECT * FROM db_adm.t_rh_grupo_gh"; //. $v_filtro . " ORDER BY " . $v_tab_campo . " " . $v_tab_ordem . " OFFSET " . $v_tab_sql_limit_in . " LIMIT " . $v_limit;
    // $_SESSION["database_adm"] = "S";
    $result = pg_query($conn, $v_sql);

    //  var_dump($v_sql);
    $v_grupos = array();

    // $v_dados[] = array("linhas" => $v_linhas);
    while ($row = pg_fetch_assoc($result)) {
        $v_grupos[] = array(
            "Id" => $row["id"],
            "Nome" => $row["nome"],
            "Descricao" => $row["descricao"],
        );
    }

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

    $v_sql = "SELECT * FROM db_adm.t_rh_grupo_gh WHERE Id = " . $v_id;
    // $_SESSION["database_adm"] = "S";
    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Id" => $row["id"],
            "Nome" => $row["nome"],
            "Descricao" => $row["descricao"]
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

    $v_sql = "INSERT INTO db_adm.t_rh_grupo_gh (Nome, Descricao)" . "\n" .
        "VALUES('" . $v_nome . "','" . $v_descricao . "') returning id";

    $result = pg_query($conn, $v_sql);
    $v_id_grupo = pg_fetch_array($result,0)[0];

    $v_sql = "insert into db_adm.t_rh_nivel_gh (nivel, descricao, id_grupo) values (0, '{$v_nome}', {$v_id_grupo})";

    pg_query($conn, $v_sql);

    $v_sql = "insert into db_adm.t_rh_funcao_gh (id_nivel, nome, id_grupo) values (0, '{$v_descricao}', {$v_id_grupo})";

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
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

    $v_sql = "UPDATE db_adm.t_rh_grupo_gh SET \n" .
        "Descricao = '" . $v_descricao . "', \n" .
        "Nome = '" . $v_nome . "' \n" .
        "WHERE Id = " . $v_id;

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

if($v_acao == 'EV_MOSTRA_GERENCIAMENTO'){

    $v_id = addslashes($_POST["v_id"]);

    $v_sql = "select emp.id, emp.nome, emp.id_grupo_gh from db_adm.t_empresas emp order by nome";

    $v_empresas = array();

    $result = pg_query($conn, $v_sql);

    while ($row = pg_fetch_assoc($result)) {
        $v_empresas[] = array(
            "Id" => $row["id"],
            "nome" => $row["nome"],
            "grupo_gh" => $row["id_grupo_gh"]
        );
    }

    $v_sql = "select us.id, us.nome from db_adm.t_user us order by nome";

    $v_usuarios = array();

    $result = pg_query($conn, $v_sql);

    while($row = pg_fetch_assoc($result)){
        $v_usuarios[] = array(
            "Id" => $row["id"],
            "nome" => $row["nome"]
        );
    }

    $v_sql = "select gh.id_usuario from db_adm.t_rh_adm_gh gh where gh.id_grupo = {$v_id}";

    $v_adm = array();

    $result = pg_query($conn, $v_sql);

    while($row = pg_fetch_assoc($result)){
        $v_adm[] = array(
            "id" => $row["id_usuario"]
        );
    }

    $v_dados = array(
        "empresas" => $v_empresas,
        "usuarios" => $v_usuarios,
        "adm" => $v_adm
    );

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}

if($v_acao == 'EV_SALVA_DEF_GRUPO'){

    $v_id_grupo = addslashes($_POST["v_id_grupo"]);
    $v_empresas = $_POST["v_empresas"];
    $v_usuarios = $_POST["v_usuarios"];

    $v_sql = "delete from db_adm.t_rh_adm_gh where id_grupo = {$v_id_grupo}";

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Empresas salvas com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    if(gettype($v_usuarios) == 'array'){
        $insert_line = 'insert into db_adm.t_rh_adm_gh (id_usuario, id_grupo) values ';
        foreach ($v_usuarios as $usuarios) {
            $insert_line .= "({$usuarios}, {$v_id_grupo}),";
        }
        pg_query($conn, substr($insert_line, 0, -1));
        // if () {
        //     $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Usuários salvos com sucesso."}';
        // } else {
        //     $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        // }
    }

    $v_sql = "update db_adm.t_empresas set id_grupo_gh = null where id_grupo_gh = {$v_id_grupo}";

    pg_query($conn, $v_sql);

    if(gettype($v_empresas) == 'array'){
        foreach($v_empresas as $empresa){
            $v_sql = "update db_adm.t_empresas set id_grupo_gh = {$v_id_grupo} where id = {$empresa}";
            
            if (pg_query($conn, $v_sql)) {
                $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Grupo salvo com sucesso."}';
            } else {
                $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
            }
        }
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}