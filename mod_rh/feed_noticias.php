<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0031") == 0) {
    print('<script> alert(\'Acesso Negado.\'); location.href = \'https://app.agrocontar.com.br\'; </script>');
}

?>

<link rel="stylesheet" href="../css/novo_feed.css">
<link rel="stylesheet" href="../css/colapse.css">

<style>
    #titulo {
        padding-top: 15px;
        padding-bottom: 4px;
        font-size: 24px;
        /* border-radius: 25px; */
        background-color: #49c095;
        font-style: italic;
        color: white;
        font-weight: bold;
        margin-top: 4px;
        margin-bottom: 8px;

    }

    .icone {
        color: #fff;
        display: block;
        line-height: 20%;
        position: absolute;
        text-align: center;
        width: 5%;
        color: #6c3a8e;
        background-attachment: scroll;
        left: 545px;
        top: 21px;
        z-index: 1;
        opacity: 80%;
        font-size: 30px;


    }

    .icone_2 {
        color: #fff;
        display: block;
        line-height: 20%;
        position: absolute;
        text-align: center;
        width: 5%;
        color: #6c3a8e;
        background-attachment: scroll;
        left: 580px;
        top: 20px;
        z-index: 1;
        opacity: 80%;
        font-size: 30px;

    }

    .icone_3 {
        color: #fff;
        display: block;
        line-height: 20%;
        position: absolute;
        text-align: center;
        width: 5%;
        color: #6c3a8e;
        background-attachment: scroll;
        left: 618px;
        top: 21px;
        z-index: 1;
        opacity: 80%;
        font-size: 30px;

    }

    /* icone_1 */
    /* unvisited link */
    h1_1:link {
        color: gray;
    }

    /* visited link */
    h1_1:visited {
        color: green;
    }

    /* mouse over link */
    h1_1:hover {
        color: red;
    }

    /* selected link */
    h1_1:active {
        color: gray;
    }

    /* icone_2 */

    /* unvisited link */
    h1_2:link {
        color: white;
    }

    /* visited link */
    h1_2:visited {
        color: green;
    }

    /* mouse over link */
    h1_2:hover {
        color: #bafbfc;
    }

    /* selected link */
    h1_2:active {
        color: white;
    }

    /* icone_3*/

    /* unvisited link */
    h1_3:link {
        color: white;
    }

    /* visited link */
    h1_3:visited {
        color: green;
    }

    /* mouse over link */
    h1_3:hover {
        color: #3b824b;
    }

    /* selected link */
    h1_3:active {
        color: white;
    }

    /* The grid: Three equal columns that floats next to each other */
    .column {
        float: left;
        width: 33.33%;
        padding: 50px;
        text-align: center;
        font-size: 25px;
        cursor: pointer;
        color: white;
    }

    .containerTab {
        padding: 20px;
        color: white;
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }

    /* Closable button inside the image */
    .closebtn {
        float: right;
        color: white;
        font-size: 35px;
        cursor: pointer;
    }

    .btn-info {
        background-color: #913b8e;

    }
