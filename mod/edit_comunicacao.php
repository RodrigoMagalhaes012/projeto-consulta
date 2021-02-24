<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0014") == 0) {
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

    <title>Document</title>
</head>

<body>

    <div class="container">



        <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-12">
                    <h3>Telas de Comunicação</h3>
                </div>
            </div>
        </div>
        <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">
            <div class="box-body" style="height: auto;">
                <div class="row">
                    <div class="form-group col-sm-2">
                        <img id="img_01" src="img/index/fundo_001.jpg" class="img-responsive" alt="img_01">
                        <button id="btn_01" class="btn btn-primary" style="width: 100%;" onclick="$('#c_acao').val('EV_UPLOAD');$('#c_img_nome').val('fundo_001');$('#userfile').click();">Carregar</button>
                    </div>
                    <div class="form-group col-sm-2">
                        <img id="img_02" src="img/index/fundo_002.jpg" class="img-responsive" alt="img_01">
                        <button id="btn_02" class="btn btn-primary" style="width: 100%;" onclick="$('#c_acao').val('EV_UPLOAD');$('#c_img_nome').val('fundo_002');$('#userfile').click();">Carregar</button>
                    </div>
                    <div class="form-group col-sm-2">
                        <img id="img_03" src="img/index/fundo_003.jpg" class="img-responsive" alt="img_01">
                        <button id="btn_03" class="btn btn-primary" style="width: 100%;" onclick="$('#c_acao').val('EV_UPLOAD');$('#c_img_nome').val('fundo_003');$('#userfile').click();">Carregar</button>
                    </div>
                    <div class="form-group col-sm-2">
                        <img id="img_04" src="img/index/fundo_004.jpg" class="img-responsive" alt="img_01">
                        <button id="btn_04" class="btn btn-primary" style="width: 100%;" onclick="$('#c_acao').val('EV_UPLOAD');$('#c_img_nome').val('fundo_004');$('#userfile').click();">Carregar</button>
                    </div>
                    <div class="form-group col-sm-2">
                        <img id="img_05" src="img/index/fundo_005.jpg" class="img-responsive" alt="img_01">
                        <button id="btn_05" class="btn btn-primary" style="width: 100%;" onclick="$('#c_acao').val('EV_UPLOAD');$('#c_img_nome').val('fundo_005');$('#userfile').click();">Carregar</button>
                    </div>
                    <div class="form-group col-sm-2">
                        <img id="img_06" src="img/index/fundo_006.jpg" class="img-responsive" alt="img/index/fundo_006.jpg">
                        <button id="btn_06" class="btn btn-primary" style="width: 100%;" onclick="$('#c_acao').val('EV_UPLOAD');$('#c_img_nome').val('fundo_006');$('#userfile').click();">Carregar</button>
                    </div>
                </div>
            </div>
        </div>
        <form enctype="multipart/form-data" action="lib/lib_edit_cominicacao.php" method="POST">
                <input id="c_img_nome" name="c_img_nome" type="hidden">
                <input id="c_acao" name="c_acao" type="hidden">
                <input name="userfile" id="userfile" type="file" onchange="this.form.submit()" style="visibility: hidden;">
        </form>




    </div>
</body>



<script language="JavaScript">
    $(document).ready(function() {

    });
</script>



</html>