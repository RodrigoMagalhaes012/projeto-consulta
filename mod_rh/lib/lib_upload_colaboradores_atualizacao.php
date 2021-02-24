<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0044") > 0) {

	$v_pos = strpos($_SESSION["vs_array_access"], "T0044");
	$v_up_colab_ler = substr($_SESSION["vs_array_access"], $v_pos + 8, 1);
	$v_up_colab_criar = substr($_SESSION["vs_array_access"], $v_pos + 12, 1);
	$v_up_colab_gravar = substr($_SESSION["vs_array_access"], $v_pos + 16, 1);
	$v_up_colab_excluir = substr($_SESSION["vs_array_access"], $v_pos + 20, 1);
}

$arquivo_tmp = $_FILES['arquivo']['tmp_name'];

$dados = file($arquivo_tmp);


$v_id_empresa = intval($_SESSION["vs_id_empresa"]);
$v_colaboradores = "";
$v_cargos = "";
$v_depto = "";
$v_escolaridade = "";
$v_tipo_contrato = "";
$v_hist_salario = "";
$v_hist_departamento = "";
$v_hist_cargo = "";
$v_estado_civil = "";
$v_user = "";
$v_user_access = "";
$layout_arquivo = "";


/////////////////////////////////////////////////////
///////ARRAY PARA BUSCAR ID DA EMPRESA COM CNPJ//////
/////////////////////////////////////////////////////

$empresa = array();
$v_sql = "SELECT cnpj, id  FROM db_adm.t_empresas WHERE id > 0 ";
$result2 = pg_query($conn, $v_sql);
while ($row2 = pg_fetch_assoc($result2)) {
	$empresa[$row2["id"]] = $row2["cnpj"];
}


//###################################################################
//######  ARRAY PARA BUSCAR ID DO COLABORADOR COM A MATRICULA  ######
//###################################################################

$colaborador = array();
$v_sql = "SELECT matricula, id  FROM db_adm_rh.t_rh_colaborador WHERE id_empresa = {$v_id_empresa} ";
$result2 = pg_query($conn, $v_sql);

while ($row2 = pg_fetch_assoc($result2)) {
	$colaborador[(int)$row2["matricula"]] = (int)$row2["id"];
}

//#####################################################################
//######  ARRAY PARA BUSCAR CODIGO DA RECEITA NA DB_ADM.T_PAISES ######
//#####################################################################

$cod_pais = array();
$v_sql = "SELECT codigo_rfb, id  FROM db_adm.t_paises WHERE id > 0 ";
$result4 = pg_query($conn, $v_sql);

while ($row4 = pg_fetch_assoc($result4)) {
	$cod_pais[$row4["codigo_rfb"]] = $row4["id"];
}

//###################################################################
//######  ARRAY PARA BUSCAR ID DO USUARIO COM O EMAIL          ######
//###################################################################

$usuario = array();
$v_sql = "SELECT email, id  FROM db_adm.t_user WHERE id > 0 ";
$result3 = pg_query($conn, $v_sql);

while ($row3 = pg_fetch_assoc($result3)) {
	$usuario[$row3["email"]] = $row3["id"];
}

//###################################################################
//######  ARRAY PARA BUSCAR ID DO COLABORADOR COM O EMAIL      ######
//###################################################################

$col_id = array();
$v_sql = "SELECT email, id  FROM db_adm_rh.t_rh_colaborador WHERE id_empresa = {$v_id_empresa} ";
$result = pg_query($conn, $v_sql);

while ($row = pg_fetch_assoc($result)) {
	$col_id[$row["email"]] = $row["id"];
}


///////////////////////////////////////////////////////////////////////////////////////
///////ARRAY PARA VERIFICAR SE IMPORTAÇÃO ESTÁ SENDO REALIZADO NA EMPRESA CORRETA//////
//////////////////////////////////////////////////////////////////////////////////////

$busca_emp = array();
$v_sql = "SELECT id, cnpj
            FROM db_adm.t_empresas
                WHERE id={$_SESSION["vs_id_empresa"]};";
$result2 = pg_query($conn, $v_sql);

if ($row2 = pg_fetch_assoc($result2)) {

	$v_cnpj_emp = $row2["cnpj"];
}


//###############################################
//######      BUSCA CNPJ NO ARQUIVO        ######
//###############################################

foreach ($dados as $linha) {
	$linha = utf8_encode($linha);
	$linha = trim($linha);
	$v_linha_txt = explode(';', $linha);
	//VERIFICANDO A COMPETENCIA
	if ($v_linha_txt[0] == "t_rh_colaborador") {
		$v_cnpj_carga = $v_linha_txt[47];
	}
}



