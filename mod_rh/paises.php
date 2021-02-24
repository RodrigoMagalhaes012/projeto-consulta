<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0027") == 0) {
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

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <style>
        input,
        textarea {
            text-transform: uppercase;
        }
    </style>

    <title>paises</title>
</head>

<body>

    <div class="container">
        <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-6">
                    <h3>Lista de Paises</h3>
                </div>
                <div class="form-group col-sm-2">
                    <select id="c_tab_busca_campo" class="form-control class_inputs" onchange="func_busca_campo_select();">
                        <option value="id|num" selected>Código</option>
                        <option value="pais|txt">Pais</option>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <input type="text" id="c_tab_busca_texto" class="form-control class_inputs" placeholder="PESQUISAR REGISTRO" onkeyup="if (event.keyCode === 13) {func_carrega_tab();}">
                </div>
            </div>
        </div>
        <div id="box_tab1" class="box" style="height: auto; max-height: 300px; background-color: white; border: none; overflow-x: hidden;">
            <input id="c_acao" type="hidden" value="">
            <div class="box-body">
                <input type="hidden" id="vf_tab_sql_limit_in" value="0">
                <input type="hidden" id="vf_tab_btn_pag_select" value="1">
                <table id="tab1" class="table">
                    <thead style="font-weight: bold;">
                        <tr>
                            <th>Código</th>
                            <th>Pais</th>
                        </tr>
                    </thead>
                    <tbody id="tab1b" style="font-weight: normal;">
                    </tbody>
                </table>
            </div>
        </div>
        <div id="box_tab_footer" class="box" style="height: 60px; margin-top: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-2">
                    <select id="c_tab_campo" class="form-control class_inputs" onchange="func_carrega_tab();">
                        <option value="ibge" selected>Código</option>
                        <option value="pais">País</option>
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
        </div>

        <div id="box_form_titulo" class="box" style="height: 60px; margin-top: 40px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-11 text-center">
                    <h2>Cadastro de Paises</h2>
                </div>
                <div class="form-group col-sm-1">
                    <button id="btn_voltar" class="btn btn-danger" style="border-radius: auto; width: auto;" onclick="goBack();">X</button>
                </div>
            </div>
        </div>
        <div id="box_form" class="box" style="padding:30px; margin-top: 10px; height: auto; background-color: white; border: none;">
            <div class="box-body" style="height: auto;">
                <div class="row">
                    <div class="form-group col-sm-1">
                        <label for="c_codigo_ibge">Código</label>
                        <input disabled id="c_codigo_ibge" type="text" class="form-control class_inputs">
                    </div>
                    <div class="form-group col-sm-11">
                        <label for="c_nome_pais">País</label>
                        <input id="c_nome_pais" type="text" class="form-control class_inputs" placeholder="pais">
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="c_descricao_cargo">Descrição</label>
                        <textarea class="form-control" id="c_descricao_cargo" rows="3"></textarea>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <div id="box_form_footer" class="box-footer">
        <div class="row">
            <div class="form-group col-sm-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                <button disabled id="btn_salvar_reg" class="btn btn-success" style="border-radius: 10px; width: 100px;" onclick="func_salvar_registro()">Salvar</button>
                <button id="btn_excluir_reg" class="btn btn-warning" style="border-radius: 10px; width: 100px;" onclick="func_excluir_registro()">Excluir</button>
            </div>
        </div>
    </div>
    <div id="box_form_footer1" class="box-footer">
        <div class="row">
            <div class="form-group col-sm-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                <button id="btn_novo_reg" class="btn btn-primary" style="border-radius: 10px; width: 100px;" onclick="func_novo_registro()">Novo</button>
            </div>
        </div>
    </div>
    </div>
</body>



