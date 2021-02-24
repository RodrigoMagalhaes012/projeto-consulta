<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0050") == 0) {
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

        .load {
            width: 100px;
            height: 100px;
            position: absolute;
            top: 40%;
            left: 50%;
            color: #523B8F;
        }
    </style>
</head>

<body style="overflow-x: hidden;">

    <!-- <div class="load"> <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
        <span class="sr-only">Loading...</span>
    </div> -->
    <div class="row" id="competencia">
        <div class="col-lg-12">
            <label for="c_competencia">Competencia</label>
            <select class="form-control class_inputs" id="c_competencia">
                <!-- <option value="">SELECIONE UMA COMPETENCIA</option> -->
                <option value="2021-01">2021/01</option>
                <option value="2021-02">2021/02</option>
                <option value="2021-03">2021/03</option>
                <option value="2021-04">2021/04</option>
                <option value="2021-05">2021/05</option>
                <option value="2021-06">2021/06</option>
            </select>
        </div>
    </div>
    <div class="tab-container" id="tab">

        <ul class="nav nav-tabs" id="tabs" role="tablist">
            <li role="presentation" class="active">
                <a data-toggle="tab" href="#tab-1" aria-expanded="false" onclick="$('#competencia').show()">
                    G-Click
                </a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#tab-2" aria-expanded="false" onclick="$('#competencia').show()">
                    Marketing
                </a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#tab-3" aria-expanded="false" onclick="$('#competencia').show()">
                    Jurídico
                </a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#tab-4" aria-expanded="false" onclick="$('#competencia').show()">
                    Satisfação TI
                </a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#tab-5" aria-expanded="false" onclick="$('#competencia').show()">
                    Vagas Fechadas RH
                </a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#tab-6" aria-expanded="false" onclick="$('#competencia').show()">
                    Trello Inovação
                </a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#tab-7" aria-expanded="false" onclick="$('#competencia').show()">
                    Satisfação Inovação
                </a>
            </li>
            <li role="presentation">
                <a data-toggle="modal" data-target="#modal" aria-expanded="false" style="cursor: pointer;">
                    Fechamento Balancete
                </a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#tab-8" aria-expanded="false" onclick="func_abre_validacao()">
                    Validação
                </a>
            </li>
            <li role="presentation">
                <a onclick="func_lancamento_liderança()" style="cursor: pointer;">
                    Calcular Liderança
                </a>
            </li>
            <li role="presentation">
                <a onclick="func_relatorio_valores()" style="cursor: pointer;">
                    Gerar relatório Financeiro
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" id="tab-1" class="tab-pane active">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none; overflow-x: hidden;">
                                    <input id="c_acao" type="hidden" value="">
                                    <div class="box-body" style="height: auto;">
                                        <div class="col-md-12">
                                            <div class="col-sm-6">
                                                <div style="margin-left: 80px; margin-bottom: 40px;" class="form-group col-sm-12 col">
                                                    <h3>Atualização de dados</h3>
                                                    <form id="dados_usuario" method="POST" enctype="multipart/form-data">
                                                        <i class="fa fa-cloud-upload fa-5x" aria-hidden="true"></i>
                                                        <label>* Upload de Arquivo </label>
                                                        <input type="file" name="arquivo">
                                                        <input id="importartxt" type="file" name="importartxt" style="visibility: hidden;">
                                                        <button class="btn btn-primary" type="button" onclick="func_upload_usuarios()" value="Importar"> <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                            Upload de Arquivo </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div style="margin-left: 80px; margin-bottom: 40px;" class="form-group col-sm-12 col">
                                                    <h3>Upload das tarefas</h3>
                                                    <form id="form_upload" method="POST" enctype="multipart/form-data">
                                                        <i class="fa fa-cloud-upload fa-5x" aria-hidden="true"></i>
                                                        <label>* Upload de Arquivo </label>
                                                        <input type="file" disabled name="arquivo_tarefas" id="arquivo_tarefas">
                                                        <input id="importartxt" type="file" name="importartxt" style="visibility: hidden;">
                                                        <button class="btn btn-primary" disabled id="btn_tarefas" type="button" onclick="func_upload_tarefas()" value="Importar"> <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                            Upload de Arquivo </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div role="tabpanel" id="tab-2" class="tab-pane">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none; overflow-x: hidden;">
                                    <input id="c_acao" type="hidden" value="">
                                    <div class="box-body" style="height: auto;">
                                        <div class="col-md-12">
                                            <div class="col-sm-12">
                                                <div style="margin-left: 80px; margin-bottom: 40px;" class="form-group col-sm-12 col">
                                                    <h3>Upload Tarefas Marketing</h3>
                                                    <form id="dados_mkt" method="POST" enctype="multipart/form-data">
                                                        <i class="fa fa-cloud-upload fa-5x" aria-hidden="true"></i>
                                                        <label>* Upload de Arquivo </label>
                                                        <input type="file" name="arquivo_mkt">
                                                        <input id="importartxt" type="file" name="importartxt" style="visibility: hidden;">
                                                        <button class="btn btn-primary" type="button" onclick="func_upload_mkt()" value="Importar"> <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                            Upload de Arquivo </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" id="tab-3" class="tab-pane">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none; overflow-x: hidden;">
                                    <input id="c_acao" type="hidden" value="">
                                    <div class="box-body" style="height: auto;">
                                        <div class="col-md-12">
                                            <div class="col-sm-12">
                                                <div style="margin-left: 80px; margin-bottom: 40px;" class="form-group col-sm-12 col">
                                                    <h3>Upload Tarefas Jurídico</h3>
                                                    <form id="dados_juridico" method="POST" enctype="multipart/form-data">
                                                        <i class="fa fa-cloud-upload fa-5x" aria-hidden="true"></i>
                                                        <label>* Upload de Arquivo </label>
                                                        <input type="file" name="arquivo_juridico">
                                                        <input id="importartxt" type="file" name="importartxt" style="visibility: hidden;">
                                                        <button class="btn btn-primary" type="button" onclick="func_upload_juridico()" value="Importar"> <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                            Upload de Arquivo </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div role="tabpanel" id="tab-4" class="tab-pane">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none; overflow-x: hidden;">
                                    <input id="c_acao" type="hidden" value="">
                                    <div class="box-body" style="height: auto;">
                                        <div class="col-md-12">
                                            <div class="col-sm-12">
                                                <div style="margin-left: 80px; margin-bottom: 40px;" class="form-group col-sm-12 col">
                                                    <h3>Upload Satisfação TI</h3>
                                                    <form id="satisfacao_ti" method="POST" enctype="multipart/form-data">
                                                        <i class="fa fa-cloud-upload fa-5x" aria-hidden="true"></i>
                                                        <label>* Upload de Arquivo </label>
                                                        <input type="file" name="arquivo">
                                                        <input id="importartxt" type="file" name="importartxt" style="visibility: hidden;">
                                                        <button class="btn btn-primary" type="button" onclick="func_upload_ti()" value="Importar"> <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                            Upload de Arquivo </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div role="tabpanel" id="tab-5" class="tab-pane">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none; overflow-x: hidden;">
                                    <input id="c_acao" type="hidden" value="">
                                    <div class="box-body" style="height: auto;">
                                        <div class="col-md-12">
                                            <div class="col-sm-12">
                                                <div style="margin-left: 80px; margin-bottom: 40px;" class="form-group col-sm-12 col">
                                                    <h3>Upload Vagas Fechadas RH</h3>
                                                    <form id="vagas_rh" method="POST" enctype="multipart/form-data">
                                                        <i class="fa fa-cloud-upload fa-5x" aria-hidden="true"></i>
                                                        <label>* Upload de Arquivo </label>
                                                        <input type="file" name="arquivo">
                                                        <input id="importartxt" type="file" name="importartxt" style="visibility: hidden;">
                                                        <button class="btn btn-primary" type="button" onclick="func_upload_vagas_rh()" value="Importar"> <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                            Upload de Arquivo </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div role="tabpanel" id="tab-6" class="tab-pane">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none; overflow-x: hidden;">
                                    <input id="c_acao" type="hidden" value="">
                                    <div class="box-body" style="height: auto;">
                                        <div class="col-md-12">
                                            <div class="col-sm-12">
                                                <div style="margin-left: 80px; margin-bottom: 40px;" class="form-group col-sm-12 col">
                                                    <h3>Upload Trello da Inovação</h3>
                                                    <form id="trello_inovacao" method="POST" enctype="multipart/form-data">
                                                        <i class="fa fa-cloud-upload fa-5x" aria-hidden="true"></i>
                                                        <label>* Upload de Arquivo </label>
                                                        <input type="file" name="arquivo">
                                                        <input id="importartxt" type="file" name="importartxt" style="visibility: hidden;">
                                                        <button class="btn btn-primary" type="button" onclick="func_upload_trello()" value="Importar"> <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                            Upload de Arquivo </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" id="tab-7" class="tab-pane">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none; overflow-x: hidden;">
                                    <input id="c_acao" type="hidden" value="">
                                    <div class="box-body" style="height: auto;">
                                        <div class="col-md-12">
                                            <div class="col-sm-12">
                                                <div style="margin-left: 80px; margin-bottom: 40px;" class="form-group col-sm-12 col">
                                                    <h3>Upload Satisfação Inovação</h3>
                                                    <form id="satisfacao_inovacao" method="POST" enctype="multipart/form-data">
                                                        <i class="fa fa-cloud-upload fa-5x" aria-hidden="true"></i>
                                                        <label>* Upload de Arquivo </label>
                                                        <input type="file" name="arquivo">
                                                        <input id="importartxt" type="file" name="importartxt" style="visibility: hidden;">
                                                        <button class="btn btn-primary" type="button" onclick="func_upload_inovacao()" value="Importar"> <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                            Upload de Arquivo </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div role="tabpanel" id="tab-8" class="tab-pane">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row" style="padding: 10px;">
                        <div class="col-sm-6">
                            <label for="c_competencia">Competencia</label>
                            <select onchange="func_abre_validacao()" class="form-control class_inputs" id="c_competencia_validacao">
                                <!-- <option value="">SELECIONE UMA COMPETENCIA</option> -->
                                <option value="2021-01">2021/01</option>
                                <option value="2021-02">2021/02</option>
                                <option value="2021-03">2021/03</option>
                                <option value="2021-04">2021/04</option>
                                <option value="2021-05">2021/05</option>
                                <option value="2021-06">2021/06</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="padding: 10px; border-width: 1px; border-style: solid;">
                        <div class="col-sm-12">
                            <table id="tabela" class="table" style="width: 100%; color: black;">
                                <thead style="font-weight: bold;">
                                    <tr>
                                        <th>Colaboradores</th>
                                        <th>Cargo</th>
                                        <th>GH</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody id="corpo_tab" style="font-weight: normal;">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal" id="modal">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="c_situacao">Situação do balancete</label>
                            <select class="form-control class_inputs" id="c_situacao">
                                <option value="0">SELECIONE UMA SITUACAO</option>
                                <option value="concluido">Concluido</option>
                                <option value="atrasado">Atrasado</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 6px;">
                        <div class="col-sm-12 text-right">
                            <button onclick="func_upload_balancete()" type="button" class="btn btn-primary">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="../class/DataTables/datatables.min.js"></script>
