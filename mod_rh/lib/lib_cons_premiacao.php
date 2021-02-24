<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

$v_acao = addslashes($_POST["v_acao"]);

if($v_acao == 'BUSCA_POR_INDICADOR'){

	$v_colaborador = addslashes($_POST["v_colaborador"]);
	$v_indicador = addslashes($_POST["v_indicador"]);

	$v_sql = "select * from db_adm.t_indicador_usuario20211 tiu 
	where tiu.id_usuario = {$v_colaborador} and tiu.id_indicador = {$v_indicador} order by competencia";

	$result = pg_query($conn, $v_sql);

	$v_dados = array();
	while($row = pg_fetch_assoc($result)){
		$v_dados[] = array(
			"competencia" => $row["competencia"],
			"porcentagem" => $row["porcentagem"],
			"tarefas_atrasadas" => $row["tarefas_atrasadas"],
			"tarefas_concluidas" => $row["tarefas_concluidas"]
		);
	}

	// ENVIANDO DADOS
	pg_close($conn);
	$v_json = json_encode($v_dados);
	echo $v_json;
}

if($v_acao == 'TOTAL_INDIVIDUAL'){

	$v_colaborador = addslashes($_POST["v_colaborador"]);

	$v_sql = "select tiu.id_indicador, tiu.porcentagem from db_adm.t_indicador_usuario20211 tiu
	where tiu.id_usuario = {$v_colaborador} order by tiu.id_indicador";

	$result = pg_query($conn, $v_sql);

	$v_porcentagem = array();
	while($row = pg_fetch_assoc($result)){
		$v_porcentagem[] = array(
			"indicador" => $row["id_indicador"],
			"porcentagem" => $row["porcentagem"]
		);
	}

	$v_sql = "select * from db_adm.t_indicadores_premiacao20211 tip where id in (
		select tiu.id_indicador from db_adm.t_indicador_usuario20211 tiu where id_usuario = {$v_colaborador}
	) order by id";

	$result = pg_query($conn, $v_sql);

	$v_indicadores = array();
	while($row = pg_fetch_assoc($result)){
		$v_indicadores[] = $row["descricao"];
	}

	$filtro_empresa = "and colab.id_empresa = {$_SESSION["vs_id_empresa"]}";
	if($_SESSION["vs_id"] == 170){
		$filtro_empresa = "";
	}
	
	$v_sql = "select * from db_adm_rh.t_rh_colaborador colab
	JOIN db_adm_rh.t_rh_cargos cargo 
		on cargo.Id = (select hist_cargo.id_cargo 
						 from db_adm_rh.t_rh_hist_cargo hist_cargo 
						 where hist_cargo.matricula = colab.matricula  
						   and hist_cargo.id_empresa = colab.id_empresa
						   and hist_cargo.data = (select MAX(hist_cargo2.data) 
													from db_adm_rh.t_rh_hist_cargo hist_cargo2 
													where hist_cargo2.matricula = hist_cargo.matricula 
													  and hist_cargo2.id_empresa  = hist_cargo.id_empresa 
													  and hist_cargo.data <= current_date)) where colab.id_usuario = {$v_colaborador} {$filtro_empresa}";

	if($result = pg_fetch_object(pg_query($conn, $v_sql))){		
		$financeiro = 0;
		if(strpos($result->nome, "Auxiliar") !== false){
			$financeiro = 300;
		}
		if(strpos($result->nome, "Analista") !== false || strpos($result->nome, "Assistente") !== false || strpos($result->nome, "Assist") !== false){
			$financeiro = 750;
		}
		if(strpos($result->nome, "Especialista") !== false || strpos($result->nome, "Espec") !== false){
			$financeiro = 1000;
		}
		if(strpos($result->nome, "Coordenador") !== false || strpos($result->nome, "Coord") !== false){
			$financeiro = 1400;
		}
		if(strpos($result->nome, "Diretor") !== false || strpos($result->nome, "Socio Diretor") !== false || strpos($result->nome, "Ger ") !== false || strpos($result->nome, "Gerente") !== false || strpos($result->nome, "Sup") !== false || strpos($result->nome, "Supervisor") !== false){
			$financeiro = 1900;
		}
	
		$v_dados = array(
			"indicadores" => $v_indicadores,
			"porcentagens" => $v_porcentagem,
			"financeiro" => $financeiro,
			"liberacao" => true
		);
	}else if($_SESSION["vs_id"] == 170){
		$v_dados = array(
			"liberacao" => true
		);
	} else {
		$v_dados = array(
			"liberacao" => false
		);
	}

	// ENVIANDO DADOS
	pg_close($conn);
	$v_json = json_encode($v_dados);
	echo $v_json;
}

