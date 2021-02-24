<?php

use PhpOffice\PhpSpreadsheet\Shared\Date;

header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0028") > 0) {

	$v_pos = strpos($_SESSION["vs_array_access"], "T0028");
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



//BUSCA CNPJ NO ARQUIVO
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

################ Busca Id Tabela Cargo

$v_sql = "SELECT id_tab_cargos
from db_adm.t_empresas
	where id = {$v_id_empresa}";

$v_id_tab_cargo = pg_fetch_array(pg_query($conn, $v_sql), 0)[0];

################ Busca Id Tabela Departamento

$v_sql = "SELECT id_tab_departamentos
from db_adm.t_empresas
	where id = {$v_id_empresa}";

$v_id_tab_departamentos = pg_fetch_array(pg_query($conn, $v_sql), 0)[0];

//#############################
//######  CRIAR USUARIO  ######
//#############################


//VERIFICA SE O LAYOUT DO ARQUIVO ESTÁ CORRETO
if ($layout_arquivo == "colaborador") {
	//VERIFICA SE ESTÁ NA EMPRESA CORRETA
	if ((int)$v_cnpj_carga == (int)$v_cnpj_emp) {

		if ($v_id_tab_cargo && $v_id_tab_departamentos != "") {

			foreach ($dados as $linha) {
				$linha = utf8_encode($linha);
				$linha = trim($linha);
				$v_linha_txt = explode(';', $linha);

				$v_chave = randString(60);
				$v_senha = geraSenha(10);

				if ($v_linha_txt[0] == "t_rh_colaborador") {

					//verifica se colaborador não está demitido na senior
					if ($v_linha_txt[48] != 7) {
						$v_cpf = intval($v_linha_txt[11]);

						$v_sql = "SELECT st_bloqueio, st_cadastro from db_adm.t_user where cpf = {$v_cpf} and st_bloqueio = 0";
						$v_sql1 = "SELECT situacao_colaborador, data_demissao from db_adm_rh.t_rh_colaborador where cpf = {$v_cpf} and id_empresa = {$v_id_empresa}";

						$situacao_usuario = pg_fetch_object(pg_query($conn, $v_sql));
						$situacao_colab = pg_fetch_object(pg_query($conn, $v_sql1));

						//caso não encontre usuário já cadastrado com determinado cpf cadastra usuário e colaborador, se encontrar faz as outras verificaçoes
						if (!$situacao_colab && !$situacao_usuario) {
							$v_user .= "('" . $v_linha_txt[2] . "','" . $v_senha . "','" . $v_linha_txt[30] . "'," . 1 . "," . 0 . "," . 0 . ",'" . $v_chave . "'," . $v_cpf . ",'" . $_SESSION["vs_db_empresa"] . "'," . $v_linha_txt[47] . "), ";
						} else {

							//verificação sobre a demissão do colaborador para criar outro usuário
							if ($situacao_usuario) {
								if (($situacao_colab->data_demissao || $situacao_colab->data_demissao != '1901-01-01') && $situacao_usuario->st_bloqueio == 1) {
									$timeZone = new DateTimeZone('America/Sao_Paulo');
									$v_data = new DateTime('now', $timeZone);
									$v_data = $v_data->format('Y-m-d');

									$v_diferenca = floor((strtotime($v_data) - strtotime($situacao_colab->data_demissao)) / (60 * 60 * 24));

									if ($v_diferenca > 0) {
										$v_user .= "('" . $v_linha_txt[2] . "','" . $v_senha . "','" . $v_linha_txt[30] . "'," . 1 . "," . 0 . "," . 0 . ",'" . $v_chave . "'," . $v_cpf . ",'" . $_SESSION["vs_db_empresa"] . "'," . $v_linha_txt[47] . "), ";
									}
								}
							}
						}
					}

					//ARRAY PARA BUSCAR SITUAÇÃO DO CADASTRO DO USUARIO
					// $v_st_cadastro = array();
					// $v_sql = "SELECT  email, st_cadastro FROM db_adm.t_user WHERE email = '" .  $v_linha_txt[30] . "' and st_cadastro = 1 ";
					// $result3 = pg_query($conn, $v_sql);

					// if (pg_num_rows($result3) == 0) {
					// 	if (strpos($v_user, $v_linha_txt[30]) == 0) {
					// 		$v_user .= "('" . $v_linha_txt[2] . "','" . $v_senha . "','" . $v_linha_txt[30] . "'," . 1 . "," . 0 . "," . 0 . ",'" . $v_chave . "'), ";
					// 	}
					// }
				}
			}

			if ($v_user != "") {
				$v_user = " VALUES " . substr($v_user, 0, -2) . ";";
				$v_sql = "INSERT INTO db_adm.t_user (nome, senha, email, st_cadastro, st_bloqueio, e_gestor, chave, cpf, db_emp, cnpj_emp) " . $v_user;
				$result = pg_query($conn, $v_sql);
			}


			//###################################################################
			//######  ARRAY PARA BUSCAR ID DO USUARIO COM O EMAIL          ######
			//###################################################################

			$usuario = array();
			$v_sql = "SELECT email, id, cpf  FROM db_adm.t_user WHERE id > 0 ";
			$result3 = pg_query($conn, $v_sql);

			while ($row3 = pg_fetch_assoc($result3)) {
				$usuario[$row3["cpf"]] = $row3["id"];
			}

			//############################################
			//######  CRIAR CADASTRO DO COLABORADOR ######
			//############################################


			foreach ($dados as $linha) {
				$linha = utf8_encode($linha);
				$linha = trim($linha);
				$v_linha_txt = explode(';', $linha);


				if ($v_linha_txt[0] == "t_rh_estado_civil") {
					$v_estado_civil .= "(" . $v_linha_txt[1] . ",'" . $v_linha_txt[2] . "'), ";
				}

				if ($v_linha_txt[0] == "t_rh_cargos") {
					$v_cargos .= "('" . $v_linha_txt[1] . "','" . $v_linha_txt[2] . "','" . $v_linha_txt[3] . "', " . $v_id_tab_cargo . " ), ";
				}

				if ($v_linha_txt[0] == "t_rh_departamentos") {
					$v_depto .= "(" . $v_linha_txt[1] . ",'" . $v_linha_txt[2] . "','" . $v_linha_txt[3] . "','" . $v_linha_txt[4] . "'," . $v_id_tab_departamentos . "), ";
				}

				if ($v_linha_txt[0] == "t_rh_escolaridade") {
					$v_escolaridade .= "(" . $v_linha_txt[1] . ",'" . $v_linha_txt[2] . "'), ";
				}

				if ($v_linha_txt[0] == "t_rh_tipo_contrato") {
					$v_tipo_contrato .= "(" . $v_linha_txt[1] . ",'" . $v_linha_txt[2] . "'), ";
				}

				if ($v_linha_txt[0] == "t_rh_colaborador") {
					$v_cpf = intval($v_linha_txt[11]);
					//ARRAY PARA BUSCAR SITUAÇÃO DO CADASTRO DO COLABORADOR
					$v_st_cadastro_col = array();

					$v_sql = "SELECT  cpf FROM db_adm_rh.t_rh_colaborador WHERE cpf = {$v_cpf} and situacao_colaborador != 7 and id_empresa = {$v_id_empresa}";
					$result3 = pg_query($conn, $v_sql);

					while ($row3 = pg_fetch_assoc($result3)) {
						$v_st_cadastro_col[$row3["cpf"]] = 7;
					}

					if (array_key_exists($v_linha_txt[11], $v_st_cadastro_col)) {
						$col_situacao =  $v_st_cadastro_col[$v_linha_txt[11]];

						// $json_msg = '{"msg_titulo":"Falha!", "msg_ev":"error", "msg":"O Colaborador' . $v_linha_txt[2] . ' já está cadastrado!." }';
					} else if ($v_linha_txt[0] == "t_rh_colaborador") { //INSERINDO COLABORADORES QUE NÃO ESTÃO CADASTRADOS

						if (array_key_exists($v_linha_txt[11], $usuario)) {
							$id_user =  $usuario[$v_linha_txt[11]];
							if (strpos($v_colaboradores, $v_linha_txt[11]) == 0) {
								$v_colaboradores .= "('" . $v_linha_txt[1] . "','" . $v_linha_txt[2] . "','" . $v_linha_txt[3] . "'," . $v_linha_txt[4] . ",'" . $v_linha_txt[5] . "'," . $v_linha_txt[6] . ",'" . $v_linha_txt[7] . "'," . $v_linha_txt[8] . ",'" . $v_linha_txt[9] . "','" . $v_linha_txt[10] . "'," . $v_linha_txt[11] . ",'" . $v_linha_txt[12] . "','" . $v_linha_txt[13] . "'," . $v_linha_txt[14] . ",'" . $v_linha_txt[15] . "','" . $v_linha_txt[16] . "','" . $v_linha_txt[17] . "'," . $v_linha_txt[18] . ",'" . $v_linha_txt[19] . "','" . $v_linha_txt[20] . "','" . $v_linha_txt[21] . "','" . $v_linha_txt[22] . "','" . $v_linha_txt[23] . "','" . $v_linha_txt[24] . "'," . $v_linha_txt[25] . "," . $v_linha_txt[26] . ",'" . $v_linha_txt[27] . "'," . $v_linha_txt[28] . "," . $v_linha_txt[29] . ",'" . $v_linha_txt[30] . "','" . $v_linha_txt[31] . "','" . $v_linha_txt[32]  . $v_linha_txt[33] . $v_linha_txt[34] . "'," . $v_linha_txt[35] . "," . $v_linha_txt[36] . "," . $v_linha_txt[37] . "," . $v_linha_txt[38] . ",'" . $v_linha_txt[39] . "','" . $v_linha_txt[40] . "','" . $v_linha_txt[41] . "','" . $v_linha_txt[42] . "','" . $v_linha_txt[43] . "','" . $v_linha_txt[44] . "','" . $v_linha_txt[45] . "'," . $v_linha_txt[46] . "," . $v_id_empresa . "," . $id_user . ",'" . $v_linha_txt[50] . "'," . $v_linha_txt[49]  . "," . $v_linha_txt[48]  . "), ";
							}
						}
					}
				}
				//INSERINDO PERMISSÃO DO USUÁRIO NA T_ACCESSES
				if ($v_linha_txt[0] == "t_rh_colaborador") {
					if (array_key_exists($v_linha_txt[11], $usuario)) {
						$id_user =  $usuario[$v_linha_txt[11]];
						$v_user_access .= "(" . $id_user . "," . $v_id_empresa . "), ";
					}
				}
			}

			if ($v_estado_civil  && $v_cargos && $v_depto && $v_escolaridade && $v_tipo_contrato && $v_colaboradores && $v_user_access  != "") {
				$v_sql000 = "INSERT INTO db_adm_rh.t_rh_sexo (id, descricao) VALUES (1, 'Masculino'), (2, 'Feminino') ON CONFLICT DO NOTHING";
				$result = pg_query($conn, $v_sql000);


				$v_estado_civil = " VALUES " . substr($v_estado_civil, 0, -2) . " ON CONFLICT DO NOTHING" . ";";
				$v_sql00 = "INSERT INTO db_adm_rh.t_rh_estado_civil (id, estado_civil)" . $v_estado_civil;
				$result = pg_query($conn, $v_sql00);


				$v_cargos = " VALUES " . substr($v_cargos, 0, -2) . " ON CONFLICT DO NOTHING" . ";";
				$v_sql1 = "INSERT INTO db_adm_rh.t_rh_cargos (id, nome, cbo, id_tabela)" . $v_cargos;
				$result1 = pg_query($conn, $v_sql1);


				$v_depto = " VALUES " . substr($v_depto, 0, -2) . " ON CONFLICT DO NOTHING" . ";";
				$v_sql2 = "INSERT INTO db_adm_rh.t_rh_departamentos (id, nome, descricao, cod_local, id_tabela)" . $v_depto;
				$result2 = pg_query($conn, $v_sql2);


				$v_escolaridade = " VALUES " . substr($v_escolaridade, 0, -2) . " ON CONFLICT DO NOTHING" . ";";
				$v_sql3 = "INSERT INTO db_adm_rh.t_rh_escolaridade (id, escolaridade)" . $v_escolaridade;
				$result3 = pg_query($conn, $v_sql3);


				$v_tipo_contrato = " VALUES " . substr($v_tipo_contrato, 0, -2) . " ON CONFLICT  DO NOTHING" . ";";
				$v_sql4 = "INSERT INTO db_adm_rh.t_rh_tipo_contrato (id, tipo_contrato)" . $v_tipo_contrato;
				$result4 = pg_query($conn, $v_sql4);


				$v_colaboradores = " VALUES " . substr($v_colaboradores, 0, -2) . " ON CONFLICT DO NOTHING" . ";";
				$v_sql = "INSERT INTO db_adm_rh.t_rh_colaborador (matricula, nome, id_sexo,
				id_pais_nascimento, cidade_nascimento, id_est_civil, pne, dt_nasc, nome_mae, nome_pai, cpf, rg, 
				orgao_expedidor, dat_expedicao, pis, cnh, cnh_categoria, cnh_vencimento, reservista, tit_eleitor, 
				zona_eleitoral, secao_eleitoral, ctps_num, ctps_serie,banco, agencia, tipo_conta, conta_bancaria,
				conta_digito, email, email_pessoal, celular, data_admissao, tipo_contrato, duracao_contrato,
				prorrogacao_contrato, cep,logradouro, complemento, endereco_numero,
				cidade, bairro, estado, pais, id_empresa, id_usuario, id_escolaridade, data_demissao, situacao_colaborador)" . $v_colaboradores;
				$result2 = pg_query($conn, $v_sql);



				$v_user_access = " VALUES " . substr($v_user_access, 0, -2) .  ";";
				$v_sql001 = "INSERT INTO db_adm.t_empresas_access (id_user, id_empresa)" . $v_user_access;
				$result001 = pg_query($conn, $v_sql001);
			} else {
			}

			//###################################################################
			//######  ARRAY PARA BUSCAR ID DO COLABORADOR COM A MATRICULA  ######
			//###################################################################

			$colaborador = array();
			$v_sql = "SELECT matricula, id  FROM db_adm_rh.t_rh_colaborador WHERE id_empresa = {$_SESSION["vs_id_empresa"]} ";
			$result2 = pg_query($conn, $v_sql);

			while ($row2 = pg_fetch_assoc($result2)) {
				$colaborador[(int)$row2["matricula"]] = (int)$row2["id"];
			}

			//#####################################
			//######  IMPORTANDO HISTORICOS  ######
			//#####################################



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
						$v_hist_departamento .= "(" . $id . "," . $v_linha_txt[2] . "," . $v_linha_txt[3] . "," . $v_id_empresa . "," . $v_linha_txt[1] . "," . $v_id_tab_departamentos . "), ";
					}
				}

				if ($v_linha_txt[0] == "t_rh_hist_cargo") {
					if (array_key_exists((int)$v_linha_txt[1], $colaborador)) {
						$id =  $colaborador[(int)$v_linha_txt[1]];
						$v_hist_cargo .= "(" . $v_linha_txt[2] . ",'" . $v_linha_txt[3] . "'," . $v_id_empresa . "," . $v_linha_txt[1] . "," . $v_id_tab_cargo  . "), ";
					}
				}
			}

			if ($v_hist_salario && $v_hist_departamento && $v_hist_cargo   != "") {

				$v_hist_salario = " VALUES " . substr($v_hist_salario, 0, -2) . " ON CONFLICT ON CONSTRAINT t_rh_hist_salario_pk DO NOTHING" . ";";
				$v_sql5 = "INSERT INTO db_adm_rh.t_rh_hist_salario (id_colaborador, data, sequencia, salario, id_empresa, matricula)" . $v_hist_salario;
				$result5 = pg_query($conn, $v_sql5);


				$v_hist_departamento = " VALUES " . substr($v_hist_departamento, 0, -2) . " ON CONFLICT  DO NOTHING" . ";";
				$v_sql6 = "INSERT INTO db_adm_rh.t_rh_hist_departamento (id_colaborador, data, id_departamento, id_empresa, matricula, id_tabela)" . $v_hist_departamento;
				$result6 = pg_query($conn, $v_sql6);


				$v_hist_cargo = " VALUES " . substr($v_hist_cargo, 0, -2) . " ON CONFLICT DO NOTHING" . ";";
				$v_sql7 = "INSERT INTO db_adm_rh.t_rh_hist_cargo (data, id_cargo, id_empresa, matricula, id_tabela)" . $v_hist_cargo;
				$result7 = pg_query($conn, $v_sql7);



				// GRAVANDO O LOG DE IMPORTAÇÃO


				$timeZone = new DateTimeZone('America/Sao_Paulo');
				$v_data = new DateTime('now', $timeZone);
				$v_data = $v_data->format('Y-m-d H:i:s');

				$v_sql = "INSERT INTO db_adm_rh.t_log
							(id_user, data_hora, id_empresa, id_processo, descricao)
							VALUES({$_SESSION["vs_id"]}, '$v_data', {$_SESSION["vs_id_empresa"]}, 1, 'Importação de colaboradores.')
							ON CONFLICT DO NOTHING;";
				$result = pg_query($conn, $v_sql);

				$json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Importação realizada com sucesso!." }';
			} else {
				$json_msg = '{"msg_titulo":"ERRO!", "msg_ev":"error", "msg":"Upload dos históricos deu errado!." }';
			}
		} else {
			$json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Favor verificar o ID da tabela de cargos e departamentos no cadastro da empresa!"}';
		}
	} else {
		$json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Você não está na empresa correta, favor verificar!"}';
	}
} else {
	$json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"O Layout do arquivo está incorreto, favor verificar!"}';
}

//Essa função gera um valor de String aleatório do tamanho recebendo por parametros
function randString($size)
{
	//String com valor possíveis do resultado, os caracteres pode ser adicionado ou retirados conforme sua necessidade
	$basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

	$return = "";

	for ($count = 0; $size > $count; $count++) {
		//Gera um caracter aleatorio
		$return .= $basic[rand(0, strlen($basic) - 1)];
	}

	return $return;
}


function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
	$lmin = 'abcdefghijklmnopqrstuvwxyz';
	$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$num = '1234567890';
	$simb = '!@#$%*-';
	$retorno = '';
	$caracteres = '';

	$caracteres .= $lmin;
	if ($maiusculas) $caracteres .= $lmai;
	if ($numeros) $caracteres .= $num;
	if ($simbolos) $caracteres .= $simb;

	$len = strlen($caracteres);
	for ($n = 1; $n <= $tamanho; $n++) {
		$rand = mt_rand(1, $len);
		$retorno .= $caracteres[$rand - 1];
	}
	return $retorno;
}

pg_close($conn);

$v_json = json_encode($json_msg);
echo $v_json;
