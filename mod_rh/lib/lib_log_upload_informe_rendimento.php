<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_HISTORICOS") {

    if (strpos($_SESSION["vs_array_access"], "T0025") > 0) {

        $v_pos = strpos($_SESSION["vs_array_access"], "T0025");
        $v_competencias_ler = substr($_SESSION["vs_array_access"], $v_pos + 8, 1);
        $v_competencias_criar = substr($_SESSION["vs_array_access"], $v_pos + 12, 1);
        $v_competencias_gravar = substr($_SESSION["vs_array_access"], $v_pos + 16, 1);
        $v_competencias_excluir = substr($_SESSION["vs_array_access"], $v_pos + 20, 1);
        // var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

    }
    // GERANDO A LISTA

    $v_sql = "SELECT  usu.nome nome_usu,
                log.data_hora 
            FROM db_adm_rh.t_log log
            inner join db_adm.t_user usu
            on usu.id = log.id_user
            where log.id_processo = 4
            and log.id_empresa = {$_SESSION["vs_id_empresa"]}";

    // var_dump($v_sql);

    $result = pg_query($conn, $v_sql);

    $v_dados = array();


    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "nome_usu" => $row["nome_usu"],
            "data_hora" => $row["data_hora"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// SELECIONANDO REGISTRO
if ($v_acao == "EV_SELECT") {

    $v_sql = "SELECT  usu.nome nome_usu,
                      log.data_hora 
                    FROM db_adm_rh.t_log log
                        inner join db_adm.t_user usu
                        on usu.id = log.id_user
                           where log.id_processo = 4
                           and log.id_empresa = {$_SESSION["vs_id_empresa"]}";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "nome_usu" => $row["nome_usu"],
            "data_hora" => $row["data_hora"]
        );
    }
    // var_dump($v_dados);
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}
