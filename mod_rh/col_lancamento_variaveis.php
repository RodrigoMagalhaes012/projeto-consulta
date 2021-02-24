<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0003") == 0) {
    print('<script> alert(\'Favor realizar login novamente!\'); location.href = \'https://app.agrocontar.com.br\'; </script>');
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

    <div class="load"> <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
        <span class="sr-only">Loading...</span>
    </div>

    <div class="container">
        <input type="hidden" id="c_index_var" value="0">
        <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-12">
                    <h3>Lançamento de Variáveis</h3>
                </div>
            </div>
        </div>


        <div id="box_tab_titulo2" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-2">
                    <button id="btnCriaCalculo" aria-hidden="true" aria-hidden="true" data-toggle="modal" data-target="#cad_calculo_modal" class="btn btn-success" style="width: 90%;"> <i class="fa fa-plus-circle fa-1x"></i> Criar calculo

                    </button>
                </div>
                <div class="form-group col-sm-8" style="margin-left: -30px;">

                    <input type="hidden" id="c_competencia_cal">
                    <input type="hidden" id="c_tipo_folha_cal">
                    <input type="hidden" id="c_id_calc">
                    <input type="hidden" id="c_status_calc">

                    <select onchange="func_carrega_calc();" id="c_select_calculo" class="form-control class_inputs">


                    </select>
                </div>

                <div class="col-sm-2" style="padding: 0px;">
                    <button class="btn btn-primary" style="width: 90%;" onclick="func();" disabled>PDF</button>
                </div>
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
                            <th style="text-align: center;">Lançamento</th>
                            <th style="text-align: center;">Colaborador</th>
                            <th style="text-align: center;">Cargo</th>
                            <th style="text-align: center;">Departamento</th>
                            <th style="text-align: center;">Matricula</th>
                            <!-- <th style="text-align: center;">Com lançamentos anteriores</th> -->
                        </tr>
                    </thead>
                    <tbody id="tab1b" style="font-weight: normal;">
                    </tbody>
                </table>
            </div>
        </div>



        <div id="box_form_footer1" class="box-footer">
            <div class="row">
                <div class="form-group col-sm-sm-xs-12 text-right" style="margin-bottom: 20px;">
                    <button id="btn_finaliza_comp" class="btn btn-primary" style="border-radius: 10px; width: 200px; margin-top:30px; height: 40px" onclick="func_altera_status();">Finalizar Lançamentos</button>
                    <button id="btn_solicita_abertura" class="btn btn-warning" style="border-radius: 10px; width: 200px; margin-top:30px; height: 40px" onclick="func_altera_status_abertura();">Solicitar Abertura</button>
                    <button disabled id="btn_calc_fechado" class="btn btn-info" style="border-radius: 10px; width: 200px; margin-top:30px; height: 40px">Integrado </button>
                </div>
            </div>
        </div>
        <!-- <button id="btn_finaliza_competencia" class="btn-lg btn-warning" style="border-radius: 20px; margin-top: 30px" onclick="func_altera_status();">Finalizar Lançamentos</button> -->


        <!-- Realiza lançamentos -->
        <div class="modal fade" id="flipFlop" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button id="button_close_modal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="modalLabel">Lançamento de Variáveis</h4>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="form-group col-sm-9">
                                <label style="margin-left: 15px;" for="c_nome_col">Colaborador</label>
                                <input style="margin-left: 15px;" disabled id="c_nome_col" type="text" class="form-control class_inputs">
                            </div>
                            <div class="form-group col-sm-1" style="display: none;">
                                <label style="visibility: hidden;" for="c_id">Id</label>
                                <input style="visibility: hidden;" disabled id="c_id" type="text" class="form-control class_inputs">
                            </div>
                            <div class="form-group col-sm-2">
                                <label style="margin-left: 15px;" for="c_matricula">Matricula</label>
                                <input style="margin-left: 15px;" disabled id="c_matricula" class="form-control class_inputs">
                            </div>
                        </div>

                        <div class="row w-100" style="padding: 30px;">
                            <div class="row w-100" style="margin: 0px 0px 10px 5px; font-weight: bold; font-size: 16px; color: green">
                                Lançamentos
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="tblCadastro" style="width: 100%; border-collapse: collapse;">
                                        <thead>
                                            <tr>
                                                <th style="border: 1px solid black;text-align: center; color:green">Rubrica</th>
                                                <th style="border: 1px solid black;text-align: center; color:green">Referência</th>
                                                <th style="border: 1px solid black;text-align: center; color:green">Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 10px; padding   : 0px;">
                                <div class="col-sm-12">
                                    <button id="btnAdicionar"> <i style="cursor: pointer; color:blue" class="fa fa-plus-circle fa-3x" aria-hidden="true"></i></button>

                                </div>
                                <div class="col-sm-12" style="padding: 5px; padding-right: 0px; margin-top: 5px; margin-bottom: 10px; text-align: right;">

                                    <button id="BtnSalvarLan" class="btn btn-success" style="width: 15%; height: 15%;border-radius: 10px;" onclick="func_salvar();">Salvar </button>

                                    <!-- <button class="btn-lg btn-success" style="border-radius: 20px;" onclick="func_salvar();">Salvar</button> -->
                                    <!-- <button class="btn-lg btn-danger" style="border-radius: 20px;" onclick="Excluir();">Excluir</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Criar Cálculo -->
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
                                    <div class="form-group col-sm-6">
                                        <label style="margin-left: 15px;" for="c_cad_competencia">Competencia</label>
                                        <select id="c_cad_competencia" class="form-control class_inputs">
                                            <option value="-" selected>Competencia</option>
                                            <option value="<?php echo date("Y"); ?>-01-01">01/<?php echo date("Y"); ?></option>
                                            <option value="<?php echo date("Y"); ?>-02-01">02/<?php echo date("Y"); ?></option>
                                            <option value="<?php echo date("Y"); ?>-03-01">03/<?php echo date("Y"); ?></option>
                                            <option value="<?php echo date("Y"); ?>-04-01">04/<?php echo date("Y"); ?></option>
                                            <option value="<?php echo date("Y"); ?>-05-01">05/<?php echo date("Y"); ?></option>
                                            <option value="<?php echo date("Y"); ?>-06-01">06/<?php echo date("Y"); ?></option>
                                            <option value="<?php echo date("Y"); ?>-07-01">07/<?php echo date("Y"); ?></option>
                                            <option value="<?php echo date("Y"); ?>-08-01">08/<?php echo date("Y"); ?></option>
                                            <option value="<?php echo date("Y"); ?>-09-01">09/<?php echo date("Y"); ?></option>
                                            <option value="<?php echo date("Y"); ?>-10-01">10/<?php echo date("Y"); ?></option>
                                            <option value="<?php echo date("Y"); ?>-11-01">11/<?php echo date("Y"); ?></option>
                                            <option value="<?php echo date("Y"); ?>-12-01">12/<?php echo date("Y"); ?></option>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label style="margin-left: 15px;" for="c_matricula">Tipo Folha</label>
                                        <select id="c_tipo_folha" class="form-control class_inputs">
                                            <option value="11" selected>11 - Cálculo Mensal </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 10px; padding: 0px;">
                                <div class="col-sm-12" style="padding: 5px; padding-right: 0px; margin-top: 5px; margin-bottom: 10px; text-align: right;">
                                    <button class="btn-lg btn-success" style="border-radius: 20px;" onclick="func_salvar_calc();">Salvar</button>

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

        $("#btnExcluirLan").prop("disabled", true);
        $("#btnAdicionar").prop("disabled", true);
        $("#BtnSalvarLan").prop("disabled", true);
        $("#btn_solicita_abertura").hide()
        $("#btn_finaliza_comp").hide()
        $("#btn_calc_fechado").hide()

        func_lista_calculo();


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

    function Adicionar() {


        var v_index = $("#c_index_var").val();
        func_lista_rubrica("ADD", v_index, 0);


        $("#tblCadastro").append(
            `<tr>
                <td style="border: 1px solid black;text-align: center;">
                   <select id="c_select_rubrica_${v_index}" class="form-control class_inputs" placeholder="SELECIONE UMA RUBRICA" onchange="if($('#c_select_rubrica_${v_index} option:selected').html().substring($('#c_select_rubrica_${v_index} option:selected').html().length - 4, $('#c_select_rubrica_${v_index} option:selected').html().length) == '(HR)'){$('#c_valor_${v_index}').val(0); $('#c_valor_${v_index}').prop('disabled', true);}else{$('#c_valor_${v_index}').prop('disabled', false);};">
                 </td>
                 <td style="border: 1px solid black;text-align: center;">
                    <input id="c_referencia_${v_index}" type="text" onkeyup="if($(this).val() == 0){$(this).val(0);}" value="0">
                 </td>
                 <td style="border: 1px solid black;text-align: center;">
                    R$<input id="c_valor_${v_index}" type="number" step="0.01" name="quantity" min="0.01" onkeyup="if($(this).val() == 0){$(this).val(0);}" value="0">
                 </td>                                    
                 <td>
                    <button id="btnExcluirLan" style="cursor: pointer; color:red; margin-left:7px" class='btnExcluir'><i style="color:red;" class="fa fa-times-circle fa-2x" aria-hidden="true"></i></button>                
                 </td>
            </tr>`)




        // $("#btnAdicionar").prop("disabled", true);


        $("#c_index_var").val(parseInt(v_index) + 1);
    };



    function func_lista_rubrica(v_acao_lista, v_index_tab, v_valor) {

        $.ajax({
            type: "POST",
            url: "lib/lib_col_lancamento_variaveis.php",
            data: {
                "v_acao": "LISTA_RUBRICA",
                "v_acao_lista": v_acao_lista,
                "v_index": v_index_tab
            },
            success: function(data) {

                var options = '<option value="0" selected>SELECIONE UMA RUBRICA</option>';
                $("#c_select_rubrica_" + v_index_tab).empty();

                for (v_index = 0; v_index < data.length; v_index++) {

                    data[v_index].tipo_lancamento


                    options += '<option value="' + data[v_index].rubrica + '">' + data[v_index].rubrica + ' - ' + data[v_index].descricao + '</option>';
                }
                $("#c_select_rubrica_" + v_index_tab).html(options);


                $("#c_select_rubrica_" + v_index_tab).val(v_valor);


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

        $("#c_index_var").val(1000);
        var v_status_calc = $("#c_status_calc").val();

        if (v_status_calc == 0 || v_status_calc == 2) {
            v_status_calc = "";
            $("#btnAdicionar").prop("disabled", false);
            $("#BtnSalvarLan").prop("disabled", false);
        } else {
            v_status_calc = "disabled";
            $("#btnAdicionar").prop("disabled", true);
            $("#BtnSalvarLan").prop("disabled", true);
        }

        var v_id_calc = $("#c_select_calculo").val();

        $.ajax({
            type: "POST",
            url: "lib/lib_col_lancamento_variaveis.php",
            data: {
                "v_acao": "EV_SELECT",
                "v_id": v_id,
                "v_matricula": v_matricula,
                "v_id_calc": v_id_calc
            },
            success: function(data) {

                $("#c_id").val(data[0].id);
                $("#c_nome_col").val(data[0].nome);
                $("#c_matricula").val(data[0].matricula);

                $("#tblCadastro").empty();
                var v_tipo = "";
                var v_tipo_combo = "";
                var v_index = 0;
                for (v_linha = 1; v_linha < data.length; v_linha++) {

                    v_index = $("#c_index_var").val();
                    v_tipo = data[v_linha].tipo;
                    if (v_status_calc == "") {
                        if (v_tipo == "HR") {
                            v_tipo = "disabled";
                        }
                    }

                    func_lista_rubrica("-", v_index, data[v_linha].rubrica);
                    // alert(v_index);

                    $("#tblCadastro").append(
                        `<tr id="tblCadastro_li_${data[v_linha].id_lancamento}">
                            <td style="border: 1px solid black;text-align: center;">
                            <select ` + v_status_calc + ` id="c_select_rubrica_${v_index}" onchange="if($('#c_select_rubrica_${v_index} option:selected').html().substring($('#c_select_rubrica_${v_index} option:selected').html().length - 4, $('#c_select_rubrica_${v_index} option:selected').html().length) == '(HR)'){$('#c_valor_${v_index}').val(0); $('#c_valor_${v_index}').prop('disabled', true);}else{$('#c_valor_${v_index}').prop('disabled', false);};" class="form-control class_inputs" placeholder="SELECIONE UMA RUBRICA">
                            </td>
                            <td style="border: 1px solid black;text-align: center;">
                                <input ` + v_status_calc + ` id="c_referencia_${v_index}" type="text" onkeyup="if($(this).val() == 0){$(this).val(0);}" value="0">
                                <input ` + v_status_calc + ` id="c_id_lanc_${v_index}" type="hidden">
                            </td>
                            <td style="border: 1px solid black;text-align: center;">
                                R$<input ` + v_tipo + `` + v_status_calc + ` id="c_valor_${v_index}" type="number" step="0.01" name="quantity" min="0.01" onkeyup="if($(this).val() == 0){$(this).val(0);}" value="0">
                            </td>                                    
                            <td>
                                <button ` + v_status_calc + ` id="btnExcluirLan" title="Excluir lançamentos" onclick="func_excluir(${data[v_linha].id_lancamento})" style="cursor: pointer; color:red; margin-left:7px" class='btnExcluir'><i style="color:red;" class="fa fa-times-circle fa-2x" aria-hidden="true"></i></button>                
                            </td>
                        </tr>`)

                    // $("#btnAdicionar").prop("disabled", true);


                    $("#c_index_var").val(parseInt(v_index) + 1);

                    $("#c_valor_" + v_index).val(data[v_linha].valor);

                    $("#c_referencia_" + v_index).val(data[v_linha].referencia);

                    $("#c_id_lanc_" + v_index).val(data[v_linha].id_lancamento);

                    // console.log(data[v_linha].id_lancamento);



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


    function func_carrega_calc() {

        var v_id_calc = $("#c_select_calculo").val();

        $.ajax({
            type: "POST",
            url: "lib/lib_col_lancamento_variaveis.php",
            data: {
                "v_acao": "CARREGA_CALC",
                "v_id_calc": v_id_calc
            },
            success: function(data) {


                $("#c_competencia_cal").val(data[0].competencia);
                $("#c_tipo_folha_cal").val(data[0].tipo_folha);
                $("#c_id_calc").val(data[0].id);
                $("#c_status_calc").val(data[0].status);


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





    function func_carrega_lancamentos(v_id) {

        $.ajax({
            type: "POST",
            url: "lib/lib_col_lancamento_variaveis.php",
            data: {
                "v_acao": "CARREGA_LANCAMENTOS",
                "v_id": v_id
            },
            success: function(data) {

                $("#c_id").val(data[0].id);
                $("#c_nome_col").val(data[0].nome);
                $("#c_matricula").val(data[0].matricula);

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

        let v_competencia_cal = $("#c_competencia_cal").val();
        let v_status_calc = $("#c_status_calc").val();

        $("#c_id").val("");
        $("#c_nome_col").val("");
        $("#c_matricula").val("");
        $("#c_select_rubrica").val("");
        $("#c_referencia").val("");
        $("#c_valor").val("");
        $("#c_id_tabela_rubrica").val("");


        // alert(v_status_calc);



        if (v_status_calc == "0" || "2") {
            document.getElementById('btn_solicita_abertura').style.display = 'none';
            document.getElementById('btn_calc_fechado').style.display = 'none';
            document.getElementById('btn_finaliza_comp').style.display = 'inline';
            $("#btnExcluirLan").prop("disabled", false);
            $("#btnAdicionar").prop("disabled", false);
            $("#BtnSalvarLan").prop("disabled", false);
        }
        if (v_status_calc == "1") {
            document.getElementById('btn_finaliza_comp').style.display = 'none';
            document.getElementById('btn_calc_fechado').style.display = 'none';
            document.getElementById('btn_solicita_abertura').style.display = 'inline';
            $("#btnExcluirLan").prop("disabled", true);
            $("#btnAdicionar").prop("disabled", true);
            $("#BtnSalvarLan").prop("disabled", true);
        }
        if (v_status_calc == "3") {
            document.getElementById('btn_finaliza_comp').style.display = 'none';
            document.getElementById('btn_solicita_abertura').style.display = 'none';
            document.getElementById('btn_calc_fechado').style.display = 'inline';
            $("#btnExcluirLan").prop("disabled", true);
            $("#btnAdicionar").prop("disabled", true);
            $("#BtnSalvarLan").prop("disabled", true);
        }


        var v_acao = "LISTAR_USUARIOS";

        $.ajax({
            type: "POST",
            url: "lib/lib_col_lancamento_variaveis.php",
            data: {
                "v_acao": v_acao,
                "v_competencia_cal": v_competencia_cal

            },
            success: function(data) {
                $('#tab1').DataTable().destroy();

                var options = '';
                var v_index = 0;
                var v_num_linhas = 0;

                // alert(v_status_calc);



                $("#tab1b").empty();
                v_num_linhas = data[0].linhas;
                for (v_index = 1; v_index < data.length; v_index++) {
                    options += '<tr  style="cursor: pointer;" onclick="func_select(' + data[v_index].Id + "," + data[v_index].Matricula + ');"> </input> ><td ><i style="cursor: pointer;"" class="fa fa-pencil-square-o fa-2x" aria-hidden="true" data-toggle="modal" data-target="#flipFlop"></i></td><td>' + data[v_index].Nome + '</td><td>' + data[v_index].Cargo + '</td><td>' + data[v_index].Departamento + '</td><td>' + data[v_index].Matricula + '</td>';
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
                        [1, "asc"]
                    ],
                    "scrollY": "50vh",
                    "scrollX": true,
                    "scrollCollapse": true,
                    "paging": true
                });

                // if (v_status_calc == "1" || "3" || "4") {
                //     // document.getElementById('btnAdicionar').style.display = 'none';
                //     // document.getElementById('BtnSalvarLan').style.display = 'none';
                //     // document.getElementById('btnExcluirLan').style.display = 'none';
                //     alert("teste1");

                //     $("#btnExcluirLan").prop("disabled", true);
                //     $("#btnAdicionar").prop("disabled", true);
                //     $("#BtnSalvarLan").prop("disabled", true);

                // } else if (v_status_calc == "0" ?? "2") {
                //     alert("teste2");
                //     $("#btnExcluirLan").prop("disabled", false);
                //     $("#btnAdicionar").prop("disabled", false);
                //     $("#BtnSalvarLan").prop("disabled", false);
                // }

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




    function func_altera_status() {

        let v_id_calc = $("#c_id_calc").val();
        let v_acao = "ALTERA_STATUS";


        $.ajax({
            type: "POST",

            url: "lib/lib_col_lancamento_variaveis.php",
            data: {
                "v_acao": v_acao,
                "v_id_calc": v_id_calc
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



    function func_altera_status_abertura() {

        let v_id_calc = $("#c_id_calc").val();
        let v_acao = "ALTERA_STATUS_ABERTURA";

        $.ajax({
            type: "POST",

            url: "lib/lib_col_lancamento_variaveis.php",
            data: {
                "v_acao": v_acao,
                "v_id_calc": v_id_calc
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



    function func_salvar() {


        let v_index = $("#c_index_var").val();
        let v_sql = "";
        let v_sql_up = "";

        for (v_index; v_index >= 0; v_index--) {

            if ($("#c_referencia_" + v_index).length > 0) {
                // console.log($("#c_referencia_" + v_index));


                let v_matricula = $("#c_matricula").val();
                let v_id_calc = $("#c_id_calc").val();
                let v_rubrica = $("#c_select_rubrica_" + v_index).val();
                let v_referencia = $("#c_referencia_" + v_index).val();
                let v_valor = $("#c_valor_" + v_index).val();

                // alert("teste");

                v_sql += "(v_id_empresa, " + v_matricula + ", " + v_rubrica + ", " + v_referencia + ", " + v_valor + ", 1, " + v_id_calc + "), ";

                v_sql_up += "criptografado_0 criptografado_1_criptografado_2.criptografado_3 criptografado_4 id_empresa = v_id_empresa, matricula='" + v_matricula + "', rubrica=" + v_rubrica + ", referencia=" + v_referencia + ", valor=" + v_valor + ",id_tabela_rubrica= 1, id_calculo=" + v_id_calc + " WHERE id_empresa = v_id_empresa" + " AND matricula='" + v_matricula + "' AND rubrica=" + v_rubrica + "AND id_calculo=" + v_id_calc + ";";
            }
        };

        let v_acao = "EV_SALVAR";

        $.ajax({
            type: "POST",

            url: "lib/lib_col_lancamento_variaveis.php",
            data: {
                "v_acao": v_acao,
                "v_sql": v_sql,
                "v_sql_up": v_sql_up

            },
            success: function(data) {
                var v_json = JSON.parse(data);
                Swal.fire({
                    icon: v_json.msg_ev,
                    title: v_json.msg_titulo,
                    text: v_json.msg
                })

                if (v_json.msg_ev == "success") {
                    $('#flipFlop').modal('hide');
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



    function func_salvar_calc() {

        var v_acao = "EV_SALVAR_CALC";
        var v_competencia = $("#c_cad_competencia").val();
        var v_tipo_folha = $("#c_tipo_folha").val();


        $.ajax({
            type: "POST",

            url: "lib/lib_col_lancamento_variaveis.php",
            data: {
                "v_acao": v_acao,
                "v_competencia": v_competencia,
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

                    func_lista_calculo();

                }
            },
        });



    }


    function func_lista_calculo(v_status) {

        var v_acao = "LISTAR_CALCULO";
        $.ajax({
            type: "POST",
            url: "lib/lib_col_lancamento_variaveis.php",
            data: {
                "v_acao": v_acao
            },
            success: function(data) {
                let options = '<option value="0">SELECIONE UM CALCULO</option>';

                $('#c_select_calculo').empty();



                for (v_index = 0; v_index < data.length; v_index++) {
                    options += "<option value='" + data[v_index].id + "'>" + "  Calculo: " + data[v_index].id + " - Competencia: " + data[v_index].competencia + ", Tipo Folha: " + data[v_index].tipo_folha + "</option>";
                }

                $('#c_select_calculo').html(options);
                // $('#c_select_calculo').val(data[v_index].status);
                $('.load').hide();

                if (data[v_index].status == "0" || data[v_index].status == "2") {
                    $("#btn_finaliza_comp").show()
                    $("#btn_solicita_abertura").hide()
                } else {
                    $("#btn_solicita_abertura").show()
                    $("#btn_finaliza_comp").hide()
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


















    function func_excluir(v_id_lanc) {

        var v_id_lanc = v_id_lanc;

        // alert(v_id_lanc);


        Swal.fire({
            title: 'Você tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, pode excluir!'
        }).then((result) => {
            if (result.value) {

                v_acao = "EV_EXCLUIR";

                $.ajax({
                    type: "POST",
                    url: "lib/lib_col_lancamento_variaveis.php",
                    data: {
                        "v_acao": v_acao,
                        "v_id_lanc": v_id_lanc
                    },
                    success: function(result) {
                        // alert(result);
                        var v_json = JSON.parse(result);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        $('#tblCadastro_li_' + v_id_lanc).remove();
                    },
                    error: function(request, status, erro) {
                        swal("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }
                });
            }

        })
    }
</script>



</html>