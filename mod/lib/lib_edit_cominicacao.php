<?php
header("Content-Type: application/json; charset=utf-8");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["c_acao"]);



// SELECIONANDO REGISTRO
if ($v_acao == "EV_UPLOAD") {
    $v_img_nome = addslashes($_POST["c_img_nome"]);

    $uploaddir = '../img/index/';
    $uploadfile = $uploaddir.$v_img_nome.".jpg";
    move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
    header ("Location: ../home.php");

}
