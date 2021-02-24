<?php
header("Content-type: application/pdf");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
$vs_id = str_replace(".", "", $_SESSION["vs_id"]);

// CARREGANDO O HTML
ob_start();
// require __DIR__ . "../holerite_mod02.html";
require_once('holerite_mod02.html');
$html = ob_get_clean();
//$html = ob_get_contents();
//ob_clean();
$v_competencia = base64_decode($_GET["ref"]);
$v_tipo = base64_decode($_GET["folha"]);
$v_matricula = base64_decode($_GET["mat"]);

$v_sql = "SELECT colab.pis, colab.matricula Matricula, colab.id_usuario,  colab.id Id, colab.Cpf Cpf, colab.Nome Nome, colab.Data_admissao Admissao, cargo.Nome Cargo, dep.Nome Departamento, cargo.cbo 
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
       and colab.id_empresa =  {$_SESSION["vs_id_empresa"]}            
    ORDER BY Matricula asc OFFSET 0 LIMIT 50";


if ($result2 = pg_query($conn, $v_sql)) {
	$row = pg_fetch_assoc($result2);

	if($row["id_usuario"] != $_SESSION["vs_id"]){
		echo "Não autorizado a baixar holerite";
		die;
	}

	$cpf_preenche = str_pad($row["cpf"], 11, "0", STR_PAD_LEFT);
	$cpf_formatado = substr($cpf_preenche, 0,3). ".".substr($cpf_preenche, 3,3). ".".substr($cpf_preenche, 6,3). "-".substr($cpf_preenche, 9,2);
	$pis_formatado = substr($row["pis"], 0,3). ".".substr($row["pis"], 3,4). ".".substr($row["pis"], 7,3). "-".substr($row["pis"], 10,1);
	
	$data_adm = date_create($row["admissao"]);
	$data_formatada = date_format($data_adm,"d/m/Y");
	$html = str_replace("[v_matricula]", "Matrícula {$row["matricula"]}", $html);
	$html = str_replace("[v_nome]", $row["nome"], $html);
	$html = str_replace("[v_cpf]", $cpf_formatado, $html);
	$html = str_replace("[v_pis]", $pis_formatado, $html);
	$html = str_replace("[v_departamento]", $row["departamento"], $html);
	$html = str_replace("[v_cargo]", $row["cargo"], $html);
	$html = str_replace("[v_cbo]", $row["cbo"], $html);
	$html = str_replace("[v_admissao]", $data_formatada, $html);

	// var_dump($row["cargo"]);
	//var_dump($row["departamento"]);
	//die;   
}


// SELECIONANDO DADOS DA EMPRESA
$v_sql = "SELECT id Cod_empresa,Nome Nome_empresa,Cnpj, url_arquivo " .
	"FROM db_adm.t_empresas " .
	"WHERE id =" . $_SESSION["vs_id_empresa"];
$result = pg_query($conn, $v_sql);


if ($row = pg_fetch_assoc($result)) {

	$cnpj_preenche = str_pad($row["cnpj"], 14, "0", STR_PAD_LEFT);
	$cnpj_formatado = substr($cnpj_preenche, 0,2). ".".substr($cnpj_preenche, 2,3). ".".substr($cnpj_preenche, 5,3). "/".substr($cnpj_preenche, 8,4)."-".substr($cnpj_preenche, 12,2);

	$html = str_replace("[v_razao_social]", $row["nome_empresa"], $html);
	$html = str_replace("[v_cnpj]", $cnpj_formatado, $html);
	$html = str_replace("[v_logo]", "<img src='{$row["url_arquivo"]}' width='70'>" , $html);
}

// SELECIONANDO BASE DO HOLERITE 
$v_sql = "SELECT bases.dependentes_ir, bases.dependentes_sf, bases.ip_autenticacao, bases.usuario_autenticacao, bases.data_autenticacao ,bases.Competencia competencia,Salario_base, Base_inss, Base_irrf, Base_fgts,Valor_fgts Fgts_mes, Total_vencimentos, Total_descontos, Total_liquido
	FROM db_adm_rh.t_rh_holerite_bases bases
   WHERE bases.Competencia = '{$v_competencia}'
	 AND bases.Tipo_folha = {$v_tipo}
	 AND bases.matricula = '{$v_matricula}'
	 and bases.id_empresa = {$_SESSION["vs_id_empresa"]}";

