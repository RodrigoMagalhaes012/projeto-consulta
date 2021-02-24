<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);
$linha = "";





// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_USUARIOS") {

    if (strpos($_SESSION["vs_array_access"], "T0005") > 0) {

        $v_pos = strpos($_SESSION["vs_array_access"], "T0005");
        $v_cad_colab_ler = substr($_SESSION["vs_array_access"], $v_pos + 8, 1);
        $v_cad_colab_criar = substr($_SESSION["vs_array_access"], $v_pos + 12, 1);
        $v_cad_colab_gravar = substr($_SESSION["vs_array_access"], $v_pos + 16, 1);
        $v_cad_colab_excluir = substr($_SESSION["vs_array_access"], $v_pos + 20, 1);
        // var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

    }

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
    $v_sql = "SELECT count(matricula) as linhas from db_emp_" . $_SESSION["vs_db_empresa"] . ".t_rh_colaborador " . $v_filtro;
    if ($result = pg_query($conn, $v_sql)) {
        $row = pg_fetch_assoc($result);
        $v_linhas = $row["linhas"];
    }



    $v_competencia_cal = addslashes($_POST["v_competencia_cal"]);

    // GERANDO A LISTA
    $v_sql = "SELECT colab.nome, colab.matricula, colab.id, cargo.nome Cargo, dep.nome Departamento
                     FROM db_adm_rh.t_rh_colaborador colab
            LEFT jOIN db_adm.t_rh_departamentos dep
                  ON dep.Id = (select hist_dep.id_departamento
                                  from db_adm_rh.t_rh_hist_departamento hist_dep
                                  where hist_dep.matricula = colab.matricula
                                    and hist_dep.id_empresa = colab.id_empresa
                                    and hist_dep.data = (select MAX(hist_dep2.data)
                                                             from db_adm_rh.t_rh_hist_departamento hist_dep2
                                                             where hist_dep2.matricula = hist_dep.matricula
                                                                 and hist_dep2.id_empresa = hist_dep.id_empresa
                                                                 and hist_dep2.data <= current_date))
            LEFT JOIN db_adm_rh.t_rh_cargos cargo
                    ON cargo.Id = (select hist_cargo.id_cargo
                                    from db_adm_rh.t_rh_hist_cargo hist_cargo
                                    where hist_cargo.matricula = colab.matricula
                                        and hist_cargo.id_empresa = colab.id_empresa
                                        and hist_cargo.data = (select MAX(hist_cargo2.data)
                                                                from db_adm_rh.t_rh_hist_cargo hist_cargo2
                                                                where hist_cargo2.matricula = hist_cargo.matricula
                                                                    and hist_cargo2.id_empresa = hist_cargo.id_empresa
                                                                    and hist_cargo.data <= current_date))
                                                                        where colab.data_demissao > '{$v_competencia_cal}' or colab.data_demissao is null
            and id_empresa = " . $_SESSION["vs_id_empresa"];

    // var_dump($v_sql);
    // die;
    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    $v_dados[] = array("linhas" => $v_linhas);
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Matricula" => $row["matricula"],
            "Id" => $row["id"],
            "Nome" => $row["nome"],
            "Cargo" => $row["cargo"],
            "Departamento" => $row["departamento"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// GERANDO LISTA DE RUBRICAS
if ($v_acao == "LISTA_RUBRICA") {

    $v_index = addslashes($_POST["v_index"]);
    $v_acao_lista = addslashes($_POST["v_acao_lista"]);
    $v_filtro = " ";

    if ($v_acao_lista == "ADD") {
        $v_filtro = " caracteristica > 0 ";
    } else {
        $v_filtro = " caracteristica > 0 ";
    }

    // $v_sql = "SELECT rubrica, case when (tipo_lancamento = 0) then concat(descricao, ' (R$)') else concat(descricao, ' (HR)') end as descricao FROM db_adm_rh.t_rh_holerite_rubricas" . $v_filtro . "ORDER BY rubrica";

    $v_sql = "SELECT rubricas.rubrica, case when (rubricas.tipo_lancamento = 0) then concat(rubricas.descricao, ' (R$)') else concat(rubricas.descricao, ' (HR)') end as descricao 
                FROM db_adm_rh.t_rh_holerite_rubricas rubricas, db_adm.t_empresas emp  
               where {$v_filtro} 
                 and rubricas.id_tabela = emp.id_tab_rubricas  
                 and emp.id = {$_SESSION["vs_id_empresa"]}
              ORDER BY rubricas.descricao";
    // var_dump($v_sql);
    // die;
    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "rubrica" => $row["rubrica"],
            "descricao" => $row["descricao"],
            "v_index" => $v_index
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}

// SALVANDO LANÇAMENTOS E REALIZANDO UPDATE
if ($v_acao == "EV_SALVAR") {

    // print_r("teste zzzzzzzzzzzzzz");
    $v_lancamentos = "";
    $v_sql_front_up = addslashes(substr($_POST["v_sql_up"], 0, -1));
    $v_sql_front_up = str_replace("criptografado_0", "UPDATE", $v_sql_front_up);
    $v_sql_front_up = str_replace("criptografado_4", "SET", $v_sql_front_up);
    $v_sql_front_up = str_replace("criptografado_1", "db_emp", $v_sql_front_up);
    $v_sql_front_up = str_replace("v_id_empresa", $_SESSION["vs_id_empresa"], $v_sql_front_up);
    $v_sql_front_up = str_replace("criptografado_3", "t_rh_lancamento", $v_sql_front_up);
    $v_sql_front_up = str_replace("criptografado_2", $_SESSION["vs_db_empresa"], $v_sql_front_up);
    $v_sql_front_up = str_replace('\\', '', $v_sql_front_up);

    $v_sql_front = addslashes(substr($_POST["v_sql"], 0, -1));
    $v_sql_front = str_replace("v_id_empresa", $_SESSION["vs_id_empresa"], $v_sql_front);
    $v_sql_front = str_replace('\\', '', $v_sql_front);

    if ($v_sql_front >=  0) {

        $v_lancamentos = " VALUES " . substr($v_sql_front, 0, -1) . " ON CONFLICT ON CONSTRAINT t_rh_lancamento_pk DO NOTHING" . ";";
        $v_sql = "INSERT INTO db_adm_rh.t_rh_lancamento
                        (id_empresa, matricula, rubrica, referencia, valor, id_tabela_rubrica, id_calculo) " . $v_lancamentos;
        // var_dump($v_sql);
        if (pg_query($conn, $v_sql)) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível salvar os lançamentos, favor tentar novamente!"}';
        }
    }

    if ($v_sql_front_up >= 0) {

        $v_sql =  $v_sql_front_up;
        // var_dump($v_sql);
        // print_r("update\n\n\n\n");

        if (pg_query($conn, $v_sql)) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível salvar os lançamentos, favor tentar novamente!"}';
        }
    }
    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}


