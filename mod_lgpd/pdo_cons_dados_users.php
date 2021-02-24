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
            color: #2E8B57;
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
                <div class="form-group col-sm-12 col" style="font-weight: bold; font-size: 25px; color: #523B8F;">CONSULTA DE DADOS DOS USUÁRIOS</div>
            </div>
        </div>

        <div id="tabela_inicial">
            <div id="box_tab1" class="row" style="height: auto; background-color: white; border: none; overflow-x: hidden;">
                <input id="c_acao" type="hidden" value="">
                <div class="box-body">
                    <table id="tab_usuarios" class="table" style="color: black; width: 100%;">
                        <thead style="font-weight: bold;">
                            <tr>
                                <th>Id</th>
                                <th>Usuário</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="corpo_tab_usuarios" style="font-weight: normal;">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="selecao_grupos" class="row" style="display: none;">
            <div id="box_tab1_titulo" class="box" style="height: 100%; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <h4 id="h_titulo_01">LGPD - Dados Bloqueados para Exclusão</h4>
                        <h5 id="h_titulo_02">Nesta área, estão todos os dados disponíveis e bloqueados para que o usuário possa solicitar a exclusão.</h5>
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
        func_carrega_tab();
    });


    function func_carrega_tab() {

        $("#c_acao").val("");
        var v_acao = "LISTAR_USUARIOS";

        $("#c_nome").prop("disabled", true);
        $("#c_descricao").prop("disabled", true);

        $.ajax({
            type: "POST",
            url: "../mod_lgpd/lib/lib_pdo_cons_dados_users.php",
            data: {
                "v_acao": v_acao
            },
            success: function(data) {
                $('#tab_usuarios').DataTable().destroy();
                let options = ''
                $("#corpo_tab_usuarios").empty()
                data.forEach(element => {
                    options += `
                <tr>
                    <td>${element.Id}</td>
                    <td>${element.Nome}</td>
                    <td>
                        <div class="btn-group" style="border: 0px; margin: 0px;">
                        <button onclick="func_consultar_dados(${element.Id}, '${element.Nome}');"
                        class="btn is-icon btn-outline-primary" title="Gerenciar Acessos">
                                <span class="button-text">
                                    <i class="fa fa-object-ungroup fa-1x"></i>
                                </span>
                            </button>
                        </div>
                    </td>
                </tr>
            `
                });

                $("#corpo_tab_usuarios").html(options)

                $("#tab_usuarios").DataTable({
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


    function func_consultar_dados(vj_id_user, vj_nome) {

        var v_id_user = vj_id_user;
        $('#h_titulo_01').html("CONSULTANDO DADOS DE " + vj_nome);
        $('#c_num_dados').val("0");
        var v_acao = "LISTAR_DADOS";

        $.ajax({
            type: "POST",
            url: "../mod_lgpd/lib/lib_pdo_cons_dados_users.php",
            data: {
                "v_acao": v_acao,
                "v_id_user": v_id_user
            },
            success: function(data) {

                $("#tab1b").empty();
                var options_dados = '';
                var v_cols = 1;
                var v_campo = "";
                var v_div = "";
                var v_cor = "";

                v_campo = data[1].campo;
                for (v_index = 1; v_index < data.length; v_index++) {

                    if (v_cols == 1) {
                        options_dados += '<tr>';
                    }

                    if (data[v_index].liberado == "S") {
                        v_cor = 'rgb(240, 255, 240)';
                    } else {
                        v_cor = 'rgb(245, 245, 245)';
                    }
                    


                    if (v_index == 1) {
                        v_div = '    <td style="border-style: none; width: 33%;">\n';
                        v_div += '        <div style="cursor: pointer; padding: 10px; margin: 10px; min-height: 100px; width: 98%; border-style: solid; border-width: 0.5px; border-color: #B5B5B5; border-radius: 10px; background-color: ' + v_cor + ';">\n';
                        v_div += '            <div style="font-size: 12px;"><strong>' + data[v_index].campo + ':</strong></div>\n';
                    }

                    if (v_campo != data[v_index].campo) {
                        v_campo = data[v_index].campo;
                        v_div += '            <div style="margin-top: 5px; font-size: 10px;"><strong>REQUISITO LEGAL:</strong> ' + data[v_index].req_legal + '</div>\n';
                        v_div += '            <div style="margin-top: 5px;font-size: 10px;"><strong>FINALIDADE:</strong> ' + data[v_index].finalidade + '</div>\n';
                        v_div += '        </div>\n';
                        v_div += '    </td>';
                        options_dados += v_div;

                        if (v_cols == 3) {
                            options_dados += '</tr>';
                            v_cols = 1;
                        } else {
                            v_cols++;
                        }

                        v_div = '    <td style="border-style: none; width: 33%;">\n';
                        v_div += '        <div style="cursor: pointer; padding: 10px; margin: 10px; min-height: 100px; width: 98%; border-style: solid; border-width: 0.5px; border-color: #B5B5B5; border-radius: 10px; background-color: ' + v_cor + ';">\n';
                        v_div += '            <div style="font-size: 12px;"><strong>' + data[v_index].campo + ':</strong></div>\n';
                        v_div += '            <div style="font-size: 10px;"><strong>' + data[v_index].campo_label + ':</strong> <span id="' + data[v_index].campo_field + '">' + data[v_index].valor + '</span></div>\n';
                    } else {

                        v_div += '            <div style="font-size: 10px;"><strong>' + data[v_index].campo_label + ':</strong> <span id="' + data[v_index].campo_field + '">' + data[v_index].valor + '</span></div>\n';

                    }



                    // CARTURANDO DADOS DO ENDEREÇO USANDO O CEP
                    if (data[v_index].campo_label == "8) CEP") {
                        $.getJSON("https://viacep.com.br/ws/" + data[v_index].valor + "/json/?callback=?", function(dados) {
                            $("#bairro").html(dados.bairro.toUpperCase());
                            $("#cidade").html(dados.localidade.toUpperCase());
                        });
                    }

                    if (data[v_index].campo_label == "1) ESCOLARIDADE") {
                        switch (parseInt(data[v_index].valor)) {
                            case 1:
                                $("#id_escolaridade").html("ANALFABETO");
                                break;
                            case 2:
                                $("#id_escolaridade").html("4ª SÉRIE INCOMPLETA");
                                break;
                            case 3:
                                $("#id_escolaridade").html("4ª SÉRIE COMPLETA");
                                break;
                            case 4:
                                $("#id_escolaridade").html("5ª A 8ª SÉRIE INCOMPLETA");
                                break;
                            case 5:
                                $("#id_escolaridade").html("1º GRAU COMPLETO");
                                break;
                            case 6:
                                $("#id_escolaridade").html("2º GRAU INCOMPLETO");
                                break;
                            case 7:
                                $("#id_escolaridade").html("2º GRAU COMPLETO");
                                break;
                            case 8:
                                $("#id_escolaridade").html("SUPERIOR INCOMPLETO");
                                break;
                            case 9:
                                $("#id_escolaridade").html("SUPERIOR COMPLETO");
                                break;
                            case 10:
                                $("#id_escolaridade").html("PÓS-GRADUAÇÃO");
                                break;
                            case 11:
                                $("#id_escolaridade").html("MESTRADO COMPLETO");
                                break;
                            case 12:
                                $("#id_escolaridade").html("DOUTORADO COMPLETO");
                                break;
                            case 13:
                                $("#id_escolaridade").html("PH.D.");
                                break;
                            case 14:
                                $("#id_escolaridade").html("2º GRAU TEC. INCOMPLETO");
                                break;
                            case 15:
                                $("#id_escolaridade").html("2º GRAU TEC. COMPLETO");
                                break;
                            case 16:
                                $("#id_escolaridade").html("MESTRADO INCOMPLETO");
                                break;
                            case 17:
                                $("#id_escolaridade").html("DOUTORADO INCOMPLETO");
                                break;
                            case 18:
                                $("#id_escolaridade").html("PÓS-GRADUAÇÃO INCOMPLETO");
                                break;
                            default:
                                $("#id_escolaridade").html("-");
                        }
                    }


                    if (data[v_index].campo_label == "1) SEXO") {
                        switch (parseInt(data[v_index].valor)) {
                            case 1:
                                $("#id_sexo").html("MASCULINO");
                                break;
                            case 2:
                                $("#id_sexo").html("FEMININO");
                                break;
                            default:
                                $("#id_sexo").html("-");
                        }
                    }
                }

                $("#id_pais_nascimento").html("BRASIL");
                $("#pais").html("BRASIL");
                $('#tab1b').html(options_dados);

                $('.load').hide();
                $("#tabela_inicial").hide()
                $("#selecao_grupos").show()

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
            url: "../mod_lgpd/lib/lib_pdo_cons_dados_users.php",
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
                        url: "../mod_lgpd/lib/lib_pdo_cons_dados_users.php",
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
                    url: "../mod_lgpd/lib/lib_pdo_cons_dados_users.php",
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