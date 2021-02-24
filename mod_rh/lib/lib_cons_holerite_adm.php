<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
$vs_id = str_replace(".", "", $_SESSION["vs_id"]);

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);

// GERANDO LISTA DE RECIBOS
if ($v_acao == "LISTAR_RECIBOS") {

	$v_ano = addslashes($_POST["v_ano"]);

	if (strpos($_SESSION["vs_array_access"], "T0018") > 0) {

		$v_pos = strpos($_SESSION["vs_array_access"], "T0018");
		$v_hol_adm_ler = substr($_SESSION["vs_array_access"], $v_pos + 8, 1);
		$v_hol_adm_criar = substr($_SESSION["vs_array_access"], $v_pos + 12, 1);
		$v_hol_adm_gravar = substr($_SESSION["vs_array_access"], $v_pos + 16, 1);
		$v_hol_adm_excluir = substr($_SESSION["vs_array_access"], $v_pos + 20, 1);
		// var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

	}

	// GERANDO A LISTA
// $v_sql = "SELECT bases.data_autenticacao,hol.competencia Competencia, hol.id_empresa Id_empresa ,hol.matricula Matricula,col.nome Nome, tipFol.tipo_folha Tipo_folha, tipFol.id Tipo, 
//                  sum(hol.valor) Provento, 0 Liquido, 
//                  case when v_hol.descontos is null then 0 else v_hol.descontos end Desconto
//             from db_adm_rh.t_rh_holerite hol  
//                  join db_adm_rh.t_rh_holerite_rubricas  as rub 
//                    on hol.id_tabela_rubrica = rub.id_tabela 
//                   and hol.rubrica = rub.rubrica 
//                   and rub.tipo = 1
//                  JOIN db_adm_rh.t_rh_holerite_bases as bases 
//                    ON hol.competencia = bases.competencia 
//                   and hol.matricula = bases.matricula 
//                   and hol.tipo_folha = bases.tipo_folha 
//                   and hol.id_empresa = bases.id_empresa 
//                  JOIN db_adm_rh.t_rh_holerite_tipo_folha AS tipFol 
//                    ON hol.tipo_Folha = tipFol.Id	
//                  join (select vhol.competencia, vhol.id_empresa,vhol.matricula,vhol.tipo_folha,sum(vhol.valor) descontos
// 				          from db_adm_rh.t_rh_holerite vhol  
// 					           join db_adm_rh.t_rh_holerite_rubricas  as vrub 
// 						         on vhol.id_tabela_rubrica = vrub.id_tabela 
//    						        and vhol.rubrica = vrub.rubrica 
// 						        and vrub.tipo = 2
// 				        group by vhol.competencia, vhol.id_empresa,vhol.matricula,vhol.tipo_folha) 
// 			        as v_hol      
// 			        on v_hol.competencia = hol.competencia
// 			       and v_hol.matricula = hol.matricula
// 			       and v_hol.id_empresa = hol.id_empresa
// 			       and v_hol.tipo_folha = hol.tipo_folha  			               
// 			   JOIN db_adm_rh.t_rh_colaborador as col 
// 		         ON col.matricula = hol.matricula 
// 	            and col.id_empresa = hol.id_empresa                    
//             where hol.competencia = (select max(hol2.competencia) 
// 			     			          from db_adm_rh.t_rh_holerite as hol2 
// 					                 where hol2.id_empresa = hol.id_empresa 
// 					                   and hol2.matricula = hol.matricula 
// 					                   and hol2.tipo_folha = hol.tipo_folha
// 					                   and hol2.competencia < '{$v_ano}-12-31' 
// 					                   and hol2.competencia > '{$v_ano}-01-01') 
//                and hol.id_empresa = {$_SESSION["vs_id_empresa"]}
// 			group by bases.data_autenticacao,hol.id_empresa,hol.competencia,tipFol.tipo_folha,tipFol.id,hol.matricula,col.nome,v_hol.descontos
//             order by hol.id_empresa,hol.competencia,tipFol.tipo_folha,hol.matricula"; 

$v_sql = "SELECT bases.data_autenticacao,hol.competencia Competencia, hol.id_empresa Id_empresa ,hol.matricula Matricula,col.nome Nome, tipFol.tipo_folha Tipo_folha, tipFol.id Tipo, 
                 sum(hol.valor) Provento, 0 Liquido, 
                 case when v_hol.descontos is null then 0 else v_hol.descontos end Desconto
            from db_adm_rh.t_rh_holerite hol  
                 join db_adm_rh.t_rh_holerite_rubricas  as rub 
                   on hol.id_tabela_rubrica = rub.id_tabela 
                  and hol.rubrica = rub.rubrica 
                  and rub.tipo = 1
                 JOIN db_adm_rh.t_rh_holerite_bases as bases 
                   ON hol.competencia = bases.competencia 
                  and hol.matricula = bases.matricula 
                  and hol.tipo_folha = bases.tipo_folha 
                  and hol.id_empresa = bases.id_empresa 
                 JOIN db_adm_rh.t_rh_holerite_tipo_folha AS tipFol 
                   ON hol.tipo_Folha = tipFol.Id	
                 join (select vhol.competencia, vhol.id_empresa,vhol.matricula,vhol.tipo_folha,sum(vhol.valor) descontos
				          from db_adm_rh.t_rh_holerite vhol  
					           join db_adm_rh.t_rh_holerite_rubricas  as vrub 
						         on vhol.id_tabela_rubrica = vrub.id_tabela 
   						        and vhol.rubrica = vrub.rubrica 
						        and vrub.tipo = 2
				        group by vhol.competencia, vhol.id_empresa,vhol.matricula,vhol.tipo_folha) 
			        as v_hol      
			        on v_hol.competencia = hol.competencia
			       and v_hol.matricula = hol.matricula
			       and v_hol.id_empresa = hol.id_empresa
			       and v_hol.tipo_folha = hol.tipo_folha  			               
			   JOIN db_adm_rh.t_rh_colaborador as col 
		         ON col.matricula = hol.matricula 
	            and col.id_empresa = hol.id_empresa                    
				where hol.competencia <= (select max(hol2.competencia) 
			     			          from db_adm_rh.t_rh_holerite as hol2 
					                 where hol2.id_empresa = hol.id_empresa 
					                   and hol2.matricula = hol.matricula 
					                   and hol2.tipo_folha = hol.tipo_folha
					                   and hol2.competencia < '{$v_ano}-12-31' 
					                   and hol2.competencia >= '{$v_ano}-01-01')
			and  hol.competencia >= (select min(hol2.competencia) 
			     			          from db_adm_rh.t_rh_holerite as hol2 
					                 where hol2.id_empresa = hol.id_empresa 
					                   and hol2.matricula = hol.matricula 
					                   and hol2.tipo_folha = hol.tipo_folha
					                   and hol2.competencia < '{$v_ano}-12-31' 
					                   and hol2.competencia >= '{$v_ano}-01-01')
               and hol.id_empresa = {$_SESSION["vs_id_empresa"]}
			group by bases.data_autenticacao,hol.id_empresa,hol.competencia,tipFol.tipo_folha,tipFol.id,hol.matricula,col.nome,v_hol.descontos
            order by hol.id_empresa,hol.competencia,tipFol.tipo_folha,hol.matricula"; 

	//var_dump($v_sql);	 
	//die;

	$result = pg_query($conn, $v_sql);

	$v_sql1 = "SELECT colab.nome, colab.id_usuario, colab.matricula 
	             from db_adm_rh.t_rh_colaborador colab 
				where colab.id_empresa =  {$_SESSION["vs_id_empresa"]}
				order by colab.nome ";

	$result1 = pg_query($conn, $v_sql1);

	$v_dados_colab = array();
	while ($row = pg_fetch_assoc($result1)) {
		$v_dados_colab[] = array(
			"Id" => $row["id_usuario"],
			"Matricula" => $row["matricula"],
			"Nome" => $row["nome"]
		);
	}

	$v_dados_hol = array();
	while ($row = pg_fetch_assoc($result)) {
		$v_dados_hol[] = array(
			"Matricula" => $row["matricula"],
			"Nome" => $row["nome"],
			"Competencia" => $row["competencia"],
			"Tipo_folha" => $row["tipo_folha"],
			"Tipo" => $row["tipo"],
			"Provento" => $row["provento"],
			"Desconto" => $row["desconto"],
			"Liquido" => $row["liquido"],
			"Data_autenticacao" => $row["data_autenticacao"]
		);
	}

	$v_dados = array();
	$v_dados["holerites"] = $v_dados_hol;
	$v_dados["colaboradores"] = $v_dados_colab;

	// ENVIANDO DADOS
	pg_close($conn);
	$v_json = json_encode($v_dados);
	echo $v_json;
}