</style>
<div class="container">
    <div id="box_form_titulo" class="row" style="margin-top: 40px; background-image: linear-gradient(to left, #6c3a8e , white); z-index: -1 ">
        <!------ Include the above in your HEAD tag ---------->
        <div class="row">

            <div class="col-sm-10" style="font-weight: bold; font-size: 25px; color: #523B8F;">Feed de Notícias</div>
            <div class="col-sm-2">
                <!-- Large modal -->
                <a onclick="func_nova_noticia()" class="btn btn btn-custom" style="padding: 1px 15px 3px 2px; border-radius: 50px; background-color: #523B8F; color: white;">
                    <span class="glyphicon glyphicon-new-window img-circle text-success btn-icon" style="padding: 6px; background: #ffffff;"></span>
                    Nova Publicação
                </a>
            </div>

        </div>

        <div class="box" style="height: 80vh; width: 100%; overflow-y:auto; border: none; background-color: white;">
            <div id="noticias" class="friend-list" style="background-color: white; opacity: 95%;">

            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #6c3a8e;">
                <h5 id="c_titulo_modal" class="modal-title" style="text-align:center; margin-top: 5px; font-weight: bold; color: white;">Nova Publicação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="formulario">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="c_titulo" class="text-center">Título</label>
                                <input type="text" class="form-control" id="c_titulo" style=" border-radius: 5px;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="text-left" for="arquivo">Upload de arquivo</label>
                                <input type="file" name="arquivo">
                                <p class="help-block text-left">Arquivo contendo a imagem desejada.</p>
                                <!-- <div>
                                        <input id="img-input" type="file" name="imagem">
                                    </div>
                                    <img id="preview" src=""> -->
                                <!-- <div>
                                    <input type="submit" value="Enviar">
                                </div> -->
                            </div>
                            <!-- <div class="form-group col-sm-9">
                                <label for="c_conteudo" style="text-align: center;">Texto</label>
                                <textarea type="text" class="form-control" id="c_conteudo" rows="8" style="margin-top: 22px; max-height: 150px"></textarea>
                            </div> -->
                        </div>
                    </form>
                    <div class="form-group col-sm-12">
                        <div id="editor" style="color: black;">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a id="btn_publicar" class="btn btn-success btn-custom" style="padding: 1px 15px 3px 2px; border-radius: 50px;">
                    <span class="glyphicon glyphicon-new-window img-circle text-success btn-icon" style="padding: 8px;background: #ffffff;"></span>
                    Publicar
                </a>
                <button type="button" class="btn btn-warning btn-custom" data-dismiss="modal" style="padding: 1px 15px 3px 2px; border-radius: 50px;">
                    <span class="glyphicon glyphicon-remove img-circle text-warning btn-icon" style="padding: 8px; background: #ffffff;"></span>
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modelId_2" tabindex="-1" role="dialog" aria-labelledby="modelTitleId2" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #6c3a8e;">
                <h5 id="titulo_comentarios" class="modal-title" style="text-align:center; margin-top: 5px; font-weight: bold; color: white;"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="form-group">
                                <textarea id="c_comentario" class="form-control" placeholder="Comente aqui..." rows="3"></textarea>
                            </div>
                            <div class="row">

                                <div class="col-sm-8"></div>
                                <div class="col-sm-2 text-right">
                                    <button id="btn_novo_comentario" type="button" class="btn btn-warning btn-custom hidden" style="padding: 1px 15px 3px 2px; border-radius: 50px; font-size: 12px">
                                        <span class="glyphicon glyphicon-comment img-circle text-success btn-icon" style="padding: 3px; background: #ffffff;"></span>
                                        Novo comentário
                                    </button>
                                </div>
                                <div class="col-sm-2 text-right">
                                    <button id="btn_comentar" type="button" class="btn btn-success btn-custom" style="padding: 1px 15px 3px 2px; border-radius: 50px; font-size: 12px">
                                        <span class="glyphicon glyphicon-sunglasses img-circle text-success btn-icon" style="padding: 3px; background: #ffffff;background: #ffffff;"></span>
                                        Comentar
                                    </button>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <hr class="margin-bottom-10">
                            <ul id="comentarios" class="list-group list-group-dividered list-group-full">

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        func_carrega_noticias()
        quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    ['link']
                ]
            }
        });
    })

    // function readImage() {
    //     if (this.files && this.files[0]) {
    //         var file = new FileReader();
    //         file.onload = function(e) {
    //             document.getElementById("preview").src = e.target.result;
    //         };
    //         file.readAsDataURL(this.files[0]);
    //     }
    // }
    // document.getElementById("img-input").addEventListener("change", readImage, false);

    function func_verifica_autorizacao() {
        $.ajax({
            type: "POST",
            url: "lib/lib_noticias.php",
            data: {
                "v_acao": "VERIFICAR_USUARIO_AUTORIZADO"
            },
            success: function(data) {



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

    function func_carrega_noticias() {

        $.ajax({
            type: "POST",
            url: "lib/lib_noticias.php",
            data: {
                "v_acao": "LISTAR_NOTICIAS"
            },
            success: function(data) {
                $("#noticias").empty()
                let noticias = ''

                data.map((element, index) => {
                    noticias += `
                    <div class="row" style="background-color: white; ">
                        <div class="col-sm-4" style="margin-top: 15px; margin-left: 15vw">
                            <div class="friend-card" style="box-shadow: 2px 2px 2px 1px rgba(108,58,142, 1); width: 185%; background-color: white; opacity: 100%;">
                                <img src="${element.imagem}" class="img-responsive cover" style="width: 100%">
                                <div class="card-info">
                                    <img src="${element.foto_usuario ? element.foto_usuario : "https://testephp.s3.amazonaws.com/usuario_padrao.png" }" alt="user" class="profile-photo-lg img-responsive">
                                    <div class="friend-info">
                                        <div class="pull-right">${element.data_publicacao}</div>
                                        <div>${element.usuario}</div>

                                        <div class="wrap-collabsible">
                                            <input id="collapsible-${element.id}" class="toggle" type="checkbox">
                                            <label style="font-size: 20px; margin-top: 20px; " for="collapsible-${element.id}" class="lbl-toggle">${element.titulo}</label>
                                            <div class="collapsible-content">
                                                <div id="conteudo-${element.id}" class="content-inner" style="color: black;">
                                                    ${element.conteudo.toString().replace(/\\/g, '')}
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row" style="margin-top: 4px;">
                                        <div class="col-sm-3">
                                            <button onclick="func_exibe_comentarios(${element.id})" type="button" class="btn btn-success btn-custom" data-dismiss="modal" style="padding: 1px 15px 3px 2px; border-radius: 50px; font-size: 12px">
                                                <span class="glyphicon glyphicon-comment img-circle text-success btn-icon" style="padding: 3px; background: #ffffff;"></span>
                                                Comentar
                                            </button>
                                        </div>
                                        <div class="col-sm-1">
                                            <button type="button" onclick="func_reagir(1, ${element.id})" class="btn btn-default btn-custom" data-dismiss="modal" style="padding: 0px 0px 0px 0px; border-radius: 50px; font-size: 12px">
                                                <span class="img-circle text-danger btn-icon" style="padding: 0px;"><img src="../img/reacoes/amei.png" alt="Amei" width="25">${element.reacoes.reacao_1 ? element.reacoes.reacao_1 : ''}</span>
                                            </button>
                                        </div>
                                        <div class="col-sm-1">
                                            <button type="button" onclick="func_reagir(2, ${element.id})" class="btn btn-default btn-custom" data-dismiss="modal" style="padding: 0px 0px 0px 0px; border-radius: 50px; font-size: 12px">
                                                <span class="img-circle text-danger btn-icon" style="padding: 0px;"><img src="../img/reacoes/rsrs.png" alt="Risadas" width="25">${element.reacoes.reacao_2 ? element.reacoes.reacao_2 : ''}</span>
                                            </button>
                                        </div>
                                        <div class="col-sm-1">
                                            <button type="button" onclick="func_reagir(3, ${element.id})" class="btn btn-default btn-custom" data-dismiss="modal" style="padding: 0px 0px 0px 0px; border-radius: 50px; font-size: 12px">
                                                <span class="img-circle text-danger btn-icon" style="padding: 0px;"><img src="../img/reacoes/fofo.png" alt="Fofo" width="25">${element.reacoes.reacao_3 ? element.reacoes.reacao_3 : ''}</span>
                                            </button>
                                        </div>
                                        <div class="col-sm-1">
                                            <button type="button" onclick="func_reagir(4, ${element.id})" class="btn btn-default btn-custom" data-dismiss="modal" style="padding: 0px 0px 0px 0px; border-radius: 50px; font-size: 12px">
                                                <span class="img-circle text-danger btn-icon" style="padding: 0px;"><img src="../img/reacoes/risos.png" alt="Risos" width="25">${element.reacoes.reacao_4 ? element.reacoes.reacao_4 : ''}</span>
                                            </button>
                                        </div>
                                        <div class="col-sm-1">
                                            <button type="button" onclick="func_reagir(5, ${element.id})" class="btn btn-default btn-custom" data-dismiss="modal" style="padding: 0px 0px 0px 0px; border-radius: 50px; font-size: 12px">
                                                <span class="img-circle text-danger btn-icon" style="padding: 0px;"><img src="../img/reacoes/ruim.png" alt="Não gostei" width="25">${element.reacoes.reacao_5 ? element.reacoes.reacao_5 : ''}</span>
                                            </button>
                                        </div>
                                        <div class="col-sm-4 text-right">
                                            <button onclick="func_seleciona_noticia(${element.id})" type="button" class="btn btn-warning btn-custom" data-dismiss="modal" style="padding: 1px 15px 3px 2px; border-radius: 50px; font-size: 12px">
                                                <span class="glyphicon glyphicon-pencil img-circle text-warning btn-icon" style="padding: 3px; background: #ffffff;"></span>
                                                Editar
                                            </button>
                                            <button onclick="func_excluir_noticia(${element.id})" type="button" class="btn btn-danger btn-custom" data-dismiss="modal" style="padding: 1px 15px 3px 2px; border-radius: 50px; font-size: 12px">
                                                <span class="glyphicon glyphicon-trash img-circle text-warning btn-icon" style="padding: 3px; background: #ffffff;"></span>
                                                Excluir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    `
                })
                $("#noticias").html(noticias)
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

    function func_reagir(reacao, id_noticia) {

        $.ajax({
            type: "POST",
            url: "lib/lib_noticias.php",
            data: {
                "v_acao": "REAGIR",
                "v_reacao": reacao,
                "v_noticia": id_noticia
            },
            success: function(data) {
                var v_json = JSON.parse(data);
                if (v_json.msg_ev == "success") {
                    func_carrega_noticias();
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
    }

    function func_publicar_noticia() {

        Swal.fire({
            title: 'Publicando notícia!',
            text: "A notícia está sendo publicada e logo poderá ser exibida para os usuários.",
            icon: 'warning',
            showCancelButton: false,
            showConfirmButton: false,
            closeOnConfirm: false, //It does close the popup when I click on close button
            closeOnCancel: false,
            allowOutsideClick: false
        })

        let formulario = document.getElementById('formulario');

        // Instância o FormData passando como parâmetro o formulário
        let formData = new FormData(formulario);

        formData.append("v_acao", 'PUBLICAR_NOTICIA')
        // formData.append("v_conteudo", JSON.stringify(quill.getContents()))
        formData.append("v_conteudo", quill.root.innerHTML)
        formData.append("v_titulo", $("#c_titulo").val())

        $.ajax({
            type: "POST",
            url: "lib/lib_noticias.php",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {

                var v_json = JSON.parse(data);
                Swal.fire(
                    v_json.msg_titulo,
                    v_json.msg,
                    v_json.msg_ev
                )

                if (v_json.msg_ev == "success") {
                    func_carrega_noticias();
                    $("#modelId").modal('hide')
                    $("#c_conteudo").val('')
                    $("#c_titulo").val('')
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
    }

    function func_excluir_noticia(id) {

        Swal.fire({
            title: 'Você tem certeza que deseja excluir?',
            text: "Você irá excluir a noticia!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, excluir!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'lib/lib_noticias.php',
                    type: 'POST',
                    data: {
                        "v_id_noticia": id,
                        "v_acao": "EXCLUIR_NOTICIA"
                    },
                    success: function(data) {
                        // console.log(data);
                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        if (v_json.msg_ev == "success") {
                            func_carrega_noticias()
                        }
                    },
                    error: function(request, status, erro) {
                        Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }
                });
            }
        })

    }

    function func_alterar_noticia(id) {

        Swal.fire({
            title: 'Você tem certeza que deseja alterar?',
            text: "Você irá alterar a noticia!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, alterar!'
        }).then((result) => {
            if (result.value) {

                Swal.fire({
                    title: 'Publicando notícia!',
                    text: "A notícia está sendo publicada e logo poderá ser exibida para os usuários.",
                    icon: 'warning',
                    showCancelButton: false,
                    showConfirmButton: false,
                    closeOnConfirm: false, //It does close the popup when I click on close button
                    closeOnCancel: false,
                    allowOutsideClick: false
                })

                let formulario = document.getElementById('formulario');

                // Instância o FormData passando como parâmetro o formulário
                let formData = new FormData(formulario);

                formData.append("v_acao", 'ALTERAR_NOTICIA')
                // formData.append("v_conteudo", JSON.stringify(quill.getContents()))
                formData.append("v_conteudo", quill.root.innerHTML)
                formData.append("v_titulo", $("#c_titulo").val())
                formData.append("v_id_noticia", id)

                $.ajax({
                    url: 'lib/lib_noticias.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        // console.log(data);
                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        if (v_json.msg_ev == "success") {
                            func_carrega_noticias()
                        }

                        $("#modelId").modal('hide')
                        $("#c_conteudo").val('')
                        $("#c_titulo").val('')
                    },
                    error: function(request, status, erro) {
                        Swal.fire("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
                    }
                });
            }
        })

    }

    function func_seleciona_noticia(id) {

        $.ajax({
            type: "POST",
            url: "lib/lib_noticias.php",
            data: {
                "v_acao": "SELECIONAR_NOTICIA",
                "v_id_noticia": id
            },
            success: function(data) {
                
                if(data.msg_ev == "error"){
                    Swal.fire(
                        data.msg_titulo,
                        data.msg,
                        data.msg_ev
                    )
                }else{
                    quill.root.innerHTML = data.conteudo
    
                    // $("#editor").html()
                    $("#c_titulo").val(data.titulo)
                    $("#c_titulo_modal").html('Alterar noticia')
    
                    $('#btn_publicar').attr('onclick', `func_alterar_noticia(${id})`)

                    $("#modelId").modal("show")
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
    }

    function func_nova_noticia() {

        // quill = new Quill('#editor', {
        //     theme: 'snow'
        // });

        $.ajax({
            type: "POST",
            url: "lib/lib_noticias.php",
            data: {
                "v_acao": "VERIFICA_AUTORIZACAO"
            },
            success: function(data) {

                var v_json = JSON.parse(data);

                if (v_json.msg_ev == "success") {
                    quill.root.innerHTML = ""
                    $("#c_titulo").val('')
                    $("#c_titulo_modal").html('Publicar nova noticia')

                    $('#btn_publicar').attr('onclick', 'func_publicar_noticia()')

                    $("#modelId").modal('show')
                } else if (v_json.msg_ev == "error") {
                    Swal.fire(
                        v_json.msg_titulo,
                        v_json.msg,
                        v_json.msg_ev
                    )
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
    }

    function func_exibe_comentarios(id) {
        $.ajax({
            type: "POST",
            url: "lib/lib_noticias.php",
            data: {
                "v_acao": "EXIBIR_COMENTARIOS",
                "v_id_noticia": id
            },
            success: function(data) {

                $("#comentarios").empty()
                let comentatios = ''
                data.forEach(element => {
                    let acoes = ''
                    if (element.pertence_usuario) {
                        acoes = `
                        <div class="row">
                            <div class="col-sm-9"></div>
                            <div class="col-sm-3 text-right">
                                <button onclick="func_carrega_comentario(${element.id}, ${id})" type="button" class="btn btn-warning btn-custom" style="padding: 1px 15px 3px 2px; border-radius: 50px; font-size: 10px">
                                    <span class="glyphicon glyphicon-pencil img-circle text-warning btn-icon" style="padding: 3px; background: #ffffff;"></span>
                                    Editar
                                </button>
                                <button onclick="func_excluir_comentario(${element.id}, ${id})" type="button" class="btn btn-danger btn-custom" style="padding: 1px 15px 3px 2px; border-radius: 50px; font-size: 10px">
                                    <span class="glyphicon glyphicon-trash img-circle text-warning btn-icon" style="padding: 3px; background: #ffffff;"></span>
                                    Excluir
                                </button>
                            </div>
                        </div>
                        `
                    }

                    comentatios += `
                    <li class="list-group-item">
                        <div class="media">
                            <div class="media-left">
                                <a class="avatar avatar-busy" href="javascript:void(0)">
                                    <img src="${element.foto_usuario ? element.foto_usuario : "https://testephp.s3.amazonaws.com/usuario_padrao.png" }">
                                    <i></i>
                                </a>
                            </div>
                            <div class="media-body">
                                <small class="text-muted pull-right">${element.data_publicacao}</small>
                                <h4 class="media-heading">${element.nome_usuario}</h4>
                                <div id="conteudo-comentario-${element.id}">${element.conteudo}</div>
                                ${acoes}
                            </div>
                        </div>
                    </li>
                    `
                });
                $("#c_comentario").val('')
                $("#comentarios").html(comentatios)
                $("#titulo_comentarios").html($(`#titulo-${id}`).html())
                $("#btn_comentar").attr('onclick', `func_publicar_comentario(${id})`)

                $("#modelId_2").modal('show')

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

    function func_publicar_comentario(id) {

        $.ajax({
            type: "POST",
            url: "lib/lib_noticias.php",
            data: {
                "v_acao": "PUBLICAR_COMENTARIO",
                "v_id_noticia": id,
                "v_comentario": $("#c_comentario").val()
            },
            success: function(data) {

                var v_json = JSON.parse(data);
                Swal.fire(
                    v_json.msg_titulo,
                    v_json.msg,
                    v_json.msg_ev
                )
                if (v_json.msg_ev == "success") {
                    $("#c_comentario").val("")
                    func_exibe_comentarios(id)
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

    }

    function func_excluir_comentario(id_comentario, id_noticia) {

        Swal.fire({
            title: 'Você tem certeza que deseja excluir?',
            text: "Você irá excluir o comentário!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, excluir!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "lib/lib_noticias.php",
                    data: {
                        "v_acao": "EXCLUIR_COMENTARIO",
                        "v_id_comentario": id_comentario
                    },
                    success: function(data) {

                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        if (v_json.msg_ev == "success") {
                            func_exibe_comentarios(id_noticia)
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
            }
        })
    }

    function func_editar_comentario(id_comentario, id_noticia) {

        Swal.fire({
            title: 'Você tem certeza que deseja editar?',
            text: "Você irá editar o comentário!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, editar!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "lib/lib_noticias.php",
                    data: {
                        "v_acao": "EDITAR_COMENTARIO",
                        "v_id_comentario": id_comentario,
                        "v_comentario": $("#c_comentario").val()
                    },
                    success: function(data) {

                        var v_json = JSON.parse(data);
                        Swal.fire(
                            v_json.msg_titulo,
                            v_json.msg,
                            v_json.msg_ev
                        )
                        if (v_json.msg_ev == "success") {
                            func_exibe_comentarios(id_noticia)
                            func_novo_comentario(id_noticia)
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
            }
        })

    }

    function func_carrega_comentario(id_comentario, id_noticia) {

        $("#btn_comentar").attr('onclick', `func_editar_comentario(${id_comentario}, ${id_noticia})`)
        $("#btn_comentar").html('<span class="glyphicon glyphicon-sunglasses img-circle text-success btn-icon" style="padding: 3px; background: #ffffff;"></span>Editar comentário')
        $("#btn_novo_comentario").attr('class', 'btn btn-warning btn-custom')
        $("#btn_novo_comentario").attr('onclick', `func_novo_comentario(${id_noticia})`)
        $("#c_comentario").val($(`#conteudo-comentario-${id_comentario}`).html())
    }

    function func_novo_comentario(id) {

        $("#btn_comentar").attr('onclick', `func_publicar_comentario(${id})`)
        $("#btn_comentar").html('<span class="glyphicon glyphicon-sunglasses img-circle text-success btn-icon" style="padding: 3px; background: #ffffff;"></span>Comentar')
        $("#btn_novo_comentario").attr('class', 'btn btn-warning btn-custom hidden')
        $("#c_comentario").val('')
    }
</script>