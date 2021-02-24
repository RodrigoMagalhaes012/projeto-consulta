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
                <div class="form-group col-sm-12 col" style="font-weight: bold; font-size: 25px; color: #523B8F;">Informes de Rendimentos</div>
            </div>
        </div>
        <div id="box_tab1" class="row" style="padding: 10px; border-width: 1px; border-style: solid;">
            <input id="c_acao" type="hidden" value="">
            <table style="width: 100%; color:black;" id="tab1" class="table">
                <thead style="font-weight: bold;">
                    <tr>
                        <th>
                            <h4>Colaborador</h4>
                        </th>
                        <th>
                            <h4>Competencia</h4>
                        </th>
                        <th>
                            <h4>Ações</h4>
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
            url: "lib/lib_informe_rendimento_adm.php",
            data: {
                "v_acao": "LISTAR_DOCUMENTOS"

            },
            success: function(data) {

                $('#tab1').DataTable().destroy();

                $("#tab1b").empty();

                let options = '';



                data.forEach(element => {


                    // let v_ciencia = ""

                    // console.log(v_ciencia);


                    // if (element.data_autenticacao) {
                    //     let data_hora = formata_data_hora(element.data_autenticacao)
                    //     v_ciencia = `<h5 id="c_msg_autentica_${element.ano_referencia}">Deu ciência digitalmente em ${data_hora.data} às ${data_hora.hora} GMT-03:00. Usuário: ${element.usuario}. IP: ${element.ip}</h5>`
                    //     console.log(v_ciencia);
                    // } else {
                    //     v_ciencia = `<h5 id="c_msg_autentica_${element.ano_referencia}"> Não deu ciência!</h5>`
                    //     console.log(v_ciencia);
                    // }

                    options += `<tr>
                                    <td>
                                            ${element.nome_col}
                                    </td>
                                    </td>
                                    <td>
                                            ${element.descricao}                                  
                                    </td>                                    
                                    <td style="text-align: center;">
                                            <div class="btn-group" style="border: 0px; margin: 0px;">
                                                <button class="btn is-icon btn-outline-primary" onclick="func_excluir(${element.id})" title="Excluir documento">
                                             <span class="button-text">
                                                     <i class="fa fa-trash fa-1x" style="color:red;"></i>
                                             </span>
                                                </button>
                                                <button target="_blank" onClick="javascript:window.open('${element.url}','_blank');"
                                                    id="btn_novo_download_${element.ano_referencia}" class="btn is-icon btn-outline-primary" title="Baixar holerite">
                                            <span class="button-text">
                                                    <i class="fa fa-cloud-download fa-1x"></i>
                                            </span>
                                                </button>
                                                 <button style="color: ${element.data_autenticacao ? 'green' : 'red'};" disabled id="btn_autentica_${element.ano_referencia}" class="btn is-icon btn-outline-primary" title="Dar Ciência">
                                            <span class="button-text">
                                                    <i class="fa fa-pencil  fa-1x" aria-hidden="true">${element.data_autenticacao ? 'Ciente' : 'Não Ciente'}</i>
                                            </span>
                                                 </button>
                                            </div>
                                     </td>
                                </tr>`

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



    function func_autentica(empresa, cpf, v_id_informe, botao) {

        // let v_id_informe = v_id_informe;

        $.ajax({
            type: "POST",
            url: "lib/lib_informe_rendimento_adm.php",
            data: {
                "v_acao": "EV_AUTENTICA",
                "v_id_empresa": empresa,
                "v_cpf": cpf,
                "v_id_informe": v_id_informe
            },
            success: function(data) {


                $(`#btn_autentica_${data.data_autenticacao}`).prop('disabled', true)

                let data_hora = formata_data_hora(data.data_hora)
                let texto_autenticacao = `Deu ciência digitalmente em ${data_hora.data} às ${data_hora.hora} GMT-03:00. Usuário: ${data.usuario}. IP: ${data.ip}`

                $("#c_msg_autentica").html(texto_autenticacao);
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



    function func_excluir(id) {

        Swal.fire({
            title: 'Você tem certeza que deseja excluir?',
            text: "Você irá excluir o Informe de Rendimento do colaborador!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, excluir!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'lib/lib_upload_informe_rendimento.php',
                    type: 'POST',
                    data: {
                        "v_id_informe": id,
                        "v_acao": "EXCLUIR_INFORME"
                    },
                    success: function(data) {
                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        if (v_json.msg_ev == "success") {
                            func_carrega_tab()
                        }
                    },
                    error: function(request, status, erro) {
                        Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }
                });
            }
        })

    }
</script>