if ($result3 = pg_query($conn, $v_sql)) {

	$meses = [
		'Janeiro',
		'Fevereiro',
		'Março',
		'Abril',
		'Maio',
		'Junho',
		'Julho',
		'Agosto',
		'Setembro',
		'Outubro',
		'Novembro',
		'Dezembro'
	];
	
	$row = pg_fetch_assoc($result3);
	$data_split = explode("-", $row["competencia"]);
	$dat_extenso = $meses[$data_split[1] - 1]."/". $data_split[0];

	$html = str_replace("[v_competencia]", $dat_extenso, $html);
	$html = str_replace("[v_salario_base]","R$ ". number_format($row["salario_base"],2,',','.'), $html);
	$html = str_replace("[v_base_inss]", "Base INSS: R$ ". number_format($row["base_inss"],2,',','.'), $html);
	$html = str_replace("[v_base_irrf]", "Base IRRF: R$ ". number_format($row["base_irrf"],2,',','.'), $html);
	$html = str_replace("[v_dep_sf]", "Dependentes Sal. Família: ". $row["dependentes_sf"], $html);
	$html = str_replace("[v_dep_ir]", "Dependentes IRRF: ". $row["dependentes_ir"], $html);
	$html = str_replace("[v_base_fgts]", "Base FGTS: R$ ".number_format($row["base_fgts"],2,',','.'), $html);
	$html = str_replace("[v_fgts_mes]", "Valor FGTS: R$ " .number_format($row["fgts_mes"],2,',','.'), $html);
	$html = str_replace("[v_total_proventos]","R$ ". number_format($row["total_vencimentos"],2,',','.'), $html);
	$html = str_replace("[v_total_descontos]", "R$ ". number_format($row["total_descontos"],2,',','.'), $html);
	$html = str_replace("[v_total_liquido]", "R$ ". number_format($row["total_liquido"],2,',','.'), $html);

	if($row["ip_autenticacao"] == '' || $row["ip_autenticacao"] == null){
		$html = str_replace("[v_assinatura]", "Não deu ciência!", $html);
	} else {
		$data_ass = date_create($row["data_autenticacao"]);
		$data_formatada = date_format($data_ass,"d/m/Y H:i:s");
		$html = str_replace("[v_assinatura]", "Deu ciência digitalmente em {$data_formatada} GMT-03:00. Usuário: {$row["usuario_autenticacao"]}. IP: {$row["ip_autenticacao"]}", $html);
	}

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
AND colab.id_usuario = {$vs_id}
AND hol.competencia = '{$v_competencia}'
AND hol.Tipo_folha = {$v_tipo}
and hol.id_empresa = {$_SESSION["vs_id_empresa"]}";

$result4 = pg_query($conn, $v_sql);

$v_verbas = "";

while ($row = pg_fetch_assoc($result4)) {
	$v_verbas .= "<tr>
		<td style='text-align: center;'>{$row["rubrica"]}</td>
		<td style='text-align: center;'>{$row["descricao_rubrica"]}</td>
		<td style='text-align: center;'>{$row["referencia"]}</td>
		<td style='text-align: center;'>R$ ". number_format($row["vencimentos"],2,',','.')."</td>
		<td></td>
	</tr>";
}

// SELECIONANDO VERBAS DO COLABORADOR - DESCONTOS
// $v_sql = "SELECT hol.Competencia Competencia, tipFol.Tipo_folha Tipo_folha,hol.Rubrica Rubrica, tipRub.descricao Tipo_Rubrica, 
// rub.descricao Descricao_rubrica,hol.referencia Referencia,hol.valor Vencimentos, 0 Descontos 
// FROM db_adm_rh.t_rh_holerite hol 
// inner join db_adm_rh.t_rh_colaborador as colab 
// 	on colab.matricula = hol.matricula 
// 	and colab.id_empresa = hol.id_empresa
// inner JOIN db_adm.t_empresas as emp ON emp.id = colab.id_empresa      
// inner JOIN db_adm_rh.t_rh_holerite_rubricas_tabela as tabRub 
// 	ON tabRub.id  = emp.id_tab_rubricas  
// inner JOIN db_adm_rh.t_rh_holerite_rubricas AS rub 
// 	ON rub.id_tabela = tabRub.id 
//    and rub.rubrica = hol.rubrica     
// INNER JOIN db_adm_rh.t_rh_holerite_tipo_folha AS tipFol ON hol.tipo_Folha = tipFol.Id 
// INNER JOIN db_adm_rh.t_rh_holerite_tipo_rubrica AS tipRub ON rub.tipo = tipRub.Id  
// WHERE rub.tipo in(2,3) 
// AND colab.id_usuario = {$vs_id}
// AND hol.competencia = '{$v_competencia}'
// AND hol.Tipo_folha = {$v_tipo}
// and hol.id_empresa = {$_SESSION["vs_id_empresa"]}";
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
		 AND hol.matricula = '{$v_matricula}' 
		 AND hol.competencia = '{$v_competencia}'
		 AND hol.Tipo_folha = {$v_tipo}
		 and hol.id_empresa = {$_SESSION["vs_id_empresa"]}";

$result5 = pg_query($conn, $v_sql);

while ($row = pg_fetch_assoc($result5)) {
	$v_verbas .= "<tr>
		<td style='text-align: center;'>{$row["rubrica"]}</td>
		<td style='text-align: center;'>{$row["descricao_rubrica"]}</td>
		<td style='text-align: center;'>{$row["referencia"]}</td>
		<td></td>
		<td style='text-align: center;'>R$ ". number_format($row["descontos"],2,',','.')."</td>
	</tr>";
}

$html = str_replace("[v_competencias]", $v_verbas, $html);
// $html = str_replace("[caminho]",realpath('../../'),$html);
use Dompdf\Dompdf;
use Dompdf\Options;
// include autoloader
require_once("../../class/dompdf/autoload.inc.php");

//Criando a Instancia
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$dompdf->setBasePath(realpath('../../'));

$dompdf->loadHtml($html);

// Definindo o papel e a orientação
$dompdf->setPaper('A4');
//$dompdf->setPaper('A4', 'landscape');   

//Renderizar o html
$dompdf->render();

//Para realizar o download somente alterar para true
$dompdf->stream("Holerite.pdf", ["Attachment" => false]);
