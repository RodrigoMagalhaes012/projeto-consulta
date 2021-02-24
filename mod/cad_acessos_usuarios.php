<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0009") == 0) {
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
        <div id="tabela_inicial">
            <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <h3>Acessos de Usuários</h3>
                    </div>
                </div>
            </div>
            <div id="box_tab1" class="row" style="height: auto; background-color: white; border: none; overflow-x: hidden;">
                <input id="c_acao" type="hidden" value="">
                <div class="box-body">
                    <table id="tab_usuarios" class="table" style="color: black; width: 100%;">
                        <thead style="font-weight: bold;">
                            <tr>
                                <th>Id</th>
                                <th>Usuário</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="corpo_tab_usuarios" style="font-weight: normal;">
    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="selecao_grupos" style="display: none;">
            <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <h3>Liberação de Telas</h3>
                    </div>
                    <div class="form-group col-sm-6">
                        <select style="color: black;" onchange="func_ver_telas_grupo()" id="c_select_grupo" class="form-control class_inputs">
                            
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div style="color: black;" class="col-sm-12">
                    <select  multiple="multiple" size="10" id="duallistbox">
                    </select>
                </div>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="form-group col-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                        <button id="btn_salvar_reg" class="btn btn-success" style="border-radius: 10px; width: 100px;" onclick="func_salvar_acessos()">Salvar</button>
                        <!-- <button disabled id="btn_excluir_reg" class="btn btn-danger" style="border-radius: 10px; width: 100px;" onclick="func_excluir_registro()">Excluir</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


<script src="../class/DataTables/datatables.min.js"></script>
<script src="../class/dual-listbox/src/jquery.bootstrap-duallistbox.js"></script>
<script language="JavaScript">
    $(document).ready(function() {
        func_carrega_tab();
        $("#c_tab_busca_texto").mask("0000000000");
    });

    function func_carrega_tab() {

        $("#c_acao").val("");
        var v_acao = "LISTAR_USUARIOS";

        $("#c_nome").prop("disabled", true);
        $("#c_descricao").prop("disabled", true);

        $.ajax({
            type: "POST",
            url: "../mod/lib/lib_cad_acessos_usuarios.php",
            data: {
                "v_acao": v_acao
            },
            success: function(data) {
                $('#tab_usuarios').DataTable().destroy();
                let options = ''
                $("#corpo_tab_usuarios").empty()
                data.forEach(element => {
                    options += `
                        <tr>
                            <td>${element.Id}</td>
                            <td>${element.Nome}</td>
                            <td>
                                <div class="btn-group" style="border: 0px; margin: 0px;">
                                <button onclick="func_abre_acessos('${element.Id}');"
                                class="btn is-icon btn-outline-primary" title="Gerenciar Acessos">
                                        <span class="button-text">
                                            <i class="fa fa-object-ungroup fa-1x"></i>
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `
                });

                $("#corpo_tab_usuarios").html(options)

                $("#tab_usuarios").DataTable({
                    "language": {
                        "url": "../class/DataTables/portugues.json",
                    },
                    "columnDefs": [{
                        "width": "15%",
                        "targets": 2,
                    }],
                    "lengthMenu": [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "Todos"]
                    ],
                    "order": [
                        [1, "asc"]
                    ],
                    "scrollY": "50vh",
                    "scrollX": true,
                    "scrollCollapse": true,
                    "paging": true
                });
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

    function func_abre_acessos(v_id){

        $.ajax({
            type: "POST",
            url: "../mod/lib/lib_cad_acessos_usuarios.php",
            data: {
                "v_acao": 'EV_CARREGA_ACESSOS'
            },
            success: function(data) {

                localStorage.setItem('id_usuario', v_id)

                let options = ''
                $("#c_select_grupo").empty()
                data.grupos.forEach(element => {
                    options += `<option value="${element.id_grupo}">${element.nome}</option>`
                });
                $("#c_select_grupo").html(options)
                func_ver_telas_grupo()
                
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

    function func_ver_telas_grupo(){
        $.ajax({
            type: "POST",
            url: "../mod/lib/lib_cad_acessos_usuarios.php",
            data: {
                "v_acao": 'EV_CARREGA_TELAS_GRUPO',
                "v_id_grupo": $("#c_select_grupo").val(),
                "v_id_usuario": localStorage.getItem('id_usuario')
            },
            success: function(data) {

                let vet_telas_usuario = []

                data.grupos_usuario.forEach(element => {
                    vet_telas_usuario.push(element.id_grupo_telas)
                });

                let options = ''
                $('#duallistbox').empty()
                data.grupos.forEach(element => {
                    options += `<option ${vet_telas_usuario.indexOf(element.id_grupo) > -1 ? 'selected' : ''}  value="${element.id_grupo}">${element.nome}</option>`
                })
                $("#duallistbox").html(options)

                $('#duallistbox').bootstrapDualListbox();

                $('#duallistbox').bootstrapDualListbox('refresh', true);

                $("#tabela_inicial").hide()
                $("#selecao_grupos").show()
                
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

    function func_salvar_acessos() {

        $.ajax({
            type: "POST",
            url: "../mod/lib/lib_cad_acessos_usuarios.php",
            data: {
                "v_acao": 'EV_SALVA_ACESSOS',
                "v_id_visibilidade": $("#c_select_grupo").val(),
                "v_grupos": $('#duallistbox').val(),
                "v_id_usuario": localStorage.getItem('id_usuario')
            },
            success: function(data) {

                // console.log(data)

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
    }

</script>
</html>