<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0089") == 0) {
    print('<script> alert(\'Acesso Negado.\'); location.href = \'https://app.agrocontar.com.br\'; </script>');
}
?>
<div class="container">
    <div id="tabela_resultados">
        <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-12">
                    <h3>Sugestões</h3>
                </div>

            </div>
        </div>
        <div id="box_tab1" class="row" style="height: auto; background-color: white; border: none; overflow-x: hidden;">
            <input id="c_acao" type="hidden" value="">
            <div class="box-body">
                <table id="tab1" class="table" style="width: 100%; color: black;">
                    <thead style="font-weight: bold;">
                        <tr>
                            <th>Sugestão</th>
                            <th>Nome</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody id="tab1b" style="font-weight: normal;">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="tabela_colaboradores" style="display: none;">
        <input id="c_id_grupo" type="hidden" value="">
        <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-12">
                    <h3>Colaboradores</h3>
                </div>
            </div>
        </div>
        <div class="row" style="color: black; height: auto; background-color: white; border: none; overflow-x: hidden;">
            <div class="col-sm-12">
                <select multiple="multiple" size="10" id="listbox-colab">

                </select>
            </div>
        </div>
        <div class="box-footer">
            <div class="row">
                <div class="form-group col-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
                    <button id="btn_novo_reg" class="btn btn-success" style="border-radius: 10px; width: 100px;" onclick="func_salvar()">Salvar</button>
                    <!-- <button disabled id="btn_excluir_reg" class="btn btn-danger" style="border-radius: 10px; width: 100px;" onclick="func_excluir_registro()">Excluir</button> -->
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../class/DataTables/datatables.min.js"></script>
<script src="../class/dual-listbox/src/jquery.bootstrap-duallistbox.js"></script>
<script language="JavaScript">
    $(document).ready(function() {
        func_carrega_tab();
    });

    function func_carrega_tab() {

        $.ajax({
            type: "POST",
            url: "lib/lib_cons_sugestoes.php",
            data: {
                "v_acao": 'LISTAR_SUGESTOES'
            },
            success: function(data) {

                // console.log(data)
                $('#tab1').DataTable().destroy();
                var options = '';
                $("#tab1b").empty();

                data.forEach(element => {
                    options += `<tr style="cursor: pointer;">
                        <td>${element.sugestao.replace(/\\/g, "")} </td>
                        <td>${element.usuario}</td>
                        <td>${element.data}</td>
                    </tr>`
                });

                $('#tab1b').html(options);

                $("#tab1").DataTable({
                    "language": {
                        "url": "../class/DataTables/portugues.json",
                    },
                    "columnDefs": [{
                        "width": "15%",
                        "targets": [1,2],
                    }],
                    "lengthMenu": [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "Todos"]
                    ],
                    "order": [
                        [2, "desc"]
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
</script>