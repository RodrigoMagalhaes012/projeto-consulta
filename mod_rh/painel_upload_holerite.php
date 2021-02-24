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

    <title>Document</title>
</head>

<body>
    <div class="container">
        <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">
            <div class="box-body" style="height: auto;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col text-center">
                            <div class="row col">
                                <div class="form-group col-sm-12 col">
                                    <h3>Importar dados Holerite</h3>
                                    <?php
                                    if (isset($_SESSION['msg'])) {
                                        echo $_SESSION['msg'];
                                        unset($_SESSION['msg']);
                                    }
                                    ?>
                                    <form id="form_upload" method="POST" action="lib/lib_painel_holerite_upload.php" enctype="multipart/form-data">
                                        <img src="img/image_painel/upload.png" alt="">
                                        <center><input type="file" name="arquivo"><br><br></center>
                                        <input id="importartxt" type="file" name="importartxt" style="visibility: hidden;">
                                        <button class="btn btn-primary btn-lg" type="button" onclick="func_upload()" value="Importar"> <i class="fa fa-cloud-upload" aria-hidden="true"></i> Importar Arquivo </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="box_titulo" class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">
            <div class="row ">
                <div class="form-group col-sm-12 col text-center ">
                    <h3>Historico de Importação</h3>
                </div>
            </div>
        </div>


        <div id="box_tab1" class="box" style="height: auto; max-height: 300px; background-color: white; border: none; overflow-x: hidden;">
            <input id="c_acao" type="hidden" value="">
            <div class="box-body">
                <input type="hidden" id="vf_tab_sql_limit_in" value="0">
                <input type="hidden" id="vf_tab_btn_pag_select" value="1">

                <table id="tab1" class="table">
                    <thead style="font-weight: bold;">
                        <tr>
                            <th>Id</th>
                            <th>Nome do Arquivo</th>
                            <th>Competência</th>
                            <th>Data e Hora</th>
                            <th>Usuário1</th>
                        </tr>
                    </thead>
                    <tbody id="tab1b" style="font-weight: normal;">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</body>


<script language="JavaScript">
    $(document).ready(function() {

    });



    function func_upload() {
        Swal.fire({
            title: 'Você tem certeza que deseja importar?',
            text: "Você estará inciando a importação!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, importar!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Carregado!',
                    'Seu arquivo está sendo carregado.',
                    'success'
                )
                $("#form_upload").submit();
            }
        })
    }
</script>