// SELECIONANDO REGISTRO
if ($v_acao == "EV_CARREGA_HOLERITE") {

	$v_competencia = addslashes($_POST["v_competencia"]);
	$v_tipo = addslashes($_POST["v_tipo"]);
	$v_id = addslashes($_POST["v_id"]);
	$v_matricula = addslashes($_POST["v_matricula"]);

	
	// SELECIONANDO DADOS DA EMPRESA
	$v_sql = "SELECT id Cod_empresa,Nome Nome_empresa,Cnpj, url_arquivo " .
		"FROM db_adm.t_empresas " .
		"WHERE Id =" . $_SESSION["vs_id_empresa"];

	$result = pg_query($conn, $v_sql);

	$v_dados = array();

	if ($row = pg_fetch_assoc($result)) {
		$v_dados[] = array(
			"Cod_empresa" => $row["cod_empresa"],
			"Nome_empresa" => $row["nome_empresa"],
			"Cnpj" => $row["cnpj"],
			"logo" => $row["url_arquivo"]
		);
	}

	// SELECIONANDO DADOS DO COLABORADOR  
	$v_sql = "SELECT  colab.matricula Matricula, colab.Cpf Cpf, colab.Nome Nome, colab.Data_admissao Admissao, colab.pis, cargo.cbo, cargo.Nome Cargo, dep.Nome Departamento 
			 FROM db_adm_rh.t_rh_colaborador colab 
			 JOIN db_adm.t_empresas as emp ON emp.id = colab.id_empresa
			 JOIN db_adm_rh.t_rh_tabela_departamento as tabDep ON tabDep.id  = emp.id_tab_departamentos       
			 left JOIN db_adm_rh.t_rh_departamentos dep 
			   ON dep.id_tabela  = tabDep.id 
			  AND dep.Id = (select hist_dep.id_departamento 
							  from db_adm_rh.t_rh_hist_departamento hist_dep
							 where hist_dep.matricula = colab.matricula 
							   and hist_dep.id_empresa = colab.id_empresa
							   and hist_dep.data = (select MAX(hist_dep2.data) 
													  from db_adm_rh.t_rh_hist_departamento hist_dep2 
													 where hist_dep2.matricula = hist_dep.matricula 
													   and hist_dep2.id_empresa = hist_dep.id_empresa 
													   and hist_dep2.data <= '{$v_competencia}'))  
			 JOIN db_adm_rh.t_rh_tabela_cargo as tabCar ON tabCar.id  = emp.id_tab_cargos  
			 left JOIN db_adm_rh.t_rh_cargos cargo 
			   ON cargo.id_tabela  = tabCar.id       
			  AND cargo.id = (select hist_cargo.id_cargo 
								from db_adm_rh.t_rh_hist_cargo hist_cargo
								where hist_cargo.matricula = colab.matricula 
								  and hist_cargo.id_empresa = colab.id_empresa
								  and hist_cargo.data = (select MAX(hist_cargo2.data) 
														   from db_adm_rh.t_rh_hist_cargo hist_cargo2 
														  where hist_cargo2.matricula = hist_cargo.matricula 
														    and hist_cargo2.id_empresa = hist_cargo.id_empresa 
															and hist_cargo2.data <= '{$v_competencia}'))
			 WHERE colab.matricula = '{$v_matricula}'
			   and colab.id_empresa = {$_SESSION["vs_id_empresa"]}
			 ORDER BY id_empresa, Matricula asc OFFSET 0 LIMIT 50";

	if ($result2 = pg_query($conn, $v_sql)) {
		$row = pg_fetch_assoc($result2);
		$v_dados[] = array(
			//"Id" => $row["id"],
			"Matricula" => $row["matricula"],
			"Nome" => $row["nome"],
			"Cargo" => $row["cargo"],
			"Departamento" => $row["departamento"],
			"Admissao" => $row["admissao"],
			"Cpf" => $row["cpf"],
			"Cbo" => $row["cbo"],
			"Pis" => $row["pis"]
		);
	}

	$v_sql = "SELECT bases.dependentes_ir, bases.dependentes_sf, bases.Competencia competencia,Salario_base, data_pagamento, Base_inss, Base_irrf, Base_fgts,Valor_fgts Fgts_mes, Total_vencimentos, Total_descontos,Total_liquido, ip_autenticacao, data_autenticacao, usuario_autenticacao
		FROM db_adm_rh.t_rh_holerite_bases bases
		   inner join db_adm_rh.t_rh_holerite  as hol
		           on bases.matricula = hol.matricula
		          and bases.competencia = hol.competencia
		          and bases.tipo_folha  = hol.tipo_folha
		          and bases.id_empresa = hol.id_empresa 
		    inner join db_adm_rh.t_rh_colaborador  as colab
		            on colab.id = hol.id_colaborador
		WHERE bases.Competencia =  '{$v_competencia}'
		 AND bases.Tipo_folha = {$v_tipo}
		 and bases.id_empresa = {$_SESSION["vs_id_empresa"]}
		 and bases.matricula = '{$v_matricula}'";

	if ($result3 = pg_query($conn, $v_sql)) {
		$row = pg_fetch_assoc($result3);
		$v_dados[] = array(
			"Competencia" => $row["competencia"],
			"Salario_base" => $row["salario_base"],
			"Base_inss" => $row["base_inss"],
			"Base_irrf" => $row["base_irrf"],
			"Base_fgts" => $row["base_fgts"],
			"Fgts_mes" => $row["fgts_mes"],
			"Total_vencimentos" => $row["total_vencimentos"],
			"Total_descontos" => $row["total_descontos"],
			"Total_liquido" => $row["total_liquido"],
			"Ip_autenticacao" => $row["ip_autenticacao"],
			"Data_autenticacao" => $row["data_autenticacao"],
			"Data_pagamento" => $row["data_pagamento"],
			"Usuario_autenticacao" => $row["usuario_autenticacao"],
			"Dependentes_ir" => $row["dependentes_ir"],
			"Dependentes_sf" => $row["dependentes_sf"]
		);
	}

	// SELECIONANDO VERBAS DO COLABORADOR - VENCIMENTOS
	$v_sql = "SELECT hol.Competencia Competencia, tipFol.Tipo_folha Tipo_folha,hol.Rubrica Rubrica, tipRub.descricao Tipo_Rubrica, 
				  rub.descricao Descricao_rubrica,hol.referencia Referencia,hol.valor Vencimentos, 0 Descontos 
		  FROM db_adm_rh.t_rh_holerite hol 
			  inner join db_adm_rh.t_rh_colaborador as colab 
					  on colab.matricula = hol.matricula 
					  and colab.id_empresa = hol.id_empresa
			  inner JOIN db_adm.t_empresas as emp ON emp.id = colab.id_empresa      
			  inner JOIN db_adm_rh.t_rh_holerite_rubricas_tabela as tabRub 
					  ON tabRub.id  = emp.id_tab_rubricas  
			  inner JOIN db_adm_rh.t_rh_holerite_rubricas AS rub 
					  ON rub.id_tabela = tabRub.id 
					 and rub.rubrica = hol.rubrica     
			  INNER JOIN db_adm_rh.t_rh_holerite_tipo_folha AS tipFol ON hol.tipo_Folha = tipFol.Id 
			  INNER JOIN db_adm_rh.t_rh_holerite_tipo_rubrica AS tipRub ON rub.tipo = tipRub.Id  
		 WHERE rub.tipo in(1) 
		   AND colab.matricula = '{$v_matricula}' 
		   AND hol.competencia = '{$v_competencia}'
		   AND hol.Tipo_folha = {$v_tipo}
		   and hol.id_empresa = {$_SESSION["vs_id_empresa"]}";
   		   

	$result4 = pg_query($conn, $v_sql);

	while ($row = pg_fetch_assoc($result4)) {
		$v_dados[] = array("Tipo_folha" => $row["tipo_folha"], "Rubrica" => $row["rubrica"], "Tipo_Rubrica" => $row["tipo_rubrica"], "Descricao_rubrica" => $row["descricao_rubrica"], "Referencia" => $row["referencia"], "Vencimentos" => $row["vencimentos"], "Descontos" => $row["descontos"]);
	}

	// SELECIONANDO VERBAS DO COLABORADOR - DESCONTOS
	$v_sql = "SELECT hol.Competencia Competencia, tipFol.Tipo_folha Tipo_folha,hol.Rubrica Rubrica, tipRub.descricao Tipo_Rubrica, 
				rub.descricao Descricao_rubrica,hol.referencia Referencia,0 Vencimentos, hol.valor Descontos 
		FROM db_adm_rh.t_rh_holerite hol 
			inner join db_adm_rh.t_rh_colaborador as colab 
					on colab.matricula = hol.matricula 
					and colab.id_empresa = hol.id_empresa
			inner JOIN db_adm.t_empresas as emp ON emp.id = colab.id_empresa      
			inner JOIN db_adm_rh.t_rh_holerite_rubricas_tabela as tabRub 
					ON tabRub.id  = emp.id_tab_rubricas  
			inner JOIN db_adm_rh.t_rh_holerite_rubricas AS rub 
					ON rub.id_tabela = tabRub.id 
				   and rub.rubrica = hol.rubrica     
			INNER JOIN db_adm_rh.t_rh_holerite_tipo_folha AS tipFol ON hol.tipo_Folha = tipFol.Id 
			INNER JOIN db_adm_rh.t_rh_holerite_tipo_rubrica AS tipRub ON rub.tipo = tipRub.Id  
	   WHERE rub.tipo in(2,3) 
		 AND colab.matricula = '{$v_matricula}' 
		 AND hol.competencia = '{$v_competencia}'
		 AND hol.Tipo_folha = {$v_tipo}
		 and hol.id_empresa = {$_SESSION["vs_id_empresa"]}";

	//var_dump($v_sql);            
	$result5 = pg_query($conn, $v_sql);

	while ($row = pg_fetch_assoc($result5)) {
		$v_dados[] = array("Tipo_folha" => $row["tipo_folha"], "Rubrica" => $row["rubrica"], "Tipo_Rubrica" => $row["tipo_rubrica"], "Descricao_rubrica" => $row["descricao_rubrica"], "Referencia" => $row["referencia"], "Vencimentos" => $row["vencimentos"], "Descontos" => $row["descontos"]);
	}

	// ENVIANDO DADOS
	pg_close($conn);
	$v_json = json_encode($v_dados);
	echo $v_json;
}

