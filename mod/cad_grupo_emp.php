<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0008") == 0) {
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
    </style>

    <title>Document</title>
</head>

<body>
    <div class="container">
        <div id="tabela_resultados">
            <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
                <div class="row">
                    <div class="form-group col-sm-11">
                        <h3>Lista de Visibilidade</h3>
                    </div>
                    <!-- <div class="form-group col-sm-2">
                        <select id="c_tab_busca_campo" class="form-control class_inputs" onchange="func_busca_campo_select();">
                            <option value="Id|num" selected>Id</option>
                            <option value="Nome|txt">Módulo</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                        <input type="text" id="c_tab_busca_texto" class="form-control class_inputs" placeholder="PESQUISAR REGISTRO" onkeyup="if (event.keyCode === 13) {func_carrega_tab();}">
                    </div> -->
    
                </div>
            </div>
            <div id="box_tab1" class="row" style="height: auto; background-color: white; border: none; overflow-x: hidden;">
                <input id="c_acao" type="hidden" value="">
                <div class="box-body">
                    <input type="hidden" id="vf_tab_sql_limit_in" value="0">
                    <input type="hidden" id="vf_tab_btn_pag_select" value="1">
    
                    <table id="tab1" class="table" style="width: 100%; color: black;">
                        <thead style="font-weight: bold;">
                            <tr>
                                <th>Id</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tab1b" style="font-weight: normal;">
    
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="form-group col-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                        <button id="btn_novo_reg" class="btn btn-primary" style="border-radius: 10px; width: 100px;" onclick="func_novo_registro()">Novo</button>
                        <!-- <button disabled id="btn_excluir_reg" class="btn btn-danger" style="border-radius: 10px; width: 100px;" onclick="func_excluir_registro()">Excluir</button> -->
                    </div>
                </div>
            </div>
        </div>

        <div id="tabela_empresas" style="display: none;">
            <input id="c_id_grupo" type="hidden" value="">
            <div id="box_tab_titulo" class="box" style="margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
                <div class="row">
                    <div class="form-group col-sm-11">
                        <h3>Lista de Empresas</h3>
                    </div>
                    <div class="form-group col-sm-1">
                        <button id="btn_voltar" class="btn btn-danger" style="border-radius: auto; width: auto;" onclick="goBack();">X</button>
                    </div>
                </div>
            </div>
            <div class="row" style="color: black; background-color: white; border: none; overflow-x: hidden;">
                <div class="col-sm-12">
                    <select multiple="multiple" style="height: 300px !important;" size="10" id="duallistbox">

                    </select>
                </div>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="form-group col-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                        <button id="btn_novo_reg" class="btn btn-success" style="border-radius: 10px; width: 100px;" onclick="func_salva_empresas()">Salvar</button>
                        <!-- <button disabled id="btn_excluir_reg" class="btn btn-danger" style="border-radius: 10px; width: 100px;" onclick="func_excluir_registro()">Excluir</button> -->
                    </div>
                </div>
            </div>
        </div>

        <div id="form_cad" style="display: none;">
            <div id="box_form_titulo" class="box" style="height: 60px; margin-top: 40px; background-color: white; border: none; overflow: hidden;">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <h3>Formulário de Cadastro</h3>
                    </div>
                </div>
            </div>
            <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">
                <div class="box-body" style="height: auto;">
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="c_id">Id</label>
                            <input disabled id="c_id" type="text" class="form-control class_inputs">
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="c_nome">Nome</label>
                            <input disabled id="c_nome" type="text" class="form-control class_inputs">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="c_descricao">Descrição</label>
                            <input disabled id="c_descricao" type="text" class="form-control class_inputs" placeholder="DESCRIÇÃO">
                        </div>
                        <div class="form-group col-sm-2">
                            <label for="c_status">Status</label>
                            <select class="form-control class_inputs" name="c_status" id="c_status">
                                <option value="S">ATIVO</option>
                                <option value="N">INATIVO</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="form-group col-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                            <button disabled id="btn_salvar_reg" class="btn btn-success" style="border-radius: 10px; width: 100px;" onclick="func_salvar_registro()">Salvar</button>
                            <!-- <button disabled id="btn_excluir_reg" class="btn btn-danger" style="border-radius: 10px; width: 100px;" onclick="func_excluir_registro()">Excluir</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="../class/DataTables/datatables.min.js"></script>
