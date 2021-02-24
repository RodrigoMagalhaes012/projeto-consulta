<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0032") == 0) {
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
                    <div class="form-group col-sm-12">
                        <h3>Lista de Grupos de Tela</h3>
                    </div>
    
                </div>
            </div>
            <div class="row" style="height: auto; background-color: white; border: none; overflow-x: hidden;">
                <input id="c_acao" type="hidden" value="">
                <div class="box-body">
    
                    <table id="tab1" class="table" style="width: 100%; color: black;">
                        <thead style="font-weight: bold;">
                            <tr>
                                <th>Id</th>
                                <th>Grupo</th>
                                <th>Descrição</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tab1b" style="font-weight: normal;">
    
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                    <button id="btn_novo_reg" class="btn btn-primary" style="border-radius: 10px; width: 100px;" onclick="func_novo_registro()">Novo</button>
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
                            <label for="c_nome">Grupo</label>
                            <input disabled id="c_nome" type="text" class="form-control class_inputs"  placeholder="NOME DO GRUPO">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="c_descricao">Descrição</label>
                            <input disabled id="c_descricao" type="text" class="form-control class_inputs" placeholder="DESCRIÇÃO DO GRUPO">
                        </div>
                        <div class="form-group col-sm-2">
                            <label for="c_status_grupo">Status</label>
                            <select id="c_status_grupo" class="form-control class_inputs" onchange="func_busca_campo_select();">
                                <option value="S" selected>ATIVO</option>
                                <option value="N">INATIVO</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="form-group col-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                            <button disabled id="btn_salvar_reg" class="btn btn-success" style="border-radius: 10px; width: 100px;" onclick="func_salvar_registro()">Salvar</button>
                            <button disabled id="btn_excluir_reg" class="btn btn-danger" style="border-radius: 10px; width: 100px;" onclick="func_excluir_registro()">Excluir</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="cad_telas" style="display: none;">
            <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <h3>Liberação de Telas para Grupo</h3>
                    </div>
                    <div class="form-group col-sm-6">
                        <select onchange="func_carrega_lib_telas(localStorage.getItem('id_grupo'), this.value)" id="c_select_modulo" class="form-control class_inputs">

                        </select>
                    </div>
                </div>
            </div>
            <div class="row" style="height: auto; background-color: white; border: none; overflow-x: hidden;">
                <input id="c_acao" type="hidden" value="">
                <div class="box-body">
                    <input type="hidden" id="vf_tab_sql_limit_in" value="0">
                    <input type="hidden" id="vf_tab_btn_pag_select" value="1">
    
                    <table id="tab_liberacao" class="table" style="width: 100%; color: black;">
                        <thead style="font-weight: bold;">
                            <tr>
                                <th>Id</th>
                                <th>Ativa</th>
                                <th>Tela</th>
                                <th>Criação</th>
                                <th>Leitura</th>
                                <th>Gravação</th>
                                <th>Exclusão</th>
                            </tr>
                        </thead>
                        <tbody id="corpo_tab_liberacao" style="font-weight: normal;">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="form-group col-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                        <button id="btn_salvar_reg" class="btn btn-success" style="border-radius: 10px; width: 100px;" onclick="func_salva_permissoes()">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="../class/DataTables/datatables.min.js"></script>
