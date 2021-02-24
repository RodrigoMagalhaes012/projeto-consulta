<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0044") == 0) {
    print('<script> alert(\'Sessão expirada, favor logar novamente.\'); location.href = \'https://app.agrocontar.com.br\'; </script>');
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

    <title>Upload de colaboradores (Atualização)</title>
</head>

<body style="overflow-x: hidden;">

    <div class="tab-container" id="tab">

        <ul class="nav nav-tabs" id="tabs">
            <li class="active">
                <a data-toggle="tab" href="#tab-1" aria-expanded="false">
                    Atualizar Colaboradores
                </a>
            </li>
            <!-- <li class="nav-item">
                <a data-toggle="tab" href="#tab-2" aria-expanded="false">
                    2. Atualização de Colaboradores
                </a>
            </li> -->
        </ul>

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>

                                        <small></small>
                                    </h5>


                                </div>
                                <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none; overflow-x: hidden;">
                                    <input id="c_acao" type="hidden" value="">
                                    <div class="box-body" style="height: auto;">
                                        <div style="border: none; " class="col-md-12">
                                            <div class="row col">
                                                <div style="margin-left: 80px; margin-bottom: 40px;" class="form-group col-sm-12 col">
                                                    <center>
                                                        <h3>Atualizar Colaboradores</h3>
                                                    </center>
                                                    <form id="form_upload" method="POST" enctype="multipart/form-data">
                                                        <i class="fa fa-cloud-upload fa-5x" aria-hidden="true"></i>
                                                        <label>* Upload de Arquivo </label>
                                                        <input type="file" name="arquivo">
                                                        <input id="importartxt" type="file" name="importartxt" style="visibility: hidden;">
                                                        <button class="btn btn-primary" type="button" onclick="func_upload_atualizacao();" value="Importar"> <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                            Upload de Arquivo </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- <div id="tab-2" class="tab-pane active">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>

                                        <small></small>
                                    </h5>


                                </div>
                                <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none; overflow-x: hidden;">
                                    <input id="c_acao" type="hidden" value="">
                                    <div class="box-body" style="height: auto;">
                                        <div style="border: none; " class="col-md-12">
                                            <div class="row col">
                                                <div style="margin-left: 80px; margin-bottom: 40px;" class="form-group col-sm-12 col">
                                                    <center>
                                                        <h3>Atualizador de colaboradores</h3>
                                                    </center>
                                                    <form id="form_upload" method="POST" enctype="multipart/form-data">
                                                        <i class="fa fa-cloud-upload fa-5x" aria-hidden="true"></i>
                                                        <label>* Importar Arquivo </label>
                                                        <input type="file" name="arquivo">
                                                        <input id="importartxt" type="file" name="importartxt" style="visibility: hidden;">
                                                        <button class="btn btn-primary" type="button" onclick="func_upload_atualizacao();" value="Importar"> <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                            Importar Arquivo </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div style="font-size: 14px; text-align: left;">
                                                        Passos para importação de Colaboradores:<br><br><b>1)</b> A lista deve ser um arquivo no formato .csv ou .txt e deve possuir cabeçalho;<br><br><b>2)</b> O arquivo de importação deve seguir o modelo abaixo: <br><br><a href="img/user_foto/modeloImportacao.csv">- Clique para baixar</a> <b>Nos campos abaixo só serão permitidos os seguintes valores:<br>- Gênero deverá pertencer à: <b>Masculino</b>, <b>M</b>, <b>Feminino</b> ou <b>F</b>;<br>- PNE deverá pertencer à: <b>Sim, Não</b>;<br>- Escolaridade deverá pertencer à: <b>Analfabeto, 4ª Série Incompleta, 4ª Série Completa, 5ª a 8ª Série Incompleta, 1º Grau Completo, 2º Grau Incompleto, 2º Grau Completo, Superior Incompleto, Superior Completo, Pós-Graduação, Mestrado Completo, Doutorado Completo, Ph.D., 2º Grau Tec. Incompleto, 2º Grau Tec. Completo, Mestrado Incompleto, Doutorado Incompleto, Pós-Graduação Incompleto</b><br><br><b>2)</b> Salve o arquivo no formato '.CSV (Separado por ponto e vírgula: ;)'.<br><br><b>3)</b> É só aguardar a importação do arquivo e verificar se todos os dados foram importados.
                                                        </b>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 -->






    </div>
    </div>

    <div id="box_titulo" class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">
        <div class="row ">
            <div class="form-group col-sm-12 col text-center ">
                <h3>Historico de Upload</h3>
            </div>
        </div>
    </div>



    <div id="box_tab1" class="row" style="padding: 10px; border-width: 1px; border-style: solid;">
        <input id="c_acao" type="hidden" value="">
        <div class="box-body">
            <table id="tab1" class="table" style="width: 100%; color:black;">
                <thead style="font-weight: bold;">
                    <tr>
                        <th>Usuário</th>
                        <th>Data e hora</th>
                        <th>Status</th>
                    </tr>
                </thead>
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


    function func_upload_atualizacao() {

        Swal.fire({
            title: 'Você tem certeza que deseja realizar o Upload?',
            text: "Você estará iniciando o Upload!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, importar!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Carregando...',
                    'Seu arquivo está sendo carregado.',
                    'warning'
                )
                // Captura os dados do formulário
                var formulario = document.getElementById('form_upload');

                // Instância o FormData passando como parâmetro o formulário
                var formData = new FormData(formulario);
                // formData.append("id", $("#c_id_usuario").val())

                $.ajax({
                    url: '../mod_rh/lib/lib_upload_colaboradores_atualizacao.php',
                    type: 'post',
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
                            func_carrega_tab();
                        }

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

                // func_carrega_tab();

            } else {
                Swal.fire({
                    icon: "error",
                    title: "FALHA!",
                    text: "Não foi possivel realizar o Upload, favor verificar e tentar novamente."
                })

            }

        })

    }


    function func_carrega_tab() {

        $("#c_acao").val("");
        var v_acao = "LISTAR_COLABORADORES";


        $("#c_competencia").prop("disabled", false);
        $("#c_id_user").prop("disabled", false);

        $("#c_id").val("");
        $("#c_id_user").val("");
        $("#c_data_hora").val("");
        $("#c_status").val("");



        $.ajax({
            type: "POST",
            url: "lib/lib_log_upload_colaboradores_atualizacao.php",
            data: {
                "v_acao": v_acao

            },
            success: function(data) {
                $('#tab1').DataTable().destroy();
                var options = '';
                var v_index = 0;
                var v_num_linhas = 0;

                $("#tab1b").empty();
                v_num_linhas = data[0].linhas;
                for (v_index = 0; v_index < data.length; v_index++) {
                    let v_status = "";
                    if (data[v_index].data_hora) {
                        v_status = "Importado";
                    }
                    options += `<tr  style="cursor: pointer;">
                                <td> ${data[v_index].nome_usu}</td>
                                <td>  ${data[v_index].data_hora}  </td>
                                <td> ${v_status}</td></tr>`;

                }
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
                        [0, "desc"]
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
</script>