<script language="JavaScript">
    $(document).ready(function() {

        $("#box_form_titulo").hide();
        $("#box_form").hide();
        $("#box_form_footer").hide();

        $("#box_tab_titulo").show();
        $("#box_tab1").show();
        $("#box_tab_footer").show();


        func_carrega_tab();


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
        var v_acao = "LISTAR_PAISES";
        var v_tab_campo = $("#c_tab_campo").val();
        var v_tab_ordem = $("#c_tab_ordem").val();
        var v_tab_busca_campo = $("#c_tab_busca_campo").val();
        var v_tab_busca_texto = $("#c_tab_busca_texto").val();
        var v_tab_sql_limit_in = $("#vf_tab_sql_limit_in").val();
        var v_limit = $("#c_limit").val();

        $("#c_nome_pais").prop("disabled", false);
        //$("#c_descricao_cargo").prop("disabled", false);
        $("#c_codigo_ibge").prop("disabled", false);

        $("#c_codigo_ibge").val("");
        $("#c_nome_pais").val("");
        //$("#c_descricao_cargo").val("");


        $("#btn_novo_reg").prop("disabled", false);
        $("#btn_salvar_reg").prop("disabled", true);
        $("#btn_excluir_reg").prop("disabled", true);

        $.ajax({
            type: "POST",
            url: "lib/lib_cad_paises.php",
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
                    options += '<tr  style="cursor: pointer;" onclick="func_select(\'' + data[v_index].ibge + '\');"><td>' + data[v_index].ibge + '</td><td>' + data[v_index].pais + '</td><td>';
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

    function goBack() {
        $("#box_form_titulo").hide();
        $("#box_form").hide();
        $("#box_form_footer").hide();
        $("#box_tab_footer").show();
        $("#box_tab_titulo").show();
        $("#box_tab1").show();
        $("#box_form_footer1").show();
        $("#c_nome_pais").val(" ");

    }


    function func_select(v_codigo_ibge) {
        var v_codigo_ibge = v_codigo_ibge;

      
        $.ajax({
            type: "POST",
            url: "lib/lib_cad_paises.php",
            data: {
                "v_acao": "EV_SELECT",
                "v_codigo_ibge": v_codigo_ibge
            },
            success: function(data) {
                

                $("#box_form_titulo").show();
                $("#box_form").show();
                $("#box_form_footer").show();
                $("#box_form_footer1").hide();
                $("#box_tab_footer").hide();
                $("#box_tab_titulo").hide();
                $("#box_tab1").hide();
                
                $("#c_acao").val("EV_SELECT");
                $("#c_nome_pais").prop("disabled", false);
                $("#c_codigo_ibge").prop("disabled", false);
             
                $("#c_codigo_ibge").val(data[0].ibge);              
                $("#c_nome_pais").val(data[0].pais);
                // $("#c_descricao_cargo").val(data[0].Descricao);



                $("#btn_novo_reg").prop("disabled", false);
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


        $("#box_form_titulo").show();
        $("#box_form").show();
        $("#box_form_footer").show();
        $("#box_tab_titulo").hide();
        $("#box_tab1").hide();
        $("#box_tab_footer").hide();
        $("#box_form_footer1").hide();


        $("#c_acao").val("EV_NOVO");

        $("#c_codigo_ibge").prop("disabled", false);
        $("#c_nome_pais").prop("disabled", false);
        // $("#c_descricao_cargo").prop("disabled", false);

        $("#c_codigo_ibge").val("");
        $("#c_nome_pais").val("");
        //$("#c_descricao_cargo").val("");


        $("#btn_novo_reg").prop("disabled", false);
        $("#btn_salvar_reg").prop("disabled", false);
        $("#btn_excluir_reg").prop("disabled", true);

    }



    function func_salvar_registro() {

        var v_acao = $("#c_acao").val();

        if (v_acao != "EV_NOVO") {
            v_acao = "EV_SALVAR";

        }

        var v_codigo_ibge = $("#c_codigo_ibge").val();
        var v_nome_pais = $("#c_nome_pais").val();
        // var v_codigo_ibge = $(" ").val();
        //var v_descricao = $("#c_descricao_cargo").val();


        if (v_codigo_ibge >= 1) {

            $.ajax({
                type: "POST",
                url: "lib/lib_cad_paises.php",
                data: {
                    "v_acao": v_acao,
                    "v_codigo_ibge": v_codigo_ibge,
                    "v_nome_pais": v_nome_pais
                    //"v_descricao": v_descricao

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
                v_codigo_ibge = $("#c_codigo_ibge").val();

                if (v_codigo_ibge > 0) {

                    $.ajax({
                        type: "POST",
                        url: "lib/lib_cad_paises.php",
                        data: {
                            "v_acao": v_acao,
                            "v_codigo_ibge": v_codigo_ibge
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