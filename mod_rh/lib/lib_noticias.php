<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

require "../../vendor/autoload.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$v_acao = addslashes($_POST["v_acao"]);

if($v_acao == 'LISTAR_NOTICIAS'){

    $v_sql = "select trn.*, count(trcn.conteudo) as qtd_comentarios, tu.nome, tu.url_arquivo from db_adm.t_empresas te
    join db_adm.t_grupo_noticias tgn on tgn.id = te.id_grupo_not 
    join db_adm_rh.t_rh_noticias trn on trn.id_grupo = tgn.id
    join db_adm.t_user tu on tu.id = trn.id_usuario
    full join db_adm_rh.t_rh_comentarios_noticias trcn on trcn.id_noticia = trn.id
    where te.id = {$_SESSION["vs_id_empresa"]}
    group by trn.id, tu.nome, tu.url_arquivo  order by data_publicacao desc";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while($row = pg_fetch_assoc($result)){
        $v_sql1 = "select reacao, count(reacao) from db_adm_rh.t_rh_noticias_reacoes trnr where id_noticia = {$row["id"]} group by reacao ";

        $result1 = pg_query($conn, $v_sql1);

        $v_reacoes = array();
        while($row1 = pg_fetch_assoc($result1)){
            $v_reacoes += array(
                "reacao_{$row1["reacao"]}" => $row1["count"]
            );
        }

        $timeZone = new DateTimeZone('America/Sao_Paulo');
        $v_data = new DateTime($row["data_publicacao"], $timeZone);
        $v_data = $v_data->format('d/m/Y - H:i');

        $v_dados[] = array(
            "id" => $row["id"],
            "titulo" => $row["titulo"],
            "conteudo" => $row["conteudo"],
            "data_publicacao" => $v_data,
            "data_modificacao" => $row["data_modificacao"],
            "imagem" => $row["url_imagem"],
            "foto_usuario" => $row["url_arquivo"],
            "qtd_comentarios" => $row["qtd_comentarios"],
            "usuario" => ucwords(mb_strtolower($row["nome"])),
            "reacoes" => $v_reacoes
        );
    }

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}

if($v_acao == 'VERIFICAR_USUARIO_AUTORIZADO'){

    $v_sql = "select tgn.* from db_adm.t_postagem_noticia tpn 
join db_adm.t_empresas te on te.id_grupo_not = tpn.id_grupo
join db_adm.t_grupo_noticias tgn on tgn.id = tpn.id_grupo 
where tpn.id_usuario = {$_SESSION["vs_id"]} and te.id = {$_SESSION["vs_id_empresa"]}";

    $result = pg_query($conn, $v_sql);

    $v_grupos = array();

    while($row = pg_fetch_assoc($result)){
        $v_grupos[] = array(
            "id" => $row["id"],
            "nome" => $row["nome"]
        );
    }

    if($v_grupos){
        $v_dados = array(
            "auth" => true,
            "grupos" => $v_grupos
        );
    }else{
        $v_dados = array(
            "auth" => false
        );
    }

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}

