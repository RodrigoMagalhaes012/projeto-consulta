<?php
header("Content-Type: application/json; charset=utf-8");
include_once( "../../class/php/class_conect_db_adm.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$uploaddir = '../../arq_emp_'.$SESSION["id_empresa"].'/img/user_foto/';
$uploadfile = $uploaddir . basename($_POST['c_img_nome'].".jpg");
move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
header ("Location: ../home.php");