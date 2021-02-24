<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0045") == 0) {
    print('<script> alert(\'Acesso Negado.\'); location.href = \'https://app.agrocontar.com.br\'; </script>');
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="../css/animate.css" rel="stylesheet">
    <link rel="stylesheet" href="../class/alert/css/class_alert.css" id="theme-styles">

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

        <div id="box_form_titulo" class="row" style="margin-top: 40px; background-image: linear-gradient(to left, #6c3a8e , white); margin-bottom: 10px;">
            <div class="row">
                <div class="form-group col-sm-12 col" style="font-weight: bold; font-size: 25px; color: #523B8F;">LGPD - Configuração da Gestão de Dados</div>
            </div>
        </div>


        <div id="box_tab1" class="row" style="height: auto; background-color: white; border: none; overflow-x: hidden;">
            <input id="c_acao" type="hidden" value="">
            <div class="box-body">
                <table id="tab1" class="table" style="color: black; width: 100%;">
                    <thead style="font-weight: bold;">
                        <tr>
                            <th>Campo</th>
                            <th>Tipo</th>
                            <th>Categoria</th>
                            <th>Ação Autorizada</th>
                        </tr>
                    </thead>
                    <tbody id="tab1b" style="font-weight: normal;">

                    </tbody>
                </table>
            </div>
        </div>


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
                    <div class="form-group col-sm-4">
                        <label for="c_conf_campo">Campo</label>
                        <input disabled id="c_conf_campo" type="text" class="form-control class_inputs">
                    </div>
                    <div class="form-group col-sm-4">
                        <input type="hidden" id="c_conf_tipo_maxid" value="0">
                        <label for="c_conf_tipo">Tipo</label>
                        <select disabled id="c_conf_tipo" class="form-control class_inputs" onchange="if($('#c_conf_tipo').val() == '000'){$('#c_conf_tipo').val(0); func_carrega_campo_edit('c_conf_tipo'); $('#exampleModalLive').modal();}">

                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                        <input type="hidden" id="c_conf_categoria_maxid" value="0">
                        <label for="c_conf_categoria">Categoria</label>
                        <select disabled id="c_conf_categoria" class="form-control class_inputs" onchange="if($('#c_conf_categoria').val() == '000'){$('#c_conf_categoria').val(0); func_carrega_campo_edit('c_conf_categoria'); $('#exampleModalLive').modal();}">

                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <input type="hidden" id="c_conf_req_legal_maxid" value="0">
                        <label for="c_conf_req_legal">Requisito Legal</label>
                        <select disabled id="c_conf_req_legal" class="form-control class_inputs" onchange="if($('#c_conf_req_legal').val() == '000'){$('#c_conf_req_legal').val(0); func_carrega_campo_edit('c_conf_req_legal'); $('#exampleModalLive').modal();}">
                            <option value="0" selected>SELECIONE</option>

                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="c_conf_acao">Ação</label>
                        <select disabled id="c_conf_acao" class="form-control class_inputs">

                        </select>
                    </div>
                </div>

                <div class="row" style="padding: 10px;">
                    <label for="c_conf_finalidade">Finalidade (até 120 caracteres)</label>
                    <textarea maxlength="130" readonly class="form-control class_inputs" id="c_conf_finalidade" rows="20" style="white-space: pre-wrap; overflow-wrap: break-word; line-height: 20px; height: 110px;"></textarea>
                </div>

            </div>
        </div>
        <div class="box-footer">
            <div class="row w-100">
                <div class="form-group col-sm-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                    <button disabled id="btn_salvar_reg" class="btn btn-success" onclick="func_salvar();">Salvar Configuração</button>
                </div>
            </div>
        </div>



        <div id="exampleModalLive" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" style="display: none;" aria-hidden="true">
            <input type="hidden" id="c_campo_edit" value="">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <ul id="ul_campo_edit" class="list-group">

                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button id="btn_close_modal_itens" type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
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
        func_carrega_combos();
    });



    function func_carrega_tab() {

        var v_acao = "CARREGA_TAB";
        $.ajax({
            type: "POST",
            url: "../mod_lgpd/lib/lib_pdo_config.php",
            data: {
                "v_acao": v_acao
            },
            success: function(data) {

                $('#tab1').dataTable({
                    "bDestroy": true
                }).fnDestroy();

                var options = '';
                $("#tab1b").empty();
                for (v_index = 1; v_index < data.length; v_index++) {
                    options += '<tr  style="cursor: pointer;" onclick="func_select(\'' + data[v_index].id + '\');"><td>' + data[v_index].campo + '</td><td>' + data[v_index].tipo + '</td><td>' + data[v_index].categoria + '</td><td>' + data[v_index].acao + '</td></tr>';
                }
                $('#tab1b').html(options);

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



    function func_carrega_combos() {

        $("#c_conf_tipo").empty();
        $("#c_conf_tipo_maxid").val(0);

        $("#c_conf_categoria").empty();
        $("#c_conf_categoria_maxid").val(0);

        $("#c_conf_req_legal").empty();
        $("#c_conf_req_legal_maxid").val(0);

        $("#c_conf_acao").empty();

        var v_acao = "CARREGA_COMBOS";
        $.ajax({
            type: "POST",
            url: "../mod_lgpd/lib/lib_pdo_config.php",
            data: {
                "v_acao": v_acao
            },
            success: function(data) {

                var options_tipo = '<option style="background-color: #9AFF9A;" value="000">EDITAR ITENS</option>';
                var options_categoria = '<option style="background-color: #9AFF9A;" value="000">EDITAR ITENS</option>';
                var options_req_legal = '<option style="background-color: #9AFF9A;" value="000">EDITAR ITENS</option>';
                options_tipo += '<option value="0">SELECIONE</option>';
                options_categoria += '<option value="0">SELECIONE</option>';
                options_req_legal += '<option value="0">SELECIONE</option>';

                var options_acao = '<option value="0">SELECIONE</option>';

                for (v_index = 0; v_index < data.length; v_index++) {

                    if (data[v_index].cb == "acoes") {
                        options_acao += '<option value="' + data[v_index].valor + '">' + data[v_index].lab + '</option>';
                    }

                    if (data[v_index].cb == "categorias") {
                        options_categoria += '<option value="' + data[v_index].valor + '">' + data[v_index].lab + '</option>';
                        if (data[v_index].valor > $("#c_conf_categoria_maxid").val()) {
                            $("#c_conf_categoria_maxid").val(data[v_index].valor);
                        }
                    }

                    if (data[v_index].cb == "req_legais") {
                        options_req_legal += '<option value="' + data[v_index].valor + '">' + data[v_index].lab + '</option>';
                        if (data[v_index].valor > $("#c_conf_req_legal_maxid").val()) {
                            $("#c_conf_req_legal_maxid").val(data[v_index].valor);
                        }
                    }

                    if (data[v_index].cb == "tipos") {
                        options_tipo += '<option value="' + data[v_index].valor + '">' + data[v_index].lab + '</option>';
                        if (data[v_index].valor > $("#c_conf_tipo_maxid").val()) {
                            $("#c_conf_tipo_maxid").val(data[v_index].valor);
                        }
                    }
                }

                $('#c_conf_tipo').html(options_tipo);
                $('#c_conf_categoria').html(options_categoria);
                $('#c_conf_req_legal').html(options_req_legal);
                $('#c_conf_acao').html(options_acao);

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

        var v_acao = "EV_SELECT";

        $.ajax({
            type: "POST",
            url: "../mod_lgpd/lib/lib_pdo_config.php",
            data: {
                "v_acao": v_acao,
                "v_id": v_id
            },
            success: function(data) {

                $("#c_conf_id").val("0");
                $("#c_conf_campo").val("");
                $("#c_conf_tipo").val(0);
                $("#c_conf_categoria").val(0);
                $("#c_conf_req_legal").val(0);
                $("#c_conf_acao").val(0);
                $("#c_conf_finalidade").html("");

                $("#c_acao").val("EV_SELECT");
                $("#c_conf_id").val(data[0].id);
                $("#c_conf_campo").val(data[0].campo);
                $("#c_conf_tipo").val(data[0].tipo);
                $("#c_conf_categoria").val(data[0].categoria);
                $("#c_conf_req_legal").val(data[0].req_legal);
                $("#c_conf_acao").val(data[0].acao);
                $("#c_conf_finalidade").html(data[0].finalidade);

                $("#c_conf_campo").prop("disabled", false);
                $("#c_conf_tipo").prop("disabled", false);
                $("#c_conf_categoria").prop("disabled", false);
                $("#c_conf_req_legal").prop("disabled", false);
                $("#c_conf_acao").prop("disabled", false);
                $("#btn_bloq_conta").prop("disabled", false);
                $("#c_conf_finalidade").prop("readonly", false);
                $("#btn_salvar_reg").prop("disabled", false);

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
        var v_conf_id = $("#c_conf_id").val();
        var v_conf_campo = $("#c_conf_campo").val();
        var v_conf_tipo = $("#c_conf_tipo").val();
        var v_conf_categoria = $("#c_conf_categoria").val();
        var v_conf_req_legal = $("#c_conf_req_legal").val();
        var v_conf_acao = $("#c_conf_acao").val();
        var v_conf_finalidade = $("#c_conf_finalidade").val();

        if (v_conf_campo.length > 0 && v_conf_tipo > 0 && v_conf_categoria > 0 && v_conf_req_legal > 0 && v_conf_acao > 0 && v_conf_finalidade.length > 0) {

            $.ajax({
                type: "POST",
                url: "../mod_lgpd/lib/lib_pdo_config.php",
                data: {
                    "v_acao": v_acao,
                    "v_conf_id": v_conf_id,
                    "v_conf_campo": v_conf_campo,
                    "v_conf_tipo": v_conf_tipo,
                    "v_conf_categoria": v_conf_categoria,
                    "v_conf_req_legal": v_conf_req_legal,
                    "v_conf_acao": v_conf_acao,
                    "v_conf_finalidade": v_conf_finalidade
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


        } else {
            Swal.fire({
                icon: "error",
                title: "FALHA!",
                text: "Todos os campos devem ser preenchidos."
            })
        }

    }




    function func_carrega_campo_edit(vj_campo_edit) {

        var v_campo_edit = vj_campo_edit;
        var v_acao = "CARREGA_CAMPO_EDIT";
        $("#ul_campo_edit").empty();
        $.ajax({
            type: "POST",
            url: "../mod_lgpd/lib/lib_pdo_config.php",
            data: {
                "v_acao": v_acao,
                "v_campo_edit": v_campo_edit
            },
            success: function(data) {
                var options = '';
                for (v_index = 1; v_index < data.length; v_index++) {

                    options += '<li id="li_' + data[v_index].id + '" class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 15px;">';
                    options += data[v_index].campo;
                    if (data[v_index].disabled == "N") {
                        options += '<span class="badge" style="background-color: red; cursor: pointer;" onclick="func_deleta_campo_edit(' + data[v_index].id + ', \'' + v_campo_edit + '\');">X</span>';
                    } else {
                        options += '<span class="badge" style="background-color: gray;">X</span>';
                    }

                    options += '</li>';

                }
                options += '<li class="list-group-item d-flex justify-content-between align-items-center form-inline" style="font-size: 15px;">';
                options += '<div class="row">';
                options += '<div class="col-sm-10 text-left">';
                options += '<input id="c_campo_edit_valor" type="text" class="form-control class_inputs" style="float:left; width: 100%;" maxlength="45">';
                options += '</div>';
                options += '<div class="col-sm-2 text-right">';
                options += '<button style="float:left;" type="button" class="btn btn-success" onclick="func_adiciona_campo_edit(\'' + v_campo_edit + '\');">INSERIR</button>';
                options += '</div>';
                options += '</div>';
                options += '</li>';
                $("#ul_campo_edit").html(options);
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



    function func_adiciona_campo_edit(vj_campo_edit_valor) {

        var v_acao = "ADICIONA_CAMPO_EDIT";
        var v_campo = vj_campo_edit_valor;
        var v_valor = $("#c_campo_edit_valor").val();
        var v_novo_id = $('#' + v_campo + '_maxid').val();
        v_novo_id = parseInt(v_novo_id) + 1;

        $.ajax({
            type: "POST",
            url: "../mod_lgpd/lib/lib_pdo_config.php",
            data: {
                "v_acao": v_acao,
                "v_novo_id": v_novo_id,
                "v_campo": v_campo,
                "v_valor": v_valor
            },
            success: function(data) {

                var options;
                if (v_campo == "c_conf_categoria") {
                    options = $('#c_conf_categoria').html();
                    options += '<option value="' + v_novo_id + '">' + v_valor + '</option>';
                    $('#c_conf_categoria').html(options_tipo);
                    $('#c_conf_categoria').val(v_novo_id);
                }

                if (v_campo == "c_conf_req_legal") {
                    options = $('#c_conf_req_legal').html();
                    options += '<option value="' + v_novo_id + '">' + v_valor + '</option>';
                    $('#c_conf_req_legal').html(options);
                    $('#c_conf_req_legal').val(v_novo_id);
                }

                if (v_campo == "c_conf_tipo") {
                    options = $('#c_conf_tipo').html();
                    options += '<option value="' + v_novo_id + '">' + v_valor + '</option>';
                    $('#c_conf_tipo').html(options);
                    $('#c_conf_tipo').val(v_novo_id);
                }

                $('#btn_close_modal_itens').click();

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



    function func_deleta_campo_edit(vj_id, vj_campo) {

        var v_acao = "DEL_CAMPO_EDIT";
        var v_campo = vj_campo;
        var v_id = vj_id;

        $.ajax({
            type: "POST",
            url: "../mod_lgpd/lib/lib_pdo_config.php",
            data: {
                "v_acao": v_acao,
                "v_id": v_id,
                "v_campo": v_campo
            },
            success: function(data) {

                if (v_campo == "c_conf_categoria") {
                    $("#c_conf_categoria option[value=" + v_id + "]").remove();
                    $("#c_conf_categoria").val(0);
                }

                if (v_campo == "c_conf_req_legal") {
                    $("#c_conf_req_legal option[value=" + v_id + "]").remove();
                    $("#c_conf_req_legal").val(0);
                }

                if (v_campo == "c_conf_tipo") {
                    $("#c_conf_tipo option[value=" + v_id + "]").remove();
                    $("#c_conf_tipo").val(0);
                }

                $('#li_' + v_id).remove();

                if ($('#' + v_campo + '_maxid').val() == v_id) {
                    $('#' + v_campo + '_maxid').val(v_id--);
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
</script>



</html>