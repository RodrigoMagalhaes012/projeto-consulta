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
    <link href="css/chat_user.css" rel="stylesheet">

</head>

<body>

    <div id="frame">
        <div class="content">
            <div class="contact-profile">
                <img id="resumo_atend_img" class="img_atendente" src="img/uni.png" alt="" />
                <p id="resumo_atend_01" style="margin: 0px; padding: 0px; height: 20px;"></p>
                <p id="resumo_atend_02" style="margin: 0px; padding: 0px; height: 20px;"></p>
            </div>
            <div class="messages">
                <ul id="grid_messages">

                </ul>
            </div>
            <div class="message-input">
                <div class="wrap">
                    <input id="c_msg" type="text" placeholder="Digite a sua mensagem...">
                    <button class="button_anexo" onclick="$('#send_file').click();"><i class="fa fa-paperclip" aria-hidden="true"></i></button>
                    <button class="button_send" onclick="newMessage();"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </div>
    <input id="c_conversa_id" type="hidden" value="0">
    <input id="c_conversa_data" type="hidden" value="-">
    <form id="formulario">
        <input type="file" id="send_file" name="arquivo" onchange="func_send_upload();" style="visibility: hidden;">
    </form>

    <script src="class/jquery/jquery-3.5.1.min.js"></script>
    <script src="class/bootstrap/js/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="class/bootstrap/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>

    <script language="JavaScript">
        $(document).ready(function() {
            func_hist_msg();

        });



        $("#c_msg").on('keydown', function(e) {
            if (e.which == 13) {
                newMessage();
                return false;
            }
        });



        function func_hist_msg() {

            var v_acao = "LISTA_CONVERSAS";
            var v_conversa_id = $("#c_conversa_id").val();
            var v_conversa_data = $("#c_conversa_data").val();

            $.ajax({
                type: "POST",
                url: "lib/lib_chat_user.php",
                data: {
                    "v_acao": v_acao,
                    "v_conversa_id": v_conversa_id
                },
                success: function(data) {

                    var options = $("#grid_messages").html();

                    var v_ultima_resp_nome = "";
                    var v_ultima_resp_foto_user = "";
                    var v_ultima_resp_msg_data = "";

                    for (v_index = 1; v_index < data.length; v_index++) {

                        if (v_conversa_data != data[v_index].cdata) {
                            v_conversa_data = data[v_index].cdata;
                            options += '<li>';
                            options += '    <p><strong>----- Conversas realizadas no dia ' + v_conversa_data + ' -----</strong></p>';
                            options += '</li>';
                        }

                        options += '<li class="' + data[v_index].msg_class + '">';
                        options += '    <img src="' + data[v_index].foto_user + '" alt="" />';

                        if(data[v_index].msg_tipo == "TXT"){
                            options += '    <p><strong>' + data[v_index].msg_nome + ' ' + data[v_index].chora + '</strong><br>' + data[v_index].msg_texto + '</p>';
                        } else {
                            options += '    <p><strong>' + data[v_index].msg_nome + ' ' + data[v_index].chora + '</strong>' + data[v_index].msg_texto + '<br><img src="img/ico_file.png" onclick="window.open(\'' + data[v_index].msg_link + '\');" style="border-radius: 0%; max-height: 80px; cursor: pointer;"></p>';
                        }
                        options += '</li>';

                        if (data[v_index].msg_class == "atendente") {
                            var v_ultima_resp_nome = data[v_index].msg_nome;
                            var v_ultima_resp_foto_user = data[v_index].foto_user;
                            var v_ultima_resp_msg_data = data[v_index].data_hora;
                        }

                        $("#c_conversa_id").val(data[v_index].id);
                        $("#c_conversa_data").val(data[v_index].cdata);

                    }

                    if (v_index == 1) {
                        $("#resumo_atend_01").html("Seja bem vindo(a), em que podemos ajudar ?");
                        $("#resumo_atend_02").html("Online");
                    } else {
                        if (v_ultima_resp_msg_data.length > 0) {
                            $("#resumo_atend_01").html("Seja bem vindo(a), A sua ultima pergunta foi respondida por " + v_ultima_resp_nome) + " em que podemos ajudar ?";
                            $("#resumo_atend_02").html(v_ultima_resp_msg_data);
                        }
                    }
                    $("#grid_messages").html(options);

                    $(".messages").animate({
                        scrollTop: 50000000
                    }, "fast");

                }
            });

            window.setTimeout(function() {
                clearInterval(func_hist_msg());
            }, 3000);

        }



        function newMessage() {

            var v_msg = $("#c_msg").val();
            $("#c_msg").val("");

            if (v_msg.length > 0) {

                v_acao = "ENVIAR_MSG";
                $.ajax({
                    type: "POST",
                    url: "lib/lib_chat_user.php",
                    data: {
                        "v_acao": v_acao,
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
            formData.append("v_msg", $("#c_msg").val());

            $.ajax({
                type: "POST",
                url: "lib/lib_chat_user.php",
                data: formData,
                contentType: false,
                processData: false,
            });

        }



    </script>

</body>

</html>