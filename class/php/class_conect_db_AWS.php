<?php

$servidor = "database-1.cf9xt2frblbg.us-east-1.rds.amazonaws.com";
$porta = 5432;
$bancoDeDados = "agrocontar";
$usuario = "postgres";
$senha = "QIzTPhh8ZhJnA8GWIrF8";

$conn = pg_connect("host=$servidor port=$porta dbname=$bancoDeDados user=$usuario password=$senha");
if (!$conn) {
    die("Não foi possível se conectar ao banco de dados.");
}
