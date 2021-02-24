<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0047") == 0) {
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

    <div class="container">

        <div class="load"> <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
            <span class="sr-only">Loading...</span>

        </div>

        <input type="hidden" id="c_num_dados" value="0">

        <div id="box_form_titulo" class="row" style="margin-top: 40px; background-image: linear-gradient(to left, #6c3a8e , white); margin-bottom: 10px;">
            <div class="row">
                <div class="form-group col-sm-12 col" style="font-weight: bold; font-size: 25px; color: #523B8F;">Gestão dos Meus Dados</div>
            </div>
        </div>

        <div class="row">
            <div id="box_tab1_titulo" class="box" style="height: 100%; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <h4 id="h_titulo_01">LGPD - Dados Bloqueados para Exclusão</h4>
                        <h5 id="h_titulo_02">Nesta área, estão todos os campos que não estão liberados para exclusão.</h5>

                        <div class="row">
                            <div class="form-group col-sm-8 text-left">
                                <button id="btn_lista_disposiveis" class="btn btn-info" onclick="func_carrega_dados();">DADOS DISPONÍVEIS PARA EXCLUSÃO</button>
                                <button id="btn_lista_bloqueados" class="btn btn-secundary" onclick="func_carrega_dados_bloq();">DADOS BLOQUEADOS PARA EXCLUSÃO</button>
                            </div>
                            <div class="form-group col-sm-4 text-right">
                                <button id="btn_solicitar" class="btn btn-danger" onclick="func_solicita();">SOLICITAR EXCLUSÃO DE DADOS</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="box_tab1" class="box" style="height: 400px; background-color: white; border: none; overflow-x: hidden;">
                <table id="tab1" class="table" style="color: black; width: 100%;">
                    <tbody id="tab1b" style="font-weight: normal;">

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>


