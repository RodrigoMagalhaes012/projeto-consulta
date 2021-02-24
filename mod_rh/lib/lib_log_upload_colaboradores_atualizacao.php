<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_COLABORADORES") {

    if (strpos($_SESSION["vs_array_access"], "T0025") > 0) {

        $v_pos = strpos($_SESSION["vs_array_access"], "T0025");
        $v_competencias_ler = substr($_SESSION["vs_array_access"], $v_pos + 8, 1);
        $v_competencias_criar = substr($_SESSION["vs_array_access"], $v_pos + 12, 1);
        $v_competencias_gravar = substr($_SESSION["vs_array_access"], $v_pos + 16, 1);
        $v_competencias_excluir = substr($_SESSION["vs_array_access"], $v_pos + 20, 1);
        // var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

    }
    // GERANDO A LISTA

    $v_sql = "SELECT hist.data_hora, usu.nome nome_usu     
                    FROM db_adm_rh.t_log as hist
                    join db_adm.t_user usu
                    on usu.id = hist.id_user 
                where hist.id_processo = 2
                and hist.id_empresa = {$_SESSION["vs_id_empresa"]} ";

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