// // SALVANDO LANÇAMENTOS
// if ($v_acao == "EV_SALVAR") {

//     $v_lancamentos = "";
//     $v_sql_front = addslashes(substr($_POST["v_sql"], 0, -1));
//     $v_sql_front = str_replace("v_id_empresa", $_SESSION["vs_id_empresa"], $v_sql_front);
//     $v_sql_front = str_replace('\\', '', $v_sql_front);

//     $v_lancamentos = " VALUES " . substr($v_sql_front, 0, -1) . ";";
//     $v_sql = "INSERT INTO db_emp_" . $_SESSION["vs_db_empresa"] . ".t_rh_lancamento
//     (id_empresa, matricula, rubrica, referencia, valor, id_tabela_rubrica, id_calculo) " . $v_lancamentos;

//     // var_dump($v_sql);

//     if (pg_query($conn, $v_sql)) {
//         $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
//     } else {
//         $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível salvar os lançamentos, favor tentar novamente!"}';
//     }

//     pg_close($conn);
//     $v_json = json_encode($json_msg);
//     echo $v_json;
// }






// FINALIZA STATUS DE LANÇAMENTOS DA COMPETENCIA DO CALCULO
if ($v_acao == "ALTERA_STATUS") {

    $v_id_calc = addslashes($_POST["v_id_calc"]);

    if ($v_id_calc == "") {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Selecione um cálculo para finalizar."}';
    } else {

        $v_sql = "UPDATE db_adm_rh.t_rh_lancamento_calculo
                    SET status= 1
                        WHERE id_empresa= {$_SESSION["vs_id_empresa"]} AND id= {$v_id_calc};";

        // var_dump($v_sql);

        if (pg_query($conn, $v_sql)) {
            $json_msg = '{"msg_titulo":"Finalizado com sucesso!", "msg_ev":"success", "msg":"Aguarde a integração ser realizada pelo administrador da folha!."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível finalizar os lançamentos dessa competencia, favor tentar novamente!"}';
        }
    }
    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

// SOLICITA ABERTURA DO CALCULO

if ($v_acao == "ALTERA_STATUS_ABERTURA") {

    $v_id_calc = addslashes($_POST["v_id_calc"]);

    if ($v_id_calc == "") {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Selecione um cálculo."}';
    } else {

        $v_sql = "UPDATE db_adm_rh.t_rh_lancamento_calculo
                    SET status= 4
                        WHERE id_empresa= {$_SESSION["vs_id_empresa"]} AND id= {$v_id_calc};";

        // var_dump($v_sql);

        if (pg_query($conn, $v_sql)) {
            $json_msg = '{"msg_titulo":"Solicitação realizada com Sucesso!", "msg_ev":"success", "msg":"Aguarde a liberação do cálculo!."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível finalizar os lançamentos dessa competencia, favor tentar novamente!"}';
        }
    }
    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}







// GERANDO LISTA DE RUBRICAS
if ($v_acao == "EV_SELECT") {

    $v_id = addslashes($_POST["v_id"]);
    $v_mtr_col = addslashes($_POST["v_matricula"]);
    $v_id_calc = addslashes($_POST["v_id_calc"]);


    $v_sql = "SELECT nome, id, matricula FROM db_adm_rh.t_rh_colaborador 
               WHERE matricula = '{$v_mtr_col}'
                and id_empresa = '{$_SESSION["vs_id_empresa"]}'";
    $result = pg_query($conn, $v_sql);

    // var_dump($v_sql);

    $v_dados = array();
    if ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "id" => $row["id"],
            "nome" => $row["nome"],
            "matricula" => $row["matricula"]
        );
    }

    $v_sql = "SELECT lanc.matricula, lanc.referencia, lanc.valor, lanc.rubrica, case when (rub.tipo_lancamento = 0) then 'R$' else  'HR' end as tipo, lanc.id id_lancamento 
                FROM db_adm_rh.t_rh_lancamento lanc 
                    INNER JOIN db_adm_rh.t_rh_holerite_rubricas AS rub ON rub.rubrica = lanc.rubrica 
                    INNER JOIN db_adm_rh.t_rh_lancamento_calculo AS cal 
                       ON lanc.id_calculo = cal.id 
                      AND lanc.id_empresa = {$_SESSION["vs_id_empresa"]}
                    INNER JOIN db_adm_rh.t_rh_holerite_tipo_folha AS tipFol ON cal.tipo_Folha = tipFol.Id 
                    INNER JOIN db_adm_rh.t_rh_holerite_tipo_rubrica AS tipRub ON rub.tipo = tipRub.Id 
                    inner join db_adm_rh.t_rh_colaborador  as colab
                       on colab.matricula = lanc.matricula 
                       AND colab.id_empresa = lanc.id_empresa
               WHERE lanc.id_calculo  = {$v_id_calc}
                 AND lanc.id_empresa = {$_SESSION["vs_id_empresa"]}
                 AND lanc.matricula =  '{$v_mtr_col}'";

    $result = pg_query($conn, $v_sql);

    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "matricula" => $row["matricula"],
            "referencia" => $row["referencia"],
            "id_lancamento" => $row["id_lancamento"],
            "valor" => $row["valor"],
            "tipo" => $row["tipo"],
            "rubrica" => $row["rubrica"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}