if ($v_acao == 'FILTRAR_COLAB') {

	$v_ano = addslashes($_POST["v_ano"]);
	$v_id_colab = addslashes($_POST["v_id_colab"]);
	
	//var_dump($v_ano);

	if ($v_id_colab == '0') {
$v_sql = "SELECT bases.data_autenticacao,hol.competencia Competencia, hol.id_empresa Id_empresa ,hol.matricula Matricula,col.nome Nome, tipFol.tipo_folha Tipo_folha, tipFol.id Tipo, 
                 sum(hol.valor) Provento, 0 Liquido, 
                 case when v_hol.descontos is null then 0 else v_hol.descontos end Desconto
            from db_adm_rh.t_rh_holerite hol  
                 join db_adm_rh.t_rh_holerite_rubricas  as rub 
                   on hol.id_tabela_rubrica = rub.id_tabela 
                  and hol.rubrica = rub.rubrica 
                  and rub.tipo = 1
                 JOIN db_adm_rh.t_rh_holerite_bases as bases 
                   ON hol.competencia = bases.competencia 
                  and hol.matricula = bases.matricula 
                  and hol.tipo_folha = bases.tipo_folha 
                  and hol.id_empresa = bases.id_empresa 
                 JOIN db_adm_rh.t_rh_holerite_tipo_folha AS tipFol 
                   ON hol.tipo_Folha = tipFol.Id	
                 join (select vhol.competencia, vhol.id_empresa,vhol.matricula,vhol.tipo_folha,sum(vhol.valor) descontos
				          from db_adm_rh.t_rh_holerite vhol  
					           join db_adm_rh.t_rh_holerite_rubricas  as vrub 
						         on vhol.id_tabela_rubrica = vrub.id_tabela 
   						        and vhol.rubrica = vrub.rubrica 
						        and vrub.tipo = 2
				        group by vhol.competencia, vhol.id_empresa,vhol.matricula,vhol.tipo_folha) 
			        as v_hol      
			        on v_hol.competencia = hol.competencia
			       and v_hol.matricula = hol.matricula
			       and v_hol.id_empresa = hol.id_empresa
			       and v_hol.tipo_folha = hol.tipo_folha  			               
			   JOIN db_adm_rh.t_rh_colaborador as col 
		         ON col.matricula = hol.matricula 
	            and col.id_empresa = hol.id_empresa                    
				where hol.competencia <= (select max(hol2.competencia) 
			     			          from db_adm_rh.t_rh_holerite as hol2 
					                 where hol2.id_empresa = hol.id_empresa 
					                   and hol2.matricula = hol.matricula 
					                   and hol2.tipo_folha = hol.tipo_folha
					                   and hol2.competencia < '{$v_ano}-12-31' 
					                   and hol2.competencia >= '{$v_ano}-01-01')
			and  hol.competencia >= (select min(hol2.competencia) 
			     			          from db_adm_rh.t_rh_holerite as hol2 
					                 where hol2.id_empresa = hol.id_empresa 
					                   and hol2.matricula = hol.matricula 
					                   and hol2.tipo_folha = hol.tipo_folha
					                   and hol2.competencia < '{$v_ano}-12-31' 
					                   and hol2.competencia >= '{$v_ano}-01-01')
               and hol.id_empresa = {$_SESSION["vs_id_empresa"]}
			group by bases.data_autenticacao,hol.id_empresa,hol.competencia,tipFol.tipo_folha,tipFol.id,hol.matricula,col.nome,v_hol.descontos
            order by hol.id_empresa,hol.competencia,tipFol.tipo_folha,hol.matricula"; 
	} else {

		$v_sql = "SELECT col.matricula matricula 
		from db_adm_rh.t_rh_colaborador col
		where col.id_usuario = {$v_id_colab}";
		$result10 = pg_query($conn, $v_sql);
		$v_dados = array();
		if ($row = pg_fetch_assoc($result10)) {
			$v_dados[] = array(
				"matricula" => $row["matricula"]
			);
		}
		$v_matricula = $row["matricula"];

$v_sql = "SELECT bases.data_autenticacao,hol.competencia Competencia, hol.id_empresa Id_empresa ,hol.matricula Matricula,col.nome Nome, tipFol.tipo_folha Tipo_folha, tipFol.id Tipo, 
                 sum(hol.valor) Provento, 0 Liquido, 
                 case when v_hol.descontos is null then 0 else v_hol.descontos end Desconto
            from db_adm_rh.t_rh_holerite hol  
                 join db_adm_rh.t_rh_holerite_rubricas  as rub 
                   on hol.id_tabela_rubrica = rub.id_tabela 
                  and hol.rubrica = rub.rubrica 
                  and rub.tipo = 1
                 JOIN db_adm_rh.t_rh_holerite_bases as bases 
                   ON hol.competencia = bases.competencia 
                  and hol.matricula = bases.matricula 
                  and hol.tipo_folha = bases.tipo_folha 
                  and hol.id_empresa = bases.id_empresa 
                 JOIN db_adm_rh.t_rh_holerite_tipo_folha AS tipFol 
                   ON hol.tipo_Folha = tipFol.Id	
                 join (select vhol.competencia, vhol.id_empresa,vhol.matricula,vhol.tipo_folha,sum(vhol.valor) descontos
				          from db_adm_rh.t_rh_holerite vhol  
					           join db_adm_rh.t_rh_holerite_rubricas  as vrub 
						         on vhol.id_tabela_rubrica = vrub.id_tabela 
   						        and vhol.rubrica = vrub.rubrica 
						        and vrub.tipo = 2
				        group by vhol.competencia, vhol.id_empresa,vhol.matricula,vhol.tipo_folha) 
			        as v_hol      
			        on v_hol.competencia = hol.competencia
			       and v_hol.matricula = hol.matricula
			       and v_hol.id_empresa = hol.id_empresa
			       and v_hol.tipo_folha = hol.tipo_folha  			               
			   JOIN db_adm_rh.t_rh_colaborador as col 
		         ON col.matricula = hol.matricula 
	            and col.id_empresa = hol.id_empresa                    
            where hol.competencia <= (select max(hol2.competencia) 
			     			          from db_adm_rh.t_rh_holerite as hol2 
					                 where hol2.id_empresa = hol.id_empresa 
					                   and hol2.matricula = hol.matricula 
					                   and hol2.tipo_folha = hol.tipo_folha
					                   and hol2.competencia < '{$v_ano}-12-31' 
					                   and hol2.competencia >= '{$v_ano}-01-01')
			and  hol.competencia >= (select min(hol2.competencia) 
			     			          from db_adm_rh.t_rh_holerite as hol2 
					                 where hol2.id_empresa = hol.id_empresa 
					                   and hol2.matricula = hol.matricula 
					                   and hol2.tipo_folha = hol.tipo_folha
					                   and hol2.competencia < '{$v_ano}-12-31' 
					                   and hol2.competencia >= '{$v_ano}-01-01')
               and hol.id_empresa = {$_SESSION["vs_id_empresa"]}
			   and col.matricula = '{$v_matricula}' 			   
			group by bases.data_autenticacao,hol.id_empresa,hol.competencia,tipFol.tipo_folha,tipFol.id,hol.matricula,col.nome,v_hol.descontos
            order by hol.id_empresa,hol.competencia,tipFol.tipo_folha,hol.matricula"; 

	}

	$result = pg_query($conn, $v_sql);

	$v_dados = array();
	while ($row = pg_fetch_assoc($result)) {
		$v_dados[] = array(
			"Data_autenticacao" => $row["data_autenticacao"],
			"Competencia" => $row["competencia"],
			"Nome" => $row["nome"],
			"Tipo" => $row["tipo"],
			"Tipo_folha" => $row["tipo_folha"],
			"Provento" => $row["provento"],
			"Desconto" => $row["desconto"],
			"Liquido" => "0",
			"Matricula" => $row["matricula"],
			"Id_usuario" => $vs_id //,
			//	"Id" => $row["id"]
		);
	}

	// ENVIANDO DADOS
	pg_close($conn);
	$v_json = json_encode($v_dados);
	echo $v_json;
}
