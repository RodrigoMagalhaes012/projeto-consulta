<?php
header("Content-Type: application/json; charset=utf-8");
include_once( "../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_USUARIOS") {

    if (strpos($_SESSION["vs_array_access"], "T0009") > 0){

        $v_pos = strpos($_SESSION["vs_array_access"], "T0009");
        $v_telas_perm_ler = substr($_SESSION["vs_array_access"],$v_pos+8,1);
        $v_telas_perm_criar = substr($_SESSION["vs_array_access"],$v_pos+12,1);
        $v_telas_perm_gravar = substr($_SESSION["vs_array_access"],$v_pos+16,1);
        $v_telas_perm_excluir = substr($_SESSION["vs_array_access"],$v_pos+20,1);
        // var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

    }

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

if($v_acao == 'EV_CARREGA_ACESSOS'){

    $v_sql = "select * from db_adm.t_access_emp_02_cad_grupos where ativo = 'S'";

    $result = pg_query($conn, $v_sql);
    $v_grupos = array();
    while($row = pg_fetch_assoc($result)){
        $v_grupos[] = array(
            "id_grupo" => $row["id"],
            "nome" => $row["nome"]
        );
    }

    $v_dados = array(
        "grupos" => $v_grupos
    );

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}

if($v_acao == 'EV_CARREGA_TELAS_GRUPO'){

    $v_id_grupo = addslashes($_POST["v_id_grupo"]);
    $v_id_usuario = addslashes($_POST["v_id_usuario"]);

    $v_sql = "select * from db_adm.t_access_telas_02_cad_grupos gptelas order by gptelas.nome";

    $result = pg_query($conn, $v_sql);
    $v_grupos = array();
    while($row = pg_fetch_assoc($result)){
        $v_grupos[] = array(
            "id_grupo" => $row["id"],
            "nome" => $row["nome"]
        );
    }

    $v_sql = "select * from db_adm.t_access ac
    inner join db_adm.t_access_telas_02_cad_grupos gptelas on gptelas.id = ac.id_grupo_telas 
    where ac.id_user = {$v_id_usuario} and ac.id_grupo_emp = {$v_id_grupo} order by gptelas.nome";

    $result = pg_query($conn, $v_sql);
    $v_grupos_usuario = array();
    while($row = pg_fetch_assoc($result)){
        $v_grupos_usuario[] = array(
            "id_grupo_telas" => $row["id_grupo_telas"]
        );
    }

    $v_dados = array(
        "grupos" => $v_grupos,
        "grupos_usuario" => $v_grupos_usuario
    );

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}

if($v_acao == 'EV_SALVA_ACESSOS'){

    $v_id_visibilidade = addslashes($_POST["v_id_visibilidade"]);
    $v_grupos = $_POST["v_grupos"];
    $v_id_usuario = addslashes($_POST["v_id_usuario"]);

    $v_sql = "delete from db_adm.t_access where id_user = {$v_id_usuario} and id_grupo_emp = {$v_id_visibilidade}";

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Empresas salvas com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    if(gettype($v_grupos) == 'array'){
        $insert_line = 'insert into db_adm.t_access (id_user, id_grupo_emp, id_grupo_telas) values ';
        foreach ($v_grupos as $grupos) {
            $insert_line .= "({$v_id_usuario}, {$v_id_visibilidade}, {$grupos}),";
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
// // SELECIONANDO REGISTRO
// if ($v_acao == "EV_SELECT") {

//     $v_id = addslashes($_POST["v_id"]);

//     $v_sql = "SELECT t_telas.Id, t_telas.Nome, t_modulos.Id AS Modulo, t_telas.Descricao FROM db_adm.t_telas JOIN db_adm.t_modulos ON t_telas.Id_Modulo = t_modulos.Id WHERE t_telas.Id = ".$v_id;
//     $_SESSION["database_adm"] = "S";
//     $result = pg_query($conn, $v_sql);

//     $v_dados = array();
//     while($row = pg_fetch_assoc($result)) {
//         $v_dados[]=array("Id" => $row["id"], 
//             "Nome" => $row["nome"], 
//             "Modulo" => $row["modulo"], 
//             "Descricao" => $row["descricao"]);
//     }
//     pg_close($conn);
//     $v_json = json_encode($v_dados);
//     echo $v_json;
// }


// // CADASTRANDO NOVO REGISTRO
// if ($v_acao == "EV_NOVO") {

//     $v_descricao = addslashes($_POST["v_descricao"]);
//     $v_nome = strtoupper(addslashes($_POST["v_nome"]));
//     $v_modulo = addslashes($_POST["v_modulo"]);

//     $v_sql = "INSERT INTO db_adm.t_telas (Nome, Id_Modulo, Descricao)"."\n".
//     "VALUES('".$v_nome."',".$v_modulo.",'".$v_descricao."')";
//     $_SESSION["database_adm"] = "S";

//     if (pg_query($conn, $v_sql)) {
//         $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Cadastro realizado com sucesso."}';
//         } else {
//             $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
//         }

//     pg_close($conn);
//     $v_json = json_encode($json_msg);
//     echo $v_json;

// }



// // SALVANDO REGISTRO
// if ($v_acao == "EV_SALVAR") {

//     $v_id = addslashes($_POST["v_id"]);
//     $v_descricao = addslashes($_POST["v_descricao"]);
//     $v_nome = strtoupper(addslashes($_POST["v_nome"]));
//     $v_modulo = addslashes($_POST["v_modulo"]);

//     $v_sql = "UPDATE db_adm.t_telas SET \n".
//     "Descricao = '".$v_descricao."', \n".
//     "Id_Modulo = ".$v_modulo.", \n".
//     "Nome = '".$v_nome."' \n".
//     "WHERE Id = ".$v_id;
//     $_SESSION["database_adm"] = "S";

//     if (pg_query($conn, $v_sql)) {
//         $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
//         } else {
//             $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
//         }

//     pg_close($conn);
//     $v_json = json_encode($json_msg);
//     echo $v_json;

// }



// // EXCLUINDO REGISTRO
// if ($v_acao == "EV_EXCLUIR") {

//     $v_id = addslashes($_POST["v_id"]);

//     $v_sql = "DELETE FROM t_telas WHERE Id = ".$v_id;
//     $_SESSION["database_adm"] = "S";

//     if (pg_query($conn, $v_sql)) {
//         $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro excluído com sucesso."}';
//         } else {
//             $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
//         }

//     pg_close($conn);
//     $v_json = json_encode($json_msg);
//     echo $v_json;

// }
