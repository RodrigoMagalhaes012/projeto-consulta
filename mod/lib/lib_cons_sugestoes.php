<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);

if($v_acao == 'LISTAR_SUGESTOES'){

    $v_sql = "select ts.*, tu.nome from db_adm.t_sugestoes ts
    join db_adm.t_user tu on tu.id = ts.id_usuario 
    order by data desc";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    while($row = pg_fetch_assoc($result)){

        $timeZone = new DateTimeZone('America/Sao_Paulo');
        $v_data = new DateTime($row["data"], $timeZone);
        $v_data = $v_data->format('d/m/Y H:i');

        $v_dados[] = array(
            "sugestao" => $row["sugestao"],
            "usuario" => $row["nome"],
            "data" => $v_data
        );
    }

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}