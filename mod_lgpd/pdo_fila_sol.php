<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0046") == 0) {
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
        <input type="hidden" id="c_num_dados" value="0">

        <div id="box_form_titulo" class="row" style="margin-top: 40px; background-image: linear-gradient(to left, #6c3a8e , white); margin-bottom: 10px;">
            <div class="row">
                <div class="form-group col-sm-12 col" style="font-weight: bold; font-size: 25px; color: #523B8F;">DPO - Fila de Pedidos</div>
            </div>
        </div>

        <div class="row">
            <div id="box_tab1_titulo" class="box" style="height: 100%; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <h4 id="h_titulo_01">LGPD - Fila de Solicitações para Exclusão de Dados</h4>
                        <h5 id="h_titulo_02">Nesta área, estão todas as solicitações pendentes.</h5>

                        <div class="row">
                            <div class="form-group col-sm-12 text-left">
                                <button id="btn_lista_pendentes" class="btn btn-info" onclick="func_carrega_pendentes();">SOLICITAÇÕES PENDENTES NA FILA</button>
                                <button id="btn_lista_finalizadas" class="btn btn-secundary" onclick="func_carrega_finalizadas();">SOLICITAÇÕES FINALIZADAS</button>
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
        func_carrega_pendentes();
    });



    function func_carrega_pendentes() {

        $('#btn_lista_finalizadas').prop("disabled", true);
        $('#btn_lista_pendentes').prop("disabled", true);

        $('#h_titulo_01').html("LGPD - Fila de Solicitações para Exclusão de Dados");
        $('#h_titulo_02').html("Relação de solicitações para exclusão de dados pendentes.");

        $('#btn_lista_pendentes').removeClass("btn-secundary");
        $('#btn_lista_pendentes').addClass("btn-info");
        $('#btn_lista_finalizadas').removeClass("btn-info");
        $('#btn_lista_finalizadas').addClass("btn-secundary");
        $('#c_num_dados').val("0");

        var v_acao = "LISTAR_DADOS";
        var v_filtro = "N";

        $.ajax({
            type: "POST",
            url: "../mod_lgpd/lib/lib_pdo_fila_sol.php",
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

                    if (data[v_index].id_user > 0) {
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
                    options_dados += '        <div id="div_dpo_' + data[v_index].id_user + '" data-toggle="tooltip" data-placement="top" title="' + data[v_index].campos + '" ' + v_disabled + ' id="c_dado_' + v_index + '" onclick="' + v_click + '" style="' + v_cursor + ' padding: 10px; margin: 10px; min-height: 100px; width: 98%; border-style: solid; border-width: 0.5px; border-color: #B5B5B5; border-radius: 10px; ' + v_cor + '">\n';
                    options_dados += '            <div style="font-size: 10px;"><strong>DATA:</strong> ' + data[v_index].data_hora + '</div>\n';
                    options_dados += '            <div style="margin-top: 5px; font-size: 10px;"><strong>NOME:</strong> ' + data[v_index].nome + '</div>\n';
                    options_dados += '            <div style="margin-top: 5px;font-size: 10px;"><strong>CAMPOS:</strong> ' + data[v_index].tt_campos + '</div>\n';
                    options_dados += '            <div style="margin-top: 5px; font-size: 10px; color: red;"><strong>STATUS DO PEDIDO:</strong> NA FILA</div>\n';
                    options_dados += '            <div style="margin-top: 5px; "><button id="btn_executar_' + data[v_index].id_user + '" class="btn btn-danger btn-xs" onclick="func_excluir_dados(' + data[v_index].id_user + ');">Executar a Exclusão dos Campos</button></div>\n';
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
                $('#btn_lista_finalizadas').prop("disabled", false);
                $('#btn_lista_pendentes').prop("disabled", false);
                $('#c_num_dados').val(v_index - 1);
            },
            error: function(request, status, erro) {
                Swal.fire({
                    icon: "error",
                    title: "FALHA!",
                    text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                })
                $('#btn_lista_finalizadas').prop("disabled", false);
                $('#btn_lista_pendentes').prop("disabled", false);
            }
        });

    }



    function func_carrega_finalizadas() {

        $('#btn_lista_finalizadas').prop("disabled", true);
        $('#btn_lista_pendentes').prop("disabled", true);

        $('#h_titulo_01').html("LGPD - Solicitações para Exclusão de Dados Finalizadas");
        $('#h_titulo_02').html("Relação de solicitações para exclusão de dados Finalizadas.");

        $('#btn_lista_finalizadas').removeClass("btn-secundary");
        $('#btn_lista_finalizadas').addClass("btn-info");
        $('#btn_lista_pendentes').removeClass("btn-info");
        $('#btn_lista_pendentes').addClass("btn-secundary");
        $('#c_num_dados').val("0");

        var v_acao = "LISTAR_DADOS";
        var v_filtro = "S";

        $.ajax({
            type: "POST",
            url: "../mod_lgpd/lib/lib_pdo_fila_sol.php",
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

                    if (data[v_index].id_user > 0) {
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
                    options_dados += '        <div ' + v_disabled + ' id="c_dado_' + v_index + '" onclick="' + v_click + '" style="' + v_cursor + ' padding: 10px; margin: 10px; min-height: 100px; width: 98%; border-style: solid; border-width: 0.5px; border-color: #B5B5B5; border-radius: 10px; ' + v_cor + '">\n';
                    options_dados += '            <div style="font-size: 10px;"><strong>DATA:</strong> ' + data[v_index].data_hora + '</div>\n';
                    options_dados += '            <div style="margin-top: 5px; font-size: 10px;"><strong>NOME:</strong> ' + data[v_index].nome + '</div>\n';
                    options_dados += '            <div style="margin-top: 5px;font-size: 10px;"><strong>CAMPOS:</strong> ' + data[v_index].tt_campos + '</div>\n';
                    options_dados += '            <div style="margin-top: 5px; font-size: 10px; color: blue;""><strong>STATUS DO PEDIDO:</strong> FINALIZADO</div>\n';
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
                $('#btn_lista_finalizadas').prop("disabled", false);
                $('#btn_lista_pendentes').prop("disabled", false);
                $('#c_num_dados').val(v_index - 1);
            },
            error: function(request, status, erro) {
                Swal.fire({
                    icon: "error",
                    title: "FALHA!",
                    text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                })
                $('#btn_lista_finalizadas').prop("disabled", false);
                $('#btn_lista_pendentes').prop("disabled", false);
            }
        });

    }



    function func_solicita() {
        Swal.fire({
            title: 'Você tem certeza que deseja excluir estes dados de forma permanente ?',
            text: "Os dados não poderão ser restaurados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, Excluir Dados!'
        }).then((result) => {
            if (result.value) {

                $('#btn_lista_finalizadas').prop("disabled", true);
                $('#btn_lista_pendentes').prop("disabled", true);

                var v_acao = "SOL_EXCLUSAO";
                var v_num_dados = $('#c_num_dados').val();

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
                        url: "../mod_lgpd/lib/lib_pdo_fila_sol.php",
                        data: {
                            "v_acao": v_acao,
                            "v_lista_ids": v_lista_ids
                        },
                        success: function(data) {

                            var v_json = JSON.parse(data);
                            Swal.fire({
                                icon: v_json.msg_ev,
                                title: v_json.msg_titulo,
                                text: v_json.msg
                            })

                            func_carrega_pendentes();

                        },
                        error: function(request, status, erro) {
                            Swal.fire({
                                icon: "error",
                                title: "FALHA!",
                                text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                            })

                            $('#btn_lista_finalizadas').prop("disabled", false);
                            $('#btn_lista_pendentes').prop("disabled", false);

                        }
                    });

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "FALHA!",
                        text: "Você deve selecionar pelo menos um campo para solicitar a exclusão de dados."
                    })

                    $('#btn_lista_finalizadas').prop("disabled", false);
                    $('#btn_lista_pendentes').prop("disabled", false);
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



    function func_excluir_dados(vj_id_user) {
        Swal.fire({
            title: 'Você tem certeza que deseja executar a anonimização dos dados solicitados ?',
            text: "Os dados não poderão ser restaurados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, Anonimizar Dados!'
        }).then((result) => {
            if (result.value) {

                var v_acao = "EXECUTAR_EXCLUSOES";
                var v_id_user = vj_id_user;

                $.ajax({
                    type: "POST",
                    url: "../mod_lgpd/lib/lib_pdo_fila_sol.php",
                    data: {
                        "v_acao": v_acao,
                        "v_id_user": v_id_user
                    },
                    success: function(data) {

                        var v_json = JSON.parse(data);
                        Swal.fire({
                            icon: v_json.msg_ev,
                            title: v_json.msg_titulo,
                            text: v_json.msg
                        })

                        func_carrega_pendentes();

                    },
                    error: function(request, status, erro) {
                        Swal.fire({
                            icon: "error",
                            title: "FALHA!",
                            text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                        })

                        $('#btn_lista_finalizadas').prop("disabled", false);
                        $('#btn_lista_pendentes').prop("disabled", false);

                    }
                });

            } else {
                Swal.fire({
                    icon: "error",
                    title: "CANCELADA!",
                    text: "Ação cancelada com sucesso."
                })
            }

        })
    }
</script>



</html>