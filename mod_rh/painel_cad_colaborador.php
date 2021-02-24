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

    <title>Cadastro de Usuarios</title>
</head>

<body>

    <div class="container">
        <div id="box_form_titulo" class="box" style="height: 60px; margin-top: 40px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-12">
                    <h3>Cadastro Colaborador</h3>
                </div>
            </div>
        </div>
        <div class="box" style="margin-top: 10px; height: auto; background-color: white; border: none;">
            <div class="box-body" style="height: auto;">
                <div class="row">
                    <div class="form-group col-sm-1">
                        <label for="c_id">Id</label>
                        <input id="c_id" type="text" class="form-control class_inputs">
                    </div>
                    <div class="form-group col-sm-8">
                        <label for="c_nome">Nome Completo</label>
                        <input id="c_nome" type="text" class="form-control class_inputs" placeholder="NOME COMPLETO">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_sexo">Sexo</label>
                        <select id="c_sexo" class="form-control class_inputs">
                            <option selected>Selecione</option>
                            <option value="M">MASCULINO</option>
                            <option value="F">FEMININO</option>
                            <option value="O">OUTRO</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label for="c_nacionalidade">Nacionalidade</label>
                        <input id="c_nacionalidade" type="text" class="form-control class_inputs" placeholder="BRASIL">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_naturalidade">Naturalidade</label>
                        <input id="c_naturalidade" type="text" class="form-control class_inputs" placeholder="São Paulo">
                    </div>

                    <div class="form-group col-sm-3">
                        <label for="c_pne">PNE</label>
                        <select id="c_pne" class="form-control class_inputs">
                            <option selected>Selecione</option>
                            <option value="0">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_nec_especial">Necessidade Especial </label>
                        <input id="c_nec_especial" type="text" class="form-control class_inputs" placeholder="">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label for="c_cpf">CPF</label>
                        <input id="c_cpf" type="text" class="form-control class_inputs" placeholder="000.000.000-00">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_dt_nasc">Data de Nascimento</label>
                        <input id="c_dt_nasc" type="text" class="form-control class_inputs" placeholder="00/00/0000">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_celular">Celular</label>
                        <input id="c_celular" type="text" class="form-control class_inputs" placeholder="(00) 0 0000-0000">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_celular_emergencia">Contato de Emergência </label>
                        <input id="c_celular_emergencia" type="text" class="form-control class_inputs" placeholder="(00) 0 0000-0000">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-3">
                        <label for="c_pis">PIS</label>
                        <input id="c_pis" type="text" class="form-control class_inputs" placeholder="000.00000-0">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_estado_civil">Estado Civil</label>
                        <select id="c_estado_civil" class="form-control class_inputs">
                            <option selected>Selecione</option>
                            <option value="">Solteiro</option>
                            <option value="">Casado</option>
                            <option value="">Divorciado</option>
                            <option value="">Viúvo</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_cnh">CNH</label>
                        <input id="c_cnh" type="number" class="form-control class_inputs" placeholder="0000000000000">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_reservista">Reservista </label>
                        <input id="c_reservista" type="text" class="form-control class_inputs" placeholder="00000000000">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label for="c_rg">RG</label>
                        <input id="c_rg" type="text" class="form-control class_inputs" placeholder="000000000">
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="c_orgao_expedidor">Órgão Expedidor</label>
                        <input id="c_orgao_expedidor" type="text" class="form-control class_inputs" placeholder="Órgão/UF">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="c_titulo_eleitoral">Título de Eleitor</label>
                        <input id="c_titulo_eleitoral" type="text" class="form-control class_inputs" placeholder="000000000000">
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="c_zona_eleitoral">Zona Eleitoral</label>
                        <input id="c_zona_eleitoral" type="textr" class="form-control class_inputs" placeholder="000">
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="c_secao_eleitoral">Seção Eleitoral</label>
                        <input id="c_secao_eleitoral" type="text" class="form-control class_inputs" placeholder="000">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="c_nome_mae">Nome da Mãe</label>
                        <input id="c_nome_mae" type="text" class="form-control class_inputs" placeholder="">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="c_nome_pai">Nome do Pai</label>
                        <input id="c_nome_pai" type="text" class="form-control class_inputs" placeholder="">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="c_email">E-Mail</label>
                        <input id="c_email" type="email" class="form-control class_inputs" style="text-transform: lowercase;" placeholder="email@email.com.br">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="c_email_pessoal">E-Mail Pessoal</label>
                        <input id="c_email_pessoal" type="email" class="form-control class_inputs" style="text-transform: lowercase;" placeholder="email@email.com.br">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3" style="padding-right: 0px;">
                        <i class="fa fa-linkedin-square" aria-hidden="true"></i>
                        <label for="l_linkedin">Linkedin</label>
                        <input disabled id="l_linkedin" type="text" class="form-control class_inputs w-100" style="padding: 5px; text-transform: lowercase;" value="https://www.linkedin.com/">
                    </div>
                    <div class="form-group col-sm-3" style="margin:0px; padding: 0px;">
                        <label for="c_linkedin" style="color: white;">E-Mail</label>
                        <input id="c_linkedin" type="email" class="form-control class_inputs w-100" style="margin:0px; padding: 10px; text-transform: lowercase;" placeholder="meuperfil">
                    </div>
                    <div class="form-group col-sm-3" style="padding-right: 0px;">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                        <label for="l_instagram">Instagram</label>
                        <input disabled id="l_instagram" type="text" class="form-control class_inputs w-100" style="padding: 5px; text-transform: lowercase;" value="https://www.instagram.com/">
                    </div>
                    <div class="form-group col-sm-3" style="margin:0px; padding: 0px;">
                        <label for="c_instagram" style="color: white;">E-Mail</label>
                        <input id="c_instagram" type="email" class="form-control class_inputs w-100" style="margin:0px; padding: 10px; text-transform: lowercase;" placeholder="meuperfil">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3" style="padding-right: 0px;">
                        <i class="fa fa-facebook-square" aria-hidden="true"></i>
                        <label for="l_faceboook">Facebook</label>
                        <input disabled id="l_faceboook" type="text" class="form-control class_inputs w-100" style="padding: 5px; text-transform: lowercase;" value="https://www.facebook.com/">
                    </div>
                    <div class="form-group col-sm-3" style="margin:0px; padding: 0px;">
                        <label for="c_faceboook" style="color: white;">E-Mail</label>
                        <input id="c_faceboook" type="email" class="form-control class_inputs w-100" style="margin:0px; padding: 10px; text-transform: lowercase;" placeholder="meuperfil">
                    </div>
                    <div class="form-group col-sm-3" style="padding-right: 0px;">
                        <i class="fa fa-twitter-square" aria-hidden="true"></i>
                        <label for="l_twitter">Twitter</label>
                        <input disabled id="l_twitter" type="text" class="form-control class_inputs w-100" style="padding: 5px; text-transform: lowercase;" value="https://www.twitter.com/">
                    </div>
                    <div class="form-group col-sm-3" style="margin:0px; padding: 0px;">
                        <label for="c_twitter" style="color: white;">E-Mail</label>
                        <input id="c_twitter" type="email" class="form-control class_inputs w-100" style="margin:0px; padding: 10px; text-transform: lowercase;" placeholder="meuperfil">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="c_departamento">Departamento</label>
                        <input id="c_departamento" type="text" class="form-control class_inputs" placeholder="DEPARTAMENTO">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="c_cargo">Cargo</label>
                        <input id="c_cargo" type="text" class="form-control class_inputs" placeholder="CARGO">
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script language="JavaScript">
    $(document).ready(function() {
        alert("teste");
        $("#c_cpf").mask("000.000.000-00");
        $("#c_pis").mask("000.00000-0");
        $("#c_dt_nasc").mask("00/00/0000");
        $("#c_celular").mask("(00) 0 0000-0000");
        $("#c_celular_emergencia").mask("(00) 0 0000-0000");

    });
</script>

</html>