// GERANDO LISTA DE RUBRICAS
if ($v_acao == "CARREGA_CALC") {

    $v_id_calc = addslashes($_POST["v_id_calc"]);

    $v_sql = "SELECT competencia, tipo_folha, id, status
    FROM db_adm_rh.t_rh_lancamento_calculo
    WHERE id={$v_id_calc} 
      AND id_empresa = {$_SESSION["vs_id_empresa"]}";
    $result = pg_query($conn, $v_sql);

    // var_dump($v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "id" => $row["id"],
            "status" => $row["status"],
            "competencia" => $row["competencia"],
            "tipo_folha" => $row["tipo_folha"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

    // var_dump($v_json);
}


if ($v_acao == "CONSULTA_VARIAVEIS") {

    $v_id_calc = addslashes($_POST["v_id_calc"]);
    $v_mtr_col = addslashes($_POST["v_mtr_col"]);

    $v_sql = "SELECT lanc.matricula, lanc.referencia, lanc.valor, lanc.rubrica 
                FROM db_adm_rh.t_rh_lancamento lanc 
                    INNER JOIN db_adm_rh.t_rh_holerite_rubricas AS rub 
                            ON rub.rubrica = lanc.rubrica 
                           AND rub.id_tabela = lanc.id_tabela_rubrica
                    INNER JOIN db_adm_rh.t_rh_lancamento_calculo AS cal 
                            ON lanc.id_calculo = cal.id 
                           AND lanc.id_empresa = cal.id_empresa
                    INNER JOIN db_adm_rh.t_rh_holerite_tipo_folha AS tipFol ON cal.tipo_Folha = tipFol.Id 
                    INNER JOIN db_adm_rh.t_rh_holerite_tipo_rubrica AS tipRub ON rub.tipo = tipRub.Id 
                    inner join db_adm_rh.t_rh_colaborador  as colab
                            on colab.matricula = lanc.matricula 
                           AND colab.id_empresa = lanc.id_empresa 
               WHERE lanc.id_calculo  = {$v_id_calc}
                 AND lanc.id_empresa = {$_SESSION["vs_id_empresa"]}
                 AND lanc.matricula =  '{$v_mtr_col}'";

    $result = pg_query($conn, $v_sql);

    $v_dados[] = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "matricula" => $row["matricula"],
            "referencia" => $row["referencia"],
            "valor" => $row["valor"],
            "rubrica" => $row["rubrica"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}






// GERANDO LISTA DE CALCULOS
if ($v_acao == "LISTAR_CALCULO") {

    $v_sql = "SELECT id, competencia, tipo_folha, status
                    FROM db_adm_rh.t_rh_lancamento_calculo calc
                   WHERE calc.id_empresa = {$_SESSION["vs_id_empresa"]}
               ORDER BY  id desc;";
    $result = pg_query($conn, $v_sql);

    // var_dump($v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "id" => $row["id"],
            "competencia" => $row["competencia"],
            "status" => $row["status"],
            "tipo_folha" => $row["tipo_folha"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// GERANDO LISTA DE CALCULOS
if ($v_acao == "SELECT_CALCULO") {

    $v_id_calc = addslashes($_POST["v_id_calc"]);

    $v_sql = "SELECT competencia 
                    FROM db_adm_rh.t_rh_lancamento_calculo 
                            where id = {$v_id_calc}
                              and id_empresa =  {$_SESSION["vs_id_empresa"]}
                                 ORDER BY  id desc;";
    $result = pg_query($conn, $v_sql);

    // var_dump($v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "competencia" => $row["competencia"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// SALVANDO LANÇAMENTOS
if ($v_acao == "EV_EXCLUIR") {

    $v_id_lanc = addslashes($_POST["v_id_lanc"]);


    $v_sql = "DELETE FROM db_adm_rh.t_rh_lancamento
               WHERE id_empresa={$_SESSION["vs_id_empresa"]} AND id={$v_id_lanc};";

    // var_dump($v_sql);

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro excluido com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível excluir o lançamento os lançamentos, favor tentar novamente!"}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}


// CRIANDO CALCULO DA FOLHA 
if ($v_acao == "EV_SALVAR_CALC") {

    $v_tipo_folha = addslashes($_POST["v_tipo_folha"]);
    $v_competencia = addslashes($_POST["v_competencia"]);

    $v_sql = "INSERT INTO db_adm_rh.t_rh_lancamento_calculo
                 (id_empresa, competencia, tipo_folha, status)
                     VALUES({$_SESSION["vs_id_empresa"]}, '{$v_competencia}', {$v_tipo_folha}, 0);";
    // var_dump($v_sql);
    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível criar o cálculo, favor verificar."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}
