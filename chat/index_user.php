<?php
require "class/php/class_criptografia.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$v_chave = explode("=", $_SERVER["REQUEST_URI"])[1];

//Instanciando classe
$mc = new MyCripty();
//Texto a ser decriptogrfado
$_SESSION["vs_id"] = $mc -> dec($_GET['chave1']);

$v_nome = explode(" ", $mc -> dec($_GET['chave2']));
$_SESSION["vs_nome"] = $v_nome[0];
$_SESSION["vs_nome"] .= " " . $v_nome[count($v_nome)-1];

$_SESSION["vs_db_empresa"] = $mc -> dec($_GET['chave3']);
echo '<script>location.href = "chat_user.php";</script>';

?>