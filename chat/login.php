<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="class/bootstrap/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link href="css/login.css" rel="stylesheet">
    <title>Document</title>
</head>

<body style="max-width: 600px; margin: auto;">
    <div class="container px-5 py-5 mx-auto">
        <div class="card card0">
            <div class="d-flex flex-lg-row flex-column-reverse">
                <div class="card card1">
                    <div class="row justify-content-center my-auto">
                        <div class="col-md-8 col-8 my-3">
                            <div class="row justify-content-center px-3 mb-1">
                                <img id="logo" class="img-thumbnail" src="https://i.imgur.com/PSXxjNY.png">
                                <h2 class="text-center heading">Omnichannel</h2>
                                <h4 class="text-center heading_cliente">Agrocontar</h4>
                            </div>

                            <div class="row justify-content-center mb-1">
                                <img id="logo_cliente" class="img-thumbnail" src="https://agrocontar.com.br/website2017/wp-content/themes/agrocontar/img/logo-agrocontar.png">
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label class="form-control-label text-muted">Usuário</label>
                                    <input type="text" id="email" name="email" placeholder="CPF ou E-mail" class="form-control">
                                </div>
                            </div>

                            <div class="row justify-content-center my-3 px-3">
                                <button class="btn-block btn-white">Acessar</button>
                            </div>

                            <div class="row mb-5">
                                <h6 class="text-center mt-3">Ou acessar por outros meios:</h6>
                                <div class="text-center">
                                    <a style="margin: 1px; cursor: pointer;"><img class="img-thumbnail" src="https://app.agrocontar.com.br/chat/img/btn_logo_facebook_cinza.png" style="border: none; max-width: 60px;"></a>
                                    <a style="margin: 1px; cursor: pointer;"><img class="img-thumbnail" src="https://app.agrocontar.com.br/chat/img/btn_logo_twitter_cinza.png" style="border: none; max-width: 60px;"></a>
                                    <a style="margin: 1px; cursor: pointer;"><img class="img-thumbnail" src="https://app.agrocontar.com.br/chat/img/btn_logo_google.png" style="border: none; max-width: 60px;"></a>
                                    <a style="margin: 1px; cursor: pointer;"><img class="img-thumbnail" src="https://app.agrocontar.com.br/chat/img/btn_logo_linkedin_cinza.png" style="border: none; max-width: 60px;"></a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="class/jquery/jquery-3.5.1.min.js"></script>
<script src="class/bootstrap/js/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
<script src="class/bootstrap/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>

</html>

<script>
        $(document).ready(function() {

        });
</script>