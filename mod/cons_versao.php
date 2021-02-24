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

    <div class="container" style="margin-top: 50px; padding: 30px;">

        <div class="row" style="border-width: 1px; border-style: solid;">
            <input id="c_acao" type="hidden" value="">
            <div class="col-sm-12">
                <table id="tabVersao" class="table">
                    <thead style="font-weight: bold;">
                        <tr>
                            <th>Versao</th>
                            <th>Titulo</th>                            
                        </tr>
                    </thead>
                    <tbody id="tabVersaob" style="font-weight: normal;">

                    </tbody>
                </table>

            </div>
        </div>

        <div class="row" style="border-width: 1px; border-style: none;">
            <div class="form-group col-sm-12">
                <h4></h4>
            </div>
        </div>
        <div class="row" style="border-width: 1px; border-style: none;">
            <div class="form-group col-sm-12">
                <h4></h4>
            </div>
        </div>
        <div class="row" style="border-width: 1px; border-style: solid; border-top-style: none;">
            <div class="col-sm-12">
                <table id="tabRelease" class="table">
                    <thead style="font-weight: bold;">
                        <tr>
                            <th>Versao</th>
                            <th>Release</th>
                            <th>Titulo</th>
                            <th>Data</th>
                            <th>Modulo</th>
                        </tr>
                    </thead>
                    <tbody id="tabReleaseb" style="font-weight: normal;">

                    </tbody>
                </table>

            </div>
        </div>

        <div class="row" style="border-width: 1px; border-style: none;">
            <div class="form-group col-sm-12">
                <h4></h4>
            </div>
        </div>
        <div class="row" style="border-width: 1px; border-style: none;">
            <div class="form-group col-sm-12">
                <h4></h4>
            </div>
        </div>

        <div class="row" style="border-width: 1px; border-style: solid;">
            <input id="c_acao" type="hidden" value="">
            <div class="col-sm-12">
                <table id="tabInformacoes" class="table">
                    <thead style="font-weight: bold;">
                     <tr>
                        <th>Informaçoes</th>
                     </tr>
                    </thead>
                    <tbody id="tabInformacoesb" style="font-weight: normal;">

                    </tbody>
                </table>

            </div>
        </div>

    </div>


</body>

<script language="JavaScript">
    $(document).ready(function() {
        func_carrega_tab();
    });

    function func_carrega_tab() {

        var v_acao = "LISTAR";
        $.ajax({
            type: "POST",
            url: "lib/lib_cons_versao.php",
            data: {
                "v_acao": v_acao
            },
            success: function(data) {
                var options = '';
                var v_index = 1;
                $("#tabVersaob").empty();
                for (v_index = 0; v_index < data.length; v_index++) {
                    options += '<tr  style="cursor: pointer;" onclick="func_select(\'' + data[v_index].Versao + '\');"><td>' + data[v_index].Versao+ '</td><td>' + data[v_index].Titulo + '</td></tr>';
                }

                $('#tabVersaob').html(options);

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

    function func_select(vj_versao) {

        var v_versao = vj_versao;

        $.ajax({
            type: "POST",
            url: "lib/lib_cons_versao.php",
            data: {
                "v_acao": "EV_CARREGA_VERSAO",
                "v_versao": v_versao
            },
            success: function(data) {

                var options = '';

                $("#tabReleaseb").empty();
                for (v_index = 0; v_index < data.length; v_index++) {
                    options += '<tr>  style="cursor: pointer;" onclick="func_select_info(\'' + data[v_index].Versao + '\');"><td>' + data[v_index].Versao + '</td><td>' + data[v_index].Alteracao + '</td><td>' + data[v_index].Descricao + '</td><td>' + data[v_index].Data_release + '</td><td>' + data[v_index].Modulo + '</td> </tr>';
                }

                $('#tabReleaseb').html(options);

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

function func_select_info(vj_versao) {

var v_versao = vj_versao;

$.ajax({
    type: "POST",
    url: "lib/lib_cons_versao.php",
    data: {
        "v_acao": "EV_CARREGA_INFORMACAO",
        "v_versao": v_versao
    },
    success: function(data) {

        var options = '';

        $("#tabInformacaob").empty();
        for (v_index = 0; v_index < data.length; v_index++) {
            options += '<tr><td>' + data[v_index].Informacao + '</td> </tr>';
        }

        $('#tabInformacaob').html(options);

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