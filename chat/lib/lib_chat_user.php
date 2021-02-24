<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



if ($v_acao == "LISTA_CONVERSAS") {

    $v_conversa_id = addslashes($_POST["v_conversa_id"]);
    
    
    $v_sql = "SELECT id, to_char(data_hora, 'DD/MM/YYYY HH24:MI:SS') as data_hora, to_char(data_hora, 'DD/MM/YYYY') as cdata, to_char(data_hora, 'HH24:MI:SS') as chora, cliente_id, cliente_nome, suporte_id, suporte_nome, msg_class, msg_tipo, msg_texto, msg_link FROM db_adm_chat.t_chat_" .  date("Y")  . "_msg WHERE cliente_id = " . $_SESSION["vs_id"] . " and id > " . $v_conversa_id . " order by data_hora";
    $result = pg_query($conn, $v_sql);

    $v_dados[] = array();
    while ($row = pg_fetch_assoc($result)) {

        $msg_id = "";
        $msg_nome = "";
        if($row["msg_class"] == "cliente"){
            $msg_id = $row["cliente_id"];
            $msg_nome = $row["cliente_nome"];
        } else {
            $msg_id = $row["suporte_id"];
            $msg_nome = $row["suporte_nome"];
        }

        if (file_exists('../img/user/' . $msg_id . '.jpg')) {
            $img = 'img/user/' . $msg_id . '.jpg';
            } else {
                $img = 'img/user/user.jpg';
            }
        $v_dados[] = array("id" => $row["id"], "data_hora" => $row["data_hora"], "cdata" => $row["cdata"], "chora" => $row["chora"], "id_user_msg" => $msg_id, "msg_nome" => $msg_nome, "msg_class" => $row["msg_class"], "msg_tipo" => $row["msg_tipo"], "msg_texto" => $row["msg_texto"], "msg_link" => $row["msg_link"], "foto_user" => $img);
        }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}



if ($v_acao == "ENVIAR_MSG") {

    $v_msg = ucfirst(strtolower(addslashes($_POST["v_msg"])));
    $v_nome =  $_SESSION["vs_nome"];

    $v_sql = "insert into db_adm_chat.t_chat_" .  date("Y")  . "_msg (empresa_id, cliente_id, cliente_nome, msg_class, msg_tipo, msg_texto) 
    values(" . $_SESSION["vs_db_empresa"] . ", " . $_SESSION["vs_id"] . ", '" . $v_nome . "', 'cliente', 'TXT', '" . $v_msg . "')";
    pg_query($conn, $v_sql);

    date_default_timezone_set('America/Sao_Paulo');

    if (file_exists('../img/user/' . $_SESSION["vs_id"] . '.jpg')) {
        $img = 'img/user/' . $_SESSION["vs_id"] . '.jpg';
        } else {
            $img = 'img/user/user.jpg';
        }
    $v_dados[] = array();
    $v_dados[] = array("data_hora" => date('d/m/Y H:i:s'), "cdata" => date('d/m/Y'), "chora" => date('H:i:s'), "id_user_msg" => $_SESSION["vs_id"], "msg_nome" => $v_nome, "msg_class" => "cliente", "msg_tipo" => "TXT", "msg_texto" => $v_msg, "foto_user" => $img);

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}



if ($v_acao == "SEND_FILE") {

    $info_arquivo = pathinfo($_FILES['arquivo']['name']);
    $v_msg = ucfirst(strtolower(addslashes($_POST["v_msg"])));

    $timeZone = new DateTimeZone('America/Sao_Paulo');
    $v_data = new DateTime('now', $timeZone);
    $v_data = $v_data->format('Y-m-d H:i:s');

    $v_file_data = preg_replace("/[^0-9]/", "", $v_data);
    $dir = str_pad($_SESSION["vs_db_empresa"], 4, '0', STR_PAD_LEFT) . "/" . str_pad($_SESSION["vs_id"], 6, '0', STR_PAD_LEFT) . "_". $v_file_data . "." . $info_arquivo["extension"];

    require "../../vendor/autoload.php";
    $sharedConfig = ([
        'version' => 'latest',
        'region'  => 'us-east-1',
        'credentials' => [
            'key' => getenv("S3_KEY"),
            'secret' => getenv("S3_SECRET")
        ]
    ]);

    $sdk = new Aws\Sdk($sharedConfig);

    $body = fopen($_FILES['arquivo']['tmp_name'], 'rb');

    // Use an Aws\Sdk class to create the S3Client object.
    $s3Client = $sdk->createS3();

    // Send a PutObject request and get the result object.
    $result = $s3Client->putObject([
        'Bucket' => 'chat-unifica',
        'Key' => $dir,
        'Body' => $body,
        'ACL'  => 'public-read'
    ]);

    // Download the contents of the object.
    // $result = $s3Client->getObject([
    //     'Bucket' => getenv("S3_BUCKET"),
    //     'Key' => $dir
    // ]);

    $url = $result['@metadata']['effectiveUri'];

    $v_sql = "insert into db_adm_chat.t_chat_" .  date("Y")  . "_msg (empresa_id, cliente_id, cliente_nome, msg_class, msg_tipo, msg_link, msg_texto) 
    values(" . $_SESSION["vs_db_empresa"] . ", " . $_SESSION["vs_id"] . ", '" . $v_nome . "', 'cliente', 'ARQ', '" . $url . "', '" . $v_msg . "')";
    pg_query($conn, $v_sql);

}