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

    // CONTANDO REGISTROS PARA GERAR OS BOTÕES DE NAVEGAÇÃO
    $v_sql = "SELECT count(id) as linhas from db_adm.t_access_telas_02_cad_grupos ";
    $_SESSION["database_adm"] = "S";
    if ($result = pg_query($conn, $v_sql)) {
        $row = pg_fetch_assoc($result);
        $v_linhas = $row["linhas"];
    }

    // GERANDO A LISTA
    $v_sql = "SELECT Id, Nome, Descricao, ativo FROM db_adm.t_access_telas_02_cad_grupos " ;//. $v_filtro . " ORDER BY " . $v_tab_campo . " " . $v_tab_ordem . " OFFSET " . $v_tab_sql_limit_in . " LIMIT " . $v_limit;
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

    $v_sql = "select * from db_adm.t_access_telas_04_cad_modulos modulos";

    $result = pg_query($conn, $v_sql);

    //  var_dump($v_sql);
    $v_modulos = array();

    // $v_dados[] = array("linhas" => $v_linhas);
    while ($row = pg_fetch_assoc($result)) {
        $v_modulos[] = array(
            "Id" => $row["id"],
            "Nome" => $row["nome"]
        );
    }

    $v_dados = array(
        "grupos" => $v_grupos,
        "modulos" => $v_modulos
    );

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// SELECIONANDO REGISTRO
if ($v_acao == "EV_SELECT") {

    $v_id = addslashes($_POST["v_id"]);

    $v_sql = "SELECT Id,Nome,Descricao, ativo FROM db_adm.t_access_telas_02_cad_grupos WHERE Id = " . $v_id;
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
if ($v_acao == "EV_SELECT_USUARIO") {


    $v_sql = "SELECT Id, Nome FROM db_adm.t_user ORDER BY Nome";
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

    $v_descricao = strtoupper(addslashes($_POST["v_descricao"]));
    $v_nome = strtoupper(addslashes($_POST["v_nome"]));
    $v_status_grupo = strtoupper(addslashes($_POST["v_status_grupo"]));

    $v_sql = "INSERT INTO db_adm.t_access_telas_02_cad_grupos (Nome, Descricao, ativo)" . "\n" .
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

    $v_sql = "UPDATE db_adm.t_access_telas_02_cad_grupos SET \n" .
        "Descricao = '" . $v_descricao . "', \n" .
        "Nome = '" . $v_nome . "', \n" .
        "ativo = '" . $v_status_grupo . "' \n" .
        "WHERE Id = " . $v_id;
    $_SESSION["database_adm"] = "S";

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

    $v_sql = "DELETE FROM db_adm.t_access_telas_02_cad_grupos WHERE Id = " . $v_id;
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

if($v_acao == 'EV_CARREGA_TELAS'){

    $v_id_grupo = addslashes($_POST["v_id_grupo"]);
    $v_id_modulo = addslashes($_POST["v_id_modulo"]);

    $v_sql = "select * from db_adm.t_access_telas_03_cad_telas telas where telas.id_modulo = {$v_id_modulo}";

    // var_dump($v_sql);

    $result = pg_query($conn, $v_sql);

    $v_telas = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_telas[] = array(
            "id_tela" => $row["id"],
            "nome" => $row["nome"]
        );
    }

    $v_sql = "select * from db_adm.t_access_telas_01_grupo_telas g_telas
            join db_adm.t_access_telas_03_cad_telas telas on g_telas.id_tela = telas.id
            where g_telas.id_grupo = {$v_id_grupo} and telas.id_modulo = {$v_id_modulo}";

    $result = pg_query($conn, $v_sql);

    $v_acessos = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_acessos[] = array(
            "id_tela" => $row["id_tela"],
            "id_grupo" => $row["id_grupo"],
            "nome" => $row["nome"],
            "perm_criar" => $row["perm_criar"],
            "perm_ler" => $row["perm_ler"],
            "perm_gravar" => $row["perm_gravar"],
            "perm_excluir" => $row["perm_excluir"]
        );
    }

    $v_dados = array(
        "telas" => $v_telas,
        "acessos" => $v_acessos
    );

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}

if($v_acao == 'EV_SALVA_ACESSOS'){

    $v_id_grupo = addslashes($_POST["v_id_grupo"]);
    $v_dados_acesso = $_POST["v_dados_acesso"];

    foreach ($v_dados_acesso as $dados) {
        if($dados['ativo'] == 'N'){
            $v_sql = "delete from db_adm.t_access_telas_01_grupo_telas where id_grupo = {$v_id_grupo} and id_tela = {$dados['id_tela']}";
            pg_query($conn, $v_sql);
        }else{
            $v_sql = "insert into db_adm.t_access_telas_01_grupo_telas
            (id_grupo, id_tela, perm_criar, perm_excluir, perm_gravar, perm_ler)
            values
            ({$v_id_grupo}, {$dados["id_tela"]}, '{$dados["criacao"]}', '{$dados["exclusao"]}', '{$dados["gravacao"]}', '{$dados["leitura"]}')
            ON CONFLICT ON CONSTRAINT t_access_telas_01_user_pkey DO NOTHING;";
            // var_dump($v_sql);
            pg_query($conn, $v_sql);

            $v_sql = "update db_adm.t_access_telas_01_grupo_telas set
            perm_criar = '{$dados["criacao"]}',
            perm_excluir = '{$dados["exclusao"]}',
            perm_gravar = '{$dados["gravacao"]}',
            perm_ler = '{$dados["leitura"]}'
            where id_tela = {$dados["id_tela"]} and id_grupo = {$v_id_grupo}";

            pg_query($conn, $v_sql);
        }
    }

    $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Acessos de tela atualizados com sucesso."}';

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}