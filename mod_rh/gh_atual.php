<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0024") == 0) {
    print('<script> alert(\'Acesso Negado.\'); location.href = \'https://app.agrocontar.com.br\'; </script>');
}

?>

<div class="container">
    <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
        <div class="row">
            <div class="form-group col-sm-6">
                <h3 id="c_titulo">Gestão Atual</h3>
            </div>
        </div>
    </div>
    <div id="box_tab1" class="box" style="height: auto; background-color: white; border: none; overflow-x: hidden;">
        <input id="c_acao" type="hidden" value="">
        <div class="box-body">
            <input type="hidden" id="vf_tab_sql_limit_in" value="0">
            <input type="hidden" id="vf_tab_btn_pag_select" value="1">

            <table id="tab1" class="table" style="width: 100%; color: black;">
                <thead style="font-weight: bold;">
                    <tr>
                        <th>Id</th>
                        <th>Função</th>
                        <th>Colaborador</th>
                        <th>Lider</th>
                    </tr>
                </thead>
                <tbody id="tab1b" style="font-weight: normal;">

                </tbody>
            </table>
            <table style="display: none;" id="tab_hier" class="table">
                <thead style="font-weight: bold;">
                    <tr>
                        <th>Função</th>
                        <th>Hierarquia</th>
                    </tr>
                </thead>
                <tbody id="tab_hier_corpo" style="font-weight: normal;">

                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="../class/DataTables/datatables.min.js"></script>
<script>
    $(document).ready(function() {
       func_carrega_tab()
    });

    function func_carrega_tab(){

        $.ajax({
            type: "POST",
            url: "lib/lib_gh_atual.php",
            data: {
                "v_acao": "EV_CARREGAR",
                "v_grupo": localStorage.getItem('grupo_gh')
            },
            success: function(data) {
                let options = ''
                $("#tab1b").empty()
                data.forEach(element => {
                    options += `<tr style="cursor: pointer;" onclick="func_visualizar_hist(${element.Id})">
                        <td>${element.Id}</td>
                        <td>${element.Gestao}</td>
                        <td>${element.Gestor}</td>
                        <td>${element.Lider}</td>
                    </tr>`
                });
                $("#tab1b").html(options)

                $("#tab1").DataTable({
                    "language": {
                        "url": "../class/DataTables/portugues.json",
                    },
                    "columnDefs": [{
                        "width": "15%",
                        "targets": 1,
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

    function func_visualizar_hist(v_id){
        $.ajax({
            type: "POST",
            url: "lib/lib_hist_gh.php",
            data: {
                "v_acao": "EV_VIS_HIST",
                "v_id": v_id,
                "v_grupo": localStorage.getItem('grupo_gh')
            },
            success: function(data) {
                $("#tab1").hide()
                $("#tab_hier").show()

                let options = ''
                $("#tab_hier_corpo").empty()
                data.forEach(element => {
                    options += `<tr style="cursor: pointer;">
                        <td>${element.Funcao}</td>
                        <td>${element.Descricao_hierarquia}</td>
                    </tr>`
                });
                $("#tab_hier_corpo").html(options)
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
</script>