<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0013") == 0) {
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
                    <h3>Políticas de Senha</h3>
                </div>
            </div>
        </div>
        <div id="box_tab1" class="box" style="height: auto; max-height: 300px; background-color: white; border: none; overflow-x: hidden;">
            <div class="row">
                <div class="form-group col-sm-7">
                    <h4 style="margin-left: 30px;">Após quantas tentativas o sistema deve bloquear o usuário ?:</h4>
                </div>
                <div class="form-group col-sm-2">
                    <select id="c_num_tentativas" class="form-control class_inputs">
                        <option value="0" selected>NUNCA</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="box_tab1" class="box" style="height: auto; max-height: 300px; background-color: white; border: none; overflow-x: hidden;">
            <div class="row">
                <div class="form-group col-sm-7">
                    <h4 style="margin-left: 30px;">Após quantos minutos o histórico de tentativas deve ser zerado ?</h4>
                </div>
                <div class="form-group col-sm-2">
                    <select id="c_minut_del_bed_hist" class="form-control class_inputs">
                        <option value="0" selected>NUNCA</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="60">60</option>
                        <option value="120">120</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="box_tab1" class="box" style="height: auto; max-height: 300px; background-color: white; border: none; overflow-x: hidden;">
            <div class="row">
                <div class="form-group col-sm-7">
                    <h4 style="margin-left: 30px;">Depois de quantos dias sem logar o usuário deve ser bloqueado ?</h4>
                </div>
                <div class="form-group col-sm-2">
                    <select id="c_num_dias_bloq" class="form-control class_inputs">
                        <option value="0" selected>NUNCA</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="60">60</option>
                        <option value="90">90</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="box_tab1" class="box" style="height: auto; max-height: 300px; background-color: white; border: none; overflow-x: hidden;">
            <div class="row">
                <div class="form-group col-sm-7">
                    <h4 style="margin-left: 30px;">Quantos dias deve ter a validade de uma senha ?</h4>
                </div>
                <div class="form-group col-sm-2">
                    <select id="c_dias_validade" class="form-control class_inputs">
                        <option value="0" selected>NUNCA</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="60">60</option>
                        <option value="90">90</option>
                        <option value="120">120</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="box_tab1" class="box" style="height: auto; max-height: 300px; background-color: white; border: none; overflow-x: hidden;">
            <div class="row">
                <div class="form-group col-sm-7">
                    <h4 style="margin-left: 30px;">Qual deve ser a quantidade mínima de caracteres da senha ?</h4>
                </div>
                <div class="form-group col-sm-2">
                    <select id="c_min_caract" class="form-control class_inputs">
                        <option value="5" selected>5</option>
                        <option value="8">8</option>
                        <option value="10">10</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="box_tab1" class="box" style="height: auto; max-height: 300px; background-color: white; border: none; overflow-x: hidden;">
            <div class="row">
                <div class="form-group col-sm-7">
                    <h4 style="margin-left: 30px;">Qual deve ser a quantidade máxima de caracteres da senha ?</h4>
                </div>
                <div class="form-group col-sm-2">
                    <select id="c_max_caract" class="form-control class_inputs">
                        <option value="5">5</option>
                        <option value="8" selected>8</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="box_tab1" class="box" style="height: auto; max-height: 300px; background-color: white; border: none; overflow-x: hidden;">
            <div class="row">
                <div class="form-group col-sm-7">
                    <h4 style="margin-left: 30px;">O usuário pode reutilizar uma das ultimas 3 senhas ?</h4>
                </div>
                <div class="form-group col-sm-2">
                    <select id="c_senha_reutil" class="form-control class_inputs">
                        <option value="S">SIM</option>
                        <option value="N" selected>NÃO</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="box_tab1" class="box" style="height: auto; max-height: 300px; background-color: white; border: none; overflow-x: hidden;">
            <div class="row">
                <div class="form-group col-sm-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                    <button id="btn_salvar_reg" class="btn btn-success" style="border-radius: 10px; width: 100px;" onclick="func_salvar();">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</body>



<script language="JavaScript">
    $(document).ready(function() {

    });



    function func_salvar() {

        var v_acao = "EV_SALVAR";
        var v_num_tentativas = $("#c_num_tentativas").val();
        var v_minut_del_bed_hist = $("#c_minut_del_bed_hist").val();
        var v_num_dias_bloq = $("#c_num_dias_bloq").val();
        var v_dias_validade = $("#c_dias_validade").val();
        var v_min_caract = $("#c_min_caract").val();
        var v_max_caract = $("#c_max_caract").val();
        var v_senha_reutil = $("#c_senha_reutil").val();


        $.ajax({
            type: "POST",
            url: "../mod/lib/lib_cfg_senhas.php",
            data: {
                "v_acao": v_acao,
                "v_num_tentativas": v_num_tentativas,
                "v_minut_del_bed_hist": v_minut_del_bed_hist,
                "v_num_dias_bloq": v_num_dias_bloq,
                "v_dias_validade": v_dias_validade,
                "v_min_caract": v_min_caract,
                "v_max_caract": v_max_caract,
                "v_senha_reutil": v_senha_reutil
            },
            success: function(data) {
                var v_json = JSON.parse(data);
                Swal.fire({
                    icon: v_json.msg_ev,
                    title: v_json.msg_titulo,
                    text: v_json.msg
                })
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
</script>



</html>