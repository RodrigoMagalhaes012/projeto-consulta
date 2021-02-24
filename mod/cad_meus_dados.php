<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// if (strpos($_SESSION["vs_array_access"], "T0007") == 0) {
//     print('<script> alert(\'Acesso Negado.\'); location.href = \'https://app.agrocontar.com.br\'; </script>');
// }

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
</head>

<body>

    <div class="container">
        <div id="box_form_titulo" class="box" style="height: 60px; margin-top: 40px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-12">
                    <h3>Meu Cadastro</h3>
                </div>
            </div>
        </div>
        <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">
            <div class="box-body" style="height: auto;">
                <input id="c_url_arquivo" type="hidden" class="form-control class_inputs w-100">
                <div class="row">
                    <div class="col-md-3">
                        <div class="col text-center">

                            <form id="form_foto" name="form_foto" enctype="multipart/form-data" method="POST">
                                <!-- <input id="c_id_img" name="c_id_img" type="hidden" value="0"> -->
                                <img id="img_foto" class="img-fluid" src="" alt="" style="border: 1px solid #B0B4B5;background:#cccccc;width:200px;height:200px;border-radius:100px;-moz-border-radius:100px;-webkit-border-radius:100px;box-shadow: 1px 1px 2px #333333;-moz-box-shadow: 1px 1px 2px #333333;-webkit-box-shadow: 1px 1px 2px #333333;">
                                <input accept="image/jpeg" id="userfoto" name="userfoto" type="file" onchange="func_upload_foto()" style="visibility: hidden;">
                            </form>
                        </div>
                        <div class="col text-center">
                            <button class="btn btn-warning" type="button" onclick="$('#userfoto').click()"> <i class="fa fa-camera" aria-hidden="true"></i> Importar foto </button>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="form-group col-sm" style="display: none;">
                                <label for="c_nome">Id_usuario</label>
                                <input disabled id="c_id_usuario" type="text" class="form-control class_inputs" placeholder="ID">
                            </div>
                            <div class="form-group col-sm" style="display: none;">
                                <label for="c_nome">Id</label>
                                <input disabled id="c_id" type="text" class="form-control class_inputs" placeholder="ID">
                            </div>
                            <div class="form-group col-sm-9">
                                <label for="c_nome">Nome Completo</label>
                                <input disabled id="c_nome" type="text" class="form-control class_inputs" placeholder="NOME COMPLETO">
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="c_sexo">Sexo</label>
                                <select disabled id="c_sexo" class="form-control class_inputs">
                                    <option selected>Selecione</option>
                                    <option value="1">MASCULINO</option>
                                    <option value="2">FEMININO</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-3">
                                <label for="c_cpf">CPF</label>
                                <input disabled id="c_cpf" type="text" class="form-control class_inputs" placeholder="000.000.000-00">
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="c_dt_nasc">Data de Nascimento</label>
                                <input disabled id="c_dt_nasc" type="date" class="form-control class_inputs" placeholder="00/00/0000">
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="c_celular">Celular</label>
                                <input id="c_celular" type="text" class="form-control class_inputs" placeholder="(00) 0 0000-0000">
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="c_celular_emergencia">Contato de Emergência </label>
                                <input id="c_celular_emergencia" type="text" class="form-control class_inputs" placeholder="(00) 0 0000-0000">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="c_email">E-Mail</label>
                                <input disabled id="c_email" type="email" class="form-control class_inputs" style="text-transform: lowercase;" placeholder="email@email.com.br">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="c_email_pessoal">E-Mail Pessoal</label>
                                <input id="c_email_pessoal" type="email" class="form-control class_inputs" style="text-transform: lowercase;" placeholder="email@email.com.br">
                            </div>


                        </div>
                        <div class="row">
                            <div class="form-group col-sm-3" style="padding-right: 0px;">
                                <i class="fa fa-linkedin-square" aria-hidden="true"></i>
                                <label for="l_linkedin">Linkedin</label>
                                <input disabled id="l_linkedin" type="text" class="form-control class_inputs w-100" style="padding: 5px; text-transform: lowercase;" value="https://www.linkedin.com/">
                            </div>
                            <div class="form-group col-sm-3" style="margin:0px; padding: 0px;">
                                <i class="fa fa-linkedin-square" aria-hidden="true" style="color: white;"></i>
                                <label for="c_linkedin" style="color: white;">E-Mail</label>
                                <input id="c_linkedin" type="email" class="form-control class_inputs w-100" style="margin:0px; padding: 10px; text-transform: lowercase;" placeholder="meuperfil">
                            </div>
                            <div class="form-group col-sm-3" style="padding-right: 0px;">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                                <label for="l_instagram">Instagram</label>
                                <input disabled id="l_instagram" type="text" class="form-control class_inputs w-100" style="padding: 5px; text-transform: lowercase;" value="https://www.instagram.com/">
                            </div>
                            <div class="form-group col-sm-3" style="margin:0px; padding: 0px;">
                                <i class="fa fa-instagram" aria-hidden="true" style="color: white;"></i>
                                <label for="c_instagram" style="color: white;">E-Mail</label>
                                <input id="c_instagram" type="email" class="form-control class_inputs w-100" style="margin:0px; padding: 10px; text-transform: lowercase;" placeholder="meuperfil">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-3" style="padding-right: 0px;">
                                <i class="fa fa-facebook-square" aria-hidden="true"></i>
                                <label for="l_faceboook">Facebook</label>
                                <input disabled id="l_faceboook" type="text" class="form-control class_inputs w-100" style="padding: 5px; text-transform: lowercase;" value="https://www.facebook.com/">
                            </div>
                            <div class="form-group col-sm-3" style="margin:0px; padding: 0px;">
                                <i class="fa fa-facebook-square" aria-hidden="true" style="color: white"></i>
                                <label for="c_faceboook" style="color: white;">E-Mail</label>
                                <input id="c_faceboook" type="email" class="form-control class_inputs w-100" style="margin:0px; padding: 10px; text-transform: lowercase;" placeholder="meuperfil">
                            </div>
                            <div class="form-group col-sm-3" style="padding-right: 0px;">
                                <i class="fa fa-twitter-square" aria-hidden="true"></i>
                                <label for="l_twitter">Twitter</label>
                                <input disabled id="l_twitter" type="text" class="form-control class_inputs w-100" style="padding: 5px; text-transform: lowercase;" value="https://www.twitter.com/">
                            </div>
                            <div class="form-group col-sm-3" style="margin:0px; padding: 0px;">
                                <i class="fa fa-twitter-square" aria-hidden="true" style="color: white;"></i>
                                <label for="c_twitter" style="color: white;">E-Mail</label>
                                <input id="c_twitter" type="email" class="form-control class_inputs w-100" style="margin:0px; padding: 10px; text-transform: lowercase;" placeholder="meuperfil">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="form-group col-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                            <button id="btn_salvar_reg" class="btn btn-success" style="border-radius: 10px; width: 100px;" onclick="func_salvar_registro()">Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script language="JavaScript">
            $(document).ready(function() {
                $("#c_cpf").mask("000.000.000-00");
                $("#c_pis").mask("000.00000-0");
                $("#c_dt_nasc").mask("00/00/0000");
                $("#c_celular").mask("(00) 0 0000-0000");
                $("#c_celular_emergencia").mask("(00) 0 0000-0000");
                func_carrega_dados();

            });



            function func_upload_foto() {
                var v_url_arquivo = $("#c_url_arquivo");


                // Captura os dados do formulário
                var formulario = document.getElementById('form_foto');


                // Instância o FormData passando como parâmetro o formulário
                var formData = new FormData(formulario);
                formData.append("url", $("#c_url_arquivo").val())
                formData.append("v_acao", 'UPLOAD_FOTO')
                Swal.fire(
                    'Carregando...',
                    'Sua foto está sendo carregada.',
                    'warning'
                )

                $.ajax({

                    url: '../mod/lib/lib_meus_dados_upload.php',
                    type: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {


                    }

                });

                $("#form_foto").submit();

            }

            function func_carrega_dados() {

                $.ajax({
                    type: "POST",
                    url: "../mod/lib/lib_cad_meus_dados.php",
                    data: {
                        "v_acao": "EV_CARREGA_DADOS"
                    },
                    success: function(data) {


                        $("#c_id_usuario").val(data.Id_usuario);
                        $("#c_id").val(data[0].Id);
                        $("#c_cpf").val(formata_cpf(data[0].Cpf));
                        $("#c_nome").val(data[0].Nome);
                        $("#c_celular").val(data[0].Celular);
                        $("#c_celular_emergencia").val(data[0].Contato_Emergencia);
                        $("#c_sexo").val(data[0].Sexo);
                        $("#c_dt_nasc").val(data[0].dt_nasc);
                        $("#c_linkedin").val(data[0].Linkedin);
                        $("#c_instagram").val(data[0].Instagram);
                        $("#c_faceboook").val(data[0].Facebook);
                        $("#c_twitter").val(data[0].Twitter);
                        $("#c_email").val(data[0].Email);
                        $("#c_email_pessoal").val(data[0].Email_Pessoal);
                        $("#c_url_arquivo").val(data[0].url_arquivo);

                        if (data[0].url_arquivo) {
                            $("#img_foto").attr("src", data[0].url_arquivo);
                        } else {
                            $("#img_foto").attr("src", "https://testephp.s3.amazonaws.com/usuario_padrao.png");
                        }
                        $("#btn_novo_reg").prop("disabled", true);
                        $("#btn_salvar_reg").prop("disabled", false);
                        $("#btn_excluir_reg").prop("disabled", false);

                    },
                    error: function(request, status, erro) {
                        Swal.fire({
                            icon: "error",
                            title: "FALHA!",
                            text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                        })
                    }
                });

            }



            function func_salvar_registro() {

                v_acao = "EV_SALVAR";
                v_id = $("#c_id").val();
                v_celular = $("#c_celular").val();
                v_email_pessoal = $("#c_email_pessoal").val();
                v_contato_emergencia = $("#c_celular_emergencia").val();

                $.ajax({
                    type: "POST",
                    url: "../mod/lib/lib_cad_meus_dados.php",
                    data: {
                        "v_acao": v_acao,
                        "v_id": v_id,
                        "v_celular": v_celular,
                        "v_contato_emergencia": v_contato_emergencia,
                        "v_email_pessoal": v_email_pessoal,
                        "v_linkedin": $("#c_linkedin").val(),
                        "v_faceboook": $("#c_faceboook").val(),
                        "v_instagram": $("#c_instagram").val(),
                        "v_twitter": $("#c_twitter").val()
                    },
                    success: function(data) {
                        var v_json = JSON.parse(data);
                        Swal.fire({
                            icon: v_json.msg_ev,
                            title: v_json.msg_titulo,
                            text: v_json.msg
                        })

                        if (v_json.msg_ev == "success") {
                            func_carrega_dados();
                        }
                    },
                    error: function(request, status, erro) {
                        Swal.fire({
                            icon: "error",
                            title: "FALHA!",
                            text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                        })
                    }
                });
            }

            function formata_cpf(cpf) {
                let cpf_str = cpf.toString()
                let cpf_11_digitos = cpf_str.padStart(11, '0')
                return cpf_11_digitos.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4")
            }
        </script>
</body>





</html>