<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>



<!DOCTYPE html>
<html class=''>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="class/bootstrap/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,300' rel='stylesheet' type='text/css'>
    <link href="class/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href='css/reset.min.css' rel='stylesheet prefetch'>
    <link href="css/chat_atendente.css" rel="stylesheet">

</head>

<body>

    <div id="frame">
        <div id="sidepanel">
            <div id="profile">
                <div class="wrap">
                    <img id="profile-img" src="<?php echo 'img/user/' . $_SESSION["vs_id"] . '.jpg'; ?>" class="online" alt="" />
                    <p id="profile-nome"><?php echo ucfirst(strtolower(explode(" ", $_SESSION["vs_nome"])[0])) . " " .  ucfirst(strtolower(explode(" ", $_SESSION["vs_nome"])[1])); ?></p>
                </div>
            </div>
            <div id="contacts" style="margin-top: 10px;">
                <ul id="fila_atend">
                    <li id="fila_1" class="fila contact" onclick="$('.fila').removeClass('active');  $(this).addClass('active');">
                        <div class="wrap">
                            <span class="contact-status online"></span>
                            <img src="img/user/louislitt.png" alt="" />
                            <div class="meta">
                                <p class="name">Louis Litt</p>
                                <p class="preview" style="color: #9cff9c;">Usuário Autenticado.</p>
                            </div>
                        </div>
                    </li>
                    <li id="fila_2" class="fila contact" onclick="$('.fila').removeClass('active');  $(this).addClass('active');">
                        <div class="wrap">
                            <img src="img/user/harveyspecter.png" alt="" />
                            <div class="meta">
                                <p class="name">Harvey Specter</p>
                                <p class="preview" style="color: #9cff9c;">Usuário Autenticado.</p>
                            </div>
                        </div>
                    </li>
                    <li id="fila_3" class="fila contact" onclick="$('.fila').removeClass('active');  $(this).addClass('active');">
                        <div class="wrap">
                            <img src="img/user/rachelzane.png" alt="" />
                            <div class="meta">
                                <p class="name">Rachel Zane</p>
                                <p class="preview" style="color: #ff9696;">Usuário não Autenticado.</p>
                            </div>
                        </div>
                    </li>
                    <li id="fila_4" class="fila contact" onclick="$('.fila').removeClass('active');  $(this).addClass('active');">
                        <div class="wrap">
                            <img src="img/user/donnapaulsen.png" alt="" />
                            <div class="meta">
                                <p class="name">Donna Paulsen</p>
                                <p class="preview" style="color: #9cff9c;">Usuário Autenticado.</p>
                            </div>
                        </div>
                    </li>
                    <li id="fila_5" class="fila contact" onclick="$('.fila').removeClass('active');  $(this).addClass('active');">
                        <div class="wrap">
                            <span class="contact-status online"></span>
                            <img src="img/user/jessicapearson.png" alt="" />
                            <div class="meta">
                                <p class="name">Jessica Pearson</p>
                                <p class="preview" style="color: #ff9696;">Usuário não Autenticado.</p>
                            </div>
                        </div>
                    </li>
                    <li id="fila_6" class="fila contact" onclick="$('.fila').removeClass('active');  $(this).addClass('active');">
                        <div class="wrap">
                            <img src="img/user/haroldgunderson.png" alt="" />
                            <div class="meta">
                                <p class="name">Harold Gunderson</p>
                                <p class="preview" style="color: #ff9696;">Usuário não Autenticado.</p>
                            </div>
                        </div>
                    </li>
                    <li id="fila_7" class="fila contact" onclick="$('.fila').removeClass('active');  $(this).addClass('active');">
                        <div class="wrap">
                            <img src="img/user/danielhardman.png" alt="" />
                            <div class="meta">
                                <p class="name">Daniel Hardman</p>
                                <p class="preview" style="color: #ff9696;">Usuário não Autenticado.</p>
                            </div>
                        </div>
                    </li>
                    <li id="fila_8" class="fila contact" onclick="$('.fila').removeClass('active');  $(this).addClass('active');">
                        <div class="wrap">
                            <span class="contact-status online"></span>
                            <img src="img/user/katrinabennett.png" alt="" />
                            <div class="meta">
                                <p class="name">Katrina Bennett</p>
                                <p class="preview" style="color: #ff9696;">Usuário não Autenticado.</p>
                            </div>
                        </div>
                    </li>
                    <li id="fila_8" class="fila contact" onclick="$('.fila').removeClass('active');  $(this).addClass('active');">
                        <div class="wrap">
                            <img src="img/user/charlesforstman.png" alt="" />
                            <div class="meta">
                                <p class="name">Charles Forstman</p>
                                <p class="preview" style="color: #ff9696;">Usuário não Autenticado.</p>
                            </div>
                        </div>
                    </li>
                    <li id="fila_9" class="fila contact" onclick="$('.fila').removeClass('active');  $(this).addClass('active');">
                        <div class="wrap">
                            <img src="img/user/jonathansidwell.png" alt="" />
                            <div class="meta">
                                <p class="name">Jonathan Sidwell</p>
                                <p class="preview" style="color: #ff9696;">Usuário Não Autenticado.</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div id="bottom-bar">
                <button id="addcontact"><i class="fa fa-user-plus fa-fw" aria-hidden="true"></i> <span>Add contact</span></button>
                <button id="settings"><i class="fa fa-cog fa-fw" aria-hidden="true"></i> <span>Settings</span></button>
            </div>
        </div>
        <div class="content">
            <div class="messages">
                <ul id="grid_messages">

                </ul>
            </div>
            <div class="message-input">
                <div class="wrap">
                    <input id="c_msg" type="text" placeholder="Digite a sua mensagem..." />
                    <button class="button_anexo" onclick="$('#send_file').click();"><i class="fa fa-paperclip" aria-hidden="true"></i></button>
                    <button class="button_send" onclick="newMessage();"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </div>
    <input id="c_cliente_id" type="hidden" value="0">
    <input id="c_cliente_nome" type="hidden" value="-">
    <input id="c_conversa_id" type="hidden" value="0">
    <input id="c_foto_user" type="hidden" value="">
    <input id="c_conversa_data" type="hidden" value="-">
    <form id="formulario">
        <input type="file" id="send_file" name="arquivo" onchange="func_send_upload();" style="visibility: hidden;">
    </form>


    <script src="class/jquery/jquery-3.5.1.min.js"></script>
    <script src="class/bootstrap/js/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="class/bootstrap/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>

    <script language="JavaScript">
        $(document).ready(function() {
            func_lista_fila();

        });



        $("#c_msg").on('keydown', function(e) {
            if (e.which == 13) {
                newMessage();
                return false;
            }
        });



        function func_lista_fila() {

            v_acao = "LISTA_FILA";

            $.ajax({
                type: "POST",
                url: "lib/lib_chat_atendente.php",
                data: {
                    "v_acao": v_acao
                },
                success: function(data) {

                    $("#fila_atend").empty();
                    var options = "";

                    for (v_index = 1; v_index < data.length; v_index++) {

                        options += '<li id="fila_' + v_index + '" class="fila contact" onclick="$(\'#grid_messages\').empty(); $(\'#c_cliente_id\').val(' + data[v_index].cliente_id + '); $(\'#c_conversa_id\').val(0); $(\'#c_cliente_nome\').val(\'' + data[v_index].cliente_nome + '\'); $(\'#c_foto_user\').val(\'' + data[v_index].foto_user + '\');">';
                        options += '<div class="wrap">';
                        options += '<span class="contact-status online"></span>';
                        options += '<img src="' + data[v_index].foto_user + '" alt="" />';
                        options += '<div class="meta">';
                        options += '<p class="name">' + data[v_index].cliente_nome + '</p>';
                        options += '<p class="preview" style="color: #9cff9c;">Autenticado: ' + data[v_index].data_hora + '</p>';
                        options += '</div>';
                        options += '</div>';
                        options += '</li>';

                    }

                    $("#fila_atend").html(options);
                    func_hist_msg();

                }
            });

        }



        function func_hist_msg() {

            var v_acao = "LISTA_CONVERSAS";
            var v_cliente_id = $("#c_cliente_id").val();
            var v_cliente_nome = $("#c_cliente_nome").val();
            var v_cliente_foto = $("#c_foto_user").val();
            var v_conversa_id = $("#c_conversa_id").val();
            var v_conversa_data = $("#c_conversa_data").val();

            $.ajax({
                type: "POST",
                url: "lib/lib_chat_atendente.php",
                data: {
                    "v_acao": v_acao,
                    "v_cliente_id": v_cliente_id,
                    "v_conversa_id": v_conversa_id
                },
                success: function(data) {
                    var options = $("#grid_messages").html();
                    for (v_index = 1; v_index < data.length; v_index++) {

                        if (v_conversa_data != data[v_index].cdata) {
                            v_conversa_data = data[v_index].cdata;
                            options += '<li>';
                            options += '    <p><strong>----- Conversas realizadas no dia ' + v_conversa_data + ' -----</strong></p>';
                            options += '</li>';
                        }

                        options += '<li class="' + data[v_index].msg_class + '">';
                        options += '    <img src="' + data[v_index].foto_user + '" alt="" />';
                        if (data[v_index].msg_tipo == "TXT") {
                            options += '    <p><strong>' + data[v_index].msg_nome + ' ' + data[v_index].chora + '</strong><br>' + data[v_index].msg_texto + '</p>';
                        } else {
                            options += '    <p><strong>' + data[v_index].msg_nome + ' ' + data[v_index].chora + '</strong>' + data[v_index].msg_texto + '<br><img src="img/ico_file.png" onclick="window.open(\'' + data[v_index].msg_link + '\');" style="border-radius: 0%; max-height: 80px; cursor: pointer;"></p>';
                        }
                        options += '</li>';

                        $("#c_conversa_id").val(data[v_index].id);
                        $("#c_conversa_data").val(data[v_index].cdata);
                    }

                    if (v_cliente_foto.length > 0) {
                        $("#profile-img").attr("src", v_cliente_foto);
                        $("#profile-nome").html(v_cliente_nome);
                        $("#grid_messages").html(options);
                    }

                    $(".messages").animate({
                        scrollTop: 50000000
                    }, "fast");

                }
            });

            window.setTimeout(function() {
                clearInterval(func_lista_fila());
            }, 3000);

        }



        function newMessage() {

            var v_cliente_id = $("#c_cliente_id").val();
            var v_cliente_nome = $("#c_cliente_nome").val();
            var v_msg = $("#c_msg").val();
            $("#c_msg").val("");

            if (v_msg.length > 0) {

                v_acao = "ENVIAR_MSG";
                $.ajax({
                    type: "POST",
                    url: "lib/lib_chat_atendente.php",
                    data: {
                        "v_acao": v_acao,
                        "v_cliente_id": v_cliente_id,
                        "v_cliente_nome": v_cliente_nome,
                        "v_msg": v_msg
                    }
                });
            }
        }



        function func_send_upload() {

            // Captura os dados do formulário
            let formulario = document.getElementById('formulario');
            let formData = new FormData(formulario);
            formData.append("v_acao", 'SEND_FILE');
            formData.append("v_cliente_id", $("#c_cliente_id").val());
            formData.append("v_cliente_nome", $("#c_cliente_nome").val());
            formData.append("v_msg", $("#c_msg").val());

            $.ajax({
                type: "POST",
                url: "lib/lib_chat_atendente.php",
                data: formData,
                contentType: false,
                processData: false,
            });

        }
    </script>
</body>

</html>