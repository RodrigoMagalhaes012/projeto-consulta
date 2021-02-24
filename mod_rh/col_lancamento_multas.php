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

    <!-- <div class="load"> <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
        <span class="sr-only">Loading...</span>
    </div> -->

    <div class="container">
        <input type="hidden" id="c_index_var" value="0">
        <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-12">
                    <h3>Lançamento de Multas</h3>
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
                            <th style="text-align: center;">Matricula</th>
                            <th style="text-align: center;">Colaborador</th>
                            <th style="text-align: center;">Cargo</th>
                            <th style="text-align: center;">Departamento</th>
                            <th style="text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tab1b" style="font-weight: normal;">
                    </tbody>
                </table>
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
                            <div class="form-group col-sm-2">
                                <label for="c_matricula">Matricula</label>
                                <input disabled id="c_matricula" class="form-control class_inputs">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="c_nome">Colaborador</label>
                                <input disabled id="c_nome" type="text" class="form-control class_inputs">
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="c_competencia">Competência</label>
                                <input type="date" id="c_competencia" class="form-control class_inputs">
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="c_valor">Valor</label>
                                <input id="c_valor" class="form-control class_inputs">
                            </div>
                            <div style="display: none;">
                                <label style="visibility: hidden;" for="c_id">Id</label>
                                <input style="visibility: hidden;" disabled id="c_id" type="text" class="form-control class_inputs">
                            </div>
                            <div style="display: none;">
                                <label style="visibility: hidden;" for="c_id_usuario">Id</label>
                                <input style="visibility: hidden;" disabled id="c_id_usuario" type="text" class="form-control class_inputs">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="c_justificativa">Justificativa:</label>
                                <textarea class="form-control" rows="3" id="c_justificativa"></textarea>
                            </div> 
                        </div>
                        <div class="row" style="margin-top: 10px; padding   : 0px;">
                            <div class="col-sm-12" style="margin-top: 5px; margin-bottom: 10px; text-align: right;">
                                <button class="btn btn-success" style="width: 15%; height: 15%;border-radius: 10px;" onclick="func_salvar();">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


<script type="text/javascript" src="../class/DataTables/datatables.min.js"></script>
<script language="JavaScript">
    $(document).ready(function() {
        func_carrega_tab();
    });

    function func_carrega_tab(){
        $.ajax({
            type: "POST",
            url: "lib/lib_col_lancamento_multas.php",
            data: {
                "v_acao": "LISTA_COLABORADORES",
                "v_competencia_cal": $("#c_select_competencia").val()
            },
            success: function(data) {
                $('#tab1').DataTable().destroy();
                $("#tab1b").empty()
                let options = ''
                data.forEach(element => {
                    options += `<tr>
                        <td style="cursor: pointer">${element.Matricula}</td>
                        <td style="cursor: pointer">${element.Nome}</td>
                        <td style="cursor: pointer">${element.Cargo}</td>
                        <td style="cursor: pointer">${element.Departamento}</td>
                        <td style="text-align: center;">
                            <div class="btn-group" style="border: 0px; margin: 0px;">
                                <button data-toggle="modal" data-target="#flipFlop"
                                onclick="func_select('${element.Matricula}','${element.Nome}', ${element.Id_usuario}, ${element.Id});"
                                class="btn is-icon btn-outline-primary" title="Lançar multa">
                                        <span class="button-text">
                                            <i class="fa fa-money fa-1x"></i>
                                        </span>
                                    </button>
                                </div>
                        </td>
                    </tr>`
                });
                $("#tab1b").html(options)

                $("#tab1").DataTable({
                    "language": {
                        "url": "../class/DataTables/portugues.json",
                    },
                    "columnDefs": [{
                        "width": "20%",
                        "targets": 2,
                    },
                    {
                        "width": "5%",
                        "targets": 0,
                    },
                    {
                        "width": "5%",
                        "targets": 3,
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

    function func_select(matricula, nome, id_usuario, id){
        $("#c_valor").val("")
        $("#c_justificativa").val("")
        $("#c_matricula").val(matricula)
        $("#c_nome").val(nome)
        $("#c_id").val(id)
        $("#c_id_usuario").val(id_usuario)
        $("#c_competencia").val($("#c_select_competencia").val())
    }

    function func_salvar(){
        if($("#c_valor").val() != ''){
            $.ajax({
                type: "POST",
                url: "lib/lib_col_lancamento_multas.php",
                data: {
                    "v_acao": "SALVA_MULTA",
                    "v_colaborador": $("#c_id").val(),
                    "v_valor": $("#c_valor").val(),
                    "v_justificativa": $("#c_justificativa").val(),
                    "v_id_usuario": $("#c_id_usuario").val(),
                    "v_competencia": $("#c_competencia").val(),
                    "v_matricula": $("#c_matricula").val()
                },
                success: function(data) {
                    let v_json = JSON.parse(data)
                    Swal.fire({
                        icon: v_json.msg_ev,
                        title: v_json.msg_titulo,
                        text: v_json.msg
                    })

                    if(v_json.msg_ev){
                        $("#c_valor").val("")
                        $("#c_justificativa").val("")
                        $("#flipFlop").modal('hide')
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
        }else{
            Swal.fire({
                icon: "warning",
                title: "AVISO!",
                text: "Preencher valor antes de salvar"
            })
        }
    }

</script>
</html>