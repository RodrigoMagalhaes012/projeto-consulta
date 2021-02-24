<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0005") == 0) {
    print('<script> alert(\'Acesso Negado.\'); location.href = \'https://app.agrocontar.com.br\'; </script>');
}

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="css/animate.css" rel="stylesheet">
    <link rel="stylesheet" href="../class/alert/css/class_alert.css" id="theme-styles">


    <script src="../class/alert/js/class_alert.js"></script>

    <style>
        input,
        textarea {
            text-transform: uppercase;
        }

        table tr td {
            color: black;
        }

        table tr th {
            color: black;
        }

        .load {
            width: 100px;
            height: 100px;
            position: absolute;
            top: 40%;
            left: 50%;
            color: #523B8F;
        }
    </style>

    <title>Document</title>
</head>

<body>

    <div class="load"> <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
        <span class="sr-only">Loading...</span>
    </div>

    <div class="container" style="margin-top: 0px; padding: 0px;">
        <!-- <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-6">
                    <h3>Lista de Colaboradores</h3>
                </div>
                <div class="form-group col-sm-2">
                    <select id="c_tab_busca_campo" class="form-control class_inputs" onchange="func_busca_campo_select();">
                        <option value="Id|num" selected>Matricula</option>
                        <option value="Nome|txt">Nome</option>
                        <option value="Cargo|txt">Cargo</option>
                        <option value="Departamento|txt">Departamento</option>
                        <option value="St_cadastro|txt">Status</option>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <input type="text" id="c_tab_busca_texto" class="form-control class_inputs" placeholder="PESQUISAR REGISTRO" onkeyup="if (event.keyCode === 13) {func_carrega_tab();}">
                </div>
            </div>
        </div> -->



        <div id="box_form_titulo1" class="row" style="margin-top: 0px; background-image: linear-gradient(to left, #6c3a8e , white);">
            <div class="row">
                <div class="form-group col-sm-12 col" style="font-weight: bold; font-size: 25px; color: #523B8F;">Cadastro de Colaboradores</div>
            </div>
        </div>




        <div id="box_tab1" class="row" style="border-color: grey; padding: 10px; border-width: 1px; border-style: solid; background-color: white; overflow-x: hidden;">
            <input id="c_acao" type="hidden" value="">

            <div class="box-body col-sm-12">
                <!-- <input type="hidden" id="vf_tab_sql_limit_in" value="0">
                <input type="hidden" id="vf_tab_btn_pag_select" value="1"> -->
                <table style="width: 100%;" id="tab1" class="table">
                    <thead style="font-weight: bold;">
                        <tr>
                            <th style="display: none;">Id</th>
                            <th>Matricula</th>
                            <th>Nome</th>
                            <th>Cargo</th>
                            <th>Departamento</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="tab1b" style="font-weight: normal;">
                    </tbody>
                </table>
            </div>
        </div>
        <!-- <div id="box_tab_footer" class="box" style="height: 60px; margin-top: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-2">
                    <select id="c_tab_campo" class="form-control class_inputs" onchange="func_carrega_tab();">
                        <option value="Matricula" selected>Matricula</option>
                        <option value="Nome">Nome</option>
                        <option value="St_cadastro">Status</option>
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <select id="c_tab_ordem" class="form-control class_inputs" onchange="func_carrega_tab();">
                        <option value="asc" selected>Crescente</option>
                        <option value="desc">Decrescente</option>
                    </select>
                </div>
                <div class="form-group col-sm-1">
                    <select id="c_limit" class="form-control class_inputs" onchange="func_carrega_tab();">
                        <option value="50" selected>50</option>
                        <option value="100">100</option>
                        <option value="150">150</option>F
                    </select>
                </div>
                <div id="div_tab_paginacao" class="form-group col-sm-7 text-right">
                </div>
            </div>
        </div> -->

        <div id="box_form_titulo" class="box" style="display: none; height: 60px; margin-top: 0px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-11 text-center">
                    <h2>Formulário de Cadastro</h2>
                </div>
                <div class="form-group col-sm-1">
                    <button id="btn_voltar" class="btn btn-danger" style="border-radius: auto; width: auto;" onclick="goBack();">X</button>
                </div>
            </div>
        </div>
        <div id="box_form" class="box" style="display: none; height: auto; background-color: white; border: none;">
            <div class="box-body" style="height: auto;">


                <div class="row">
                    <div class="form-group col-sm-12">
                        <h3>Dados Pessoais</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group" style="display: none;">
                        <label for="c_id">Id</label>
                        <input disabled id="c_id" type="text" class="form-control class_inputs">
                        <input disabled id="c_id_usuario" type="hidden" class="form-control class_inputs">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="c_nome">Nome Completo</label>
                        <input id="c_nome" type="text" class="form-control class_inputs" placeholder="NOME COMPLETO" required>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_select_sexo">Sexo</label>
                        <select id="c_select_sexo" class="form-control class_inputs" required>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_select_escolaridade">Escolaridade</label>
                        <select id="c_select_escolaridade" class="form-control class_inputs">
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label for="c_select_nacionalidade">Nacionalidade</label>
                        <select id="c_select_nacionalidade" class="form-control class_inputs" onchange="func_muda_campo_nacionalidade()" required>
                        </select>
                    </div>
                    <div id="naturalidade_brasil">
                        <div class="form-group col-sm-2">
                            <label for="c_select_naturalidade">Naturalidade</label>
                            <select id="c_select_naturalidade" class="form-control class_inputs" required>
                            </select>
                        </div>
                    </div>
                    <div id="naturalidade">
                        <div class="form-group col-sm-2">
                            <label for="c_naturalidade">Naturalidade</label>
                            <input id="c_naturalidade" type="text" class="form-control class_inputs" placeholder="São Paulo" required>
                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="c_select_est_civil">Estado Civil</label>
                        <select id="c_select_est_civil" class="form-control class_inputs" required>
                        </select>
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="c_pne">PNE</label>
                        <select id="c_pne" class="form-control class_inputs">
                            <option value=" " selected>Selecione</option>
                            <option value="S">SIM</option>
                            <option value="N">NÃO</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_nec_especial">Necessidade Especial </label>
                        <input id="c_nec_especial" type="text" class="form-control class_inputs" placeholder="">
                    </div>
                </div>
                <div class="row">

                    <div class="form-group col-sm-2">
                        <label for="c_dt_nasc">Data de Nascimento</label>
                        <input id="c_dt_nasc" type="date" class="form-control class_inputs" placeholder="00/00/0000" required>
                    </div>
                    <div class="form-group col-sm-5">
                        <label for="c_nome_mae">Nome da Mãe</label>
                        <input id="c_nome_mae" type="text" class="form-control class_inputs" placeholder="" required>
                    </div>
                    <div class="form-group col-sm-5">
                        <label for="c_nome_pai">Nome do Pai</label>
                        <input id="c_nome_pai" type="text" class="form-control class_inputs" placeholder="">
                    </div>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <div class="form-group col-sm-12">
                        <h3>Documentos</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label for="c_cpf">CPF</label>
                        <input id="c_cpf" type="text" class="form-control class_inputs" placeholder="000.000.000-00" required>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_rg">RG</label>
                        <input id="c_rg" type="text" class="form-control class_inputs" placeholder="000000000" required>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_orgao_expedidor">Órgão Expedidor</label>
                        <input id="c_orgao_expedidor" type="text" class="form-control class_inputs" placeholder="Órgão/UF" required>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_data_expedicao">Data de Expedição</label>
                        <input id="c_data_expedicao" type="date" class="form-control class_inputs" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-2">
                        <label for="c_pis">PIS</label>
                        <input id="c_pis" type="text" class="form-control class_inputs" placeholder="000.00000-0">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_cnh">CNH</label>
                        <input id="c_cnh" type="number" class="form-control class_inputs" placeholder="0000000000000">
                    </div>
                    <div class="form-group col-sm-1">
                        <label for="c_cat_cnh">Categoria </label>
                        <input id="c_cat_cnh" type="text" class="form-control class_inputs">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_cnh_vencimento">Data Vencimento</label>
                        <input id="c_cnh_vencimento" type="date" class="form-control class_inputs">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_reservista">Reservista </label>
                        <input id="c_reservista" type="text" class="form-control class_inputs" placeholder="00000000000">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label for="c_titulo_eleitoral">Título de Eleitor</label>
                        <input id="c_titulo_eleitoral" type="text" class="form-control class_inputs" placeholder="000000000000" required>
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="c_zona_eleitoral">Zona Eleitoral</label>
                        <input id="c_zona_eleitoral" type="textr" class="form-control class_inputs" placeholder="000" required>
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="c_secao_eleitoral">Seção Eleitoral</label>
                        <input id="c_secao_eleitoral" type="text" class="form-control class_inputs" placeholder="000" required>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_ctps_num">CTPS Número</label>
                        <input id="c_ctps_num" type="number" class="form-control class_inputs" placeholder="0000000000000" required>
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="c_ctps_serie">CTPS Série</label>
                        <input id="c_ctps_serie" type="text" class="form-control class_inputs" placeholder="00000000000" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="c_select_banco">Banco</label>
                        <select id="c_select_banco" class="form-control class_inputs">
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_agencia_financeiro">Agencia</label>
                        <input id="c_agencia_financeiro" type="number" class="form-control class_inputs" placeholder="000">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_conta_financeito">Conta Corrente</label>
                        <input id="c_conta_financeito" type="number" class="form-control class_inputs" placeholder="000">
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="c_digito">Dígito</label>
                        <input id="c_digito" type="text" class="form-control class_inputs" placeholder="000">
                    </div>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <div class="form-group col-sm-12">
                        <h3>Contatos</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="c_email">E-Mail</label>
                        <input id="c_email" type="email" class="form-control class_inputs" style="text-transform: lowercase;" placeholder="email@email.com.br">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="c_email_pessoal">E-Mail Pessoal</label>
                        <input disabled id="c_email_pessoal" type="email" class="form-control class_inputs" style="text-transform: lowercase;" placeholder="email@email.com.br">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="c_celular">Celular</label>
                        <input disabled id="c_celular" type="text" class="form-control class_inputs" placeholder="(00) 0 0000-0000">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="c_celular_emergencia">Contato de Emergência </label>
                        <input disabled id="c_celular_emergencia" type="text" class="form-control class_inputs" placeholder="(00) 0 0000-0000">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3" style="padding-right: 0px;">
                        <label for="l_linkedin">Linkedin</label>
                        <input disabled id="l_linkedin" type="text" class="form-control class_inputs w-100" style="padding: 5px; text-transform: lowercase;" value="https://www.linkedin.com/">
                    </div>
                    <div class="form-group col-sm-3" style="margin:0px; padding: 0px;">
                        <label for="c_linkedin" style="color: white;">E-Mail</label>
                        <input disabled id="c_linkedin" type="email" class="form-control class_inputs w-100" style="margin:0px; padding: 10px; text-transform: lowercase;" placeholder="meuperfil">
                    </div>
                    <div class="form-group col-sm-3" style="padding-right: 0px;">
                        <label for="l_instagram">Instagram</label>
                        <input disabled id="l_instagram" type="text" class="form-control class_inputs w-100" style="padding: 5px; text-transform: lowercase;" value="https://www.instagram.com/">
                    </div>
                    <div class="form-group col-sm-3" style="margin:0px; padding: 0px;">
                        <label for="c_instagram" style="color: white;">E-Mail</label>
                        <input disabled id="c_instagram" type="email" class="form-control class_inputs w-100" style="margin:0px; padding: 10px; text-transform: lowercase;" placeholder="meuperfil">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3" style="padding-right: 0px;">
                        <label for="l_facebook">Facebook</label>
                        <input disabled id="l_facebook" type="text" class="form-control class_inputs w-100" style="padding: 5px; text-transform: lowercase;" value="https://www.facebook.com/">
                    </div>
                    <div class="form-group col-sm-3" style="margin:0px; padding: 0px;">
                        <label for="c_facebook" style="color: white;">E-Mail</label>
                        <input disabled id="c_facebook" type="email" class="form-control class_inputs w-100" style="margin:0px; padding: 10px; text-transform: lowercase;" placeholder="meuperfil">
                    </div>
                    <div class="form-group col-sm-3" style="padding-right: 0px;">
                        <label for="l_twitter">Twitter</label>
                        <input disabled id="l_twitter" type="text" class="form-control class_inputs w-100" style="padding: 5px; text-transform: lowercase;" value="https://www.twitter.com/">
                    </div>
                    <div class="form-group col-sm-3" style="margin:0px; padding: 0px;">
                        <label for="c_twitter" style="color: white;">E-Mail</label>
                        <input disabled id="c_twitter" type="email" class="form-control class_inputs w-100" style="margin:0px; padding: 10px; text-transform: lowercase;" placeholder="meuperfil">
                    </div>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <div class="form-group col-sm-12">
                        <h3>Dados Contratuais</h3>
                    </div>
                </div>
                <div class="row">
                    <div id="dep_novo_cad">
                        <div class="form-group col-sm-6">
                            <label for="c_select_departamento">Departamento</label>
                            <select id="c_select_departamento" class="form-control class_inputs" placeholder="SELECIONE UM DEPARTAMENTO">
                            </select>
                        </div>
                    </div>
                    <div id="cargo_novo_cad">
                        <div class="form-group col-sm-6">
                            <label for="c_select_cargo_novo">Cargo</label>
                            <select id="c_select_cargo_novo" class="form-control class_inputs" placeholder="SELECIONE UM CARGO">
                            </select>
                        </div>
                    </div>
                    <div id="dep_at_cad">
                        <div class="form-group col-sm-4">
                            <label for="c_departamento">Departamento</label>
                            <input disabled id="c_departamento" type="text" class="form-control class_inputs">
                        </div>
                        <div class="btn-group col-sm-2" role="group">
                            <label style="color: white;">Departamento</label>
                            <!-- <label for="c_btn_i_dep" style="color: white;">Btn Incluir</label> -->
                            <button id="c_btn_i_dep" title="Incluir Departamento" class="btn is-icon btn-outline-primary btn-primary" data-toggle="modal" data-target="#modalIncluirDepartamento">
                                <span class="button-text">
                                    <i class="fa fa-plus-square"></i>
                                </span>
                            </button>

                            <!-- <label for="c_btn_h_dep" style="color: white;">Btn Historico</label> -->
                            <button id="c_btn_h_dep" class="btn is-icon btn-outline-primary btn-warning" title="Histórico de Departamento" data-toggle="modal" data-target="#modalHistoricoDepartamento">
                                <span class="button-text">
                                    <i class="fa fa-history"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div id="cargo_at_cad">
                        <div class="form-group col-sm-4">
                            <label for="c_cargo">Cargo</label>
                            <input disabled id="c_cargo" type="text" class="form-control class_inputs" placeholder="Cargo">
                        </div>
                        <div class="btn-group col-sm-2" role="group">
                            <label style="color: white;">Cargo</label>
                            <!-- <label for="c_btn_i_cargo" style="color: white;">-</label> -->
                            <button class="btn is-icon btn-outline-primary btn-primary" title="Incluir Cargo" data-toggle="modal" data-target="#modalIncluirCargo">
                                <span class="button-text">
                                    <i class="fa fa-plus-square"></i>
                                </span>
                            </button>
                            <!-- <label for="c_btn_h_cargo" style="color: white;">---</label> -->
                            <button type="button" class="btn is-icon btn-outline-primary btn-warning" title="Histórico de Cargo" data-toggle="modal" data-target="#modalHistoricoCargo">
                                <span class="button-text">
                                    <i class="fa fa-history"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="gh_novo">
                        <div class="form-group col-sm-6">
                            <label for="c_select_gh_novo">Grupo Hierárquico</label>
                            <select id="c_select_gh_novo" class="form-control class_inputs" placeholder="SELECIONE UM GRUPO HIERÁRQUICO">
                            </select>
                        </div>
                    </div>
                    <div id="gh_atualiza">
                        <div class="form-group col-sm-4">
                            <label for="c_gh">Grupo Hierárquico</label>
                            <input disabled id="c_gh" type="text" class="form-control class_inputs" placeholder="COORDENAÇÃO">
                        </div>
                        <div class="btn-group col-sm-2" role="group">
                            <label style="color: white;">Grupo Hierárquico</label>
                            <!-- <label for="c_btn_i_salario" style="color: white;">----------------------------------------------------------------------------------</label> -->
                            <button id="c_btn_i_salario" class="btn is-icon btn-outline-primary btn-primary" title="Incluir salário" data-toggle="modal" data-target="#modalIncluirGH">
                                <span class="button-text">
                                    <i class="fa fa-plus-square"></i>
                                </span>
                            </button>
                            <button id="c_btn_i_salario" class="btn is-icon btn-outline-primary btn-warning" title="Histórico de salário" data-toggle="modal" data-target="#modalHistoricoGH">
                                <span class="button-text">
                                    <i class="fa fa-history"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="c_dt_admissao">Data Admissão</label>
                        <input id="c_dt_admissao" type="date" class="form-control class_inputs" placeholder="00/00/0000">
                    </div>
                </div>
                <div class="row">
                    <div id="salario_novo">
                        <div class="form-group col-sm-6">
                            <label for="c_salario_novo">Salário</label>
                            <input id="c_salario_novo" type="text" class="form-control class_inputs" placeholder="R$1.045,00">
                        </div>
                    </div>
                    <div id="salario_atualiza">
                        <div class="form-group col-sm-4">
                            <label for="c_salario">Salario</label>
                            <input disabled id="c_salario" type="text" class="form-control class_inputs" placeholder="R$1045,00">
                        </div>
                        <div class="btn-group col-sm-2" role="group">
                            <label style="color: white;">Salario</label>
                            <!-- <label for="c_btn_i_salario" style="color: white;">----------------------------------------------------------------------------------</label> -->
                            <button id="c_btn_i_salario" class="btn is-icon btn-outline-primary btn-primary" title="Incluir salário" data-toggle="modal" data-target="#modalIncluirSalario">
                                <span class="button-text">
                                    <i class="fa fa-plus-square"></i>
                                </span>
                            </button>
                            <button id="c_btn_i_salario" class="btn is-icon btn-outline-primary btn-warning" title="Histórico de salário" data-toggle="modal" data-target="#modalHistoricoSalario">
                                <span class="button-text">
                                    <i class="fa fa-history"></i>
                                </span>
                            </button>
                        </div>
                        <!-- <div class="form-group col-sm-1">
                            <label for="c_btn_i_salario" style="color: white;">-</label>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalIncluirSalario">
                                Incluir
                            </button>
                        </div>
                        <div class="form-group col-sm-1">
                            <label for="c_btn_h_salario" style="color: white;">----</label>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalHistoricoSalario">
                                Histórico
                            </button>
                        </div> -->
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="c_tipo_contrato">Tipo de Contrato</label>
                        <select id="c_tipo_contrato" class="form-control class_inputs">
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label for="c_matricula">Matrícula </label>
                        <input id="c_matricula" type="text" class="form-control class_inputs" placeholder="00000000000">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_duracao_contrato">Duração do Contrato (Dias)</label>
                        <input id="c_duracao_contrato" type="number" class="form-control class_inputs" placeholder="30 / 60 / 90 / 180">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_per_experiencia">Período Experiência</label>
                        <input id="c_per_experiencia" type="text" class="form-control class_inputs" placeholder="3 meses">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_pro_contrato">Prorrogação do Contrato</label>
                        <input id="c_pro_contrato" type="text" class="form-control class_inputs" placeholder="3 meses">
                    </div>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <div class="form-group col-sm-12">
                        <h3>Endereço</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label for="c_cep">CEP</label>
                        <input id="c_cep" type="text" class="form-control class_inputs" placeholder="00.000-000">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_logradouro">Logradouro</label>
                        <input id="c_logradouro" type="text" class="form-control class_inputs" placeholder="c_logradouro">
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="c_complemento">Complemento</label>
                        <input id="c_complemento" type="text" class="form-control class_inputs" placeholder="Complemento">
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="c_numero_end">Número</label>
                        <input id="c_numero_end" type="number" class="form-control class_inputs" placeholder="000">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="c_cidade">Cidade</label>
                        <input id="c_cidade" type="text" class="form-control class_inputs" placeholder="Cidade">
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="c_bairro">Bairro</label>
                        <input id="c_bairro" type="text" class="form-control class_inputs" placeholder="Bairro">
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="c_uf">Estado</label>
                        <input id="c_uf" type="text" class="form-control class_inputs" placeholder="SP">
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="c_pais_end">País</label>
                        <input id="c_pais_end" type="text" class="form-control class_inputs" placeholder="BRASIL">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="display: none;" id="box_form_footer" class="box-footer">
        <div class="row">
            <div class="form-group col-sm-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                <button id="btn_salvar_reg" class="btn btn-success" style="border-radius: 10px; width: 100px;" onclick="func_salvar_registro()">Salvar</button>
                <button id="btn_excluir_reg" class="btn btn-warning" style="border-radius: 10px; width: 100px;" onclick="func_excluir_registro()">Excluir</button>
                <button id="btn_demitir_col" class="btn btn-danger" style="border-radius: 10px; width: 140px;" onclick="func_bloqueio_conta()">Demitir</button>
                <button id="btn_info_colaborador" class="btn btn-info" style="border-radius: 10px; width: 140px;" onclick="func_email_confirma()">Informações</button>
            </div>
        </div>
    </div>
    <div id="box_form_footer1" class="box-footer">
        <div class="row">
            <div class="form-group col-sm-sm-xs-12 text-right" style="margin-bottom: 20px;">
                <button id="btn_novo_reg" class="btn btn-primary" style="border-radius: 10px; width: 100px;" onclick="func_novo_registro()">Novo</button>
            </div>
        </div>
    </div>
    </div>


    <!-- Modal para inclusao de departamento -->
    <div class="modal fade" id="modalIncluirDepartamento" tabindex="-1" role="dialog" aria-labelledby="modalIncluirDepartamento" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalIncluirDepartamento">Adicionar departamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true"> </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="box_form" class="box" style=" height: auto; background-color: white; border: none;">
                        <div class="box-body" style="height: auto;">
                            <div class="row">
                                <div class="form-group ">
                                    <input id="c_id_dep" type="hidden" class="form-control class_inputs">
                                    <input id="c_id_tab_departamentos" type="hidden" class="form-control class_inputs">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="c_dt_dep">Data</label>
                                    <input required id="c_dt_dep" type="date" class="form-control class_inputs" placeholder="00/00/0000">
                                </div>
                                <div class="form-group col-sm-8">
                                    <label for="c_select_dep">Departamento</label>
                                    <select id="c_select_dep" class="form-control class_inputs" placeholder="SELECIONE UM CARGO">
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="c_select_h_dep">Historico de departamentos</label>
                                    <select onchange="func_troca_dep_select()" id="c_select_h_dep" class="form-control class_inputs" placeholder="SELECIONE UM DEPARTAMENTO">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button id="btn_novo_dep" type="button" class="btn btn-primary" onclick="func_cad_novo_dep()">Cadastrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para historico de departamento -->
    <div class="modal fade" id="modalHistoricoDepartamento" tabindex="-1" role="dialog" aria-labelledby="modalHistoricoDepartamento" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHistoricoDepartamento">Histórico de departamentos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true"> </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="box_form" class="box" style=" height: auto; background-color: white; border: none;">
                        <div class="box-body" style="height: auto;">
                            <table class="table" id="c_tabela_h_dep">
                                <thead>
                                    <tr>
                                        <th scope="col">Data</th>
                                        <th scope="col">Departamento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal para inclusao de cargos -->
    <div class="modal fade" id="modalIncluirCargo" tabindex="-1" role="dialog" aria-labelledby="modalIncluirCargo" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalIncluirCargo">Adicionar cargo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true"> </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="box_form" class="box" style=" height: auto; background-color: white; border: none;">
                        <div class="box-body" style="height: auto;">
                            <div class="row">
                                <div class="form-group ">
                                    <input id="c_id_cargo" type="hidden" class="form-control class_inputs">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="c_dt_cargo">Data</label>
                                    <input required id="c_dt_cargo" type="date" class="form-control class_inputs" placeholder="00/00/0000">
                                </div>
                                <div class="form-group col-sm-8">
                                    <label for="c_select_cargo">Cargo</label>
                                    <select id="c_select_cargo" class="form-control class_inputs" placeholder="SELECIONE UM CARGO">
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="c_select_h_cargo">Historico de cargos</label>
                                    <select onchange="func_troca_cargo_select()" id="c_select_h_cargo" class="form-control class_inputs" placeholder="SELECIONE UM CARGO">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button id="btn_novo_dep" type="button" class="btn btn-primary" onclick="func_cad_novo_cargo()">Cadastrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para historico de cargos -->
    <div class="modal fade" id="modalHistoricoCargo" tabindex="-1" role="dialog" aria-labelledby="modalHistoricoCargo" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHistoricoCargo">Histórico de cargos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true"> </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="box_form" class="box" style="height: auto; background-color: white; border: none;">
                        <div class="box-body" style="height: auto;">
                            <table class="table" id="c_tabela_h_cargo">
                                <thead>
                                    <tr>
                                        <th scope="col">Data</th>
                                        <th scope="col">Cargo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal para inclusao de salarios -->
    <div class="modal fade" id="modalIncluirSalario" tabindex="-1" role="dialog" aria-labelledby="modalIncluirSalario" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalIncluirSalario">Adicionar salário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true"> </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="box_form" class="box" style="height: auto; background-color: white; border: none;">
                        <div class="box-body" style="height: auto;">
                            <div class="row">
                                <div class="form-group ">
                                    <input id="c_id_salario" type="hidden" class="form-control class_inputs">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="c_dt_salario">Data</label>
                                    <input required id="c_dt_salario" type="date" class="form-control class_inputs" placeholder="00/00/0000">
                                </div>
                                <div class="form-group col-sm-8">
                                    <label for="c_salario_atualiza">Salario</label>
                                    <input id="c_salario_atualiza" type="text" class="form-control class_inputs" placeholder="R$1045,00">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="c_select_h_salario">Histórico de salários</label>
                                    <select onchange="func_troca_salario_select()" id="c_select_h_salario" class="form-control class_inputs" placeholder="SELECIONE UM CARGO">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button id="btn_novo_dep" type="button" class="btn btn-primary" onclick="func_cad_novo_salario()">Cadastrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para historico de salarios -->
    <div class="modal fade" id="modalHistoricoSalario" tabindex="-1" role="dialog" aria-labelledby="modalHistoricoSalario" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHistoricoSalario">Histórico de salários</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true"> </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="box_form" class="box" style="height: auto; background-color: white; border: none;">
                        <div class="box-body" style="height: auto;">
                            <table class="table" id="c_tabela_h_salario">
                                <thead>
                                    <tr>
                                        <th scope="col">Data</th>
                                        <th scope="col">Salário</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para inclusao de gh -->
    <div class="modal fade" id="modalIncluirGH" tabindex="-1" role="dialog" aria-labelledby="modalIncluirGH" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalIncluirGH">Adicionar Grupo Hierárquico</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true"> </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="box_form" class="box" style="height: auto; background-color: white; border: none;">
                        <div class="box-body" style="height: auto;">
                            <div class="row">
                                <div class="form-group ">
                                    <input id="c_id_gh" type="hidden" class="form-control class_inputs">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="c_dt_gh">Data</label>
                                    <input required id="c_dt_gh" type="date" class="form-control class_inputs" placeholder="00/00/0000">
                                </div>
                                <div class="form-group col-sm-8">
                                    <label for="c_select_gh">Grupo Hierárquico</label>
                                    <select id="c_select_gh" class="form-control class_inputs" placeholder="SELECIONE UM GRUPO HIERARQUICO">
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="c_select_h_gh">Histórico de Grupo Hierárquico</label>
                                    <select onchange="func_troca_gh_select()" id="c_select_h_gh" class="form-control class_inputs" placeholder="SELECIONE UM GRUPO HIERARQUICO">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button id="btn_novo_dep" type="button" class="btn btn-primary" onclick="func_cad_novo_gh()">Cadastrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para historico de gh -->
    <div class="modal fade" id="modalHistoricoGH" tabindex="-1" role="dialog" aria-labelledby="modalHistoricoGH" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHistoricoGH">Histórico de Grupo Hierárquico</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true"> </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="box_form" class="box" style="height: auto; background-color: white; border: none;">
                        <div class="box-body" style="height: auto;">
                            <table class="table" id="c_tabela_h_gh">
                                <thead>
                                    <tr>
                                        <th scope="col">Data</th>
                                        <th scope="col">Grupo Hierárquico</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../class/DataTables/datatables.min.js"></script>
    <script language="JavaScript">
        $(document).ready(function() {

            $("#box_form_titulo").hide();
            $("#box_form").hide();
            $("#box_form_footer").hide();
            $("#box_tab_titulo").show();
            $("#box_tab1").show();
            $("#box_tab_footer").show();


            func_carrega_tab();
            func_lista_gh();
            func_lista_dpto();
            func_lista_cargo();
            func_preenche_select();
            $("#c_cpf").mask("999.999.999-99");
            $("#c_pis").mask("999.99999-9");
            $("#c_tab_busca_texto").mask("0000000000");
            $("#c_dt_nasc").mask("00/00/0000");
            $("#c_cep").mask("00.000-000");
            $("#c_celular").mask("(00) 0 0000-0000");
            $("#c_celular_emergencia").mask("(00) 0 0000-0000");
            $("#c_salario").mask('#.##0,00', {
                reverse: true
            });
        });



        function func_tab_paginar(vj_pag) {
            var v_pag = vj_pag;
            var v_limit = $("#c_limit").val();
            $("#vf_tab_btn_pag_select").val(v_pag + 1);
            $("#vf_tab_sql_limit_in").val(v_limit * v_pag);
            func_carrega_tab();

        }



        function func_carrega_tab() {

            func_select_naturalidade();

            $("#c_acao").val("");
            var v_acao = "LISTAR_USUARIOS";
            // var v_tab_campo = $("#c_tab_campo").val();
            // var v_tab_ordem = $("#c_tab_ordem").val();
            // var v_tab_busca_campo = $("#c_tab_busca_campo").val();
            // var v_tab_busca_texto = $("#c_tab_busca_texto").val();
            // var v_tab_sql_limit_in = $("#vf_tab_sql_limit_in").val();
            // var v_limit = $("#c_limit").val();

            $("#c_nome").prop("disabled", true);
            $("#c_sexo").prop("disabled", true);
            $("#c_dt_nasc").prop("disabled", true);
            $("#c_email").prop("disabled", true);
            // $("#c_departamento").prop("disabled", false);
            // $("#c_cargo").prop("disabled", false);

            $("#c_id").val("");
            $("#c_nome").val("");
            $("#c_celular").val("");
            $("#c_sexo").val("");
            $("#c_dt_nasc").val("");
            $("#c_email").val("");
            // $("#c_departamento").val("");
            $("#c_cargo").val("");

            $("#btn_novo_reg").prop("disabled", false);
            // $("#btn_salvar_reg").prop("disabled", true);
            // $("#btn_excluir_reg").prop("disabled", true);
            $("#btn_demitir_col").prop("disabled", true);
            $("#btn_info_colaborador").prop("disabled", true);

            $.ajax({
                type: "POST",
                url: "lib/lib_cad_colaborador.php",
                data: {
                    "v_acao": v_acao,
                    // "v_tab_campo": v_tab_campo,
                    // "v_tab_ordem": v_tab_ordem,
                    // "v_tab_busca_campo": v_tab_busca_campo,
                    // "v_tab_busca_texto": v_tab_busca_texto,
                    // "v_tab_sql_limit_in": v_tab_sql_limit_in,
                    // "v_limit": v_limit
                },
                success: function(data) {
                    $('#tab1').DataTable().destroy();



                    var options = '';
                    var v_index = 0;
                    var v_num_linhas = 0;
                    $("#tab1b").empty();


                    v_num_linhas = data[0].linhas;
                    for (v_index = 1; v_index < data.length; v_index++) {
                        if (data[v_index].Situacao == 1) {
                            data[v_index].Situacao = "Trabalhando";
                        }
                        if (data[v_index].Situacao == 7) {
                            data[v_index].Situacao = "Demitido";
                        }
                        if (data[v_index].Situacao == 2) {
                            data[v_index].Situacao = "Férias";
                        }
                        options += '<tr  style="cursor: pointer;" onclick="func_select(\'' + data[v_index].Id + '\',' + '\'' + data[v_index].Matricula + '\');"><td style="display: none;">' + data[v_index].Id + '</td><td>' + data[v_index].Matricula + '</td><td>' + data[v_index].Nome + '</td><td>' + data[v_index].Cargo + '</td><td>' + data[v_index].Departamento + '</td><td>' + data[v_index].Situacao + '</td></tr>';
                    }



                    $('#tab1b').html(options);


                    // $("#div_tab_paginacao").empty();
                    // var divAtual = document.getElementById("div_tab_paginacao");
                    // var v_num_pag = Math.round(v_num_linhas / v_limit);
                    // for (v_index = 0; v_index <= v_num_pag; v_index++) {
                    //     var novoBtn = document.createElement("button");
                    //     novoBtn.setAttribute('id', 'btn_pag' + (v_index + 1));
                    //     novoBtn.setAttribute('class', 'btn btn-default');
                    //     novoBtn.innerHTML = (v_index + 1);
                    //     novoBtn.setAttribute('onClick', 'func_tab_paginar(' + v_index + ');');
                    //     divAtual.appendChild(novoBtn);
                    // }

                    // var v_tab_btn_pag_select = $("#vf_tab_btn_pag_select").val();
                    // $("#btn_pag" + v_tab_btn_pag_select).css("background-color", "#C6E2FF");

                    $("#tab1").DataTable({
                        "language": {
                            "url": "../class/DataTables/portugues.json",
                        },
                        "columnDefs": [{
                            "width": "15%",
                            "targets": 1,
                        }],
                        "lengthMenu": [
                            [5, 10, 25, 50, -1],
                            [5, 10, 25, 50, "Todos"]
                        ],
                        "order": [
                            [2, "asc"]
                        ],
                        "scrollY": "50vh",
                        "scrollX": true,
                        "scrollCollapse": true,
                        "paging": true
                    });

                    $('.load').hide();
                },
                error: function(request, status, erro) {
                    Swal.fire({
                        icon: "error",
                        title: "FALHA!",
                        text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                    })
                }
            });
        }

        function goBack() {
            func_reseta_select();
            $("#box_form_titulo").hide();
            $("#box_form").hide();
            $("#box_form_footer").hide();
            $("#box_tab_footer").show();
            $("#box_tab_titulo").show();
            $("#box_tab1").show();
            $("#box_form_footer1").show();
            $("#c_nome ").val(" ");

        }



        function func_lista_dpto() {
            $.ajax({
                type: "POST",
                url: "lib/lib_cad_colaborador.php",
                data: {
                    "v_acao": "LISTA_DPTO"
                },
                success: function(data) {
                    var options = '<option value="0" selected>SELECIONE UM DEPARTAMENTO</option>';
                    $("#c_select_dep").empty();

                    for (v_index = 0; v_index < data.length; v_index++) {
                        options += '<option value="' + data[v_index].Id + '">' + data[v_index].Departamento + '</option>';
                    }
                    $('#c_select_dep').html(options);
                },
                error: function(request, status, erro) {
                    Swal.fire({
                        icon: "error",
                        title: "FALHA!",
                        text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                    })
                }
            });
        }

        function func_lista_cargo() {
            $.ajax({
                type: "POST",
                url: "lib/lib_cad_colaborador.php",
                data: {
                    "v_acao": "LISTA_CARGO"
                },
                success: function(data) {
                    var options = '<option value="0">SELECIONE UM CARGO</option>';
                    $("#c_select_cargo").empty();
                    for (v_index = 0; v_index < data.length; v_index++) {
                        options += '<option value="' + data[v_index].Id + '">' + data[v_index].Cargo + '</option>';
                    }
                    $('#c_select_cargo').html(options);
                },
                error: function(request, status, erro) {
                    Swal.fire({
                        icon: "error",
                        title: "FALHA!",
                        text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                    })
                }
            });
        }

        function func_lista_gh() {
            $.ajax({
                type: "POST",
                url: "lib/lib_cad_colaborador.php",
                data: {
                    "v_acao": "LISTA_GH"
                },
                success: function(data) {
                    var options = '<option value="0">SELECIONE UM GH</option>';
                    $("#c_select_gh").empty();
                    for (v_index = 0; v_index < data.length; v_index++) {
                        options += '<option value="' + data[v_index].Id + '">' + data[v_index].Gh + '</option>';
                    }
                    $('#c_select_gh').html(options);
                },
                error: function(request, status, erro) {
                    Swal.fire({
                        icon: "error",
                        title: "FALHA!",
                        text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                    })
                }
            });
        }


        function func_select(v_id, v_matricula) {

            $.ajax({
                type: "POST",
                url: "lib/lib_cad_colaborador.php",
                data: {
                    "v_acao": "EV_SELECT",
                    "v_id": v_id,
                    "v_matricula": v_matricula
                },
                success: function(data) {



                    func_reseta_select();

                    $("#dep_at_cad").show()
                    $("#dep_novo_cad").hide()
                    $("#cargo_at_cad").show()
                    $("#cargo_novo_cad").hide()
                    $("#salario_atualiza").show()
                    $("#salario_novo").hide()
                    $("#gh_atualiza").show()
                    $("#gh_novo").hide()

                    $("#box_form_titulo").show();
                    $("#box_form").show();
                    $("#box_form_footer").show();
                    $("#box_tab_footer").hide();
                    $("#box_tab_titulo").hide();
                    $("#box_tab1").hide();
                    $("#box_form_footer1").hide();

                    $("#c_acao").val("EV_SELECT");
                    $("#c_nome").prop("disabled", false);
                    $("#c_sexo").prop("disabled", false);
                    $("#c_dt_nasc").prop("disabled", false);
                    $("#c_email").prop("disabled", false);





                    $("#c_id").val(data.Id);
                    $("#c_id_usuario").val(data.usuario);
                    $("#c_nome").val(data.Colaborador);
                    $("#c_celular").val(data.Celular);
                    $("#c_celular_emergencia").val(data.Cel_Emergencia);
                    $("#c_select_sexo").val(data.Sexo);

                    $("#c_naturalidade").val(data.Naturalidade);
                    $("#c_select_escolaridade").val(data.Escolaridade);
                    $("#c_select_nacionalidade").val(data.Nacionalidade);
                    $("#c_select_est_civil").val(data.Estado_civil);
                    $("#c_tipo_contrato").val(data.Tipo_contrato);
                    $("#c_select_banco").val(data.Codigo_banco);
                    $("#c_dt_nasc").val(data.dt_nasc);
                    $("#c_email").val(data.Email);
                    $("#c_email_pessoal").val(data.Email_Pessoal);
                    $("#c_pne").val(data.Pne);
                    $("#c_nec_especial").val(data.Necessidade);
                    $("#c_cpf").val(data.Cpf);
                    $("#c_celular").val(data.Celular);
                    $("#c_pis").val(data.Pis);
                    $("#c_estado_civil").val(data.Est_Civil);
                    $("#c_cnh").val(data.Cnh);
                    $("#c_reservista").val(data.Reservista);
                    $("#c_rg").val(data.Rg);
                    $("#c_orgao_expedidor").val(data.Orgao_expedidor);
                    $("#c_data_expedicao").val(data.Data_expedicao);
                    $("#c_titulo_eleitoral").val(data.Tit_Eleitor);
                    $("#c_zona_eleitoral").val(data.Zona_Eleitoral);
                    $("#c_secao_eleitoral").val(data.Secao_Eleitoral);
                    $("#c_nome_mae").val(data.Nome_Mae);
                    $("#c_nome_pai").val(data.Nome_Pai);
                    $("#c_logradouro").val(data.logradouro);
                    $("#c_pro_contrato").val(data.Prorrogacao_contrato);
                    $("#c_linkedin").val(data.Linkedin);
                    $("#c_instagram").val(data.Instagram);
                    $("#c_facebook").val(data.Facebook);
                    $("#c_twitter").val(data.Twitter);
                    $("#c_departamento").val(data.Departamento);
                    $("#c_cargo").val(data.Cargo);
                    $("#c_gh").val(data.Gh_nome);
                    $("#c_cnh_vencimento").val(data.Cnh_vencimento);
                    $("#c_ctps_num").val(data.Ctps_num);
                    $("#c_ctps_serie").val(data.Ctps_serie);
                    $("#c_banco_financeiro").val(data.Banco);
                    $("#c_agencia_financeiro").val(data.Agencia);
                    $("#c_conta_financeito").val(data.Conta_bancaria);
                    $("#c_digito").val(data.Conta_digito);
                    $("#c_dt_admissao").val(data.Data_admissao);
                    $("#c_matricula").val(data.Matricula);
                    $("#c_salario").val(data.Salario);
                    $("#c_tipo_contrato").val(data.Tipo_contrato);
                    $("#c_duracao_contrato").val(data.Duracao_contrato);
                    $("#c_venc_contrato").val(data.Vencimento_contrato);
                    $("#c_per_experiencia").val(data.Periodo_experiencia);
                    $("#c_cep").val(data.Cep);
                    $("#c_numero_end").val(data.Endereco_numero);
                    $("#c_complemento").val(data.Complemento);
                    $("#c_bairro").val(data.Bairro);
                    $("#c_uf").val(data.Estado);
                    $("#c_pais_end").val(data.Pais);
                    $("#c_cidade").val(data.Cidade);
                    $("#c_cat_cnh").val(data.Cnh_categoria);

                    if ($("#c_select_nacionalidade").val() == 32) {
                        $("#naturalidade").hide();
                        $("#naturalidade_brasil").show();
                        // $(`#c_select_naturalidade>option:selected`);
                        $("#c_select_naturalidade").val(data.Naturalidade);
                    } else {
                        $("#naturalidade_brasil").hide();
                        $("#naturalidade").show();
                        $("#c_naturalidade").val(data.Naturalidade);
                    }
                    if ($("#c_pais_end").val() == 32) {
                        $("#c_pais_end").val("Brasil");
                    }

                    var options = '<option value="0">SELECIONE UM DEPARTAMENTO PARA ALTERAR</option>';
                    var v_hist_tabela = '';
                    $("#c_select_h_dep").empty();
                    $("#c_tabela_h_dep>tbody").empty();
                    data.Departamentos.forEach(element => {
                        let v_data_split = element.Data_dep.split('-')
                        let v_data_formatada = `${v_data_split[2]}/${v_data_split[1]}/${v_data_split[0]}`
                        options += '<option value="' + element.Id_dep + ' - ' + element.Id + '">' + `${v_data_formatada} - ${element.Nome_dep}` + '</option>';
                        v_hist_tabela += `<tr> <td>${v_data_formatada}</td><td>${element.Nome_dep}</td></tr>`
                    });
                    $('#c_select_h_dep').html(options);
                    $("#c_tabela_h_dep>tbody").html(v_hist_tabela);

                    var options = '<option value="0">SELECIONE UM CARGO PARA ALTERAR</option>';
                    var v_hist_tabela = '';
                    $("#c_select_h_cargo").empty();
                    $("#c_tabela_h_cargo>tbody").empty();
                    data.Cargos.forEach(element => {
                        let v_data_split = element.Data_cargo.split('-')
                        let v_data_formatada = `${v_data_split[2]}/${v_data_split[1]}/${v_data_split[0]}`
                        options += '<option value="' + element.Id_cargo + ' - ' + element.Id + '">' + `${v_data_formatada} - ${element.Nome_cargo}` + '</option>';
                        v_hist_tabela += `<tr> <td>${v_data_formatada}</td><td>${element.Nome_cargo}</td></tr>`
                    });
                    $('#c_select_h_cargo').html(options);
                    $("#c_tabela_h_cargo>tbody").html(v_hist_tabela);



                    var options = '<option value="0">SELECIONE UM SALARIO PARA ALTERAR</option>';
                    var v_hist_tabela = '';
                    $("#c_select_h_salario").empty();
                    $("#c_tabela_h_salario>tbody").empty();
                    data.Salarios.forEach(element => {
                        let v_data_split = element.Data_salario.split('-')
                        let v_data_formatada = `${v_data_split[2]}/${v_data_split[1]}/${v_data_split[0]}`
                        options += '<option value="' + element.Id + '">' + `${v_data_formatada} - ${element.Salario}` + '</option>';
                        v_hist_tabela += `<tr> <td>${v_data_formatada}</td><td>${element.Salario}</td></tr>`
                    });
                    $('#c_select_h_salario').html(options);
                    $("#c_tabela_h_salario>tbody").html(v_hist_tabela);

                    // $("#btn_novo_reg").prop("disabled", false);
                    // $("#btn_salvar_reg").prop("disabled", false);
                    // $("#btn_excluir_reg").prop("disabled", false);
                    // $("#btn_info_colaborador").prop("disabled", false);



                    var options = '<option value="0">SELECIONE UM GH PARA ALTERAR</option>';
                    var v_hist_tabela = '';
                    $("#c_select_h_gh").empty();
                    $("#c_tabela_h_gh>tbody").empty();
                    data.Gh.forEach(element => {
                        let v_data_split = element.Data_gh.split('-')
                        let v_data_formatada = `${v_data_split[2]}/${v_data_split[1]}/${v_data_split[0]}`
                        options += '<option value="' + element.Id_gh + ' - ' + element.Id + '">' + `${v_data_formatada} - ${element.Nome_gh}` + '</option>';
                        v_hist_tabela += `<tr> <td>${v_data_formatada}</td><td>${element.Nome_gh}</td></tr>`
                    });
                    $('#c_select_h_gh').html(options);
                    $("#c_tabela_h_gh>tbody").html(v_hist_tabela);


                    if (data.St_Cadastro == 1) {
                        $("#btn_demitir_col").prop("disabled", false);
                    }
                },
                error: function(request, status, erro) {
                    Swal.fire({
                        icon: "error",
                        title: "FALHA!",
                        text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                    })
                }
            });

        }



        function func_busca_campo_select() {
            $("#c_tab_busca_texto").val("");
            var v_tab_busca_campo = $("#c_tab_busca_campo").val();
            if (v_tab_busca_campo.split('|')[1] == "num") {
                $("#c_tab_busca_texto").mask("0000000000");
            } else {
                $("#c_tab_busca_texto").unmask();
            }
        }



        function func_novo_registro() {

            func_reseta_select();

            $("#naturalidade_brasil").hide();

            $("#dep_at_cad").hide()
            $("#dep_novo_cad").show()
            $("#cargo_at_cad").hide()
            $("#cargo_novo_cad").show()
            $("#salario_atualiza").hide()
            $("#salario_novo").show()
            $("#gh_atualiza").hide()
            $("#gh_novo").show()
            $("#box_form_titulo").show();
            $("#box_form").show();
            $("#box_form_footer").show();
            $("#box_tab_titulo").hide();
            $("#box_tab1").hide();
            $("#box_tab_footer").hide();
            $("#box_form_footer1").hide();

            $("#c_acao").val("EV_NOVO");
            $("#c_nome").prop("disabled", false);
            $("#c_sexo").prop("disabled", false);
            $("#c_dt_nasc").prop("disabled", false);
            $("#c_email").prop("disabled", false);

            $("#c_id").val("");
            $("#c_nome").val("");
            $("#c_celular").val("");
            $("#c_celular_emergencia").val("");
            $("#c_sexo").val("");
            $("#c_dt_nasc").val("");
            $("#c_email").val("");
            $("#c_email_pessoal").val("");
            $("#c_nacionalidade").val("");
            $("#c_naturalidade").val("");
            $("#c_pne").val("");
            $("#c_nec_especial").val("");
            $("#c_cpf").val("");
            $("#c_celular").val("");
            $("#c_pis").val("");
            $("#c_estado_civil").val("");
            $("#c_cnh").val("");
            $("#c_reservista").val("");
            $("#c_rg").val("");
            $("#c_orgao_expedidor").val("");
            $("#c_titulo_eleitoral").val("");
            $("#c_zona_eleitoral").val("");
            $("#c_secao_eleitoral").val("");
            $("#c_nome_mae").val("");
            $("#c_nome_pai").val("");
            $("#c_linkedin").val("");
            $("#c_instagram").val("");
            $("#c_facebook").val("");
            $("#c_twitter").val("");
            // $("#c_departamento").val("");
            $("#c_cargo").val("");
            $("#c_gh").val("");
            $("#c_cnh_vencimento").val("");
            $("#c_ctps_num").val("");
            $("#c_ctps_serie").val("");
            $("#c_banco_financeiro").val("");
            $("#c_agencia_financeiro").val("");
            $("#c_conta_financeito").val("");
            $("#c_dt_admissao").val("");
            $("#c_matricula").val("");
            $("#c_salario").val("");
            // $("#c_tipo_contrato").val("");
            $("#c_duracao_contrato").val("");
            $("#c_venc_contrato").val("");
            $("#c_per_experiencia").val("");
            $("#c_cep").val("");
            $("#c_logradouro").val("");
            $("#c_numero_end").val("");
            $("#c_complemento").val("");
            $("#c_bairro").val("");
            $("#c_uf").val("");
            $("#c_pais_end").val("");
            $("#c_cidade").val("");
            $("#c_cat_cnh").val("");

            $("#btn_novo_reg").prop("disabled", false);
            // $("#btn_salvar_reg").prop("disabled", false);
            $("#btn_excluir_reg").prop("disabled", true);
            $("#btn_demitir_col").prop("disabled", true);
            $("#btn_info_colaborador").prop("disabled", true);



        }



        function func_salvar_registro() {

            let v_naturalidade

            if ($("#c_select_nacionalidade").val() == 32) {
                v_naturalidade = $("#c_select_naturalidade").val();
                $("#naturalidade_brasil").show();
            } else {
                v_naturalidade = $("#c_naturalidade").val();
            }

            var v_acao = $("#c_acao").val();
            if (v_acao != "EV_NOVO") {
                v_acao = "EV_SALVAR";
            }





            if ($("#c_nome").val().length > 5 &&
                $("#c_select_sexo").val().length > 0 &&
                $("#c_dt_nasc").val().length > 5 &&
                $("#c_email").val().length > 5 &&
                $("#c_select_escolaridade").val().length > 0 &&
                $("#c_select_nacionalidade").val().length > 0 &&
                $("#c_select_est_civil").val().length > 0 &&
                $("#c_select_departamento").val().length > 0 &&
                $("#c_select_cargo_novo").val().length > 0 &&
                $("#c_select_gh").val().length > 0 &&
                $("#c_matricula").val().length > 0 &&
                $("#c_dt_admissao").val().length > 5 &&
                $("#c_tipo_contrato").val().length > 0) {
                $.ajax({
                    type: "POST",
                    url: "lib/lib_cad_colaborador.php",
                    data: {
                        "v_id": $("#c_id").val(),
                        "v_acao": v_acao,
                        "v_venc_contrato": $("#c_venc_contrato").val(),
                        "v_id": $("#c_id").val(),
                        "v_nome": $("#c_nome").val(),
                        "v_sexo": $("#c_select_sexo").val(),
                        "v_dt_nasc": $("#c_dt_nasc").val(),
                        "v_email": $("#c_email").val(),
                        "v_Nacionalidade": $("#c_select_nacionalidade").val(),
                        "v_Naturalidade": v_naturalidade,
                        "v_Pne": $("#c_pne").val(),
                        "v_Necessidade": $("#c_nec_especial").val(),
                        "v_cpf": $("#c_cpf").val().replace(/[^\d]+/g, ''),
                        "v_Pis": $("#c_pis").val(),
                        "v_Est_Civil": $("#c_select_est_civil").val(),
                        "v_escolaridade": $("#c_select_escolaridade").val(),
                        "v_Cnh": $("#c_cnh").val(),
                        "v_Reservista": $("#c_reservista").val(),
                        "v_Rg": $("#c_rg").val(),
                        "v_rg_expedicao": $("#c_data_expedicao").val(),
                        "v_Orgao_expedidor": $("#c_orgao_expedidor").val(),
                        "v_Tit_Eleitor": $("#c_titulo_eleitoral").val(),
                        "v_Zona_Eleitoral": $("#c_zona_eleitoral").val(),
                        "v_Secao_Eleitoral": $("#c_secao_eleitoral").val(),
                        "v_Nome_Mae": $("#c_nome_mae").val(),
                        "v_Nome_Pai": $("#c_nome_pai").val(),
                        "v_Id_Dpto": $("#c_select_departamento").val(),
                        "v_Id_Cargo": $("#c_select_cargo_novo").val(),
                        "v_cnh_vencimento": $("#c_cnh_vencimento").val(),
                        "v_ctps_num": $("#c_ctps_num").val(),
                        "v_ctps_serie": $("#c_ctps_serie").val(),
                        "v_banco_financeiro": $("#c_select_banco").val(),
                        "v_agencia_financeiro": $("#c_agencia_financeiro").val(),
                        "v_conta_financeito": $("#c_conta_financeito").val(),
                        "v_conta_digito": $("#c_digito").val(),
                        "v_dt_admissao": $("#c_dt_admissao").val(),
                        "v_matricula": $("#c_matricula").val(),
                        "v_salario": $("#c_salario_novo").val(),
                        "v_tipo_contrato": $("#c_tipo_contrato").val(),
                        "v_duracao_contrato": $("#c_duracao_contrato").val(),
                        "v_pro_contrato": $("#c_pro_contrato").val(),
                        "v_per_experiencia": $("#c_per_experiencia").val(),
                        "v_cep": $("#c_cep").val(),
                        "v_logradouro": $("#c_logradouro").val(),
                        "v_numero_end": $("#c_numero_end").val(),
                        "v_complemento": $("#c_complemento").val(),
                        "v_bairro": $("#c_bairro").val(),
                        "v_uf": $("#c_uf").val(),
                        "v_pais_end": $("#c_pais_end").val(),
                        "v_cidade": $("#c_cidade").val(),
                        "v_cnh_cat": $("#c_cat_cnh").val()
                    },
                    success: function(data) {
                        var v_json = JSON.parse(data);
                        Swal.fire({
                            icon: v_json.msg_ev,
                            title: v_json.msg_titulo,
                            text: v_json.msg
                        })

                        if (v_json.msg_ev == "success") {

                            limpa_form_cadastro();
                            $("#box_form_titulo").hide();
                            $("#box_form").hide();
                            $("#box_form_footer").hide();
                            $("#box_tab_titulo").show();
                            $("#box_tab1").show();
                            $("#box_tab_footer").show();
                            func_carrega_tab();
                        }
                    },
                    // error: function(request, status, erro) {
                    //     Swal.fire({
                    //         icon: "error",
                    //         title: "FALHA!",
                    //         // text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                    //     })
                    // }
                });
            }
            // else {

            //     Swal.fire({
            //         icon: "error",
            //         title: "FALHA!",
            //         text: "Preencha a escolaridade."
            //     })

            // }
        }



        function func_excluir_registro() {

            Swal.fire({
                title: 'Você tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, pode excluir!'
            }).then((result) => {
                if (result.value) {

                    v_acao = "EV_EXCLUIR";
                    v_id = $("#c_id").val();
                    v_matricula = $("#c_matricula").val();
                    v_id_usuario = $("#c_id_usuario").val();


                    if (v_id > 0) {

                        $.ajax({
                            type: "POST",
                            url: "lib/lib_cad_colaborador.php",
                            data: {
                                "v_acao": v_acao,
                                "v_id": v_id,
                                "v_matricula": v_matricula,
                                "v_id_usuario": v_id_usuario

                            },
                            success: function(data) {
                                var v_json = JSON.parse(data);
                                Swal.fire(
                                    v_json.msg_titulo,
                                    v_json.msg,
                                    v_json.msg_ev
                                )

                                if (v_json.msg_ev == "success") {

                                    limpa_form_cadastro();
                                    $("#box_form_titulo").hide();
                                    $("#box_form").hide();
                                    $("#box_form_footer").hide();
                                    $("#box_tab_titulo").show();
                                    $("#box_tab1").show();
                                    $("#box_tab_footer").show();
                                    func_carrega_tab();
                                }
                            },
                            error: function(request, status, erro) {
                                swal("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                            }
                        });
                    } else {

                        Swal.fire({
                            icon: "error",
                            title: "FALHA!",
                            text: "Selecione um registro."
                        })

                    }
                }
            })
        }


        function func_bloqueio_conta() {

            $("#btn_demitir_col").prop("disabled", true);
            v_acao = "EV_BLOQ";
            v_id = $("#c_id").val();

            $.ajax({
                type: "POST",
                url: "lib/lib_cad_colaborador.php",
                data: {
                    "v_acao": v_acao,
                    "v_id": v_id
                },
                success: function(data) {
                    var v_json = JSON.parse(data);
                    Swal.fire({
                        icon: v_json.msg_ev,
                        title: v_json.msg_titulo,
                        text: v_json.msg
                    })
                },
                error: function(request, status, erro) {
                    Swal.fire({
                        icon: "error",
                        title: "FALHA!",
                        text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                    })
                }
            });
        }



        function func_email_confirma() {

            $("#btn_demitir_col").prop("disabled", true);
            $("#btn_info_colaborador").prop("disabled", true);
            v_acao = "EV_EMAIL";
            v_id = $("#c_id").val();

            $.ajax({
                type: "POST",
                url: "lib/lib_cad_colaborador.php",
                data: {
                    "v_acao": v_acao,
                    "v_id": v_id
                },
                success: function(data) {
                    var v_json = JSON.parse(data);
                    Swal.fire({
                        icon: v_json.msg_ev,
                        title: v_json.msg_titulo,
                        text: v_json.msg
                    })

                    if (v_json.msg_ev == "success") {
                        func_carrega_tab();
                    }
                },
                error: function(request, status, erro) {
                    Swal.fire({
                        icon: "error",
                        title: "FALHA!",
                        text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                    })
                }
            });
        }


        function limpa_form_cadastro() {
            $("#c_id").val("");
            $("#c_nome").val("");
            $("#c_celular").val("");
            $("#c_celular_emergencia").val("");
            $("#c_sexo").val("");
            $("#c_dt_nasc").val("");
            $("#c_email").val("");
            $("#c_email_pessoal").val("");
            $("#c_nacionalidade").val("");
            $("#c_naturalidade").val("");
            $("#c_pne").val("");
            $("#c_nec_especial").val("");
            $("#c_cpf").val("");
            $("#c_celular").val("");
            $("#c_pis").val("");
            $("#c_estado_civil").val("");
            $("#c_cnh").val("");
            $("#c_reservista").val("");
            $("#c_rg").val("");
            $("#c_orgao_expedidor").val("");
            $("#c_titulo_eleitoral").val("");
            $("#c_zona_eleitoral").val("");
            $("#c_secao_eleitoral").val("");
            $("#c_nome_mae").val("");
            $("#c_nome_pai").val("");
            $("#c_linkedin").val("");
            $("#c_instagram").val("");
            $("#c_facebook").val("");
            $("#c_twitter").val("");
            // $("#c_departamento").val("");
            $("#c_cargo").val("");
            $("#c_gh").val("");
            $("#c_cnh_vencimento").val("");
            $("#c_ctps_num").val("");
            $("#c_ctps_serie").val("");
            $("#c_banco_financeiro").val("");
            $("#c_agencia_financeiro").val("");
            $("#c_conta_financeito").val("");
            $("#c_dt_admissao").val("");
            $("#c_matricula").val("");
            $("#c_salario").val("");
            // $("#c_tipo_contrato").val("");
            $("#c_duracao_contrato").val("");
            $("#c_venc_contrato").val("");
            $("#c_per_experiencia").val("");
            $("#c_cep").val("");
            $("#c_logradouro").val("");
            $("#c_numero_end").val("");
            $("#c_complemento").val("");
            $("#c_bairro").val("");
            $("#c_uf").val("");
            $("#c_pais_end").val("");
            $("#c_cidade").val("");
            $("#c_cat_cnh").val("");
        }

        function limpa_formulário_cep() {
            // Limpa valores do formulário de cep.
            $("#c_logradouro").val("");
            $("#c_bairro").val("");
            $("#c_cidade").val("");
            $("#c_uf").val("");
            $("#ibge").val("");
        }

        function func_cad_novo_dep() {
            $.ajax({
                type: "POST",
                url: "lib/lib_cad_colaborador.php",
                data: {
                    "v_acao": "EV_NOVO_DEP",
                    "v_id": $("#c_id").val(),
                    "v_data": $("#c_dt_dep").val(),
                    "v_departamento": $("#c_select_dep").val(),
                    "v_id_dep": $('#c_id_dep').val(),
                    "v_matricula": $("#c_matricula").val(),
                    "v_id_tab_departamentos": $('#c_id_tab_departamentos').val()

                },
                success: function(data) {
                    var v_json = JSON.parse(data);
                    Swal.fire({
                        icon: v_json.msg_ev,
                        title: v_json.msg_titulo,
                        text: v_json.msg
                    })

                    if (v_json.msg_ev == "success") {
                        var options = '<option value="0">SELECIONE UM DEPARTAMENTO PARA ALTERAR</option>';
                        var v_hist_tabela = '';
                        $("#c_select_h_dep").empty();
                        $("#c_tabela_h_dep>tbody").empty();
                        v_json.departamentos.forEach(element => {
                            let v_data_split = element.Data_dep.split('-')
                            let v_data_formatada = `${v_data_split[2]}/${v_data_split[1]}/${v_data_split[0]}`
                            options += '<option value="' + element.Id_dep + ' - ' + element.Id + '">' + `${v_data_formatada} - ${element.Nome_dep}` + '</option>';
                            v_hist_tabela += `<tr> <td>${v_data_formatada}</td><td>${element.Nome_dep}</td></tr>`
                        });
                        $('#c_select_h_dep').html(options);
                        $("#c_tabela_h_dep>tbody").html(v_hist_tabela);

                        $('#c_id_dep').val("");
                        $(`#c_select_dep`).prop('selectedIndex', 0);
                        $("#c_dt_dep").val("");

                        $('#modalIncluirDepartamento').modal('hide');
                        // func_select(element.Id);
                    }
                },
                error: function(request, status, erro) {
                    Swal.fire({
                        icon: "error",
                        title: "FALHA!",
                        text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                    })
                }
            });
        }

        function func_troca_dep_select() {
            let v_cod_dep_split = $('#c_select_h_dep').val().split(' - ');
            let v_dados_dep = $('#c_select_h_dep option:selected').text();
            let v_dados_dep_split = v_dados_dep.split(' - ');
            let v_data_split = v_dados_dep_split[0].split('/');
            let v_data_formatada = `${v_data_split[2]}-${v_data_split[1]}-${v_data_split[0]}`;
            $('#c_dt_dep').val(v_data_formatada);
            $("#c_id_dep").val(v_cod_dep_split[1]);
            // alert($(`#c_select_dep>option[value=${v_cod_dep_split[0]}]`).val())
            $(`#c_select_dep>option:selected`).attr('selected', false);
            $(`#c_select_dep>option[value=${v_cod_dep_split[0]}]`).attr('selected', true);
        }


        //MANIPULAÇAO DE CARGOS
        function func_cad_novo_cargo() {
            $.ajax({
                type: "POST",
                url: "lib/lib_cad_colaborador.php",
                data: {
                    "v_acao": "EV_NOVO_CARGO",
                    "v_id": $("#c_id").val(),
                    "v_data": $("#c_dt_cargo").val(),
                    "v_cargo": $("#c_select_cargo").val(),
                    "v_matricula": $("#c_matricula").val(),
                    "v_id_cargo": $('#c_id_cargo').val()
                },
                success: function(data) {
                    var v_json = JSON.parse(data);
                    Swal.fire({
                        icon: v_json.msg_ev,
                        title: v_json.msg_titulo,
                        text: v_json.msg
                    })

                    if (v_json.msg_ev == "success") {
                        var options = '<option value="0">SELECIONE UM CARGO PARA ALTERAR</option>';
                        var v_hist_tabela = '';
                        $("#c_select_h_cargo").empty();
                        $("#c_tabela_h_cargo>tbody").empty();
                        v_json.cargos.forEach(element => {
                            let v_data_split = element.Data_cargo.split('-')
                            let v_data_formatada = `${v_data_split[2]}/${v_data_split[1]}/${v_data_split[0]}`
                            options += '<option value="' + element.Id_cargo + ' - ' + element.Id + '">' + `${v_data_formatada} - ${element.Nome_cargo}` + '</option>';
                            v_hist_tabela += `<tr> <td>${v_data_formatada}</td><td>${element.Nome_cargo}</td></tr>`
                        });
                        $('#c_select_h_cargo').html(options);
                        $("#c_tabela_h_cargo>tbody").html(v_hist_tabela);

                        $('#c_id_cargo').val("");
                        $(`#c_select_cargo`).prop('selectedIndex', 0);
                        $("#c_dt_cargo").val("");
                    }
                },
                error: function(request, status, erro) {
                    Swal.fire({
                        icon: "error",
                        title: "FALHA!",
                        text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                    })
                }
            });
        }

        function func_troca_cargo_select() {
            let v_cod_cargo_split = $('#c_select_h_cargo').val().split(' - ');
            let v_dados_cargo = $('#c_select_h_cargo option:selected').text();
            let v_dados_cargo_split = v_dados_cargo.split(' - ');
            let v_data_split = v_dados_cargo_split[0].split('/');
            let v_data_formatada = `${v_data_split[2]}-${v_data_split[1]}-${v_data_split[0]}`;
            $('#c_dt_cargo').val(v_data_formatada);
            $("#c_id_cargo").val(v_cod_cargo_split[1]);
            $(`#c_select_cargo>option:selected`).attr('selected', false);
            $(`#c_select_cargo>option[value=${v_cod_cargo_split[0]}]`).attr('selected', true);
        }

        //MANIPULAÇAO DE GH
        function func_cad_novo_gh() {
            $.ajax({
                type: "POST",
                url: "lib/lib_cad_colaborador.php",
                data: {
                    "v_acao": "EV_NOVO_GH",
                    "v_id": $("#c_id").val(),
                    "v_id_usuario": $("#c_id_usuario").val(),
                    "v_data": $("#c_dt_gh").val(),
                    "v_gh": $("#c_select_gh").val(),
                    "v_id_gh": $('#c_id_gh').val()

                },
                success: function(data) {
                    var v_json = JSON.parse(data);
                    Swal.fire({
                        icon: v_json.msg_ev,
                        title: v_json.msg_titulo,
                        text: v_json.msg
                    })

                    if (v_json.msg_ev == "success") {
                        var options = '<option value="0">SELECIONE UM GH PARA ALTERAR</option>';
                        var v_hist_tabela = '';
                        $("#c_select_h_gh").empty();
                        $("#c_tabela_h_gh>tbody").empty();
                        v_json.cargos.forEach(element => {
                            let v_data_split = element.Data_gh.split('-')
                            let v_data_formatada = `${v_data_split[2]}/${v_data_split[1]}/${v_data_split[0]}`
                            options += '<option value="' + element.Id_gh + ' - ' + element.Id + '">' + `${v_data_formatada} - ${element.Nome_gh}` + '</option>';
                            v_hist_tabela += `<tr> <td>${v_data_formatada}</td><td>${element.Nome_gh}</td></tr>`
                        });
                        $('#c_select_h_gh').html(options);
                        $("#c_tabela_h_gh>tbody").html(v_hist_tabela);

                        $('#c_id_gh').val("");
                        $(`#c_select_gh`).prop('selectedIndex', 0);
                        $("#c_dt_gh").val("");
                    }
                },
                error: function(request, status, erro) {
                    Swal.fire({
                        icon: "error",
                        title: "FALHA!",
                        text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                    })
                }
            });
        }

        function func_troca_gh_select() {
            let v_cod_gh_split = $('#c_select_h_gh').val().split(' - ');
            let v_dados_gh = $('#c_select_h_gh option:selected').text();
            let v_dados_gh_split = v_dados_gh.split(' - ');
            let v_data_split = v_dados_gh_split[0].split('/');
            let v_data_formatada = `${v_data_split[2]}-${v_data_split[1]}-${v_data_split[0]}`;
            $('#c_dt_gh').val(v_data_formatada);
            $("#c_id_gh").val(v_cod_gh_split[1]);
            $(`#c_select_gh>option:selected`).attr('selected', false);
            $(`#c_select_gh>option[value=${v_cod_gh_split[0]}]`).attr('selected', true);
        }


        //MANIPULAÇAO DE SALARIO
        function func_cad_novo_salario() {
            $.ajax({
                type: "POST",
                url: "lib/lib_cad_colaborador.php",
                data: {
                    "v_acao": "EV_NOVO_SALARIO",
                    "v_id": $("#c_id").val(),
                    "v_data": $("#c_dt_salario").val(),
                    "v_salario": $("#c_salario_atualiza").val(),
                    "v_matricula": $("#c_matricula"),
                    "v_id_salario": $('#c_id_salario').val()
                },
                success: function(data) {
                    var v_json = JSON.parse(data);
                    Swal.fire({
                        icon: v_json.msg_ev,
                        title: v_json.msg_titulo,
                        text: v_json.msg
                    })

                    if (v_json.msg_ev == "success") {
                        var options = '<option value="0">SELECIONE UM SALARIO PARA ALTERAR</option>';
                        var v_hist_tabela = '';
                        $("#c_select_h_salario").empty();
                        $("#c_tabela_h_salario>tbody").empty();
                        v_json.salarios.forEach(element => {
                            let v_data_split = element.Data_salario.split('-')
                            let v_data_formatada = `${v_data_split[2]}/${v_data_split[1]}/${v_data_split[0]}`
                            options += '<option value="' + element.Id + '">' + `${v_data_formatada} - ${element.Salario}` + '</option>';
                            v_hist_tabela += `<tr> <td>${v_data_formatada}</td><td>${element.Salario}</td></tr>`
                        });
                        $('#c_select_h_salario').html(options);
                        $("#c_tabela_h_salario>tbody").html(v_hist_tabela);

                        $('#c_id_salario').val("");
                        $("#c_salario_atualiza").val("");
                        $("#c_dt_salario").val("");
                    }
                },
                error: function(request, status, erro) {
                    Swal.fire({
                        icon: "error",
                        title: "FALHA!",
                        text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                    })
                }
            });
        }

        function func_troca_salario_select() {
            let v_cod_salario_split = $('#c_select_h_salario').val().split(' - ');
            let v_dados_salario = $('#c_select_h_salario option:selected').text();
            let v_dados_salario_split = v_dados_salario.split(' - ');
            let v_data_split = v_dados_salario_split[0].split('/');
            let v_data_formatada = `${v_data_split[2]}-${v_data_split[1]}-${v_data_split[0]}`;
            $('#c_dt_salario').val(v_data_formatada);
            $("#c_id_salario").val($('#c_select_h_salario option:selected').val());
            $("#c_salario_atualiza").val(v_dados_salario_split[1])
        }

        function func_reseta_select() {
            $(`#c_select_cargo>option:selected`).attr('selected', false);
            $(`#c_select_departamento>option:selected`).attr('selected', false);
            $(`#c_select_sexo>option:selected`).attr('selected', false);
            // $(`#c_select_escolaridade>option:selected`).attr('selected', false);
            $(`#c_select_nacionalidade>option:selected`).attr('selected', false);
            $(`#c_select_est_civil>option:selected`).attr('selected', false);
            $(`#c_select_banco>option:selected`).attr('selected', false);
            $(`#c_select_gh>option:selected`).attr('selected', false);
            $(`#c_tipo_contrato>option:selected`).attr('selected', false);
            $(`#c_select_nacionalidade>option:selected`).attr('selected', false);
            $(`#c_select_naturalidade>option:selected`).attr('selected', false);
            $("#c_select_departamento").prop('selectedIndex', 0);
            $("#c_select_cargo_novo").prop('selectedIndex', 0);
            $("#c_select_sexo").prop('selectedIndex', 0);
            // $("#c_select_escolaridade").prop('selectedIndex', 0);
            $("#c_select_nacionalidade").prop('selectedIndex', 0);
            $('#c_select_est_civil').prop('selectedIndex', 0);
            $("#c_select_banco").prop('selectedIndex', 0);
            $("#c_select_gh").prop('selectedIndex', 0);
            $("#c_tipo_contrato").prop('selectedIndex', 0);
            $("#c_select_nacionalidade").prop('selectedIndex', 0);
            $("#c_select_naturalidade").prop('selectedIndex', 0);
        }

        function func_preenche_select() {
            $.ajax({
                type: "POST",
                url: "lib/lib_cad_colaborador.php",
                data: {
                    "v_acao": "LISTA_DADOS_NEC"
                },
                success: function(data) {
                    var options = '<option value="-">SELECIONE UM DEPARTAMENTO</option>';
                    $("#c_select_departamento").empty();
                    data.Departamentos.forEach(element => {
                        options += '<option value="' + element.Id_dep + '">' + element.Departamento + '</option>';
                    });
                    $('#c_select_departamento').html(options);

                    var options = '<option value="-">SELECIONE UM CARGO</option>';
                    $("#c_select_cargo_novo").empty();
                    data.Cargos.forEach(element => {
                        options += '<option value="' + element.Id_cargo + '">' + element.Cargo + '</option>';
                    });
                    $('#c_select_cargo_novo').html(options);

                    var options = '<option value="-">SELECIONE UM SEXO</option>';
                    $("#c_select_sexo").empty();
                    data.Sexos.forEach(element => {
                        options += '<option value="' + element.Id_sexo + '">' + element.Sexo + '</option>';
                    });
                    $('#c_select_sexo').html(options);

                    var options = '<option value="0" selected>SELECIONE UMA ESCOLARIDADE</option>';
                    $("#c_select_escolaridade").empty();
                    data.Escolaridades.forEach(element => {
                        options += '<option value="' + element.Id_escolaridade + '">' + element.Escolaridade + '</option>';
                    });
                    $('#c_select_escolaridade').html(options);

                    var options = '<option value="-">SELECIONE UMA NACIONALIDADE</option>';
                    $("#c_select_nacionalidade").empty();
                    data.Paises.forEach(element => {
                        options += '<option value="' + element.Id_pais + '">' + element.Pais + '</option>';
                    });
                    $('#c_select_nacionalidade').html(options);

                    var options = '<option value="-">SELECIONE UM ESTADO CIVIL</option>';
                    $("#c_select_est_civil").empty();
                    data.Estado_civil.forEach(element => {
                        options += '<option value="' + element.Id_est_civil + '">' + element.Estado_civil + '</option>';
                    });
                    $('#c_select_est_civil').html(options);

                    var options = '<option value="0">SELECIONE UM BANCO</option>';
                    $("#c_select_banco").empty();
                    data.Bancos.forEach(element => {
                        options += '<option value="' + element.Codigo + '">' + element.Nome + '</option>';
                    });
                    $('#c_select_banco').html(options);

                    var options = '<option value="-">SELECIONE UM GRUPO HIERARQUICO</option>';
                    $("#c_select_gh_novo").empty();
                    data.Gh.forEach(element => {
                        options += '<option value="' + element.Id + '">' + element.Nome + '</option>';
                    });
                    $('#c_select_gh_novo').html(options);

                    var options = '<option value="-">SELECIONE UM TIPO DE CONTRATO</option>';
                    $("#c_tipo_contrato").empty();
                    data.Tipos_contrato.forEach(element => {
                        options += '<option value="' + element.Id + '">' + element.Tipo_contrato + '</option>';
                    });
                    $('#c_tipo_contrato').html(options);
                },
                error: function(request, status, erro) {
                    Swal.fire({
                        icon: "error",
                        title: "FALHA!",
                        text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                    })
                }
            });
        }

        function func_select_naturalidade() {

            $.ajax({
                type: "GET",
                url: "https://servicodados.ibge.gov.br/api/v1/localidades/municipios",
                success: function(data) {

                    var options = '<option value="0">SELECIONE UM MUNICIPIO</option>';
                    $("#c_select_naturalidade").empty();
                    data.forEach(element => {
                        options += '<option value="' + element.id + '">' + element.nome + '</option>';
                    });
                    $('#c_select_naturalidade').html(options);
                }
            });

        }

        function func_muda_campo_nacionalidade() {

            if ($("#c_select_nacionalidade").val() == 32) {
                $("#naturalidade").hide();
                $("#naturalidade_brasil").show();
            } else {
                $("#naturalidade").show();
                $("#naturalidade_brasil").hide();
            }

        }

        //Quando o campo cep perde o foco.
        $("#c_cep").blur(function() {

            //Nova variável "cep" somente com dígitos.
            var cep = $(this).val().replace(/\D/g, '');

            //Verifica se campo cep possui valor informado.
            if (cep != "") {

                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;

                //Valida o formato do CEP.
                if (validacep.test(cep)) {

                    //Preenche os campos com "..." enquanto consulta webservice.
                    $("#c_logradouro").val("...");
                    $("#c_bairro").val("...");
                    $("#c_cidade").val("...");
                    $("#c_uf").val("...");
                    $("#ibge").val("...");

                    //Consulta o webservice viacep.com.br/
                    $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {

                        if (!("erro" in dados)) {
                            //Atualiza os campos com os valores da consulta.
                            $("#c_logradouro").val(dados.logradouro);
                            $("#c_bairro").val(dados.bairro);
                            $("#c_cidade").val(dados.localidade);
                            $("#c_uf").val(dados.uf);
                            $("#ibge").val(dados.ibge);
                        } //end if.
                        else {
                            //CEP pesquisado não foi encontrado.
                            limpa_formulário_cep();
                            alert("CEP não encontrado.");
                        }
                    });
                } //end if.
                else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        });
    </script>
</body>

</html>