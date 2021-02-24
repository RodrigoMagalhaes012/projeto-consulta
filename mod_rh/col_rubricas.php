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

        .load {
            width: 100px;
            height: 100px;
            position: absolute;
            top: 40%;
            left: 50%;
            color: #523B8F;
        }

        .container-border {
            border: 1px solid #ccc;
            border-radius: 15px;
            background-color: white;
        }
    </style>
    <title>Document</title>
</head>

<body>
    <div class="container-fluid container-border">
        <div class="load"> <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
            <span class="sr-only">Loading...</span>
        </div>
        <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-6">
                    <h3>Lista de Rubricas</h3>
                </div>

            </div>
        </div>
        <!-- <div id="box_tab1" class="row" style="height: auto; border-style: solid; border-color: #ccc; background-color: white; border: none; overflow-x: hidden;"> -->
        <div id="box_tab1" class="row" style="border-color: grey; padding: 10px; border-width: 1px; border-style: solid; border-color: #ccc; background-color: white; overflow-x: hidden;">
            <input id="c_acao" type="hidden" value="">
            <div class="box-body">
                <input type="hidden" id="vf_tab_sql_limit_in" value="0">
                <input type="hidden" id="vf_tab_btn_pag_select" value="1">
                <table id="tab1" class="table" style="color: black; width: 100%;">
                    <thead style="font-weight: bold;">
                        <tr>
                            <th>Rubrica</th>
                            <th>Descrição</th>
                            <th>Tipo</th>
                            <th>Lançamento em horas?</th> <!-- Tipo de Lançamento -->
                            <th>Permite lançamento</th> <!-- Característica -->
                        </tr>
                    </thead>
                    <tbody id="tab1b" style="font-weight: normal;">

                    </tbody>
                </table>
            </div>
        </div>

        <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">
            <div class="box-footer">
                <div class="row">
                    <div class="form-group col-sm-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                        <button id="btn_salvar_reg" class="btn btn-success" style="border-radius: 10px; width: 100px;" onclick="func_salva_rubricas()">Salvar</button>
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


    function func_carrega_tab() {

        //capturando valor dos campos e trazendo pra variaveis
        $("#c_acao").val("");
        var v_acao = "LISTAR_RUBRICAS";

        $("#c_rubrica").prop("disabled", true);
        $("#c_tipo").prop("disabled", true);
        $("#c_descricao").prop("disabled", true);
        $("#c_tipo_lancamento").prop("disabled", true);
        $("#c_caracteristica").prop("disabled", true);
        $("#c_id_tabela").prop("disabled", true);

        $("#c_rubrica").val("");
        $("#c_tipo").val("");
        $("#c_descricao").val("");
        $("#c_tipo_lancamento").val("");
        $("#c_caracteristica").val("");
        $("#c_id_tabela").val("");

        $.ajax({
            type: "POST",
            url: "lib/lib_col_rubricas.php",
            data: {
                "v_acao": v_acao
            },
            success: function(data) {
                $('#tab1').DataTable().destroy();
                var options = '';
                $("#tab1b").empty();

                for (v_index = 1; v_index < data.length; v_index++) {

                    // options += '<tr  style="cursor: pointer;" onclick="func_select(\'' + data[v_index].Rubrica + '\');"><td>' + data[v_index].Rubrica + '</td><td>' + data[v_index].Tipo + '</td><td>' + data[v_index].Descricao + '</td><td><input type="checkbox" id="check_tipo_lanc_' + data[v_index].Rubrica + '" ' + data[v_index].Tipo_lancamento + '></td><td><input type="checkbox"id="check_carac_' + data[v_index].Rubrica + '"' + data[v_index].Caracteristica + '></td></tr>';
                    options += '<tr  style="cursor: pointer;" onclick="func_select(\'' + data[v_index].Rubrica + '\');"><td>' + data[v_index].Rubrica + '</td><td>' + data[v_index].Descricao + '</td><td>' + data[v_index].Tipo + '</td><td><input type="checkbox" id="check_tipo_lanc_' + data[v_index].Rubrica + '" ' + data[v_index].Tipo_lancamento + '></td><td><input type="checkbox"id="check_carac_' + data[v_index].Rubrica + '"' + data[v_index].Caracteristica + '></td></tr>';


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
                    "scrollX": "5vh",
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

    function func_salva_rubricas() {
        var lista = document.getElementById('tab1b').rows;
        var vet_dados = [];
        for (var i = 0; i < lista.length; i++) {
            vet_dados.push({
                v_rubrica: lista[i].cells[0].innerHTML,
                v_tipo_lancamento: $(`#check_tipo_lanc_` + lista[i].cells[0].innerHTML).prop('checked') ? 1 : 0,
                v_caracteristica: $(`#check_carac_` + lista[i].cells[0].innerHTML).prop('checked') ? 1 : 0

            });
        }
        $.ajax({
            type: "POST",
            url: "lib/lib_col_rubricas.php",
            data: {
                "v_acao": "EV_SALVAR",
                "v_vet_dados": vet_dados

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

    function func_select(v_rubrica) {

        $.ajax({
            type: "POST",
            url: "lib/lib_col_rubricas.php",
            data: {
                "v_acao": "EV_SELECT",
                "v_rubrica": v_rubrica
            },
            success: function(data) {

                $("#c_acao").val("EV_SELECT");
                $("#c_tipo_lancamento").prop("disabled", false);
                $("#c_caracteristica").prop("disabled", false);

                $("#c_rubrica").val(data[0].Rubrica);
                $("#c_tipo").val(data[0].Tipo);
                $("#c_descricao").val(data[0].Descricao);
                $("#c_tipo_lancamento").val(data[0].Tipo_lancamento);
                $("#c_caracteristica").val(data[0].Caracteristica);
                $("#c_id_tabela").val(data[0].Id_tabela);
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