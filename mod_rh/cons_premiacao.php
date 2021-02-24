<style>
    #titulo {
        padding-top: 15px;
        padding-bottom: 4px;
        font-size: 24px;
        /* border-radius: 25px; */
        background-image: linear-gradient(to right, #401f4e, 9%, #271F4E 18%);
        font-style: italic;
        color: white;
        font-weight: bold;
        margin-top: 4px;
        margin-bottom: 8px;
    }
</style>


<div class="container">
    <div id="box_form_titulo" class="row" style="height: 60px; margin-top: 40px; background-color: white; border: none;">
        <div class="row" style="background-image: linear-gradient(to left, #6c3a8e , white);">
            <div class="form-group col-sm-12">
                <div class="col-sm-10" style="font-weight: bold; font-size: 25px; color: #523B8F;">Premiação</div>
                <div class="col-sm-2"></div>
            </div>
        </div>
    </div>
    <div id="box_form_titulo" class="row" style="height: 60px; margin-top: 20px; background-color: white; border: none;">
        <div class="row">
            <div class="hidden" id="equipe">
                <label for="c_equipe">Lideres:</label>
                <select onchange="func_seleciona_lideranca()" class="form-control class_inputs" id="c_equipe" required>
                    <!-- <option value="0">Individual</option> -->
                </select>
            </div>

            <div class="hidden" id="colaborador">
                <label for="c_colaborador">Colaborador:</label>
                <select onchange="func_seleciona_colaborador()" class="form-control class_inputs" id="c_colaborador">

                </select>
            </div>

            <div class="hidden" id="indicador">
                <label for="c_indicador">Indicadores:</label>
                <select onchange="func_seleciona_indicador()" class="form-control class_inputs" id="c_indicador">
                </select>
            </div>
            <div class="hidden" id="regras">
                <label for="btn_regras" style="color: white;">---</label>
                <button id="btn_regras" class="btn btn-info" type="submit" onclick="func_regras()">Regras</button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center" id="titulo">
            </div>
        </div>
        <div class="graficos" id="graficos_total">
            <div class="row">
                <div class="col-sm-6">
                    <div class="text-center" style="box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2); background-color: White; border-radius: 5px; border-color: rgba(0, 0, 0, 0.2); height: 393px">
                        <canvas id="grafico_total" style="background-position: center; background-image:url(../img/index/logo_6.png); background-size: 320px; background-repeat: no-repeat;"></canvas>
                    </div>
                </div>

                <div class="col-sm-6">

                    <!-- <div class="text-center" style="padding-bottom:25.5%; padding-bottom: 51.5%; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2); background-position: center; background-color: White; border-radius: 5px; border-color: rgba(0, 0, 0, 0.2); height: 65px; width: 100%;  margin-top: 10px; background-image:url(img/image_painel/fundo.png); background-size: 510px; background-repeat: no-repeat "> -->
                    <!-- <div class="row">
                        <div>
                            <div class="col-sm-6">
                                <span class="glyphicon glyphicon-signal" aria-hidden="true" style="color: #271F4E; font-size: 20px; border:#49c095; left: 420px;"></span>
                            </div>
                            <div class="col-sm-6">
                                <span class="glyphicon glyphicon-piggy-bank" aria-hidden="true" style="color: #271F4E; font-size: 20px; border:#49c095; left: 200px;"></span>
                            </div>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-sm-12">
                            <div style="box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2); background-color: White ; border-radius: 5px; border-color: rgba(0, 0, 0, 0.2); width: 100%;">
                                <table class="table" style="background-position: center; background-image:url(../img/index/logo_6.png); background-size: 310px; background-repeat: no-repeat; ">
                                    <thead id="tabgeral">
                                        <tr>
                                            <th style="text-align: left; color: #271F4E;">Critério</th>
                                            <th style="color: #271F4E;">
                                                <span class="glyphicon glyphicon-signal" aria-hidden="true" style="color: #271F4E; font-size: 20px;"></span>
                                            </th>
                                            <th style="color: #271F4E;">
                                                <span class="glyphicon glyphicon-piggy-bank" aria-hidden="true" style="color: #271F4E; font-size: 20px;"></span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabgeralb">
                                        <!-- <tr>
                                    <th style="text-align: left; color: #271F4E;">valor</th>
                                    <td style="text-align: center; color: #271F4E;">R$ 500,00</td>
                                    <td style="text-align: center; color: #271F4E;">R$ 400,00</td>
                                    <td style="text-align: center; color: #271F4E;">R$ 300,00</td>
                                </tr> -->
                                        <!-- <tr>
                                    <th style="text-align: left; color: #271F4E;">%</th>
                                    <td style="text-align: center; color: #271F4E;">11%</td>
                                    <td style="text-align: center; color: #271F4E;">6%</td>
                                    <td style="text-align: center; color: #271F4E;">8%</td>
                                </tr> -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>


    </div>

    <div class="row graficos" id="graficos_individual_gclick">
        <div>
            <div class="col-md-6" style="text-align:center;">
                <div class="box text-center" style="text-align:center; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2); background-color: White; border-radius: 5px; border-color: rgba(0, 0, 0, 0.2); height: 100%;  width: 100%; margin-right: 250px; margin-top: 10px">
                    <canvas id="grafico_ind_gclick" style="background-position: center; background-image:url(../img/index/logo_6.png); background-size: 320px; background-repeat: no-repeat;"></canvas>
                </div>
            </div>

            <div class="col-md-6" style="text-align:center;">
                <div class="text-center" style="padding-bottom:25.5%; padding-bottom: 51.5%; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);background-position: center; background-color: White; border-radius: 5px;  height: 65px; width: 100%;  margin-top: 10px; background-image:url(img/image_painel/fundo.png); background-size: 510px; background-repeat: no-repeat ">


                    <!-- <div class="row" style="padding: 10%;">
                        <span class="glyphicon glyphicon-signal" aria-hidden="true" style="color: #271F4E; font-size: 30px; border:#49c095;"></span>
                    </div>
                    <div class="row">
                        <span class="glyphicon glyphicon-piggy-bank" aria-hidden="true" style="color: #271F4E; font-size: 30px; border:#49c095"></span>
                    </div> -->
                </div>
            </div>

        </div>
    </div>

    <div class="row graficos" id="graficos_multa">
        <!-- <div class="col-sm-3">
            </div> -->

        <div class="col-sm-6 text-center">
            <div class="box text-center" style="text-align:center; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2); background-color: White; border-radius: 5px; border-color: rgba(0, 0, 0, 0.2); height: 100%;  width: 100%; margin-right: 250px; margin-top: 10px; margin-bottom:10px">
                <canvas id="grafico_multa" style="background-position: center; background-image:url(../img/index/logo_6.png); background-size: 320px; background-repeat: no-repeat;"></canvas>
            </div>
        </div>

        <div class="col-md-6" style="text-align:center;">

            <div class="box text-center" style="text-align:center; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2); background-color: White; border-radius: 5px; border-color: rgba(0, 0, 0, 0.2); height: 90%;  width: 100%; margin-right: 250px; margin-top: 10px;">
                <table class="table table-striped table-dark table-sm" id="tabela_multas" style="height: 100%">
                    <thead id="tabmultas">
                        <tr>
                            <th scope="col" style="color: #271F4E; text-align: left;">MÊS</th>
                            <th scope="col" style="color: #271F4E; text-align: center;">JAN</th>
                            <th scope="col" style="color: #271F4E; text-align: center;">FEV</th>
                            <th scope="col" style="color: #271F4E; text-align: center;">MAR</th>
                            <th scope="col" style="color: #271F4E; text-align: center;">ABR</th>
                            <th scope="col" style="color: #271F4E; text-align: center;">MAI</th>
                            <th scope="col" style="color: #271F4E; text-align: center;">JUN</th>
                        </tr>
                    </thead>
                    <tbody id="tabmultasb">
                        <!-- <tr>
                            <th scope="row" style="color: #271F4E; text-align: left;">MULTAS</th>
                            <td><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true" style="color: #271F4E;"></span></td>
                            <td><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true" style="color: #271F4E;"></span></td>
                            <td><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true" style="color: red;"></span></td>
                            <td><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true" style="color: red;"></span></td>
                            <td><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true" style="color: #271F4E;"></span></td>
                            <td><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true" style="color: red;"></span></td>
                        </tr>
                        <tr>
                            <th scope="row" style="color: #271F4E; text-align: left;">PRÊMIO</th>
                            <td><span style="color: #271F4E;"> 3%</span></td>
                            <td><span style="color: #271F4E;"> 3%</span></td>
                            <td><span style="color: red;"> 0%</span></td>
                            <td><span style="color: red;"> 0%</span></td>
                            <td><span style="color: #271F4E;"> 3%</span></td>
                            <td><span style="color: red;"> 0%</span></td>
                        </tr> -->
                    </tbody>
                </table>
            </div>

            <!-- <div class="text-center" style="padding-bottom:25.5%; padding-bottom: 51.5%; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);background-image:url(img/image_painel/fundo.png); background-position: center; background-size: 510px; background-repeat: no-repeat; background-color: white; border-radius: 5px; border-color: rgba(0, 0, 0, 0.2); height: 65px; width: 100%;  margin-top: 10px;">
                <div class="row" style="padding: 10%;">
                    <span class="glyphicon glyphicon-signal" aria-hidden="true" style="color: #271F4E; font-size: 30px; border:#49c095;"></span>
                </div>
                <div class="row">
                    <span class="glyphicon glyphicon-piggy-bank" aria-hidden="true" style="color: #271F4E; font-size: 30px; border:#49c095"></span>
                </div>
            </div> -->
        </div>


    </div>

    <div class="row graficos" id="graficos_sat_usuario">
        <!-- <div class="col-sm-3">
            </div> -->

        <div class="col-sm-6 text-center">
            <div class="box text-center" style="text-align:center; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2); background-color: White; border-radius: 5px; border-color: rgba(0, 0, 0, 0.2); height: 100%;  width: 100%; margin-right: 250px; margin-top: 10px; margin-bottom:10px">
                <canvas id="grafico_sat_usuario" style="background-position: center; background-image:url(../img/index/logo_6.png); background-size: 320px; background-repeat: no-repeat;"></canvas>
            </div>
        </div>

        <div class="col-md-6" style="text-align:center;">
            <div class="text-center" style="padding-bottom:25.5%; padding-bottom: 51.5%; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);background-image:url(img/image_painel/fundo.png); background-position: center; background-size: 510px; background-repeat: no-repeat; background-color: white; border-radius: 5px; border-color: rgba(0, 0, 0, 0.2); height: 65px; width: 100%;  margin-top: 10px;">
                <!-- <div class="row" style="padding: 10%;">
                    <span class="glyphicon glyphicon-signal" aria-hidden="true" style="color: #271F4E; font-size: 30px; border:#49c095;"></span>
                </div>
                <div class="row">
                    <span class="glyphicon glyphicon-piggy-bank" aria-hidden="true" style="color: #271F4E; font-size: 30px; border:#49c095"></span>
                </div> -->
            </div>
        </div>

    </div>

    <div class="row graficos" id="graficos_balancete">
        <!-- <div class="col-sm-3">
            </div> -->

        <div class="col-sm-6 text-center">
            <div class="box text-center" style="text-align:center; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2); background-color: White; border-radius: 5px; border-color: rgba(0, 0, 0, 0.2); height: 100%;  width: 100%; margin-right: 250px; margin-top: 10px; margin-bottom:10px">
                <canvas id="grafico_balancete" style="background-position: center; background-image:url(../img/index/logo_6.png); background-size: 320px; background-repeat: no-repeat;"></canvas>
            </div>
        </div>

        <div class="col-md-6" style="text-align:center;">
            <div class="text-center" style="padding-bottom:25.5%; padding-bottom: 51.5%; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);background-image:url(img/image_painel/fundo.png); background-position: center; background-size: 510px; background-repeat: no-repeat; background-color: white; border-radius: 5px; border-color: rgba(0, 0, 0, 0.2); height: 65px; width: 100%;  margin-top: 10px;">
                <!-- <div class="row" style="padding: 10%;">
                    <span class="glyphicon glyphicon-signal" aria-hidden="true" style="color: #271F4E; font-size: 30px; border:#49c095;"></span>
                </div>
                <div class="row">
                    <span class="glyphicon glyphicon-piggy-bank" aria-hidden="true" style="color: #271F4E; font-size: 30px; border:#49c095"></span>
                </div> -->
            </div>
        </div>

    </div>

    <div class="row graficos" id="graficos_equipe_gclick">
        <div class="col-sm-3">
        </div>
        <div class="col-sm-6 text-center">
            <div class="box text-center" style="text-align:center; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2); background-color: White; border-radius: 5px; border-color: rgba(0, 0, 0, 0.2); height: 90%;  width: 100%; margin-right: 250px; margin-top: 10px">
                <canvas id="grafico_equipe_gclick" style="background-position: center; background-image:url(../img/index/logo_6.png); background-size: 320px; background-repeat: no-repeat;"></canvas>
            </div>
        </div>
    </div>

    <div class="row graficos" id="graficos_vagas_rh">
        <!-- <div class="col-sm-3">
            </div> -->
        <div class="col-sm-6 text-center">
            <div class="box text-center" style="text-align:center; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2); background-color: White; border-radius: 5px; border-color: rgba(0, 0, 0, 0.2); height: 90%;  width: 100%; margin-right: 250px; margin-top: 10px">
                <canvas id="grafico_vagas_rh" style="background-position: center; background-image:url(../img/index/logo_6.png); background-size: 320px; background-repeat: no-repeat;"></canvas>
            </div>
        </div>

    </div>

    <div class="row graficos" id="graficos_trello">
        <div class="col-sm-6 text-center">
            <div class="box text-center" style="text-align:center; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2); background-color: White; border-radius: 5px; border-color: rgba(0, 0, 0, 0.2); height: 90%;  width: 100%; margin-right: 250px; margin-top: 10px">
                <canvas id="grafico_trello" style="background-position: center; background-image:url(../img/index/logo_6.png); background-size: 320px; background-repeat: no-repeat;"></canvas>
            </div>
        </div>
        <div class="col-md-6" style="text-align:center;">
            <div class="box text-center" style="text-align:center; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2); background-color: White; border-radius: 5px; border-color: rgba(0, 0, 0, 0.2); height: 100%;  width: 100%; margin-right: 250px; margin-top: 10px; margin-bottom:10px">
                <canvas id="perc_atraso_trello" style="background-position: center; background-image:url(../img/index/logo_6.png); background-size: 320px; background-repeat: no-repeat;"></canvas>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalRegras" id="modalRegras">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titulo_regras"></h4>
            </div>
            <div class="modal-body" id="corpo_regras">
            </div>
        </div>
    </div>
