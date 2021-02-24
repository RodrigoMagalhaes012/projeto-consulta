<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);






// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_CALCULOS") {

    if (strpos($_SESSION["vs_array_access"], "T0005") > 0) {

        $v_pos = strpos($_SESSION["vs_array_access"], "T0005");
        $v_cad_colab_ler = substr($_SESSION["vs_array_access"], $v_pos + 8, 1);
        $v_cad_colab_criar = substr($_SESSION["vs_array_access"], $v_pos + 12, 1);
        $v_cad_colab_gravar = substr($_SESSION["vs_array_access"], $v_pos + 16, 1);
        $v_cad_colab_excluir = substr($_SESSION["vs_array_access"], $v_pos + 20, 1);
        // var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

    }

    // $v_tab_campo = addslashes($_POST["v_tab_campo"]);
    // $v_tab_ordem = addslashes($_POST["v_tab_ordem"]);
    // $v_tab_busca_campo = explode("|", addslashes($_POST["v_tab_busca_campo"]))[0];
    // $v_tab_busca_campo_tipo = explode("|", addslashes($_POST["v_tab_busca_campo"]))[1];
    // $v_tab_busca_texto = addslashes($_POST["v_tab_busca_texto"]);
    // $v_tab_sql_limit_in = addslashes($_POST["v_tab_sql_limit_in"]);
    // $v_limit = addslashes($_POST["v_limit"]);
    // $v_linhas = 0;

    // CONSTRUINDO OS FILTROS
    $v_filtro = "";
    if (!empty($v_tab_busca_texto)) {

        if ($v_tab_busca_campo_tipo == "txt") {
            $v_filtro = "WHERE " . $v_tab_busca_campo . " like '%" . $v_tab_busca_texto . "%'";
        } else {
            $v_filtro = "WHERE " . $v_tab_busca_campo . " = " . $v_tab_busca_texto;
        }
        // var_dump("$v_filtro");
    }

    // CONTANDO REGISTROS PARA GERAR OS BOTÕES DE NAVEGAÇÃO
    $v_sql = "SELECT count(id) as linhas from db_adm_rh.t_rh_colaborador " . $v_filtro;
    if ($result = pg_query($conn, $v_sql)) {
        $row = pg_fetch_assoc($result);
        $v_linhas = $row["linhas"];
    }

    // GERANDO A LISTA
    $v_sql = "SELECT competencia, tipo_folha, status, id
    FROM db_adm_rh.t_rh_lancamento_calculo ORDER BY competencia asc;";

    // var_dump($v_sql);
    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    $v_dados[] = array("linhas" => $v_linhas);
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("competencia" => $row["competencia"], "id" => $row["id"], "tipo_folha" => $row["tipo_folha"], "status" => $row["status"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}

















// GERANDO LISTA DE EMPRESAS
// if ($v_acao == "LISTAR_USUARIOS") {

//     if (strpos($_SESSION["vs_array_access"], "T0005") > 0) {

//         $v_pos = strpos($_SESSION["vs_array_access"], "T0005");
//         $v_cad_colab_ler = substr($_SESSION["vs_array_access"], $v_pos + 8, 1);
//         $v_cad_colab_criar = substr($_SESSION["vs_array_access"], $v_pos + 12, 1);
//         $v_cad_colab_gravar = substr($_SESSION["vs_array_access"], $v_pos + 16, 1);
//         $v_cad_colab_excluir = substr($_SESSION["vs_array_access"], $v_pos + 20, 1);
//         // var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

//     }

//     // CONSTRUINDO OS FILTROS
//     $v_filtro = "";
//     if (!empty($v_tab_busca_texto)) {

//         if ($v_tab_busca_campo_tipo == "txt") {
//             $v_filtro = "WHERE " . $v_tab_busca_campo . " like '%" . $v_tab_busca_texto . "%'";
//         } else {
//             $v_filtro = "WHERE " . $v_tab_busca_campo . " = " . $v_tab_busca_texto;
//         }
//         var_dump($v_filtro);
//     }

//     // CONTANDO REGISTROS PARA GERAR OS BOTÕES DE NAVEGAÇÃO
//     $v_sql = "SELECT count(matricula) as linhas from db_emp_" . $_SESSION["vs_db_empresa"] . ".t_rh_colaborador " . $v_filtro;
//     if ($result = pg_query($conn, $v_sql)) {
//         $row = pg_fetch_assoc($result);
//         $v_linhas = $row["linhas"];
//     }

//     // GERANDO A LISTA
//     $v_sql = "SELECT nome, matricula,  id
// 	FROM db_emp_" . $_SESSION["vs_db_empresa"] . ".t_rh_colaborador";

//     // var_dump($v_sql);

//     $result = pg_query($conn, $v_sql);

//     $v_dados = array();

//     $v_dados[] = array("linhas" => $v_linhas);
//     while ($row = pg_fetch_assoc($result)) {
//         $v_dados[] = array("Matricula" => $row["matricula"], "Id" => $row["id"], "Nome" => $row["nome"]);
//     }

//     // ENVIANDO DADOS
//     pg_close($conn);
//     $v_json = json_encode($v_dados);
//     echo $v_json;
// }



// GERANDO LISTA DE RUBRICAS
if ($v_acao == "LISTA_RUBRICA") {

    $v_sql = "SELECT rubrica, descricao FROM db_adm.t_rh_holerite_rubricas ORDER BY rubrica";
    $result = pg_query($conn, $v_sql);

    // var_dump($v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("rubrica" => $row["rubrica"], "descricao" => $row["descricao"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// SALVANDO LANÇAMENTOS
if ($v_acao == "EV_SALVAR") {

    $v_tipo_folha = addslashes($_POST["v_tipo_folha"]);
    $v_competencia = addslashes($_POST["v_competencia"]);
    $v_status = addslashes($_POST["v_status"]);
    // $v_id_tabela_rubrica = addslashes($_POST["v_id_tabela_rubrica"]);


    $v_sql = "INSERT INTO db_adm_rh.t_rh_lancamento_calculo
    (id_empresa, competencia, tipo_folha, status)
    VALUES({$_SESSION["vs_id_empresa"]}, '{$v_competencia}', {$v_tipo_folha}, {$v_status});";
    // var_dump($v_sql);
    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

// SALVANDO LANÇAMENTOS
if ($v_acao == "EV_ATUALIZA") {

    $v_tipo_folha = addslashes($_POST["v_tipo_folha"]);
    $v_competencia = addslashes($_POST["v_competencia"]);
    $v_status = addslashes($_POST["v_status"]);
    $v_id = addslashes($_POST["v_id"]);
    // $v_id_tabela_rubrica = addslashes($_POST["v_id_tabela_rubrica"]);

    $v_sql = "UPDATE db_adm_rh.t_rh_lancamento_calculo
                SET status={$v_status}
                    WHERE id_empresa={$_SESSION["vs_id_empresa"]} AND competencia='{$v_competencia}' AND tipo_folha= {$v_tipo_folha} AND id = {$v_id};";
    // var_dump($v_sql);
    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Status alterado com sucesso!"}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível alterar o status, favor tentar novamente."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}


// GERANDO LISTA DE RUBRICAS
if ($v_acao == "EV_SELECT") {

    $v_id = addslashes($_POST["v_id"]);


    $v_sql = "SELECT competencia, tipo_folha, status, id
    FROM db_adm_rh.t_rh_lancamento_calculo WHERE id = {$v_id};";
    $result = pg_query($conn, $v_sql);
    // var_dump($v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "id" => $row["id"],
            "competencia" => $row["competencia"],
            "tipo_folha" => $row["tipo_folha"],
            "status" => $row["status"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}







// GERANDO LISTA DE RUBRICAS
if ($v_acao == "LISTAR_CALCULO") {

    $v_sql = "SELECT id, competencia, tipo_folha
                    FROM db_adm_rh.t_rh_lancamento_calculo ORDER BY  id desc;";
    $result = pg_query($conn, $v_sql);

    // var_dump($v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "id" => $row["id"],
            "competencia" => $row["competencia"],
            "tipo_folha" => $row["tipo_folha"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}




// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_EMPRESAS") {

    $v_sql = "SELECT t_empresas.cnpj, t_empresas.db_emp, t_empresas.nome from db_adm.t_access 
    join db_adm.t_access_emp_01_grupo_emp on db_adm.t_access.id_grupo_emp = db_adm.t_access_emp_01_grupo_emp.id_grupo_emp
    join db_adm.t_empresas on db_adm.t_access_emp_01_grupo_emp.id_emp = db_adm.t_empresas.id 
    where t_access.id_user = {$_SESSION["vs_id"]} 
    group by t_empresas.cnpj, t_empresas.db_emp, t_empresas.nome
    order by t_empresas.nome";
    $result = pg_query($conn, $v_sql);

    $v_dados = '[';
    while ($row = pg_fetch_assoc($result)) {

        $cpf_cnpj = $row["cnpj"];
        if (strlen($row["cnpj"]) > 11) {
            $cpf_cnpj = str_pad($cpf_cnpj, 14, '0', STR_PAD_LEFT);
            $bloco_1 = substr($cpf_cnpj, 0, 2);
            $bloco_2 = substr($cpf_cnpj, 2, 3);
            $bloco_3 = substr($cpf_cnpj, 5, 3);
            $bloco_4 = substr($cpf_cnpj, 8, 4);
            $digito_verificador = substr($cpf_cnpj, -2);
            $cpf_cnpj = $bloco_1 . "." . $bloco_2 . "." . $bloco_3 . "/" . $bloco_4 . "-" . $digito_verificador;
        } else {
            $cpf_cnpj = str_pad($cpf_cnpj, 11, '0', STR_PAD_LEFT);
            $bloco_1 = substr($cpf_cnpj, 0, 3);
            $bloco_2 = substr($cpf_cnpj, 3, 3);
            $bloco_3 = substr($cpf_cnpj, 6, 3);
            $dig_verificador = substr($cpf_cnpj, -2);
            $cpf_cnpj = $bloco_1 . "." . $bloco_2 . "." . $bloco_3 . "-" . $dig_verificador;
        }

        $v_dados .= '{"cnpj":"' . ($row["cnpj"] . "|" . $row["db_emp"])
            . '","nome":"' . ($row["nome"] . " - " . $cpf_cnpj)
            . '"},';
    }

    $v_dados = substr($v_dados, 0, -1) . ']';
    echo $v_dados;
}


if ($v_acao == 'FILTRAR_COLAB') {

    $v_competencia = addslashes($_POST["v_competencia"]);
    $v_tipo_folha = addslashes($_POST["v_tipo_folha"]);

    $v_sql = "SELECT col.nome nome_col, col.matricula matricula_col, col.id id_col
	FROM db_adm_rh.t_rh_colaborador as col
	join db_adm_rh.t_rh_lancamento as lanc on lanc.id_colaborador = col.id 
    where lanc.competencia = '{$v_competencia}' and tipo_folha = {$v_tipo_folha}";

    var_dump($v_sql);
    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "nome_col" => $row["nome_col"],
            "matricula_col" => $row["matricula_col"],
            "id_col" => $row["id_col"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}
