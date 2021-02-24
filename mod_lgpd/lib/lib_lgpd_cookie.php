<?php
header("Content-Type: application/json; charset=utf-8");
include_once( "../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);

// REGISTRANDO QUANDO O USUÁRIO CLICAR NO BOTÃO ENTENDI SOBRE O USO DOS COOKIES
if ($v_acao == "REGISTRA_CIENCIA_COOKIES") {
    setcookie("cookie_lgpd", "ok", time() + 2147483647, "/");

    $v_sql = "update db_adm.t_user set dt_lgpd_ciencia_cookies = current_timestamp where id = ".$_SESSION["vs_id"];
    pg_query($conn, $v_sql);
    pg_close($conn);
}

// REGISTRANDO QUANDO O USUÁRIO LER A POLÍTICA DE PRIVACIDADE
if ($v_acao == "REGISTRA_ACESSO_POLITICA_PRIVACI") {
    $v_sql = "update db_adm.t_user set dt_lgpd_leu_pol_privaci = current_timestamp where id = ".$_SESSION["vs_id"];
    pg_query($conn, $v_sql);
    pg_close($conn);
}