</div>
</div>

<!-- GRAFICOS -->
<script type="text/javascript" src="../class/Chart.js-2.9.4/dist/Chart.bundle.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        func_verifica_lideranca()
    })

    function func_verifica_lideranca() {
        $.ajax({
            type: "POST",
            url: "lib/lib_cons_premiacao.php",
            data: {
                "v_acao": "BUSCA_DADOS_LIDERANCA"
            },
            success: function(data) {

                $("#colaborador").removeClass()
                $("#equipe").removeClass()
                $("#indicador").removeClass()
                $("#regras").removeClass()

                if (data.lider) {

                    $("#colaborador").addClass("col-sm-4")
                    $("#equipe").addClass("col-sm-4")
                    $("#indicador").addClass("col-sm-3")
                    $("#regras").addClass("col-sm-1")

                    let option = ''
                    data.equipes.forEach(element => {
                        option += `<option value="${element.id}">${element.nome}</option>`
                    });
                    $("#c_equipe").html(option)
                    $("#c_equipe").val(data.lideranca)

                    option = `<option value="${data.id_colaborador}">${data.nome}</option>`
                    data.colaboradores.forEach(element => {
                        option += `<option value="${element.id_colaborador}">${element.nome}</option>`
                    });
                    $("#c_colaborador").html(option)

                    option = '<option value="0">Resumo geral</option>'
                    data.indicadores.forEach(element => {
                        option += `<option value="${element.id_indicador}">${element.descricao}</option>`
                    });
                    $("#c_indicador").html(option)
                } else {

                    $("#colaborador").addClass("col-sm-6")
                    $("#equipe").addClass("hidden")
                    $("#indicador").addClass("col-sm-5")
                    $("#regras").addClass("col-sm-1")

                    $("#c_colaborador").html(`<option value="${data.id_colaborador}">${data.nome}</option>`)

                    let option = '<option value="0">Resumo geral</option>'
                    data.indicadores.forEach(element => {
                        option += `<option value="${element.id_indicador}">${element.descricao}</option>`
                    });
                    $("#c_indicador").html(option)
                }

                func_seleciona_indicador()
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

    function func_seleciona_lideranca() {
        $.ajax({
            type: "POST",
            url: "lib/lib_cons_premiacao.php",
            data: {
                "v_acao": "SELECIONA_LIDERANCA",
                "v_id_lideranca": $("#c_equipe").val()
            },
            success: function(data) {

                option = ``
                data.colaboradores.forEach(element => {
                    option += `<option value="${element.id}">${element.nome}</option>`
                });
                $("#c_colaborador").html(option)
                $("#c_colaborador").val(data.id)

                func_seleciona_colaborador()
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

    function func_seleciona_colaborador() {
        $.ajax({
            type: "POST",
            url: "lib/lib_cons_premiacao.php",
            data: {
                "v_acao": "SELECIONA_COLABORADOR",
                "v_id_colaborador": $("#c_colaborador").val()
            },
            success: function(data) {

                option = '<option value="0">Resumo geral</option>'
                data.forEach(element => {
                    option += `<option value="${element.id}">${element.descricao}</option>`
                });
                $("#c_indicador").html(option)

                func_seleciona_indicador()
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

    function func_seleciona_indicador() {

        $(".graficos").hide()

        if ($("#c_indicador").val() === "0") {
            $.ajax({
                type: "POST",
                url: "lib/lib_cons_premiacao.php",
                data: {
                    "v_acao": "TOTAL_INDIVIDUAL",
                    "v_colaborador": $("#c_colaborador").val()
                },
                success: function(data) {

                    if (data.liberacao) {

                        // console.log(data)

                        let total = 0
                        let vetor_total_tarefas = [0]
                        let vetor_valores = []
                        let ultimo_indicador = data.porcentagens[0].indicador
                        let pos = 0
                        // let option_criterio = '<tr><th style="text-align: left; color: #271F4E;">Critério</th>'
                        // let option_valor = '<tr><th style="text-align: left; color: #271F4E;">Valor</th>'
                        // let option_porc = '<tr><th style="text-align: left; color: #271F4E;">%</th>'

                        let option = ''
                        data.porcentagens.forEach(element => {
                            let porcentagem = parseFloat(element.porcentagem)
                            total += porcentagem
                            let indicador = parseInt(element.indicador)
                            if (indicador == ultimo_indicador) {
                                vetor_total_tarefas[pos] += porcentagem
                            } else {
                                pos++
                                vetor_total_tarefas.push(porcentagem)
                            }
                            ultimo_indicador = parseInt(element.indicador)
                        });

                        for (let i = 0; i < data.indicadores.length; i++) {
                            let finan_parcial = (vetor_total_tarefas[i] * data.financeiro) / 100
                            option += `
                                <tr>
                                    <td>${data.indicadores[i]}</td>
                                    <td>${vetor_total_tarefas[i]}</td>
                                    <td>R$${finan_parcial.toFixed(2)}</td>
                                </tr>
                            `
                        }

                        // $("#tabgeral").html(option_criterio)
                        $("#tabgeralb").html(option)

                        let financeiro = (data.financeiro * total) / 100;
                        $(".glyphicon-signal").html(` ${total}%`)
                        $(".glyphicon-piggy-bank").html(` R$${financeiro.toFixed(2)}`)

                        grafico_porcentagem_total(vetor_total_tarefas, data.indicadores, vetor_valores)
                    } else {
                        window.location = ''
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
        }

        if ($("#c_indicador").val() === "1") {
            $.ajax({
                type: "POST",
                url: "lib/lib_cons_premiacao.php",
                data: {
                    "v_acao": "BUSCA_POR_INDICADOR",
                    "v_colaborador": $("#c_colaborador").val(),
                    "v_indicador": $("#c_indicador").val()
                },
                success: function(data) {

                    let concluidas = []
                    let atrasadas = []
                    let percentual_atraso = []
                    let porcentagem = []
                    let total_concluidas = 0
                    let total_atrasadas = 0
                    let total_perc_atraso = 0

                    data.forEach(element => {
                        let atraso = (parseFloat(element.tarefas_atrasadas) / (parseFloat(element.tarefas_concluidas) + parseFloat(element.tarefas_atrasadas))) * 100
                        concluidas.push(element.tarefas_concluidas)
                        atrasadas.push(element.tarefas_atrasadas)
                        percentual_atraso.push(atraso)
                        porcentagem.push(element.porcentagem)
                        total_concluidas += parseFloat(element.tarefas_concluidas)
                        total_atrasadas += parseFloat(element.tarefas_atrasadas)
                        total_perc_atraso += atraso
                    });

                    grafico_individual_gclick(concluidas, atrasadas, percentual_atraso, porcentagem, total_atrasadas, total_concluidas, total_perc_atraso)

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

        if ($("#c_indicador").val() === "2") {
            $.ajax({
                type: "POST",
                url: "lib/lib_cons_premiacao.php",
                data: {
                    "v_acao": "BUSCA_POR_INDICADOR",
                    "v_colaborador": $("#c_colaborador").val(),
                    "v_indicador": $("#c_indicador").val()
                },
                success: function(data) {

                    let porcentagem = []
                    let option_multas = '<tr><th scope="row" style="color: #271F4E; text-align: left;">MULTAS</th>'
                    let option_premio = '<tr><th scope="row" style="color: #271F4E; text-align: left;">PRÊMIO</th>'

                    data.forEach(element => {
                        porcentagem.push(parseInt(element.porcentagem))
                    });

                    porcentagem.forEach(element => {
                        let cor
                        let icone
                        if (element == 0) {
                            cor = 'red'
                            icone = 'glyphicon-thumbs-down'
                        } else {
                            cor = '#006400'
                            icone = 'glyphicon-thumbs-up'
                        }
                        option_multas += `<td><span style="color: ${cor};"> ${element}%</span></td>`
                        option_premio += `<td><span class="glyphicon ${icone}" aria-hidden="true" style="color: ${cor};"></span></td>`
                    });

                    option_multas += '<tr>'
                    option_premio += '<tr>'

                    $("#tabmultasb").html(option_multas += option_premio)

                    grafico_multas(porcentagem)

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

        if ($("#c_indicador").val() === "4") {
            $.ajax({
                type: "POST",
                url: "lib/lib_cons_premiacao.php",
                data: {
                    "v_acao": "BUSCA_POR_INDICADOR",
                    "v_colaborador": $("#c_colaborador").val(),
                    "v_indicador": $("#c_indicador").val()
                },
                success: function(data) {

                    let porcentagem = []

                    data.forEach(element => {
                        porcentagem.push(parseInt(element.porcentagem))
                    });

                    grafico_equipe_gclick(porcentagem)

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

        if ($("#c_indicador").val() === "6") {
            $.ajax({
                type: "POST",
                url: "lib/lib_cons_premiacao.php",
                data: {
                    "v_acao": "BUSCA_POR_INDICADOR",
                    "v_colaborador": $("#c_colaborador").val(),
                    "v_indicador": $("#c_indicador").val()
                },
                success: function(data) {

                    let porcentagem = []

                    data.forEach(element => {
                        porcentagem.push(parseInt(element.porcentagem))
                    });

                    grafico_balancete(porcentagem)

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

        if ($("#c_indicador").val() === "7") {
            $.ajax({
                type: "POST",
                url: "lib/lib_cons_premiacao.php",
                data: {
                    "v_acao": "BUSCA_POR_INDICADOR",
                    "v_colaborador": $("#c_colaborador").val(),
                    "v_indicador": $("#c_indicador").val()
                },
                success: function(data) {

                    let concluidas = []
                    let atrasadas = []
                    let percentual_atraso = []
                    let porcentagem = []
                    let total_concluidas = 0
                    let total_atrasadas = 0
                    let total_perc_atraso = 0

                    data.forEach(element => {
                        let atraso = (parseFloat(element.tarefas_atrasadas) / (parseFloat(element.tarefas_concluidas) + parseFloat(element.tarefas_atrasadas))) * 100
                        concluidas.push(element.tarefas_concluidas)
                        atrasadas.push(element.tarefas_atrasadas)
                        percentual_atraso.push(atraso)
                        porcentagem.push(element.porcentagem)
                        total_concluidas += parseFloat(element.tarefas_concluidas)
                        total_atrasadas += parseFloat(element.tarefas_atrasadas)
                        total_perc_atraso += atraso
                    });

                    grafico_vagas_fechadas_rh(concluidas, atrasadas, percentual_atraso, porcentagem, total_atrasadas, total_concluidas, total_perc_atraso)

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

        if ($("#c_indicador").val() === "9") {
            $.ajax({
                type: "POST",
                url: "lib/lib_cons_premiacao.php",
                data: {
                    "v_acao": "BUSCA_POR_INDICADOR",
                    "v_colaborador": $("#c_colaborador").val(),
                    "v_indicador": $("#c_indicador").val()
                },
                success: function(data) {

                    let concluidas = []
                    let atrasadas = []
                    let percentual_atraso = []
                    let porcentagem = []
                    let total_concluidas = 0
                    let total_atrasadas = 0
                    let total_perc_atraso = 0

                    data.forEach(element => {
                        let atraso = (parseFloat(element.tarefas_atrasadas) / (parseFloat(element.tarefas_concluidas) + parseFloat(element.tarefas_atrasadas))) * 100
                        concluidas.push(element.tarefas_concluidas)
                        atrasadas.push(element.tarefas_atrasadas)
                        percentual_atraso.push(atraso)
                        porcentagem.push(element.porcentagem)
                        total_concluidas += parseFloat(element.tarefas_concluidas)
                        total_atrasadas += parseFloat(element.tarefas_atrasadas)
                        total_perc_atraso += atraso
                    });

                    grafico_tarefas_trello(concluidas, atrasadas, percentual_atraso, porcentagem, total_atrasadas, total_concluidas, total_perc_atraso)

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

        if ($("#c_indicador").val() === "10") {
            $.ajax({
                type: "POST",
                url: "lib/lib_cons_premiacao.php",
                data: {
                    "v_acao": "BUSCA_POR_INDICADOR",
                    "v_colaborador": $("#c_colaborador").val(),
                    "v_indicador": $("#c_indicador").val()
                },
                success: function(data) {

                    let porcentagem = []

                    data.forEach(element => {
                        porcentagem.push(parseInt(element.porcentagem))
                    });

                    grafico_sat_usuario(porcentagem)

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


        if ($("#c_indicador").val() === '13') {
            $.ajax({
                type: "POST",
                url: "lib/lib_cons_premiacao.php",
                data: {
                    "v_acao": "BUSCA_POR_INDICADOR",
                    "v_colaborador": $("#c_colaborador").val(),
                    "v_indicador": $("#c_indicador").val()
                },
                success: function(data) {
                    let concluidas = []
                    let atrasadas = []
                    let percentual_atraso = []
                    let porcentagem = []
                    let total_concluidas = 0
                    let total_atrasadas = 0
                    let total_perc_atraso = 0

                    data.forEach(element => {
                        let atraso = (parseFloat(element.tarefas_atrasadas) / (parseFloat(element.tarefas_concluidas) + parseFloat(element.tarefas_atrasadas))) * 100
                        concluidas.push(element.tarefas_concluidas)
                        atrasadas.push(element.tarefas_atrasadas)
                        percentual_atraso.push(atraso)
                        porcentagem.push(element.porcentagem)
                        total_concluidas += parseFloat(element.tarefas_concluidas)
                        total_atrasadas += parseFloat(element.tarefas_atrasadas)
                        total_perc_atraso += atraso
                    });

                    grafico_individual_equipe(concluidas, atrasadas, percentual_atraso, porcentagem, total_atrasadas, total_concluidas, total_perc_atraso)
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
    }

    function grafico_tarefas_trello(concluidas, atrasadas, percentual_atraso, porcentagem, total_atrasadas, total_concluidas, total_atraso) {
        $("#graficos_trello").show()
        var ctx = document.getElementById("grafico_trello").getContext('2d');
        if (window.chart != undefined) {
            window.chart.destroy()
        }
        window.chart = new Chart(ctx, {
            type: 'bar',
            data: {
                datasets: [{
                        label: 'Concluidas',
                        data: concluidas,
                        backgroundColor: 'rgba(  21, 124, 193 , 1 )',
                        borderColor: 'rgba(  21, 124, 193 , 1 )',
                        borderWidth: 1,
                        order: 4
                    },
                    {
                        label: 'Atrasadas',
                        data: atrasadas,
                        backgroundColor: 'rgba( 193, 21, 21, 1 )',
                        borderColor: 'rgba( 193, 21, 21, 1 )',
                        borderWidth: 1,
                        order: 3
                    },
                    {
                        label: 'Percentual de atraso',
                        data: percentual_atraso,
                        borderColor: 'rgba( 150, 51, 255, 1 )',
                        pointBackgroundColor: 'rgba( 150, 51, 255, 1 )',
                        borderWidth: 3,
                        type: 'line',
                        order: 2
                    },
                    {
                        // label: 'Premiação referente',
                        // data: porcentagem,
                        // borderColor: 'rgba(  21, 193, 75 , 1 )',
                        // pointBackgroundColor: 'rgba(  21, 193, 75 , 1 )',
                        // showLine: false,
                        // type: 'line',
                        // order: 1
                    }
                ],
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho']
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Entregas no Prazo'
                },
                tooltips: {
                    mode: 'index'
                }
            }
        });

        var ctxL2 = document.getElementById("perc_atraso_trello").getContext('2d');
        window.chart = new Chart(ctxL2, {
            type: 'line',
            data: {
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho'],
                datasets: [{
                    label: "Percentual",
                    data: percentual_atraso,
                    borderColor: 'rgba( 115, 21, 193 , 1)',
                    pointBackgroundColor: 'rgba( 115, 21, 193 , 1)',
                    borderWidth: 2
                    // steppedLine: 'before'
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Percentual de Atraso'
                },
                tooltips: {
                    mode: 'index'
                },
                legend: {
                    display: false
                }
            }
        });
    }

    function grafico_porcentagem_total(data, labels, data1) {
        $("#graficos_total").show()
        var ctx = document.getElementById("grafico_total").getContext('2d');
        if (window.chart != undefined) {
            window.chart.destroy()
        }
        window.chart = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                datasets: [{
                        label: 'Porcentagem total',
                        data: data,
                        backgroundColor: 'rgba(   21, 167, 193  , 1 )',
                        borderWidth: 1,
                        order: 4
                    },
                    {
                        label: 'Valor',
                        data: data1,
                        backgroundColor: 'rgba(    86, 158, 0   , 1 )',
                        borderWidth: 1,
                        order: 4
                    }
                ],
                labels: labels
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Porcentagem total por tipo de indicador'
                },
                tooltips: {
                    mode: 'index'
                }
            }
        });
    }

    function grafico_multas(porcentagem) {
        $("#graficos_multa").show()
        var ctxL = document.getElementById("grafico_multa").getContext('2d');
        if (window.chart != undefined) {
            window.chart.destroy()
        }
        window.chart = new Chart(ctxL, {
            type: 'line',
            data: {
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho'],
                datasets: [{
                    label: "Percentual",
                    data: porcentagem,
                    borderColor: 'rgba( 115, 21, 193 , 1)',
                    pointBackgroundColor: 'rgba( 115, 21, 193 , 1)',
                    borderWidth: 2
                    // steppedLine: 'before'
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Percentual de Multa'
                },
                tooltips: {
                    mode: 'index'
                },
                legend: {
                    display: false
                }
            }
        });
    }

    function grafico_sat_usuario(porcentagem) {
        $("#graficos_sat_usuario").show()
        var ctxL = document.getElementById("grafico_sat_usuario").getContext('2d');
        if (window.chart != undefined) {
            window.chart.destroy()
        }
        window.chart = new Chart(ctxL, {
            type: 'line',
            data: {
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho'],
                datasets: [{
                    label: "Percentual",
                    data: porcentagem,
                    borderColor: 'rgba( 115, 21, 193 , 1)',
                    pointBackgroundColor: 'rgba( 115, 21, 193 , 1)',
                    borderWidth: 2
                    // steppedLine: 'before'
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Satisfaçaõ do Usuário'
                },
                tooltips: {
                    mode: 'index'
                },
                legend: {
                    display: false
                }
            }
        });
    }

    function grafico_balancete(porcentagem) {
        $("#graficos_balancete").show()
        var ctxL = document.getElementById("grafico_balancete").getContext('2d');
        if (window.chart != undefined) {
            window.chart.destroy()
        }
        window.chart = new Chart(ctxL, {
            type: 'line',
            data: {
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho'],
                datasets: [{
                    label: "Percentual",
                    data: porcentagem,
                    borderColor: 'rgba( 115, 21, 193 , 1)',
                    pointBackgroundColor: 'rgba( 115, 21, 193 , 1)',
                    borderWidth: 2
                    // steppedLine: 'before'
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Fechamento do Balancete'
                },
                tooltips: {
                    mode: 'index'
                },
                legend: {
                    display: false
                }
            }
        });
    }

    function grafico_equipe_gclick(porcentagem) {
        $("#graficos_equipe_gclick").show()
        var ctxL = document.getElementById("grafico_equipe_gclick").getContext('2d');
        if (window.chart != undefined) {
            window.chart.destroy()
        }
        window.chart = new Chart(ctxL, {
            type: 'line',
            data: {
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho'],
                datasets: [{
                    label: "Percentual",
                    data: porcentagem,
                    borderColor: 'rgba(26, 21, 193, 1)',
                    pointBackgroundColor: 'rgba(26, 21, 193, 1)',
                    borderWidth: 2,
                    pointStyle: 'rectRot'
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Percentual da Equipe'
                },
                tooltips: {
                    mode: 'index'
                },
                legend: {
                    display: false
                }
            }
        });
    }

    function grafico_vagas_fechadas_rh(concluidas, atrasadas, percentual_atraso, porcentagem, total_atrasadas, total_concluidas, total_atraso) {
        $("#graficos_vagas_rh").show()
        var ctx = document.getElementById("grafico_vagas_rh").getContext('2d');
        if (window.chart != undefined) {
            window.chart.destroy()
        }
        window.chart = new Chart(ctx, {
            type: 'bar',
            data: {
                datasets: [{
                        label: 'Concluidas',
                        data: concluidas,
                        backgroundColor: 'rgba(  21, 124, 193 , 1 )',
                        borderColor: 'rgba(  21, 124, 193 , 1 )',
                        borderWidth: 1,
                        order: 4
                    },
                    {
                        label: 'Atrasadas',
                        data: atrasadas,
                        backgroundColor: 'rgba( 193, 21, 21, 1 )',
                        borderColor: 'rgba( 193, 21, 21, 1 )',
                        borderWidth: 1,
                        order: 3
                    },
                    {
                        label: 'Percentual de atraso',
                        data: percentual_atraso,
                        borderColor: 'rgba( 150, 51, 255, 1 )',
                        pointBackgroundColor: 'rgba( 150, 51, 255, 1 )',
                        borderWidth: 3,
                        type: 'line',
                        order: 2
                    },
                    {
                        // label: 'Premiação referente',
                        // data: porcentagem,
                        // borderColor: 'rgba(  21, 193, 75 , 1 )',
                        // pointBackgroundColor: 'rgba(  21, 193, 75 , 1 )',
                        // showLine: false,
                        // type: 'line',
                        // order: 1
                    }
                ],
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho']
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Vagas Fechadas'
                },
                tooltips: {
                    mode: 'index'
                }
            }
        });
    }

    function grafico_individual_gclick(concluidas, atrasadas, percentual_atraso, porcentagem, total_atrasadas, total_concluidas, total_atraso) {
        $("#graficos_individual_gclick").show()
        var ctx = document.getElementById("grafico_ind_gclick").getContext('2d');
        if (window.chart != undefined) {
            window.chart.destroy()
        }
        window.chart = new Chart(ctx, {
            type: 'bar',
            data: {
                datasets: [{
                        label: 'Concluidas no Prazo',
                        data: concluidas,
                        backgroundColor: 'rgba( 90, 211, 209, 1 )',
                        borderColor: 'rgba( 90, 211, 209, 1 )',
                        borderWidth: 1,
                        order: 4
                    },
                    {
                        label: 'Percentual de atraso',
                        data: percentual_atraso,
                        backgroundColor: 'rgba( 255, 87, 51, 1 )',
                        borderColor: 'rgba( 255, 87, 51, 1 )',
                        borderWidth: 1,
                        order: 3
                    },
                    {
                        label: 'Atrasadas',
                        data: atrasadas,
                        borderColor: 'rgba( 150, 51, 255, 1 )',
                        borderWidth: 3,

                        // Changes this dataset to become a line
                        type: 'line',
                        order: 2
                    },
                    {
                        // label: 'Premiação referente',
                        // data: porcentagem,
                        // borderColor: 'rgba( 0, 255, 0, 1 )',
                        // pointBackgroundColor: 'rgba( 0, 255, 0, 1 )',
                        // showLine: false,

                        // Changes this dataset to become a line
                        type: 'line',
                        order: 1
                    }
                ],
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho']
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Tarefas '
                },
                tooltips: {
                    mode: 'index'
                }
            }
        });
    }

    function grafico_individual_equipe(concluidas, atrasadas, percentual_atraso, porcentagem, total_atrasadas, total_concluidas, total_atraso) {
        $("#graficos_individual_gclick").show()
        var ctx = document.getElementById("grafico_ind_gclick").getContext('2d');
        if (window.chart != undefined) {
            window.chart.destroy()
        }
        window.chart = new Chart(ctx, {
            type: 'bar',
            data: {
                datasets: [{
                        label: 'Concluidas',
                        data: concluidas,
                        backgroundColor: 'rgba( 90, 211, 209, 1 )',
                        borderColor: 'rgba( 90, 211, 209, 1 )',
                        borderWidth: 1,
                        order: 4
                    },
                    {
                        label: 'Percentual de atraso',
                        data: percentual_atraso,
                        backgroundColor: 'rgba( 255, 87, 51, 1 )',
                        borderColor: 'rgba( 255, 87, 51, 1 )',
                        borderWidth: 1,
                        order: 3
                    },
                    {
                        label: 'Atrasadas',
                        data: atrasadas,
                        borderColor: 'rgba( 150, 51, 255, 1 )',
                        borderWidth: 3,

                        // Changes this dataset to become a line
                        type: 'line',
                        order: 2
                    },
                    // {
                    // label: 'Premiação referente',
                    // data: porcentagem,
                    // borderColor: 'rgba( 0, 255, 0, 1 )',
                    // pointBackgroundColor: 'rgba( 0, 255, 0, 1 )',
                    // showLine: false,

                    // Changes this dataset to become a line
                    // type: 'line',
                    // order: 1
                    // }
                ],
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho']
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Tarefas '
                },
                tooltips: {
                    mode: 'index'
                }
            }
        });
    }

    function func_regras() {
        let regras = ''
        if ($("#c_indicador").val() === "0") {
            Swal.fire({
                icon: "warning",
                title: "AVISO!",
                text: "Por favor, selecione um indicador para visualizar a regra referente."
            })
        }
        if ($("#c_indicador").val() === "1") {
            $("#titulo_regras").html('REGRAS DE TAREFAS INDIVIDUAS DO G-CLICK')
            regras = `
            <div class="table-responsive" style="padding: 10px; color:black;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="3" class="text-center">24% - PONTUALIDADE NA EXECUÇÃO DAS TAREFAS (GCLICK)</th>
                        </tr>
                        <tr>
                            <th class="text-center">% Fora do Prazo</th>
                            <th class="text-center">Percentual Premiação (mês) - Individual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><=0,5%</td>
                            <td class="text-center">4%</td>
                        </tr>
                        <tr>
                            <td class="text-center">>0,5% e <1%</td>
                            <td class="text-center">2%</td>
                        </tr>
                        <tr>
                            <td class="text-center">>=1%</td>
                            <td class="text-center">0%</td>
                        </tr>
                        <tr>
                            <td colspan="3">*Indicador mensal individual.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            `
        }
        if ($("#c_indicador").val() == '2') {
            $("#titulo_regras").html('REGRAS DE MULTAS MENSAIS')
            regras = `
            <div class="table-responsive" style="padding: 10px; color:black;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">18% - MULTAS GERADAS DURANTE O MÊS</th>
                        </tr>
                        <tr>
                            <th class="text-center">Quantidade de multas</th>
                            <th class="text-center">Percentual Premiação (mês)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">=0</td>
                            <td class="text-center">3%</td>
                        </tr>
                        <tr>
                            <td class="text-center">>0</td>
                            <td class="text-center">0%</td>
                        </tr>
                        <tr>
                            <td colspan="3">*Indicador mensal individual.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            `
        }
        if ($("#c_indicador").val() == '3') {
            $("#titulo_regras").html('REGRAS AVALIAÇÃO DE DESEMPENHO SEMESTRAL')
            regras = `
            <div class="table-responsive" style="padding: 10px; color:black;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">34% - AVALIAÇÃO DE DESEMPENHO</th>
                        </tr>
                        <tr>
                            <th class="text-center">Percentual AVD</th>
                            <th class="text-center">Percentual Premiação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">Acima de 90%</td>
                            <td class="text-center">34%</td>
                        </tr>
                        <tr>
                            <td class="text-center">De 80% a 89,9%</td>
                            <td class="text-center">25%</td>
                        </tr>
                        <tr>
                            <td class="text-center">De 70 a 79,9%</td>
                            <td class="text-center">16%</td>
                        </tr>
                        <tr>
                            <td class="text-center">Abaixo de 69,9%</td>
                            <td class="text-center">7%</td>
                        </tr>
                        <tr>
                            <td colspan="3">*Indicador semestral individual.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            `
        }
        if ($("#c_indicador").val() === "4") {
            $("#titulo_regras").html('REGRAS DE TAREFAS EQUIPE DO G-CLICK')
            regras = `
            <div class="table-responsive" style="padding: 10px; color:black;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="3" class="text-center">24% - PONTUALIDADE NA EXECUÇÃO DAS TAREFAS (GCLICK)</th>
                        </tr>
                        <tr>
                            <th class="text-center">% Fora do Prazo</th>
                            <th class="text-center">Percentual Premiação (mês) - Equipe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><=0,5%</td>
                            <td class="text-center">4%</td>
                        </tr>
                        <tr>
                            <td class="text-center">>0,5% e <1%</td>
                            <td class="text-center">2%</td>
                        </tr>
                        <tr>
                            <td class="text-center">>=1%</td>
                            <td class="text-center">0%</td>
                        </tr>
                        <tr>
                            <td colspan="3">*Indicador mensal em equipe.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            `
        }

        if ($("#c_indicador").val() == '5') {
            $("#titulo_regras").html('REGRAS DE SATISFAÇÃO DO CLIENTE')
            regras = `
            <div class="table-responsive" style="padding: 10px; color:black;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">18% - SATISFAÇÃO DO CLIENTE</th>
                        </tr>
                        <tr>
                            <th class="text-center">Percentual Satisfação</th>
                            <th class="text-center">Percentual Premiação (trimestre)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">Acima de 90%</td>
                            <td class="text-center">9%</td>
                        </tr>
                        <tr>
                            <td class="text-center">De 80% a 89,9%</td>
                            <td class="text-center">7%</td>
                        </tr>
                        <tr>
                            <td class="text-center">De 70 a 79,9%</td>
                            <td class="text-center">5%</td>
                        </tr>
                        <tr>
                            <td class="text-center">Abaixo de 69,9%</td>
                            <td class="text-center">0%</td>
                        </tr>
                        <tr>
                            <td colspan="3">*Indicador trimestral individual.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            `
        }
        if ($("#c_indicador").val() == '6') {
            $("#titulo_regras").html("REGRAS DE FECHAMENTO DE BALANCETE")
            regras = `
            <div class="table-responsive" style="padding: 10px; color:black;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">18% - FECHAMENTO DO BALANCETE</th>
                        </tr>
                        <tr>
                            <th class="text-center">Fechamento em dias</th>
                            <th class="text-center">Percentual Premiação (mês)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">Até o 5º dia útil</td>
                            <td class="text-center">3%</td>
                        </tr>
                        <tr>
                            <td class="text-center">Acima do 5º dia útil</td>
                            <td class="text-center">0%</td>
                        </tr>
                        <tr>
                            <td colspan="2">*Indicador mensal individual.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            `
        }
        if ($("#c_indicador").val() == '7') {
            $("#titulo_regras").html("REGRAS DE FECHAMENTO DE VAGAS")
            regras = `
            <div class="table-responsive" style="padding: 10px; color:black;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">48% - VAGAS FECHADAS NO PRAZO</th>
                        </tr>
                        <tr>
                            <th class="text-center">Percentual Fechamento</th>
                            <th class="text-center">Percentual Premiação (mês)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">Acima de 85%</td>
                            <td class="text-center">8%</td>
                        </tr>
                        <tr>
                            <td class="text-center">De 70 a 84,9%</td>
                            <td class="text-center">4%</td>
                        </tr>
                        <tr>
                            <td class="text-center">Abaixo de 69,9%</td>
                            <td class="text-center">0%</td>
                        </tr>
                        <tr>
                            <td colspan="2">*Indicador mensal em equipe.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            `
        }
        if ($("#c_indicador").val() == '8') {
            $("#titulo_regras").html("REGRAS DE TAXA DE EFETIVAÇÃO")
            regras = `
            <div class="table-responsive" style="padding: 10px; color:black;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">18% - TAXA DE EFETIVAÇÃO</th>
                        </tr>
                        <tr>
                            <th class="text-center">Percentual efetivação</th>
                            <th class="text-center">Percentual Premiação (mês)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">Acima 85%</td>
                            <td class="text-center">3%</td>
                        </tr>
                        <tr>
                            <td class="text-center">De 70 a 84,9%</td>
                            <td class="text-center">1,5%</td>
                        </tr>
                        <tr>
                            <td class="text-center">Abaixo de 69,9%</td>
                            <td class="text-center">0%</td>
                        </tr>
                        <tr>
                            <td colspan="2">*Indicador mensal em equipe.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            `
        }
        if ($("#c_indicador").val() == '9') {
            $("#titulo_regras").html('REGRAS DE ENTREGAS NO PRAZO')
            regras = `
            <div class="table-responsive" style="padding: 10px; color:black;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">48% - ENTREGAS NO PRAZO</th>
                        </tr>
                        <tr>
                            <th class="text-center">Percentual de entregas</th>
                            <th class="text-center">Percentual Premiação (mês)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">Acima de 85%</td>
                            <td class="text-center">8%</td>
                        </tr>
                        <tr>
                            <td class="text-center">De 70 a 84,9%</td>
                            <td class="text-center">4%</td>
                        </tr>
                        <tr>
                            <td class="text-center">Abaixo de 69,9%</td>
                            <td class="text-center">0%</td>
                        </tr>
                        <tr>
                            <td colspan="2">*Indicador mensal individual.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            `
        }
        if ($("#c_indicador").val() == '10') {
            $("#titulo_regras").html("REGRAS DE SATISFAÇÃO DO USUÁRIO")
            regras = `
            <div class="table-responsive" style="padding: 10px; color:black;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">18% - SATISFAÇÃO DO USUÁRIO</th>
                        </tr>
                        <tr>
                            <th class="text-center">Percentual de entregas</th>
                            <th class="text-center">Percentual Premiação (mês)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">Acima de 85%</td>
                            <td class="text-center">3%</td>
                        </tr>
                        <tr>
                            <td class="text-center">De 70 a 84,9%</td>
                            <td class="text-center">1,5%</td>
                        </tr>
                        <tr>
                            <td class="text-center">Abaixo de 69,9%</td>
                            <td class="text-center">0%</td>
                        </tr>
                        <tr>
                            <td colspan="2">*Indicador mensal individual.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            `
        }
        if ($("#c_indicador").val() == '11') {
            $("#titulo_regras").html("REGRAS DO PLANO DE MARKETING")
            regras = `
            <div class="table-responsive" style="padding: 10px; color:black;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">18% - CUMPRIMENTO DO PLANO DE MARKETING</th>
                        </tr>
                        <tr>
                            <th class="text-center">Percentual</th>
                            <th class="text-center">Percentual Premiação (mês)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">Acima de 85%</td>
                            <td class="text-center">3%</td>
                        </tr>
                        <tr>
                            <td class="text-center">De 70 a 84,9%</td>
                            <td class="text-center">1,5%</td>
                        </tr>
                        <tr>
                            <td class="text-center">Abaixo de 69,9%</td>
                            <td class="text-center">0%</td>
                        </tr>
                        <tr>
                            <td colspan="2">*Indicador mensal individual.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            `
        }
        if ($("#c_indicador").val() == '12') {
            $("#titulo_regras").html("REGRAS DE META DE VENDAS")
            regras = `
            <div class="table-responsive" style="padding: 10px; color:black;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">18% - CUMPRIMENTO DA META DE VENDAS</th>
                        </tr>
                        <tr>
                            <th class="text-center">Percentual</th>
                            <th class="text-center">Percentual Premiação (trimestre)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">Acima de 90%</td>
                            <td class="text-center">9%</td>
                        </tr>
                        <tr>
                            <td class="text-center">De 80 a 89,9%</td>
                            <td class="text-center">7%</td>
                        </tr>
                        <tr>
                            <td class="text-center">De 70 a 79,9%</td>
                            <td class="text-center">5%</td>
                        </tr>
                        <tr>
                            <td class="text-center">Abaixo de 69,9%</td>
                            <td class="text-center">0%</td>
                        </tr>
                        <tr>
                            <td colspan="2">*Indicador trimestral individual.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            `
        }
        if ($("#c_indicador").val() == '13') {
            $("#titulo_regras").html("REGRAS DE TAREFAS INDIVIDUAIS + EQUIPE G-CLICK")
            regras = `
            <div class="table-responsive" style="padding: 10px; color:black;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">48% - TAREFAS INDIVIDUAIS + EQUIPE G-CLICK</th>
                        </tr>
                        <tr>
                            <th class="text-center">Percentual de atraso</th>
                            <th class="text-center">Percentual Premiação (mês) - Individual e Equipe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><= 0,5%</td>
                            <td class="text-center">8%</td>
                        </tr>
                        <tr>
                            <td class="text-center">>0,5 e <1%</td>
                            <td class="text-center">4%</td>
                        </tr>
                        <tr>
                            <td class="text-center">>1%</td>
                            <td class="text-center">0%</td>
                        </tr>
                        <tr>
                            <td colspan="2">*Indicador mensal individual e em equipe.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            `
        }

        if ($("#c_indicador").val() != '0') {
            $("#corpo_regras").html(regras)
            $("#modalRegras").modal('toggle')
        }
    }
</script>