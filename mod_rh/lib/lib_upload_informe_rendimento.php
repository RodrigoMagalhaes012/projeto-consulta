<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

require "../../vendor/autoload.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$v_acao = addslashes($_POST["v_acao"]);


if (strpos($_SESSION["vs_array_access"], "T0083") > 0) {

    $v_pos = strpos($_SESSION["vs_array_access"], "T0083");
    $v_up_hol_ler = substr($_SESSION["vs_array_access"], $v_pos + 8, 1);
    $v_up_hol_criar = substr($_SESSION["vs_array_access"], $v_pos + 12, 1);
    $v_up_hol_gravar = substr($_SESSION["vs_array_access"], $v_pos + 16, 1);
    $v_up_hol_excluir = substr($_SESSION["vs_array_access"], $v_pos + 20, 1);
}

$v_id_emp = $_SESSION["vs_id_empresa"];


if ($v_acao == 'UPLOAD_INFORME') {

    $arquivo = isset($_FILES['arquivo']) ? $_FILES['arquivo'] : FALSE;
    $info_arquivo = pathinfo($_FILES['arquivo']['name'][0]);

    if ($info_arquivo["extension"] == "PDF" || "pdf" || "Pdf") {

        for ($controle = 0; $controle < count($arquivo['name']); $controle++) {
            $v_chave = randString(10);
            $v_nome_arquivo =  $arquivo['name'][$controle];
            $v_cnpj = explode('_', $v_nome_arquivo)[0];
            $v_cpf = explode('_', $v_nome_arquivo)[1];
            $v_ano_ref = str_replace(['.', 'pdf', 'PDF', 'Pdf'], '', explode('_', $v_nome_arquivo)[2]);

            $v_sql = "SELECT * 
                        from db_adm_rh.t_rh_informe_rendimento 
                            where id_empresa = {$_SESSION["vs_id_empresa"]} 
                            and ano_referencia = '{$v_ano_ref}'
                            and cpf = {$v_cpf}";

            // var_dump($v_sql);

            $result = pg_query($conn, $v_sql);

            if (pg_num_rows($result) == 0) {

                $v_sql = "INSERT INTO db_adm_rh.t_rh_informe_rendimento 
                            (id_empresa, cpf, ano_referencia, descricao, nome_documento)
                            VALUES({$v_id_emp}, {$v_cpf}, '{$v_ano_ref}', 'Informe Rendimento {$v_ano_ref}', 'Informe Rendimento') returning id";
                $result = pg_query($conn, $v_sql);
                $v_id_informe = pg_fetch_array($result, 0)[0];

                $dir = "rh/{$v_id_emp}/INFORME_RENDIMENTO/{$v_id_informe}_{$v_cpf}_{$v_cnpj}_{$v_ano_ref}.{$info_arquivo["extension"]}";
                $caminho_arquivo = "{$v_id_informe}_{$v_cpf}_{$v_cnpj}_{$v_ano_ref}.{$info_arquivo["extension"]}";

                $sharedConfig = ([
                    'version' => 'latest',
                    'region'  => 'us-east-1',
                    'credentials' => [
                        'key' => getenv("S3_KEY"),
                        'secret' => getenv("S3_SECRET")
                    ]
                ]);

                $sdk = new Aws\Sdk($sharedConfig);

                $body = fopen($_FILES['arquivo']['tmp_name'][$controle], 'rb');

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

                $v_sql = "UPDATE db_adm_rh.t_rh_informe_rendimento
                            SET url_arquivo = '{$url}',
                                caminho_arquivo = '{$caminho_arquivo}'
                            WHERE id = {$v_id_informe}";
                pg_query($conn, $v_sql);

                $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Upload realizado com sucesso!"}';
            } else {
                $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Erro ao realizar upload, favor excluir o Informe do colaborador CPF:' . $v_cpf . ' e referencia de: ' . $v_ano_ref . '!"}';
            }
        }
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"É permitido apenas arquivos com extensão PDF.!"}';
    }

    // GRAVANDO O LOG DE IMPORTAÇÃO
    $timeZone = new DateTimeZone('America/Sao_Paulo');
    $v_data = new DateTime('now', $timeZone);
    $v_data = $v_data->format('Y-m-d H:i:s');

    $v_sql = "INSERT INTO db_adm_rh.t_log
                (id_user, data_hora, id_empresa, id_processo, descricao)
                VALUES({$_SESSION["vs_id"]}, '$v_data', {$_SESSION["vs_id_empresa"]}, 4, 'Importação de Informe de rendimento Realizada!')
                ON CONFLICT DO NOTHING;";
    $result = pg_query($conn, $v_sql);


    // $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Importação realizada com sucesso!." }';

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}


if ($v_acao == 'EXCLUIR_INFORME') {

    $v_id_informe = addslashes($_POST["v_id_informe"]);
    $id_empresa = intval($_SESSION["vs_id_empresa"]);


    if ($v_id_emp) {
        $v_sql = "DELETE FROM db_adm_rh.t_rh_informe_rendimento
        WHERE id = {$v_id_informe} returning caminho_arquivo";

        if ($result = pg_query($conn, $v_sql)) {
            $caminho_arquivo = pg_fetch_array($result, 0)[0];

            // var_dump($caminho_arquivo);

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
                "Key" => "rh/{$v_id_emp}/INFORME_RENDIMENTO/{$caminho_arquivo}"
            ]);
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Erro ao excluir!"}';
        }
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Erro ao excluir!"}';
    }


    // GRAVANDO O LOG DE IMPORTAÇÃO
    $timeZone = new DateTimeZone('America/Sao_Paulo');
    $v_data = new DateTime('now', $timeZone);
    $v_data = $v_data->format('Y-m-d H:i:s');

    $v_sql = "INSERT INTO db_adm_rh.t_log
                (id_user, data_hora, id_empresa, id_processo, descricao)
                VALUES({$_SESSION["vs_id"]}, '$v_data', {$_SESSION["vs_id_empresa"]}, 5, 'Exclulsão do Informe de Rendimento!')
                ON CONFLICT DO NOTHING;";

    if ($result = pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Informe excluído com sucesso!"}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}


function randString($size)
{
    //String com valor possíveis do resultado, os caracteres pode ser adicionado ou retirados conforme sua necessidade
    $basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    $return = "";

    for ($count = 0; $size > $count; $count++) {
        //Gera um caracter aleatorio
        $return .= $basic[rand(0, strlen($basic) - 1)];
    }

    return $return;
}
