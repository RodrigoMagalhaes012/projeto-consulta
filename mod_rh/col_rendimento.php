<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0082") == 0) {
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

    <title>Documentos</title>
</head>

<body>
    <div class="container" style="margin: 0px; padding: 0px;">
        <div id="box_form_titulo" class="row" style="margin-top: 40px; background-image: linear-gradient(to left, #6c3a8e , white);">
            <div class="row">
                <div class="form-group col-sm-12 col" style="font-weight: bold; font-size: 25px; color: #523B8F;">Informe de Rendimentos</div>
            </div>
        </div>
        <div id="box_tab1" class="row" style="padding: 10px; border-width: 1px; border-style: solid;">
            <input id="c_acao" type="hidden" value="">
            <table style="width: 100%; color:black;" id="tab1" class="table">
                <thead style="font-weight: bold;">
                    <tr>
                        <th>
                            <h4>Competência</h4>
                        </th>
                        <th>
                            <h4>Ações</h4>
                        </th>
                        <th>
                            <h4>Mensagens</h4>
                        </th>
                    </tr>
                </thead>
                <tbody id="tab1b" style="font-weight: normal;">
                </tbody>
            </table>
        </div>
    </div>
</body>
<script src="../class/DataTables/datatables.min.js"></script>
<script language="JavaScript">
    $(document).ready(function() {
        func_carrega_tab();
    });

    function func_carrega_tab() {

        $.ajax({
            type: "POST",
            url: "lib/lib_informe_rendimento.php",
            data: {
                "v_acao": "LISTAR_DOCUMENTOS"

            },
            success: function(data) {

                $('#tab1').DataTable().destroy();

                $("#tab1b").empty();

                let options = '';



                data.forEach(element => {

                    let v_ciencia = ""

                    console.log(v_ciencia);


                    if (element.data_autenticacao) {
                        let data_hora = formata_data_hora(element.data_autenticacao)
                        v_ciencia = `<h6 id="c_msg_autentica_${element.ano_referencia}">Deu ciência digitalmente em ${data_hora.data} às ${data_hora.hora} GMT-03:00. Usuário: ${element.usuario}. IP: ${element.ip}</h6>`;
                    } else {
                        v_ciencia = `<h6 id="c_msg_autentica_${element.ano_referencia}"> Não deu ciência!</h6>`;
                    }
                    // let data_hora = formata_data_hora(data.data_hora);
                    // let texto_autenticacao = `Deu ciência digitalmente em ${data_hora.data} às ${data_hora.hora} GMT-03:00. Usuário: ${data.usuario}. IP: ${data.ip}`;

                    options += `<tr>
                                    <td>
                                        ${element.descricao}
                                    </td>
                                    <td>
                                    <button  target="_blank" onClick="javascript:window.open('${element.url}','_blank');"
                                            id="btn_novo_download" class="btn is-icon btn-outline-primary" title="Baixar Informe de Rendimento">
                                                <span class="button-text">
                                                    <i class="fa fa-cloud-download fa-1x"></i>
                                                </span>
                                    </button>
                                    <button ${element.data_autenticacao ? 'disabled' : ''} id="btn_autentica_${element.ano_referencia}" class="btn is-icon btn-outline-primary" title="Dar Ciência"
                                            onclick="func_autentica('${element.id_empresa}', '${element.cpf}', '${element.id}', this)">
                                                <span class="button-text">
                                                    <i class="fa fa-pencil  fa-1x" aria-hidden="true">${element.data_autenticacao ? 'Ciente' : 'Dar Ciência'}</i>
                                                </span>
                                    </button>
                                    </td>
                                    <td>
                                            ${v_ciencia}
                                    </td>
                                </tr>`
                    console.log(element.ano_referencia);
                });

                $('#tab1b').html(options);

                $("#tab1").DataTable({
                    "language": {
                        "url": "../class/DataTables/portugues.json",
                    },
                    "columnDefs": [{
                        "width": "40%",
                        "targets": 0,
                    }],
                    "lengthMenu": [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "Todos"]
                    ],
                    "order": [
                        [0, "asc"]
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
                    text: "Problema ocorrido: " + status + "\nDescição: " + erro +
                        "\nInformações da requisição: " + request.responseText
                })
            }
        });
    }


    function func_download_rendimento() {
        $.ajax({
            type: "POST",
            url: "lib/lib_informe_rendimento.php",
            data: {
                "v_acao": "DOWNLOAD_INFORME"

            },
            success: function(data) {

                window.open(`documentos/informes_rendimento/${data.cnpj}_${data.cpf}_2020.pdf`, '_blank')

            },
            error: function(request, status, erro) {
                Swal.fire({
                    icon: "error",
                    title: "FALHA!",
                    text: "Problema ocorrido: " + status + "\nDescição: " + erro +
                        "\nInformações da requisição: " + request.responseText
                })
            }
        });
    }


    function func_autentica(empresa, cpf, v_id_informe, botao) {

        // let v_id_informe = v_id_informe;

        $.ajax({
            type: "POST",
            url: "lib/lib_informe_rendimento.php",
            data: {
                "v_acao": "EV_AUTENTICA",
                "v_id_empresa": empresa,
                "v_cpf": cpf,
                "v_id_informe": v_id_informe
            },
            success: function(data) {


                $("#btn_autentica_" + data.ano_referencia).prop('disabled', true)

                console.log($("#btn_autentica_" + data.ano_referencia));


                let data_hora = formata_data_hora(data.data_hora)
                let texto_autenticacao = `Deu ciência digitalmente em ${data_hora.data} às ${data_hora.hora} GMT-03:00. Usuário: ${data.usuario}. IP: ${data.ip}`

                $(`#c_msg_autentica_${data.ano_referencia}`).html(texto_autenticacao);
                $(`#${botao.id}`).html(`<span class="button-text">
                        <i class="fa fa-pencil  fa-1x" aria-hidden="true">Ciente</i>
                    </span>`)

                Swal.fire({
                    icon: "success",
                    title: "SUCESSO!",
                    text: "Informe Autenticado com Sucesso!"
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
</script>