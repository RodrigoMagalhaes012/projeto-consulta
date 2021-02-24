<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0021") == 0) {
    print('<script> alert(\'Acesso Negado.\'); location.href = \'https://app.agrocontar.com.br\'; </script>');
}

?>



<div class="container">

    <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
        <div class="row">
            <div class="form-group col-sm-6">
                <h3>Lista de Níveis</h3>
            </div>
            <div class="form-group col-sm-6">
                <select onchange="func_carrega_tab();" id="c_select_grupo" class="form-control class_inputs">

                </select>
            </div>
        </div>
    </div>
    <div id="box_tab1" class="box" style="height: auto; max-height: 300px; background-color: white; border: none; overflow-x: hidden;">
        <input id="c_acao" type="hidden" value="">
        <div class="box-body">
            <input type="hidden" id="vf_tab_sql_limit_in" value="0">
            <input type="hidden" id="vf_tab_btn_pag_select" value="1">

            <table id="tab1" class="table">
                <thead style="font-weight: bold;">
                    <tr>
                        <th style="display: none;">ID</th>
                        <th>Nível</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody id="tab1b" style="font-weight: normal;">
                </tbody>
            </table>
        </div>
    </div>

    <div id="box_form_titulo" class="box" style="height: 60px; margin-top: 40px; background-color: white; border: none; overflow: hidden;">
        <div class="row">
            <div class="form-group col-sm-12">
                <h3>Formulário de Cadastro</h3>
            </div>
        </div>
    </div>
    <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">
        <div class="box-body" style="height: auto;">
            <div class="row">
                <div class="form-group col-sm-3">
                    <label for="c_nivel">Nível</label>
                    <input disabled id="c_nivel" type="text" class="form-control class_inputs">
                </div>
                <div class="form-group col-sm-3">
                    <label for="c_descricao">Descrição</label>
                    <input disabled id="c_descricao" type="text" class="form-control class_inputs">
                </div>
            </div>
        </div>
        <div class="box-footer">
            <div class="row">
                <div class="form-group col-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                    <button id="btn_novo_reg" class="btn btn-primary" style="border-radius: 10px; width: 100px;" onclick="func_novo_registro()">Novo</button>
                    <button disabled id="btn_salvar_reg" class="btn btn-success" style="border-radius: 10px; width: 100px;" onclick="func_salvar_registro()">Salvar</button>
                    <button disabled id="btn_excluir_reg" class="btn btn-danger" style="border-radius: 10px; width: 100px;" onclick="func_excluir_registro()">Excluir</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script language="JavaScript">
    $(document).ready(function() {
        func_carrega_grupo()
    });

    function func_carrega_grupo(){
        $.ajax({
            type: "POST",
            url: "lib/lib_cad_gh_nivel.php",
            data: {
                "v_acao": "EV_CARREGA_GRUPO"
            },
            success: function(data) {
                let options = '<option value="0">SELECIONE UM GRUPO</option>'
                $("#c_select_grupo").empty()
                data.forEach(element => {
                    options += `
                        <option value="${element.id_grupo}">${element.nome}</option>
                    `
                });
                $("#c_select_grupo").html(options)
                if(data.length > 0){
                    $("#c_select_grupo").prop('selectedIndex', 1)
                    func_carrega_tab();
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

    function func_carrega_tab() {

        $("#c_acao").val("");
        var v_acao = "LISTAR";

        localStorage.setItem('grupo_gh', $("#c_select_grupo").val())

        $.ajax({
            type: "POST",
            url: "lib/lib_cad_gh_nivel.php",
            data: {
                "v_acao": v_acao,
                "v_grupo": $("#c_select_grupo").val()
            },
            success: function(data) {
                $("#c_nivel").val("")
                $("#c_descricao").val("")
                $("#c_nivel").prop('disabled', true)
                $("#c_descricao").prop('disabled', true)
                $("#btn_novo_reg").prop('disabled', false)
                $("#btn_salvar_reg").prop('disabled', true)
                var options = '';
                let menu_options = `
                        <li><a onclick="$('#div_tela').load('cad_gh_nivel.php');">Grupos e Níveis</a></li>
                        <li><a onclick="$('#div_tela').load('hist_gh.php');">Gestões finalizadas</a></li>
                        <li><a onclick="$('#div_tela').load('gh_atual.php');">Resumo gestão atual</a></li>
                `
                
                $("#tab1b").empty();
                data.forEach(element => {
                    options += '<tr style="cursor: pointer;" onclick="func_select(\'' + element.Nivel + '\', \'' + element.Descricao + '\');"><td>' + element.Nivel + '</td><td>' + element.Descricao + '</td></tr>';
                    if(element.Nivel != 0){
                        menu_options += `
                            <li><a onclick="$('#div_tela').load('cad_gh.php'); localStorage.setItem('nivel', ${element.Nivel});  localStorage.setItem('desc_nivel', '${element.Descricao}');">${element.Descricao}</a></li>
                        `
                    }
                });
                $('#tab1b').html(options);

                $("#menu_gh").empty()
                $("#menu_gh").html(menu_options)
                
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

    function func_select(v_nivel, v_descricao) {

        $("#c_descricao").prop('disabled', false)
        $("#btn_novo_reg").prop('disabled', true)

        if (v_nivel == 0) {
            $("#c_nivel").prop('disabled', true)
        } else {
            $("#c_nivel").prop('disabled', false)
        }

        $("#c_nivel").val(v_nivel)
        $("#c_descricao").val(v_descricao)
        $("#btn_salvar_reg").prop("disabled", false);

    }

    function func_salvar_registro() {

        let v_acao = $("#c_acao").val();
        if (v_acao != "EV_NOVO") {
            v_acao = "EV_SALVAR";
        }
        v_nivel = $("#c_nivel").val()
        v_descricao = $("#c_descricao").val()

        if (v_nivel.length > 0 && v_descricao.length > 5) {
            $.ajax({
                type: "POST",
                url: "lib/lib_cad_gh_nivel.php",
                data: {
                    "v_acao": v_acao,
                    "v_nivel": v_nivel,
                    "v_descricao": v_descricao,
                    "v_grupo": $("#c_select_grupo").val()
                },
                success: function(data) {
                    var v_json = JSON.parse(data);
                    Swal.fire({
                        icon: v_json.msg_ev,
                        title: v_json.msg_titulo,
                        text: v_json.msg
                    })

                    if (v_json.msg_ev == "success") {
                        func_carrega_tab();
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
        } else {

            Swal.fire({
                icon: "error",
                title: "FALHA!",
                text: "Preencha todos os campos."
            })

        }
    }

    function func_novo_registro() {

        $("#c_acao").val("EV_NOVO");
        $("#c_nivel").prop("disabled", false);
        $("#c_descricao").prop("disabled", false);


        $("#c_nivel").val("");
        $("#c_descricao").val("");


        $("#btn_novo_reg").prop("disabled", true);
        $("#btn_salvar_reg").prop("disabled", false);
        $("#btn_excluir_reg").prop("disabled", true);

    }
    
</script>