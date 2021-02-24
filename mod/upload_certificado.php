<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0015") == 0) {
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
        <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">
            <div class="box-body" style="height: auto;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col text-center">
                            <div class="row col">
                                <div class="form-group col-sm-12 col">
                                    <h3>Importar Certificado</h3>
                                    <form id="form_upload" method="POST" action="lib/lib_cad_empresas_up_certs.php" enctype="multipart/form-data">
                                        <img src="img/image_painel/upload.png" alt="">
                                        <center><input type="file" name="arquivo"><br><br></center>
                                        <input id="importartxt" type="file" name="importartxt" style="visibility: hidden;">
                                        <button class="btn btn-primary btn-lg" type="button" onclick="func_upload()" value="Importar"> <i class="fa fa-cloud-upload" aria-hidden="true"></i> Importar Arquivo </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="box_titulo" class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">
            <div class="row ">
                <div class="form-group col-sm-12 col text-center ">
                    <h3>Historico de Importação</h3>
                </div>
            </div>
        </div>

        <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-3">
                    <select id="c_tab_busca_campo" class="form-control class_inputs" onchange="func_busca_campo_select();">
                        <option value="Competencia" selected>Competencia</option>
                        <option value="Usuario">Usuário</option>
                        <option value="Status">Status</option>
                    </select>
                </div>
                <div class="form-group col-sm-9">
                    <input type="text" id="c_tab_busca_texto" class="form-control class_inputs" placeholder="PESQUISAR REGISTRO" onkeyup="if (event.keyCode === 13) {func_carrega_tab();}">
                </div>

            </div>
        </div>


        <div id="box_tab1" class="box" style="height: auto; max-height: 300px; background-color: white; border: none; overflow-x: hidden;">
            <input id="c_acao" type="hidden" value="">
            <div class="box-body col-sm-12">

                <!-- Tabela para gerenciamento do historico de importação       -->


                <table class="table table-card">
                    <thead>
                        <tr>
                            <th>Data / Hora</th>
                            <th>Competência</th>
                            <th>Usuário</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!---->
                        <!---->
                        <tr class="ng-star-inserted">
                            <td>01/01/2020 12:00:00</td>
                            <td id="c_competencia" class="table-card-group-down">04/2020</td>
                            <td id="c_usuario" class="table-card-group-down">Rodrigo Amorim</td>
                            <td id="c_status" class="table-card-group-up">Finalizado</td>
                            <td>
                                <div class="btn-group" style="border: 0px; margin: 0px;">
                                    <button id="btn_novo_download" class="btn is-icon btn-outline-primary" title="Baixar o arquivo">
                                        <span class="button-text">
                                            <i class="fa fa-cloud-download fa-1x"></i>
                                        </span>
                                    </button>
                                    <button id="btn_excluir_comp" class="btn is-icon btn-outline-primary" title="Excluir importação" onclick="func_excluir_competencia()">
                                        <span class="button-text">
                                            <i class="fa fa-trash-o  fa-1x" aria-hidden="true"></i>
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>


<script language="JavaScript">
    $(document).ready(function() {

    });



    function func_upload() {
        Swal.fire({
            title: 'Você tem certeza que deseja importar?',
            text: "Você estará inciando a importação!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, importar!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Carregado!',
                    'Seu arquivo está sendo carregado.',
                    'success'
                )
                $("#form_upload").submit();
            }
        })
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
        var v_acao = "LISTAR_USUARIOS";
        var v_tab_campo = $("#c_tab_campo").val();
        var v_tab_ordem = $("#c_tab_ordem").val();
        var v_tab_busca_campo = $("#c_tab_busca_campo").val();
        var v_tab_busca_texto = $("#c_tab_busca_texto").val();
        var v_tab_sql_limit_in = $("#vf_tab_sql_limit_in").val();
        var v_limit = $("#c_limit").val();

        $("#c_nome").prop("disabled", true);
        $("#c_celular").prop("disabled", true);
        $("#c_sexo").prop("disabled", true);
        $("#c_dt_nasc").prop("disabled", true);
        $("#c_email").prop("disabled", true);

        $("#c_id").val("");
        $("#c_nome").val("");
        $("#c_celular").val("");
        $("#c_sexo").val("");
        $("#c_dt_nasc").val("");
        $("#c_email").val("");

        $("#btn_novo_reg").prop("disabled", false);
        $("#btn_salvar_reg").prop("disabled", true);
        $("#btn_excluir_reg").prop("disabled", true);
        $("#btn_bloq_conta").prop("disabled", true);
        $("#btn_enviar_email").prop("disabled", true);

        $.ajax({
            type: "POST",
            url: "lib/lib_cad_usuarios.php",
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
                var v_index = 0;
                var v_num_linhas = 0;
                $("#tab1b").empty();
                v_num_linhas = data[0].linhas;
                for (v_index = 1; v_index < data.length; v_index++) {
                    options += '<tr  style="cursor: pointer;" onclick="func_select(\'' + data[v_index].Id + '\');"><td>' + data[v_index].Id + '</td><td>' + data[v_index].Nome + '</td><td>' + data[v_index].Celular + '</td><td>' + data[v_index].Email + '</td><td>' + data[v_index].St_Cadastro + '</td></tr>';
                }
                $('#tab1b').html(options);


                $("#div_tab_paginacao").empty();
                var divAtual = document.getElementById("div_tab_paginacao");
                var v_num_pag = Math.round(v_num_linhas / v_limit);
                for (v_index = 0; v_index <= v_num_pag; v_index++) {
                    var novoBtn = document.createElement("button");
                    novoBtn.setAttribute('id', 'btn_pag' + (v_index + 1));
                    novoBtn.setAttribute('class', 'btn btn-default');
                    novoBtn.innerHTML = (v_index + 1);
                    novoBtn.setAttribute('onClick', 'func_tab_paginar(' + v_index + ');');
                    divAtual.appendChild(novoBtn);
                }

                var v_tab_btn_pag_select = $("#vf_tab_btn_pag_select").val();
                $("#btn_pag" + v_tab_btn_pag_select).css("background-color", "#C6E2FF");

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
            url: "lib/lib_cad_usuarios.php",
            data: {
                "v_acao": "EV_SELECT",
                "v_id": v_id
            },
            success: function(data) {

                $("#c_acao").val("EV_SELECT");
                $("#c_nome").prop("disabled", false);
                $("#c_celular").prop("disabled", false);
                $("#c_sexo").prop("disabled", false);
                $("#c_dt_nasc").prop("disabled", false);
                $("#c_email").prop("disabled", false);

                $("#c_id").val(data[0].Id);
                $("#c_nome").val(data[0].Nome);
                $("#c_celular").val(data[0].Celular);
                $("#c_sexo").val(data[0].Sexo);
                $("#c_dt_nasc").val(data[0].dt_nasc);
                $("#c_email").val(data[0].Email);

                $("#btn_novo_reg").prop("disabled", true);
                $("#btn_salvar_reg").prop("disabled", false);
                $("#btn_excluir_reg").prop("disabled", false);
                $("#btn_enviar_email").prop("disabled", false);
                if (data[0].St_Cadastro == 1) {
                    $("#btn_bloq_conta").prop("disabled", false);
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


    function func_excluir_competencia() {

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
                        url: "lib/lib_upload_holerite.php",
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
                        text: "Selecione uma competência."
                    })

                }
            }
        })
    }
</script>