<script src="../class/DataTables/datatables.min.js"></script>
<script language="JavaScript">
    $(document).ready(function() {
        func_carrega_dados();
    });



    function func_carrega_dados() {

        $('#btn_lista_bloqueados').prop("disabled", true);
        $('#btn_lista_disposiveis').prop("disabled", true);
        $('#btn_solicitar').prop("disabled", true);

        $('#h_titulo_01').html("LGPD - Dados Disponíveis para Exclusão");
        $('#h_titulo_02').html("Para excluir os dados que deseja, basta seleciona-los e clicar no botão SOLICITAR EXCLUSÃO DE DADOS.");

        $('#btn_lista_disposiveis').removeClass("btn-secundary");
        $('#btn_lista_disposiveis').addClass("btn-info");
        $('#btn_lista_bloqueados').removeClass("btn-info");
        $('#btn_lista_bloqueados').addClass("btn-secundary");
        $('#c_num_dados').val("0");

        var v_acao = "LISTAR_DADOS";
        var v_filtro = "1";

        $.ajax({
            type: "POST",
            url: "../mod_lgpd/lib/lib_pdo_sol_user.php",
            data: {
                "v_acao": v_acao,
                "v_filtro": v_filtro
            },
            success: function(data) {

                var options_dados = '';
                $("#tab1b").empty();
                var v_cols = 1;
                var v_index = 0;
                var v_disabled = "";
                var v_click = "";
                var v_cor = "";
                var v_point = "";

                for (v_index = 1; v_index < data.length; v_index++) {

                    if (data[v_index].acao_executada == "S" || data[v_index].acao_executada == "N") {
                        var v_disabled = "disabled";
                        var v_click = "";
                        var v_cursor = "";
                        var v_cor = "background-color: rgb(245, 245, 245);";
                    } else {
                        var v_disabled = "";
                        var v_cursor = "cursor: pointer;";
                        var v_cor = "background-color: rgb(255, 255, 255);";
                        var v_click = "if($(this).css(\'background-color\') == \'rgb(255, 255, 255)\'){$(this).css(\'background-color\',\'rgb(240, 255, 240)\');}else{$(this).css(\'background-color\',\'rgb(255, 255, 255)\');}";
                    }

                    if (v_cols == 1) {
                        options_dados += '<tr style="border-style: none;">';
                    }

                    options_dados += '    <td style="border-style: none; width: 33%;">\n';
                    options_dados += '        <div id="c_dado_' + v_index + '" onclick="' + v_click + '" style="' + v_cursor + ' padding: 10px; margin: 10px; min-height: 100px; width: 98%; border-style: solid; border-width: 0.5px; border-color: #B5B5B5; border-radius: 10px; ' + v_cor + '">\n';
                    options_dados += '            <input ' + v_disabled + ' type="hidden" id="c_field_id_' + v_index + '" value="' + data[v_index].id + '">\n';
                    options_dados += '            <div ' + v_disabled + ' style="font-size: 10px;"><strong>CAMPO:</strong> ' + data[v_index].campo + '</div>\n';
                    options_dados += '            <div ' + v_disabled + ' style="margin-top: 5px; font-size: 10px;"><strong>REQUISITO LEGAL:</strong> ' + data[v_index].req_legal + '</div>\n';
                    options_dados += '            <div ' + v_disabled + ' style="margin-top: 5px;font-size: 10px;"><strong>FINALIDADE:</strong> ' + data[v_index].finalidade + '</div>\n';

                    if (data[v_index].acao_executada == "N") {
                        options_dados += '            <div id="c_status_ev_' + data[v_index].id_sol + '" style="margin-top: 5px; font-size: 10px; color: red;"><strong>STATUS DO PEDIDO:</strong> NA FILA</div>\n';
                        options_dados += '            <div style="margin-top: 5px; "><button id="btn_cancelar_' + data[v_index].id_sol + '" class="btn btn-danger btn-xs" onclick="func_cancelar(' + data[v_index].id_sol + ');">Cancelar Exclusão</button></div>\n';
                    } else if (data[v_index].acao_executada == "S") {
                        options_dados += '            <div ' + v_disabled + ' style="margin-top: 5px; font-size: 10px; color: blue;""><strong>STATUS DO PEDIDO:</strong> FINALIZADO</div>\n';
                    }

                    options_dados += '        </div>\n';
                    options_dados += '    </td>';

                    if (v_cols == 3) {
                        options_dados += '</tr>';
                        v_cols = 1;
                    } else {
                        v_cols++;
                    }

                }

                $('#tab1b').html(options_dados);
                $('#btn_lista_bloqueados').prop("disabled", false);
                $('#btn_lista_disposiveis').prop("disabled", false);
                $('#btn_solicitar').prop("disabled", false);
                $('#c_num_dados').val(v_index - 1);

                $('.load').hide();

            },
            error: function(request, status, erro) {
                Swal.fire({
                    icon: "error",
                    title: "FALHA!",
                    text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                })
                $('#btn_lista_bloqueados').prop("disabled", false);
                $('#btn_lista_disposiveis').prop("disabled", false);
            }
        });



    }



    function func_carrega_dados_bloq() {

        $('#btn_lista_bloqueados').prop("disabled", true);
        $('#btn_lista_disposiveis').prop("disabled", true);
        $('#btn_solicitar').prop("disabled", true);

        $('#h_titulo_01').html("LGPD - Dados Bloqueados para Exclusão");
        $('#h_titulo_02').html("Todos os dados bloqueados possuem justificativas para o bloqueio.");

        $('#btn_lista_bloqueados').removeClass("btn-secundary");
        $('#btn_lista_bloqueados').addClass("btn-info");
        $('#btn_lista_disposiveis').removeClass("btn-info");
        $('#btn_lista_disposiveis').addClass("btn-secundary");
        $('#c_num_dados').val("0");

        var v_acao = "LISTAR_DADOS";
        var v_filtro = "2";

        $.ajax({
            type: "POST",
            url: "../mod_lgpd/lib/lib_pdo_sol_user.php",
            data: {
                "v_acao": v_acao,
                "v_filtro": v_filtro
            },
            success: function(data) {

                var options_dados = '';
                $("#tab1b").empty();
                var v_cols = 1;

                for (v_index = 1; v_index < data.length; v_index++) {

                    if (v_cols == 1) {
                        options_dados += '<tr style="border-style: none;">';
                    }

                    options_dados += '    <td style="border-style: none; width: 33%;">\n';
                    options_dados += '        <div style="cursor: pointer; padding: 10px; margin: 10px; min-height: 100px; width: 98%; border-style: solid; border-width: 0.5px; border-color: #B5B5B5; border-radius: 10px; background-color: rgb(245, 245, 245);">\n';
                    options_dados += '            <div style="font-size: 10px;"><strong>CAMPO:</strong> ' + data[v_index].campo + '</div>\n';
                    options_dados += '            <div style="margin-top: 5px; font-size: 10px;"><strong>REQUISITO LEGAL:</strong> ' + data[v_index].req_legal + '</div>\n';
                    options_dados += '            <div style="margin-top: 5px;font-size: 10px;"><strong>FINALIDADE:</strong> ' + data[v_index].finalidade + '</div>\n';
                    options_dados += '        </div>\n';
                    options_dados += '    </td>';

                    if (v_cols == 3) {
                        options_dados += '</tr>';
                        v_cols = 1;
                    } else {
                        v_cols++;
                    }
                }

                $('#tab1b').html(options_dados);
                $('#btn_lista_bloqueados').prop("disabled", false);
                $('#btn_lista_disposiveis').prop("disabled", false);
            },
            error: function(request, status, erro) {
                Swal.fire({
                    icon: "error",
                    title: "FALHA!",
                    text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                })
                $('#btn_lista_bloqueados').prop("disabled", false);
                $('#btn_lista_disposiveis').prop("disabled", false);
            }
        });

    }



    function func_solicita() {
        Swal.fire({
            title: 'Você tem certeza que deseja excluir estes dados de forma permanente ?',
            text: "Informe o motivo da sua solicitação:",
            input: 'textarea',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, Excluir Dados!'
        }).then((result) => {
            if (result.value) {

                $('#btn_lista_bloqueados').prop("disabled", true);
                $('#btn_lista_disposiveis').prop("disabled", true);
                $('#btn_solicitar').prop("disabled", true);

                var v_acao = "SOL_EXCLUSAO";
                var v_num_dados = $('#c_num_dados').val();
                var v_just = result.value;
                if (v_just.length == 0) {
                    v_just = "-";
                }

                var v_lista_ids = "";
                for (v_index = 1; v_index <= v_num_dados; v_index++) {
                    if ($("#c_dado_" + v_index).css("background-color") == "rgb(240, 255, 240)") {
                        v_lista_ids += $("#c_field_id_" + v_index).val() + "|";
                    }
                }
                v_lista_ids = v_lista_ids.slice(0, -1);

                if (v_lista_ids.length > 0) {

                    $.ajax({
                        type: "POST",
                        url: "../mod_lgpd/lib/lib_pdo_sol_user.php",
                        data: {
                            "v_acao": v_acao,
                            "v_lista_ids": v_lista_ids,
                            "v_just": v_just
                        },
                        success: function(data) {

                            var v_json = JSON.parse(data);
                            Swal.fire({
                                icon: v_json.msg_ev,
                                title: v_json.msg_titulo,
                                text: v_json.msg
                            })

                            func_carrega_dados();

                        },
                        error: function(request, status, erro) {
                            Swal.fire({
                                icon: "error",
                                title: "FALHA!",
                                text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                            })

                            $('#btn_lista_bloqueados').prop("disabled", false);
                            $('#btn_lista_disposiveis').prop("disabled", false);
                            $('#btn_solicitar').prop("disabled", false);

                        }
                    });

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "FALHA!",
                        text: "Você deve selecionar pelo menos um campo para solicitar a exclusão de dados."
                    })

                    $('#btn_lista_bloqueados').prop("disabled", false);
                    $('#btn_lista_disposiveis').prop("disabled", false);
                    $('#btn_solicitar').prop("disabled", false);
                }

            } else {
                Swal.fire({
                    icon: "error",
                    title: "CANCELADA!",
                    text: "Exclusão cancelada com sucesso."
                })
            }

        })
    }



    function func_cancelar(vj_id_sol) {

        var v_id_sol = vj_id_sol;

        Swal.fire({
            title: 'Você tem certeza que deseja desistir da exclusão deste dado ?',
            text: "Esta ação será imediata.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, Desistir!'
        }).then((result) => {
            if (result.value) {

                $('#btn_lista_bloqueados').prop("disabled", true);
                $('#btn_lista_disposiveis').prop("disabled", true);
                $('#btn_solicitar').prop("disabled", true);

                var v_acao = "CANCELAR_SOL";

                $.ajax({
                    type: "POST",
                    url: "../mod_lgpd/lib/lib_pdo_sol_user.php",
                    data: {
                        "v_acao": v_acao,
                        "v_id_sol": v_id_sol
                    },
                    success: function(data) {

                        var v_json = JSON.parse(data);
                        Swal.fire({
                            icon: v_json.msg_ev,
                            title: v_json.msg_titulo,
                            text: v_json.msg
                        })

                        func_carrega_dados();

                    },
                    error: function(request, status, erro) {
                        Swal.fire({
                            icon: "error",
                            title: "FALHA!",
                            text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                        })

                        $('#btn_lista_bloqueados').prop("disabled", false);
                        $('#btn_lista_disposiveis').prop("disabled", false);
                        $('#btn_solicitar').prop("disabled", false);

                    }
                });

            } else {
                Swal.fire({
                    icon: "error",
                    title: "CANCELADA!",
                    text: "Exclusão cancelada com sucesso."
                })
            }

        })

    }
</script>



</html>