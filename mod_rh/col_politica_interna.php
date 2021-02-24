<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0030") == 0) {
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

    <title>Documentos</title>
</head>

<body>
    <!-- <div class="container" style="height: 100%; margin-bottom: 0px; margin: 0px; padding: 10px; background-color: white; overflow: hidden; border-width: 1px; border-style: solid; border-color: #ccc;"> -->
    <div class=" container">

        <div id="box_form_titulo" class="row" style="margin-top: -10px; background-image: linear-gradient(to left, #6c3a8e , white);">
            <div class="row">
                <div class="form-group col-sm-12 col" style="font-weight: bold; font-size: 25px; color: #523B8F;">Políticas Internas</div>
            </div>
        </div>




        <div id="box_tab1" class="row" style="border-color: grey; padding: 10px; border-width: 1px; border-style: solid; border-color: #ccc; background-color: white; overflow-x: hidden;">
            <input id="c_acao" type="hidden" value="">
            <table style="width: 100%; color:black;" id="tab1" class="table">
                <thead style="font-weight: bold;">
                    <tr>
                        <th>
                            Documento
                        </th>
                        <th>
                            Referência
                        </th>
                        <th>
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody id="tab1b" style="font-weight: normal;">
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-12 text-right" style="margin-top: 15px;">
            <button onclick="func_cadastrar()" id="btn_novo_reg" class="btn btn-primary" style="border-radius: 10px;">Cadastrar Política</button>
        </div>
    </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Cadastrar Nova Política</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form id="formulario">
                            <div class="form-group col-sm-9">
                                <label for="c_descricao">Descrição</label>
                                <input type="text" class="form-control" id="c_descricao" placeholder="Política Interna">
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="c_referencia">Referência</label>
                                <input type="text" class="form-control" id="c_referencia" placeholder="2021">
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="text-left" for="arquivo">Upload de arquivo</label>
                                <input id="c_arquivo" type="file" name="arquivo">
                                <p class="help-block text-left">Arquivo PDF contendo a política desejada.</p>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="func_upload_politica()">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- </div> -->
</body>
<script src="../class/DataTables/datatables.min.js"></script>
<script language="JavaScript">
    $(document).ready(function() {
        func_carrega_tab();
    });

    function func_carrega_tab() {

        $.ajax({
            type: "POST",
            url: "lib/lib_col_politica_interna.php",
            data: {
                "v_acao": "LISTAR_DOCUMENTOS"

            },
            success: function(data) {

                $('#tab1').DataTable().destroy();

                $("#tab1b").empty();
                let options = ''
                data.forEach(element => {
                    options += `<tr>
                            <td>
                                ${element.descricao}
                            </td>
                            <td>
                                ${element.ano_referencia}
                            </td>
                            <td> <button target="_blank" onClick="javascript:window.open('${element.url}','_blank');" class="btn is-icon btn-outline-primary" title="Visualizar documento">
                                    <span class="button-text">
                                        <i class="fa fa-search fa-1x"></i>
                                    </span>
                                </button>
                                <button class="btn is-icon btn-outline-primary" onclick="func_excluir(${element.id})" title="Excluir documento">
                                    <span class="button-text">
                                        <i class="fa fa-trash fa-1x" style="color:red;"></i>
                                    </span>
                                </button>
                                </td>
                        </tr>`
                });

                $('#tab1b').html(options);

                $("#tab1").DataTable({
                    "language": {
                        "url": "../class/DataTables/portugues.json",
                    },
                    "columnDefs": [{
                        "width": "15%",
                        "targets": 1,
                    }],
                    "lengthMenu": [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "Todos"]
                    ],
                    "order": [
                        [0, "asc"]
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
                    text: "Problema ocorrido: " + status + "\nDescição: " + erro +
                        "\nInformações da requisição: " + request.responseText
                })
            }
        });
    }

    function func_upload_politica() {
        if ($("#c_descricao").val().length > 3) {
            Swal.fire({
                title: 'Fazendo upload da política!',
                text: "Sua politica está sendo enviada.",
                icon: 'warning',
                showCancelButton: false,
                showConfirmButton: false,
                closeOnConfirm: false, //It does close the popup when I click on close button
                closeOnCancel: false,
                allowOutsideClick: false
            })

            // Captura os dados do formulário
            let formulario = document.getElementById('formulario');

            // Instância o FormData passando como parâmetro o formulário
            let formData = new FormData(formulario);

            formData.append("v_acao", 'UPLOAD_POLITICA')
            formData.append("v_referencia", $("#c_referencia").val())
            formData.append("v_descricao", $("#c_descricao").val())

            $.ajax({
                url: 'lib/lib_col_politica_interna.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,

                success: function(data) {
                    // console.log(data);
                    var v_json = JSON.parse(data);
                    Swal.fire(
                        v_json.msg_titulo,
                        v_json.msg,
                        v_json.msg_ev
                    )
                    if (v_json.msg_ev == "success") {
                        $('#myModal').modal('hide')
                        func_carrega_tab()
                    }
                },
                error: function(request, status, erro) {
                    Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                }
            });
        } else {
            Swal.fire(
                "AVISO",
                "Informe uma descrição para a política",
                "warning"
            )
        }
    }

    function func_excluir(id) {

        Swal.fire({
            title: 'Você tem certeza que deseja excluir?',
            text: "Você irá excluir a politica!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, excluir!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'lib/lib_col_politica_interna.php',
                    type: 'POST',
                    data: {
                        "v_id_politica": id,
                        "v_acao": "EXCLUIR_POLITICA"
                    },
                    success: function(data) {
                        // console.log(data);
                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        if (v_json.msg_ev == "success") {
                            func_carrega_tab()
                        }
                    },
                    error: function(request, status, erro) {
                        Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }
                });
            }
        })

    }

    function func_cadastrar() {
        $.ajax({
            type: "POST",
            url: "lib/lib_col_politica_interna.php",
            data: {
                "v_acao": "VERIFICA_AUTORIZACAO"
            },
            success: function(data) {

                var v_json = JSON.parse(data);

                if (v_json.msg_ev == "success") {
                    $("#c_descricao").val('')
                    $("#c_referencia").val('')
                    $("#c_arquivo").val('')

                    $("#myModal").modal('show')
                } else if (v_json.msg_ev == "error") {
                    Swal.fire(
                        v_json.msg_titulo,
                        v_json.msg,
                        v_json.msg_ev
                    )
                }

            },
            error: function(request, status, erro) {
                Swal.fire({
                    icon: "error",
                    title: "FALHA!",
                    text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                })
            }
        })
    }
</script>