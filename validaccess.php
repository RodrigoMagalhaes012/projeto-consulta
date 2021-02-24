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
    <link href="css/index.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">

</head>

<body style="margin: 0px; padding: 0px;">

    <div class="container-responsive">

        <div id="carousel_background" data-interval="7000" class="carousel slide background carousel-fade" data-ride="carousel" style="position: fixed;">
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <div class="item active">

                    <img src="img/index/fundo_001.jpg" style="width: 100%; height: auto;">
                </div>
                <div class="item">

                    <img src="img/index/fundo_002.jpg" style="width: 100%; height: auto;">
                </div>
                <div class="item">

                    <img src="img/index/fundo_003.jpg" style="width: 100%; height: auto;">
                </div>
                <div class="item">

                    <img src="img/index/fundo_004.jpg" style="width: 100%; height: auto;">
                </div>
                <div class="item">

                    <img src="img/index/fundo_005.jpg" style="width: 100%; height: auto;">
                </div>
                <div class="item">

                    <img src="img/index/fundo_006.jpg" style="width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </div>

    </div>






    <div class="container-responsive">
        <div class="row">
            <div class="form-group col-sm-12">
                <div class="form-group col-sm-8">
                </div>

                <div id="col2" class="col-sm-3" style="background-color: rgb(255, 255, 255, 0.8);">

                    <div class="row" style="margin-left: 10px; margin-right: 10px;">
                        <div class="form-group col-sm-12">
                            <img src="img/index/logo.png" class="img-responsive">
                        </div>
                    </div>

                    <div class="row" style="margin-left: 10px; margin-right: 10px;">
                        <div class="form-group col-sm-12">
                            <label for="c_senha">NOVA SENHA</label>
                            <img id="c_olho" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABDUlEQVQ4jd2SvW3DMBBGbwQVKlyo4BGC4FKFS4+TATKCNxAggkeoSpHSRQbwAB7AA7hQoUKFLH6E2qQQHfgHdpo0yQHX8T3exyPR/ytlQ8kOhgV7FvSx9+xglA3lM3DBgh0LPn/onbJhcQ0bv2SHlgVgQa/suFHVkCg7bm5gzB2OyvjlDFdDcoa19etZMN8Qp7oUDPEM2KFV1ZAQO2zPMBERO7Ra4JQNpRa4K4FDS0R0IdneCbQLb4/zh/c7QdH4NL40tPXrovFpjHQr6PJ6yr5hQV80PiUiIm1OKxZ0LICS8TWvpyyOf2DBQQtcXk8Zi3+JcKfNafVsjZ0WfGgJlZZQxZjdwzX+ykf6u/UF0Fwo5Apfcq8AAAAASUVORK5CYII=" />
                            <input onchange="func_limpa_campo();" type="password" maxlength="20" class="form-control" id="c_senha">
                        </div>
                    </div>

                    <div class="row" style="margin-left: 10px; margin-right: 10px;">
                        <div class="form-group col-sm-12">
                            <div class="form-group w-100">
                                <label for="c_senha2">CONFIRME A NOVA SENHA</label>
                                <img id="c_olho2" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABDUlEQVQ4jd2SvW3DMBBGbwQVKlyo4BGC4FKFS4+TATKCNxAggkeoSpHSRQbwAB7AA7hQoUKFLH6E2qQQHfgHdpo0yQHX8T3exyPR/ytlQ8kOhgV7FvSx9+xglA3lM3DBgh0LPn/onbJhcQ0bv2SHlgVgQa/suFHVkCg7bm5gzB2OyvjlDFdDcoa19etZMN8Qp7oUDPEM2KFV1ZAQO2zPMBERO7Ra4JQNpRa4K4FDS0R0IdneCbQLb4/zh/c7QdH4NL40tPXrovFpjHQr6PJ6yr5hQV80PiUiIm1OKxZ0LICS8TWvpyyOf2DBQQtcXk8Zi3+JcKfNafVsjZ0WfGgJlZZQxZjdwzX+ykf6u/UF0Fwo5Apfcq8AAAAASUVORK5CYII=" />
                                <input disabled id="c_senha2" type="password" maxlength="20" onkeyup="if (event.keyCode === 13) func_autenticar();" class="form-control w-100">
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-left: 10px; margin-right: 10px;">
                        <div class="form-group col-sm-12">
                            <div id="popover-password" style="font-size: 13px;">
                                <p>Força da Senha: <span id="result"></span></p>
                                <div class="progress" style="margin-bottom: 10px;">
                                    <div id="passbar" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                </div>
                                <ul class="list-unstyled">
                                    <li class=""><span class="low-case"><i class="fa fa-times" aria-hidden="true"></i></span>&nbsp; 1 Minúscula</li>
                                    <li class=""><span class="upper-case"><i class="fa fa-times" aria-hidden="true"></i></span>&nbsp; 1 Maiúscula</li>
                                    <li class=""><span class="one-number"><i class="fa fa-times" aria-hidden="true"></i></span> &nbsp;1 Número (0-9)</li>
                                    <li class=""><span class="one-special-char"><i class="fa fa-times" aria-hidden="true"></i></span> &nbsp;1 Caracter Especial (!@#$%^&*).</li>
                                    <li class=""><span class="eight-character"><i class="fa fa-times" aria-hidden="true"></i></span>&nbsp; Mínimo de 8 Caracteres</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-left: 10px; margin-right: 10px;">
                        <div class="form-group col-sm-12 text-right">
                            <button disabled id="btn_login" type="button" class="btn btn-primary" onclick="func_autenticar();">ATIVAR ACESSO</button>
                        </div>
                    </div>

                </div>

                <div id="col3" class="col-sm-1">

                </div>
            </div>
        </div>
    </div>
</body>


<script src="class/jquery/dist/jquery.min.js"></script>
<script src="class/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="class/jquery/dist/jquery.mask.min.js"></script>
<script src="class/alert/js/class_alert.js"></script>
<script src="js/validaccess.js"></script>

<script language="JavaScript">
    $(document).ready(function() {
        $("#div_row").css("height", window.screen.availHeight);
        $("#col2").css("height", window.screen.availHeight);
    });



    function func_autenticar() {

        var v_senha = $("#c_senha").val();
        var v_senha2 = $("#c_senha2").val();

        if (v_senha == v_senha2) {

            var url_string = window.location.href;
            var url = new URL(url_string);
            var v_chave = url.searchParams.get("chave");

            $.ajax({
                type: "POST",
                url: "mod/lib/lib_validaccess.php",
                dataType: 'json',
                data: {
                    "v_acao": "ATIVACCESS",
                    "v_chave": v_chave,
                    "v_senha": v_senha
                },
                success: function(data) {

                    var v_json = JSON.parse(data);

                    if (v_json.msg_ev == "success") {

                        Swal.fire({
                            icon: "success",
                            title: "SUCESSO!",
                            text: "O seu perfil foi ativado com sucesso.  Em 5 segundos, você será direcionado(a) para a tela de login."
                        })

                        setTimeout(function() {
                            location.href = v_json.msg;
                        }, 4000);

                    } else {

                        Swal.fire({
                            icon: "error",
                            title: "FALHA!",
                            text: v_json.msg
                        })

                    }
                }
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "FALHA!",
                text: "Senhas não conferem."
            })
        }
    }

    function func_limpa_campo(){
        if($("#c_senha").val() == ''){
            $("#c_senha2").val("")
        }
    }

    var senha = $('#c_senha');
    var senha2 = $('#c_senha2');
    var c_olho = $("#c_olho");
    var c_olho2 = $("#c_olho2");





    c_olho.mousedown(function() {
        senha.attr("type", "text");
    });

    c_olho2.mousedown(function() {
        senha2.attr("type", "text");
    });

    c_olho.mouseup(function() {
        senha.attr("type", "password");
    });

    c_olho2.mouseup(function() {
        senha2.attr("type", "password");
    });
    // para evitar o problema de arrastar a imagem e a senha continuar exposta, 
    //citada pelo nosso amigo nos comentários
    $("#c_olho").mouseout(function() {
        $("#c_senha").attr("type", "password");
    });

    $("#c_olho2").mouseout(function() {
        $("#c_senha2").attr("type", "password");
    });
</script>

</html>