if($v_acao == 'PUBLICAR_NOTICIA'){

    $v_sql = "select tgn.* from db_adm.t_postagem_noticia tpn 
        join db_adm.t_empresas te on te.id_grupo_not = tpn.id_grupo
        join db_adm.t_grupo_noticias tgn on tgn.id = tpn.id_grupo 
        where tpn.id_usuario = {$_SESSION["vs_id"]} and te.id = {$_SESSION["vs_id_empresa"]}";

    $result = pg_query($conn, $v_sql);

    if($grupo = pg_fetch_object($result)){
        
        $v_titulo = addslashes($_POST["v_titulo"]);
        $v_conteudo = addslashes($_POST["v_conteudo"]);

        $timeZone = new DateTimeZone('America/Sao_Paulo');
        $v_data = new DateTime('now', $timeZone);
        $v_data = $v_data->format('Y-m-d H:i:s');

        if(!empty($_FILES['arquivo']['name'])){

            $v_sql = "INSERT INTO db_adm_rh.t_rh_noticias
            (id_grupo, titulo, conteudo, data_publicacao, id_usuario)
            VALUES({$grupo->id}, '{$v_titulo}', '{$v_conteudo}', '{$v_data}', {$_SESSION["vs_id"]}) returning id";

            $result = pg_query($conn, $v_sql);
            $v_id_noticia = pg_fetch_array($result, 0)[0];

            $info_arquivo = pathinfo($_FILES['arquivo']['name']);
            $dir = "rh/noticias/NOTICIA_{$v_id_noticia}.{$info_arquivo["extension"]}";
    
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
            $v_sql1 = "UPDATE db_adm_rh.t_rh_noticias
            SET url_imagem='{$url}', nome_imagem = 'NOTICIA_{$v_id_noticia}.{$info_arquivo["extension"]}'
            WHERE id = {$v_id_noticia}";
    
            if (pg_query($conn, $v_sql1)) {
                $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Noticia publicada com sucesso."}';
            } else {
                $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
            }
        }else{
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Selecione uma imagem para publicar a notícia."}';
        }


    }else{
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Usuário não autorizado a publicar noticia."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}

if($v_acao == 'EXCLUIR_NOTICIA'){

    $v_sql = "select tgn.* from db_adm.t_postagem_noticia tpn 
join db_adm.t_empresas te on te.id_grupo_not = tpn.id_grupo
join db_adm.t_grupo_noticias tgn on tgn.id = tpn.id_grupo 
where tpn.id_usuario = {$_SESSION["vs_id"]} and te.id = {$_SESSION["vs_id_empresa"]}";

    $result = pg_query($conn, $v_sql);

    if($grupo = pg_fetch_object($result)){
        
        $v_id_noticia = addslashes($_POST["v_id_noticia"]);

        $v_sql = "DELETE FROM db_adm_rh.t_rh_noticias
        WHERE id = {$v_id_noticia}  returning nome_imagem";

        $result = pg_query($conn, $v_sql);
        $v_nome_imagem = pg_fetch_array($result, 0)[0];

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
            "Key" => "rh/noticias/{$v_nome_imagem}"
        ]);

        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Noticia excluida com sucesso."}';


    }else{
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Usuário não autorizado a excluir noticia."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}

if($v_acao == 'SELECIONAR_NOTICIA'){

    $v_sql = "select tgn.* from db_adm.t_postagem_noticia tpn 
join db_adm.t_empresas te on te.id_grupo_not = tpn.id_grupo
join db_adm.t_grupo_noticias tgn on tgn.id = tpn.id_grupo 
where tpn.id_usuario = {$_SESSION["vs_id"]} and te.id = {$_SESSION["vs_id_empresa"]}";

    $result = pg_query($conn, $v_sql);

    if($grupo = pg_fetch_object($result)){

        $v_id_noticia = addslashes($_POST["v_id_noticia"]);

        $v_sql = "SELECT id, id_grupo, titulo, conteudo, data_publicacao, data_modificacao, url_imagem
        FROM db_adm_rh.t_rh_noticias where id = {$v_id_noticia}";

        $result = pg_query($conn, $v_sql);

        $json_msg = pg_fetch_object($result);

    }else{
        $json_msg = array(
            "msg_titulo"=>"FALHA!",
            "msg_ev"=>"error",
            "msg"=>"Usuário não autorizado a excluir noticia."
        );
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
    
}

if($v_acao == 'ALTERAR_NOTICIA'){

    $v_id_noticia = addslashes($_POST["v_id_noticia"]);

    $v_sql = "select tgn.*, trn.nome_imagem from db_adm.t_postagem_noticia tpn 
        join db_adm.t_empresas te on te.id_grupo_not = tpn.id_grupo
        join db_adm.t_grupo_noticias tgn on tgn.id = tpn.id_grupo
        join db_adm_rh.t_rh_noticias trn on trn.id_grupo = tgn.id 
        where tpn.id_usuario = {$_SESSION["vs_id"]} and te.id = {$_SESSION["vs_id_empresa"]}  and trn.id = {$v_id_noticia}";

    $result = pg_query($conn, $v_sql);

    if($grupo = pg_fetch_object($result)){
        
        $v_titulo = addslashes($_POST["v_titulo"]);
        $v_conteudo = addslashes($_POST["v_conteudo"]);

        $timeZone = new DateTimeZone('America/Sao_Paulo');
        $v_data = new DateTime('now', $timeZone);
        $v_data = $v_data->format('Y-m-d H:i:s');

        $url = "";
        $nome_imagem = "";

        if(!empty($_FILES['arquivo']['name'])){
            $info_arquivo = pathinfo($_FILES['arquivo']['name']);
            $dir = "rh/noticias/NOTICIA_{$v_id_noticia}.{$info_arquivo["extension"]}";
    
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
            
            $s3Client->deleteObject([
                "Bucket" => "testephp",
                "Key" => "rh/noticias/{$grupo->nome_imagem}"
            ]);
        
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
            $url = ", url_imagem = '{$url}', ";
            $nome_imagem = ", nome_imagem = 'NOTICIA_{$v_id_noticia}.{$info_arquivo["extension"]}'";
        }

        $v_sql = "UPDATE db_adm_rh.t_rh_noticias
        SET id_grupo={$grupo->id}, titulo='{$v_titulo}', conteudo='{$v_conteudo}', data_modificacao='{$v_data}' {$url} {$nome_imagem}
        WHERE id = {$v_id_noticia}";

        // var_dump($v_sql);die;

        if (pg_query($conn, $v_sql)) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Noticia publicada com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }

    }else{
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Usuário não autorizado a alterar noticia."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}

if($v_acao == 'EXIBIR_COMENTARIOS'){

    $v_id_noticia = addslashes($_POST["v_id_noticia"]);

    $v_sql = "select trcn.*, tu.nome, tu.url_arquivo from db_adm_rh.t_rh_comentarios_noticias trcn
        join db_adm.t_user tu on tu.id = trcn.id_usuario 
        where trcn.id_noticia = {$v_id_noticia} order by trcn.data_publicacao desc";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while($row = pg_fetch_assoc($result)){

        $timeZone = new DateTimeZone('America/Sao_Paulo');
        $v_data = new DateTime($row["data_publicacao"], $timeZone);
        $v_data = $v_data->format('d/m/Y - H:i');

        if($_SESSION["vs_id"] == $row["id_usuario"]){
            $v_dados[] = array(
                "id" => $row["id"],
                "data_publicacao" => $v_data,
                "conteudo" => $row["conteudo"],
                "nome_usuario" => ucwords(mb_strtolower($row["nome"])),
                "foto_usuario" => $row["url_arquivo"],
                "id_usuario" => $row["id_usuario"],
                "pertence_usuario" => true
            );
        }else{
            $v_dados[] = array(
                "id" => $row["id"],
                "data_publicacao" => $v_data,
                "conteudo" => $row["conteudo"],
                "nome_usuario" => ucwords(mb_strtolower($row["nome"])),
                "foto_usuario" => $row["url_arquivo"],
                "id_usuario" => $row["id_usuario"],
                "pertence_usuario" => false
            );
        }
    }

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}

if($v_acao == 'PUBLICAR_COMENTARIO'){

    $v_id_noticia = addslashes($_POST["v_id_noticia"]);
    $v_comentario = addslashes($_POST["v_comentario"]);

    $timeZone = new DateTimeZone('America/Sao_Paulo');
    $v_data = new DateTime('now', $timeZone);
    $v_data = $v_data->format('Y-m-d H:i:s');

    $v_sql = "INSERT INTO db_adm_rh.t_rh_comentarios_noticias
    (id_noticia, data_publicacao, conteudo, id_usuario)
    VALUES({$v_id_noticia}, '{$v_data}', '{$v_comentario}', {$_SESSION["vs_id"]})";

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Comentário publicado com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}

if($v_acao == 'EXCLUIR_COMENTARIO'){

    $v_id_comentario = addslashes($_POST["v_id_comentario"]);

    $v_sql = "DELETE FROM db_adm_rh.t_rh_comentarios_noticias
    WHERE id = {$v_id_comentario} and id_usuario = {$_SESSION["vs_id"]}";

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Comentário excluido com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}

if($v_acao == 'EDITAR_COMENTARIO'){

    $v_id_comentario = addslashes($_POST["v_id_comentario"]);
    $v_comentario = addslashes($_POST["v_comentario"]);

    $timeZone = new DateTimeZone('America/Sao_Paulo');
    $v_data = new DateTime('now', $timeZone);
    $v_data = $v_data->format('Y-m-d H:i:s');

    $v_sql = "UPDATE db_adm_rh.t_rh_comentarios_noticias
    SET data_modificacao='{$v_data}', conteudo='{$v_comentario}'
    WHERE id = {$v_id_comentario} and id_usuario = {$_SESSION["vs_id"]}";

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Comentário editado com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;


}

if($v_acao == 'REAGIR'){

    $v_noticia = addslashes($_POST["v_noticia"]);
    $v_reacao = addslashes($_POST["v_reacao"]);

    $v_sql = "INSERT INTO db_adm_rh.t_rh_noticias_reacoes (id_noticia , id_usuario , reacao)
    VALUES ({$v_noticia}, {$_SESSION["vs_id"]}, {$v_reacao})
    ON CONFLICT (id_noticia, id_usuario)
    DO UPDATE SET
        reacao = {$v_reacao}
    WHERE
    db_adm_rh.t_rh_noticias_reacoes.id_noticia = {$v_noticia}
    and db_adm_rh.t_rh_noticias_reacoes.id_usuario = {$_SESSION["vs_id"]}";

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Comentário editado com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

if($v_acao == 'VERIFICA_AUTORIZACAO'){

    $v_sql = "select tgn.* from db_adm.t_postagem_noticia tpn 
        join db_adm.t_empresas te on te.id_grupo_not = tpn.id_grupo
        join db_adm.t_grupo_noticias tgn on tgn.id = tpn.id_grupo 
        where tpn.id_usuario = {$_SESSION["vs_id"]} and te.id = {$_SESSION["vs_id_empresa"]}";

    $result = pg_query($conn, $v_sql);

    if($grupo = pg_fetch_object($result)){

        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Usuário autorizado."}';

    }else{
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Usuário não autorizado a publicar noticia."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}