<script language="JavaScript">
    function func_lancamento_liderança() {
        Swal.fire({
            title: 'Tem certeza que deseja calcular as porcentagens dos lideres?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'lib/lib_upload_premiacao.php',
                    type: 'POST',
                    data: {
                        "v_acao": "CALCULO_LIDERES",
                        "v_competencia": $("#c_competencia").val()
                    },
                    success: function(data) {
                        // console.log(data);
                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                    },
                    error: function(request, status, erro) {
                        Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }

                });
            }
        })
    }

    function func_relatorio_valores() {
        Swal.fire({
            title: 'Tem certeza que deseja gerar o relatorio?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'lib/lib_upload_premiacao.php',
                    type: 'POST',
                    data: {
                        "v_acao": "RELATORIO_FINANCEIRO"
                    },
                    success: function(data) {
                        window.location.href = '../tmp/Relatorio_Financeiro_Premiaçao.xlsx'
                    },
                    error: function(request, status, erro) {
                        Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }

                });
            }
        })
    }

    function func_abre_validacao() {
        $("#competencia").hide()

        $.ajax({
            url: 'lib/lib_upload_premiacao.php',
            type: 'POST',
            data: {
                "v_acao": "CARREGA_COLAB",
                "v_competencia": $("#c_competencia_validacao").val()
            },
            success: function(data) {

                $('#tabela').DataTable().destroy();

                let options = ''
                data.forEach(element => {
                    let button = ''
                    if (element.indicador) {
                        button = `<button disabled style="color: green;" class="btn is-icon btn-outline-primary">
                                        <span class="button-text">
                                            <i class="fa fa-pencil  fa-1x" aria-hidden="true">Incluso</i>
                                        </span>
                                    </button>`
                    } else {
                        button = `<button style="color: red;" class="btn is-icon btn-outline-primary" onclick="func_incluir(${element.id})">
                                        <span class="button-text">
                                            <i class="fa fa-pencil  fa-1x" aria-hidden="true">Incluir</i>
                                        </span>
                                    </button>`
                    }
                    options += `<tr>
                        <td>${element.nome}</td>
                        <td>${element.cargo}</td>
                        <td>${element.gh}</td>
                        <td>${button}</td>
                    </tr>`
                });
                $("#corpo_tab").html(options)

                $("#tabela").DataTable({
                    "language": {
                        "url": "../class/DataTables/portugues.json",
                    },
                    "columnDefs": [{
                        "width": "320px",
                        "targets": 0,
                    }],
                    "lengthMenu": [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "Todos"]
                    ],
                    "order": [
                        [0, "asc"]
                    ],
                    "scrollY": "50vh",
                    "scrollX": true,
                    "scrollCollapse": true,
                    "paging": true
                });

            },
            error: function(request, status, erro) {
                Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
            }

        });

    }

    function func_incluir(id) {
        Swal.fire({
            title: 'Tem certeza que deseja incluir porcentagem para esse colaborador?',
            text: "Você estará incluindo!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'lib/lib_upload_premiacao.php',
                    type: 'POST',
                    data: {
                        "v_acao": "INCLUIR_PORCENTAGEM_GCLICK",
                        "v_colaborador": id,
                        "v_competencia": $("#c_competencia_validacao").val()
                    },
                    success: function(data) {
                        // console.log(data);
                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        if (v_json.msg_ev == "success") {
                            func_abre_validacao()
                        }
                    },
                    error: function(request, status, erro) {
                        Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }

                });
            }
        })
    }

    function func_upload_usuarios() {

        // Captura os dados do formulário
        let formulario = document.getElementById('dados_usuario');

        // Instância o FormData passando como parâmetro o formulário
        let formData = new FormData(formulario);

        formData.append("v_acao", 'UPLOAD_DADOS')

        Swal.fire({
            title: 'Você tem certeza que deseja importar?',
            text: "Você estará iniciando a importação!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, importar!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Carregando...',
                    'Seu arquivo está sendo carregado.',
                    'warning'
                )

                $.ajax({
                    url: 'lib/lib_upload_premiacao.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        // console.log(data);
                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        if (v_json.msg_ev == "success") {
                            $("#btn_tarefas").prop("disabled", false);
                            $("#arquivo_tarefas").prop("disabled", false);
                        }
                    },
                    error: function(request, status, erro) {
                        Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }

                });
            }
        })
    }

    function func_upload_tarefas() {

        // Captura os dados do formulário
        let formulario = document.getElementById('form_upload');

        // Instância o FormData passando como parâmetro o formulário
        let formData = new FormData(formulario);

        formData.append("v_acao", 'UPLOAD_TAREFAS')
        formData.append("v_competencia", $("#c_competencia").val())

        Swal.fire({
            title: 'Você tem certeza que deseja importar?',
            text: "Você estará iniciando a importação!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, importar!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Carregando...',
                    'Seu arquivo está sendo carregado.',
                    'warning'
                )

                $.ajax({
                    url: 'lib/lib_upload_premiacao.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        // console.log(data);
                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        if (v_json.msg_ev == "success") {
                            // $("#btn_tarefas").prop("disabled", false);
                            // $("#arquivo_tarefas").prop("disabled", false);
                        }
                    },
                    error: function(request, status, erro) {
                        Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }
                });
            }
        })
    }

    function func_upload_mkt() {
        // Captura os dados do formulário
        let formulario = document.getElementById('dados_mkt');

        // Instância o FormData passando como parâmetro o formulário
        let formData = new FormData(formulario);

        formData.append("v_acao", 'UPLOAD_MKT')
        formData.append("v_competencia", $("#c_competencia").val())

        Swal.fire({
            title: 'Você tem certeza que deseja importar?',
            text: "Você estará iniciando a importação!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, importar!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Carregando...',
                    'Seu arquivo está sendo carregado.',
                    'warning'
                )

                $.ajax({
                    url: 'lib/lib_upload_premiacao.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        // console.log(data);
                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        if (v_json.msg_ev == "success") {
                            // $("#btn_tarefas").prop("disabled", false);
                            // $("#arquivo_tarefas").prop("disabled", false);
                        }
                    },
                    error: function(request, status, erro) {
                        Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }
                });
            }
        })
    }

    function func_upload_balancete() {

        Swal.fire({
            title: 'Você tem certeza que deseja enviar?',
            text: "Você estará iniciando a importação!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim!'
        }).then((result) => {
            if (result.value) {
                if ($("#c_situacao").val() == '0') {
                    Swal.fire(
                        "FALHA!",
                        "Favor, seleciona a situação!",
                        "error"
                    );
                } else {
                    $.ajax({
                        url: 'lib/lib_upload_premiacao.php',
                        type: 'POST',
                        data: {
                            "v_acao": 'UPLOAD_BALANCETE',
                            "v_competencia": $("#c_competencia").val(),
                            "v_situacao": $("#c_situacao").val()
                        },
                        success: function(data) {
                            // console.log(data);
                            var v_json = JSON.parse(data);
                            Swal.fire(
                                v_json.msg_titulo,
                                v_json.msg,
                                v_json.msg_ev
                            )
                            if (v_json.msg_ev == "success") {
                                // $("#btn_tarefas").prop("disabled", false);
                                // $("#arquivo_tarefas").prop("disabled", false);
                            }
                        },
                        error: function(request, status, erro) {
                            Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                        }
                    });
                }
            }
        })
    }

    function func_upload_juridico() {
        // Captura os dados do formulário
        let formulario = document.getElementById('dados_juridico');

        // Instância o FormData passando como parâmetro o formulário
        let formData = new FormData(formulario);

        formData.append("v_acao", 'UPLOAD_JURIDICO')
        formData.append("v_competencia", $("#c_competencia").val())

        Swal.fire({
            title: 'Você tem certeza que deseja importar?',
            text: "Você estará iniciando a importação!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, importar!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Carregando...',
                    'Seu arquivo está sendo carregado.',
                    'warning'
                )

                $.ajax({
                    url: 'lib/lib_upload_premiacao.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        // console.log(data);
                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        if (v_json.msg_ev == "success") {
                            // $("#btn_tarefas").prop("disabled", false);
                            // $("#arquivo_tarefas").prop("disabled", false);
                        }
                    },
                    error: function(request, status, erro) {
                        Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }
                });
            }
        })
    }

    function func_upload_ti() {
        // Captura os dados do formulário
        let formulario = document.getElementById('satisfacao_ti');

        // Instância o FormData passando como parâmetro o formulário
        let formData = new FormData(formulario);

        formData.append("v_acao", 'UPLOAD_TI')
        formData.append("v_competencia", $("#c_competencia").val())

        Swal.fire({
            title: 'Você tem certeza que deseja importar?',
            text: "Você estará iniciando a importação!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, importar!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Carregando...',
                    'Seu arquivo está sendo carregado.',
                    'warning'
                )

                $.ajax({
                    url: 'lib/lib_upload_premiacao.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        // console.log(data);
                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        if (v_json.msg_ev == "success") {
                            // $("#btn_tarefas").prop("disabled", false);
                            // $("#arquivo_tarefas").prop("disabled", false);
                        }
                    },
                    error: function(request, status, erro) {
                        Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }
                });
            }
        })
    }

    function func_upload_inovacao() {
        // Captura os dados do formulário
        let formulario = document.getElementById('satisfacao_inovacao');

        // Instância o FormData passando como parâmetro o formulário
        let formData = new FormData(formulario);

        formData.append("v_acao", 'UPLOAD_INOVACAO_SATISFACAO')
        formData.append("v_competencia", $("#c_competencia").val())

        Swal.fire({
            title: 'Você tem certeza que deseja importar?',
            text: "Você estará iniciando a importação!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, importar!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Carregando...',
                    'Seu arquivo está sendo carregado.',
                    'warning'
                )

                $.ajax({
                    url: 'lib/lib_upload_premiacao.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        // console.log(data);
                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        if (v_json.msg_ev == "success") {
                            // $("#btn_tarefas").prop("disabled", false);
                            // $("#arquivo_tarefas").prop("disabled", false);
                        }
                    },
                    error: function(request, status, erro) {
                        Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }
                });
            }
        })
    }

    function func_upload_vagas_rh() {
        // Captura os dados do formulário
        let formulario = document.getElementById('vagas_rh');

        // Instância o FormData passando como parâmetro o formulário
        let formData = new FormData(formulario);

        formData.append("v_acao", 'UPLOAD_VAGAS_RH')
        formData.append("v_competencia", $("#c_competencia").val())

        Swal.fire({
            title: 'Você tem certeza que deseja importar?',
            text: "Você estará iniciando a importação!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, importar!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Carregando...',
                    'Seu arquivo está sendo carregado.',
                    'warning'
                )

                $.ajax({
                    url: 'lib/lib_upload_premiacao.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        // console.log(data);
                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        if (v_json.msg_ev == "success") {
                            // $("#btn_tarefas").prop("disabled", false);
                            // $("#arquivo_tarefas").prop("disabled", false);
                        }
                    },
                    error: function(request, status, erro) {
                        Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }
                });
            }
        })
    }

    function func_upload_trello() {
        // Captura os dados do formulário
        let formulario = document.getElementById('trello_inovacao');

        // Instância o FormData passando como parâmetro o formulário
        let formData = new FormData(formulario);

        formData.append("v_acao", 'UPLOAD_TRELLO')
        formData.append("v_competencia", $("#c_competencia").val())

        Swal.fire({
            title: 'Você tem certeza que deseja importar?',
            text: "Você estará iniciando a importação!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, importar!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Carregando...',
                    'Seu arquivo está sendo carregado.',
                    'warning'
                )

                $.ajax({
                    url: 'lib/lib_upload_premiacao.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        // console.log(data);
                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        if (v_json.msg_ev == "success") {
                            // $("#btn_tarefas").prop("disabled", false);
                            // $("#arquivo_tarefas").prop("disabled", false);
                        }
                    },
                    error: function(request, status, erro) {
                        Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }
                });
            }
        })
    }
</script>