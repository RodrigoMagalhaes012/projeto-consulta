<!doctype html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<title>Pesquisar CNPJ de empresas</title>
	<!--Importando Script Jquery-->

	<link href="css/animate.css" rel="stylesheet">
	<link rel="stylesheet" href="../class/alert/css/class_alert.css" id="theme-styles">

	<script src="../class/alert/js/class_alert.js"></script>

	<style>
		input,
		textarea {
			text-transform: uppercase;
		}

		table tr td {
			color: black;
		}

		table tr th {
			color: black;
		}
	</style>

</head>

<body>
	<!--Formulário-->

	<div class="container">
		<input id="c_acao" name="c_acao" type="hidden" value="">
		<input id="c_db_emp" name="c_db_emp" type="hidden" value="0">

		<!-- <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
			<div class="row">
				<div class="form-group col-sm-6">
					<h3>Lista de Empresas</h3>
				</div>
				<div class="form-group col-sm-2">
					<select id="c_tab_busca_campo" class="form-control class_inputs" onchange="func_busca_campo_select();">
						<option value="Id|num" selected>Id</option>
						<option value="Cnpj|txt">CNPJ / CPF</option>
						<option value="Nome|txt">Razão Social</option>
					</select>
				</div>
				<div class="form-group col-sm-4">
					<input type="text" id="c_tab_busca_texto" class="form-control class_inputs" placeholder="PESQUISAR REGISTRO" onkeyup="if (event.keyCode === 13) {func_carrega_tab();}">
				</div>

			</div>
		</div> -->
		<div id="box_tab1" class="row" style="border-color: grey; padding: 10px; border-width: 1px; border-style: solid; background-color: white; overflow-x: hidden;">
			<div class="box-body">
				<input type="hidden" id="vf_tab_sql_limit_in" value="0">
				<input type="hidden" id="vf_tab_btn_pag_select" value="1">

				<table style="width: 100%;" id="tab1" class="table">
					<thead style="font-weight: bold;">
						<tr>
							<th>Id</th>
							<th>Nome</th>
							<th>CNPJ / CPF</th>
							<th>Cert. Exp.</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody id="tab1b" style="font-weight: normal;">

					</tbody>
				</table>
			</div>
		</div>
		<!-- <div id="box_tab_footer" class="box" style="height: 60px; margin-top: 10px; background-color: white; border: none; overflow: hidden;">
			<div class="row">
				<div class="form-group col-sm-2">
					<select id="c_tab_campo" class="form-control class_inputs" onchange="func_carrega_tab();">
						<option value="id" selected>Id</option>
						<option value="cnpj">cnpj</option>
						<option value="nome">Razão Social</option>
						<option value="st_cadastro">Status</option>
					</select>
				</div>
				<div class="form-group col-sm-2">
					<select id="c_tab_ordem" class="form-control class_inputs" onchange="func_carrega_tab();">
						<option value="asc" selected>Crescente</option>
						<option value="desc">Decrescente</option>
					</select>
				</div>
				<div class="form-group col-sm-1">
					<select id="c_limit" class="form-control class_inputs" onchange="func_carrega_tab();">
						<option value="50" selected>50</option>
						<option value="100">100</option>
						<option value="150">150</option>
					</select>
				</div>
				<div id="div_tab_paginacao" class="form-group col-sm-7 text-right">

				</div>
			</div>
		</div> -->
		<div id="box_form_titulo" class="box" style="height: 60px; margin-top: 40px; background-color: white; border: none; overflow: hidden; display: none;">
			<input id="c_id" name="c_id" type="hidden" value="">
			<div class="row">
				<div class="form-group col-sm-11">
					<h3>Formulário de Cadastro</h3>
				</div>
				<div class="form-group col-sm-1">
					<button id="btn_voltar" class="btn btn-danger" style="border-radius: auto; width: auto;" onclick="goBack();">X</button>
				</div>
			</div>
		</div>
		<div id="box_form_cad" class="box" style="margin-top: 10px; height: auto; background-color: white; border: none; display: none;">
			<div class="box-body" style="height: auto;">
				<div class="row">
					<div class="form-group col-sm-2">
						<label for="tipo_doc">Tipo Doc</label>
						<select id="tipo_doc" class="form-control class_inputs" onchange="func_select_doc();">
							<option selected value="CNPJ">CNPJ</option>
							<option value="CPF">CPF</option>
						</select>
					</div>
					<div class="form-group col-sm-2">
						<label id="l_doc" for="cnpj"><span style="color: red; font-size: 18px;">* </span> CNPJ</label>
						<input id="cnpj" required type="text" class="form-control class_inputs" onfocusout="func_busca_empresa();">
					</div>
					<!-- PUXA DADOS DA RECEITA FEDERAL -->
					<div class="form-group col-sm-1" style="margin:0px; padding: 0px;">
						<label for="c_busca_cnpj" style="color: white;">-</label>
						<span class="input-group-btn">

							<img src="img/home/img_receita.jpg" width="31px" height="31px" style="cursor: pointer;" onclick="func_busca_receita();">
						</span>
					</div>
					<div class="form-group col-sm-7">
						<label for="c_nome"><span style="color: red; font-size: 18px;">* </span> Razão Social</label>
						<input id="c_nome" type="text" class="form-control class_inputs">
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="c_fantasia">Nome Fantasia</label>
						<input id="c_fantasia" type="text" class="form-control class_inputs">
					</div>
					<div class="form-group col-sm-6">
						<label for="c_atividade_principal">Atividade Principal</label>
						<input id="c_atividade_principal" type="text" class="form-control class_inputs">
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-3">
						<label for="c_telefone">Telefone</label>
						<input id="c_telefone" required type="text" class="form-control class_inputs">
					</div>
					<div class="form-group col-sm-6">
						<label for="c_email">E-mail</label>
						<input id="c_email" type="text" class="form-control class_inputs">
					</div>
					<div class="form-group col-sm-3">
						<label for="c_cep">CEP</label>
						<input id="c_cep" type="text" class="form-control class_inputs">
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-5">
						<label for="c_logradouro">Logradouro</label>
						<input id="c_logradouro" type="text" class="form-control class_inputs">
					</div>
					<div class="form-group col-sm-5">
						<label for="c_complemento">Complemento</label>
						<input id="c_complemento" type="text" class="form-control class_inputs">
					</div>
					<div class="form-group col-sm-2">
						<label for="c_numero">Numero</label>
						<input id="c_numero" type="text" class="form-control class_inputs">
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						<label for="c_bairro">Bairro</label>
						<input id="c_bairro" type="text" class="form-control class_inputs">
					</div>
					<div class="form-group col-sm-4">
						<label for="c_municipio">Municipio</label>
						<input id="c_municipio" type="text" class="form-control class_inputs">
					</div>
					<div class="form-group col-sm-4">
						<label for="c_uf"><span style="color: red; font-size: 18px;">* </span> Estado</label>
						<select id="c_uf" class="form-control class_inputs">
							<option value="-">Uf ?</option>
							<option value="AC">Acre</option>
							<option value="AL">Alagoas</option>
							<option value="AP">Amapá</option>
							<option value="AM">Amazonas</option>
							<option value="BA">Bahia</option>
							<option value="CE">Ceará</option>
							<option value="DF">Distrito Federal</option>
							<option value="ES">Espírito Santo</option>
							<option value="GO">Goiás</option>
							<option value="MA">Maranhão</option>
							<option value="MT">Mato Grosso</option>
							<option value="MS">Mato Grosso do Sul</option>
							<option value="MG">Minas Gerais</option>
							<option value="PA">Pará</option>
							<option value="PB">Paraíba</option>
							<option value="PR">Paraná</option>
							<option value="PE">Pernambuco</option>
							<option value="PI">Piauí</option>
							<option value="RJ">Rio de Janeiro</option>
							<option value="RN">Rio Grande do Norte</option>
							<option value="RS">Rio Grande do Sul</option>
							<option value="RO">Rondônia</option>
							<option value="RR">Roraima</option>
							<option value="SC">Santa Catarina</option>
							<option value="SP">São Paulo</option>
							<option value="SE">Sergipe</option>
							<option value="TO">Tocantins</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="c_atividades_secundarias">Atividades Secundárias</label>
						<input id="c_atividades_secundarias" type="text" name="c_atividades_secundarias" class="form-control class_inputs">
					</div>

					<div class="form-group col-sm-2">
						<label for="c_abertura">Abertura</label>
						<input id="c_abertura" type="text" class="form-control class_inputs">
					</div>
					<div class="form-group col-sm-4">
						<label for="c_natureza_juridica">Natureza Juridica</label>
						<input id="c_natureza_juridica" type="text" class="form-control class_inputs">
					</div>
				</div>
			</div>
		</div>

		<div id="box_inscricao_div" class="box" style="margin-top: 10px; height: auto; background-color: white; border: none; display: none;">
			<div class="box-body" style="height: auto;">
				<div class="row">
					<div class="col-md-12">
						<div class="col">
							<div class="row">
								<div class="form-group col-sm-4">
									<label for="c_cno">Inscrição CNO</label>
									<input id="c_cno" type="text" class="form-control class_inputs">
								</div>
								<div class="form-group col-sm-4">
									<label for="c_caepf">Inscrição CAEPF</label>
									<input id="c_caepf" type="text" class="form-control class_inputs">
								</div>
								<div class="form-group col-sm-4">
									<label for="c_cei">CEI</label>
									<input id="c_cei" type="text" class="form-control class_inputs">
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-3">
									<label for="c_insc_estadual"><span style="color: red; font-size: 18px;">* </span> Inscrição Estadual</label>
									<input id="c_insc_estadual" type="text" class="form-control class_inputs">
								</div>
								<div class="form-group col-sm-3">
									<label for="c_insc_municipal">Inscrição Municipal</label>
									<input id="c_insc_municipal" type="text" class="form-control class_inputs">
								</div>
								<div class="form-group col-sm-6">
									<label for="c_tipo"><span style="color: red; font-size: 18px;">* </span> Tipo</label>
									<select id="c_tipo" class="form-control class_inputs" onchange="func_busca_db_emp()">
										<option selected value="0">SELECIONE UM TIPO</option>
										<option value="1">EMPRESA CONTÁBIL</option>
										<option value="2">EMPRESA MATRIZ</option>
										<option value="3">EMPRESA FILIAL COM CERTIFICADO DIGITAL</option>
										<option value="4">EMPRESA FILIAL SEM CERTIFICADO DIGITAL</option>
										<option value="5">EMPRESA PESSOA FÍSICA COM CERTIFICADO</option>
										<option value="6">EMPRESA PESSOA FÍSICA SEM CERTIFICADO</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div id="box_certificado" class="box" style="margin-top: 10px; height: auto; background-color: white; border: none; display: none;">
			<div class="box-body" style="height: auto;">
				<div class="row">
					<div class="col-md-12">
						<div class="col">
							<div class="row">
								<div class="form-group col-sm-5">
									<label for="c_emp_contabil"><span style="color: red; font-size: 18px;">* </span> Empresa Contábil Responsável</label>
									<select id="c_emp_contabil" class="form-control class_inputs">
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="c_senha_cert">Senha do Certificado</label>
									<input id="c_senha_cert" type="password" class="form-control class_inputs">
								</div>
								<div class="form-group col-sm-4">
									<form id="form_upload" name="form_upload" method="POST" enctype="multipart/form-data">
										<input id="arquivo_cert" name="arquivo_cert" type="file" onchange="func_upload_cert()" style="visibility: hidden;">
										<button id="btn_upload_cert" class="btn btn-primary btn-lg" type="button" onclick="if($('#c_senha_cert').val().length > 0){$('#arquivo_cert').click();}else{alert('Informe a senha do certificado digital.');}" value="importar"> <i class="fa fa-cloud-upload" aria-hidden="true"></i> Anexar Certificado </button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div id="box_certificado2" class="box" style="margin-top: 10px; height: auto; background-color: white; border: none; display: none;">
			<div class="box-body" style="height: auto;">
				<div class="row">
					<div class="col-md-12">
						<div class="col">
							<div class="row">
								<div class="form-group col-sm-3">
									<label for="c_cargo"><span style=" color: red; font-size: 18px;">* </span>Tabela Cargo</label>
									<select id="c_cargo" type="text" class="form-control class_inputs">
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="c_departamento"><span style="color: red; font-size: 18px;">* </span>Tabela Departamento</label>
									<select id="c_departamento" type="text" class="form-control class_inputs">
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="c_rubrica"><span style="color: red; font-size: 18px;">* </span>Tabela Rubricas</label>
									<select id="c_rubrica" type="text" class="form-control class_inputs">
									</select>
								</div>
								<div class="form-group col-sm-3">
									<label for="c_politica_senhas"><span style="color: red; font-size: 18px;">* </span>Tabela Politica Senhas</label>
									<select id="c_politica_senhas" type="text" class="form-control class_inputs">
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>



		<div id="box_form_titulo2" class="box" style="height: 60px; margin-top: 15px; background-color: white; border: none; overflow: hidden; display: none;">
			<div class="row">
				<div class="form-group col-sm-12">
					<h3>Modulos liberados</h3>
				</div>
			</div>
		</div>

		<div id="box_check" class="box" style="height: 60px; margin-top: 5px; background-color: white; border: none; overflow: hidden; display: none;">
			<div class="row">
				<div id="check_box1" class="custom-control custom-switch col-sm-3">
					<input id="c_mod_fisco" type="checkbox" class="form-check-input" id="c_modulo_fisco" checked="">
					<label for="c_mod_fisco" class="custom-control-label" for="c_modulo_fisco">Modulo Fiscal</label>
				</div>
				<div id="check_box2" class="custom-control custom-switch col-sm-3">
					<input id="c_mod_rh" type="checkbox" class="form-check-input" id="c_modulo_rh" checked="">
					<label for="c_mod_rh" class="custom-control-label" for="c_modulo_rh">Modulo RH</label>
				</div>
				<div id="check_box2" class="custom-control custom-switch col-sm-3">
					<input id="c_mod_adm" type="checkbox" class="form-check-input" id="c_modulo_adm" name="c_modulo_adm" checked="">
					<label for="c_mod_adm" class="custom-control-label" for="c_modulo_adm">Administrativo</label>
				</div>
				<div id="check_box2" class="custom-control custom-switch col-sm-3">
					<input id="c_mod_cons" type="checkbox" class="form-check-input" disabled id="c_modulo_cons" checked="">
					<label for="c_mod_cons" class="custom-control-label" for="c_modulo_cons">Conciliador</label>
				</div>
			</div>
		</div>
		<div id="box_logo_emp_title" class="box" style="height: 60px; margin-top: 15px; background-color: white; border: none; overflow: hidden; display: none;">
			<div class="row">
				<div class="form-group col-sm-12">
					<h3>Logo da empresa em documentos</h3>
				</div>
			</div>
		</div>
		<div id="box_logo_emp" class="box" style="height: 300px; margin-top: 5px; background-color: white; border: none; overflow: hidden; display: none;">
			<div class="row">
				<div class="col-md-3">
					<div class="col text-center">
						<form id="form_logo" name="form_logo" enctype="multipart/form-data" method="POST">
							<!-- <input id="c_id_img" name="c_id_img" type="hidden" value="0"> -->
							<img id="img_logo" class="img-fluid" src="" alt="" style="border: 1px solid #B0B4B5;background:#cccccc;width:200px;height:200px;border-radius:100px;-moz-border-radius:100px;-webkit-border-radius:100px;box-shadow: 1px 1px 2px #333333;-moz-box-shadow: 1px 1px 2px #333333;-webkit-box-shadow: 1px 1px 2px #333333;">
							<input accept="image/jpeg" id="logofoto" name="logofoto" type="file" onchange="func_upload_logo()" style="visibility: hidden;">
						</form>
					</div>
					<div class="col text-center">
						<button class="btn btn-warning" type="button" onclick="$('#logofoto').click()"> <i class="fa fa-camera" aria-hidden="true"></i> Importar Logo </button>
					</div>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<div class="row">
				<div class="form-group col-sm-xs-12 text-right" style="margin-right: 5px; margin-top: 20px; margin-bottom: 20px;">
					<button id="btn_novo_reg" class="btn btn-primary" style="border-radius: 10px; width: 100px;" onclick="func_novo_registro()">Novo</button>
					<button disabled id="btn_salvar_reg" class="btn btn-success" style="border-radius: 10px; width: 100px; display: none;" onclick="func_salvar_registro()">Salvar</button>
					<button disabled id="btn_desab_empresa" class="btn btn-danger" style="border-radius: 10px; width: 100px; display: none;" onclick="func_desabilitar()">Desabilitar</button>
				</div>
			</div>
		</div>

	</div>


	<script src="../class/DataTables/datatables.min.js"></script>
	<script>
		$(document).ready(function() {

			$("#box_form_titulo").hide();
			$("#box_form_titulo2").hide();
			$("#box_form").hide();
			$("#box_form_cad").hide();
			$("#btn_salvar_reg").hide();
			$("#btn_desab_empresa").hide();
			$("#box_check").hide();
			$("#box_logo_emp").hide();
			$("#box_logo_emp_title").hide();
			$("#box_certificado").hide();
			$("#box_certificado2").hide();
			$("#box_inscricao_div").hide();

			$("#tab1b").empty();
			$("#box_tab_titulo").show();
			$("#box_tab1").show();
			$("#box_tab_footer").show();

			func_lista_emp_contabil();
			func_lista_cargo();
			func_lista_departamento();
			func_lista_rubrica();
			func_lista_politica_senha();

			func_carrega_tab_emp();

			$("#c_abertura").mask('99/99/9999');
			$("#c_tab_busca_texto").mask("0000000000");
			$("#cnpj").mask("00000000000000");

		});

		function func_upload_cert() {

			var v_cnpj = $("#cnpj").val();
			var v_certPassword = $("#c_senha_cert").val();

			// Captura os dados do formulário
			var formulario = document.getElementById('form_upload');
			// Instância o FormData passando como parâmetro o formulário
			var formData = new FormData(formulario);
			formData.set("v_acao", "V_UPLOAD_CERT");
			formData.set("v_cnpj", v_cnpj);
			formData.set("v_certPassword", v_certPassword);

			$.ajax({
				url: 'lib/lib_cad_empresas.php',
				type: 'post',
				data: formData,
				contentType: false,
				processData: false,
				success: function(data) {
					var v_json = JSON.parse(data);

					if (v_json.msg_ev == "success") {
						$("#btn_upload_cert").html("Certificado Anexado !");
						$("#btn_upload_cert").removeClass("btn-primary");
						$("#btn_upload_cert").addClass("btn-success");
					} else {
						$("#btn_upload_cert").html("Anexar Certificado");
						$("#btn_upload_cert").removeClass("btn-success");
						$("#btn_upload_cert").addClass("btn-primary");
					}

					Swal.fire({
						icon: v_json.msg_ev,
						title: v_json.msg_titulo,
						text: v_json.msg
					})
				},
			});

		}

		function func_tab_paginar(vj_pag) {
			var v_pag = vj_pag;
			var v_limit = $("#c_limit").val();
			$("#vf_tab_btn_pag_select").val(v_pag + 1);
			$("#vf_tab_sql_limit_in").val(v_limit * v_pag);
			func_carrega_tab_emp();
		}


		function func_busca_db_emp() {

			var v_tipo = $("#c_tipo").val();
			var v_cnpj = $("#cnpj").val();

			if (v_tipo == 4 || v_tipo == 6) {
				$("#btn_upload_cert").prop("disabled", true);
				$("#c_senha_cert").prop("disabled", true);
				$("#c_senha_cert").val("");
			} else {
				$("#btn_upload_cert").prop("disabled", false);
				$("#c_senha_cert").prop("disabled", false);
			}

			if (v_tipo == 3 || v_tipo == 4) {
				$.ajax({
					type: "POST",
					url: "../mod/lib/lib_cad_empresas.php",
					data: {
						"v_acao": "EV_DB_EMP",
						"v_cnpj": v_cnpj
					},
					success: function(data) {
						if (data > 0) {
							$("#c_db_emp").val(data);
						} else {
							$("#c_db_emp").val("0");
							Swal.fire({
								icon: "error",
								title: "FALHA!",
								text: "A matriz desta empresa não está cadastrada.  Favor, cadastre primeiro a matriz para depois cadastrar a filial."
							})
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
		}

		function func_select_doc() {

			var v_tipo_doc = $("#tipo_doc").val();
			$("#l_doc").html(v_tipo_doc);
			$("#btn_upload_cert").prop("disabled", true);
			$("#cnpj").val("");

			if (v_tipo_doc == "CNPJ") {
				$("#c_tipo").empty();
				var options = '<option selected value="0">SELECIONE UM TIPO</option>';
				options += '<option value="1">EMPRESA CONTABIL</option>';
				options += '<option value="2">EMPRESA MATRIZ</option>';
				options += '<option value="3">EMPRESA FILIAL COM CERTIFICADO DIGITAL</option>';
				options += '<option value="4">EMPRESA FILIAL SEM CERTIFICADO DIGITAL</option>';
				$('#c_tipo').html(options);
				$('#c_tipo').val("2");
			} else {
				$("#c_tipo").empty();
				var options = '<option selected value="0">SELECIONE UM TIPO</option>';
				options += '<option value="5">EMPRESA PESSOA FÍSICA COM CERTIFICADO</option>';
				options += '<option value="6">EMPRESA PESSOA FÍSICA SEM CERTIFICADO</option>';
				$('#c_tipo').html(options);
				$('#c_tipo').val(6);
			}
		}



		function func_carrega_tab_emp() {

			$("#c_acao").val("");
			var v_acao = "LISTAR_EMPRESAS";
			// var v_tab_campo = $("#c_tab_campo").val();
			// var v_tab_ordem = $("#c_tab_ordem").val();
			// var v_tab_busca_campo = $("#c_tab_busca_campo").val();
			// var v_tab_busca_texto = $("#c_tab_busca_texto").val();
			// var v_tab_sql_limit_in = $("#vf_tab_sql_limit_in").val();
			// var v_limit = $("#c_limit").val();

			$("#cnpj").prop("disabled", true);
			$("#c_nome").prop("disabled", true);
			$("#tipo_doc").prop("disabled", true);
			$("#c_tipo").prop("disabled", true);

			$("#c_id").val("");
			$("#cnpj").val("");
			$("#c_nome").val("");

			$("#btn_novo_reg").prop("disabled", false);
			$("#btn_salvar_reg").prop("disabled", true);
			$("#btn_desab_empresa").prop("disabled", true);

			$.ajax({
				type: "POST",
				url: "../mod/lib/lib_cad_empresas.php",
				data: {
					"v_acao": v_acao,
					// "v_tab_campo": v_tab_campo,
					// "v_tab_ordem": v_tab_ordem,
					// "v_tab_busca_campo": v_tab_busca_campo,
					// "v_tab_busca_texto": v_tab_busca_texto,
					// "v_tab_sql_limit_in": v_tab_sql_limit_in,
					// "v_limit": v_limit
				},
				success: function(data) {
					var options = '';
					var v_index = 0;
					var v_num_linhas = 0;
					$('#tab1').DataTable().destroy();
					$("#tab1b").empty();
					v_num_linhas = data[0].linhas;
					for (v_index = 1; v_index < data.length; v_index++) {
						options += '<tr  style="cursor: pointer;" onclick="func_select_emp(\'' + data[v_index].Id + '\');"><td>' + data[v_index].Id + '</td><td>' + data[v_index].Nome + '</td><td>' + data[v_index].Cnpj + '</td><td>' + data[v_index].fisco_cert_dt_validade + '</td><td>' + data[v_index].St_Cadastro + '</td></tr>';
					}
					$('#tab1b').html(options);

					$("#tab1").DataTable({
						"language": {
							"url": "../class/DataTables/portugues.json",
						},
						"columnDefs": [{
								"width": "60%",
								"targets": 1,
							},
							{
								"width": "15%",
								"targets": [2, 3, 4],
							},
							{
								"width": "5%",
								"targets": 0,
							}
						],
						"lengthMenu": [
							[5, 10, 25, 50, -1],
							[5, 10, 25, 50, "Todos"]
						],
						"order": [
							[1, "asc"]
						],
						"scrollY": "50vh",
						"scrollX": true,
						"scrollCollapse": true,
						"paging": true
					});

					// $("#div_tab_paginacao").empty();
					// var divAtual = document.getElementById("div_tab_paginacao");
					// var v_num_pag = Math.round(v_num_linhas / v_limit);
					// for (v_index = 0; v_index <= v_num_pag; v_index++) {
					// 	var novoBtn = document.createElement("button");
					// 	novoBtn.setAttribute('id', 'btn_pag' + (v_index + 1));
					// 	novoBtn.setAttribute('class', 'btn btn-default');
					// 	novoBtn.innerHTML = (v_index + 1);
					// 	novoBtn.setAttribute('onClick', 'func_tab_paginar(' + v_index + ');');
					// 	divAtual.appendChild(novoBtn);
					// }

					// var v_tab_btn_pag_select = $("#vf_tab_btn_pag_select").val();
					// $("#btn_pag" + v_tab_btn_pag_select).css("background-color", "#C6E2FF");

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


		//VALIDA EXTÃO DE ARQUIVOS 
		function validaExtensao(id) {
			var result = true;
			var extensoes = new Array('pfx', 'p12'); // Arquivos permitidos
			var ext = $('#' + id).val().split(".")[1].toLowerCase();
			if ($.inArray(ext, extensoes) === -1) { // Arquivo não permitido
				result = false;
			} else {
				alert("Erro ao anexar certificado!");
			}
			return result;
		}



		//SELECIONA EMPRESA NO DB
		function func_select_emp(v_id) {

			$("#c_acao").val("EV_SELECT");

			$.ajax({
				type: "POST",
				url: "../mod/lib/lib_cad_empresas.php",
				data: {
					"v_acao": "EV_SELECT",
					"v_id": v_id
				},
				success: function(data) {

					$("#btn_novo_reg").hide();
					$("#box_tab_footer").hide();
					$("#box_tab_titulo").hide();
					$("#box_tab1").hide();
					$("#box_form_cad").show();
					$("#box_form_titulo").show();
					$("#box_certificado").show();
					$("#box_certificado2").show();
					$("#box_inscricao_div").show();
					$("#box_form_titulo2").show();
					$("#box_check").show();
					$("#box_logo_emp").show();
					$("#box_logo_emp_title").show();
					$("#btn_salvar_reg").show();
					$("#btn_desab_empresa").show();
					$("#cnpj").prop("disabled", true);
					$("#tipo_doc").prop("disabled", true);
					$("#c_tipo").prop("disabled", true);
					$("#c_nome").prop("disabled", false);
					$("#c_id").val(data[0].id);
					$("#c_tipo").val(data[0].tipo);

					if (data[0].tipo != 4) {
						$("#btn_upload_cert").prop("disabled", false);
					} else {
						$("#btn_upload_cert").prop("disabled", true);
					}

					$("#c_nome").val(data[0].nome);
					$("#c_fantasia").val(data[0].descricao);
					$("st_cadastro").val(data[0].st_cadastro);
					$("#cnpj").val(data[0].cnpj).mask("00.000.000/0000-00");
					$("#c_uf").val(data[0].uf);
					$("#c_senha_cert").val(data[0].fisco_certi_senha);
					$("#c_emp_contabil").val(data[0].fisco_cnpj_agrocontar);
					$("#c_atividade_principal").val(data[0].ativ_principal);
					$("#c_telefone").val(data[0].telefone);
					$("#c_email").val(data[0].email);
					$("#c_cep").val(data[0].cep);
					$("#c_logradouro").val(data[0].logradouro);
					$("#c_complemento").val(data[0].complemento);
					$("#c_numero").val(data[0].numero);
					$("#c_bairro").val(data[0].bairro);
					$("#c_municipio").val(data[0].municipio);
					$("#c_insc_estadual").val(data[0].insc_estadual);
					$("#c_insc_municipal").val(data[0].insc_municipal);
					$("#c_natureza_juridica").val(data[0].natureza_juridica);
					$("#c_atividades_secundarias").val(data[0].ativ_secundarias);
					$("#c_abertura").val(data[0].dat_abertura);
					$("#c_cargo").val(data[0].id_tab_cargos);
					$("#c_departamento").val(data[0].id_tab_departamentos);
					$("#c_rubrica").val(data[0].id_tab_rubricas);
					$("#c_politica_senhas").val(data[0].id_tab_politica_senhas);



					console.log("VARIAVEIS SEM LENGHT: ", (data[0].id_tab_cargos), (data[0].id_tab_departamentos), (data[0].id_tab_rubricas), (data[0].id_tab_politica_senhas), (data[0].descricao));
					// console.log("VARIAVEIS LENGHT: ", v_id_cargo.length, v_id_departamento.length, v_id_rubrica.length, v_id_politica_senhas.length, v_fisco_cnpj_agrocontar.length);

					//ATRIBUINDO VALORES PADRÕES PARA VARIÁVEIS NULAS
					if ((data[0].id_tab_cargos) == null) {
						$("#c_cargo").val(0);
					}
					if ((data[0].id_tab_departamentos) == null) {
						$("#c_departamento").val(0);
					}
					if ((data[0].id_tab_rubricas) == null) {
						$("#c_rubrica").val(0);
					}
					if ((data[0].id_tab_politica_senhas) == null) {
						$("#c_politica_senhas").val(0);
					}

					console.log("VARIAVEIS SEM LENGHT: ", (data[0].id_tab_cargos), (data[0].id_tab_departamentos), (data[0].id_tab_rubricas), (data[0].id_tab_politica_senhas), (data[0].descricao));


					$("#img_logo").attr("src", data[0].url_arquivo);
					if (data[0].modulo_fisco == "S") {
						$("#c_modulo_fisco").attr("checked", "checked");
					} else {
						$("#c_modulo_fisco").attr("checked", "");
					}
					if (data[0].modulo_rh == "S") {
						$("#c_modulo_rh").attr("checked", "checked");
					} else {
						$("#c_modulo_rh").attr("checked", "");
					}
					if (data[0].modulo_adm == "S") {
						$("#c_modulo_adm").attr("checked", "checked");
					} else {
						$("#c_modulo_adm").attr("checked", "");
					}
					if (data[0].modulo_cons == "S") {
						$("#c_modulo_cons").attr("checked", "checked");
					} else {
						$("#c_modulo_cons").attr("checked", "");
					}
					$("#btn_novo_reg").prop("disabled", false);
					$("#btn_salvar_reg").prop("disabled", false);
					$("#btn_desab_empresa").prop("disabled", false);

					if (data[0].check_cert == "OK") {
						if (data[0].check_cert_dt_validade == "OK") {
							$("#btn_upload_cert").html("Certificado Anexado<br>Válido até " + data[0].fisco_cert_dthr_validade);
							$("#btn_upload_cert").removeClass("btn-primary");
							$("#btn_upload_cert").removeClass("btn-danger");
							$("#btn_upload_cert").addClass("btn-success");
						} else {
							$("#btn_upload_cert").html("Certificado Anexado<br>Data expirada: " + data[0].fisco_cert_dthr_validade);
							$("#btn_upload_cert").removeClass("btn-primary");
							$("#btn_upload_cert").removeClass("btn-success");
							$("#btn_upload_cert").addClass("btn-danger");
						}
					} else {
						$("#btn_upload_cert").html("Anexar Certificado");
						$("#btn_upload_cert").removeClass("btn-success");
						$("#btn_upload_cert").removeClass("btn-danger");
						$("#btn_upload_cert").addClass("btn-primary");
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



		function func_busca_campo_select() {
			$("#c_tab_busca_texto").val("");
			var v_tab_busca_campo = $("#c_tab_busca_campo").val();
			if (v_tab_busca_campo.split('|')[1] == "num") {
				$("#c_tab_busca_texto").mask("0000000000");
			} else {
				if (v_tab_busca_campo.split('|')[0] == "Cnpj") {
					$("#c_tab_busca_texto").mask("00.000.000/0000-00");
				} else {
					$("#c_tab_busca_texto").unmask();
				}
			}
		}


		function func_lista_emp_contabil() {
			$.ajax({
				type: "POST",
				url: "lib/lib_cad_empresas.php",
				data: {
					"v_acao": "LISTA_EMP_CONTABIL"


				},
				success: function(data) {

					var options = '<option value="0">SELECIONE UMA EMPRESA</option>';
					$("#c_emp_contabil").empty();
					for (v_index = 0; v_index < data.length; v_index++) {
						options += '<option value="' + data[v_index].cnpj + '">' + data[v_index].nome + '</option>';
					}
					$('#c_emp_contabil').html(options);
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

		function func_lista_cargo() {
			$.ajax({
				type: "POST",
				url: "lib/lib_cad_empresas.php",
				data: {
					"v_acao": "LISTA_CARGO"


				},
				success: function(data) {

					var options = '<option value="0">SELECIONE UM CARGO</option>';
					$("#c_cargo").empty();
					for (v_index = 0; v_index < data.length; v_index++) {
						options += '<option value="' + data[v_index].id + '">' + data[v_index].descricao + '</option>';
					}
					$('#c_cargo').html(options);
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


		function func_lista_departamento() {
			$.ajax({
				type: "POST",
				url: "lib/lib_cad_empresas.php",
				data: {
					"v_acao": "LISTA_DEPARTAMENTO"


				},
				success: function(data) {

					var options = '<option value="0">SELECIONE UM DEPARTAMENTO</option>';
					$("#c_departamento").empty();
					for (v_index = 0; v_index < data.length; v_index++) {
						options += '<option value="' + data[v_index].id + '">' + data[v_index].descricao + '</option>';
					}
					$('#c_departamento').html(options);
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


		function func_lista_rubrica() {
			$.ajax({
				type: "POST",
				url: "lib/lib_cad_empresas.php",
				data: {
					"v_acao": "LISTA_RUBRICA"


				},
				success: function(data) {

					var options = '<option value="0">SELECIONE UMA RUBRICA</option>';
					$("#c_rubrica").empty();
					for (v_index = 0; v_index < data.length; v_index++) {
						options += '<option value="' + data[v_index].id + '">' + data[v_index].descricao + '</option>';
					}
					$('#c_rubrica').html(options);
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

		function func_lista_politica_senha() {
			$.ajax({
				type: "POST",
				url: "lib/lib_cad_empresas.php",
				data: {
					"v_acao": "LISTA_POLITICA_SENHA"
				},
				success: function(data) {

					var options = '<option value="0">SELECIONE UMA POLITICA DE SENHA</option>';
					$("#c_politica_senhas").empty();
					for (v_index = 0; v_index < data.length; v_index++) {
						options += '<option value="' + data[v_index].id + '">' + data[v_index].descricao + '</option>';
					}
					$('#c_politica_senhas').html(options);
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


		function func_novo_registro() {

			$("#c_acao").val("EV_NOVO");

			$("#box_form_cad").show();
			$("#box_form_titulo").show();
			$("#box_certificado").show();
			$("#box_certificado2").show();
			$("#box_inscricao_div").show();
			$("#box_form_titulo2").show();
			$("#btn_salvar_reg").show();
			$("#box_check").show();
			$("#box_logo_emp").show();
			$("#box_logo_emp_title").show();

			$("#btn_desab_empresa").hide();
			$("#btn_novo_reg").hide();
			$("#box_tab_footer").hide();
			$("#box_tab_titulo").hide();
			$("#box_tab1").hide();


			$("#cnpj").prop("disabled", false);
			$("#c_nome").prop("disabled", false);
			$("#tipo_doc").prop("disabled", false);
			$("#c_tipo").prop("disabled", false);

			$("#c_id").val("");
			$("#c_tipo").val("0");
			$("#c_nome").val("");
			$("#c_fantasia").val("");
			$("st_cadastro").val("");
			$("#cnpj").val("");
			$("#c_uf").val("");
			$("#c_fisco_cuf").val("0");
			$("#c_senha_cert").val("");
			$("#c_emp_contabil").val("0");
			$("#c_cargo").val("0");
			$("#c_departamento").val("0");
			$("#c_rubrica").val("0");
			$("#c_politica_senhas").val("0");
			// $("#c_fisco_import_data_hora").val("");
			// $("#c_fisco_ultimo_nsu_entrada").val("");
			$("#c_modulo_adm").attr("checked", false);
			$("#c_modulo_rh").attr("checked", false);
			$("#c_modulo_fisco").attr("checked", false);
			$("#c_modulo_cons").attr("checked", false);
			$("#c_atividade_principal").val("");
			$("#c_telefone").val("");
			$("#c_db_emp").val("0");
			$("#c_email").val("");
			$("#c_cep").val("");
			$("#c_logradouro").val("");
			$("#c_complemento").val("");
			$("#c_numero").val("");
			$("#c_bairro").val("");
			$("#c_municipio").val("");
			$("#c_insc_estadual").val("");
			$("#c_insc_municipal").val("");
			$("#c_natureza_juridica").val("");
			$("#c_atividades_secundarias").val("");
			$("#c_abertura").val("");
			$("#btn_novo_reg").prop("disabled", false);
			$("#btn_salvar_reg").prop("disabled", false);
			$("#btn_desab_empresa").prop("disabled", true);

		}



		function func_salvar_registro() {

			v_acao = $("#c_acao").val();
			if (v_acao != "EV_NOVO") {
				v_acao = "EV_SALVAR";
			}

			if ($('#c_mod_fisco').is(":checked")) {
				v_mod_fisco = "S";
			} else {
				v_mod_fisco = "N";
			}

			if ($('#c_mod_rh').is(":checked")) {
				v_mod_rh = "S";
			} else {
				v_mod_rh = "N";
			}

			if ($('#c_mod_adm').is(":checked")) {
				v_mod_adm = "S";
			} else {
				v_mod_adm = "N";
			}

			if ($('#c_mod_cons').is(":checked")) {
				v_mod_cons = "S";
			} else {
				v_mod_cons = "N";
			}

			v_id = $("#c_id").val();
			v_tipo = $("#c_tipo").val();
			v_nome = $("#c_nome").val();
			v_fantasia = $("#c_fantasia").val();
			v_st_cadastro = $("#st_cadastro").val();
			v_cnpj = $("#cnpj").val();
			v_uf = $("#c_uf").val();
			v_fisco_certi_senha = $("#c_senha_cert").val();
			v_fisco_cnpj_agrocontar = $("#c_emp_contabil").val();
			v_senha_cert = $("#c_senha_cert").val();
			v_atividade_principal = $("#c_atividade_principal").val();
			v_telefone = $("#c_telefone").val();
			v_email = $("#c_email").val();
			v_cep = $("#c_cep").val();
			v_logradouro = $("#c_logradouro").val();
			v_complemento = $("#c_complemento").val();
			v_numero = $("#c_numero").val();
			v_bairro = $("#c_bairro").val();
			v_municipio = $("#c_municipio").val();
			v_insc_estadual = $("#c_insc_estadual").val();
			v_insc_municipal = $("#c_insc_municipal").val();
			v_natureza_juridica = $("#c_natureza_juridica").val();
			v_atividades_secundarias = $("#c_atividades_secundarias").val();
			v_abertura = $("#c_abertura").val();
			v_db_emp = $("#c_db_emp").val();
			v_id_cargo = $("#c_cargo").val();
			v_id_departamento = $("#c_departamento").val();
			v_id_rubrica = $("#c_rubrica").val();
			v_id_politica_senhas = $("#c_politica_senhas").val();
			v_tipo = $("#c_tipo").val();


			if (v_id_cargo == null) {
				v_id_cargo = '0';
			}
			if (v_id_departamento == null) {
				v_id_departamento = '0';
			}
			if (v_id_rubrica == null) {
				v_id_rubrica = '0';
			}
			if (v_id_politica_senhas == null) {
				v_id_politica_senhas = '0';
			}

			if (v_cnpj.length > 5 && v_nome.length > 5 && v_uf != "-" && v_tipo.length > 0 && v_fisco_cnpj_agrocontar > 0 && v_insc_estadual.length >= 0 && v_id_cargo > 0 && v_id_departamento > 0 && v_id_rubrica > 0 && v_id_politica_senhas > 0) {

				$.ajax({
					type: "POST",
					url: "../mod/lib/lib_cad_empresas.php",
					data: {
						"v_acao": v_acao,
						"v_db_emp": v_db_emp,
						"v_id": v_id,
						"v_tipo": v_tipo,
						"v_nome": v_nome,
						"v_fantasia": v_fantasia,
						"v_st_cadastro": v_st_cadastro,
						"v_cnpj": v_cnpj,
						"v_uf": v_uf,
						"v_fisco_certi_senha": v_fisco_certi_senha,
						"v_fisco_cnpj_agrocontar": v_fisco_cnpj_agrocontar,
						"v_senha_cert": v_senha_cert,
						"v_atividade_principal": v_atividade_principal,
						"v_telefone": v_telefone,
						"v_email": v_email,
						"v_cep": v_cep,
						"v_logradouro": v_logradouro,
						"v_complemento": v_complemento,
						"v_numero": v_numero,
						"v_bairro": v_bairro,
						"v_municipio": v_municipio,
						"v_insc_estadual": v_insc_estadual,
						"v_insc_municipal": v_insc_municipal,
						"v_natureza_juridica": v_natureza_juridica,
						"v_atividades_secundarias": v_atividades_secundarias,
						"v_abertura": v_abertura,
						"v_mod_fisco": v_mod_fisco,
						"v_mod_rh": v_mod_rh,
						"v_mod_adm": v_mod_adm,
						"v_mod_cons": v_mod_cons,
						"v_id_cargo": v_id_cargo,
						"v_id_departamento": v_id_departamento,
						"v_id_rubrica": v_id_rubrica,
						"v_id_politica_senhas": v_id_politica_senhas
					},
					success: function(data) {
						var v_json = JSON.parse(data);
						Swal.fire({
							icon: v_json.msg_ev,
							title: v_json.msg_titulo,
							text: v_json.msg
						})

						if (v_json.msg_ev == "success") {
							func_carrega_tab_emp()
							goBack();
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
			} else {

				Swal.fire({
					icon: "error",
					title: "FALHA!",
					text: "Preencha todos os campos."
				})

			}
		}



		function func_desabilitar() {

			Swal.fire({
				title: 'Você tem certeza?',
				text: "Você não poderá reverter isso!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Sim, pode excluir!'
			}).then((result) => {
				if (result.value) {

					v_acao = "EV_DESABILITAR_EMPRESA";
					v_cnpj = $("#cnpj").val();

					if (v_cnpj > 0) {

						$.ajax({
							type: "POST",
							url: "../mod/lib/lib_cad_empresas.php",
							data: {
								"v_acao": v_acao,
								"v_cnpj": v_cnpj
							},
							success: function(data) {
								var v_json = JSON.parse(data);
								Swal.fire(
									v_json.msg_titulo,
									v_json.msg,
									v_json.msg_ev
								)

								if (v_json.msg_ev == "success") {
									func_carrega_tab_emp();
								}
							},
							error: function(request, status, erro) {
								swal("FALHA!", "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText, "error");
							}
						});
					} else {

						Swal.fire({
							icon: "error",
							title: "FALHA!",
							text: "Selecione um registro."
						})

					}
				}
			})
		}


		function func_busca_empresa() {

			if ($("#tipo_doc").val() == "CNPJ" && $("#cnpj").val().length == 14) {

				if ($("#cnpj").val().substring(8, 12) > 1) {
					$("#c_tipo").empty();
					var options = '<option selected value="0">SELECIONE UM TIPO</option>';
					options += '<option value="3">FILIAL C/ CERTIFICADO DIGITAL</option>';
					options += '<option value="4">FILIAL S/ CERTIFICADO DIGITAL</option>';
					$('#c_tipo').html(options);
					$('#c_tipo').val(0);
				} else {
					$("#c_tipo").empty();
					var options = '<option selected value="0">SELECIONE UM TIPO</option>';
					options += '<option value="1">CONTABIL</option>';
					options += '<option value="2">MATRIZ</option>';
					$('#c_tipo').html(options);
					$('#c_tipo').val(0);
				}

				//Início do Comando AJAX
				v_acao = $("#c_acao").val();
				$.ajax({

					url: 'lib/lib_cad_empresas.php',
					dataType: 'json',
					type: "POST",
					data: {
						"v_acao": 'buscar_cnpj',
						"v_cnpj": $("#cnpj").val(),
					},

					success: function(resposta) {
						//Confere se houve erro e o imprime
						if (resposta.status == "ERROR") {
							alert(resposta.message + "\nPor favor, digite os dados manualmente.");
							// $("#cnpj").val("");
							// return false;
						}

						$("#c_status").val(resposta.status);
						$("#c_message").val(resposta.message);
						$("#c_billing").val(resposta.billing);
						$("#c_billing.free").val(resposta.billing.free);
						$("#c_billing.database").val(resposta.billing.database);
						$("#cnpj").val(resposta.cnpj);

						$("#c_tipo").val("0");
						$("#btn_upload_cert").prop("disabled", "true");
						$("#c_abertura").val(resposta.abertura);
						$("#c_nome").val(resposta.nome);
						$("#c_fantasia").val(resposta.fantasia);
						$("#c_atividade_principal").val(resposta.atividade_principal[0].text + "(" + resposta.atividade_principal[0].code + ")");
						$("#c_atividade_principal_code").val(resposta.atividade_principal[0].code + "(" + resposta.atividade_principal[0].code + ")");
						$("#c_atividade_principal_text").val(resposta.atividade_principal.text);
						$("#c_atividades_secundarias").val(resposta.atividades_secundarias[0].text + "(" + resposta.atividade_principal[0].code + ")");
						$("#c_atividades_secundarias_code").val(resposta.atividades_secundarias.code);
						$("#c_atividades_secundarias_text").val(resposta.atividades_secundarias.text);
						$("#c_natureza_juridica").val(resposta.natureza_juridica);
						$("#c_logradouro").val(resposta.logradouro);
						$("#c_numero").val(resposta.numero);
						$("#c_complemento").val(resposta.complemento);
						$("#c_cep").val(resposta.cep);
						$("#c_bairro").val(resposta.bairro);
						$("#c_municipio").val(resposta.municipio);
						$("#c_uf").val(resposta.uf);
						$("#c_email").val(resposta.email);
						$("#c_telefone").val(resposta.telefone);
						$("#c_efr").val(resposta.efr);
						$("#c_situacao").val(resposta.situacao);
						$("#c_data_situacao").val(resposta.data_situacao);
						$("#c_motivo_situacao").val(resposta.motivo_situacao);
						$("#c_situacao_especial").val(resposta.situacao_especial);
						$("#c_data_situacao_especial").val(resposta.data_situacao_especial);
						$("#c_capital_social").val(resposta.capital_social);
						$("#c_qsa").val(resposta.qsa[0].text + "(" + resposta.atividade_principal[0].code + ")");
						$("#c_qsa.nome").val(resposta.qsa.nome);
						$("#c_qsa.qual").val(resposta.qsa.qual);
						$("#c_qsa.pais_origem").val(resposta.qsa.pais_origem);
						$("#c_qsa.nome_rep_legal").val(resposta.qsa.nome_rep_legal);
						$("#c_qsa.qual_rep_legal").val(resposta.qsa.qual_rep_legal);

					}
				});
			}
		}

		function func_atualiza_empresa() {

			if ($("#tipo_doc").val() == "CNPJ" && $("#cnpj").val().length == 14) {

				// Início do Comando AJAX
				v_acao = $("#c_acao").val();
				$.ajax({

					url: 'lib/lib_cad_empresas.php',
					dataType: 'json',
					type: "POST",
					data: {
						"v_acao": 'buscar_cnpj',
						"v_cnpj": $("#cnpj").val(),
					},

					success: function(resposta) {
						//Confere se houve erro e o imprime
						if (resposta.status == "ERROR") {
							alert(resposta.message + "\nPor favor, digite os dados manualmente.");
							$("#cnpj").val("");
							return false;
						}

						$("#c_status").val(resposta.status);
						$("#c_message").val(resposta.message);
						$("#c_billing").val(resposta.billing);
						$("#c_billing.free").val(resposta.billing.free);
						$("#c_billing.database").val(resposta.billing.database);
						$("#cnpj").val(resposta.cnpj);
						// $("#c_tipo").val("0");
						$("#btn_upload_cert").prop("disabled", "true");
						$("#c_abertura").val(resposta.abertura);
						$("#c_nome").val(resposta.nome);
						$("#c_fantasia").val(resposta.fantasia);
						$("#c_atividade_principal").val(resposta.atividade_principal[0].text + "(" + resposta.atividade_principal[0].code + "), ");
						$("#_code").val(resposta.atividade_principal[0].code + "(" + resposta.atividade_principal[0].code + ")");
						$("#_text").val(resposta.atividade_principal.text);
						$("#c_atividades_secundarias").val(resposta.atividades_secundarias[0].text + "(" + resposta.atividade_principal[0].code + "), ");
						$("#c_atividades_secundarias_code").val(resposta.atividades_secundarias.code);
						$("#c_atividades_secundarias_text").val(resposta.atividades_secundarias.text);
						$("#c_natureza_juridica").val(resposta.natureza_juridica);
						$("#c_logradouro").val(resposta.logradouro);
						$("#c_numero").val(resposta.numero);
						$("#c_complemento").val(resposta.complemento);
						$("#c_cep").val(resposta.cep);
						$("#c_bairro").val(resposta.bairro);
						$("#c_municipio").val(resposta.municipio);
						$("#c_uf").val(resposta.uf);
						$("#c_email").val(resposta.email);
						$("#c_telefone").val(resposta.telefone);
						$("#c_efr").val(resposta.efr);
						$("#c_situacao").val(resposta.situacao);
						$("#c_data_situacao").val(resposta.data_situacao);
						$("#c_motivo_situacao").val(resposta.motivo_situacao);
						$("#c_situacao_especial").val(resposta.situacao_especial);
						$("#c_data_situacao_especial").val(resposta.data_situacao_especial);
						$("#c_capital_social").val(resposta.capital_social);
						$("#c_qsa").val(resposta.qsa[0].text + "(" + resposta.atividade_principal[0].code + ")");
						$("#c_qsa.nome").val(resposta.qsa.nome);
						$("#c_qsa.qual").val(resposta.qsa.qual);
						$("#c_qsa.pais_origem").val(resposta.qsa.pais_origem);
						$("#c_qsa.nome_rep_legal").val(resposta.qsa.nome_rep_legal);
						$("#c_qsa.qual_rep_legal").val(resposta.qsa.qual_rep_legal);

					}
				});
			}
		}


		function goBack() {


			$("#box_form_titulo").hide();
			$("#box_form_titulo2").hide();
			$("#box_form_cad").hide();
			$("#btn_salvar_reg").hide();
			$("#btn_desab_empresa").hide();
			$("#box_check").hide();
			$("#box_logo_emp").hide();
			$("#box_logo_emp_title").hide();
			$("#box_certificado").hide();
			$("#box_certificado2").hide();
			$("#box_inscricao_div").hide();

			$("#c_id").val("");
			$("#c_tipo").val("0");
			$("#c_nome").val("");
			$("#c_fantasia").val("");
			$("st_cadastro").val("");
			$("#cnpj").val("");
			$("#c_uf").val("");
			// $("#c_fisco_cuf").val("");
			$("#c_senha_cert").val("");
			$("#c_emp_contabil").val("");
			$("#c_cargo").val("");
			$("#c_departamento").val("");
			$("#c_rubrica").val("");
			$("#c_politica_senhas").val("");
			// $("#c_fisco_import_data_hora").val("");
			// $("#c_fisco_ultimo_nsu_entrada").val("");
			// $("#c_modulo_fisco").val("");
			// $("#c_modulo_rh").val("");
			$("#c_atividade_principal").val("");
			$("#c_telefone").val("");
			$("#c_db_emp").val("0");
			$("#c_email").val("");
			$("#c_cep").val("");
			$("#c_logradouro").val("");
			$("#c_complemento").val("");
			$("#c_numero").val("");
			$("#c_bairro").val("");
			$("#c_municipio").val("");
			$("#c_insc_estadual").val("");
			$("#c_insc_municipal").val("");
			$("#c_natureza_juridica").val("");
			$("#c_atividades_secundarias").val("");
			$("#c_abertura").val("");

			$("#btn_novo_reg").show();
			$("#box_tab_titulo").show();
			$("#box_tab1").show();
			$("#box_tab_footer").show();

		}

		function func_busca_receita() {
			Swal.fire({
				title: 'Você tem certeza que deseja atualizar com dados da Receita?',
				text: "Você estará inciando a consulta!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Sim, consultar!'
			}).then((result) => {
				if (result.value) {
					Swal.fire(
						'Carregado!',
						'Atualização realizada com sucesso!',
						'success'
					)
					func_atualiza_empresa();
				}
			})
		}

		function func_upload_logo() {


			let v_id = $("#c_id").val();
			// Captura os dados do formulário
			var formulario = document.getElementById('form_logo');


			// Instância o FormData passando como parâmetro o formulário
			var formData = new FormData(formulario);
			formData.append("v_id", v_id);
			formData.append("v_acao", 'UPLOAD_LOGO');
			Swal.fire(
				'Logo Incluida com sucesso',
				'A logo da empresa aparecerá após salvar o cadastro.',
				'warning'
			)
			$.ajax({
				url: '../mod/lib/lib_upload_logo_emp.php',
				type: 'post',
				data: formData,
				contentType: false,
				processData: false,
				success: function(response) {

				}
			});
			// $("#form_logo").submit();
		}
	</script>

</body>

</html>