<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);

// GERANDO LISTA DE EMPRESAS
if ($v_acao == "ENVIAR_SUGESTAO") {
    $v_sugestao = addslashes($_POST["v_sugestao"]);

    $timeZone = new DateTimeZone('America/Sao_Paulo');
	$v_data = new DateTime('now', $timeZone);
	$v_data = $v_data->format('Y-m-d H:i:s');

    $v_sql = "insert into db_adm.t_sugestoes (sugestao, data, id_usuario) values ('{$v_sugestao}', '{$v_data}', {$_SESSION["vs_id"]})";

    // var_dump($v_sql);
    $result = pg_query($conn, $v_sql);

    $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Sugestão enviada com sucesso!." }';
    // var_dump($json_msg);
    $v_json = json_encode($json_msg);
    echo $v_json;
}