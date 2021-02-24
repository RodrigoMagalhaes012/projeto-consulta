<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="css/animate.css" rel="stylesheet">
    <link rel="stylesheet" href="../class/alert/css/class_alert.css" id="theme-styles">
    <link rel="stylesheet" href="css/dunfe.css" id="theme-styles">
    <link rel="stylesheet" href="css/codbarras.css">
    <script src="../class/alert/js/class_alert.js"></script>

    <title>AgroContar APP</title>


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

</head>

<body>


    <div class="container">

        <div class="load"> <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
            <span class="sr-only">Loading...</span>
        </div>

        <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-12">
                    <h3>Gestão de Cálculo</h3>
                </div>
                <!-- <div class="form-group col-sm-6">
                    <select id="c_select_calculo" class="form-control class_inputs">
                        <option value="-">Selecione um calculo</option>
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <i id="btnCriaCalculo" style="cursor: pointer; color:blue" class="fa fa-plus-circle fa-2x" aria-hidden="true" aria-hidden="true" data-toggle="modal" data-target="#cad_calculo_modal"></i>
                </div> -->
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
                            <th style="text-align: center;">Ações</th>
                            <th style="text-align: center;">Cálculo</th>
                            <th style="text-align: center;">Competencia</th>
                            <th style="text-align: center;">Tipo Folha</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: center;">Integrar Lançamentos</th>
                            <th style="text-align: center;">Desfazer Integração</th>


                            <!-- <th style="text-align: center;">Com lançamentos anteriores</th> -->
                        </tr>
                    </thead>
                    <tbody id="tab1b" style="font-weight: normal;" align="center">
                    </tbody>
                </table>
            </div>
        </div>
        <button id="btnCriaCalculo" aria-hidden="true" aria-hidden="true" data-toggle="modal" data-target="#cad_calculo_modal" style="border-radius: 20px; margin-left: 960px; margin-top:30px;" id="btn_novo_reg" class="btn btn-primary" style="border-radius: 10px; width: 100px;">Novo Cálculo</button>



        <!-- GERENCIAMENTO DE CALCULO -->
        <div class="modal fade" id="carrega_calculo_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="modalLabel">Criar Calculo</h4>
                    </div>
                    <div class="modal-body">


                        <div class="row w-100" style="padding: 30px;">
                            <div class="row w-100" style="margin: 0px 0px 10px 5px; font-weight: bold; font-size: 16px; color: green">
                                Criar Calculo
                            </div>

                            <div class="row">
                                <input type="hidden" id="c_cal_id">
                                <div class="col-sm-12">
                                    <div class="form-group col-sm-4">
                                        <label style="margin-left: 15px;" for="c_cal_competencia">Competencia</label>
                                        <input disabled id="c_cal_competencia" class="form-control class_inputs">

                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label style="margin-left: 15px;" for="c_cal_tipo_folha">Tipo Folha</label>
                                        <select disabled id="c_cal_tipo_folha" class="form-control class_inputs">
                                            <option value="-" selected>Tipo Folha</option>
                                            <option value="11">11 - Cálculo Mensal </option>
                                            <option value="12">12 - Folha Complementar</option>
                                            <option value="14">14 - Pamento de Dissídio</option>
                                            <option value="31">31 - Adiantamento 13° Salário</option>
                                            <option value="32">32 - 13° Sálario Integral</option>
                                            <option value="91">91 - Adiantamento de Salarial</option>
                                            <option value="93">93 - Especiais</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label style="margin-left: 15px;" for="c_cal_status">Status</label>
                                        <select id="c_cal_status" class="form-control class_inputs">
                                            <!-- <option value="-" selected>Status</option> -->
                                            <option value="0">Aberto</option>
                                            <option value="1">Finalizado</option>
                                            <option value="2">Em Andamento</option>
                                            <option value="3">Integrado</option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 10px; padding: 0px;">
                                <div class="col-sm-12" style="padding: 5px; padding-right: 0px; margin-top: 5px; margin-bottom: 10px; text-align: right;">
                                    <!-- <button class="btn-lg btn-success" style="border-radius: 20px;" onclick="func_atualiza();">Salvar</button> -->
                                    <button class="btn btn-success" style="border-radius: 10px; width: 100px; margin-top:30px; height: 40px" onclick="func_atualiza();">Salvar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- The modal -->
        <div class="modal fade" id="cad_calculo_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="modalLabel">Criar Calculo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row w-100" style="padding: 30px;">
                            <div class="row w-100" style="margin: 0px 0px 10px 5px; font-weight: bold; font-size: 16px; color: green">
                                Criar Calculo
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group col-sm-4">
                                        <label style="margin-left: 15px;" for="c_cal_competencia">Competencia</label>
                                        <input id="c_competencia" class="form-control class_inputs">
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label style="margin-left: 15px;" for="c_cal_tipo_folha">Tipo Folha</label>
                                        <select id="c_tipo_folha" class="form-control class_inputs">
                                            <option value="11" selected>11 - Cálculo Mensal </option>
                                            <option value="12">12 - Folha Complementar</option>
                                            <option value="14">14 - Pamento de Dissídio</option>
                                            <option value="31">31 - Adiantamento 13° Salário</option>
                                            <option value="32">32 - 13° Sálario Integral</option>
                                            <option value="91">91 - Adiantamento de Salarial</option>
                                            <option value="93">93 - Especiais</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label style="margin-left: 15px;" for="c_cal_status">Status</label>
                                        <select id="c_status" class="form-control class_inputs">
                                            <option value="0" selected>Aberto </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px; padding: 0px;">
                                <div class="col-sm-12" style="padding: 5px; padding-right: 0px; margin-top: 5px; margin-bottom: 10px; text-align: right;">
                                    <!-- <button class="btn-lg btn-success" style="border-radius: 20px;" onclick="func_salvar();">Salvar</button> -->
                                    <button class="btn btn-success" style="border-radius: 10px; width: 100px; margin-top:30px; height: 40px" onclick="func_salvar();">Salvar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


