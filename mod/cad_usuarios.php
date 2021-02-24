<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0011") == 0) {
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



        <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-6">
                    <h3>Lista de Usuários</h3>
                </div>
            </div>
        </div>
        <div id="box_tab1" class="row" style="padding: 10px; border-width: 1px; border-style: solid;">
            <input id="c_acao" type="hidden" value="">
            <div class="box-body">
                <input type="hidden" id="vf_tab_sql_limit_in" value="0">
                <input type="hidden" id="vf_tab_btn_pag_select" value="1">

                <table id="tab1" class="table" style="color: black; width: 100%;">
                    <thead style="font-weight: bold;">
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>E-Mail</th>
                            <th>Status</th>
                            <th>Empresa</th>
                        </tr>
                    </thead>
                    <tbody id="tab1b" style="font-weight: normal;">

                    </tbody>
                </table>
            </div>
        </div>


        <div id="box_form_titulo" class="box" style="height: 60px; margin-top: 40px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-11">
                    <h3>Formulário de Cadastro</h3>
                </div>
                <div class="form-group col-sm-1">
                    <button id="btn_voltar" class="btn btn-danger" style="border-radius: auto; width: auto;" onclick="goBack();">X</button>
                </div>
            </div>
        </div>
        <div id="box_cad" class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">
            <div class="box-body" style="height: auto;">
                <div class="row">
                    <div class="form-group col-sm-1">
                        <label for="c_id">Id</label>
                        <input disabled id="c_id" type="text" class="form-control class_inputs">
                    </div>
                    <div class="form-group col-sm-11">
                        <label for="c_nome">Nome Completo</label>
                        <input id="c_nome" type="text" class="form-control class_inputs" placeholder="NOME COMPLETO">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-4">
                    <label for="c_email">E-Mail</label>
                    <input id="c_email" type="email" class="form-control class_inputs" style="text-transform: lowercase;" placeholder="email@email.com.br">
                </div>
                <div class="form-group col-sm-4">
                    <label for="c_cpf">CPF</label>
                    <input id="c_cpf" type="text" class="form-control class_inputs" style="text-transform: lowercase;" placeholder="000.000.000-00">
                </div>
                <div class="form-group col-sm-4">
                    <label for="c_select_emp">Empresa</label>
                    <select id="c_select_emp" class="form-control class_inputs">

                    </select>
                </div>
            </div>
        </div>
        <div id="box-footer_1" class="box-footer">
            <div class="row">
                <div class="form-group col-sm-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                    <button id="btn_salvar_reg" class="btn btn-success" style="border-radius: 10px; width: 100px;" onclick="func_salvar_registro()">Salvar</button>
                    <button id="btn_excluir_reg" class="btn btn-warning" style="border-radius: 10px; width: 100px;" onclick="func_excluir_registro()">Excluir</button>
                    <button id="btn_bloq_conta" class="btn btn-danger" style="border-radius: 10px; width: 140px;" onclick="func_bloqueio_conta()">Bloquear Conta</button>
                    <button id="btn_enviar_email" class="btn btn-info" style="border-radius: 10px; width: 230px;" onclick="func_email_confirma()">Desbloqueio / Reset de Senha</button>
                </div>
            </div>
        </div>

        <div id="box-footer_2" class="box-footer">
            <div class="row">
                <div class="form-group col-sm-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                    <button id="btn_novo_reg" class="btn btn-primary" style="border-radius: 10px; width: 100px;" onclick="func_novo_registro()">Novo</button>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>


