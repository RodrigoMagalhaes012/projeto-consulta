<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servidor = getenv("BD_SERVER");
$porta = getenv("BD_PORT");
// $bancoDeDados = "db_emp_".$_SESSION["vs_db_empresa");
$bancoDeDados = getenv("BD_NAME");
$usuario = getenv("BD_USER");
$senha = getenv("BD_PASS");

$conn = pg_connect("host=$servidor port=$porta dbname=$bancoDeDados user=$usuario password=$senha");
if (!$conn) {
    die("Não foi possível se conectar ao banco de dados.");
}
