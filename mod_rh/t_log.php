<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Pesquisar CNPJ de empresas</title>
    <!--Importando Script Jquery-->

    <link href="css/animate.css" rel="stylesheet">
    <link rel="stylesheet" href="../class/alert/css/class_alert.css" id="theme-styles">

    <script src="../class/alert/js/class_alert.js"></script>

    <style>
        input,
        textarea {
            text-transform: uppercase;
        }

        table tr td {
            color: black;
        }

        table tr th {
            color: black;
        }

        .load {
            width: 100px;
            height: 100px;
            position: absolute;
            top: 40%;
            left: 50%;
            color: #523B8F;
        }

        .container-border {
            border: 1px solid #ccc;
            border-radius: 15px;
            background-color: white;
        }
    </style>

</head>

<body>
    <!--Formulário-->

    <div class="container-fluid container-border" style="margin: 0px; padding: 0px;">
        <div class="load"> <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
            <span class="sr-only">Loading...</span>

        </div>

        <input id="c_acao" name="c_acao" type="hidden" value="">
        <input id="c_db_emp" name="c_db_emp" type="hidden" value="0">



        <div id="box_tab_titulo" class="box" style="height: 55px; margin-bottom: 0px; background-image: linear-gradient(to left, #6c3a8e , white); border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-12 col" style="margin: 0px; padding: 0px; font-weight: bold; font-size: 25px; color: #523B8F;">Lista de Log</div>
            </div>
        </div>

        <!-- <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-image: linear-gradient(to left, #6c3a8e , white); border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-6">
                    <h3>Lista de Log</h3>
                </div>
            </div>
        </div> -->

        <div id="box_tab1" class="row" style="margin: 0px; border-color: grey; padding: 10px; border-width: 1px; border-style: none; border-color: #ccc; background-color: white; overflow-x: hidden;">
            <div class="box-body">
                <!-- <input type="hidden" id="vf_tab_sql_limit_in" value="0">
                <input type="hidden" id="vf_tab_btn_pag_select" value="1"> -->

                <table style="width: 100%;" id="tab1" class="table">
                    <thead style="font-weight: bold;">
                        <tr>
                            <th>Usuário</th>
                            <th>Data e Hora</th>
                            <th>Empresa</th>
                            <th>Processo</th>
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
                <div class="form-group col-sm-11">
                    <h3>Formulário de Cadastro</h3>
                </div>
                <div class="form-group col-sm-1">
                    <button id="btn_voltar" class="btn btn-danger" style="border-radius: auto; width: auto;" onclick="goBack();">X</button>
                </div>
            </div>
        </div>
        <div id="box_form_cad" class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">
            <div class="box-body" style="height: auto;">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="c_usuario" style="cursor: default;">Usuário</label>
                        <input id="c_usuario" type="text" class="form-control class_inputs">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="c_data_hora" style="cursor: default;">Data hora</label>
                        <input id="c_data_hora" type="text" class="form-control class_inputs">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="c_empresa" style="cursor: default;">Empresa</label>
                        <input id="c_empresa" type="text" class="form-control class_inputs">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="c_tipo_log" style="cursor: default;">Processo</label>
                        <input id="c_tipo_log" type="text" class="form-control class_inputs">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="c_descricao" style="cursor: default;">Descricao</label>
                        <textarea id="c_descricao" type="textarea" class="form-control class_inputs"></textarea>
                        <!-- <textarea class="form-control" id="c_descricao_dpto" rows="3"> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="box-footer">
            <div class="row">
                <div class="form-group col-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                    <!-- <button id="btn_novo_reg" class="btn btn-primary" style="border-radius: 10px; width: 100px;" onclick="func_novo_registro()">Novo</button> -->
                    <!-- <button disabled id="btn_salvar_reg" class="btn btn-success" style="border-radius: 10px; width: 100px;" onclick="func_salvar_registro()">Salvar</button> -->
                    <!-- <button disabled id="btn_desab_empresa" class="btn btn-danger" style="border-radius: 10px; width: 100px;" onclick="func_desabilitar()">Desabilitar</button> -->
                </div>
            </div>
        </div>

    </div>


    <script src="../class/DataTables/datatables.min.js"></script>
    <script>
        $(document).ready(function() {

            $("#box_form_titulo").hide();
            $("#box_form_titulo2").hide();
            $("#box_form").hide();
            $("#box_form_cad").hide();
            // $("#btn_salvar_reg").hide();
            // $("#btn_desab_empresa").hide();
            $("#box_check").hide();
            $("#box_logo_emp").hide();
            $("#box_logo_emp_title").hide();
            $("#box_certificado").hide();
            $("#box_inscricao_div").hide();

            $("#tab1b").empty();
            // $('#tab1').DataTable().destroy(); //add
            $("#box_tab_titulo").show();
            $("#box_tab1").show();
            $("#box_tab_footer").show();

            // func_lista_emp_contabil();
            func_carrega_tab_emp();
            // func_carrega_tab_emp(data[v_index].usuario);

        });




        function func_carrega_tab_emp() {
            // alert("func_carrega_tab_emp");
            // die;
            $("#c_acao").val("");
            var v_acao = "LISTAR_EMPRESAS";
            $("#c_usuario").prop("disabled", true);
            $("#c_data_hora").prop("disabled", true);
            $("#c_empresa").prop("disabled", true);
            $("#c_tipo_log").prop("disabled", true);
            $("#c_descricao").prop("disabled", true);
            // var v_tab_campo = $("#c_tab_campo").val();
            // var v_tab_ordem = $("#c_tab_ordem").val();
            // var v_tab_busca_campo = $("#c_tab_busca_campo").val();
            // var v_tab_busca_texto = $("#c_tab_busca_texto").val();
            // var v_tab_sql_limit_in = $("#vf_tab_sql_limit_in").val();
            // var v_limit = $("#c_limit").val();

            // $("#c_nome").prop("disabled", true);
            // $("#c_nome").prop("disabled", true);
            // $("#tipo_doc").prop("disabled", true);
            // $("#c_tipo").prop("disabled", true);

            // $("#c_id").val("");
            // $("#cnpj").val("");
            // $("#c_nome").val("");

            // $("#btn_novo_reg").prop("disabled", false);
            // $("#btn_salvar_reg").prop("disabled", true);
            // $("#btn_desab_empresa").prop("disabled", true);

            $.ajax({
                type: "POST",
                url: 'lib/lib_t_log.php',
                data: {
                    "v_acao": v_acao
                },
                success: function(data) {
                    var options = '';
                    var v_index = 0;
                    var v_num_linhas = 0;
                    $('#tab1').DataTable().destroy(); //add
                    $("#tab1b").empty();
                    var options = ''; //add
                    v_num_linhas = data[0].linhas;
                    for (v_index = 1; v_index < data.length; v_index++) {
                        options += '<tr style="cursor: pointer;" onclick="func_select_emp(\'' + data[v_index].id_user + '\',' + '\'' + data[v_index].data_hora + '\');"><td>' + data[v_index].usuario + '</td><td>' + data[v_index].data_hora + '</td><td>' + data[v_index].empresa + '</td><td>' + data[v_index].tipo_log + '</td><td>' + data[v_index].descricao + '</td></tr>';
                    }

                    $('#tab1b').html(options);

                    $("#tab1").DataTable({
                        "language": {
                            "url": "../class/DataTables/portugues.json",
                        },
                        "columnDefs": [{
                            "width": "100%",
                            "targets": 1,
                        }],
                        "lengthMenu": [
                            [5, 10, 25, 50, -1],
                            [5, 10, 25, 50, "Todos"]
                        ],
                        "order": [
                            [1, "desc"]
                        ],
                        "scrollY": "50vh",
                        "scrollX": "50vh",
                        "scrollCollapse": true,
                        "paging": true,
                    });

                    // $("#div_tab_paginacao").empty();
                    // var divAtual = document.getElementById("div_tab_paginacao");
                    // var v_num_pag = Math.round(v_num_linhas / v_limit);
                    // for (v_index = 0; v_index <= v_num_pag; v_index++) {
                    // 	var novoBtn = document.createElement("button");
                    // 	novoBtn.setAttribute('id', 'btn_pag' + (v_index + 1));
                    // 	novoBtn.setAttribute('class', 'btn btn-default');
                    // 	novoBtn.innerHTML = (v_index + 1);
                    // 	novoBtn.setAttribute('onClick', 'func_tab_paginar(' + v_index + ');');
                    // 	divAtual.appendChild(novoBtn);
                    // }

                    // var v_tab_btn_pag_select = $("#vf_tab_btn_pag_select").val();
                    // $("#btn_pag" + v_tab_btn_pag_select).css("background-color", "#C6E2FF");
                    $('.load').hide();
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


        //VALIDA EXTÃO DE ARQUIVOS 
        function validaExtensao(id) {
            alert("validaExtensao");
            die;
            var result = true;
            var extensoes = new Array('pfx', 'p12'); // Arquivos permitidos
            var ext = $('#' + id).val().split(".")[1].toLowerCase();
            if ($.inArray(ext, extensoes) === -1) { // Arquivo não permitido
                result = false;
            } else {
                alert("Erro ao anexar certificado!");
            }
            return result;
        }

        //SELECIONA EMPRESA NO DB
        function func_select_emp(v_id, v_data_hora) {
            // alert("func_select_emp");
            // die;
            $("#c_acao").val("EV_SELECT");
            // alert("EV_SELECT");
            // die;

            $.ajax({
                type: "POST",
                url: 'lib/lib_t_log.php',
                data: {
                    "v_acao": "EV_SELECT",
                    "v_id": v_id,
                    "v_data_hora": v_data_hora
                },
                success: function(data) {

                    // $("#btn_novo_reg").hide();
                    $("#box_tab_footer").hide();
                    $("#box_tab_titulo").hide();
                    $("#box_tab1").hide();
                    $("#box_form_cad").show();
                    $("#box_form_titulo").show();
                    // $("#btn_salvar_reg").show(); //BOTÃO SALVAR
                    // $("#btn_desab_empresa").show(); //BOTÃO DESABILITAR
                    // $("#c_nome").prop("disabled", false);
                    // $("#c_id").val(data[0].id);
                    // $("#c_tipo").val(data[0].tipo);



                    $("#c_usuario").val(data[0].usuario); //modificado
                    $("#c_data_hora").val(data[0].data_hora); //modificado
                    $("#c_empresa").val(data[0].empresa); //modificado
                    $("#c_tipo_log").val(data[0].tipo_log); //modificado
                    $("#c_descricao").val(data[0].descricao); //modificado
                    // $("st_cadastro").val(data[0].st_cadastro);
                    // $("#cnpj").val(data[0].cnpj).mask("00.000.000/0000-00");
                    // $("#c_uf").val(data[0].uf);
                    // $("#c_senha_cert").val(data[0].fisco_certi_senha);
                    // $("#c_emp_contabil").val(data[0].fisco_cnpj_agrocontar);
                    // $("#c_atividade_principal").val(data[0].ativ_principal);
                    // $("#c_telefone").val(data[0].telefone);
                    // $("#c_email").val(data[0].email);
                    // $("#c_cep").val(data[0].cep);
                    // $("#c_logradouro").val(data[0].logradouro);
                    // $("#c_complemento").val(data[0].complemento);
                    // $("#c_numero").val(data[0].numero);
                    // $("#c_bairro").val(data[0].bairro);
                    // $("#c_municipio").val(data[0].municipio);
                    // $("#c_insc_estadual").val(data[0].insc_estadual);
                    // $("#c_insc_municipal").val(data[0].insc_municipal);
                    // $("#c_natureza_juridica").val(data[0].natureza_juridica);
                    // $("#c_atividades_secundarias").val(data[0].ativ_secundarias);
                    // $("#c_abertura").val(data[0].dat_abertura);
                    // $("#img_logo").attr("src", "../mod/img/logo_emp/" + data[0].cnpj + ".jpg");
                    // if (data[0].modulo_fisco == "S") {
                    //     $("#c_modulo_fisco").attr("checked", "checked");
                    // } else {
                    //     $("#c_modulo_fisco").attr("checked", "");
                    // }
                    // if (data[0].modulo_rh == "S") {
                    //     $("#c_modulo_rh").attr("checked", "checked");
                    // } else {
                    //     $("#c_modulo_rh").attr("checked", "");
                    // }
                    // if (data[0].modulo_adm == "S") {
                    //     $("#c_modulo_adm").attr("checked", "checked");
                    // } else {
                    //     $("#c_modulo_adm").attr("checked", "");
                    // }
                    // if (data[0].modulo_cons == "S") {
                    //     $("#c_modulo_cons").attr("checked", "checked");
                    // } else {
                    //     $("#c_modulo_cons").attr("checked", "");
                    // }
                    // $("#btn_novo_reg").prop("disabled", true);
                    // $("#btn_salvar_reg").prop("disabled", false);
                    // $("#btn_desab_empresa").prop("disabled", false);

                    // if (data[0].check_cert == "OK") {
                    //     if (data[0].check_cert_dt_validade == "OK") {
                    //         $("#btn_upload_cert").html("Certificado Anexado<br>Válido até " + data[0].fisco_cert_dthr_validade);
                    //         $("#btn_upload_cert").removeClass("btn-primary");
                    //         $("#btn_upload_cert").removeClass("btn-danger");
                    //         $("#btn_upload_cert").addClass("btn-success");
                    //     } else {
                    //         $("#btn_upload_cert").html("Certificado Anexado<br>Data expirada: " + data[0].fisco_cert_dthr_validade);
                    //         $("#btn_upload_cert").removeClass("btn-primary");
                    //         $("#btn_upload_cert").removeClass("btn-success");
                    //         $("#btn_upload_cert").addClass("btn-danger");
                    //     }
                    // } else {
                    //     $("#btn_upload_cert").html("Anexar Certificado");
                    //     $("#btn_upload_cert").removeClass("btn-success");
                    //     $("#btn_upload_cert").removeClass("btn-danger");
                    //     $("#btn_upload_cert").addClass("btn-primary");
                    // }

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
            alert("func_novo_registro");
            die;

            $("#c_acao").val("EV_NOVO");

            // $("#btn_novo_reg").hide();
            $("#box_tab_footer").hide();
            $("#box_tab_titulo").hide();
            $("#box_tab1").hide();

            $("#box_form_cad").show();
            $("#box_form_titulo").show();
            // $("#btn_salvar_reg").show();

            // $("#btn_desab_empresa").hide();

            $("#C_usuario").val("");
            $("#c_data_hora").val("");
            $("#c_empresa").val("");
            $("#c_tipo_log").val("");
            $("#c_descricao").val("");

            // $("#btn_novo_reg").prop("disabled", false);
            // $("#btn_salvar_reg").prop("disabled", false);
            // $("#btn_desab_empresa").prop("disabled", true);



            // $("#box_certificado").show();
            // $("#box_inscricao_div").show();
            // $("#box_form_titulo2").show();

            // $("#box_check").show();
            // $("#box_logo_emp").show();
            // $("#box_logo_emp_title").show();





            // $("#cnpj").prop("disabled", false);
            // $("#c_nome").prop("disabled", false);
            // $("#tipo_doc").prop("disabled", false);
            // $("#c_tipo").prop("disabled", false);

            // $("#c_id").val("");
            // $("#c_tipo").val("0");

            // $("st_cadastro").val("");
            // $("#cnpj").val("");
            // $("#c_uf").val("");
            // $("#c_fisco_cuf").val("0");
            // $("#c_senha_cert").val("");
            // $("#c_emp_contabil").val("0");
            // $("#c_fisco_import_data_hora").val("");
            // $("#c_fisco_ultimo_nsu_entrada").val("");
            // $("#c_modulo_adm").attr("checked", false);
            // $("#c_modulo_rh").attr("checked", false);
            // $("#c_modulo_fisco").attr("checked", false);
            // $("#c_modulo_cons").attr("checked", false);
            // $("#c_atividade_principal").val("");
            // $("#c_telefone").val("");
            // $("#c_db_emp").val("0");
            // $("#c_email").val("");
            // $("#c_cep").val("");
            // $("#c_logradouro").val("");
            // $("#c_complemento").val("");
            // $("#c_numero").val("");
            // $("#c_bairro").val("");
            // $("#c_municipio").val("");
            // $("#c_insc_estadual").val("");
            // $("#c_insc_municipal").val("");
            // $("#c_natureza_juridica").val("");
            // $("#c_atividades_secundarias").val("");
            // $("#c_abertura").val("");



        }



        function func_salvar_registro() {
            alert("func_salvar_registro");
            die;

            v_acao = $("#c_acao").val();
            if (v_acao != "EV_NOVO") {
                v_acao = "EV_SALVAR";
            }

            if ($('#c_mod_fisco').is(":checked")) {
                v_mod_fisco = "S";
            } else {
                v_mod_fisco = "N";
            }

            if ($('#c_mod_rh').is(":checked")) {
                v_mod_rh = "S";
            } else {
                v_mod_rh = "N";
            }

            if ($('#c_mod_adm').is(":checked")) {
                v_mod_adm = "S";
            } else {
                v_mod_adm = "N";
            }

            if ($('#c_mod_cons').is(":checked")) {
                v_mod_cons = "S";
            } else {
                v_mod_cons = "N";
            }

            v_id = $("#c_id").val();
            v_tipo = $("#c_tipo").val();
            v_empresa_portal = $("#c_empresa_portal").val();
            v_empresa_erp = $("#c_empresa_erp").val();
            v_nome = $("#c_nome").val();
            v_st_cadastro = $("#st_cadastro").val();
            v_cnpj = $("#cnpj").val();
            v_uf = $("#c_uf").val();
            v_fisco_certi_senha = $("#c_senha_cert").val();
            v_fisco_cnpj_agrocontar = $("#c_emp_contabil").val();
            v_senha_cert = $("#c_senha_cert").val();
            v_atividade_principal = $("#c_atividade_principal").val();
            v_telefone = $("#c_telefone").val();
            v_email = $("#c_email").val();
            v_cep = $("#c_cep").val();
            v_logradouro = $("#c_logradouro").val();
            v_complemento = $("#c_complemento").val();
            v_numero = $("#c_numero").val();
            v_bairro = $("#c_bairro").val();
            v_municipio = $("#c_municipio").val();
            v_insc_estadual = $("#c_insc_estadual").val();
            v_insc_municipal = $("#c_insc_municipal").val();
            v_natureza_juridica = $("#c_natureza_juridica").val();
            v_atividades_secundarias = $("#c_atividades_secundarias").val();
            v_abertura = $("#c_abertura").val();
            v_db_emp = $("#c_db_emp").val();

            if (v_cnpj.length > 5 && v_nome.length > 5 && v_uf != "-" && v_tipo > 0 && v_fisco_cnpj_agrocontar > 0 && v_insc_estadual >= 0) {
                $.ajax({
                    type: "POST",
                    url: 'lib/lib_t_log.php',
                    data: {
                        "v_acao": v_acao,
                        "v_db_emp": v_db_emp,
                        "v_id": v_id,
                        "v_tipo": v_tipo,
                        "v_empresa_portal": v_empresa_portal,
                        "v_empresa_erp": v_empresa_erp,
                        "v_nome": v_nome,
                        "v_st_cadastro": v_st_cadastro,
                        "v_cnpj": v_cnpj,
                        "v_uf": v_uf,
                        "v_fisco_certi_senha": v_fisco_certi_senha,
                        "v_fisco_cnpj_agrocontar": v_fisco_cnpj_agrocontar,
                        "v_senha_cert": v_senha_cert,
                        "v_atividade_principal": v_atividade_principal,
                        "v_telefone": v_telefone,
                        "v_email": v_email,
                        "v_cep": v_cep,
                        "v_logradouro": v_logradouro,
                        "v_complemento": v_complemento,
                        "v_numero": v_numero,
                        "v_bairro": v_bairro,
                        "v_municipio": v_municipio,
                        "v_insc_estadual": v_insc_estadual,
                        "v_insc_municipal": v_insc_municipal,
                        "v_natureza_juridica": v_natureza_juridica,
                        "v_atividades_secundarias": v_atividades_secundarias,
                        "v_abertura": v_abertura,
                        "v_mod_fisco": v_mod_fisco,
                        "v_mod_rh": v_mod_rh,
                        "v_mod_adm": v_mod_adm,
                        "v_mod_cons": v_mod_cons
                    },
                    success: function(data) {
                        var v_json = JSON.parse(data);
                        Swal.fire({
                            icon: v_json.msg_ev,
                            title: v_json.msg_titulo,
                            text: v_json.msg
                        })

                        if (v_json.msg_ev == "success") {
                            goBack();
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



        function func_desabilitar() {
            alert("func_desabilitar");
            die;

            Swal.fire({
                title: 'Você tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, pode excluir!'
            }).then((result) => {
                if (result.value) {

                    v_acao = "EV_DESABILITAR_EMPRESA";
                    v_cnpj = $("#cnpj").val();

                    if (v_cnpj > 0) {

                        $.ajax({
                            type: "POST",
                            url: 'lib/lib_t_log.php',
                            data: {
                                "v_acao": v_acao,
                                "v_cnpj": v_cnpj
                            },
                            success: function(data) {
                                var v_json = JSON.parse(data);
                                Swal.fire(
                                    v_json.msg_titulo,
                                    v_json.msg,
                                    v_json.msg_ev
                                )

                                if (v_json.msg_ev == "success") {
                                    func_carrega_tab_emp();
                                }
                            },
                            error: function(request, status, erro) {
                                swal("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                            }
                        });
                    } else {

                        Swal.fire({
                            icon: "error",
                            title: "FALHA!",
                            text: "Selecione um registro."
                        })

                    }
                }
            })
        }



        function goBack() { //OK

            // $("#btn_novo_reg").prop("disabled", false);
            // $("#btn_salvar_reg").prop("disabled", true);
            // $("#btn_desab_empresa").prop("disabled", true);

            $("#box_form_cad").hide();
            $("#box_form_titulo").hide();
            // $("#btn_salvar_reg").hide();
            // $("#btn_desab_empresa").hide();
            // $("#btn_novo_reg").show();
            $("#box_tab_titulo").show();
            $("#box_tab1").show();
            $("#box_tab_footer").show();
            $("#c_usuario").val("");
            $("#c_data_hora").val("");
            $("#c_empresa").val("");
            $("#c_tipo_log").val("");
            $("#c_descricao").val("");
        }
    </script>

</body>

</html>