<script src="../class/DataTables/datatables.min.js"></script>
<script language="JavaScript">
    $(document).ready(function() {

        $("#c_select_emp").val("-");

        $("#box_cad").hide();
        $("#box-footer_1").hide();
        $("#box_form_footer").hide();
        $("#box_form_titulo").hide();

        $("#box_tab1").show();
        $("#box_tab_titulo").show();
        $("#box-footer_2").show();


        func_carrega_tab();
        func_lista_empresas();
        $("#c_tab_busca_texto").mask("0000000000");
        $("#c_cpf").mask("000.000.000-00");
    });



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

        // $("#c_nome").prop("disabled", true);
        // $("#c_email").prop("disabled", true);
        // $("#c_cpf").prop("disabled", true);
        // $("#c_select_emp").prop("disabled", true);


        $("#c_id").val("");
        $("#c_nome").val("");
        $("#c_email").val("");
        $("#c_cpf").val("");
        $("#c_select_emp").val("");

        // $("#btn_novo_reg").prop("disabled", false);
        // $("#btn_salvar_reg").prop("disabled", true);
        // $("#btn_excluir_reg").prop("disabled", true);
        // $("#btn_bloq_conta").prop("disabled", true);
        // $("#btn_enviar_email").prop("disabled", true);

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
                $('#tab1').DataTable().destroy();
                var options = '';
                var v_index = 0;
                var v_num_linhas = 0;
                $("#tab1b").empty();
                v_num_linhas = data[0].linhas;
                for (v_index = 1; v_index < data.length; v_index++) {
                    options += '<tr  style="cursor: pointer;" onclick="func_select(\'' + data[v_index].Id + '\');"><td>' + data[v_index].Id + '</td><td>' + data[v_index].Nome + '</td><td>' + data[v_index].Email + '</td><td>' + data[v_index].St_Cadastro + '</td><<td>' + data[v_index].nome_emp + '</td>';
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

                $("#tab1").DataTable({
                    "language": {
                        "url": "../class/DataTables/portugues.json",
                    },
                    "columnDefs": [{
                        "width": "15%",
                        "targets": 2,
                    }],
                    "lengthMenu": [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "Todos"]
                    ],
                    "order": [
                        [1, "asc"]
                    ],
                    "scrollY": "50vh",
                    "scrollX": true,
                    "scrollCollapse": true,
                    "paging": true
                });

                $("#box_cad").hide();
                $("#box-footer_1").hide();
                $("#box_form_footer").hide();
                $("#box_form_titulo").hide();
                $("#box-footer_1").hide();

                $("#box_tab1").show();
                $("#box_tab_titulo").show();
                $("#box-footer_2").show();

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


                $("#box_cad").show();
                $("#box-footer_1").show();
                $("#box_form_footer").show();
                $("#box_form_titulo").show();

                $("#box_tab1").hide();
                $("#box_tab_titulo").hide();
                $("#box-footer_2").hide();




                $("#c_acao").val("EV_SELECT");

                $("#c_id").val(data[0].Id);
                $("#c_nome").val(data[0].Nome);
                $("#c_email").val(data[0].Email);
                $("#c_cpf").val(data[0].Cpf);
                $("#c_select_emp").val(data[0].Cnpj_emp);

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



    function func_novo_registro() {


        $("#box_cad").show();
        $("#box-footer_1").show();
        $("#box_form_footer").show();
        $("#box_form_titulo").show();
        $("#box-footer_1").show();

        $("#box_tab1").hide();
        $("#box_tab_titulo").hide();
        $("#box-footer_2").hide();



        $("#c_id").val("");
        $("#c_nome").val("");
        $("#c_email").val("");
        $("#c_select_emp").val("");
        $("#c_cpf").val("");

    }




    function goBack() {

        $("#box_cad").hide();
        $("#box-footer_1").hide();
        $("#box_form_footer").hide();
        $("#box_form_titulo").hide();
        $("#box-footer_1").hide();

        $("#box_tab1").show();
        $("#box_tab_titulo").show();
        $("#box-footer_2").show();

        $("#c_id").val("");
        $("#c_nome").val("");
        $("#c_email").val("");
        $("#c_select_emp").val("");
        $("#c_cpf").val("");

        $("#btn_novo_reg").prop("disabled", false);

    }

    function func_salvar_registro() {

        v_acao = $("#c_acao").val();
        if (v_acao != "EV_NOVO") {
            v_acao = "EV_SALVAR";
        }

        v_id = $("#c_id").val();
        v_nome = $("#c_nome").val();
        v_email = $("#c_email").val();
        v_cnpj_emp = $("#c_select_emp").val();
        v_cpf = $("#c_cpf").val();

        if (v_nome.length > 5 && v_email.length > 5 && v_cnpj_emp.length > 5 && v_cpf.length > 5) {

            $.ajax({
                type: "POST",
                url: "lib/lib_cad_usuarios.php",
                data: {
                    "v_acao": v_acao,
                    "v_id": v_id,
                    "v_nome": v_nome,
                    "v_email": v_email,
                    "v_cnpj_emp": v_cnpj_emp,
                    "v_cpf": v_cpf
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
        } else {

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
                        url: "lib/lib_cad_usuarios.php",
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


    function func_bloqueio_conta() {


        v_acao = "EV_BLOQ";
        v_id = $("#c_id").val();

        $.ajax({
            type: "POST",
            url: "lib/lib_cad_usuarios.php",
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
                func_carrega_tab();
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

        // $("#btn_bloq_conta").prop("disabled", true);
        // $("#btn_enviar_email").prop("disabled", true);
        v_acao = "EV_EMAIL";
        v_id = $("#c_id").val();

        $.ajax({
            type: "POST",
            url: "lib/lib_cad_usuarios.php",
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



    function func_lista_empresas() {

        $.ajax({
            type: "POST",
            url: "lib/lib_cad_usuarios.php",
            data: {
                "v_acao": "EV_LISTA_EMPRESAS"
            },
            success: function(data) {

                let options = '<option value="0" selected>Selecione uma empresa.</option>';
                $("#c_select_emp").empty()
                data.forEach(element => {
                    options += `<option value="${element.cnpj}">${element.nome}</option>`
                });
                $("#c_select_emp").html(options);
                $("#c_select_emp").val("0");

            }
        });
    }
</script>



</html>