<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);

if($v_acao == 'EV_LISTA_EMPRESAS'){

    $_SESSION["vs_array_access"] = " ";
    $v_sql = "select LPAD(CAST(t_empresas.id AS VARCHAR),4,'0') as id_emp, LPAD(CAST(t_empresas.db_emp AS VARCHAR),4,'0') as db_emp, db_adm.t_empresas.nome from db_adm.t_access 
    join db_adm.t_access_emp_01_grupo_emp on db_adm.t_access.id_grupo_emp = db_adm.t_access_emp_01_grupo_emp.id_grupo_emp
    join db_adm.t_empresas on db_adm.t_access_emp_01_grupo_emp.id_emp = db_adm.t_empresas.id 
    where t_access.id_user = {$_SESSION["vs_id"]}
    group by t_empresas.id, t_empresas.db_emp, t_empresas.nome
    order by t_empresas.nome";

    $v_result_access_empresas = pg_query($conn, $v_sql);

    $v_dados = array();
    while($row = pg_fetch_assoc($v_result_access_empresas)){
        $v_dados[] = array(
            "nome" => $row["nome"],
            "id_emp" => $row["id_emp"],
            "db_emp" => $row["db_emp"],
            "db_emp_atual" => $_SESSION["vs_db_empresa"],
            "id_emp_atual" => $_SESSION["vs_id_empresa"]
        );
        $_SESSION["vs_array_access"] .= "ID_EMP_" . $row["id_emp"] . "|DB_EMP_" . $row["db_emp"] . " ";
    }

    // // ###################################################### //
    // // CARREGANDO A LISTA DE TELAS LIBERADAS
    // // ###################################################### //
    $v_sql = "select LPAD(CAST(t_access_telas_01_grupo_telas.id_tela AS VARCHAR),4,'0') as id_tela, LPAD(CAST(t_access_telas_03_cad_telas.id_modulo AS VARCHAR),4,'0') as id_modulo, t_access_telas_01_grupo_telas.perm_ler, t_access_telas_01_grupo_telas.perm_criar, t_access_telas_01_grupo_telas.perm_gravar, t_access_telas_01_grupo_telas.perm_excluir 
    from db_adm.t_access 
    join db_adm.t_access_telas_01_grupo_telas on db_adm.t_access.id_grupo_telas = db_adm.t_access_telas_01_grupo_telas.id_grupo 
    join db_adm.t_access_telas_03_cad_telas on db_adm.t_access_telas_01_grupo_telas.id_tela = db_adm.t_access_telas_03_cad_telas.id 
    where t_access.id_user = " . $_SESSION["vs_id"] . " 
    group by t_access_telas_01_grupo_telas.id_tela, t_access_telas_03_cad_telas.id_modulo, t_access_telas_01_grupo_telas.perm_ler, t_access_telas_01_grupo_telas.perm_criar, t_access_telas_01_grupo_telas.perm_gravar, t_access_telas_01_grupo_telas.perm_excluir 
    order by t_access_telas_01_grupo_telas.id_tela";

    $v_result_access_telas = pg_query($conn, $v_sql);
    while ($v_row_access_telas = pg_fetch_assoc($v_result_access_telas)) {
        $_SESSION["vs_array_access"] .= "M" . $v_row_access_telas["id_modulo"] . "|T" . $v_row_access_telas["id_tela"] . "|L/" . $v_row_access_telas["perm_ler"] . "|C/" . $v_row_access_telas["perm_criar"] . "|G/" . $v_row_access_telas["perm_gravar"] . "|E/" . $v_row_access_telas["perm_excluir"] . " ";
    }

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}

if($v_acao == 'EV_MUDA_EMPRESA'){

    $v_db_empresa = addslashes($_POST["v_db_empresa"]);
    $v_id_empresa = addslashes($_POST["v_id_empresa"]);

    $_SESSION["vs_db_empresa"] = $v_db_empresa;
    $_SESSION["vs_id_empresa"] = $v_id_empresa;

    $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Acesso alterado com sucesso."}';
    $v_json = json_encode($json_msg);
    echo $v_json;

}