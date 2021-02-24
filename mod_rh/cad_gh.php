<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0023") == 0) {
    print('<script> alert(\'Acesso Negado.\'); location.href = \'https://app.agrocontar.com.br\'; </script>');
}

?>



<div class="container" style="margin-top: 50px; padding: 30px;">
    <div id="tabela_resultados" >
        <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-6">
                    <h3 id="c_titulo"></h3>
                </div>
            </div>
        </div>
        <div id="box_tab1" class="row" style="padding: 10px; border-width: 1px; border-style: solid;">
            <input id="c_acao" type="hidden" value="">
            <div class="col-sm-12">

                <table id="tab1" class="table" style="width: 100%; color: black;">
                    <thead style="font-weight: bold;">
                        <tr>
                            <th>Id</th>
                            <th>Nível</th>
                            <th>Função</th>
                            <th>Colaborador</th>
                            <th>Id Lider</th>
                            <th>Função Lider</th>
                            <th>Colaborador Lider</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tab1b" style="font-weight: normal;">

                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-footer">
            <div class="row">
                <div class="form-group col-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                    <button id="btn_novo_reg" class="btn btn-primary" style="border-radius: 10px; width: 100px;" onclick="func_novo_registro()">Novo</button>
                    <!-- <button disabled id="btn_excluir_reg" class="btn btn-danger" style="border-radius: 10px; width: 100px;" onclick="func_excluir_registro()">Excluir</button> -->
                </div>
            </div>
        </div>
    </div>

    
    <div id="form_cadastro" style="display: none;">
        <div id="box_form_titulo" class="box" style="height: 60px; margin-top: 40px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-12">
                    <h3 id="titulo_formulario"></h3>
                </div>
            </div>
        </div>
        <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">
            <div class="box-body" style="height: auto;">
                <div class="row">
                    <div style="display: none;" class="form-group col-sm-1">
                        <label for="c_id">Id</label>
                        <input disabled id="c_id" type="text" class="form-control class_inputs">
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="c_gh_nome">Nome do GH</label>
                        <input disabled id="c_gh_nome" type="text" class="form-control class_inputs">
                    </div>
                    <div id="select_gestor" class="form-group col-sm-5">
                        <label for="c_id_user_gestor">Nome do Gestor</label>
                        <select disabled id="c_id_user_gestor" class="form-control class_inputs">
    
                        </select>
                    </div>
                    <div id="data_inicio" style="display: none;" class="form-group col-sm-3">
                        <label for="c_data_inicio">Inicio da Gestão</label>
                        <input id="c_data_inicio" type="date" class="form-control class_inputs" placeholder="00/00/0000">
                    </div>
                    <div style="display: none;" id="text_gestor" class="form-group col-sm-8">
                        <label for="c_user_gestor">Nome do Gestor</label>
                        <input disabled id="c_user_gestor" type="text" class="form-control class_inputs">
                    </div>
                </div>
                <div class="row">
                    <div id="select_lider">
                        <div class="form-group col-sm-12">
                            <label for="c_id_gh_lider">Lider</label>
                            <select disabled id="c_id_gh_lider" class="form-control class_inputs" onchange="func_busca_liderados(this.value)">
        
                            </select>
                        </div>
                    </div>
                    <div style="display: none;" id="text_lider" class="form-group col-sm-4">
                        <label for="c_gh_lider">Função Lider</label>
                        <input disabled id="c_gh_lider" type="text" class="form-control class_inputs">
                    </div>
                    <div style="display: none;" id="text_id_lider" class="form-group col-sm-1">
                        <label for="c_gh_id_lider">Id Lider</label>
                        <input disabled id="c_gh_id_lider" type="text" class="form-control class_inputs">
                    </div>
                    <div style="display: none;" id="text_colab_lider" class="form-group col-sm-8">
                        <label for="c_gh_colab_lider">Colaborador Lider</label>
                        <input disabled id="c_gh_colab_lider" type="text" class="form-control class_inputs">
                    </div>
                </div>
                <div class="row" id="novo_gestor">
                    <div style="display: none;" id="data_nova_lid" class="form-group col-sm-4">
                        <label for="c_data_finalizacao">Inicio Nova Liderança</label>
                        <input id="c_data_nova_lideranca" type="date" class="form-control class_inputs" placeholder="00/00/0000">
                    </div>
                    <div style="display: none;" id="select_lider_atualiza" class="form-group col-sm-8">
                        <label for="c_id_gh_lider_atualiza">Lider</label>
                        <select id="c_id_gh_lider_atualiza" class="form-control class_inputs"">
    
                        </select>
                    </div>
                    <div style="display: none;" id="data_finalizacao" class="form-group col-sm-4">
                        <label for="c_data_finalizacao">Finalização da Gestão</label>
                        <input id="c_data_finalizacao" type="date" class="form-control class_inputs" placeholder="00/00/0000">
                    </div>
                    <div style="display: none;" id="select_n_gestor" class="form-group col-sm-8">
                        <label for="c_novo_gestor">Novo Gestor</label>
                        <select id="c_novo_gestor" class="form-control class_inputs">
    
                        </select>
                    </div>
                </div>
                <div id="gestoes_lideradas"  class="row" style="display: none;">
                    <div class="row">
                        <div class="col-sm-12">
                            <h5>Gestões que serão lideradas.</h5>
                        </div>
                    </div>
                    <div class="row" id="check_liderados">
                        <!-- <div class="col-sm-3 form-group" >
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="gridCheck">
                                    <label class="form-check-label" for="gridCheck">  Check me out</label>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-6 ">
                        <button disabled id="btn_finaliza_reg" class="btn btn-warning" style="border-radius: 10px;" onclick="func_finaliza_gestao()">Finalizar Gestão</button>
                        <button disabled id="btn_atualiza_lider" class="btn btn-info" style="border-radius: 10px;" onclick="func_atualiza_lider()">Atualizar Lider</button>
                        <!-- <button disabled id="btn_excluir_reg" class="btn btn-danger" style="border-radius: 10px; width: 100px;" onclick="func_excluir_registro()">Excluir</button> -->
                    </div>
                    <div class="form-group col-sm-6 text-right">
                        <button disabled id="btn_salvar_reg" class="btn btn-success" style="border-radius: 10px;" onclick="func_salvar_registro()">Salvar</button>
                        <!-- <button disabled id="btn_excluir_reg" class="btn btn-danger" style="border-radius: 10px; width: 100px;" onclick="func_excluir_registro()">Excluir</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../class/DataTables/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        func_carrega_gh(localStorage.getItem('nivel'), localStorage.getItem('desc_nivel'))
        func_lista_gh_lider(localStorage.getItem('nivel'));
        func_lista_gestores();
    });

    function func_finaliza_gestao(){
        
        if($("#data_finalizacao").css('display') == 'none'){
            $("#c_acao").val("EV_FINALIZA_GESTAO")
            $("#data_finalizacao").show()
            $("#select_n_gestor").show()
            $("#data_nova_lid").hide()
            $("#select_lider_atualiza").hide()
            $("#btn_salvar_reg").prop("disabled", false);
        }else{
            $("#c_acao").val("EV_FINALIZA_GESTAO")
            $("#data_finalizacao").hide()
            $("#select_n_gestor").hide()
            $("#btn_salvar_reg").prop("disabled", true);
        }
    }

    function func_atualiza_lider(){

        if($("#data_nova_lid").css('display') == 'none'){
            $("#c_acao").val("EV_ATUALIZA_LIDER")
            $("#data_finalizacao").hide()
            $("#select_n_gestor").hide()
            $("#data_nova_lid").show()
            $("#select_lider_atualiza").show()
            $("#btn_salvar_reg").prop("disabled", false);
        }else{
            $("#c_acao").val("EV_FINALIZA_GESTAO")
            $("#data_nova_lid").hide()
            $("#select_lider_atualiza").hide()
            $("#btn_salvar_reg").prop("disabled", true);
        }
    }

    function func_salva_novo_lider(){

        let v_id_lider = $("#c_id_gh_lider_atualiza").val()
        let v_id = $("#c_id").val();

        if (v_id_lider != 0) {
            $.ajax({
                type: "POST",
                url: "lib/lib_cad_gh.php",
                data: {
                    "v_acao": "EV_ATUALIZA_LIDER",
                    "v_id": v_id,
                    "v_id_lider": v_id_lider,
                    "v_grupo": localStorage.getItem('grupo_gh')
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
            })
        }else{
            Swal.fire({
                icon: "error",
                title: "FALHA!",
                text: "Preencha o novo lider."
            })
        }
    }

    function func_salvar_registro(){

        v_acao = $("#c_acao").val();

        let v_id = $("#c_id").val();
        let v_gh_nome = $("#c_gh_nome").val();
        let v_id_user_gestor = $("#c_id_user_gestor").val();
        let v_id_gh_lider = $("#c_id_gh_lider").val();
        let v_data_finalizacao = $("#c_data_finalizacao").val()
        let v_data_inicio = $("#c_data_inicio").val()

        if(v_acao == 'EV_FINALIZA_GESTAO'){
            if (v_data_finalizacao.length > 0) {
                $.ajax({
                    type: "POST",
                    url: "lib/lib_cad_gh.php",
                    data: {
                        "v_acao": v_acao,
                        "v_id": v_id,
                        "v_data_finalizacao": v_data_finalizacao,
                        "v_novo_gestor": $("#c_novo_gestor").val(),
                        "v_id_lider": $("#c_gh_id_lider").val(),
                        "v_id_nivel": localStorage.getItem('nivel'),
                        "v_gh_nome": v_gh_nome,
                        "v_grupo": localStorage.getItem('grupo_gh')
                    },
                    success: function(data) {
                        var v_json = JSON.parse(data);
                        Swal.fire({
                            icon: v_json.msg_ev,
                            title: v_json.msg_titulo,
                            text: v_json.msg
                        })

                        if (v_json.msg_ev == "success") {
                            func_carrega_gh(localStorage.getItem('nivel'), localStorage.getItem('desc_nivel'))
                            $("#c_novo_gestor").val("0")
                            $("#c_data_finalizacao").val("")
                            $("#select_n_gestor").hide()
                            $("#data_finalizacao").hide()
                            $("#tabela_resultados").show()
                            $("#form_cadastro").hide()
                        }
                    },
                    error: function(request, status, erro) {
                        Swal.fire({
                            icon: "error",
                            title: "FALHA!",
                            text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                        })
                    }
                })
            }else{
                Swal.fire({
                    icon: "error",
                    title: "FALHA!",
                    text: "Preencha a data de finalização."
                })
            }
        } else if(v_acao == 'EV_ATUALIZA_LIDER'){
            let v_id_lider = $("#c_id_gh_lider_atualiza").val()

            if (v_id_lider != 0 && $("#c_data_nova_lideranca").val().length > 0) {
                $.ajax({
                    type: "POST",
                    url: "lib/lib_cad_gh.php",
                    data: {
                        "v_acao": "EV_ATUALIZA_LIDER",
                        "v_id": v_id,
                        "v_id_lider": v_id_lider,
                        "v_data_nova_lideranca": $("#c_data_nova_lideranca").val(),
                        "v_grupo": localStorage.getItem('grupo_gh')
                    },
                    success: function(data) {
                        var v_json = JSON.parse(data);
                        Swal.fire({
                            icon: v_json.msg_ev,
                            title: v_json.msg_titulo,
                            text: v_json.msg
                        })
                        if (v_json.msg_ev == "success") {
                            func_carrega_gh(localStorage.getItem('nivel'), localStorage.getItem('desc_nivel'));
                            $("#c_id_gh_lider_atualiza").val("0")
                            $("#c_data_nova_lideranca").val("")
                            $("#select_lider_atualiza").hide()
                            $("#data_nova_lid").hide()
                            $("#tabela_resultados").show()
                            $("#form_cadastro").hide()
                        }
                    },
                    error: function(request, status, erro) {
                        Swal.fire({
                            icon: "error",
                            title: "FALHA!",
                            text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                        })
                    }
                })
            }else{
                Swal.fire({
                    icon: "error",
                    title: "FALHA!",
                    text: "Preencha todos os campos."
                })
            }
        }else{
            
            let check = document.getElementsByClassName('form-check-input')
            let liderados = []
            for(let i=0 ; i<check.length ; i++ ){
                if(check[i].checked){
                    liderados.push(check[i].value);
                }
            }
            
            if (v_gh_nome.length >= 2 && v_id_user_gestor > 0) {

                $.ajax({
                    type: "POST",
                    url: "lib/lib_cad_gh.php",
                    data: {
                        "v_acao": v_acao,
                        "v_id": v_id,
                        "v_gh_nome": v_gh_nome,
                        "v_id_user_gestor": v_id_user_gestor,
                        "v_id_gh_lider": v_id_gh_lider,
                        "v_id_nivel": localStorage.getItem('nivel'),
                        "v_id_liderados": liderados.length > 0 ? liderados : lider = ['null'],
                        "v_data_inicio": v_data_inicio,
                        "v_grupo": localStorage.getItem('grupo_gh')
                    },
                    success: function(data) {
                        var v_json = JSON.parse(data);
                        Swal.fire({
                            icon: v_json.msg_ev,
                            title: v_json.msg_titulo,
                            text: v_json.msg
                        })

                        if (v_json.msg_ev == "success") {
                            func_carrega_gh(localStorage.getItem('nivel'), localStorage.getItem('desc_nivel'));
                            $("#tabela_resultados").show()
                            $("#form_cadastro").hide()
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
    }

    function func_carrega_gh(v_nivel, v_desc_nivel) {

        $("#c_titulo").html(v_desc_nivel)

        $.ajax({
            type: "POST",
            url: "lib/lib_cad_gh.php",
            dataType: 'json',
            data: {
                "v_acao": "EV_CARREGA_GH",
                "v_nivel": v_nivel,
                "v_grupo": localStorage.getItem('grupo_gh')
            },
            success: function(data) {
                let dados = ''
                $('#tab1').DataTable().destroy();
                $("#tab1b").empty()
                data.forEach(element => {
                    dados += `
                        <tr style="cursor: pointer;" id="${element.Id}">
                            <td onclick="func_select(${element.Id})">${element.Id}</td>
                            <td onclick="func_select(${element.Id})">${element.Nivel}</td>
                            <td onclick="func_select(${element.Id})">${element.Nome}</td>
                            <td onclick="func_select(${element.Id})">${element.Colaborador}</td>
                            <td onclick="func_select(${element.Id})">${element.Id_lider}</td>
                            <td onclick="func_select(${element.Id})">${element.Lider}</td>
                            <td onclick="func_select(${element.Id})">${element.Colaborador_lider ? element.Colaborador_lider : '-'}</td>
                            </td><td style="text-align: center;">
                                <div class="btn-group" style="border: 0px; margin: 0px;">
                                    <button
                                    class="btn is-icon btn-outline-primary" title="Visualizar colaboradores da gestão">
                                        <span class="button-text">
                                            <i class="fa fa-search fa-1x"></i>
                                        </span>
                                    </button>
                                    <button  
                                    id="btn_novo_download" class="btn is-icon btn-outline-primary" title="Finalizar gestão">
                                        <span class="button-text">
                                            <i class="fa fa-times fa-1x"></i>
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>`
                });
                $("#tab1b").html(dados)
               
                $("#tab1").DataTable({
                    "language": {
                        "url": "../class/DataTables/portugues.json",
                    },
                    "columnDefs": [{
                        "width": "35%",
                        "targets": 6,
                    }],
                    "lengthMenu": [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "Todos"]
                    ],
                    "order": [
                        [2, "asc"]
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
        })
    }

    function func_select(v_id) {

        let dados = document.getElementById(v_id)

        $("#titulo_formulario").html(`Formulário de Atualização - ${localStorage.getItem('desc_nivel')}`)

        $("#c_acao").val("EV_FINALIZA_GESTAO");
        $("#c_gh_nome").prop("disabled", true);

        $("#select_lider").hide()
        $("#select_gestor").hide()
        $("#data_inicio").hide()
        $("#btn_salvar_reg").show()
        $("#btn_finaliza_reg").show()
        $("#btn_atualiza_lider").show()
        $("#btn_salvar_reg").show()
        $("#text_gestor").show()
        
        $("#text_lider").show()
        $("#text_colab_lider").show()
        // $("#text_id_lider").show()
        // $("#select_lider_atualiza").show()
        $("#gestoes_lideradas").hide()

        $("#c_id").val(dados.children[0].innerHTML);
        $("#c_gh_nome").val(dados.children[2].innerHTML);
        $("#c_user_gestor").val(dados.children[3].innerHTML);
        $("#c_gh_id_lider").val(dados.children[4].innerHTML);
        $("#c_gh_lider").val(dados.children[5].innerHTML);
        $("#c_id_gh_lider_atualiza").val(dados.children[4].innerHTML);
        $("#c_gh_colab_lider").val(dados.children[6].innerHTML);

        $("#btn_novo_reg").prop("disabled", false);
        $("#btn_finaliza_reg").prop("disabled", false);
        $("#btn_excluir_reg").prop("disabled", false);
        $("#btn_atualiza_lider").prop('disabled', false)

        $("#tabela_resultados").hide()
        $("#form_cadastro").show()

    }

    function func_lista_gh_lider(v_nivel) {
        // CARREGANDO A LISTA DE GH LIDER
        $.ajax({
            type: "POST",
            url: "lib/lib_cad_gh.php",
            data: {
                "v_acao": "LISTA_GH_LIDER",
                "v_nivel": v_nivel,
                "v_grupo": localStorage.getItem('grupo_gh')
            },
            success: function(data) {
                var options = '<option value="0">SELECIONE UM GH LIDER</option>';
                $("#c_id_gh_lider").empty();
                for (v_index = 0; v_index < data.length; v_index++) {
                    options += '<option value="' + data[v_index].Id + '">' + data[v_index].Nome + '</option>';
                }
                $('#c_id_gh_lider').html(options);

                var options = '<option value="0">SELECIONE UM GH LIDER</option>';
                $("#c_id_gh_lider_atualiza").empty();
                for (v_index = 0; v_index < data.length; v_index++) {
                    options += '<option value="' + data[v_index].Id + '">' + data[v_index].Nome + '</option>';
                }
                $('#c_id_gh_lider_atualiza').html(options);
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

    function func_lista_gestores() {
        $.ajax({
            type: "POST",
            url: "lib/lib_cad_gh.php",
            data: {
                "v_acao": "LISTA_GESTORES",
                "v_grupo": localStorage.getItem('grupo_gh')
            },
            success: function(data) {
                var options = '<option value="0">SELECIONE UM GESTOR</option>';
                $("#c_id_user_gestor").empty();
                for (v_index = 0; v_index < data.length; v_index++) {
                    options += '<option value="' + data[v_index].Id_usuario + '">' + data[v_index].Nome + '</option>';
                }
                $('#c_id_user_gestor').html(options);
                $("#c_novo_gestor").html(options)
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

    function func_busca_liderados(v_id){
        $.ajax({
            type: "POST",
            url: "lib/lib_cad_gh.php",
            data: {
                "v_acao": "LISTA_LIDERADOS",
                "v_id": v_id,
                "v_nivel": localStorage.getItem('nivel'),
                "v_grupo": localStorage.getItem('grupo_gh')
            },
            success: function(data) {
                if(data.length > 0){
                    $("#gestoes_lideradas").show()
                }
                let check = ''
                data.forEach(element => {
                    check += `
                        <div class="col-sm-3 form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="gridCheck" value="${element.Id}">
                                <label class="form-check-label" for="gridCheck">${element.Nome}</label>
                            </div>
                        </div>
                    `
                });
                $("#check_liderados").html(check)
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

    function func_novo_registro() {
        $("#titulo_formulario").html(`Formulário de Cadastro - ${localStorage.getItem('desc_nivel')}`)
        $("#btn_salvar_reg").show()
        $("#btn_atualiza_lider").hide()
        $("#btn_finaliza_reg").hide()
        $("#tabela_resultados").hide()
        $("#form_cadastro").show()
        $("#data_inicio").show()
        $("#select_lider").show()
        $("#c_gh_nome").show()
        $("#select_gestor").show()
        $("#c_acao").val("EV_NOVO");
        $("#c_gh_nome").prop("disabled", false);
        $("#c_id_user_gestor").prop("disabled", false);
        $("#c_id_gh_lider").prop("disabled", false);
        $("#data_finalizacao").hide()
        $("#select_n_gestor").hide()
        $("#data_nova_lid").hide()
        $("#select_lider_atualiza").hide()
        $("#text_gestor").hide()
        $("#text_lider").hide()
        $("#text_colab_lider").hide()

        $("#c_id").val("");
        $("#c_gh_nome").val("");

        $("#btn_salvar_reg").prop("disabled", false);
        $("#btn_excluir_reg").prop("disabled", true);
    }

</script>