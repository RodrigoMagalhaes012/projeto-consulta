<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0010") == 0) {
    print('<script> alert(\'Acesso Negado.\'); location.href = \'https://app.agrocontar.com.br\'; </script>');
}

?>
    <div class="container">

        <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-6">
                    <h3>Lista de Usuários</h3>
                </div>
                <div class="form-grupo col-sm-6 text-right">
                    <button id="btn_remover_selecao" onclick="func_desmarca_todos()" class="btn btn-danger" style="border-radius: 10px;">Desmarcar seleção</button>
                    <button id="btn_selecionar_todos" onclick="func_seleciona_todos()" class="btn btn-warning" style="border-radius: 10px;">Selecionar todos</button>
                    <button id="btn_enviar_email" onclick="func_envia_email()" class="btn btn-primary" style="border-radius: 10px;">Enviar redefinição de senha</button>
                </div>
            </div>
        </div>
        <div id="box_tab1" class="row" style="background-color: white; border: none; overflow-x: hidden;">
            <input id="c_acao" type="hidden" value="">
            <div class="box-body">
                <table id="tab1" class="table" style="width: 100%; color: black;">
                    <thead style="font-weight: bold;">
                        <tr>
                            <th>Selecionar</th>
                            <th>E-mail</th>
                            <th>Nome</th>
                            <th class="hidden">id</th>
                        </tr>
                    </thead>
                    <tbody id="tab1b" style="font-weight: normal;">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>


<script src="../class/DataTables/datatables.min.js"></script>
<script language="JavaScript">
    $(document).ready(function() {
        func_carrega_tab()
    })

    function func_carrega_tab(){

        $.ajax({
            url: 'lib/lib_redefine_senha.php',
            type: 'POST',
            data:{
                "v_acao": "CARREGA_USUARIOS"
            },
            success: function(data) {
                $('#tab1').DataTable().destroy();
                let dados = ''
                $("#tab1b").empty()
                data.forEach(element => {
                    dados += `
                        <tr>
                            <td><input type="checkbox" id="${element.id}"></td>
                            <td>${element.email}</td>
                            <td>${element.nome}</td>
                            <td class="hidden">${element.id}</td>
                        </tr>
                    `
                });
                $("#tab1b").html(dados)

                $("#tab1").DataTable({
                    "language": {
                        "url": "../class/DataTables/portugues.json",
                    },
                    "columnDefs": [{
                        "width": "10%",
                        "targets": 0,
                    }],
                    "lengthMenu": [
                        [-1],
                        ["Todos"]
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

    function func_seleciona_todos(){

        $('input:checkbox').prop("checked", true);

    }

    function func_desmarca_todos(){
        $('input:checkbox').prop("checked", false);
    }

    function func_envia_email(){

        let vet_dados = []
        let i = 0
        $('#tab1> tbody  > tr').each(function() {
        // aqui tem a linha (tr)
            let linha = $(this);
            let children = linha.context.children

            if($(`#${children[3].innerHTML}`).prop('checked')){
                vet_dados.push(children[1].innerHTML)
            }
        });

        if(vet_dados.length > 0){
            Swal.fire({
                title: 'Você tem certeza?',
                text: "Você enviará email de recuperação para os usuários selecionados!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, enviar!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "lib/lib_redefine_senha.php",
                        data: {
                            "v_acao": "ENVIAR_EMAIL",
                            "v_emails": vet_dados 
                        },
                        success: function(data) {
                            var v_json = JSON.parse(data);
                            Swal.fire(
                                v_json.msg_titulo,
                                v_json.msg,
                                v_json.msg_ev
                            )
                            $('input:checkbox').prop("checked", false);
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
            })
        }else{
            Swal.fire({
                icon: "error",
                title: "FALHA!",
                text: "Selecione pelo menos um usuário para enviar o email de recuperação de senha."
            })
        }

    }



</script>