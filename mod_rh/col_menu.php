<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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

        [class=touch]:active {
            background-color: #d3c4dd;
        }
    </style>

    <title>Document</title>
</head>

<body>

    <div class="load"> <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
        <span class="sr-only">Loading...</span>
    </div>

    <div class="container">
        <div id="box_form_titulo" class="row" style="margin-top: 40px; background-image: linear-gradient(to left, #6c3a8e , white); z-index: -1; margin: 0px; padding: 0px;">
            <div class="row">
                <div class="form-group col-sm-12 col" style="font-weight: bold; font-size: 25px; color: #523B8F;">UNI RH</div>
            </div>
        </div>
        <div id="box_form_titulo" class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">

            <div class="row" style="margin-bottom: 40px;">
                <?php
                if (strpos($_SESSION["vs_array_access"], "T0019") > 0) {
                    echo '<div class="col-sm-6 col-xs-6 text-center">';
                    echo '<div class="touch" style="max-width: 10em; margin-left: auto; margin-right: auto">';
                    echo '<img src="./../img/image_painel/recibo_pagamento.png" style="cursor:pointer;" onclick="$(\'#div_tela\').load(\'cons_holerite.php\') ;">';
                    echo '<h6><b>Holerites</b></h6>';
                    echo '</div>';
                    echo '</div>';
                } ?>
                <?php
                if (strpos($_SESSION["vs_array_access"], "T0082") > 0) {
                    echo '<div class="col-sm-6 col-xs-6 text-center">';
                    echo '<div class="touch" style="max-width: 10em; margin-left: auto; margin-right: auto">';
                    echo '<img src="./../img/image_painel/informe.png" style="cursor:pointer;" onclick="$(\'#div_tela\').load(\'col_rendimento.php\') ;">';
                    echo '<h6><b>Informe de Rendimentos</b></h6>';
                    echo '</div>';
                    echo '</div>';
                } ?>
            </div>

            <div class="row" style="margin-bottom: 40px;">
                <?php
                if (strpos($_SESSION["vs_array_access"], "T0030") > 0) {
                    echo '<div class="col-sm-6 col-xs-6 text-center">';
                    echo '<div class="touch" style="max-width: 10em; margin-left: auto; margin-right: auto">';
                    echo '<img src="./../img/image_painel/politica.png" style="cursor:pointer;" onclick="$(\'#div_tela\').load(\'col_politica_interna.php\') ;">';
                    echo '<h6><b>Políticas Internas</b></h6>';
                    echo '</div>';
                    echo '</div>';
                } ?>
                <?php
                if (strpos($_SESSION["vs_array_access"], "T0031") > 0) {
                    echo '<div class="col-sm-6 col-xs-6 text-center">';
                    echo '<div class="touch" style="max-width: 10em; margin-left: auto; margin-right: auto">';
                    echo '<img src="./../img/image_painel/noticias.png" style="cursor:pointer;" onclick="$(\'#div_tela\').load(\'feed_noticias.php\') ;">';
                    echo '<h6><b>Feed de Notícias</b></h6>';
                    echo '</div>';
                    echo '</div>';
                } ?>
            </div>

            <div class="row" style="margin-bottom: 40px;">
                <?php
                if (strpos($_SESSION["vs_array_access"], "T0051") > 0) {
                    echo '<div class="col-sm-6 col-xs-6 text-center">';
                    echo '<div class="touch" style="max-width: 10em; margin-left: auto; margin-right: auto">';
                    echo '<img src="./../img/image_painel/premiação.png" style="cursor:pointer;" onclick="$(\'#div_tela\').load(\'cons_premiacao.php\') ;">';
                    echo '<h6><b>Premiação</b></h6>';
                    echo '</div>';
                    echo '</div>';
                } ?>
            </div>
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