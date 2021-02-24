<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

require "../../vendor/autoload.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$v_acao = addslashes($_POST["v_acao"]);

if ($v_acao == 'LISTAR_DOCUMENTOS') {

    $v_sql = "SELECT * from db_adm_rh.t_rh_politicas trp where id_empresa = {$_SESSION["vs_id_empresa"]}";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "nome_documento" => $row["nome_documento"],
            "descricao" => $row["descricao"],
            "ano_referencia" => $row["ano_referencia"],
            "id_empresa" => $row["id_empresa"],
            "id" => $row["id"],
            "url" => $row["url_arquivo"]
        );
    }

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}

if ($v_acao == 'UPLOAD_POLITICA') {

    $id_empresa = $_SESSION["vs_id_empresa"];
    $v_descricao = addslashes($_POST["v_descricao"]);
    $v_referencia = addslashes($_POST["v_referencia"]);

    $v_sql = "SELECT * from db_adm.t_postagem_politica tpp 
                where tpp.id_empresa = {$id_empresa} 
                    and tpp.id_usuario = {$_SESSION["vs_id"]}";

    $result = pg_query($conn, $v_sql);

    if (pg_num_rows($result) > 0) {

        if (!empty($_FILES['arquivo']['name'])) {

            $info_arquivo = pathinfo($_FILES['arquivo']['name']);
    
            $v_sql1 = "INSERT INTO db_adm_rh.t_rh_politicas
            (descricao, ano_referencia, id_empresa)
            VALUES('{$v_descricao}', '{$v_referencia}', {$id_empresa}) returning id";
    
            $result = pg_query($conn, $v_sql1);
            $v_id_politica = pg_fetch_array($result, 0)[0];
    
            $dir = "rh/{$id_empresa}/POLITICA/POLITICA_{$v_id_politica}.{$info_arquivo["extension"]}";
    
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
                'Bucket' => getenv("S3_BUCKET"),
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
    
            $v_sql = "UPDATE db_adm_rh.t_rh_politicas
            SET url_arquivo = '{$url}', nome_documento = 'POLITICA_{$v_id_politica}.{$info_arquivo["extension"]}'
            WHERE id = {$v_id_politica}";
    
            if (pg_query($conn, $v_sql)) {
                $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Política publicada com sucesso!"}';
            } else {
                $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Erro ao publicar política!"}';
            }
        }else{
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Por favor, selecione um arquivo!"}';
        }

    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Usuário não autorizado a publicar política!"}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

if ($v_acao == 'EXCLUIR_POLITICA') {

    $v_id_politica = addslashes($_POST["v_id_politica"]);
    $id_empresa = $_SESSION["vs_id_empresa"];

    $v_sql = "select * from db_adm.t_postagem_politica tpp where tpp.id_empresa = {$id_empresa} and tpp.id_usuario = {$_SESSION["vs_id"]}";

    $result = pg_query($conn, $v_sql);

    if (pg_num_rows($result) > 0) {

        $v_sql = "DELETE FROM db_adm_rh.t_rh_politicas
        WHERE id = {$v_id_politica} returning nome_documento";



        if ($result = pg_query($conn, $v_sql)) {

            $v_nome = pg_fetch_array($result, 0)[0];

            $sharedConfig = ([
                'version' => 'latest',
                'region'  => 'us-east-1',
                'credentials' => [
                    'key' => getenv("S3_KEY"),
                    'secret' => getenv("S3_SECRET")
                ]
            ]);

            $sdk = new Aws\Sdk($sharedConfig);

            $s3Client = $sdk->createS3();

            $s3Client->deleteObject([
                "Bucket" => "testephp",
                "Key" => "rh/{$id_empresa}/POLITICA/{$v_nome}"
            ]);

            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Política excluida com sucesso!"}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Erro ao excluir!"}';
        }
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Usuário não autorizado a excluir política!"}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

if($v_acao == 'VERIFICA_AUTORIZACAO'){

    $id_empresa = $_SESSION["vs_id_empresa"];

    $v_sql = "SELECT * from db_adm.t_postagem_politica tpp 
                where tpp.id_empresa = {$id_empresa} 
                    and tpp.id_usuario = {$_SESSION["vs_id"]}";

    $result = pg_query($conn, $v_sql);

    if (pg_num_rows($result) > 0) {

        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Usuário autorizado."}';

    }else{
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Usuário não autorizado a publicar noticia."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}