<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

require "../../vendor/autoload.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$v_acao = addslashes($_POST["v_acao"]);

if ($v_acao == 'UPLOAD_LOGO') {

    $v_id_emp = addslashes($_POST["v_id"]);
    $v_id_user = intval($_SESSION["vs_id"]);

    $info_arquivo = pathinfo($_FILES['logofoto']['name']);

    if ($info_arquivo["extension"] == "JPEG" || "GIF" || "JPG") {

        $dir = "logo_emp/{$v_id_emp}.{$info_arquivo["extension"]}";


        $sharedConfig = ([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'credentials' => [
                'key' => getenv("S3_KEY"),
                'secret' => getenv("S3_SECRET")
            ]
        ]);

        $sdk = new Aws\Sdk($sharedConfig);

        $body = fopen($_FILES['logofoto']['tmp_name'], 'rb');

        // Use an Aws\Sdk class to create the S3Client object.
        $s3Client = $sdk->createS3();

        // Send a PutObject request and get the result object.
        $result = $s3Client->putObject([
            'Bucket' => getenv("S3_BUCKET"),
            'Key' => $dir,
            'Body' => $body,
            'ACL'  => 'public-read'
        ]);


        $url = $result['@metadata']['effectiveUri'];


        $v_sql = "UPDATE db_adm.t_empresas
        SET url_arquivo = '{$url}',
            arquivo_img = '{$v_id_emp}.{$info_arquivo["extension"]}'
        WHERE id = {$v_id_emp}";


        if (pg_query($conn, $v_sql)) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Foto adicionada com sucesso!"}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Erro ao adicionar foto!"}';
        }
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Extensão de imagem não aceita!"}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}
