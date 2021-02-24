<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0019") == 0) {
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

        #negrito tr td {
            font-weight: bolder;
        }

        table tr td {
            color: black;
        }

        table tr #subtitulo {
            color: #8B8989;
        }

        table tr th {
            color: black;
        }

        #proventos {
            color: #0000CD;
        }

        #descontos {
            color: #B50600;
        }

        #liquido {
            color: #006400;
        }

        #c_proventos {
            color: #0000CD;
        }

        #c_descontos {
            color: #B50600;
        }

        #c_liquido {
            color: #006400;
        }

        #titulo {
            background-color: #B8B8B8;
            color: #4D4D4D;
        }

        .load {
            width: 100px;
            height: 100px;
            position: absolute;
            top: 40%;
            left: 50%;
            color: #523B8F;
        }
    </style>

</head>

<body>

    <div class="load"> <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
        <span class="sr-only">Loading...</span>
    </div>


    <div class="container" style="margin: 0px; padding: 0px;">


        <div id="box_form_titulo" class="row" style="margin-top: 40px; background-image: linear-gradient(to left, #6c3a8e , white);">
            <div class="row">
                <div class="form-group col-sm-8 col" style="font-weight: bold; font-size: 25px; color: #523B8F;">Holerites <span id="ano_titulo"></span></div>
            </div>
        </div>
    </div>

    <div id="box_form_barra" class="row">
        <div class=" row">
            <div class="form-group col-sm-4" style="left: 66%; margin: 5px;">
                <select onchange="func_carrega_tab()" id="c_select_ano" class="form-control class_inputs">
                    <?php
                    $anoInicio = intval(date('Y'));
                    for ($i = 0; $i < 10; $i++, $anoInicio--) {
                        echo '<option value="' . $anoInicio . '">' . $anoInicio . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div id="paginacao" class="row" style="padding: 10px; border-width: 1px; border-style: solid;">
        <input id="c_acao" type="hidden" value="">
        <div class="col-sm-12 table-responsive">
            <table style="width: 100%;" id="tabHolerite" class="table table-striped">
                <thead style="font-weight: bold;">
                    <tr>
                        <th class="th-sm">Competencia</th>
                        <th>Folha</th>
                        <th style="text-align: right;">Proventos</th>
                        <th style="text-align: right;">Descontos</th>
                        <th style="text-align: right;">Liquido</th>
                        <th style="text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody id="tabHoleriteb" style="font-weight: normal;">

                </tbody>
            </table>

        </div>
    </div>
    <div id="c_tipo_folha" style="display: none;">

    </div>
    <div id="c_competencia" style="display: none;">

    </div>
    <div class="row" id="btn_acoes" style="display: none;">
        <div class="form-group col-sm-10 offset-sm-2">

        </div>
        <div class="form-group col-sm-1 offset-sm-2">
            <button id="btn_voltar" class="btn btn-danger" style="border-radius: auto; width: auto;" onclick="goBack();">Voltar</button>
        </div>
        <div class="form-group col-sm-1 offset-sm-1">
            <button id="btn_download" class="btn btn-success" style="border-radius: auto; width: auto;" onclick="func_download();">Download</button>
        </div>
    </div>
    <div id="detalhes_holerite" class="row" style="margin-top: 1%; display: none;">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th id="titulo" class="c_recibo_pagamento" scope="col"></th>
                    <th id="titulo" class="c_calculo_mensal" scope="col"></th>
                    <th id="titulo" class="c_matricula" scope="col"></th>
                </tr>
            </thead>
        </table>

        <div class="col-sm-12 table-responsive">

            <table class="table table-sm" id="negrito">
                <tbody>
                    <tr>
                        <td>
                            <div id="subtitulo">RAZÃO SOCIAL</div>
                            <div id="c_nome_empresa"></div>
                        </td>
                        <td>
                            <div id="subtitulo">CNPJ</div>
                            <div id="c_cnpj"></div>
                        </td>
                        <td id="logo">

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div id="subtitulo">NOME</div>
                            <div id="c_nome_colaborador"></div>
                        </td>
                        <td>
                            <div id="subtitulo">CPF</div>
                            <div id="c_cpf"></div>
                        </td>
                        <td>
                            <div id="subtitulo">PIS</div>
                            <div id="c_pis"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div id="subtitulo">DEPARTAMENTO</div>
                            <div id="c_departamento"></div>
                        </td>
                        <td>
                            <div id="subtitulo">CARGO/FUNÇÃO</div>
                            <div id="c_cargo"></div>
                        </td>
                        <td>
                            <div id="subtitulo">CBO</div>
                            <div id="c_cbo"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div id="subtitulo">ADMISSÃO</div>
                            <div id="c_admissao"></div>
                        </td>
                        <td>
                            <div id="subtitulo">SALÁRIO BASE</div>
                            <div id="c_salario_base"></div>
                        </td>
                        <td>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-12 table-responsive">
            <table id="tabela_valores" class="table table-bordered table-sm">
            </table>
        </div>
        <div class="col-sm-12 table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th id="proventos" class="text-center">Total de Proventos</th>
                        <th id="descontos" class="text-center">Total de Descontos</th>
                        <th id="liquido" class="text-center">Total Líquido</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="c_proventos" class="text-center"></td>
                        <td id="c_descontos" class="text-center"></td>
                        <td id="c_liquido" class="text-center"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-12 table-responsive">
            <table id="tabela_bases" class="table table-bordered table-sm">
            </table>
        </div>
    </div>
    <div class="row">
        <div id="c_msg_autentica" class="col-sm-12">
        </div>
    </div>
    <div class="row">
        <div id="c_msg_responsabilidade" class="col-sm-12">
        </div>
    </div>

    <!-- <div class="box-footer">
            <div class="row">
                <div class="form-group col-sm-sm-xs-6 text-center" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                    <button id="btn_autenticar" class="btn btn-warning" onclick="func_autentica()">Autenticar</button>
                </div>
                <div class="form-group col-sm-sm-xs-6 text-center" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                    <button id="btn_download" class="btn btn-success" onclick="window.open('lib/lib_cons_holerite_pdf.php?ref='+$('#c_competencia').html() + '&folha='+$('#c_tipo_folha').val(),'_blank');" style="border-radius: 10px; width: 200px;">Fazer Download</button>
                </div>
            </div>
        </div> -->
    </div>
    <script src="../class/DataTables/datatables.min.js"></script>
    <script language="JavaScript">
        $(document).ready(function() {
            func_carrega_tab();
            // $("#tabHolerite").bootgrid();
            $("#btn_acoes").hide();
            $("#detalhes_holerite").hide();
            $('#c_msg_autentica').hide();
        });

        function func_download() {
            // console.log(btoa(($('.c_matricula').html()).split(' ')[1]))
            window.open(`lib/lib_cons_holerite_pdf.php?ref=${btoa($('#c_competencia').html())}&folha=${btoa($('#c_tipo_folha').html())}&mat=${btoa(($('.c_matricula').html()).split(' ')[1])}`);
            // window.open('lib/lib_cons_holerite_pdf.php?ref=${btoa(data[v_index].Competencia)}&folha=${btoa(data[v_index].Tipo)}&mat=${btoa(data[v_index].Matricula)}');
        }

        function func_autentica(competencia, tipo_folha, matricula, botao) {

            $.ajax({
                type: "POST",
                url: "lib/lib_cons_holerite.php",
                data: {
                    "v_acao": "EV_AUTENTICA",
                    "v_competencia": competencia,
                    "v_tipo": tipo_folha,
                    "v_matricula": matricula
                },
                success: function(data) {

                    $(`#btn_autentica_${data.competencia}`).prop('disabled', true)

                    let data_hora = formata_data_hora(data.data_hora)
                    let texto_autenticacao = `Deu ciência digitalmente em ${data_hora.data} às ${data_hora.hora} GMT-03:00. Usuário: ${data.usuario}. IP: ${data.ip}`

                    $("#c_msg_autentica").html(texto_autenticacao)
                    $(`#${botao.id}`).html(`<span class="button-text">
                                            <i class="fa fa-pencil  fa-1x" aria-hidden="true">Ciente</i>
                                        </span>`)

                    Swal.fire({
                        icon: "success",
                        title: "SUCESSO!",
                        text: "Folha autenticada com sucesso!"
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


        function func_carrega_tab() {

            var v_acao = "LISTAR_RECIBOS";

            $("#ano_titulo").html($("#c_select_ano").val())

            $.ajax({
                type: "POST",
                url: "lib/lib_cons_holerite.php",
                data: {
                    "v_acao": v_acao,
                    "v_ano": $("#c_select_ano").val()
                },
                success: function(data) {
                    $('#tabHolerite').DataTable().destroy();
                    var options = '';
                    var v_index = 0;

                    $("#tabHoleriteb").empty();
                    for (v_index = 0; v_index < data.length; v_index++) {
                        let provento = parseFloat(data[v_index].Provento)
                        let desconto = parseFloat(data[v_index].Desconto)
                        let liquido = provento - desconto
                        options += '<tr style="cursor: pointer;"><td onclick="func_select(\'' + data[v_index].Competencia + '\',\'' + data[v_index].Tipo + '\');" >' + data_mes_ano(data[v_index].Competencia) +
                            '</td><td onclick="func_select(\'' + data[v_index].Competencia + '\',\'' + data[v_index].Tipo + '\');">' + data[v_index].Tipo_folha +
                            '</td><td onclick="func_select(\'' + data[v_index].Competencia + '\',\'' + data[v_index].Tipo + '\');" style="text-align: right;">' +
                            formata_monetario(provento) + '</td><td onclick="func_select(\'' + data[v_index].Competencia + '\',\'' + data[v_index].Tipo + '\');" style="text-align: right;">' +
                            formata_monetario(desconto) +
                            '</td><td onclick="func_select(\'' + data[v_index].Competencia + '\',\'' + data[v_index].Tipo + '\');" style="text-align: right;">' + formata_monetario(liquido) +
                            `</td><td style="text-align: center;">
                            <div class="btn-group" style="border: 0px; margin: 0px;">
                                <button onclick="func_select('${data[v_index].Competencia}','${data[v_index].Tipo}');"
                                class="btn is-icon btn-outline-primary" title="Visualizar holerite">
                                        <span class="button-text">
                                            <i class="fa fa-search fa-1x"></i>
                                        </span>
                                    </button>
                                    <button onclick="window.open('lib/lib_cons_holerite_pdf.php?ref=${btoa(data[v_index].Competencia)}&folha=${btoa(data[v_index].Tipo)}&mat=${btoa(data[v_index].Matricula)}');" 
                                    id="btn_novo_download" class="btn is-icon btn-outline-primary" title="Baixar holerite">
                                        <span class="button-text">
                                            <i class="fa fa-cloud-download fa-1x"></i>
                                        </span>
                                    </button>
                                    <button ${data[v_index].Data_autenticacao ? 'disabled' : ''} id="btn_autentica_${data[v_index].Competencia}" class="btn is-icon btn-outline-primary" title="Dar Ciência"
                                    onclick="func_autentica('${data[v_index].Competencia}', '${data[v_index].Tipo}', '${data[v_index].Matricula}', this)">
                                        <span class="button-text">
                                            <i class="fa fa-pencil  fa-1x" aria-hidden="true">${data[v_index].Data_autenticacao ? 'Ciente' : 'Dar Ciência'}</i>
                                        </span>
                                    </button>
                                </div>
                            </td>`;
                    }

                    $('#tabHoleriteb').html(options);

                    $("#btn_download").prop('disabled', true)
                    $("#btn_autenticar").prop('disabled', true)

                    $("#tabHolerite").DataTable({
                        "language": {
                            "url": "../class/DataTables/portugues.json",
                        },
                        "columnDefs": [{
                            "width": "10%",
                            "targets": [2, 3, 4],
                        }, {
                            "width": "20%",
                            "targets": [0, 1],
                        }],
                        "lengthMenu": [
                            [5, 10, 20, -1],
                            [5, 10, 20, "Todos"]
                        ],
                        "order": [
                            [0, "desc"]
                        ],
                        "scrollY": "50vh",
                        "scrollX": true,
                        "scrollCollapse": true,
                        "paging": true
                    });
                    $('.load').hide();
                    $('#c_msg_autentica').hide();

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

        function func_select(vj_competencia, vj_tipo) {

            var v_competencia = vj_competencia;
            var v_tipo = vj_tipo;

            $("#c_tipo_folha").html(vj_tipo);

            //        $v_competencia = data[v_index].Competencia;

            $.ajax({
                type: "POST",
                url: "lib/lib_cons_holerite.php",
                data: {
                    "v_acao": "EV_CARREGA_HOLERITE",
                    "v_competencia": v_competencia,
                    "v_tipo": v_tipo,
                    "v_ano": $("#c_select_ano").val()
                },
                success: function(data) {

                    $("#paginacao").hide();
                    $("#detalhes_holerite").show();
                    $("#btn_acoes").show();


                    $("#c_msg_responsabilidade").html('Todas as informações contidas nesse documento são de responsabilidade da empresa pagadora.')

                    $("#btn_download").prop('disabled', false)

                    $("#c_empresa").html(data[0].Cod_empresa);
                    $("#c_nome_empresa").html(data[0].Nome_empresa);
                    $("#c_cnpj").html(formata_cnpj(data[0].Cnpj));
                    $("#logo").html(`<img src="${data[0].logo}" width="80">`)

                    $(".c_matricula").html(`Matrícula ${data[1].Matricula}`);
                    $(".c_calculo_mensal").html(data_extenso(data[2].Competencia));
                    $(".c_recibo_pagamento").html('RECIBO DE PAGAMENTO')
                    $("#c_nome_colaborador").html(data[1].Nome);
                    $("#c_cpf").html(formata_cpf(data[1].Cpf));
                    $("#c_pis").html(formata_cpf(data[1].Pis));
                    $("#c_admissao").html(formata_data(data[1].Admissao));
                    $("#c_departamento").html(data[1].Departamento);
                    $("#c_cargo").html(data[1].Cargo);
                    $("#c_cbo").html(data[1].Cbo);

                    $("#c_competencia").html(data[2].Competencia);
                    $("#c_data_pagamento").html(formata_data(data[2].Data_pagamento));
                    $("#c_salario_base").html(formata_monetario(data[2].Salario_base));
                    $("#c_proventos").html(formata_monetario(data[2].Total_vencimentos));
                    $("#c_descontos").html(formata_monetario(data[2].Total_descontos));
                    $("#c_liquido").html(formata_monetario(data[2].Total_liquido));

                    if (data[2].Data_autenticacao != null) {
                        let data_hora = formata_data_hora(data[2].Data_autenticacao)
                        let texto_autenticacao = `Deu ciência digitalmente em ${data_hora.data} às ${data_hora.hora} GMT-03:00. Usuário: ${data[2].Usuario_autenticacao}. IP: ${data[2].Ip_autenticacao}`
                        $("#c_msg_autentica").html(texto_autenticacao)
                        $("#btn_autenticar").prop('disabled', true)
                    } else {
                        $("#btn_autenticar").prop('disabled', false)
                        $("#c_msg_autentica").html(`Não deu ciência!`)
                    }

                    $("#tabela_bases").empty()

                    let bases = `<tbody>
                        <tr>
                            <td>Base INSS: ${formata_monetario(data[2].Base_inss)}</td>
                            <td>Base FGTS:  ${formata_monetario(data[2].Base_fgts)}</td>
                            <td>Valor FGTS: ${formata_monetario(data[2].Fgts_mes)}</td>
                        </tr>
                        <tr>
                            <td>Base IRRF: ${formata_monetario(data[2].Base_irrf)}</td>
                            <td>Dependentes IRRF: ${data[2].Dependentes_ir}</td>
                            <td>Dependentes Sal. Família: ${data[2].Dependentes_sf}</td>
                        </tr>
                    </tbody>`

                    $("#tabela_bases").html(bases)

                    //$("#tabela_valores").empty()

                    $("#tabRubricasb").empty();
                    let options = `
                    <thead>
                        <tr>
                            <th class="text-center">CÓD</th>
                            <th class="text-center">DESCRIÇÃO</th>
                            <th class="text-center">REFERÊNCIA</th>
                            <th class="text-center" id="proventos">PROVENTOS (+)</th>
                            <th class="text-center" id="descontos">DESCONTOS (-)</th>
                        </tr>
                    </thead>
                    </tbody>
                    `
                    let options_proventos
                    let options_descontos
                    for (v_index = 3; v_index < data.length; v_index++) {
                        if (data[v_index].Tipo_Rubrica == 'Proventos') {
                            options_proventos += `
                            '<tr>
                                <td style="text-align: center;">${data[v_index].Rubrica}</td>
                                <td style="text-align: center;">${data[v_index].Descricao_rubrica}</td>
                                <td style="text-align: center;">${data[v_index].Referencia}</td>
                                <td style="text-align: center;">${formata_monetario(data[v_index].Vencimentos)}</td>
                                <td></td>
                            </tr>';
                            `
                        }
                        if (data[v_index].Tipo_Rubrica == 'Descontos') {
                            options_descontos += `
                            '<tr>
                                <td style="text-align: center;">${data[v_index].Rubrica}</td>
                                <td style="text-align: center;">${data[v_index].Descricao_rubrica}</td>
                                <td style="text-align: center;">${data[v_index].Referencia}</td>
                                <td></td>
                                <td style="text-align: center;">${formata_monetario(data[v_index].Descontos)}</td>
                            </tr>';
                            `
                        }
                    }
                    options += options_proventos
                    options += options_descontos
                    options += '</tbody>'

                    $('#tabela_valores').html(options);
                    $("#c_select_ano").hide();
                    $("#c_msg_autentica").show();
                    $("#c_msg_responsabilidade").show();

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

        function data_mes_ano(data) {
            let data_split = data.split('-')
            let data_formatada = `${data_split[1]}/${data_split[0]}`
            return data_formatada
        }

        function data_extenso(data) {
            let meses = [
                'Janeiro',
                'Fevereiro',
                'Março',
                'Abril',
                'Maio',
                'Junho',
                'Julho',
                'Agosto',
                'Setembro',
                'Outubro',
                'Novembro',
                'Dezembro'
            ]
            let data_split = data.split('-')
            // let dat = new Date(data_split[0], (data_split[1] - 1), data_split[2])
            let dat_extenso = `${meses[data_split[1] - 1]}/${data_split[0]}`
            return dat_extenso

        }

        function formata_data_hora(data_hora) {
            let dado = data_hora.split(' ')
            let data = dado[0].split('-')
            let hora = dado[1]
            let data_formatada = `${data[2]}/${data[1]}/${data[0]}`
            return {
                "data": data_formatada,
                "hora": hora
            }
        }

        function formata_data(data) {
            let data_split = data.split('-')
            let data_formatada = `${data_split[2]}/${data_split[1]}/${data_split[0]}`
            return data_formatada
        }

        function formata_monetario_sem_cifrao(valor) {
            let formato_monetario = {
                minimumFractionDigits: 2,
                style: 'currency',
                currency: 'BRL'
            }
            let valor_float = parseFloat(valor)
            return valor_float.toLocaleString(formato_monetario)
        }

        function formata_monetario(valor) {
            let formato_monetario = {
                minimumFractionDigits: 2,
                style: 'currency',
                currency: 'BRL'
            }
            let valor_float = parseFloat(valor)
            return valor_float.toLocaleString('pt-BR', formato_monetario)
        }

        function formata_cpf(cpf) {
            let cpf_str = cpf.toString()
            let cpf_11_digitos = cpf_str.padStart(11, '0')
            return cpf_11_digitos.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4")
        }

        function formata_cnpj(cnpj) {
            let cnpj_str = cnpj.toString()
            let cnpj_14_digitos = cnpj_str.padStart(14, '0')
            return cnpj_str.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5")
        }

        function goBack() {
            $("#detalhes_holerite").hide();
            $("#btn_acoes").hide();
            $("#paginacao").show();
            $("#c_msg_autentica").hide();
            $("#c_msg_responsabilidade").hide();
            $("#c_select_ano").show();
        }
    </script>
</body>


</html>