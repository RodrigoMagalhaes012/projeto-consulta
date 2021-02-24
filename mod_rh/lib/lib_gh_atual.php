<?php
header("Content-Type: application/json; charset=utf-8");
include_once( "../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);

if($v_acao == 'EV_CARREGAR'){

    if (strpos($_SESSION["vs_array_access"], "T0024") > 0){

        $v_pos = strpos($_SESSION["vs_array_access"], "T0024");
        $v_gh_atual_ler = substr($_SESSION["vs_array_access"],$v_pos+8,1);
        $v_gh_atual_criar = substr($_SESSION["vs_array_access"],$v_pos+12,1);
        $v_gh_atual_gravar = substr($_SESSION["vs_array_access"],$v_pos+16,1);
        $v_gh_atual_excluir = substr($_SESSION["vs_array_access"],$v_pos+20,1);
        // var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

    }

    $v_grupo = addslashes($_POST["v_grupo"]);

    $v_sql = "WITH RECURSIVE hierarquia AS (
        SELECT
            gh.id_usuario ,
            gh.id_lider ,
            gh.nome,
            gh.id,
            gh.id_grupo
        FROM
            db_adm.t_rh_funcao_gh gh
        where gh.data_finalizacao is null and gh.data_troca_lider is null and gh.id_usuario is not null
        UNION
            SELECT
                gh1.id_usuario ,
                gh1.id_lider ,
                gh1.nome,
                gh1.id,
                gh1.id_grupo
            FROM
                db_adm.t_rh_funcao_gh gh1
            INNER JOIN hierarquia h ON h.id = gh1.id_lider
    ) SELECT
        hierarquia.id_usuario ,
        hierarquia.id_lider ,
        hierarquia.nome gestao,
        hierarquia.id,
        usu.nome gestor,
        usu1.nome lider
    FROM
        hierarquia
        join db_adm.t_user usu on usu.id = hierarquia.id_usuario
        join db_adm.t_rh_funcao_gh gh2 on hierarquia.id_lider = gh2.id
        left join db_adm.t_user usu1 on usu1.id = gh2.id_usuario
    where hierarquia.id_grupo = {$v_grupo}";
    
    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    while($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Id" => $row["id"],
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
    // $v_grupo = addslashes($_POST["v_grupo"]);

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