if($v_acao == 'BUSCA_DADOS_LIDERANCA'){
	
	$v_resposta = array();

	$v_sql = "select * from db_adm.t_indicadores_premiacao20211 tip where id in (
		select tiu.id_indicador from db_adm.t_indicador_usuario20211 tiu where id_usuario = {$_SESSION["vs_id"]}
	) order by id";

	$result = pg_query($conn, $v_sql);

	$v_indicadores = array();
	while($row = pg_fetch_assoc($result)){
		$v_indicadores[] = array(
			"id_indicador" => $row["id"],
			"descricao" => $row["descricao"]
		);
	}

	$v_sql = "select nome from db_adm.t_user where id = {$_SESSION["vs_id"]}";

	$nome = pg_fetch_result(pg_query($conn, $v_sql), 'nome');

	$v_sql = "select id_lider from db_adm.t_rh_funcao_gh where id_usuario = {$_SESSION["vs_id"]}";

	$result = pg_query($conn, $v_sql);
	
	if($id_lider = pg_fetch_result($result, 'id_lider') || $_SESSION["vs_id"] == 170){

		$v_sql = "select id from db_adm.t_rh_funcao_gh where id_usuario = {$_SESSION["vs_id"]}";

		$result = pg_query($conn, $v_sql);

		$id_lideranca = pg_fetch_result($result, 'id');

		if($_SESSION["vs_id"] == 170){
			$id_lideranca = 6;
		}

		$v_sql = "WITH RECURSIVE arvore AS
		(
		  select
			  func.data_finalizacao,
			  func.nome nome_func,
			func.id,
			func.nome,
			func.id_lider,
			func.id_usuario,
			CAST(func.nome AS TEXT) AS desc,
			CAST(func.id AS TEXT) AS desc_id
		  FROM
			db_adm.t_rh_funcao_gh func
		  WHERE
			func.id_lider is NULL
		  UNION ALL
		  select
			  func.data_finalizacao,
			func.nome nome_func,
			func.id,
			arvore.nome,
			func.id_lider,
			func.id_usuario,
			CAST(arvore.desc || ' > ' || func.nome AS TEXT) AS desc,
			CAST(arvore.desc_id || ' > ' || func.id AS TEXT) AS desc_id
		  FROM
			db_adm.t_rh_funcao_gh func 
		  INNER JOIN
			arvore ON func.id_lider = arvore.id
		)
		select
		  arvore.id,
		  cast(nome_func || ' - ' || us.nome as text) as nome
		FROM
		  arvore
		  join db_adm.t_user us on us.id = arvore.id_usuario 
		  where desc_id like '%{$id_lideranca}%'
		ORDER BY
		  nome";

		$result = pg_query($conn, $v_sql);

		$v_equipes = array();
		while($row = pg_fetch_assoc($result)){
			$v_equipes[] = array(
				"id" => $row["id"],
				"nome" => $row["nome"]
			);
		}

		$v_colaboradores = array();

		$v_sql = "select tu.id, tu.nome from db_adm.t_hist_gh gh
			join db_adm.t_user tu on tu.id = gh.id_usuario 
			where gh.id_gh = {$id_lideranca} group by tu.id order by tu.nome";

		$result1 = pg_query($conn, $v_sql);

		while($row = pg_fetch_assoc($result1)){
			$v_colaboradores[] = array(
				"id_colaborador" => $row["id"],
				"nome" => $row["nome"]
			);
		}

		$v_resposta = array(
			"lider" => true,
			"id_colaborador" => $_SESSION["vs_id"],
			"nome" => $nome,
			"indicadores" => $v_indicadores,
			"colaboradores" => $v_colaboradores,
			"equipes" => $v_equipes,
			"lideranca" => $id_lideranca
		);
	}else{

		$v_resposta = array(
			"lider" => false,
			"id_colaborador" => $_SESSION["vs_id"],
			"nome" => $nome,
			"indicadores" => $v_indicadores
		);
	}

	pg_close($conn);
	$v_json = json_encode($v_resposta);
	echo $v_json;

}

if($v_acao == 'SELECIONA_COLABORADOR'){

	$v_id_colaborador = addslashes($_POST["v_id_colaborador"]);

	$v_sql = "select * from db_adm.t_indicadores_premiacao20211 tip where id in (
		select tiu.id_indicador from db_adm.t_indicador_usuario20211 tiu where id_usuario = {$v_id_colaborador}
	) order by id";

	$result = pg_query($conn, $v_sql);
	$v_dados = array();
	while($row = pg_fetch_assoc($result)){
		$v_dados[] = array(
			"id" => $row["id"],
			"descricao" => $row["descricao"]
		);
	}

	pg_close($conn);
	$v_json = json_encode($v_dados);
	echo $v_json;
}

if($v_acao == 'SELECIONA_LIDERANCA'){

	$v_id_lideranca = addslashes($_POST["v_id_lideranca"]);

	$v_sql = "select tu.id, tu.nome from db_adm.t_hist_gh gh
			join db_adm.t_user tu on tu.id = gh.id_usuario 
			where gh.id_gh = {$v_id_lideranca} group by tu.id order by tu.nome";

	$result = pg_query($conn, $v_sql);

	$v_colaboradores = array();
	while($row = pg_fetch_assoc($result)){
		$v_colaboradores[] = array(
			"id" => $row["id"],
			"nome" => $row["nome"]
		);
	}

	$v_sql = "select tu.id, tu.nome from db_adm.t_rh_funcao_gh gh
	join db_adm.t_user tu on tu.id = gh.id_usuario 
	where gh.id = {$v_id_lideranca}";

	$result1 = pg_fetch_object(pg_query($conn, $v_sql));

	$v_dados = array(
		"colaboradores" => $v_colaboradores,
		"nome" => $result1->nome,
		"id" => $result1->id
	);

	pg_close($conn);
	$v_json = json_encode($v_dados);
	echo $v_json;

}