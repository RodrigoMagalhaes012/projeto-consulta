<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);

//ARRAY PARA BUSCAR MATRICULA
$v_sql = "SELECT col.matricula matricula 
                from db_adm_rh.t_rh_colaborador col
                where col.id_usuario = {$_SESSION["vs_id"]}";
$result10 = pg_query($conn, $v_sql);
$v_dados = array();
if ($row = pg_fetch_assoc($result10)) {
    $v_dados[] = array(
        "matricula" => $row["matricula"]
    );
    $v_matricula = $row["matricula"];
}

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
    $v_sql = "SELECT count(matricula) as linhas from db_adm_rh.t_rh_colaborador " . $v_filtro;
    if ($result = pg_query($conn, $v_sql)) {
        $row = pg_fetch_assoc($result);
        $v_linhas = $row["linhas"];
    }

    // GERANDO A LISTA
    $v_sql = "SELECT colab.nome, colab.matricula, colab.id, cargo.nome Cargo, dep.nome Departamento, emp.id_tab_rubricas, emp.id_tab_cargos, emp.id_tab_departamentos, colab.situacao_colaborador
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
                                                           and hist_cargo2.data <= current_date))
          WHERE colab.id_empresa = " . intval($_SESSION["vs_id_empresa"]);
    $result = pg_query($conn, $v_sql);

    // var_dump($v_sql);    
    //die;

    $v_dados = array();

    $v_dados[] = array("linhas" => $v_linhas);
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Matricula" => $row["matricula"],
            "Id" => $row["id"],
            "Nome" => $row["nome"],
            "Cargo" => $row["cargo"],
            "Situacao" => $row["situacao_colaborador"],
            "Departamento" => $row["departamento"]
            // "id_tab_rubricas" => $row["id_tab_rubricas"],
            // "id_tab_cargos" => $row["id_tab_cargos"],
            // "id_tab_departamentos" => $row["id_tab_departamentos"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// GERANDO LISTA DE CARGOS  
