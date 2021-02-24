<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$dir = "/certs/pfx";
// recebendo o arquivo multipart 
$file = $_FILES["arquivo"];
// Move o arquivo da pasta temporaria de upload para a pasta de destino 
if (move_uploaded_file($file["tmp_name"], "$dir/" . $file["name"])) {
    echo "Arquivo enviado com sucesso!";
} else {
    echo "Erro, o arquivo não pode ser enviado.";
}


// ############################ //
// VALIDAR SENHA E DATA DE VALIDADE DO CERTIFICADO DIGITAL//
// ############################ //
// var_dump("TESTE OK");
// $v_acao = addslashes($_POST["c_acao"]);



// if ($v_acao == 'EV_SALVAR') {

// $_SESSION["arquivo_cert"] = $_FILES['upload_cert']['tmp_name'];



// $arquivo_tmp = $_FILES['arquivo']['tmp_name'];

// $v_cnpj_empresa = '13994242000190';
// $certPassword = 'xxxxxx';

// $data = file_get_contents($arquivo_tmp);
// if (openssl_pkcs12_read($data, $certs, $certPassword)) {

// $CertPriv = array();
// $CertPriv = openssl_x509_parse(openssl_x509_read($certs['cert']));
// $v_validade = date('Y-m-d H:i:s', $CertPriv['validTo_time_t']);
// $v_cnpj_cert = explode(":", $CertPriv['subject']['CN'])[1];


// $Hora = date('Y-m-d H:i:s');
// if ($v_validade >= $Hora) {
// if ($v_cnpj == $v_cnpj_cert) {

// $data = file_get_contents('/mod_fisco/certs/pfx/' . $v_cnpj_empresa . '.pfx');
// if (openssl_pkcs12_read($data, $certs, $certPassword)) {


// $json_certs = json_encode($certs);
// $json = json_decode($json_certs);
// $certKey = $json->cert;
// $priKey = $json->pkey;

// // ############################ //
// // GERANDO O CERTIFICADO PEM //
// // ############################ //
// $arquivo = fopen('certs/pem/' . $v_cnpj_empresa . '.pem', 'w');
// //escrevemos no arquivo
// $texto = $priKey . "\n\n" . $certKey;
// fwrite($arquivo, $texto);
// //Fechamos o arquivo após escrever nele
// fclose($arquivo);
// }
// }
// }
// } else {

// echo "SENHA INCORRETA";
// }
// } else {
// echo "O CADASTRO PRECISA SER SALVO ANTES DA REALIZAÇÃO DO UPLOAD DO CERTIFICADO DIGITAL!";
// }