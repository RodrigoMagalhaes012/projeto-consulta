<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);

if($v_acao == 'LISTAR_EMPRESAS'){

    $v_sql = "select nome, id from db_adm.t_empresas order by nome";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    while($row = pg_fetch_assoc($result)){
        $v_dados[] = array(
            "id" => $row["id"],
            "nome" => $row["nome"]
        );
    }

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}

if($v_acao == 'LISTAR_COLABORADORES'){

    $v_id_empresa = addslashes($_POST["v_empresa"]);

    $v_sql = "select tpp.id_usuario, trc.nome from db_adm.t_postagem_politica tpp 
    join db_adm_rh.t_rh_colaborador trc on trc.id_usuario = tpp.id_usuario 
    where tpp.id_empresa = {$v_id_empresa}";

    $result = pg_query($conn, $v_sql);

    $v_liberados = array();

    while($row = pg_fetch_assoc($result)){
        $v_liberados[] = array(
            "id" => $row["id_usuario"],
            "nome" => $row["nome"]
        );
    }

    $v_sql = "select trc.id_usuario, trc.nome from db_adm_rh.t_rh_colaborador trc where id_usuario not in (
        select id_usuario from db_adm.t_postagem_politica tpp where id_empresa = {$v_id_empresa}
    ) and id_empresa = {$v_id_empresa}";

    $result1 = pg_query($conn, $v_sql);

    $v_colab = array();

    while($row = pg_fetch_assoc($result1)){
        $v_colab[] = array(
            "id" => $row["id_usuario"],
            "nome" => $row["nome"]
        );
    }

    $v_dados = array(
        "colaboradores" => $v_colab,
        "liberados" => $v_liberados
    );

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}

if($v_acao == 'SALVAR_PERMISSAO'){

    $v_id_empresa = addslashes($_POST["v_empresa"]);
    $v_colaboradores = $_POST["v_colaboradores"];

    $v_sql = "DELETE FROM db_adm.t_postagem_politica
    WHERE id_empresa = {$v_id_empresa}";


    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    if(gettype($v_colaboradores) == 'array'){
        $v_sql = "INSERT INTO db_adm.t_postagem_politica
        (id_usuario, id_empresa) VALUES ";


        foreach ($v_colaboradores as $colab) {
            $v_sql .= "({$colab}, {$v_id_empresa}),";
        }

        if (pg_query($conn, substr($v_sql, 0, -1))) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}