<script type="text/javascript" src="../class/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="js/jquery-barcode.js"></script>
<script language="JavaScript">
    $(document).ready(function() {

        func_carrega_tab()
        // $('#competencia_tab').val(dataFormatada);
        $(".btnExcluir").bind("click", Excluir);
        $("#btnAdicionar").bind("click", Adicionar);
        $('#c_valor').mask('#.##0,00', {
            reverse: true
        });

        $("#c_referencia").mask("00:00");
    });

    function Excluir() {
        var par = $(this).parent().parent(); //tr
        par.remove();
    };



    function func_select(v_id) {

        $.ajax({
            type: "POST",
            url: "lib/lib_col_calculo_folha_pagamento.php",
            data: {
                "v_acao": "EV_SELECT",
                "v_id": v_id
            },
            success: function(data) {

                $("#c_cal_id").val(data[0].id);
                $("#c_cal_competencia").val(data[0].competencia);
                $("#c_cal_tipo_folha").val(data[0].tipo_folha);
                $("#c_cal_status").val(data[0].status);
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



    function func_carrega_tab() {



        $("#c_id").val("");
        $("#c_nome_col").val("");
        $("#c_matricula").val("");
        $("#c_select_rubrica").val("");
        $("#c_referencia").val("");
        $("#c_valor").val("");
        $("#c_id_tabela_rubrica").val("");


        var v_acao = "LISTAR_CALCULOS";


        $.ajax({
            type: "POST",
            url: "lib/lib_col_calculo_folha_pagamento.php",
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
                    if (data[v_index].tipo_folha == 11) {
                        data[v_index].tipo_folha = "Cálculo Mensal";
                    }
                    if (data[v_index].tipo_folha == 12) {
                        data[v_index].tipo_folha = "Folha Complementar";
                    }
                    if (data[v_index].tipo_folha == 14) {
                        data[v_index].tipo_folha = "Pamento de Dissídio";
                    }
                    if (data[v_index].tipo_folha == 31) {
                        data[v_index].tipo_folha = "Adiantamento 13° Salário";
                    }
                    if (data[v_index].tipo_folha == 32) {
                        data[v_index].tipo_folha = "13° Sálario Integral";
                    }
                    if (data[v_index].tipo_folha == 91) {
                        data[v_index].tipo_folha = "Adiantamento Salarial";
                    }
                    if (data[v_index].tipo_folha == 93) {
                        data[v_index].tipo_folha = "Especiais";
                    }
                    if (data[v_index].status == 0) {
                        data[v_index].status = "Aberto";
                    }
                    if (data[v_index].status == 1) {
                        data[v_index].status = "Finalizado";
                    }
                    if (data[v_index].status == 2) {
                        data[v_index].status = "Em Andamento";
                    }
                    if (data[v_index].status == 3) {
                        data[v_index].status = "Integrado";
                    }
                    if (data[v_index].status == 4) {
                        data[v_index].status = "Solicitado Abertura";

                    }
                    // options += '<tr  style="cursor: pointer;" onclick="func_select(\'' + data[v_index].id + '\');"> </input> ><td ><i style="cursor: pointer;"" class="fa fa-pencil-square-o fa-2x" title="Cálculo" aria-hidden="true" data-toggle="modal" data-target="#carrega_calculo_modal"></i></td><td>' + data[v_index].competencia + '</td><td>' + data[v_index].tipo_folha + '</td><td>' + data[v_index].status + '</td><td><i class="fa fa-arrow-circle-up fa-2x" aria-hidden="true" title="Integrar Lançamentos" onclick="func_integra(' + data[v_index].id + ', \'' + data[v_index].competencia + '\')"style="color:blue;"></i></td>';
                    // options += '<tr  style="cursor: pointer;" onclick="func_select(\'' + data[v_index].id + '\');"> </input> ><td ><i style="cursor: pointer;"" class="fa fa-pencil-square-o fa-2x" title="Cálculo" aria-hidden="true" data-toggle="modal" data-target="#carrega_calculo_modal"></i></td><td>' + data[v_index].competencia + '</td><td>' + data[v_index].tipo_folha + '</td><td>' + data[v_index].status + '</td><td><i class="fa fa-arrow-circle-up fa-2x" aria-hidden="true" title="Integrar Lançamentos" onclick="func_integra(' + data[v_index].id + ', \'' + data[v_index].competencia + '\')"style="color:green;"></i></td>';
                    options += '<tr style="cursor: pointer;" onclick="func_select(\'' + data[v_index].id + '\');"></input><td><i style="cursor: pointer;"" class="fa fa-pencil-square-o fa-2x" title="Cálculo" aria-hidden="true" data-toggle="modal" data-target="#carrega_calculo_modal"></i></td><td>' + data[v_index].id + '</td><td>' + data[v_index].competencia + '</td><td>' + data[v_index].tipo_folha + '</td><td>' + data[v_index].status + '</td><td><i class="fa fa-arrow-circle-up fa-2x" aria-hidden="true" title="Integrar Lançamentos" onclick="func_integra(' + data[v_index].id + ', \'' + data[v_index].competencia + '\')"style="color:green;"></i></td><td><i class="fa fa-arrow-circle-down fa-2x" aria-hidden="true" title="Desfazer Integração" onclick="func_integra_excluir(' + data[v_index].id + ', \'' + data[v_index].competencia + '\')"style="color:red;"></i></td>';
                }
                $('#tab1b').html(options);


                $("#tab1").DataTable({
                    "language": {
                        "url": "../class/DataTables/portugues.json",
                    },
                    "columnDefs": [{
                        "width": "15%",
                        "targets": 0,
                    }],

                    "lengthMenu": [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "Todos"]
                    ],
                    "order": [
                        [1, "desc"]
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





    // function dataAtualFormatada() {
    //     var data = new Date(),
    //         mes = (data.getMonth() + 1).toString().padStart(2, '0'), //+1 pois no getMonth Janeiro começa com zero.
    //         ano = data.getFullYear();
    //     return mes + "/" + ano;
    // }








    function func_atualiza() {

        var v_acao = "EV_ATUALIZA";
        var v_competencia = $("#c_cal_competencia").val();
        var v_tipo_folha = $("#c_cal_tipo_folha").val();
        var v_status = $("#c_cal_status").val()
        var v_id = $("#c_cal_id").val()

        // alert(v_acao);
        // alert(v_id);
        // alert(v_matricula);
        // alert(v_competencia);
        // alert(v_tipo_folha);
        // alert(v_rubrica);
        // alert(v_referencia);
        // alert(v_valor);


        $.ajax({
            type: "POST",

            url: "lib/lib_col_calculo_folha_pagamento.php",
            data: {
                "v_acao": v_acao,
                "v_competencia": v_competencia,
                "v_status": v_status,
                "v_id": v_id,
                "v_tipo_folha": v_tipo_folha

            },
            success: function(data) {
                var v_json = JSON.parse(data);
                Swal.fire({
                    icon: v_json.msg_ev,
                    title: v_json.msg_titulo,
                    text: v_json.msg
                })

                if (v_json.msg_ev == "success") {
                    $('#carrega_calculo_modal').modal('hide');
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












    function func_salvar() {

        var v_acao = "EV_SALVAR";
        var v_competencia = $("#c_competencia").val();
        var v_tipo_folha = $("#c_tipo_folha").val();
        var v_status = $("#c_status").val()

        // alert(v_acao);
        // alert(v_id);
        // alert(v_matricula);
        // alert(v_competencia);
        // alert(v_tipo_folha);
        // alert(v_rubrica);
        // alert(v_referencia);
        // alert(v_valor);


        $.ajax({
            type: "POST",

            url: "lib/lib_col_calculo_folha_pagamento.php",
            data: {
                "v_acao": v_acao,
                "v_competencia": v_competencia,
                "v_status": v_status,
                "v_tipo_folha": v_tipo_folha

            },
            success: function(data) {
                var v_json = JSON.parse(data);
                Swal.fire({
                    icon: v_json.msg_ev,
                    title: v_json.msg_titulo,
                    text: v_json.msg
                })

                if (v_json.msg_ev == "success") {
                    $('#cad_calculo_modal').modal('hide');
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


    function func_lista_calculo() {

        var v_acao = "LISTAR_CALCULO";
        $.ajax({
            type: "POST",
            url: "lib/lib_col_lancamento_variaveis.php",
            data: {
                "v_acao": v_acao
            },
            success: function(data) {
                var options = '';
                $("#c_select_calculo").empty();
                for (v_index = 0; v_index < data.length; v_index++) {
                    options += "<option value='" + data[v_index].competencia + "'>" + "Calculo: " + data[v_index].id + " - Competencia: " + data[v_index].competencia + ", Tipo Folha: " + data[v_index].tipo_folha + "</option>";
                }
                $('#c_select_calculo').html(options);

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

    function func_integra(vj_id, vj_competencia) {
        var v_acao = "ADD_VARIAVEIS";
        // var v_status = $("#c_cal_status").val(); //add        
        $.ajax({
            type: "POST",
            url: "lib/lib_integra_variaveis.php",
            data: {
                "v_acao": v_acao,
                "v_competencia": vj_competencia,
                "v_id": vj_id
                // "v_status": v_status //add
            },
            success: function(data) {
                // $("#c_cal_status").val(data[0].status);

                // var options = '';
                // $("#c_select_calculo").empty();
                // for (v_index = 0; v_index < data.length; v_index++) {
                //     options += "<option value='" + data[v_index].competencia + "'>" + "Calculo: " + data[v_index].id + " - Competencia: " + data[v_index].competencia + ", Tipo Folha: " + data[v_index].tipo_folha + "</option>";
                // }
                // $('#c_select_calculo').html(options);
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

    function func_integra_excluir(vj_id, vj_competencia) {
        var v_acao = "LIMPAR_VARIAVEIS";
        $.ajax({
            type: "POST",
            url: "lib/lib_integra_excluir_variaveis.php",
            data: {
                "v_acao": v_acao,
                "v_competencia": vj_competencia,
                "v_id": vj_id
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
</script>

</html>