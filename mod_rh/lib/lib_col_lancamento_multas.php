<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);

// GERANDO LISTA DE COLABORADORES
if ($v_acao == "LISTA_COLABORADORES") {

    // GERANDO A LISTA
    $v_sql = "SELECT colab.nome, colab.id_usuario, colab.matricula, colab.id, cargo.nome Cargo, dep.nome Departamento, colab.situacao_colaborador
    FROM db_adm_rh.t_rh_colaborador colab 
         JOIN db_adm.t_empresas as emp 
           ON emp.id = colab.id_empresa 
         JOIN db_adm_rh.t_rh_tabela_departamento as tabDep 
           ON tabDep.id = emp.id_tab_departamentos  
         left JOIN db_adm_rh.t_rh_departamentos dep 
           ON dep.id_tabela = tabDep.id 
          and dep.Id = (select hist_dep.id_departamento 
                          from db_adm_rh.t_rh_hist_departamento hist_dep 
                          where hist_dep.matricula = colab.matricula
                            and hist_dep.id_empresa = colab.id_empresa
                            and hist_dep.data = (select MAX(hist_dep2.data) 
                                                   from db_adm_rh.t_rh_hist_departamento hist_dep2 
                                                   where hist_dep2.matricula = hist_dep.matricula
                                                     AND hist_dep2.id_empresa = hist_dep.id_empresa
                                                     and hist_dep2.data <= current_date)) 
         JOIN db_adm_rh.t_rh_tabela_cargo as tabCar ON tabCar.id  = emp.id_tab_cargos
         JOIN db_adm_rh.t_rh_cargos cargo 
           ON cargo.id_tabela  = tabCar.id           
          AND cargo.Id = (select hist_cargo.id_cargo 
                            from db_adm_rh.t_rh_hist_cargo hist_cargo 
                            where hist_cargo.matricula = colab.matricula
                             and hist_cargo.id_empresa = colab.id_empresa 
                              and hist_cargo.data = (select MAX(hist_cargo2.data) 
                                                       from db_adm_rh.t_rh_hist_cargo hist_cargo2 
                                                       where hist_cargo2.matricula = hist_cargo.matricula 
                                                         and hist_cargo2.id_empresa = hist_cargo.id_empresa 
                                                         and hist_cargo.data <= current_date))
        WHERE colab.id_empresa = {$_SESSION["vs_id_empresa"]}";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Matricula" => $row["matricula"],
            "Id" => $row["id"],
            "Nome" => $row["nome"],
            "Cargo" => $row["cargo"],
            "Departamento" => $row["departamento"],
            "Id_usuario" => $row["id_usuario"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}

if($v_acao == 'SALVA_MULTA'){

    $v_valor = addslashes($_POST["v_valor"]);
    $v_colaborador = addslashes($_POST["v_colaborador"]);
    $v_justificativa = addslashes($_POST["v_justificativa"]);
    $v_id_usuario = addslashes($_POST["v_id_usuario"]);
    $v_competencia = addslashes($_POST["v_competencia"]);
    $v_matricula = addslashes($_POST["v_matricula"]);

    $v_sql = "INSERT INTO db_adm_rh.t_multas
    (id_colaborador, valor, justificativa, id_empresa, matricula, competencia)
    VALUES({$v_colaborador}, {$v_valor}, '{$v_justificativa}', {$_SESSION["vs_id_empresa"]}, '{$v_matricula}', '{$v_competencia}')";

    $result = pg_query($conn, $v_sql);

    if($result){

        $data = explode('-', $v_competencia);
        $data = "{$data[0]}-{$data[1]}";

        $v_sql = "UPDATE db_adm.t_indicador_usuario20211
        SET porcentagem=0, tarefas_atrasadas=NULL, tarefas_concluidas=NULL
        WHERE id_usuario={$v_id_usuario} AND id_indicador=2 AND competencia='{$data}'";

        // pg_query($conn, $v_sql);

        // $v_sql = "INSERT INTO db_adm.t_indicador_usuario20211
        // (id_usuario, id_indicador, competencia, porcentagem)
        // VALUES({$v_id_usuario}, 2, '{$v_competencia}', 0)";

        if(pg_query($conn, $v_sql)){
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Multa salva com sucesso."}';
        }else{
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível salvar os lançamentos, favor tentar novamente!"}';
        }
        
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível salvar os lançamentos, favor tentar novamente!"}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}