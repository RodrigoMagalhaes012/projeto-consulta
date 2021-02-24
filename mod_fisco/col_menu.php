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
    </style>

    <title>Document</title>
</head>

<body>

    <div class="load"> <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
        <span class="sr-only">Loading...</span> </div>

    <div class="container">
        <div id="box_form_titulo" class="box" style="height: 60px; margin-top: 40px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-12 col text-center">
                    <h3>Gestão Fiscal</h3>
                </div>
            </div>
        </div>
        <div id="box_form_titulo" class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">

        
        </div>
    </div>
    </div>
</body>


<script language="JavaScript">
    $(document).ready(function() {

        $('.load').hide();
    });



    function func_upload_foto() {


        // Captura os dados do formulário
        var formulario = document.getElementById('form_arquivo');

        // Instância o FormData passando como parâmetro o formulário
        var formData = new FormData(formulario);

        $.ajax({
            url: 'lib/menu_painel_col.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {},
        });

        $("#arquivo_folha").attr('src', 'img/user_foto/' + $("#c_id_arquivo").val() + '.jpg');

    }
</script>

</html>