<script src="../class/dual-listbox/src/jquery.bootstrap-duallistbox.js"></script>
<script language="JavaScript">
    $(document).ready(function() {
        func_carrega_tab();
        $("#c_tab_busca_texto").mask("0000000000");
    });

    function goBack() {
        $("#form_cad").hide()
        $("#tabela_resultados").show()
        $("#tabela_empresas").hide()

    }

    function func_tab_paginar(vj_pag) {
        var v_pag = vj_pag;
        var v_limit = $("#c_limit").val();
        $("#vf_tab_btn_pag_select").val(v_pag + 1);
        $("#vf_tab_sql_limit_in").val(v_limit * v_pag);
        func_carrega_tab();
    }

    function func_carrega_tab() {

        $("#c_acao").val("");
        var v_acao = "LISTAR_MODULOS";
        var v_tab_campo = $("#c_tab_campo").val();
        var v_tab_ordem = $("#c_tab_ordem").val();
        var v_tab_busca_campo = $("#c_tab_busca_campo").val();
        var v_tab_busca_texto = $("#c_tab_busca_texto").val();
        var v_tab_sql_limit_in = $("#vf_tab_sql_limit_in").val();
        var v_limit = $("#c_limit").val();

        $("#c_nome").prop("disabled", true);
        $("#c_descricao").prop("disabled", true);

        $("#c_id").val("");
        $("#c_nome").val("");
        $("#c_descricao").val("");

        $("#btn_novo_reg").prop("disabled", false);
        $("#btn_salvar_reg").prop("disabled", true);
        $("#btn_excluir_reg").prop("disabled", true);

        $.ajax({
            type: "POST",
            url: "../mod/lib/lib_cad_grupo_emp.php",
            data: {
                "v_acao": v_acao,
                "v_tab_campo": v_tab_campo,
                "v_tab_ordem": v_tab_ordem,
                "v_tab_busca_campo": v_tab_busca_campo,
                "v_tab_busca_texto": v_tab_busca_texto,
                "v_tab_sql_limit_in": v_tab_sql_limit_in,
                "v_limit": v_limit
            },
            success: function(data) {

                // console.log(data)
                $('#tab1').DataTable().destroy();
                var options = '';
                $("#tab1b").empty();

                data.grupos.forEach(element => {
                    options += `<tr  style="cursor: pointer;">
                        <td>${element.Id} </td>
                        <td>${element.Nome}</td>
                        <td>${element.Descricao}</td>
                        <td>${element.ativo == 'S' ? 'Ativo' : 'Inativo'}</td>
                        <td><div class="btn-group" style="border: 0px; margin: 0px;">
                            <button onclick="func_select('${element.Id}');"
                            class="btn is-icon btn-outline-primary" title="Modificar Grupo">
                                    <span class="button-text">
                                        <i class="fa fa-object-ungroup fa-1x"></i>
                                    </span>
                                </button>
                                <button onclick="func_abre_cad_empresas(${element.Id})"
                                id="btn_novo_download" class="btn is-icon btn-outline-primary" title="Gerenciar Empresas do Grupo">
                                    <span class="button-text">
                                        <i class="fa fa-building fa-1x"></i>
                                    </span>
                                </button>
                            </div>
                        </td>
                    </tr>`
                });

                $('#tab1b').html(options);

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
                        [0, "asc"]
                    ],
                    "scrollY": "50vh",
                    "scrollX": true,
                    "scrollCollapse": true,
                    "paging": true
                });

                $("#form_cad").hide()
                $("#tabela_resultados").show()
                $("#tabela_empresas").hide()
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

    function func_select(v_id) {

        $.ajax({
            type: "POST",
            url: "../mod/lib/lib_cad_grupo_emp.php",
            data: {
                "v_acao": "EV_SELECT",
                "v_id": v_id
            },
            success: function(data) {

                $("#c_acao").val("EV_SELECT");
                $("#c_nome").prop("disabled", false);
                $("#c_descricao").prop("disabled", false);

                $("#form_cad").show()
                $("#tabela_resultados").hide()

                $("#c_id").val(data[0].Id);
                $("#c_nome").val(data[0].Nome);
                $("#c_descricao").val(data[0].Descricao);

                $("#btn_novo_reg").prop("disabled", true);
                $("#btn_salvar_reg").prop("disabled", false);
                $("#btn_excluir_reg").prop("disabled", false);

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

    function func_abre_cad_empresas(v_id){
        $.ajax({
            type: "POST",
            url: "../mod/lib/lib_cad_grupo_emp.php",
            data: {
                "v_acao": "EV_EMPRESAS_LIBERADAS",
                "v_id": v_id
            },
            success: function(data) {
                $('#c_id_grupo').val(v_id)
                let vet_emp_gp = []

                data.grupos.forEach(element => {
                    vet_emp_gp.push(element.id_emp);
                });

                let options = ''
                $("#duallistbox").empty()
                data.empresas.forEach(element => {
                    options += `<option ${vet_emp_gp.indexOf(element.Id) > -1 ? 'selected' : ''}  value="${element.Id}">${element.nome}</option>`
                });
                $("#duallistbox").html(options)

                $('#duallistbox').bootstrapDualListbox();

                $('#duallistbox').bootstrapDualListbox('refresh', true);

                $("#form_cad").hide()
                $("#tabela_resultados").hide()
                $("#tabela_empresas").show()

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

    function func_salva_empresas(){

        $.ajax({
            type: "POST",
            url: "../mod/lib/lib_cad_grupo_emp.php",
            data: {
                "v_acao": "EV_SALVA_EMP_GRUPO",
                "v_empresas": $('#duallistbox').val(),
                "v_id_grupo": $('#c_id_grupo').val()
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

        $("#c_acao").val("EV_NOVO");
        $("#c_nome").prop("disabled", false);
        $("#c_descricao").prop("disabled", false);

        $("#form_cad").show()
        $("#tabela_resultados").hide()
        $("#tabela_empresas").hide()

        $("#c_id").val("");
        $("#c_nome").val("");
        $("#c_descricao").val("");

        $("#btn_novo_reg").prop("disabled", true);
        $("#btn_salvar_reg").prop("disabled", false);
        $("#btn_excluir_reg").prop("disabled", true);

    }

    function func_salvar_registro() {

        v_acao = $("#c_acao").val();
        if (v_acao != "EV_NOVO") {
            v_acao = "EV_SALVAR";
        }

        v_id = $("#c_id").val();
        v_nome = $("#c_nome").val();
        v_descricao = $("#c_descricao").val();


        if (v_descricao.length > 2 && v_nome.length > 5) {

            $.ajax({
                type: "POST",
                url: "../mod/lib/lib_cad_grupo_emp.php",
                data: {
                    "v_acao": v_acao,
                    "v_id": v_id,
                    "v_descricao": v_descricao,
                    "v_nome": v_nome,
                    "v_status_grupo": $("#c_status").val()
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
        }else{
            Swal.fire({
                icon: "error",
                title: "FALHA!",
                text: "Preencha todos os campos."
            })
        }
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

                if (v_id > 0) {

                    $.ajax({
                        type: "POST",
                        url: "../mod/lib/lib_cad_grupo_emp.php",
                        data: {
                            "v_acao": v_acao,
                            "v_id": v_id
                        },
                        success: function(data) {
                            var v_json = JSON.parse(data);
                            Swal.fire(
                                v_json.msg_titulo,
                                v_json.msg,
                                v_json.msg_ev
                            )

                            if (v_json.msg_ev == "success") {
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
</script>

</html>