if ($v_acao == "LISTA_CARGO") {
    $v_sql = "SELECT Id, Nome Cargo FROM db_adm_rh.t_rh_cargos ORDER BY Nome";


    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Id" => $row["id"],
            "Cargo" => $row["cargo"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// GERANDO LISTA DE DEPARTAMENTOS
if ($v_acao == "LISTA_DPTO") {

    $v_sql = "SELECT Id, Nome Departamento FROM db_adm_rh.t_rh_departamentos ORDER BY Nome";
    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Id" => $row["id"],
            "Departamento" => $row["departamento"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}

//LISTAGEM DE DADOS NECESSARIOS PARA O CADASTRO DE NOVO COLABORADOR
if ($v_acao == "LISTA_DADOS_NEC") {

    $v_sql_dep = "SELECT Id, Nome Departamento FROM db_adm_rh.t_rh_departamentos ORDER BY Nome";

    $result_dep = pg_query($conn, $v_sql_dep);

    $v_sql_cargos = "SELECT Id, Nome Cargo FROM db_adm_rh.t_rh_cargos ORDER BY Nome";
    $result_cargos = pg_query($conn, $v_sql_cargos);

    $v_sql_sexo = "SELECT id, Descricao Sexo FROM db_adm_rh.t_rh_sexo ORDER BY Descricao";
    $result_sexo = pg_query($conn, $v_sql_sexo);

    $v_sql_contrato = "SELECT id, tipo_contrato FROM db_adm_rh.t_rh_tipo_contrato";
    $result_contrato = pg_query($conn, $v_sql_contrato);

    $v_sql_escolaridade = "SELECT id, escolaridade FROM db_adm_rh.t_rh_escolaridade";
    $result_escolaridade = pg_query($conn, $v_sql_escolaridade);

    $v_sql_est_civil = "SELECT id, estado_civil FROM db_adm_rh.t_rh_estado_civil";
    $result_est_civil = pg_query($conn, $v_sql_est_civil);

    $v_sql_pais = "SELECT id, pais FROM db_adm.t_paises";
    $result_pais = pg_query($conn, $v_sql_pais);

    $v_sql_banco = "SELECT codigo, nome_extenso FROM db_adm.t_bancos ORDER BY nome_extenso";
    $result_banco = pg_query($conn, $v_sql_banco);

    $v_sql_gh = "SELECT id, nome gh_nome FROM db_adm.t_rh_funcao_gh where data_finalizacao is null";
    $result_gh = pg_query($conn, $v_sql_gh);

    $v_sql_tipo_contrato = "SELECT id, tipo_contrato FROM db_adm_rh.t_rh_tipo_contrato";
    $result_tipo_contrato = pg_query($conn, $v_sql_tipo_contrato);


    $v_dados_dep = array();
    while ($row = pg_fetch_assoc($result_dep)) {
        $v_dados_dep[] = array(
            "Id_dep" => $row["id"],
            "Departamento" => $row["departamento"]
        );
    }

    $v_dados_cargos = array();
    while ($row = pg_fetch_assoc($result_cargos)) {
        $v_dados_cargos[] = array(
            "Id_cargo" => $row["id"],
            "Cargo" => $row["cargo"]
        );
    }

    $v_dados_sexo = array();
    while ($row = pg_fetch_assoc($result_sexo)) {
        $v_dados_sexo[] = array(
            "Id_sexo" => $row["id"],
            "Sexo" => $row["sexo"]
        );
    }

    $v_dados_contrato = array();
    while ($row = pg_fetch_assoc($result_contrato)) {
        $v_dados_contrato[] = array(
            "Id_contrato" => $row["id"],
            "Tipo_contrato" => $row["tipo_contrato"]
        );
    }

    $v_dados_escolaridade = array();
    while ($row = pg_fetch_assoc($result_escolaridade)) {
        $v_dados_escolaridade[] = array(
            "Id_escolaridade" => $row["id"],
            "Escolaridade" => $row["escolaridade"]
        );
    }

    $v_dados_est_civil = array();
    while ($row = pg_fetch_assoc($result_est_civil)) {
        $v_dados_est_civil[] = array(
            "Id_est_civil" => $row["id"],
            "Estado_civil" => $row["estado_civil"]
        );
    }

    $v_dados_paises = array();
    while ($row = pg_fetch_assoc($result_pais)) {
        $v_dados_paises[] = array(
            "Id_pais" => $row["id"],
            "Pais" => $row["pais"]
        );
    }

    $v_dados_bancos = array();
    while ($row = pg_fetch_assoc($result_banco)) {
        $v_dados_bancos[] = array(
            "Codigo" => $row["codigo"],
            "Nome" => $row["nome_extenso"]
        );
    }

    $v_dados_gh = array();
    while ($row = pg_fetch_assoc($result_gh)) {
        $v_dados_gh[] = array(
            "Id" => $row["id"],
            "Nome" => $row["gh_nome"]
        );
    }

    $v_dados_tipo_contrato = array();
    while ($row = pg_fetch_assoc($result_tipo_contrato)) {
        $v_dados_tipo_contrato[] = array(
            "Id" => $row["id"],
            "Tipo_contrato" => $row["tipo_contrato"]
        );
    }

    $v_dados = array(
        "Cargos" => $v_dados_cargos,
        "Departamentos" => $v_dados_dep,
        "Sexos" => $v_dados_sexo,
        "Contratos" => $v_dados_contrato,
        "Escolaridades" => $v_dados_escolaridade,
        "Estado_civil" => $v_dados_est_civil,
        "Paises" => $v_dados_paises,
        "Bancos" => $v_dados_bancos,
        "Gh" => $v_dados_gh,
        "Tipos_contrato" => $v_dados_tipo_contrato
    );

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    // var_dump($v_json);
    echo $v_json;
}


// GERANDO LISTA DE GH
if ($v_acao == "LISTA_GH") {

    $v_sql = "SELECT Id, nome gh_nome FROM db_adm.t_rh_funcao_gh where data_finalizacao is null ORDER BY gh_nome";
    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("Id" => $row["id"], "Gh" => $row["gh_nome"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}

// SELECIONANDO REGISTRO
if ($v_acao == "EV_SELECT") {

    $v_id = addslashes($_POST["v_id"]);
    $v_matricula = addslashes($_POST["v_matricula"]);

    $v_sql = "SELECT  colab.id id_colab, colab.Nome Colaborador, colab.Cpf, colab.Orgao_expedidor, colab.dat_expedicao, dep.nome departamento_nome, colab.id_usuario usuario,
     cargo.nome cargo_nome, HIS_SAL.salario salario, db_adm_rh.t_rh_escolaridade.escolaridade escolaridade, 
     db_adm_rh.t_rh_estado_civil.estado_civil estado_civil, db_adm_rh.t_rh_tipo_contrato.tipo_contrato tipo_contrato, 
     pais_nascimento.id pais_nascimento, colab.Email, colab.Celular, tipo_sexo.descricao,colab.id_sexo, colab.id_Est_Civil, 
     colab.id_Escolaridade, colab.Pne, colab.Cnh_vencimento, colab.Ctps_num, colab.Ctps_serie, colab.Data_admissao, colab.Matricula, 
     colab.Tipo_contrato, colab.Duracao_contrato, colab.prorrogacao_contrato, colab.Periodo_experiencia, colab.Cep, colab.Logradouro, 
     colab.Endereco_numero, colab.Complemento, colab.Bairro, colab.Estado, pais_endereco.pais pais_endereco, colab.Necessidade, 
     colab.cidade_nascimento, colab.Nome_Pai, colab.Nome_Mae, colab.Pais, colab.Cep, colab.Logradouro, colab.Complemento, colab.Bairro, 
     colab.Cidade, colab.Estado, colab.Celular, colab.Cel_Emergencia, colab.Email_Pessoal, colab.Rg, colab.Dat_Expedicao, colab.Cnh, 
     colab.Reservista, colab.Tit_Eleitor, colab.Zona_Eleitoral, colab.Secao_Eleitoral, colab.Cnh_categoria, colab.Pis, colab.Ctps_Num, 
     colab.facebook, colab.instagram, colab.twitter, colab.linkedin, colab.Ctps_Serie, colab.Banco codigo_banco, bancos.nome_extenso nome_banco, 
     colab.agencia, colab.conta_bancaria, colab.conta_digito, dep.Id Departamento, cargo.Id Cargo, gh.nome gh_nome, gh.id, 
     to_char(colab.dt_nasc, 'YYYY-MM-DD') as dt_nasc, emp.id_tab_rubricas, emp.id_tab_cargos, emp.id_tab_departamentos          
  FROM db_adm_rh.t_rh_colaborador colab 
   full JOIN db_adm.t_rh_funcao_gh gh 
     ON gh.Id = ( select hist_gh.id_gh 
                   from db_adm.t_hist_gh hist_gh 
                   where hist_gh.id_usuario = colab.id_usuario 
                     and hist_gh.data = ( select MAX(hist_gh2.data) 
                                            from db_adm.t_hist_gh hist_gh2 
                                            where hist_gh2.id_usuario = hist_gh.id_usuario 
                                              and hist_gh2.data <= current_date ) ) 
   JOIN db_adm.t_empresas as emp 
     ON emp.id = colab.id_empresa 
   JOIN db_adm_rh.t_rh_tabela_departamento as tabDep 
     ON tabDep.id = emp.id_tab_departamentos 
   left JOIN db_adm_rh.t_rh_departamentos dep 
     ON dep.id_tabela = tabDep.id 
    and dep.Id = (select hist_dep.id_departamento 
                    from db_adm_rh.t_rh_hist_departamento hist_dep 
                    where hist_dep.matricula = colab.matricula  
                      and hist_dep.id_empresa  = colab.id_empresa 
                      and hist_dep.data = (select MAX(hist_dep2.data) 
                                             from db_adm_rh.t_rh_hist_departamento hist_dep2 
                                             where hist_dep2.matricula = hist_dep.matricula 
                                               and hist_dep2.id_empresa = hist_dep.id_empresa 
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
                                                   and hist_cargo2.id_empresa  = hist_cargo.id_empresa 
                                                   and hist_cargo2.data <= current_date)) 
   left JOIN db_adm_rh.t_rh_hist_salario HIS_SAL 
      ON colab.Id = HIS_SAL.id_colaborador 
      and HIS_SAL.data = (select MAX(salario.data) 
                            from db_adm_rh.t_rh_hist_salario salario 
                            where salario.data <= current_date 
                              and salario.id_empresa  = colab.id_empresa
                              and salario.matricula = colab.matricula) 
                              and HIS_SAL.sequencia = (select MAX(salario_seq.sequencia) 
                                                         from db_adm_rh.t_rh_hist_salario salario_seq 
                                                         WHERE HIS_SAL.data = salario_seq.data 
                                                           and salario_seq.id_empresa = colab.id_empresa 
                                                           and salario_seq.matricula = colab.matricula) 
   left JOIN db_adm_rh.t_rh_escolaridade 
     ON db_adm_rh.t_rh_escolaridade.id = colab.id_escolaridade 
   left JOIN db_adm_rh.t_rh_estado_civil 
     ON db_adm_rh.t_rh_estado_civil.id = colab.id_est_civil 
   left JOIN db_adm_rh.t_rh_tipo_contrato 
     ON db_adm_rh.t_rh_tipo_contrato.id = colab.tipo_contrato 
   left JOIN db_adm.t_paises pais_nascimento 
     ON pais_nascimento.codigo_rfb = colab.id_pais_nascimento 
   left JOIN db_adm.t_paises pais_endereco 
     ON pais_endereco.id = colab.pais 
   left JOIN db_adm_rh.t_rh_sexo tipo_sexo 
     ON tipo_sexo.id = colab.id_sexo 
   left JOIN db_adm.t_bancos bancos 
     ON bancos.codigo = colab.banco 
     WHERE colab.matricula = '{$v_matricula}' 
       and colab.id_empresa = " . $_SESSION["vs_id_empresa"];

    //var_dump($v_sql);

    $result = pg_query($conn, $v_sql);

    $v_departamentos = buscaDepartamentos($conn,  $v_matricula);
    $v_cargos = buscaCargos($conn,  $v_matricula);
    $v_salarios = buscaSalarios($conn,  $v_matricula);
    $v_gh = buscaGH($conn,  $v_matricula);

    while ($row = pg_fetch_assoc($result)) {
        $v_dados = array(
            "Id" => $row["id_colab"],
            "Colaborador" => $row["colaborador"],
            "Cpf" => $row["cpf"],
            "Email" => $row["email"],
            "Celular" => $row["celular"],
            "Gh_nome" => $row["gh_nome"],
            "Departamento" => $row["departamento_nome"],
            "Cargo" => $row["cargo_nome"],
            "dt_nasc" => $row["dt_nasc"],
            "Email_Pessoal" => $row["email_pessoal"],
            "Pne" => $row["pne"],
            "Necessidade" => $row["necessidade"],
            "Cel_Emergencia" => $row["cel_emergencia"],
            "Pis" => $row["pis"],
            "Est_Civil" => $row["id_est_civil"],
            "Cnh" => $row["cnh"],
            "Cnh_categoria" => $row["cnh_categoria"],
            "Reservista" => $row["reservista"],
            "Rg" => $row["rg"],
            "Orgao_expedidor" => $row["orgao_expedidor"],
            "Data_expedicao" => $row["dat_expedicao"],
            "Tit_Eleitor" => $row["tit_eleitor"],
            "Zona_Eleitoral" => $row["zona_eleitoral"],
            "Secao_Eleitoral" => $row["secao_eleitoral"],
            "Nome_Mae" => $row["nome_mae"],
            "Nome_Pai" => $row["nome_pai"],
            "Cnh_vencimento" => $row["cnh_vencimento"],
            "Ctps_num" => $row["ctps_num"],
            "Ctps_serie" => $row["ctps_serie"],
            "Data_admissao" => $row["data_admissao"],
            "Matricula" => $row["matricula"],
            "Salario" => $row["salario"],
            "Duracao_contrato" => $row["duracao_contrato"],
            "Periodo_experiencia" => $row["periodo_experiencia"],
            "Cep" => $row["cep"],
            "logradouro" => $row["logradouro"],
            "Endereco_numero" => $row["endereco_numero"],
            "Complemento" => $row["complemento"],
            "Bairro" => $row["bairro"],
            "Estado" => $row["estado"],
            "Pais" => $row["pais"],
            "Cidade" => $row["cidade"],
            "Sexo" => $row["id_sexo"],
            "Escolaridade" => $row["id_escolaridade"],
            "Nacionalidade" => $row["pais"],
            "Naturalidade" => $row["cidade_nascimento"],
            "Estado_civil" => $row["id_est_civil"],
            "Gh_id" => $row["id"],
            "Tipo_contrato" => $row["tipo_contrato"],
            "Codigo_banco" => $row['codigo_banco'],
            "Agencia" => $row['agencia'],
            "Conta_bancaria" => $row['conta_bancaria'],
            "Conta_digito" => $row['conta_digito'],
            "Prorrogacao_contrato" => $row['prorrogacao_contrato'],
            "Instagram" => $row["instagram"],
            "Facebook" => $row["facebook"],
            "Linkedin" => $row["linkedin"],
            "Twitter" => $row["twitter"],
            "Departamentos" => $v_departamentos,
            "Cargos" => $v_cargos,
            "Salarios" => $v_salarios,
            "Gh" => $v_gh,
            "id_tab_rubricas" => $row["id_tab_rubricas"],
            "id_tab_cargos" => $row["id_tab_cargos"],
            "usuario" => $row["usuario"],
            "id_tab_departamentos" => $row["id_tab_departamentos"]
        );
    }
    pg_close($conn);
    $v_json = json_encode($v_dados);
    // var_dump($v_json);
    echo $v_json;
}


// CADASTRANDO NOVO REGISTRO
if ($v_acao == "EV_NOVO") {

    $v_cpf = addslashes($_POST["v_cpf"]);
    $v_nome = strtoupper(addslashes($_POST["v_nome"]));
    $v_sexo = addslashes($_POST["v_sexo"]);
    $v_dt_nasc = addslashes(implode('-', array_reverse(explode('/', $_POST["v_dt_nasc"]))));
    $v_email = strtolower(addslashes($_POST["v_email"]));
    $v_id_gh = addslashes($_POST["v_id_gh"]);
    $v_id_dpto = addslashes($_POST["v_Id_Dpto"]);
    $v_Id_Cargo = addslashes($_POST["v_Id_Cargo"]);
    $v_Nacionalidade = addslashes($_POST["v_Nacionalidade"]);
    $v_Naturalidade = addslashes($_POST["v_Naturalidade"]);
    $v_Pne = addslashes($_POST["v_Pne"]);
    $v_Necessidade = addslashes($_POST["v_Necessidade"]);
    $v_Pis = addslashes($_POST["v_Pis"]);
    $v_Est_Civil = addslashes($_POST["v_Est_Civil"]);
    $v_Cnh = addslashes($_POST["v_Cnh"]);
    $v_cnh_cat = addslashes($_POST["v_cnh_cat"]);
    $v_Reservista = addslashes($_POST["v_Reservista"]);
    $v_Rg = addslashes($_POST["v_Rg"]);
    $v_Orgao_expedidor = addslashes($_POST["v_Orgao_expedidor"]);
    $v_Tit_Eleitor = addslashes($_POST["v_Tit_Eleitor"]);
    $v_Zona_Eleitoral = addslashes($_POST["v_Zona_Eleitoral"]);
    $v_Secao_Eleitoral = addslashes($_POST["v_Secao_Eleitoral"]);
    $v_Nome_Mae = addslashes($_POST["v_Nome_Mae"]);
    $v_Nome_Pai = addslashes($_POST["v_Nome_Pai"]);
    $v_cnh_vencimento = addslashes($_POST["v_cnh_vencimento"]);
    $v_ctps_num = addslashes($_POST["v_ctps_num"]);
    $v_ctps_serie = addslashes($_POST["v_ctps_serie"]);
    $v_banco_financeiro = addslashes($_POST["v_banco_financeiro"]);
    $v_agencia_financeiro = addslashes($_POST["v_agencia_financeiro"]);
    $v_conta_financeito = addslashes($_POST["v_conta_financeito"]);
    $v_dt_admissao = addslashes($_POST["v_dt_admissao"]);
    $v_matricula = addslashes($_POST["v_matricula"]);
    $v_salario = addslashes($_POST["v_salario"]);
    $v_tipo_contrato = addslashes($_POST["v_tipo_contrato"]);
    $v_duracao_contrato = addslashes($_POST["v_duracao_contrato"]);
    $v_per_experiencia = addslashes($_POST["v_per_experiencia"]);
    $v_cep = addslashes($_POST["v_cep"]);
    $v_logradouro = addslashes($_POST["v_logradouro"]);
    $v_numero_end = addslashes($_POST["v_numero_end"]);
    $v_complemento = addslashes($_POST["v_complemento"]);
    $v_bairro = addslashes($_POST["v_bairro"]);
    $v_uf = addslashes($_POST["v_uf"]);
    $v_pais_end = addslashes($_POST["v_pais_end"]);
    $v_cidade = addslashes($_POST["v_cidade"]);
    $v_cnh_cat = addslashes($_POST["v_cnh_cat"]);
    $v_escolaridade = addslashes($_POST["v_escolaridade"]);
    $v_conta_digito = addslashes($_POST["v_conta_digito"]);
    $v_pro_contrato = addslashes($_POST["v_pro_contrato"]);
    $v_rg_expedicao = addslashes($_POST["v_rg_expedicao"]);

    $v_cpf == '' ? $v_cpf = 0 : $v_cpf = $v_cpf;
    $v_Est_Civil == '' ? $v_Est_Civil = 1 : $v_Est_Civil = $v_Est_Civil;
    $v_cnh_vencimento == '' ? $v_cnh_vencimento = '1901-01-01' : $v_cnh_vencimento = $v_cnh_vencimento;
    $v_dt_admissao == '' ? $v_dt_admissao = '1901-01-01' : $v_dt_admissao = $v_dt_admissao;
    $v_tipo_contrato == '' ? $v_tipo_contrato = 1  : $v_tipo_contrato = $v_tipo_contrato;
    $v_dt_nasc == '' ? $v_dt_nasc = '1901-01-01' : $v_dt_nasc = $v_dt_nasc;
    $v_cidade == '' ? $v_cidade = 0 : $v_cidade = $v_cidade;
    $v_rg_expedicao == '' ? $v_rg_expedicao = '1901-01-01' : $v_rg_expedicao = $v_rg_expedicao;
    $v_agencia_financeiro == '' ? $v_agencia_financeiro = 0 : $v_agencia_financeiro = $v_agencia_financeiro;
    $v_conta_financeito == '' ? $v_conta_financeito = 0 : $v_conta_financeito = $v_conta_financeito;
    $v_pro_contrato == '' ? $v_pro_contrato = 0 : $v_pro_contrato = $v_pro_contrato;
    $v_duracao_contrato == '' ? $v_duracao_contrato = 0 : $v_duracao_contrato = $v_duracao_contrato;

    if (pg_fetch_assoc(pg_query($conn, "SELECT id from db_adm.t_user where email='{$v_email}' and st_cadastro = 1"))) {
        $json_msg = '{"msg_titulo":"ERRO!", "msg_ev":"error", "msg":"Usuário ativo já cadastrado com esse email."}';
    } else {
        if ($v_email != '') {
            $v_senha = randString(6);
            $v_senha_criptografada = password_hash($v_senha, PASSWORD_DEFAULT);
            $v_chave = randString(60);

            $v_sql = "INSERT INTO db_adm.t_user (nome, senha, email, st_cadastro, st_bloqueio, e_gestor, chave, dt_alter_senha, dt_ultimo_login)
                    values ('{$v_nome}', '{$v_senha_criptografada}', '{$v_email}', 1, 0, 0, '{$v_chave}', null , null) RETURNING id";

            $result = pg_query($conn, $v_sql);
            $v_id_usuario = pg_fetch_array($result, 0)[0];

            $v_id_empresa = intval($_SESSION["vs_db_empresa"]);
            $v_sql = "INSERT INTO db_adm.t_empresas_access (id_user, id_empresa)
                    VALUES ({$v_id_usuario}, {$v_id_empresa})";

            pg_query($conn, $v_sql);

            // enviar_email($v_id_usuario, $conn);

            $v_sql = "INSERT INTO db_adm_rh.t_rh_colaborador (id_usuario, id_empresa, id_escolaridade, Nome, id_Sexo, dt_nasc, Email, Email_Pessoal, Pais,
                        cidade_nascimento, Pne, Necessidade, Cpf, Pis, id_Est_Civil, Cnh, Reservista, rg, Orgao_expedidor, dat_expedicao, Tit_Eleitor, Zona_Eleitoral,
                        Secao_Eleitoral, Nome_Mae, Nome_Pai, Cnh_vencimento, Ctps_num, Ctps_serie, Banco, Agencia, Conta_bancaria, conta_digito, Data_admissao, Matricula, Tipo_contrato,
                        prorrogacao_contrato, id_pais_nascimento, Duracao_contrato, Periodo_experiencia, Cep, logradouro, Endereco_numero, Complemento, Bairro, Estado, Cidade, Cnh_categoria )
                        VALUES ( {$v_id_usuario}, {$_SESSION["vs_db_empresa"]}, {$v_escolaridade} , '{$v_nome}', {$v_sexo}, '{$v_dt_nasc}', '{$v_email}', {$v_Nacionalidade}, 
                        '{$v_Naturalidade}', '{$v_Pne}', '{$v_Necessidade}', '{$v_cpf}', '{$v_Pis}', '{$v_Est_Civil}', '{$v_Cnh}', '{$v_Reservista}', '{$v_Rg}', '{$v_Orgao_expedidor}', '{$v_rg_expedicao}',
                        '{$v_Tit_Eleitor}', '{$v_Zona_Eleitoral}', '{$v_Secao_Eleitoral}', '{$v_Nome_Mae}', '{$v_Nome_Pai}', '{$v_cnh_vencimento}', '{$v_ctps_num}', '{$v_ctps_serie}', {$v_banco_financeiro},
                        '{$v_agencia_financeiro}', '{$v_conta_financeito}', '{$v_conta_digito}', '{$v_dt_admissao}', '{$v_matricula}', '{$v_tipo_contrato}', '{$v_pro_contrato}', 
                        '{$v_Nacionalidade}', '{$v_duracao_contrato}', '{$v_per_experiencia}', '{$v_cep}', '{$v_logradouro}', '{$v_numero_end}', '{$v_complemento}', '{$v_bairro}', '{$v_uf}', '{$v_cidade}', '{$v_cnh_cat}' ) RETURNING id";
            // var_dump($v_sql);

            $result = pg_query($conn, $v_sql);
            $v_id_colaborador = pg_fetch_array($result, 0)[0];

            // var_dump($v_id_colaborador);

            $v_sql = "INSERT INTO db_adm.t_hist_gh ( id_usuario, data ) VALUES ( {$v_id_gh}, {$v_id_usuario}, '{$v_dt_admissao}' )";
            // var_dump($v_sql);
            pg_query($conn, $v_sql);

            $v_sql = "INSERT INTO db_adm_rh.t_rh_hist_cargo ( id_cargo, id_colaborador, data, matricula, id_tabela, id_empresa ) VALUES ( {$v_Id_Cargo}, {$v_id_colaborador}, '{$v_dt_admissao}', '{$v_matricula}', 1, {$_SESSION["vs_db_empresa"]} )";
            // var_dump($v_sql);
            pg_query($conn, $v_sql);

            $v_sql = "INSERT INTO db_adm_rh.t_rh_hist_departamento ( id_departamento, id_colaborador, data, matricula, id_tabela, id_empresa  ) VALUES ( {$v_id_dpto}, {$v_id_colaborador}, '{$v_dt_admissao}', '{$v_matricula}', 1, {$_SESSION["vs_db_empresa"]} )";
            // var_dump($v_sql);
            pg_query($conn, $v_sql);

            $v_sql = "INSERT INTO db_adm_rh.t_rh_hist_salario ( id_colaborador, salario, data, sequencia, matricula, id_empresa ) VALUES ( {$v_id_colaborador}, '{$v_salario}', '{$v_dt_admissao}', 1, '{$v_matricula}', {$_SESSION["vs_db_empresa"]}  )";
            // var_dump($v_sql);
            pg_query($conn, $v_sql);

            // var_dump($v_senha);
            if ($result) {
                $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Cadastro realizado com sucesso."}';
            } else {
                $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
            }
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Favor informar o e-mail principal!"}';
        }
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}



// SALVANDO REGISTRO
if ($v_acao == "EV_SALVAR") {

    $v_id = addslashes($_POST["v_id"]);
    $v_cpf = addslashes($_POST["v_cpf"]);
    $v_nome = strtoupper(addslashes($_POST["v_nome"]));
    $v_sexo = addslashes($_POST["v_sexo"]);
    $v_dt_nasc = addslashes(implode('-', array_reverse(explode('/', $_POST["v_dt_nasc"]))));
    $v_email = strtolower(addslashes($_POST["v_email"]));
    $v_id_dpto = addslashes($_POST["v_Id_Dpto"]);
    $v_Id_Cargo = addslashes($_POST["v_Id_Cargo"]);
    $v_Nacionalidade = addslashes($_POST["v_Nacionalidade"]);
    $v_Naturalidade = addslashes($_POST["v_Naturalidade"]);
    $v_Pne = addslashes($_POST["v_Pne"]);
    $v_Necessidade = addslashes($_POST["v_Necessidade"]);
    $v_Pis = addslashes($_POST["v_Pis"]);
    $v_Est_Civil = addslashes($_POST["v_Est_Civil"]);
    $v_Cnh = addslashes($_POST["v_Cnh"]);
    $v_cnh_cat = addslashes($_POST["v_cnh_cat"]);
    $v_Reservista = addslashes($_POST["v_Reservista"]);
    $v_Rg = addslashes($_POST["v_Rg"]);
    $v_Orgao_expedidor = addslashes($_POST["v_Orgao_expedidor"]);
    $v_Tit_Eleitor = addslashes($_POST["v_Tit_Eleitor"]);
    $v_Zona_Eleitoral = addslashes($_POST["v_Zona_Eleitoral"]);
    $v_Secao_Eleitoral = addslashes($_POST["v_Secao_Eleitoral"]);
    $v_Nome_Mae = addslashes($_POST["v_Nome_Mae"]);
    $v_Nome_Pai = addslashes($_POST["v_Nome_Pai"]);
    $v_cnh_vencimento = addslashes($_POST["v_cnh_vencimento"]);
    $v_ctps_num = addslashes($_POST["v_ctps_num"]);
    $v_ctps_serie = addslashes($_POST["v_ctps_serie"]);
    $v_banco_financeiro = addslashes($_POST["v_banco_financeiro"]);
    $v_agencia_financeiro = addslashes($_POST["v_agencia_financeiro"]);
    $v_conta_financeito = addslashes($_POST["v_conta_financeito"]);
    $v_dt_admissao = addslashes($_POST["v_dt_admissao"]);
    $v_matricula = addslashes($_POST["v_matricula"]);
    $v_salario = addslashes($_POST["v_salario"]);
    $v_tipo_contrato = addslashes($_POST["v_tipo_contrato"]);
    $v_duracao_contrato = addslashes($_POST["v_duracao_contrato"]);
    $v_per_experiencia = addslashes($_POST["v_per_experiencia"]);
    $v_cep = addslashes($_POST["v_cep"]);
    $v_logradouro = addslashes($_POST["v_logradouro"]);
    $v_numero_end = addslashes($_POST["v_numero_end"]);
    $v_complemento = addslashes($_POST["v_complemento"]);
    $v_bairro = addslashes($_POST["v_bairro"]);
    $v_uf = addslashes($_POST["v_uf"]);
    $v_pais_end = addslashes($_POST["v_pais_end"]);
    $v_cidade = addslashes($_POST["v_cidade"]);
    $v_cnh_cat = addslashes($_POST["v_cnh_cat"]);
    $v_escolaridade = addslashes($_POST["v_escolaridade"]);
    $v_conta_digito = addslashes($_POST["v_conta_digito"]);
    $v_pro_contrato = addslashes($_POST["v_pro_contrato"]);
    $v_rg_expedicao = addslashes($_POST["v_rg_expedicao"]);

    $v_cpf == '' ? $v_cpf = 0 : $v_cpf = $v_cpf;
    $v_Est_Civil == '' ? $v_Est_Civil = 1 : $v_Est_Civil = $v_Est_Civil;
    $v_cnh_vencimento == '' ? $v_cnh_vencimento = '1901-01-01' : $v_cnh_vencimento = $v_cnh_vencimento;
    $v_dt_admissao == '' ? $v_dt_admissao = '1901-01-01' : $v_dt_admissao = $v_dt_admissao;
    $v_tipo_contrato == '' ? $v_tipo_contrato = 1  : $v_tipo_contrato = $v_tipo_contrato;
    $v_dt_nasc == '' ? $v_dt_nasc = '1901-01-01' : $v_dt_nasc = $v_dt_nasc;
    $v_cidade == '' ? $v_cidade = 0 : $v_cidade = $v_cidade;
    $v_rg_expedicao == '' ? $v_rg_expedicao = '1901-01-01' : $v_rg_expedicao = $v_rg_expedicao;
    $v_agencia_financeiro == '' ? $v_agencia_financeiro = 0 : $v_agencia_financeiro = $v_agencia_financeiro;
    $v_conta_financeito == '' ? $v_conta_financeito = 0 : $v_conta_financeito = $v_conta_financeito;
    $v_pro_contrato == '' ? $v_pro_contrato = 0 : $v_pro_contrato = $v_pro_contrato;
    $v_duracao_contrato == '' ? $v_duracao_contrato = 0 : $v_duracao_contrato = $v_duracao_contrato;

    if ($v_sexo) {
        if ($v_Est_Civil != "-") {
            if ($v_pais_end != "-") {
                if ($v_escolaridade > 0) {
                    $v_sql = "UPDATE db_adm_rh.t_rh_colaborador SET
                        id_escolaridade = {$v_escolaridade},
                        Nome = '{$v_nome}',
                        id_Sexo = {$v_sexo},
                        dt_nasc = '{$v_dt_nasc}',
                        Email = '{$v_email}',
                        Pais = {$v_Nacionalidade},
                        cidade_nascimento = '{$v_Naturalidade}',
                        Pne = '{$v_Pne}',
                        Necessidade = '{$v_Necessidade}',
                        Cpf = '{$v_cpf}',
                        Pis = '{$v_Pis}',
                        id_Est_Civil = '{$v_Est_Civil}',
                        Cnh = '{$v_Cnh}',
                        Reservista = '{$v_Reservista}',
                        rg = '{$v_Rg}',
                        Orgao_expedidor = '{$v_Orgao_expedidor}',
                        dat_expedicao = '{$v_rg_expedicao}',
                        Tit_Eleitor = '{$v_Tit_Eleitor}',
                        Zona_Eleitoral = '{$v_Zona_Eleitoral}',
                        Secao_Eleitoral = '{$v_Secao_Eleitoral}',
                        Nome_Mae = '{$v_Nome_Mae}',
                        Nome_Pai = '{$v_Nome_Pai}',
                        Cnh_vencimento = '{$v_cnh_vencimento}',
                        Ctps_num = '{$v_ctps_num}',
                        Ctps_serie = '{$v_ctps_serie}',
                        Banco = {$v_banco_financeiro},
                        Agencia = '{$v_agencia_financeiro}',
                        Conta_bancaria = '{$v_conta_financeito}',
                        conta_digito = '{$v_conta_digito}',
                        Data_admissao = '{$v_dt_admissao}',
                        Matricula = '{$v_matricula}',
                        Tipo_contrato = '{$v_tipo_contrato}',
                        prorrogacao_contrato = '{$v_pro_contrato}',
                        id_pais_nascimento = '{$v_Nacionalidade}',
                        Duracao_contrato = '{$v_duracao_contrato}',
                        Periodo_experiencia = '{$v_per_experiencia}',
                        Cep = '{$v_cep}',
                        logradouro = '{$v_logradouro}',
                        Endereco_numero = '{$v_numero_end}',
                        Complemento = '{$v_complemento}',
                        Bairro = '{$v_bairro}',
                        Estado = '{$v_uf}',
                        Cidade = '{$v_cidade}',
                        Cnh_categoria = '{$v_cnh_cat}' 
                        WHERE matricula =  '{$v_matricula}' AND id_empresa = {$_SESSION["vs_id_empresa"]}";
                    // var_dump($v_sql);
                    if (pg_query($conn, $v_sql)) {
                        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
                    } else {
                        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
                    }
                } else {
                    $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Favor informar a escolaridade!"}';
                }
            } else {
                $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Favor informar a Nacionalidade!"}';
            }
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Favor informar o Estado Civil!"}';
        }
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Favor informar o Sexo!"}';
    }
    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

// EXCLUINDO REGISTRO
if ($v_acao == "EV_EXCLUIR") {

    $v_id = addslashes($_POST["v_id"]);
    $v_matricula = addslashes($_POST["v_matricula"]);
    $v_id_usuario = addslashes($_POST["v_id_usuario"]);

    $v_sql1 = "DELETE FROM db_adm_rh.t_rh_hist_salario WHERE matricula = '{$v_matricula}' and id_empresa =  {$_SESSION["vs_id_empresa"]}";
    pg_query($conn, $v_sql1);
    $v_sql2 = "DELETE FROM db_adm_rh.t_rh_hist_cargo WHERE matricula = '{$v_matricula}' and id_empresa =  {$_SESSION["vs_id_empresa"]}";
    pg_query($conn, $v_sql2);
    $v_sql3 = "DELETE FROM db_adm_rh.t_rh_hist_departamento WHERE matricula = '{$v_matricula}' and id_empresa =  {$_SESSION["vs_id_empresa"]}";
    pg_query($conn, $v_sql3);
    $v_sql4 = "DELETE FROM db_adm_rh.t_rh_holerite WHERE matricula = '{$v_matricula}' and id_empresa =  {$_SESSION["vs_id_empresa"]}";
    pg_query($conn, $v_sql4);
    $v_sql5 = "DELETE FROM db_adm_rh.t_rh_holerite_bases WHERE matricula = '{$v_matricula}' and id_empresa =  {$_SESSION["vs_id_empresa"]}";
    pg_query($conn, $v_sql5);
    $v_sql6 = "DELETE FROM db_adm_rh.t_rh_colaborador WHERE matricula = '{$v_matricula}' and id_empresa =  {$_SESSION["vs_id_empresa"]}";

    if (pg_query($conn, $v_sql6)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro excluído com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

// BLOQUEANDO CONTA DO USUÁRIO
if ($v_acao == "EV_BLOQ") {

    $v_id = addslashes($_POST["v_id"]);
    $v_chave = randString(60);

    $v_sql = "UPDATE db_adm_rh.t_rh_colaborador SET \n" .
        "Chave = '" . $v_chave . "', \n" .
        "WHERE Id = " . $v_id;
    $result = pg_query($conn, $v_sql);
    $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Conta bloqueada com sucesso."}';

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}




// EXCLUINDO REGISTRO
if ($v_acao == "EV_EMAIL") {

    $v_id = addslashes($_POST["v_id"]);
    $v_chave = randString(60);

    // DESABILITANDO ACESSO ATÉ QUE A NOVA SENHA SEJA CADASTRADA PELO USUÁRIO
    $v_sql = "UPDATE db_adm_rh.t_rh_colaborador SET \n" .
        "Chave = '" . $v_chave . "', \n" .
        "WHERE id = {$v_id} and id_empresa =  {$_SESSION["vs_id_empresa"]}";
    $resultA = pg_query($conn, $v_sql);

    enviar_email($v_id);
    $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Confirmação de cadastro enviada com sucesso."}';
    $v_json = json_encode($json_msg);
    echo $v_json;
}

//INSERINDO NOVO DEPARTAMENTO
if ($v_acao == "EV_NOVO_DEP") {

    $v_id = addslashes($_POST["v_id"]);
    $v_data = addslashes($_POST["v_data"]);
    $v_departamento = addslashes($_POST["v_departamento"]);
    $v_id_dep = addslashes($_POST["v_id_dep"]);
    $v_matricula = addslashes($_POST["v_matricula"]);
    // $v_id_tab_departamentos = addslashes($_POST["v_id_tab_departamentos"]);

    if ($v_id_dep == '') {
        $v_sql = "INSERT INTO db_adm_rh.t_rh_hist_departamento (data, id_departamento, id_colaborador, id_tabela, id_empresa, matricula) VALUES ('" . $v_data . "'," . $v_departamento . "," . $v_id . "," . 1 . "," . $_SESSION["vs_id_empresa"] . ",'" . $v_matricula  . "')";
        // var_dump($v_sql);
        $result = pg_query($conn, $v_sql);

        $v_departamentos = buscaDepartamentos($conn,  $v_id_dep);

        $v_json_departamentos = json_encode($v_departamentos);

        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Departamento inserido com sucesso!.", "departamentos":' . $v_json_departamentos . ' }';
        // var_dump($json_msg);
        $v_json = json_encode($json_msg);
        echo $v_json;
    } else {
        $v_sql = "UPDATE db_adm_rh.t_rh_hist_departamento
             SET data = '" . $v_data . "', id_departamento = " . $v_departamento . " where id = " . $v_id_dep;
        // var_dump($v_sql);
        $result = pg_query($conn, $v_sql);

        $v_departamentos = buscaDepartamentos($conn,  $v_matricula);

        $v_json_departamentos = json_encode($v_departamentos);

        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Departamento atualizado com sucesso!.", "departamentos":' . $v_json_departamentos . ' }';
        // var_dump($json_msg);
        $v_json = json_encode($json_msg);
        echo $v_json;
    }
}
//INSERINDO NOVO CARGO
if ($v_acao == "EV_NOVO_CARGO") {

    $v_id = addslashes($_POST["v_id"]);
    $v_data = addslashes($_POST["v_data"]);
    $v_cargo = addslashes($_POST["v_cargo"]);
    $v_id_cargo = addslashes($_POST["v_id_cargo"]);
    // var_dump($v_id_cargo);
    $v_matricula = addslashes($_POST["v_matricula"]);

    if ($v_id_cargo == '') {
        $v_sql = "INSERT INTO db_adm_rh.t_rh_hist_cargo (data, id_cargo, matricula, id_empresa, id_tabela) VALUES ('" . $v_data . "','" . $v_cargo . "'," . $v_matricula . ",'" . $_SESSION["vs_id_empresa"] . "'," . 1 . ") ON CONFLICT ON CONSTRAINT t_rh_hist_cargo_pk DO NOTHING";
        // var_dump($v_sql);
        $result = pg_query($conn, $v_sql);

        $v_cargos = buscaCargos($conn, $v_matricula);

        $v_json_cargos = json_encode($v_cargos);

        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Cargo inserido com sucesso!.", "cargos":' . $v_json_cargos . ' }';
        // var_dump($json_msg);
        $v_json = json_encode($json_msg);
        echo $v_json;
    } else {
        $v_sql = "UPDATE db_adm_rh.t_rh_hist_cargo
             SET data = '" . $v_data . "', id_cargo = '" . $v_cargo . "' where id =  '{$v_id_cargo}'";

        $result = pg_query($conn, $v_sql);

        $v_cargos = buscaCargos($conn,  $v_matricula);

        $v_json_cargos = json_encode($v_cargos);

        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Cargo atualizado com sucesso!.", "cargos":' . $v_json_cargos . ' }';
        // var_dump($json_msg);
        $v_json = json_encode($json_msg);
        echo $v_json;
    }
}

//INSERINDO NOVO GH
if ($v_acao == "EV_NOVO_GH") {

    $v_id = addslashes($_POST["v_id"]);
    $v_id_usuario = addslashes($_POST["v_id_usuario"]);
    $v_data = addslashes($_POST["v_data"]);
    $v_gh = addslashes($_POST["v_gh"]);
    $v_id_gh = addslashes($_POST["v_id_gh"]);

    if ($v_id_gh == '') {
        $v_sql = "INSERT INTO db_adm.t_hist_gh (data, id_gh, id_usuario) VALUES ('" . $v_data . "'," . $v_gh . "," . $v_id_usuario . ")";
        // var_dump($v_sql);
        $result = pg_query($conn, $v_sql);

        $v_gh = buscaGH($conn,  $v_matricula);

        $v_json_gh = json_encode($v_gh);

        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"GH inserido com sucesso!.", "gh":' . $v_json_gh . ' }';
        // var_dump($json_msg);
        $v_json = json_encode($json_msg);
        echo $v_json;
    } else {
        $v_sql = "UPDATE db_adm.t_hist_gh
             SET data = '" . $v_data . "', id_gh = " . $v_gh .
            "where id = " . $v_id_gh;

        $result = pg_query($conn, $v_sql);

        $v_gh = buscaGH($conn,  $v_matricula);

        $v_json_gh = json_encode($v_gh);

        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"GH atualizado com sucesso!.", "cargos":' . $v_json_gh . ' }';
        // var_dump($json_msg);
        $v_json = json_encode($json_msg);
        echo $v_json;
    }
}

//INSERINDO NOVO SALARIO
if ($v_acao == "EV_NOVO_SALARIO") {

    $v_id = addslashes($_POST["v_id"]);
    $v_data = addslashes($_POST["v_data"]);
    $v_salario = addslashes($_POST["v_salario"]);
    $v_id_salario = addslashes($_POST["v_id_salario"]);

    if ($v_id_salario == '') {
        $v_sql = "INSERT INTO db_adm_rh.t_rh_hist_salario (data, salario, id_colaborador, sequencia) VALUES ('" . $v_data . "'," . $v_salario . "," . $v_id . ", 1)";
        // var_dump($v_sql);
        $result = pg_query($conn, $v_sql);

        $v_salarios = buscaSalarios($conn,  $v_matricula);

        $v_json_salarios = json_encode($v_salarios);

        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Salário inserido com sucesso!.", "salarios":' . $v_json_salarios . ' }';
        // var_dump($json_msg);
        $v_json = json_encode($json_msg);
        echo $v_json;
    } else {
        $v_sql = "UPDATE db_adm_rh.t_rh_hist_salario
             SET data = '" . $v_data . "', salario = " . $v_salario .
            "where id = " . $v_id_salario;

        $result = pg_query($conn, $v_sql);

        $v_salarios = buscaSalarios($conn,  $v_matricula);

        $v_json_salarios = json_encode($v_salarios);

        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Salário atualizado com sucesso!.", "salarios":' . $v_json_salarios . ' }';
        // var_dump($json_msg);
        $v_json = json_encode($json_msg);
        echo $v_json;
    }
}


function enviar_email($id, $conn)
{

    include_once("../../class/php/class_conect_db.php");

    $v_sql = "SELECT Id, split_part(Nome,' ',1) as Nome, Email, Chave FROM db_adm.t_user WHERE id = " . $id;
    // var_dump($v_sql);
    $result = pg_query($conn, $v_sql);

    if ($row = pg_fetch_assoc($result)) {

        $v_hora = date("H");
        if ($v_hora >= 12 && $v_hora < 18) {
            $v_welcome = "Boa tarde";
        } else if ($v_hora >= 0 && $v_hora < 12) {
            $v_welcome = "Bom dia";
        } else {
            $v_welcome = "Boa noite";
        }

        require "../../class/gmail/ClassEmail.php";
        $msg = "    <style>
        #btn_ativar {
          background-color: #4caf50;
          border: none;
          color: white;
          padding: 15px 32px;
          text-align: center;
          text-decoration: none;
          display: inline-block;
          font-size: 16px;
          margin: 4px 2px;
          cursor: pointer;
          border-radius: 12px;
        }
      </style>
      <center>
        <img src='cid:logo_email.png' alt='Logo Agrocontar' />
        <h1 style='color: #15b168;'>" . $v_welcome . " " . ucfirst(strtolower($row["nome"])) . ",</h1>
        <h2 style='color: #15b168;'>Etapa de confirmação de segurança:</h2>
        <p>
          Esta é a ultima etapa para que o seu acesso seja ativado.Clique no botão
          abaixo e cadastre a sua senha de acesso:
        </p>
        <p>
          <a
            id='btn_ativar'
            href='https://app.agrocontar.com.br/validaccess.php?chave=" . $row["chave"] . "'
            target='_blank'
            >Ativar agora</a
          >
        </p>
        <p></p>
        <p><strong></strong></p>
        <p><strong>Att,</strong></p>
        <p><strong>Suporte Inovação | Agrocontar</strong></p>
        <p></p>
      </center>";


        $email = $row["email"];
        $assunto = "Sistema Agrocontar2.0: Confirmação de usuário";
        $EnviaEmail = new Email_api();
        $EnviaEmail->send_email($msg, $email, $assunto);
    }

    // pg_close($conn);
}



//Essa função gera um valor de String aleatório do tamanho recebendo por parametros
function randString($size)
{
    //String com valor possíveis do resultado, os caracteres pode ser adicionado ou retirados conforme sua necessidade
    $basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    $return = "";

    for ($count = 0; $size > $count; $count++) {
        //Gera um caracter aleatorio
        $return .= $basic[rand(0, strlen($basic) - 1)];
    }

    return $return;
}

function buscaDepartamentos($conn,  $v_matricula)
{
    $v_sql_departamentos = "SELECT data as dt_departamento, db_adm_rh.t_rh_departamentos.nome as nome_departamento,
                    db_adm_rh.t_rh_hist_departamento.id_departamento, db_adm_rh.t_rh_hist_departamento.id
                    from db_adm_rh.t_rh_hist_departamento
                    join db_adm_rh.t_rh_departamentos on t_rh_hist_departamento.id_departamento = db_adm_rh.t_rh_departamentos.id
                    where t_rh_hist_departamento.matricula = '{$v_matricula}' 
                      and t_rh_hist_departamento.id_empresa =  {$_SESSION["vs_id_empresa"]}
                    order by dt_departamento ";

    $result_departamentos = pg_query($conn, $v_sql_departamentos);

    // $v_dados = array();
    $v_departamentos = array();
    while ($row = pg_fetch_assoc($result_departamentos)) {
        array_push(
            $v_departamentos,
            array(
                "Data_dep" => $row["dt_departamento"],
                "Nome_dep" => $row["nome_departamento"],
                "Id_dep" => $row["id_departamento"],
                "Id" => $row["id"]
            )
        );
    }

    return $v_departamentos;
}


function buscaCargos($conn, $v_matricula)
{

    /*
    $v_sql_cargos = "select db_adm_rh.t_rh_hist_cargo.data as dt_cargo, db_adm_rh.t_rh_cargos.nome as nome_cargo,
                            db_adm_rh.t_rh_hist_cargo.id_cargo, db_adm_rh.t_rh_hist_cargo.id
                       from db_adm_rh.t_rh_hist_cargo 
                            join db_adm_rh.t_rh_cargos on t_rh_hist_cargo.id_cargo = db_adm_rh.t_rh_cargos.id
                       where t_rh_hist_cargo.id_colaborador = ". $v_id. " order by dt_cargo ";
*/
    $v_sql_cargos = "SELECT his_cargo.data dt_cargo, cargos.nome nome_cargo, his_cargo.id_cargo id_cargo
    FROM db_adm_rh.t_rh_hist_cargo his_cargo 
        join db_adm_rh.t_rh_cargos as cargos 
          on cargos.id = his_cargo.id_cargo 
          and cargos.id_tabela = his_cargo.id_tabela       
    where his_cargo.matricula = '{$v_matricula}' 
      and his_cargo.id_empresa = {$_SESSION["vs_id_empresa"]}
     order by his_cargo.data";

    $result_cargos = pg_query($conn, $v_sql_cargos);
    // var_dump($v_sql_cargos);
    // $v_dados = array();
    $v_cargos = array();
    while ($row = pg_fetch_assoc($result_cargos)) {
        array_push(
            $v_cargos,
            array(
                "Data_cargo" => $row["dt_cargo"],
                "Nome_cargo" => $row["nome_cargo"],
                "Id_cargo" => $row["id_cargo"]
                //, "Id" => $row["id"]
            )
        );
    }

    return $v_cargos;
}

function buscaSalarios($conn,  $v_matricula)
{
    $v_sql_salarios = "SELECT data as dt_salario, db_adm_rh.t_rh_hist_salario.salario,
                              db_adm_rh.t_rh_hist_salario.id
                         from db_adm_rh.t_rh_hist_salario 
                        where t_rh_hist_salario.matricula = '{$v_matricula}' 
                        order by dt_salario ";

    $result_salarios = pg_query($conn, $v_sql_salarios);
    // var_dump($v_sql_cargos);
    // $v_dados = array();
    $v_salarios = array();
    while ($row = pg_fetch_assoc($result_salarios)) {
        array_push(
            $v_salarios,
            array(
                "Data_salario" => $row["dt_salario"],
                "Salario" => $row["salario"],
                "Id" => $row["id"]
            )
        );
    }
    // var_dump($v_salarios);
    return $v_salarios;
}

function buscaGH($conn,  $v_matricula)
{
    /*
    $v_sql_gh = "select hgh.data, fgh.nome, hgh.id_gh, hgh.id from db_adm.t_hist_gh hgh 
                join db_adm.t_rh_funcao_gh fgh on fgh.id = hgh.id_gh 
                where hgh.id_colaborador = {$v_id} order by hgh.data";
    */
    $v_sql_gh = "SELECT hgh.data, fgh.nome, hgh.id_gh, hgh.id
   FROM db_adm_rh.t_rh_colaborador colab 
        LEFT JOIN db_adm.t_hist_gh hgh
          on hgh.id_usuario = colab.id_usuario 
          and hgh.data = (select MAX(hgh2.data) 
                             from db_adm.t_hist_gh hgh2 
                            where hgh2.id_usuario = hgh.id_usuario 
                              and hgh2.data <= current_date ) 
        left join db_adm.t_rh_funcao_gh fgh 
          on fgh.id = hgh.id_gh 
    WHERE colab.matricula = '{$v_matricula}' and colab.id_empresa = {$_SESSION["vs_id_empresa"]}";

    // var_dump($v_sql_gh);
    $result_gh = pg_query($conn, $v_sql_gh);
    // $v_dados = array();
    $v_gh = array();
    while ($row = pg_fetch_assoc($result_gh)) {
        array_push(
            $v_gh,
            array(
                "Data_gh" => $row["data"],
                "Nome_gh" => $row["nome"],
                "Id_gh" => $row["id_gh"],
                "Id" => $row["id"]
            )
        );
    }

    return $v_gh;
}
