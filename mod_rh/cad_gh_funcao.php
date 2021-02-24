<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0020") == 0) {
    print('<script> alert(\'Acesso Negado.\'); location.href = \'https://app.agrocontar.com.br\'; </script>');
}

?>

<div class="container">

    <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
        <div class="row">
            <div class="form-group col-sm-6">
                <h3>Lista de Funções</h3>
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
                        <th>Nível</th>
                        <th>Descrição do Nível</th>
                        <th>Descrição da Função</th>
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
                <div class="form-group col-sm-3" style="display: none;">
                    <label for="c_id">id</label>
                    <input disabled id="c_id" type="text" class="form-control class_inputs">
                </div>
                <div class="form-group col-sm-3">
                    <label for="c_nivel">Nível</label>
                    <select id="c_nivel" class="form-control class_inputs"></select>
                </div>
                <div class="form-group col-sm-3">
                    <label for="c_descricao">Descrição da Função</label>
                    <input disabled id="c_descricao_funcao" type="text" class="form-control class_inputs">
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
        func_carrega_tab();
    });


    function func_carrega_tab() {

        $("#c_acao").val("");
        var v_acao = "LISTAR";

        $.ajax({
            type: "POST",
            url: "lib/lib_cad_gh_funcao.php",
            data: {
                "v_acao": v_acao
            },
            success: function(data) {
                
                $("#c_nivel").val("")
                $("#c_descricao_funcao").val("")
                $("#c_nivel").prop('disabled', true)
                $("#c_descricao_funcao").prop('disabled', true)
                $("#btn_novo_reg").prop('disabled', false)
                $("#btn_salvar_reg").prop('disabled', true)
                var options = '';
                $("#tab1b").empty();
                data.funcoes.forEach(element => {
                    options += '<tr  style="cursor: pointer;" onclick="func_select(\'' + element.Nivel + '\', \'' + element.Descricao + '\',\'' + element.Desc_nivel + '\',\'' + element.Id + '\');"><td>' + element.Nivel + '</td><td>' + element.Desc_nivel + '</td><td>' + element.Descricao + '</td></tr>';
                });
                $('#tab1b').html(options);

                options = '<option value="0">SELECIONE UM NÍVEL</option>'
                $("#c_nivel").empty()
                data.niveis.forEach(element => {
                    options += `<option value='${element.Nivel}'>${element.Nivel} - ${element.Descricao}</option>`
                });
                $("#c_nivel").html(options)

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

    function func_select(v_nivel, v_descricao_func, v_desc_nivel, v_id) {

        $("#c_descricao_funcao").prop('disabled', false)
        $("#btn_novo_reg").prop('disabled', true)

        if (v_nivel == 1) {
            $("#c_nivel").prop('disabled', true)
        } else {
            $("#c_nivel").prop('disabled', false)
        }

        $("#c_id").val(v_id)
        $("#c_nivel").val(v_nivel)
        $("#c_descricao_funcao").val(v_descricao_func)
        $("#btn_salvar_reg").prop("disabled", false);

    }

    function func_salvar_registro() {

        let v_acao = $("#c_acao").val();
        if (v_acao != "EV_NOVO") {
            v_acao = "EV_SALVAR";
        }
        v_nivel = $("#c_nivel").val()
        v_descricao = $("#c_descricao_funcao").val()

        if (v_nivel.length != 0 && v_descricao.length > 5) {
            $.ajax({
                type: "POST",
                url: "lib/lib_cad_gh_funcao.php",
                data: {
                    "v_acao": v_acao,
                    "v_nivel": v_nivel,
                    "v_descricao": v_descricao,
                    "v_id": $("#c_id").val()
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
        $("#c_descricao_funcao").prop("disabled", false);


        $("#btn_novo_reg").prop("disabled", true);
        $("#btn_salvar_reg").prop("disabled", false);
        $("#btn_excluir_reg").prop("disabled", true);

    }
    
</script>