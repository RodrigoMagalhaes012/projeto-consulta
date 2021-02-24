<?php
header("Content-Type: application/json; charset=utf-8");
include_once( "../../class/php/class_conect_db_adm.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "EV_SALVAR") {

    $v_num_tentativas = addslashes($_POST["v_num_tentativas"]);
    $v_minut_del_bed_hist = addslashes($_POST["v_minut_del_bed_hist"]);
    $v_num_dias_bloq = addslashes($_POST["v_num_dias_bloq"]);
    $v_dias_validade = addslashes($_POST["v_dias_validade"]);
    $v_min_caract = addslashes($_POST["v_min_caract"]);
    $v_max_caract = addslashes($_POST["v_max_caract"]);
    $v_senha_reutil = addslashes($_POST["v_senha_reutil"]);

    $v_sql = "UPDATE db_emp_" . $_SESSION["vs_db_empresa"] . ".t_politicas_senhas SET \n" .
        "num_tentativas = " . $v_num_tentativas . ", \n" .
        "minut_del_bed_hist = " . $v_minut_del_bed_hist . ", \n" .
        "num_dias_bloq = " . $v_num_dias_bloq . ", \n" .
        "dias_validade = " . $v_dias_validade . ", \n" .
        "min_caract = " . $v_min_caract . ", \n" .
        "max_caract = " . $v_max_caract . ", \n" .
        "senha_reutil = '" . $v_senha_reutil . "'";
    $_SESSION["database_adm"] = "S";

    if (pg_query($conn_adm, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Configuração atualizada com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn_adm);
    $v_json = json_encode($json_msg);
    echo $v_json;
}