<script language="JavaScript">
    $(document).ready(function() {
        func_carrega_tab();
    });

    // function func_tab_paginar(vj_pag) {
    //     var v_pag = vj_pag;
    //     var v_limit = $("#c_limit").val();
    //     $("#vf_tab_btn_pag_select").val(v_pag + 1);
    //     $("#vf_tab_sql_limit_in").val(v_limit * v_pag);
    //     func_carrega_tab();
    // }

    function func_carrega_tab() {

        $("#c_acao").val("");
        var v_acao = "LISTAR_GRUPOS";
        var v_tab_campo = $("#c_tab_campo").val();
        var v_tab_ordem = $("#c_tab_ordem").val();
        var v_tab_busca_campo = $("#c_tab_busca_campo").val();
        var v_tab_busca_texto = $("#c_tab_busca_texto").val();
        var v_tab_sql_limit_in = $("#vf_tab_sql_limit_in").val();
        var v_limit = $("#c_limit").val();

        $("#c_nome").prop("disabled", true);
        $("#c_descricao").prop("disabled", true);
        $("#c_status_grupo").prop("disabled", true);

        $("#c_id").val("");
        $("#c_nome").val("");
        $("#c_descricao").val("");
        $("#c_status_grupo").val("");

        $("#btn_novo_reg").prop("disabled", false);
        $("#btn_salvar_reg").prop("disabled", true);
        $("#btn_excluir_reg").prop("disabled", true);

        $.ajax({
            type: "POST",
            url: "../mod/lib/lib_cad_grupo_telas.php",
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
                var options = '';
                $("#form_cad").hide()
                $('#tab1').DataTable().destroy();
                $("#tab1b").empty();
                // v_num_linhas = data.grupos[0].linhas;
                for (v_index = 0; v_index < data.grupos.length; v_index++) {
                    options += '<tr  style="cursor: pointer;"><td>' +
                    data.grupos[v_index].Id + '</td><td>' + data.grupos[v_index].Nome +
                    '</td><td>' + data.grupos[v_index].Descricao +
                    '</td><td>' +`<div class="btn-group" style="border: 0px; margin: 0px;">
                                <button onclick="func_select('${data.grupos[v_index].Id}');"
                                class="btn is-icon btn-outline-primary" title="Modificar Grupo">
                                        <span class="button-text">
                                            <i class="fa fa-object-ungroup fa-1x"></i>
                                        </span>
                                    </button>
                                    <button onclick="func_abre_lib_telas(${data.grupos[v_index].Id})"
                                    id="btn_novo_download" class="btn is-icon btn-outline-primary" title="Liberar Telas do Grupo">
                                        <span class="button-text">
                                            <i class="fa fa-object-group fa-1x"></i>
                                        </span>
                                    </button>
                                </div>` +
                    '</td></tr>';
                }
                $('#tab1b').html(options);

                $("#tab1").DataTable({
                    "language": {
                        "url": "../class/DataTables/portugues.json",
                    },
                    "columnDefs": [{
                        "width": "35%",
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

                options = '<option value="0">SELECIONE UM MÓDULO</option>'
                $("#c_select_modulo").empty()
                data.modulos.forEach(element => {
                    options += `<option value="${element.Id}">${element.Nome}</option>`
                });
                $("#c_select_modulo").html(options)

                $("#tabela_resultados").show()
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
            url: "../mod/lib/lib_cad_grupo_telas.php",
            data: {
                "v_acao": "EV_SELECT",
                "v_id": v_id
            },
            success: function(data) {

                $("#tabela_resultados").hide()
                $("#form_cad").show()

                $("#c_acao").val("EV_SELECT");
                $("#c_nome").prop("disabled", false);
                $("#c_descricao").prop("disabled", false);
                $("#c_status_grupo").prop("disabled", false);

                $("#c_id").val(data[0].Id);
                $("#c_nome").val(data[0].Nome);
                $("#c_descricao").val(data[0].Descricao);
                $("#c_status_grupo").val(data[0].Ativo);

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

    function func_abre_lib_telas(v_id){
        localStorage.setItem('id_grupo',v_id)
        $("#tabela_resultados").hide()
        $("#cad_telas").show()
        $("#c_select_modulo").prop('selectedIndex', 1)
        func_carrega_lib_telas(v_id, $("#c_select_modulo").val())
    }

    function func_carrega_lib_telas(v_id_grupo, v_id_modulo){
        $.ajax({
            type: "POST",
            url: "../mod/lib/lib_cad_grupo_telas.php",
            data: {
                "v_acao": "EV_CARREGA_TELAS",
                "v_id_grupo": v_id_grupo,
                "v_id_modulo": v_id_modulo
            },
            success: function(data) {

                $('#tab_liberacao').DataTable().destroy();

                let vet_telas = []
                let vet_acessos = []

                data.telas.forEach(telas => {
                    vet_telas.push(telas.id_tela)    
                });

                data.acessos.forEach(acessos => {
                    vet_acessos.push(acessos.id_tela)
                })               

                $("#corpo_tab_liberacao").empty()
                options = ''
                data.telas.forEach(telas => {
                    if(vet_acessos.indexOf(telas.id_tela) > -1){
                        options += `<tr>
                                    <td>${telas.id_tela}</td>
                                    <td><input onchange="func_check_tela(this.id)" type="checkbox" id="A-${telas.id_tela}" checked></td>
                                    <td>${telas.nome}</td>
                                    <td><input type="checkbox" id="C-${telas.id_tela}" ${data.acessos[vet_acessos.indexOf(telas.id_tela)].perm_criar == 'S' ? 'checked' : ''}></td>
                                    <td><input type="checkbox" id="L-${telas.id_tela}" ${data.acessos[vet_acessos.indexOf(telas.id_tela)].perm_ler == 'S' ? 'checked' : ''}></td>
                                    <td><input type="checkbox" id="G-${telas.id_tela}" ${data.acessos[vet_acessos.indexOf(telas.id_tela)].perm_gravar == 'S' ? 'checked' : ''}></td>
                                    <td><input type="checkbox" id="E-${telas.id_tela}" ${data.acessos[vet_acessos.indexOf(telas.id_tela)].perm_excluir == 'S' ? 'checked' : ''}></td>
                                </tr>`
                    }else{
                        options += `<tr>
                                    <td>${telas.id_tela}</td>
                                    <td><input onchange="func_check_tela(this.id)" type="checkbox" id="A-${telas.id_tela}"></td>
                                    <td>${telas.nome}</td>
                                    <td><input type="checkbox" id="C-${telas.id_tela}"></td>
                                    <td><input type="checkbox" id="L-${telas.id_tela}"></td>
                                    <td><input type="checkbox" id="G-${telas.id_tela}"></td>
                                    <td><input type="checkbox" id="E-${telas.id_tela}"></td>
                                </tr>`
                    }
                });
                $("#corpo_tab_liberacao").html(options)

                $("#tab_liberacao").DataTable({
                        "language": {
                        "url": "../class/DataTables/portugues.json",
                    },
                    "columnDefs": [{
                        "width": "70%",
                        "targets": 2,
                    }],
                    "lengthMenu": [
                        [-1],
                        ["Todos"]
                    ],
                    "order": [
                        [2, "asc"]
                    ],
                    "scrollY": "50vh",
                    "scrollX": true,
                    "scrollCollapse": true,
                    "paging": true
                });

            },
            error: function(request, status, erro) {
                Swal.fire({
                    icon: "error",
                    title: "FALHA!",
                    text: "Problema ocorrido: " + status + "\nDescrição: " + erro + "\nInformações da requisição: " + request.responseText
                })
            }
        });
    }

    function func_check_tela(v_id){

        let v_num_id = v_id.split('-')[1]

        if($(`#${v_id}`).prop('checked')){
            $(`#C-${v_num_id}`).prop('checked', true)
            $(`#L-${v_num_id}`).prop('checked', true)
            $(`#G-${v_num_id}`).prop('checked', true)
            $(`#E-${v_num_id}`).prop('checked', true)
        }else{
            $(`#C-${v_num_id}`).prop('checked', false)
            $(`#L-${v_num_id}`).prop('checked', false)
            $(`#G-${v_num_id}`).prop('checked', false)
            $(`#E-${v_num_id}`).prop('checked', false)
        }
    }

    function func_salva_permissoes(){
        // console.log($("#tab_liberacao > tbody >tr"))
        let vet_dados = []
        $('#tab_liberacao> tbody  > tr').each(function() {
        // aqui tem a linha (tr)
            let linha = $(this);
            let children = linha.context.children

            let dados = {
                id_tela: children[0].innerHTML,
                ativo: $(`#A-${children[0].innerHTML}`).prop('checked') ? 'S' : 'N',
                criacao: $(`#C-${children[0].innerHTML}`).prop('checked') ? 'S' : 'N',
                leitura: $(`#L-${children[0].innerHTML}`).prop('checked') ? 'S' : 'N',
                gravacao: $(`#G-${children[0].innerHTML}`).prop('checked') ? 'S' : 'N',
                exclusao: $(`#E-${children[0].innerHTML}`).prop('checked') ? 'S' : 'N'
            }
            vet_dados.push(dados)
        });

        $.ajax({
            type: "POST",
            url: "../mod/lib/lib_cad_grupo_telas.php",
            data: {
                "v_acao": "EV_SALVA_ACESSOS",
                "v_dados_acesso": vet_dados,
                "v_id_grupo": localStorage.getItem('id_grupo')
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
                    text: "Problema ocorrido: " + status + "\nDescrição: " + erro + "\nInformações da requisição: " + request.responseText
                })
            }
        });
    }

    function func_novo_registro(){
        $("#tabela_resultados").hide()
        $("#form_cad").show()

        $("#c_acao").val("EV_NOVO");
        $("#c_nome").prop("disabled", false);
        $("#c_descricao").prop("disabled", false);
        $("#c_status_grupo").prop("disabled", false);

        $("#c_status_grupo").prop("selectedIndex", 0);

        $("#btn_salvar_reg").prop("disabled", false);
        $("#btn_excluir_reg").prop("disabled", true);
    }

    function func_salvar_registro(){
        let v_acao = $("#c_acao").val()
        if(v_acao != "EV_NOVO"){
            v_acao = "EV_SALVAR"
        }

        $.ajax({
            type: "POST",
            url: "../mod/lib/lib_cad_grupo_telas.php",
            data: {
                "v_acao": v_acao,
                "v_nome": $("#c_nome").val(),
                "v_descricao": $("#c_descricao").val(),
                "v_status_grupo": $("#c_status_grupo").val(),
                "v_id": $("#c_id").val()
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
                    text: "Problema ocorrido: " + status + "\nDescrição: " + erro + "\nInformações da requisição: " + request.responseText
                })
            }
        });

    }

    function func_excluir_registro(){
        $.ajax({
            type: "POST",
            url: "../mod/lib/lib_cad_grupo_telas.php",
            data: {
                "v_acao": "EV_EXCLUIR",
                "v_id": $("#c_id").val()
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
                    text: "Problema ocorrido: " + status + "\nDescrição: " + erro + "\nInformações da requisição: " + request.responseText
                })
            }
        });
    }

</script>
</html>