//BUSCA TITULO DO LAYOUT DO ARQUIVO
foreach ($dados as $linha) {
	$linha = utf8_encode($linha);
	$linha = trim($linha);
	$v_linha_txt = explode(';', $linha);
	//VERIFICANDO A COMPETENCIA
	if ($v_linha_txt[0] == "layout_arquivo") {
		$layout_arquivo = $v_linha_txt[1];
	}
}
//###############################################
//######  ATUALIZA CADASTRO DO COLABORADOR ######
//###############################################



if ($layout_arquivo == "colaborador") {
	//VERIFICA SE ESTÁ NA EMPRESA CORRETA
	if ((int)$v_cnpj_carga == (int)$v_cnpj_emp) {

		foreach ($dados as $linha) {
			$linha = utf8_encode($linha);
			$linha = trim($linha);
			$v_linha_txt = explode(';', $linha);


			if ($v_linha_txt[0] == "t_rh_estado_civil") {
				$v_sql = "UPDATE db_adm_rh.t_rh_estado_civil SET
				id = {$v_linha_txt[1]}, 
				estado_civil = '{$v_linha_txt[2]}' 
				where id = {$v_linha_txt[1]}";

				$result = pg_query($conn, $v_sql);
				// print_r("Estado Civil OK \n");
			}

			if ($v_linha_txt[0] == "t_rh_cargos") {
				$v_sql = "UPDATE db_adm_rh.t_rh_cargos SET
				id = '{$v_linha_txt[1]}', 
				nome = '{$v_linha_txt[2]}',
				cbo = '{$v_linha_txt[3]}'
				where id = '{$v_linha_txt[1]}'";
				$result = pg_query($conn, $v_sql);
				// print_r("Cargos OK \n");
			}

			if ($v_linha_txt[0] == "t_rh_departamentos") {
				$v_sql = "UPDATE db_adm_rh.t_rh_departamentos SET
				id = {$v_linha_txt[1]}, 
				nome = '{$v_linha_txt[2]}',
				descricao = '{$v_linha_txt[3]}',
				cod_local = '{$v_linha_txt[4]}'
				where id = {$v_linha_txt[1]}";

				$result = pg_query($conn, $v_sql);
				// print_r("Departamentos OK \n");
			}

			if ($v_linha_txt[0] == "t_rh_escolaridade") {
				$v_sql = "UPDATE db_adm_rh.t_rh_escolaridade SET
				id = {$v_linha_txt[1]},
				escolaridade = '{$v_linha_txt[2]}'
				where id = {$v_linha_txt[1]}";

				$result = pg_query($conn, $v_sql);
				// print_r("Escolaridade OK \n");
			}

			if ($v_linha_txt[0] == "t_rh_tipo_contrato") {
				$v_sql = "UPDATE db_adm_rh.t_rh_tipo_contrato SET
				id = {$v_linha_txt[1]},
				tipo_contrato = '{$v_linha_txt[2]}'
				where id = {$v_linha_txt[1]}";

				$result = pg_query($conn, $v_sql);
				// print_r("Tipo contrato OK \n");
			}

			if ($v_linha_txt[0] == "t_rh_colaborador") {
				if (array_key_exists($v_linha_txt[30], $usuario)) {
					$id_user =  $usuario[$v_linha_txt[30]];
					if (array_key_exists($v_linha_txt[46], $cod_pais)) {
						$id_pais =  $cod_pais[$v_linha_txt[46]];
						if (array_key_exists($v_linha_txt[30], $col_id)) {
							$v_id =  $col_id[$v_linha_txt[30]];
							$v_cpf = addslashes($v_linha_txt[11]);
							$v_nome = strtoupper(addslashes($v_linha_txt[2]));
							$v_sexo = addslashes($v_linha_txt[3]);
							$v_dt_nasc = addslashes(implode('', array_reverse(explode('\'', $v_linha_txt[8]))));
							$v_email_pessoal = strtolower(addslashes($v_linha_txt[31]));
							$v_Nacionalidade = addslashes($id_pais);
							$v_Naturalidade = addslashes($v_linha_txt[5]);
							$v_Pne = addslashes($v_linha_txt[7]);
							$v_Pis = addslashes($v_linha_txt[15]);
							$v_Est_Civil = addslashes($v_linha_txt[6]);
							$v_Cnh = addslashes($v_linha_txt[17]);
							$v_cnh_cat = addslashes($v_linha_txt[17]);
							$v_Reservista = addslashes($v_linha_txt[19]);
							$v_Rg = addslashes($v_linha_txt[12]);
							$v_Orgao_expedidor = addslashes($v_linha_txt[13]);
							$v_Tit_Eleitor = addslashes($v_linha_txt[20]);
							$v_Zona_Eleitoral = addslashes($v_linha_txt[21]);
							$v_Secao_Eleitoral = addslashes($v_linha_txt[22]);
							$v_Nome_Mae = addslashes($v_linha_txt[9]);
							$v_Nome_Pai = addslashes($v_linha_txt[10]);
							$v_cnh_vencimento = addslashes(implode('', array_reverse(explode('\'', $v_linha_txt[18]))));
							$v_ctps_num = addslashes($v_linha_txt[23]);
							$v_ctps_serie = addslashes($v_linha_txt[24]);
							$v_banco_financeiro = addslashes($v_linha_txt[25]);
							$v_agencia_financeiro = addslashes($v_linha_txt[26]);
							$v_conta_financeito = addslashes($v_linha_txt[29]);
							$v_dt_admissao = addslashes(implode('', array_reverse(explode('\'', $v_linha_txt[35]))));
							$v_matricula = addslashes($v_linha_txt[1]);
							$v_tipo_contrato = addslashes($v_linha_txt[36]);
							$v_duracao_contrato = addslashes($v_linha_txt[37]);
							$v_cep = addslashes($v_linha_txt[39]);
							$v_logradouro = addslashes($v_linha_txt[40]);
							$v_numero_end = addslashes($v_linha_txt[42]);
							$v_complemento = addslashes($v_linha_txt[41]);
							$v_bairro = addslashes($v_linha_txt[44]);
							$v_uf = addslashes($v_linha_txt[45]);
							$v_pais_end = addslashes($id_pais);
							$v_cidade = addslashes($v_linha_txt[43]);
							$v_cnh_cat = addslashes($v_linha_txt[17]);
							$v_conta_digito = addslashes($v_linha_txt[29]);
							$v_pro_contrato = addslashes($v_linha_txt[38]);
							$v_rg_expedicao = addslashes(implode('', array_reverse(explode('\'', $v_linha_txt[14]))));


							$v_cpf == '' ? $v_cpf = 0 : $v_cpf = $v_cpf;
							$v_Est_Civil == '' ? $v_Est_Civil = 1 : $v_Est_Civil = $v_Est_Civil;
							$v_cnh_vencimento == '' ? $v_cnh_vencimento = '1901-01-01' : $v_cnh_vencimento = $v_cnh_vencimento;
							$v_dt_admissao == '' ? $v_dt_admissao = '1901-01-01' : $v_dt_admissao = $v_dt_admissao;
							$v_tipo_contrato == '' ? $v_tipo_contrato = 1  : $v_tipo_contrato = $v_tipo_contrato;
							$v_dt_nasc == '' ? $v_dt_nasc = '1901-01-01' : $v_dt_nasc = $v_dt_nasc;
							$v_cidade == '' ? $v_cidade = 0 : $v_cidade = $v_cidade;
							$v_rg_expedicao == '' ? $v_rg_expedicao = '1901-01-01' : $v_rg_expedicao = $v_rg_expedicao;
							$v_agencia_financeiro == '' ? $v_agencia_financeiro = 0 : $v_agencia_financeiro = $v_agencia_financeiro;
							$v_conta_financeito == '' ? $v_conta_financeito = 0 : $v_conta_financeito = $v_conta_financeito;
							$v_pro_contrato == '' ? $v_pro_contrato = 0 : $v_pro_contrato = $v_pro_contrato;
							$v_duracao_contrato == '' ? $v_duracao_contrato = 0 : $v_duracao_contrato = $v_duracao_contrato;

							$v_sql = "UPDATE db_adm_rh.t_rh_colaborador SET			
								Nome = '{$v_nome}',
								id_Sexo = {$v_sexo},
								dt_nasc = '{$v_dt_nasc}',			
								Email_Pessoal = '{$v_email_pessoal}',
								Pais = {$v_Nacionalidade},
								cidade_nascimento = '{$v_Naturalidade}',
								Pne = '{$v_Pne}',			
								Cpf = '{$v_cpf}',
								Pis = '{$v_Pis}',
								id_Est_Civil = '{$v_Est_Civil}',
								Cnh = '{$v_Cnh}',
								Reservista = '{$v_Reservista}',
								rg = '{$v_Rg}',
								Orgao_expedidor = '{$v_Orgao_expedidor}',
								dat_expedicao = '{$v_rg_expedicao}',			
								Tit_Eleitor = '{$v_Tit_Eleitor}',
								Zona_Eleitoral = '{$v_Zona_Eleitoral}',
								Secao_Eleitoral = '{$v_Secao_Eleitoral}',
								Nome_Mae = '{$v_Nome_Mae}',
								Nome_Pai = '{$v_Nome_Pai}',
								Cnh_vencimento = '{$v_cnh_vencimento}',
								Ctps_num = '{$v_ctps_num}',
								Ctps_serie = '{$v_ctps_serie}',
								Banco = {$v_banco_financeiro},
								Agencia = '{$v_agencia_financeiro}',
								Conta_bancaria = '{$v_conta_financeito}',
								conta_digito = '{$v_conta_digito}',
								Data_admissao = '{$v_dt_admissao}',
								Matricula = '{$v_matricula}',
								Tipo_contrato = '{$v_tipo_contrato}',
								prorrogacao_contrato = '{$v_pro_contrato}',
								id_pais_nascimento = '{$v_Nacionalidade}',
								Duracao_contrato = '{$v_duracao_contrato}',			
								Cep = '{$v_cep}',
								logradouro = '{$v_logradouro}',
								Endereco_numero = '{$v_numero_end}',
								Complemento = '{$v_complemento}',
								Bairro = '{$v_bairro}',
								Estado = '{$v_uf}',
								Cidade = '{$v_cidade}',
								Cnh_categoria = '{$v_cnh_cat}',
								data_demissao = {$v_linha_txt[49]},
								id_escolaridade = {$v_linha_txt[50]},
								situacao_colaborador = {$v_linha_txt[48]}
								WHERE Cpf = '{$v_cpf}' and id_empresa = {$v_id_empresa}";

							$result = pg_query($conn, $v_sql);

							// print_r("Colaboradores OK \n");
						}
					}
				}
			}
		}


		//#####################################
		//######  IMPORTANDO HISTORICOS  ######
		//#####################################

		// foreach ($dados as $linha) {
		// 	$linha = utf8_encode($linha);
		// 	$linha = trim($linha);
		// 	$v_linha_txt = explode(';', $linha);

		// 	if ($v_linha_txt[0] == "t_rh_hist_salario") {
		// 		if (array_key_exists((int)$v_linha_txt[1], $colaborador)) {
		// 			$id =  $colaborador[(int)$v_linha_txt[1]];
		// 			$v_hist_salario .= "(" . $id . "," . $v_linha_txt[2] . "," . $v_linha_txt[3] . "," . $v_linha_txt[4] . "), ";
		// 		}
		// 	}


		// 	if ($v_linha_txt[0] == "t_rh_hist_departamento") {
		// 		if (array_key_exists((int)$v_linha_txt[1], $colaborador)) {
		// 			$id =  $colaborador[(int)$v_linha_txt[1]];
		// 			$v_hist_departamento .= "(" . $id . "," . $v_linha_txt[2] . "," . $v_linha_txt[3] . "), ";
		// 		}
		// 	}

		// 	if ($v_linha_txt[0] == "t_rh_hist_cargo") {
		// 		if (array_key_exists((int)$v_linha_txt[1], $colaborador)) {
		// 			$id =  $colaborador[(int)$v_linha_txt[1]];
		// 			$v_hist_cargo .= "(" . $id . "," . $v_linha_txt[2] . ",'" . $v_linha_txt[3] . "'), ";
		// 		}
		// 	}
		// }

		foreach ($dados as $linha) {
			$linha = utf8_encode($linha);
			$linha = trim($linha);
			$v_linha_txt = explode(';', $linha);

			if ($v_linha_txt[0] == "t_rh_hist_salario") {
				if (array_key_exists((int)$v_linha_txt[1], $colaborador)) {
					$id =  $colaborador[(int)$v_linha_txt[1]];
					$v_hist_salario .= "(" . $id . "," . $v_linha_txt[2] . "," . $v_linha_txt[3] . "," . $v_linha_txt[4] . ","  . $v_id_empresa . "," . $v_linha_txt[1] . "), ";
				}
			}


			if ($v_linha_txt[0] == "t_rh_hist_departamento") {
				if (array_key_exists((int)$v_linha_txt[1], $colaborador)) {
					$id =  $colaborador[(int)$v_linha_txt[1]];
					$v_hist_departamento .= "(" . $id . "," . $v_linha_txt[2] . "," . $v_linha_txt[3] . "," . $v_id_empresa . "," . $v_linha_txt[1] . "), ";
				}
			}

			if ($v_linha_txt[0] == "t_rh_hist_cargo") {
				if (array_key_exists((int)$v_linha_txt[1], $colaborador)) {
					$id =  $colaborador[(int)$v_linha_txt[1]];
					$v_hist_cargo .= "(" . $v_linha_txt[2] . ",'" . $v_linha_txt[3] . "'," . $v_id_empresa . "," . $v_linha_txt[1] . "), ";
				}
			}
		}

		if ($v_hist_salario && $v_hist_departamento && $v_hist_cargo   != "") {

			$v_hist_salario = " VALUES " . substr($v_hist_salario, 0, -2) . " ON CONFLICT ON CONSTRAINT t_rh_hist_salario_pk DO NOTHING" . ";";
			$v_sql5 = "INSERT INTO db_adm_rh.t_rh_hist_salario (id_colaborador, data, sequencia, salario, id_empresa, matricula)" . $v_hist_salario;
			$result5 = pg_query($conn, $v_sql5);
			// print_r("Historico de salario OK \n");

			$v_hist_departamento = " VALUES " . substr($v_hist_departamento, 0, -2) . " ON CONFLICT  DO NOTHING" . ";";
			$v_sql6 = "INSERT INTO db_adm_rh.t_rh_hist_departamento (id_colaborador, data, id_departamento, id_empresa, matricula)" . $v_hist_departamento;
			$result6 = pg_query($conn, $v_sql6);
			// print_r("Historico de departamentos OK \n");

			$v_hist_cargo = " VALUES " . substr($v_hist_cargo, 0, -2) . " ON CONFLICT DO NOTHING" . ";";
			$v_sql7 = "INSERT INTO db_adm_rh.t_rh_hist_cargo (data, id_cargo, id_empresa, matricula)" . $v_hist_cargo;
			$result7 = pg_query($conn, $v_sql7);
		}


		// $v_hist_salario = " VALUES " . substr($v_hist_salario, 0, -2) . " ON CONFLICT ON CONSTRAINT t_rh_hist_salario_pk DO NOTHING" . ";";
		// $v_sql5 = "INSERT INTO db_adm_rh.t_rh_hist_salario (id_colaborador, data, sequencia, salario)" . $v_hist_salario;
		// $result5 = pg_query($conn, $v_sql5);
		// // print_r("Historico de salario OK \n");

		// $v_hist_departamento = " VALUES " . substr($v_hist_departamento, 0, -2) . " ON CONFLICT ON CONSTRAINT t_rh_hist_departamento_pk DO NOTHING" . ";";
		// $v_sql6 = "INSERT INTO db_adm_rh.t_rh_hist_departamento (id_colaborador, data, id_departamento)" . $v_hist_departamento;
		// $result6 = pg_query($conn, $v_sql6);
		// // print_r("Historico de departamentos OK \n");

		// $v_hist_cargo = " VALUES " . substr($v_hist_cargo, 0, -2) . " ON CONFLICT ON CONSTRAINT t_rh_hist_cargo_pk DO NOTHING" . ";";
		// $v_sql7 = "INSERT INTO db_adm_rh.t_rh_hist_cargo (id_colaborador, data, id_cargo)" . $v_hist_cargo;
		// $result7 = pg_query($conn, $v_sql7);
		// print_r("Historico de cargos OK\n");


		// GRAVANDO O LOG DE IMPORTAÇÃO

		$timeZone = new DateTimeZone('America/Sao_Paulo');
		$v_data = new DateTime('now', $timeZone);
		$v_data = $v_data->format('Y-m-d H:i:s');

		$v_sql = "INSERT INTO db_adm_rh.t_log
			(id_user, data_hora, id_empresa, id_processo, descricao)
			VALUES({$_SESSION["vs_id"]}, '$v_data', {$_SESSION["vs_id_empresa"]}, 2, 'Atualização de colaboradores.')
			ON CONFLICT DO NOTHING;";

		$result = pg_query($conn, $v_sql);

		$json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Importação realizada com sucesso!." }';


		// print_r("LOG de importação OK\n\n\n");
	} else {
		$json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Você não está na empresa correta, favor verificar!"}';
	}
} else {
	$json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"O Layout do arquivo está incorreto, favor verificar!"}';
}


pg_close($conn);

$v_json = json_encode($json_msg);
echo $v_json;
