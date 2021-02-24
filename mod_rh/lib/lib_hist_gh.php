<?php
header("Content-Type: application/json; charset=utf-8");
include_once( "../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);

if($v_acao == 'EV_CARREGAR'){

    if (strpos($_SESSION["vs_array_access"], "T0026") > 0){

        $v_pos = strpos($_SESSION["vs_array_access"], "T0026");
        $v_hist_gh_ler = substr($_SESSION["vs_array_access"],$v_pos+8,1);
        $v_hist_gh_criar = substr($_SESSION["vs_array_access"],$v_pos+12,1);
        $v_hist_gh_gravar = substr($_SESSION["vs_array_access"],$v_pos+16,1);
        $v_hist_gh_excluir = substr($_SESSION["vs_array_access"],$v_pos+20,1);
        // var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

    }

    $v_grupo = addslashes($_POST["v_grupo"]);

    $v_sql = " select gh.id, gh.data_finalizacao, gh.data_troca_lider, gh.id_lider, gh.nome gestao, usu.nome gestor, usu1.nome lider from db_adm.t_rh_funcao_gh gh
            inner join db_adm.t_user usu on usu.id = gh.id_usuario
            inner join db_adm.t_rh_funcao_gh gh1 on gh1.id = gh.id_lider 
            inner join db_adm.t_user usu1 on usu1.id = gh1.id_usuario 
            where (gh.data_finalizacao is not null or gh.data_troca_lider is not null) and gh.id_grupo = {$v_grupo}";
    
    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    while($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Id" => $row["id"],
            "Data_finalizacao" => $row["data_finalizacao"],
            "Data_troca_lider" => $row["data_troca_lider"],
            "Gestao" => $row["gestao"],
            "Gestor" => $row["gestor"],
            "Lider" => $row["lider"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}

if($v_acao == 'EV_VIS_HIST'){

    $v_id = addslashes($_POST["v_id"]);
    $v_grupo = addslashes($_POST["v_grupo"]);

    $v_sql = "select * from db_adm.t_rh_historico_gh hist where hist.id_finalizacao = {$v_id}";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    while($row = pg_fetch_assoc($result)){
        $v_dados[] = array(
            "Funcao" => $row["funcao"],
            "Descricao_hierarquia" => $row["descricao_hierarquia"]
        );
    }

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}