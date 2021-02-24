<?php
header("Content-Type: application/json; charset=utf-8");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}




$uploaddir = '../certs/pfx/';
$uploadfile = $uploaddir .$_POST["c_id_img"].".pfx";
move_uploaded_file($_FILES['userfoto']['tmp_name'], $uploadfile);
echo "OK";





