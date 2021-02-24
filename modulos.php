<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>

<html>

<head>
    <title>Unifica</title>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Fullscreen Background Image Slideshow with CSS3 - A Css-only fullscreen background image slideshow" />
    <meta name="keywords" content="css3, css-only, fullscreen, background, slideshow, images, content" />
    <meta name="author" content="RENALU-TECH Soluções web">
    <link rel="icon" href="img/home/favicon.png" type="image/ico" />
    <link rel="stylesheet" href="class/alert/css/class_alert.css" id="theme-styles">
    <link href="class/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="class/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/cookie_msg.css">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
</head>

<body>

    <!-- <div class="container">
        <div id="box_form_titulo" class="box" style="margin-top: 30px; height: auto; background-color: white; border: none;">
            <div class="row">
                <div class="col-sm-6 col-xs-6 text-center">
                    <i class="fa fa-money fa-5x" aria-hidden="true" style="cursor:pointer;color:green" onclick="$('#div_tela').load('cons_holerite.php');"></i>
                    <h6>Recibos de Pagamento</h6>
                </div>

                <div class="col-sm-6 col-xs-6 text-center">
                    <i class="fa fa-plane fa-5x" aria-hidden="true" style="cursor:pointer;" onclick="$('#div_tela').load('col_ferias.php');"></i>
                    <h6>Férias</h6>
                </div>

            </div>
            <div class="row">
                <div class="col-sm-6 col-xs-6 text-center">
                    <i class="fa fa-folder-open-o fa-5x" aria-hidden="true" style="cursor:pointer; color:#DAA520;" onclick="$('#div_tela').load('col_politica_interna.php');"></i>
                    <h6>Políticas Internas</h6>
                </div>

                <div class="col-sm-6 col-xs-6 text-center">
                    <i class="fa fa-file-text fa-5x" aria-hidden="true" style="cursor:pointer;" onclick="$('#div_tela').load('col_rendimento.php');"></i>
                    <h6>Informes de Rendimentos</h6>
                </div>
            </div>
            <div class="row">

                <div class="col-sm-6 col-xs-6 text-center">
                    <i class="fa fa-newspaper-o fa-5x" aria-hidden="true" style="cursor:pointer; color:#6CA6CD;" onclick="$('#div_tela').load('feed_noticias.php');"></i>
                    <h6>Feed de Notícias </h6>
                </div>
                <div class="col-sm-6 col-xs-6 text-center">
                    <i class="fa fa-clock-o fa-5x" style="cursor:pointer;" onclick="$('#div_tela').load('col_ponto.php');"></i>
                    <h6>Controle de Ponto</h6>
                </div>
            </div>

            <div class="row">

                <div class="col-sm-6 col-xs-6 text-center">
                    <i class="fa fa-line-chart fa-5x" aria-hidden="true" style="cursor:pointer;" onclick="$('#div_tela').load('col_premiacao.php');"></i>
                    <h6>Premiação</h6>
                </div>

                <div class="col-sm-6 col-xs-6 text-center">
                    <i class="fa fa-shopping-bag fa-5x" aria-hidden="true" style="cursor:pointer;" onclick="$('#div_tela').load('col_beneficios.php');"></i>
                    <h6>Benefícios</h6>
                </div>
            </div>
        </div>
    </div>
    </div> -->

    <div class="container-responsive">
        <div class="row" style="margin: 100px;">

            <?php if (strpos($_SESSION["vs_array_access"], "M0002") > 0) {
                echo '<div style="margin-bottom:80px; cursor:pointer;" class="col-sm-4 col-xs-12 text-center">';
                echo '<i class="" aria-hidden="true" id="btn_mod_rh" class="btn btn-success" onclick="location.href = \'mod_rh\';">';
                echo '<img src="img/modulos/portal_do_colaborador.png">';
                echo '</i>';
                echo '</div>';
            } ?>



            <?php if (strpos($_SESSION["vs_array_access"], "M0003") > 0) {
                echo '<div style="margin-bottom:80px; cursor:pointer;" class="col-sm-4 col-xs-12 text-center">';
                echo '<i class="" aria-hidden="true" id="btn_mod_rh" class="btn btn-success" onclick="location.href = \'mod_fisco\';">';
                echo '<img src="img/modulos/gestao_fiscal.png">';
                echo '</i>';
                echo '</div>';
            } ?>




            <?php if (strpos($_SESSION["vs_array_access"], "M0004") > 0) {
                echo '<div style="margin-bottom:80px; cursor:pointer;" class="col-sm-4 col-xs-12 text-center">';
                echo '<i class="" aria-hidden="true" id="btn_mod_rh" class="btn btn-success" onclick="location.href = \'mod\';">';
                echo '<img src="img/modulos/administrador_local.png">';
                echo '</i>';
                echo '</div>';
            } ?>

















            <!-- <div style="margin-bottom:80px; cursor:pointer;" class="col-sm-4 col-xs-12 text-center">
                <i class="fa fa-book fa-5x" aria-hidden="true"></i>
                <h4>Conciliador Contabil</h4>
            </div>
            <div style="margin-bottom:80px; cursor:pointer;" class="col-sm-4 col-xs-12 text-center">
                <i class="fa fa-area-chart fa-5x" aria-hidden="true"> </i>
                <h4>Analitycs</h4>
            </div>
            <div style="margin-bottom:80px; cursor:pointer;" class="col-sm-4 col-xs-12 text-center">
                <i class="fa fa-building fa-5x" aria-hidden="true"></i>
                <h4> Portal do Cliente</h4>
            </div> -->

        </div>
    </div>
</body>


<script src="class/jquery/dist/jquery.min.js"></script>
<script src="class/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="class/jquery/dist/jquery.mask.min.js"></script>
<script src="class/alert/js/class_alert.js"></script>

<script language="JavaScript">
    $(document).ready(function() {
        $("#div_row").css("height", window.screen.availHeight);
        $("#col2").css("height", window.screen.availHeight);

        var v_lgpd_ciencia = getCookie("ck_lgpd_ciencia");
        if (v_lgpd_ciencia == "") {
            $("#cookie-law-info-bar").show();
        } else {
            $("#cookie-law-info-bar").hide();
        }

    });


    function func_lgpd_ler_politica_privaci() {
        var v_acao = "REGISTRA_ACESSO_POLITICA_PRIVACI";

        $.ajax({
            type: "POST",
            url: "mod_lgpd/lib/lib_lgpd_cookie.php",
            dataType: "json",
            data: {
                "v_acao": v_acao
            },
            complete: function(data) {
                window.open("../mod_lgpd/política_privacidade.pdf", "_blank");
            }
        });

    }



    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }



    function setCookie(cname, cvalue, exdays) {
        var d = new Date();

        // d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        d.setTime(d.getTime() + (exdays * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }


    function func_login() {

        var v_acao = "LOGAR";
        var v_user = $("#c_user").val();
        var v_senha = $("#c_senha").val();
        var v_captcha = $("#c_captcha").val();

        $.ajax({
            type: "POST",
            url: "lib/lib_login.php",
            dataType: 'json',
            data: {
                "v_acao": v_acao,
                "v_user": v_user,
                "v_senha": v_senha,
                "v_captcha": v_captcha
            },
            success: function(data) {

                var v_json = JSON.parse(data);

                if (v_json.msg_ev == "success") {
                    location.href = v_json.msg;
                } else {

                    Swal.fire({
                        icon: "error",
                        title: "FALHA!",
                        text: v_json.msg
                    })

                }
            }
        });
    }
</script>

</html>