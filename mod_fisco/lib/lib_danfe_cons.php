<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "CARREGA_TAB") {

    $v_cnpj = explode("|", addslashes($_POST["v_empresa"]))[0];
    $v_db_emp1 = explode("|", addslashes($_POST["v_empresa"]))[1];
    $v_db_emp = str_pad($v_db_emp1, 4, '0', STR_PAD_LEFT);
    $v_filtro_analises = addslashes($_POST["v_filtro_analises"]);
    $v_filtro_manifesto = addslashes($_POST["v_filtro_manifesto"]);
    $v_filtro_dt_in = addslashes($_POST["v_filtro_dt_in"]);
    $v_filtro_dt_fim = addslashes($_POST["v_filtro_dt_fim"]);
    $v_tipo = addslashes($_POST["v_tipo"]);
    $v_filtro_ano_in = substr($v_filtro_dt_in, 0, 4);
    $v_filtro_ano_fim = substr($v_filtro_dt_fim, 0, 4);

    $filtro = "";
    if ($v_filtro_analises == "T") {
        $filtro = "";
    } else {
        $filtro = "and analise_status = '" . $v_filtro_analises . "' ";
    }

    if ($v_filtro_manifesto != "T") {
        $filtro .= "and nfe_manifesto = " . $v_filtro_manifesto . " ";
    }

    if ($v_tipo == 1) {
        $filtro .= "and nfe_tipo = 1 and cnpj_dest = " . $v_cnpj . " ";
    } else {
        $filtro .= "and nfe_tipo = 2 and cnpj_emit = " . $v_cnpj . " ";
    }




    if ($v_filtro_ano_in == $v_filtro_ano_fim) {
        $v_sql = "SELECT \n"
            . "case analise_status when '-' then '<i class=\"fa fa-pencil-square-o fa-2x\" aria-hidden=\"true\"></i>' when 'S' then '<i class=\"fa fa-check-square-o fa-2x\" aria-hidden=\"true\"></i>' when 'P' then '<i class=\"fa fa-minus-square fa-2x\" aria-hidden=\"true\"></i>' else '<i class=\"fa fa-exclamation-triangle fa-2x\" aria-hidden=\"true\"></i>' end as tipo_check_ico, \n"
            . "case analise_status when '-' then 'black' when 'S' then '#006400' when 'P' then '#C85A53' else '#B8860B' end as tipo_check_cor, \n"
            . "case analise_status when '-' then 'white' when 'S' then '#F0FFF0' when 'P' then '#FFF5EE' else '#FFFAF0' end as tipo_check_bcor, \n"
            . "concat(chave_01,chave_02,chave_03) as nfe_chave, \n"
            . "substring(LPAD(cast(chave_02 as varchar), 15, '0'),8,3) as nfe_serie, \n"
            . "concat(substring(chave_02, 11, 5), substring(chave_03, 0, 5)) as nfe_num, \n"
            . "extract(year from dt_emit) ano, TO_CHAR(dt_emit, 'DD/MM/YYYY') AS dt_emit, dt_emit AS dt_emit_order, chave_01, chave_02, chave_03, nfe_tipo, case when (nfe_tipo = 1) then cnpj_emit else cnpj_dest end as cnpj, razao_social_nfe, case when nfe_tipo = 1 then 'ENTRADA' else 'SAÍDA' end AS tipo, quant_prod, vnf, t_fisco_lista_nfe_manifesto.nfe_status as manifesto, nfe_manifesto FROM db_emp_" . $v_db_emp . ".t_fisco_" . $v_filtro_ano_in . "_nfeproc \n"
            . "LEFT JOIN db_adm.t_fisco_lista_nfe_manifesto ON db_emp_" . $v_db_emp . ".t_fisco_" . $v_filtro_ano_in . "_nfeproc.nfe_manifesto = db_adm.t_fisco_lista_nfe_manifesto.id \n"
            . "WHERE dt_emit BETWEEN '" . $v_filtro_dt_in . " 00:00:00' AND '" . $v_filtro_dt_fim . " 23:59:59' " . $filtro . " ORDER BY dt_emit_order DESC";
    } else {

        $v_sql = "select tipo_check_ico, tipo_check_cor, tipo_check_bcor, nfe_chave, nfe_serie, nfe_num, ano, dt_emit, chave_01, chave_02, chave_03, nfe_tipo, cnpj, razao_social_nfe, tipo, quant_prod, vnf, manifesto, nfe_manifesto FROM \n"
            . "(SELECT \n"
            . "case analise_status when '-' then '<i class=\"fa fa-pencil-square-o fa-2x\" aria-hidden=\"true\"></i>' when 'S' then '<i class=\"fa fa-check-square-o fa-2x\" aria-hidden=\"true\"></i>' when 'P' then '<i class=\"fa fa-minus-square fa-2x\" aria-hidden=\"true\"></i>' else '<i class=\"fa fa-exclamation-triangle fa-2x\" aria-hidden=\"true\"></i>' end as tipo_check_ico, \n"
            . "case analise_status when '-' then 'black' when 'S' then '#006400' when 'P' then '#C85A53' else '#B8860B' end as tipo_check_cor, \n"
            . "case analise_status when '-' then 'white' when 'S' then '#F0FFF0' when 'P' then '#FFF5EE' else '#FFFAF0' end as tipo_check_bcor, \n"
            . "concat(chave_01,chave_02,chave_03) as nfe_chave, \n"
            . "substring(chave_02, 8, 3) as nfe_serie, \n"
            . "concat(substring(chave_02, 11, 5), substring(chave_03, 0, 5)) as nfe_num, \n"
            . "dt_emit AS dt_emit_order, \n"
            . "extract(year from dt_emit) ano, TO_CHAR(dt_emit, 'DD/MM/YYYY') AS dt_emit, chave_01, chave_02, chave_03, nfe_tipo, case when (nfe_tipo = 1) then cnpj_emit else cnpj_dest end as cnpj, razao_social_nfe, case when nfe_tipo = 1 then 'ENTRADA' else 'SAÍDA' end AS tipo, quant_prod, vnf, t_fisco_lista_nfe_manifesto.nfe_status as manifesto, nfe_manifesto FROM db_emp_" . $v_db_emp . ".t_fisco_" . $v_filtro_ano_in . "_nfeproc \n"
            . "LEFT JOIN db_adm.t_fisco_lista_nfe_manifesto ON db_emp_" . $v_db_emp . ".t_fisco_" . $v_filtro_ano_in . "_nfeproc.nfe_manifesto = db_adm.t_fisco_lista_nfe_manifesto.id \n"
            . "WHERE dt_emit >= '" . $v_filtro_dt_in . " 00:00:00' " . $filtro . " \n"
            . "UNION \n"
            . "SELECT \n"
            . "case analise_status when '-' then '<i class=\"fa fa-pencil-square-o fa-2x\" aria-hidden=\"true\"></i>' when 'S' then '<i class=\"fa fa-check-square-o fa-2x\" aria-hidden=\"true\"></i>' when 'P' then '<i class=\"fa fa-minus-square fa-2x\" aria-hidden=\"true\"></i>' else '<i class=\"fa fa-exclamation-triangle fa-2x\" aria-hidden=\"true\"></i>' end as tipo_check_ico, \n"
            . "case analise_status when '-' then 'black' when 'S' then '#006400' when 'P' then '#C85A53' else '#B8860B' end as tipo_check_cor, \n"
            . "case analise_status when '-' then 'white' when 'S' then '#F0FFF0' when 'P' then '#FFF5EE' else '#FFFAF0' end as tipo_check_bcor, \n"
            . "concat(chave_01,chave_02,chave_03) as nfe_chave, \n"
            . "substring(chave_02, 8, 3) as nfe_serie, \n"
            . "concat(substring(chave_02, 11, 5), substring(chave_03, 0, 5)) as nfe_num, \n"
            . "dt_emit AS dt_emit_order, \n"
            . "extract(year from dt_emit) ano, TO_CHAR(dt_emit, 'DD/MM/YYYY') AS dt_emit, chave_01, chave_02, chave_03, nfe_tipo, case when (nfe_tipo = 1) then cnpj_emit else cnpj_dest end as cnpj, razao_social_nfe, case when nfe_tipo = 1 then 'ENTRADA' else 'SAÍDA' end AS tipo, quant_prod, vnf, t_fisco_lista_nfe_manifesto.nfe_status as manifesto, nfe_manifesto FROM db_emp_" . $v_db_emp . ".t_fisco_" . $v_filtro_ano_fim . "_nfeproc \n"
            . "LEFT JOIN db_adm.t_fisco_lista_nfe_manifesto ON db_emp_" . $v_db_emp . ".t_fisco_" . $v_filtro_ano_fim . "_nfeproc.nfe_manifesto = db_adm.t_fisco_lista_nfe_manifesto.id \n"
            . "WHERE dt_emit <= '" . $v_filtro_dt_fim . " 00:00:00' " . $filtro . ") tabx \n"
            . "ORDER BY dt_emit_order DESC";
    }

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("tipo_check_cor" => $row["tipo_check_cor"], "tipo_check_bcor" => $row["tipo_check_bcor"], "tipo_check_ico" => $row["tipo_check_ico"], "razao_social_nfe" => $row["razao_social_nfe"], "db_emp" => $v_db_emp1, "ano" => substr($row["ano"], 0, 4), "dt_emit" => $row["dt_emit"], "chave" => $row["nfe_chave"], "num_nfe" => $row["nfe_num"], "serie_nfe" => $row["nfe_serie"], "chave_01" => $row["chave_01"], "chave_02" => $row["chave_02"], "chave_03" => $row["chave_03"], "cnpj" => str_pad($row["cnpj"], 14, '0', STR_PAD_LEFT), "nfe_tipo" => $row["nfe_tipo"], "tipo" => $row["tipo"], "quant_prod" => str_pad($row["quant_prod"], 3, '0', STR_PAD_LEFT), "vnf" => number_format($row["vnf"], 2, ",", "."), "cod_manifesto" => $row["nfe_manifesto"], "manifesto" => $row["manifesto"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_EMPRESAS") {

    $v_sql = "select t_empresas.cnpj, t_empresas.db_emp, t_empresas.nome from db_adm.t_access 
    join db_adm.t_access_emp_01_grupo_emp on db_adm.t_access.id_grupo_emp = db_adm.t_access_emp_01_grupo_emp.id_grupo_emp
    join db_adm.t_empresas on db_adm.t_access_emp_01_grupo_emp.id_emp = db_adm.t_empresas.id 
    where t_access.id_user = {$_SESSION["vs_id"]} 
    group by t_empresas.cnpj, t_empresas.db_emp, t_empresas.nome
    order by t_empresas.nome";
    $result = pg_query($conn, $v_sql);

    $v_dados = '[';
    while ($row = pg_fetch_assoc($result)) {

        $cpf_cnpj = $row["cnpj"];
        if (strlen($row["cnpj"]) > 11) {
            $cpf_cnpj = str_pad($cpf_cnpj, 14, '0', STR_PAD_LEFT);
            $bloco_1 = substr($cpf_cnpj, 0, 2);
            $bloco_2 = substr($cpf_cnpj, 2, 3);
            $bloco_3 = substr($cpf_cnpj, 5, 3);
            $bloco_4 = substr($cpf_cnpj, 8, 4);
            $digito_verificador = substr($cpf_cnpj, -2);
            $cpf_cnpj = $bloco_1 . "." . $bloco_2 . "." . $bloco_3 . "/" . $bloco_4 . "-" . $digito_verificador;
        } else {
            $cpf_cnpj = str_pad($cpf_cnpj, 11, '0', STR_PAD_LEFT);
            $bloco_1 = substr($cpf_cnpj, 0, 3);
            $bloco_2 = substr($cpf_cnpj, 3, 3);
            $bloco_3 = substr($cpf_cnpj, 6, 3);
            $dig_verificador = substr($cpf_cnpj, -2);
            $cpf_cnpj = $bloco_1 . "." . $bloco_2 . "." . $bloco_3 . "-" . $dig_verificador;
        }

        $v_dados .= '{"cnpj":"' . ($row["cnpj"] . "|" . $row["db_emp"])
            . '","nome":"' . ($row["nome"] . " - " . $cpf_cnpj)
            . '"},';
    }

    $v_dados = substr($v_dados, 0, -1) . ']';
    echo $v_dados;
}



// SALVANDO REGISTRO
if ($v_acao == "SALVAR_OBS") {

    $v_chave_01 = addslashes($_POST["v_chave_01"]);
    $v_chave_02 = addslashes($_POST["v_chave_02"]);
    $v_chave_03 = addslashes($_POST["v_chave_03"]);
    $v_nfe_tipo = addslashes($_POST["v_nfe_tipo"]);
    $v_db_emp = str_pad(addslashes($_POST["v_db_emp"]), 4, '0', STR_PAD_LEFT);
    $v_ano = "20" . substr($v_chave_01, 2, 2);
    $v_resultado = addslashes($_POST["v_resultado"]);
    $v_analise_texto = strtoupper(addslashes($_POST["v_analise_texto"]));

    $v_sql = "INSERT INTO db_emp_" . $v_db_emp . ".t_fisco_" . $v_ano . "_nfe_analises (chave_01, chave_02, chave_03, nfe_tipo, id_user, analise_status, analise_texto) 
    VALUES ('" . $v_chave_01 . "', '" . $v_chave_02 . "', '" . $v_chave_03 . "', " . $v_nfe_tipo . ", " . $_SESSION["vs_id"] . ", '" . $v_resultado . "', '" . $v_analise_texto . "')";

    if (pg_query($conn, $v_sql)) {

        $v_sql = "UPDATE db_emp_" . $v_db_emp . ".t_fisco_" . $v_ano . "_nfeproc SET analise_status = '" . $v_resultado . "' 
        WHERE chave_01 = '" . $v_chave_01 . "' and chave_02 = '" . $v_chave_02 . "' and chave_03 = '" . $v_chave_03 . "' and nfe_tipo = " . $v_nfe_tipo;
        pg_query($conn, $v_sql);

        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Validação e observações salvas com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "CONSULTAR_NFE") {

    $v_chave_01 = addslashes($_POST["v_chave_01"]);
    $v_chave_02 = addslashes($_POST["v_chave_02"]);
    $v_chave_03 = addslashes($_POST["v_chave_03"]);
    $v_nfe_tipo = addslashes($_POST["v_nfe_tipo"]);
    $v_db_emp = addslashes($_POST["v_db_emp"]);
    $v_db_emp = str_pad($v_db_emp, 4, '0', STR_PAD_LEFT);
    $v_ano = addslashes($_POST["v_ano"]);


    $v_sql = "SELECT TO_CHAR(t_fisco_" . $v_ano . "_nfe_analises.data_hora,'DD/MM/YYYY HH:MI') as data_hora, t_fisco_" . $v_ano . "_nfe_analises.id_user, t_fisco_" . $v_ano . "_nfe_analises.analise_status, t_fisco_" . $v_ano . "_nfe_analises.analise_texto, t_user.nome as nome_user 
    FROM db_emp_" . $v_db_emp . ".t_fisco_" . $v_ano . "_nfe_analises 
    JOIN db_adm.t_user on db_emp_" . $v_db_emp . ".t_fisco_" . $v_ano . "_nfe_analises.id_user = db_adm.t_user.id 
    WHERE chave_01 = '" . $v_chave_01 . "' AND chave_02 = '" . $v_chave_02 . "' AND chave_03 = '" . $v_chave_03 . "' AND nfe_tipo = " . $v_nfe_tipo . "
    order by t_fisco_" . $v_ano . "_nfe_analises.id desc";

    $result = pg_query($conn, $v_sql);

    $v_analise_texto = "";

    while ($row = pg_fetch_assoc($result)) {

        $analise_status = "";
        switch ($row["analise_status"]) {
            case "-":
                $analise_status = "";
                break;
            case "P":
                $analise_status = "NÃO LANÇADA";
                break;
            case "S":
                $analise_status = "ANALISADA COM SUCESSO";
                break;
            case "N":
                $analise_status = "ANALISADA COM RESSALVA";
                break;
        }

        if (!empty($analise_status)) {
            if (!empty($v_analise_texto)) {
                $v_analise_texto .= "<br><br>";
            }
            $v_analise_texto .= $analise_status . "<br>";
            $v_analise_texto .= $row["data_hora"] . " - " . $row["nome_user"] . "<br>";
            $v_analise_texto .= $row["analise_texto"];
        }
    }



    $v_xml_nfe = "";
    $v_danfe_chave = "";
    $v_xml_nfe = "";
    $v_danfe_tipo = "";

    $v_sql = "SELECT chave_01, chave_02, chave_03, nfe_tipo, quant_prod, xml_nfe FROM db_emp_" . $v_db_emp . ".t_fisco_" . $v_ano . "_nfeproc WHERE chave_01 = '" . $v_chave_01 . "' AND chave_02 = '" . $v_chave_02 . "' AND chave_03 = '" . $v_chave_03 . "' AND nfe_tipo = " . $v_nfe_tipo;
    $result = pg_query($conn, $v_sql);

    if ($row = pg_fetch_assoc($result)) {
        $v_xml_nfe = $row["xml_nfe"];
        $v_danfe_tipo = $row["nfe_tipo"];
        $v_danfe_chave =  $row["chave_01"] . $row["chave_02"] . $row["chave_03"];
    }
    // ENVIANDO DADOS
    pg_close($conn);


    $v_sefaz_xml = simplexml_load_string($v_xml_nfe);

    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->emit->xNome)) {
        $v_emit_razao_social = $v_sefaz_xml->NFe->infNFe->emit->xNome;
    } else {
        $v_emit_razao_social = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->emit->enderEmit->xLgr)) {
        $v_emit_end1 = $v_sefaz_xml->NFe->infNFe->emit->enderEmit->xLgr;
    } else {
        $v_emit_end1 = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->emit->enderEmit->nro)) {
        $v_emit_end1 .= ", " . $v_sefaz_xml->NFe->infNFe->emit->enderEmit->nro;
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->emit->enderEmit->xBairro)) {
        $v_emit_end2 = $v_sefaz_xml->NFe->infNFe->emit->enderEmit->xBairro;
    } else {
        $v_emit_end2 = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->emit->enderEmit->CEP)) {
        $v_emit_end2 .=  " - " . $v_sefaz_xml->NFe->infNFe->emit->enderEmit->CEP;
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->emit->enderEmit->xMun)) {
        $v_emit_end3 =  $v_sefaz_xml->NFe->infNFe->emit->enderEmit->xMun;
    } else {
        $v_emit_end3 = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->emit->enderEmit->UF)) {
        $v_emit_end3 .=  " - " . $v_sefaz_xml->NFe->infNFe->emit->enderEmit->UF;
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->emit->enderEmit->fone)) {
        $v_emit_end3 .= " Fone/Fax: " . $v_sefaz_xml->NFe->infNFe->emit->enderEmit->fone;
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->ide->nNF)) {
        $v_danfe_num =  $v_sefaz_xml->NFe->infNFe->ide->nNF;
    } else {
        $v_danfe_num = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->ide->serie)) {
        $v_danfe_serie =  $v_sefaz_xml->NFe->infNFe->ide->serie;
    } else {
        $v_danfe_serie = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->ide->natOp)) {
        $v_emit_natureza =  $v_sefaz_xml->NFe->infNFe->ide->natOp;
    } else {
        $v_emit_natureza = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->protNFe->infProt->dhRecbto)) {
        $v_emit_prot_num =  $v_sefaz_xml->protNFe->infProt->dhRecbto;
        $v_emit_prot_num = str_replace("T", " ", $v_emit_prot_num);
    } else {
        $v_emit_prot_num = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->emit->IE)) {
        $v_emit_inscr_est = $v_sefaz_xml->NFe->infNFe->emit->IE;
    } else {
        $v_emit_inscr_est = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->emit->CNPJ)) {
        $v_emit_cnpj = $v_sefaz_xml->NFe->infNFe->emit->CNPJ;
        $bloco_1 = substr($v_emit_cnpj, 0, 2);
        $bloco_2 = substr($v_emit_cnpj, 2, 3);
        $bloco_3 = substr($v_emit_cnpj, 5, 3);
        $bloco_4 = substr($v_emit_cnpj, 8, 4);
        $digito_verificador = substr($v_emit_cnpj, -2);
        $v_emit_cnpj = $bloco_1 . "." . $bloco_2 . "." . $bloco_3 . "/" . $bloco_4 . "-" . $digito_verificador;
    } else {
        $v_emit_cnpj = "0";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->emit->CPF)) {
        $v_emit_cnpj = $v_sefaz_xml->NFe->infNFe->emit->CPF;
        $bloco_1 = substr($v_emit_cnpj, 0, 3);
        $bloco_2 = substr($v_emit_cnpj, 3, 3);
        $bloco_3 = substr($v_emit_cnpj, 6, 3);
        $dig_verificador = substr($v_emit_cnpj, -2);
        $v_emit_cnpj = $bloco_1 . "." . $bloco_2 . "." . $bloco_3 . "-" . $dig_verificador;
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->dest->xNome)) {
        $v_dest_razao_social = $v_sefaz_xml->NFe->infNFe->dest->xNome;
    } else {
        $v_dest_razao_social = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->dest->CNPJ)) {
        $v_dest_cnpj = $v_sefaz_xml->NFe->infNFe->dest->CNPJ;
        $bloco_1 = substr($v_dest_cnpj, 0, 2);
        $bloco_2 = substr($v_dest_cnpj, 2, 3);
        $bloco_3 = substr($v_dest_cnpj, 5, 3);
        $bloco_4 = substr($v_dest_cnpj, 8, 4);
        $digito_verificador = substr($v_dest_cnpj, -2);
        $v_dest_cnpj = $bloco_1 . "." . $bloco_2 . "." . $bloco_3 . "/" . $bloco_4 . "-" . $digito_verificador;
    } else {
        $v_dest_cnpj = "0";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->dest->CPF)) {
        $v_dest_cnpj = $v_sefaz_xml->NFe->infNFe->dest->CPF;
        $bloco_1 = substr($v_dest_cnpj, 0, 3);
        $bloco_2 = substr($v_dest_cnpj, 3, 3);
        $bloco_3 = substr($v_dest_cnpj, 6, 3);
        $dig_verificador = substr($v_dest_cnpj, -2);
        $v_dest_cnpj = $bloco_1 . "." . $bloco_2 . "." . $bloco_3 . "-" . $dig_verificador;
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->ide->dhEmi)) {
        $v_dest_dt_emit = $v_sefaz_xml->NFe->infNFe->ide->dhEmi;
    } else {
        $v_dest_dt_emit = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->ide->dhSaiEnt)) {
        $v_dest_dt_saida = $v_sefaz_xml->NFe->infNFe->ide->dhSaiEnt;
    } else {
        $v_dest_dt_saida = "";
    }

    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->dest->enderDest->xLgr)) {
        $v_dest_end = $v_sefaz_xml->NFe->infNFe->dest->enderDest->xLgr;
        $v_dest_end .= ", " . $v_sefaz_xml->NFe->infNFe->dest->enderDest->nro;
    } else {
        $v_dest_end = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->dest->enderDest->xBairro)) {
        $v_dest_bairro = $v_sefaz_xml->NFe->infNFe->dest->enderDest->xBairro;
    } else {
        $v_dest_bairro = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->dest->enderDest->CEP)) {
        $v_dest_cep = $v_sefaz_xml->NFe->infNFe->dest->enderDest->CEP;
    } else {
        $v_dest_cep = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->dest->enderDest->xMun)) {
        $v_dest_municip = $v_sefaz_xml->NFe->infNFe->dest->enderDest->xMun;
    } else {
        $v_dest_municip = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->dest->enderDest->fone)) {
        $v_dest_fone = $v_sefaz_xml->NFe->infNFe->dest->enderDest->fone;
    } else {
        $v_dest_fone = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->dest->enderDest->UF)) {
        $v_dest_uf = $v_sefaz_xml->NFe->infNFe->dest->enderDest->UF;
    } else {
        $v_dest_uf = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->dest->IE)) {
        $v_dest_ie = $v_sefaz_xml->NFe->infNFe->dest->IE;
    } else {
        $v_dest_ie = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vBC)) {
        $v_calc_bc_icms = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vBC;
    } else {
        $v_calc_bc_icms = "0,00";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vICMS)) {
        $v_calc_icms = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vICMS;
    } else {
        $v_calc_icms = "0,00";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vBCST)) {
        $v_calc_bc_icms_st = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vBCST;
    } else {
        $v_calc_bc_icms_st = "0,00";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vFCP)) {
        $v_calc_fcp = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vFCP;
    } else {
        $v_calc_fcp = "0,00";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vProd)) {
        $v_calc_val_tt_prod = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vProd;
    } else {
        $v_calc_val_tt_prod = "0,00";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vPIS)) {
        $v_calc_pis = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vPIS;
    } else {
        $v_calc_pis = "0,00";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vICMSDeson)) {
        $v_calc_icms_dson = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vICMSDeson;
    } else {
        $v_calc_icms_dson = "0,00";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vCOFINS)) {
        $v_calc_cofins = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vCOFINS;
    } else {
        $v_calc_cofins = "0,00";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vFrete)) {
        $v_calc_frete = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vFrete;
    } else {
        $v_calc_frete = "0,00";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vSeg)) {
        $v_calc_seguro = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vSeg;
    } else {
        $v_calc_seguro = "0,00";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vDesc)) {
        $v_calc_desconto = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vDesc;
    } else {
        $v_calc_desconto = "0,00";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vOutro)) {
        $v_calc_outras_desp = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vOutro;
    } else {
        $v_calc_outras_desp = "0,00";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vIPI)) {
        $v_calc_ipi = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vIPI;
    } else {
        $v_calc_ipi = "0,00";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vIPI)) { // ??????????????????????????????
        $v_calc_val_aprox_trib = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vIPI; // ??????????????????????????????
    } else { // ??????????????????????????????
        $v_calc_val_aprox_trib = "0,00"; // ??????????????????????????????
    } // ??????????????????????????????
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vNF)) {
        $v_calc_val_tt_nota = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vNF;
    } else {
        $v_calc_val_tt_nota = "0,00";
    }

    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->transporta->xNome)) {
        $v_transp_razao_social = $v_sefaz_xml->NFe->infNFe->transp->transporta->xNome;
    } else {
        $v_transp_razao_social = "";
    }

    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->modFrete)) {
        $modFrete = $v_sefaz_xml->NFe->infNFe->transp->modFrete;

        switch ($modFrete) {
            case 0:
                $v_transp_frete_conta = "0 - Contratação do Frete por conta do Remetente (CIF)";
                break;
            case 1:
                $v_transp_frete_conta = "1 - Contratação do Frete por conta do Destinatário (FOB)";
                break;
            case 2:
                $v_transp_frete_conta = "2 - Contratação do Frete por conta de Terceiros";
                break;
            case 3:
                $v_transp_frete_conta = "3 - Transporte Próprio por conta do Remetente";
                break;
            case 4:
                $v_transp_frete_conta = "4 - Transporte Próprio por conta do Destinatário";
                break;
            case 9:
                $v_transp_frete_conta = "9 - Sem Ocorrência de Transporte";
                break;
        }
    } else {
        $v_transp_frete_conta = "";
    }

    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->veicTransp->RNTC)) {
        $v_transp_cod_antt = $v_sefaz_xml->NFe->infNFe->transp->veicTransp->RNTC;
    } else {
        $v_transp_cod_antt = "0";
    }

    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->veicTransp->placa)) {
        $v_transp_placa = $v_sefaz_xml->NFe->infNFe->transp->veicTransp->placa;
    } else {
        $v_transp_placa = "";
    }

    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->veicTransp->UF)) {
        $v_transp_placa_uf = $v_sefaz_xml->NFe->infNFe->transp->veicTransp->UF;
    } else {
        $v_transp_placa_uf = "";
    }

    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->transporta->CNPJ)) {
        $v_transp_cnpj = $v_sefaz_xml->NFe->infNFe->transp->transporta->CNPJ;
        $bloco_1 = substr($v_transp_cnpj, 0, 2);
        $bloco_2 = substr($v_transp_cnpj, 2, 3);
        $bloco_3 = substr($v_transp_cnpj, 5, 3);
        $bloco_4 = substr($v_transp_cnpj, 8, 4);
        $digito_verificador = substr($v_transp_cnpj, -2);
        $v_transp_cnpj = $bloco_1 . "." . $bloco_2 . "." . $bloco_3 . "/" . $bloco_4 . "-" . $digito_verificador;
    } else {
        $v_transp_cnpj = "0";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->transporta->CPF)) {
        $v_transp_cnpj = $v_sefaz_xml->NFe->infNFe->transp->transporta->CPF;
        $bloco_1 = substr($v_transp_cnpj, 0, 3);
        $bloco_2 = substr($v_transp_cnpj, 3, 3);
        $bloco_3 = substr($v_transp_cnpj, 6, 3);
        $dig_verificador = substr($v_transp_cnpj, -2);
        $v_transp_cnpj = $bloco_1 . "." . $bloco_2 . "." . $bloco_3 . "-" . $dig_verificador;
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->transporta->xEnder)) {
        $v_transp_end = $v_sefaz_xml->NFe->infNFe->transp->transporta->xEnder;
    } else {
        $v_transp_end = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->transporta->xMun)) {
        $v_transp_cidade = $v_sefaz_xml->NFe->infNFe->transp->transporta->xMun;
    } else {
        $v_transp_cidade = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->transporta->UF)) {
        $v_transp_uf = $v_sefaz_xml->NFe->infNFe->transp->transporta->UF;
    } else {
        $v_transp_uf = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->transporta->IE)) {
        $v_transp_ie = $v_sefaz_xml->NFe->infNFe->transp->transporta->IE;
    } else {
        $v_transp_ie = "0";
    }

    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->vol->qVol)) {
        $v_transp_quant = $v_sefaz_xml->NFe->infNFe->transp->vol->qVol;
    } else {
        $v_transp_quant = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->vol->esp)) {
        $v_transp_especie = $v_sefaz_xml->NFe->infNFe->transp->vol->esp;
    } else {
        $v_transp_especie = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->vol->marca)) {
        $v_transp_marca = $v_sefaz_xml->NFe->infNFe->transp->vol->marca;
    } else {
        $v_transp_marca = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->vol->nVol)) {
        $v_transp_num = $v_sefaz_xml->NFe->infNFe->transp->vol->nVol;
    } else {
        $v_transp_num = "0";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->vol->pesoB)) {
        $v_transp_pb = $v_sefaz_xml->NFe->infNFe->transp->vol->pesoB;
    } else {
        $v_transp_pb = "";
    }
    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->transp->vol->pesoL)) {
        $v_transp_pl = $v_sefaz_xml->NFe->infNFe->transp->vol->pesoL;
    } else {
        $v_transp_pl = "";
    }


    // OBTENDO NÚMERO DE DUPLICATAS
    $v_quant_dup = substr_count($v_xml_nfe, '</nDup>');

    if (isset($v_sefaz_xml->NFe->infNFe->cobr->fat->nFat)) {
        $v_dup_nFat = $v_sefaz_xml->NFe->infNFe->cobr->fat->nFat;
    } else {
        $v_dup_nFat = "0,00";
    }

    if (isset($v_sefaz_xml->NFe->infNFe->cobr->fat->vOrig)) {
        $v_dup_vOrig = $v_sefaz_xml->NFe->infNFe->cobr->fat->vOrig;
    } else {
        $v_dup_vOrig = "0,00";
    }

    if (isset($v_sefaz_xml->NFe->infNFe->cobr->fat->vDesc)) {
        $v_dup_vDesc = $v_sefaz_xml->NFe->infNFe->cobr->fat->vDesc;
    } else {
        $v_dup_vDesc = "0,00";
    }

    if (isset($v_sefaz_xml->NFe->infNFe->cobr->fat->vLiq)) {
        $v_dup_vLiq = $v_sefaz_xml->NFe->infNFe->cobr->fat->vLiq;
    } else {
        $v_dup_vLiq = "0,00";
    }

    $v_duplicatas = "FATURA: " . $v_dup_nFat . " &nbsp; &nbsp; - &nbsp; &nbsp; VALOR ORIG.: R$ " . $v_dup_vOrig . " &nbsp; &nbsp; - &nbsp; &nbsp; DESCONTO: R$ " . $v_dup_vDesc . " &nbsp; &nbsp; - &nbsp; &nbsp; VALOR LIQ.: R$ " . $v_dup_vLiq . "</div>" . "<br>";



    for ($v_num = 0; $v_num < $v_quant_dup; $v_num++) {

        if (isset($v_sefaz_xml->NFe->infNFe->cobr->dup->nDup)) {
            $v_dup_nDup = $v_sefaz_xml->NFe->infNFe->cobr->dup->nDup;
        } else {
            $v_dup_nDup = "";
        }

        if (isset($v_sefaz_xml->NFe->infNFe->cobr->dup->vDup)) {
            $v_dup_vDup = $v_sefaz_xml->NFe->infNFe->cobr->dup->vDup;
        } else {
            $v_dup_vDup = "";
        }

        if (isset($v_sefaz_xml->NFe->infNFe->cobr->dup->dVenc)) {
            $v_dup_dVenc = $v_sefaz_xml->NFe->infNFe->cobr->dup->dVenc;
            $v_dup_dVenc = implode('/', array_reverse(explode('-', $v_dup_dVenc)));
        } else {
            $v_dup_dVenc = "";
        }

        $v_duplicatas .= "NÚMERO: " . $v_dup_nDup . " &nbsp; &nbsp; - &nbsp; &nbsp; VALOR: R$ " . $v_dup_vDup . " &nbsp; &nbsp; - &nbsp; &nbsp; VENCIMENTO: " . $v_dup_dVenc . "</div>" . "<br>";
    }
    $v_duplicatas = substr($v_duplicatas, 0, -4);



    // OBTENDO VALORES DA NFE
    if (isset($v_sefaz_xml->NFe->infNFe->infAdic->infCpl)) {
        $v_dados_adicionais = $v_sefaz_xml->NFe->infNFe->infAdic->infCpl;
    } else {
        $v_dados_adicionais = "";
    }

    $v_dados_adicionais = str_replace("\\", "-", $v_dados_adicionais);
    $v_dados_adicionais = str_replace("|", "-", $v_dados_adicionais);
    $v_dados_adicionais = str_replace("\"", "-", $v_dados_adicionais);
    $v_dados_adicionais = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $v_dados_adicionais);
    $v_dados_adicionais = preg_replace("/\r|\n/", "<br>", $v_dados_adicionais);

    // OBTENDO VALORES DA NFE
    $v_emit_prot_lab = $v_danfe_chave;

    $v_dados = '[{"v_emit_razao_social":"' . $v_emit_razao_social
        . '","v_emit_end1":"' . $v_emit_end1
        . '","v_emit_end2":"' . $v_emit_end2
        . '","v_emit_end3":"' . $v_emit_end3
        . '","v_danfe_tipo":"' . $v_danfe_tipo
        . '","v_danfe_num":"' . $v_danfe_num
        . '","v_danfe_serie":"' . str_pad($v_danfe_serie, 3, "0", STR_PAD_LEFT)
        . '","v_danfe_chave":"' . $v_danfe_chave
        . '","v_emit_natureza":"' . $v_emit_natureza
        . '","v_emit_prot_num":"' . $v_emit_prot_num
        . '","v_emit_inscr_est":"' . $v_emit_inscr_est
        . '","v_emit_cnpj":"' . $v_emit_cnpj
        . '","v_dest_razao_social":"' . $v_dest_razao_social
        . '","v_dest_cnpj":"' . $v_dest_cnpj
        . '","v_dest_dt_emit":"' . substr($v_dest_dt_emit, 0, 10)
        . '","v_dest_end":"' . $v_dest_end
        . '","v_dest_bairro":"' . $v_dest_bairro
        . '","v_dest_cep":"' . $v_dest_cep
        . '","v_dest_municip":"' . $v_dest_municip
        . '","v_dest_uf":"' . $v_dest_uf
        . '","v_dest_fone":"' . $v_dest_fone
        . '","v_dest_ie":"' . $v_dest_ie
        . '","v_dest_dt_saida":"' . substr($v_dest_dt_saida, 0, 10)
        . '","v_dest_hr_saida":"' . substr($v_dest_dt_saida, 11, 8)
        . '","v_duplicatas":"' . $v_duplicatas
        . '","v_calc_bc_icms":"' . $v_calc_bc_icms
        . '","v_calc_icms":"' . $v_calc_icms
        . '","v_calc_bc_icms_st":"' . $v_calc_bc_icms_st
        . '","v_calc_icms_dson":"' . $v_calc_icms_dson
        . '","v_calc_val_tt_prod":"' . $v_calc_val_tt_prod
        . '","v_calc_frete":"' . $v_calc_frete
        . '","v_calc_seguro":"' . $v_calc_seguro
        . '","v_calc_outras_desp":"' . $v_calc_outras_desp
        . '","v_calc_ipi":"' . $v_calc_ipi
        . '","v_calc_val_aprox_trib":"' . $v_calc_val_aprox_trib
        . '","v_calc_val_tt_nota":"' . $v_calc_val_tt_nota
        . '","v_calc_desconto":"' . $v_calc_desconto
        . '","v_calc_pis":"' . $v_calc_pis
        . '","v_calc_cofins":"' . $v_calc_cofins
        . '","v_calc_fcp":"' . $v_calc_fcp
        . '","v_transp_razao_social":"' . $v_transp_razao_social
        . '","v_transp_frete_conta":"' . $v_transp_frete_conta
        . '","v_transp_cod_antt":"' . $v_transp_cod_antt
        . '","v_transp_placa":"' . $v_transp_placa
        . '","v_transp_placa_uf":"' . $v_transp_placa_uf
        . '","v_transp_cnpj":"' . $v_transp_cnpj
        . '","v_transp_end":"' . $v_transp_end
        . '","v_transp_cidade":"' . $v_transp_cidade
        . '","v_transp_uf":"' . $v_transp_uf
        . '","v_transp_ie":"' . $v_transp_ie
        . '","v_transp_quant":"' . $v_transp_quant
        . '","v_transp_especie":"' . $v_transp_especie
        . '","v_transp_marca":"' . $v_transp_marca
        . '","v_transp_num":"' . $v_transp_num
        . '","v_transp_pb":"' . $v_transp_pb
        . '","v_transp_pl":"' . $v_transp_pl
        . '","v_dados_adicionais":"' . $v_dados_adicionais
        . '","v_item_cProd":"' . '-'
        . '","v_item_xProd":"' . '-'
        . '","v_item_NCM":"' . '-'
        . '","v_item_CST":"' . '-'
        . '","v_item_CFOP":"' . '-'
        . '","v_item_uTrib":"' . '-'
        . '","v_item_qCom":"' . '-'
        . '","v_item_vUnCom":"' . '-'
        . '","v_item_vProd":"' . '-'
        . '","v_item_calc_icms":"' . '-'
        . '","v_item_valor_icms":"' . '-'
        . '","v_item_valor_ipi":"' . '-'
        . '","v_item_aliq_icms":"' . '-'
        . '","v_item_aliq_ipi":"' . '-'
        . '","v_analise_texto":"' . $v_analise_texto
        . '"}';


    foreach ($v_sefaz_xml->NFe->infNFe->det as $key => $valor) {

        if (isset($valor->prod->cProd)) {
            $v_item_cProd =  $valor->prod->cProd;
        } else {
            $v_item_cProd = 0;
        }
        $v_item_cProd = preg_replace('/[^0-9]/', '', $v_item_cProd);
        $v_item_cProd = (int)$v_item_cProd;

        // OBTENDO VALORES DA NFE
        if (isset($valor->prod->xProd)) {
            $v_item_xProd = $valor->prod->xProd;
            $v_item_xProd .= " " . $valor->infAdProd;
        } else {
            $v_item_xProd = "";
        }

        $v_item_xProd = str_replace("\\", "-", $v_item_xProd);
        $v_item_xProd = str_replace("|", "-", $v_item_xProd);
        $v_item_xProd = str_replace("\"", "-", $v_item_xProd);
        $v_item_xProd = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $v_item_xProd);
        $v_item_xProd = preg_replace("/\r|\n/", "<br>", $v_item_xProd);


        // OBTENDO VALORES DA NFE
        if (isset($valor->prod->NCM)) {
            $v_item_NCM =  $valor->prod->NCM;
        } else {
            $v_item_NCM = '0';
        }


        // ###################################################### //
        // CARREGANDO ICMS
        // ###################################################### //
        for ($v_num = 0; $v_num < 100; $v_num++) {
            $icms = "ICMS" . (string)str_pad($v_num, 2, '0', STR_PAD_LEFT);

            // OBTENDO VALORES DA NFE
            if (isset($valor->imposto->ICMS->$icms->CST)) {
                $v_CST_ICMS = $valor->imposto->ICMS->$icms->orig;
                $v_CST_ICMS .= $valor->imposto->ICMS->$icms->CST;
            }

            // OBTENDO VALORES DA NFE
            if (isset($valor->imposto->ICMS->$icms->vBC)) {
                $v_item_bicms = $valor->imposto->ICMS->$icms->vBC;
            } else {
                $v_item_bicms = '0,00';
            }
            // OBTENDO VALORES DA NFE
            if (isset($valor->imposto->ICMS->$icms->pICMS)) {
                $v_item_aliqicms = $valor->imposto->ICMS->$icms->pICMS;
            } else {
                $v_item_aliqicms = '0';
            }
            // OBTENDO VALORES DA NFE
            if (isset($valor->imposto->ICMS->$icms->vICMS)) {
                $v_item_vicms = $valor->imposto->ICMS->$icms->vICMS;
            } else {
                $v_item_vicms = '0,00';
            }

            if (isset($valor->imposto->ICMS->$icms->vBC)) {
                break;
            }
        }



        // ###################################################### //
        // CARREGANDO CODIGOS DE ISENÇÕES DO ICMS
        // ###################################################### //
        if ((int)$v_CST_ICMS == 0) {
            $v_array_ICMSSN = array();
            $v_array_ICMSSN[0] = "ICMSSN101";
            $v_array_ICMSSN[1] = "ICMSSN102";
            $v_array_ICMSSN[2] = "ICMSSN103";
            $v_array_ICMSSN[3] = "ICMSSN201";
            $v_array_ICMSSN[4] = "ICMSSN202";
            $v_array_ICMSSN[5] = "ICMSSN203";
            $v_array_ICMSSN[6] = "ICMSSN300";
            $v_array_ICMSSN[7] = "ICMSSN400";
            $v_array_ICMSSN[8] = "ICMSSN500";
            $v_array_ICMSSN[9] = "ICMSSN900";

            for ($v_num = 0; $v_num <= 9; $v_num++) {
                $icms = $v_array_ICMSSN[$v_num];
                if (isset($valor->imposto->ICMS->$icms->orig)) {

                    // OBTENDO VALORES DA NFE
                    $v_CST_ICMS = 0;
                    if (isset($valor->imposto->ICMS->$icms->CSOSN)) {
                        $v_CST_ICMS = $valor->imposto->ICMS->$icms->orig;
                        $v_CST_ICMS .= $valor->imposto->ICMS->$icms->CSOSN;
                    }

                    // OBTENDO VALORES DA NFE
                    if (isset($valor->imposto->ICMS->$icms->vBCSTRet)) {
                        $v_item_bicms = $valor->imposto->ICMS->$icms->vBCSTRet;
                    } else {
                        $v_item_bicms = '0,00';
                    }
                    // OBTENDO VALORES DA NFE
                    if (isset($valor->imposto->ICMS->$icms->vICMSSubstituto)) {
                        $v_item_aliqicms = $valor->imposto->ICMS->$icms->vICMSSubstituto;
                    } else {
                        $v_item_aliqicms = '0';
                    }
                    // OBTENDO VALORES DA NFE
                    if (isset($valor->imposto->ICMS->$icms->vICMSSTRet)) {
                        $v_item_vicms = $valor->imposto->ICMS->$icms->vICMSSTRet;
                    } else {
                        $v_item_vicms = '0,00';
                    }

                    if (isset($valor->imposto->ICMS->$icms->vBCSTRet)) {
                        break;
                    }
                }
            }
        }



        // OBTENDO VALORES DA NFE
        if (isset($valor->imposto->IPI->pIPI)) {
            $v_item_pIPI = $valor->imposto->IPI->pIPI;
        } else {
            $v_item_pIPI = '0,00';
        }

        // OBTENDO VALORES DA NFE
        if (isset($valor->prod->CFOP)) {
            $v_item_CFOP =  $valor->prod->CFOP;
        } else {
            $v_item_CFOP = '0';
        }
        // OBTENDO VALORES DA NFE
        if (isset($valor->prod->uTrib)) {
            $v_item_uTrib =  $valor->prod->uTrib;
        } else {
            $v_item_uTrib = '';
        }
        // OBTENDO VALORES DA NFE
        if (isset($valor->prod->qCom)) {
            $v_item_qCom =  $valor->prod->qCom;
        } else {
            $v_item_qCom = '0';
        }
        // OBTENDO VALORES DA NFE
        if (isset($valor->prod->vUnCom)) {
            $v_item_vUnCom =  $valor->prod->vUnCom;
        } else {
            $v_item_vUnCom = '0';
        }
        // OBTENDO VALORES DA NFE
        if (isset($valor->prod->vProd)) {
            $v_item_vProd =  $valor->prod->vProd;
        } else {
            $v_item_vProd = '0';
        }

        $v_item_in = strpos($v_sefaz_xml, "<vICMS>") + strlen("<vICMS>");
        $v_item_fim = strpos($v_sefaz_xml, "</vICMS>") - $v_item_in;
        $v_item_icms = substr($v_sefaz_xml, $v_item_in, $v_item_fim);
        if (strlen($v_item_icms) == 0) {
            $v_item_icms = "0,00";
        }

        $v_item_in = strpos($v_sefaz_xml, "<vPIS>") + strlen("<vPIS>");
        $v_item_fim = strpos($v_sefaz_xml, "</vPIS>") - $v_item_in;
        $v_item_pis = substr($v_sefaz_xml, $v_item_in, $v_item_fim);
        if (strlen($v_item_pis) == 0) {
            $v_item_pis = "0,00";
        }

        $v_dados .= ',{"v_emit_razao_social":"' . '-'
            . '","v_emit_end1":"' . '-'
            . '","v_emit_end2":"' . '-'
            . '","v_emit_end3":"' . '-'
            . '","v_danfe_tipo":"' . '-'
            . '","v_danfe_num":"' . '-'
            . '","v_danfe_serie":"' . '-'
            . '","v_danfe_chave":"' . '-'
            . '","v_emit_natureza":"' . '-'
            . '","v_emit_prot_lab":"' . '-'
            . '","v_emit_prot_num":"' . '-'
            . '","v_emit_inscr_est":"' . '-'
            . '","v_emit_cnpj":"' . '-'
            . '","v_dest_razao_social":"' . '-'
            . '","v_dest_cnpj":"' . '-'
            . '","v_dest_dt_emit":"' . '-'
            . '","v_dest_end":"' . '-'
            . '","v_dest_bairro":"' . '-'
            . '","v_dest_cep":"' . '-'
            . '","v_dest_municip":"' . '-'
            . '","v_dest_uf":"' . '-'
            . '","v_dest_fone":"' . '-'
            . '","v_dest_ie":"' . '-'
            . '","v_dest_dt_saida":"' . '-'
            . '","v_dest_hr_saida":"' . '-'
            . '","v_duplicatas":"' . '-'
            . '","v_calc_bc_icms":"' . '-'
            . '","v_calc_icms":"' . '-'
            . '","v_calc_bc_icms_st":"' . '-'
            . '","v_calc_icms_dson":"' . '-'
            . '","v_calc_val_tt_prod":"' . '-'
            . '","v_calc_frete":"' . '-'
            . '","v_calc_seguro":"' . '-'
            . '","v_calc_outras_desp":"' . '-'
            . '","v_calc_ipi":"' . '-'
            . '","v_calc_val_aprox_trib":"' . '-'
            . '","v_calc_val_tt_nota":"' . '-'
            . '","v_calc_desconto":"' . '-'
            . '","v_calc_pis":"' . '-'
            . '","v_calc_cofins":"' . '-'
            . '","v_calc_fcp":"' . '-'
            . '","v_transp_razao_social":"' . '-'
            . '","v_transp_frete_conta":"' . '-'
            . '","v_transp_placa":"' . '-'
            . '","v_transp_placa_uf":"' . '-'
            . '","v_transp_cnpj":"' . '-'
            . '","v_transp_end":"' . '-'
            . '","v_transp_cidade":"' . '-'
            . '","v_transp_uf":"' . '-'
            . '","v_transp_ie":"' . '-'
            . '","v_transp_quant":"' . '-'
            . '","v_transp_especie":"' . '-'
            . '","v_transp_marca":"' . '-'
            . '","v_transp_num":"' . '-'
            . '","v_transp_pb":"' . '-'
            . '","v_transp_pl":"' . '-'
            . '","v_dados_adicionais":"' . '-'
            . '","v_item_cProd":"' . $v_item_cProd
            . '","v_item_xProd":"' . $v_item_xProd
            . '","v_item_NCM":"' . $v_item_NCM
            . '","v_item_CST":"' . str_pad($v_CST_ICMS, 3, '0', STR_PAD_LEFT)
            . '","v_item_CFOP":"' . $v_item_CFOP
            . '","v_item_uTrib":"' . $v_item_uTrib
            . '","v_item_qCom":"' . $v_item_qCom
            . '","v_item_vUnCom":"' . round($v_item_vUnCom, 2)
            . '","v_item_vProd":"' . $v_item_vProd
            . '","v_item_calc_icms":"' . $v_item_bicms
            . '","v_item_valor_icms":"' . $v_item_vicms
            . '","v_item_valor_ipi":"' . $v_item_pis
            . '","v_item_aliq_icms":"' . $v_item_aliqicms
            . '","v_item_aliq_ipi":"' . $v_item_pIPI
            . '","v_item_analise_texto":"' . '-'
            . '"}';
    }

    $v_dados .= ']';


    echo $v_dados;
}



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "PDF_LISTA_NFE") {

    $v_pdf_titulo = addslashes($_POST["v_pdf_titulo"]);
    $v_pdf_logo = addslashes($_POST["v_pdf_logo"]);
    $v_pdf_orientacao = addslashes($_POST["v_pdf_orientacao"]);

    $v_cnpj = explode("|", addslashes($_POST["v_empresa"]))[0];
    $v_empresa_rs = addslashes($_POST["v_empresa_rs"]);
    $v_db_emp1 = explode("|", addslashes($_POST["v_empresa"]))[1];
    $v_db_emp = str_pad($v_db_emp1, 4, '0', STR_PAD_LEFT);
    $v_filtro_analises = addslashes($_POST["v_filtro_analises"]);
    $v_filtro_manifesto = addslashes($_POST["v_filtro_manifesto"]);
    $v_filtro_analises_info = addslashes($_POST["v_filtro_analises_info"]);
    $v_filtro_dt_in = addslashes($_POST["v_filtro_dt_in"]);
    $v_filtro_dt_fim = addslashes($_POST["v_filtro_dt_fim"]);
    $v_tipo = addslashes($_POST["v_tipo"]);
    $v_filtro_ano_in = substr($v_filtro_dt_in, 0, 4);
    $v_filtro_ano_fim = substr($v_filtro_dt_fim, 0, 4);

    $date = new DateTime();
    $data_atual = $date->format('d/m/Y H:i');


    $v_pdf_html = '
    <table style="border-collapse: collapse; width: 100%; height: 56px;" border="1">
    <tbody>
    <tr style="font-size: 12px; background-color: #EEE8AA;">
    <th style="height: 25px; width: 12%; text-align: center;"><strong>DATA IMPRESSÃO</strong></th>
    <th style="height: 25px; width: 15%; text-align: center;"><strong>PERÍODO</strong></th>
    <th style="height: 25px; width: 12%; text-align: center;"><strong>CNPJ / CPF</strong></th>
    <th style="height: 25px; width: 40%; text-align: left; padding-left: 10px;"><strong>RAZÃO SOCIAL</strong></th>
    <th style="height: 25px; width: 21%; text-align: center;"><strong>SITUAÇÃO</strong></th>
    </tr>
    <tr style="font-size: 12px;">
    <td style="height: 25px; width: 12%; text-align: center;">' . $data_atual . '</td>
    <td style="height: 25px; width: 15%; text-align: center;">' . $v_dup_dVenc = implode('/', array_reverse(explode('-', $v_filtro_dt_in))) . ' - ' . implode('/', array_reverse(explode('-', $v_filtro_dt_fim))) . '</td>
    <td style="height: 25px; width: 12%; text-align: center;">' . $v_cnpj . '</td>
    <td style="height: 25px; width: 40%; text-align: left; padding-left: 10px;">' . substr($v_empresa_rs, 0, -21) . '</td>
    <td style="height: 25px; width: 21%; text-align: center;">' . $v_filtro_analises_info . '</td>
    </tr>
    </tbody>
    </table>';



    $filtro = "";
    if ($v_filtro_analises == "T") {
        $filtro = "";
    } else {
        $filtro = "and analise_status = '" . $v_filtro_analises . "' ";
    }

    if ($v_filtro_manifesto != "T") {
        $filtro .= "and nfe_manifesto = '" . $v_filtro_manifesto . "' ";
    }

    if ($v_tipo == 1) {
        $filtro .= "and nfe_tipo = 1 and cnpj_dest = " . $v_cnpj . " ";
    } else {
        $filtro .= "and nfe_tipo = 2 and cnpj_emit = " . $v_cnpj . " ";
    }

    if ($v_filtro_ano_in == $v_filtro_ano_fim) {
        $v_sql = "SELECT chave_01, chave_02, chave_03, nfe_tipo, xml_nfe FROM db_emp_" . $v_db_emp . ".t_fisco_" . $v_filtro_ano_in . "_nfeproc \n"
            . "WHERE dt_emit BETWEEN '" . $v_filtro_dt_in . " 00:00:00' AND '" . $v_filtro_dt_fim . " 23:59:59' and nfe_tipo = " . $v_tipo . $filtro . " ORDER BY dt_emit DESC";
    } else {
        $v_sql = "select tab_temp.dt_emit, tab_temp.chave_01, tab_temp.chave_02, tab_temp.chave_03, tab_temp.nfe_tipo, tab_temp.xml_nfe from 
        (SELECT dt_emit, chave_01, chave_02, chave_03, nfe_tipo, xml_nfe FROM db_emp_" . $v_db_emp . ".t_fisco_2020_nfeproc \n"
        ."WHERE dt_emit >= '" . $v_filtro_dt_in . " 00:00:00' and nfe_tipo = " . $v_tipo . $filtro . " "
        ."UNION 
        SELECT dt_emit, chave_01, chave_02, chave_03, nfe_tipo, xml_nfe FROM db_emp_" . $v_db_emp . ".t_fisco_2021_nfeproc \n"
        ."WHERE dt_emit <= '" . $v_filtro_dt_fim . " 23:59:59' and nfe_tipo = " . $v_tipo . $filtro . " ) tab_temp "
        ."ORDER BY tab_temp.dt_emit DESC";
    }
    
    $result = pg_query($conn, $v_sql);
    while ($row = pg_fetch_assoc($result)) {

        $v_ano = "20" . substr($row["chave_01"], 2, 2);

        $v_sql2 = "SELECT TO_CHAR(data_hora,'DD/MM/YYYY HH:MI') as data_hora, id_user, analise_status, analise_texto 
        FROM db_emp_" . $v_db_emp . ".t_fisco_" . $v_ano . "_nfe_analises 
        WHERE chave_01 = '" . $row["chave_01"] . "' AND chave_02 = '" . $row["chave_02"] . "' AND chave_03 = '" . $row["chave_03"] . "' AND nfe_tipo = " . $row["nfe_tipo"] . "
        order by id desc";
        $result2 = pg_query($conn, $v_sql2);

        $v_analise_texto = "";
        while ($row2 = pg_fetch_assoc($result2)) {

            $analise_status = "";
            switch ($row2["analise_status"]) {
                case "-":
                    $analise_status = "";
                    break;
                case "P":
                    $analise_status = " - NÃO LANÇADA";
                    break;
                case "S":
                    $analise_status = " - ANALISADA COM SUCESSO";
                    break;
                case "N":
                    $analise_status = " - ANALISADA COM RESSALVA";
                    break;
            }

            if (!empty($analise_status)) {
                if (!empty($v_analise_texto)) {
                    $v_analise_texto .= "<br>";
                }
                $v_analise_texto .= $row2["data_hora"] . $analise_status . "<br>";
                $v_analise_texto .= $row2["analise_texto"];
            }
        }




        $v_sefaz_xml = simplexml_load_string($row["xml_nfe"]);

        // OBTENDO VALORES DA NFE
        if (isset($v_sefaz_xml->protNFe->infProt->chNFe)) {
            $v_xml_chave = $v_sefaz_xml->protNFe->infProt->chNFe;
        } else {
            $v_xml_chave = "";
        }
        // OBTENDO VALORES DA NFE
        if (isset($v_sefaz_xml->NFe->infNFe->emit->CNPJ)) {
            $v_xml_cnpj = $v_sefaz_xml->NFe->infNFe->emit->CNPJ;
            $bloco_1 = substr($v_xml_cnpj, 0, 2);
            $bloco_2 = substr($v_xml_cnpj, 2, 3);
            $bloco_3 = substr($v_xml_cnpj, 5, 3);
            $bloco_4 = substr($v_xml_cnpj, 8, 4);
            $digito_verificador = substr($v_xml_cnpj, -2);
            $v_xml_cnpj = $bloco_1 . "." . $bloco_2 . "." . $bloco_3 . "/" . $bloco_4 . "-" . $digito_verificador;
        } else {
            $v_xml_cnpj = "";
        }
        // OBTENDO VALORES DA NFE
        if (isset($v_sefaz_xml->NFe->infNFe->emit->CPF)) {
            $v_xml_cnpj = $v_sefaz_xml->NFe->infNFe->emit->CPF;
            $bloco_1 = substr($v_xml_cnpj, 0, 3);
            $bloco_2 = substr($v_xml_cnpj, 3, 3);
            $bloco_3 = substr($v_xml_cnpj, 6, 3);
            $dig_verificador = substr($v_xml_cnpj, -2);
            $v_xml_cnpj = $bloco_1 . "." . $bloco_2 . "." . $bloco_3 . "-" . $dig_verificador;
        }

        // OBTENDO VALORES DA NFE
        if (isset($v_sefaz_xml->NFe->infNFe->emit->xNome)) {
            $v_xml_nome = $v_sefaz_xml->NFe->infNFe->emit->xNome;
        } else {
            $v_xml_nome = "";
        }
        // OBTENDO VALORES DA NFE
        if (isset($v_sefaz_xml->NFe->infNFe->ide->nNF)) {
            $v_xml_num = $v_sefaz_xml->NFe->infNFe->ide->nNF;
        } else {
            $v_xml_num = "";
        }
        // OBTENDO VALORES DA NFE
        if (isset($v_sefaz_xml->NFe->infNFe->ide->serie)) {
            $v_xml_serie = $v_sefaz_xml->NFe->infNFe->ide->serie;
        } else {
            $v_xml_serie = "";
        }
        // OBTENDO VALORES DA NFE
        if (isset($v_sefaz_xml->NFe->infNFe->ide->dhEmi)) {
            $v_xml_dt_emit = $v_sefaz_xml->NFe->infNFe->ide->dhEmi;
            $v_xml_dt_emit = str_replace("T", " ", substr($v_xml_dt_emit, 0, -15));
            $v_xml_dt_emit = implode('/', array_reverse(explode('-', $v_xml_dt_emit)));
        } else {
            $v_xml_dt_emit = "";
        }
        // OBTENDO VALORES DA NFE
        if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vNF)) {
            $v_xml_total = $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vNF;
        } else {
            $v_xml_total = "";
        }

        $v_pdf_html .= '
        <br>
        <table style="border-collapse: collapse; width: 100%; height: 56px;" border="1">
        <tbody>
        <tr style="font-size: 12px; background-color: #FAFAD2;">
        <th style="height: 25px; width: 12%; text-align: center;"><strong>CPF/CNPJ EMITENTE</strong></td>
        <th style="height: 25px; width: 25%; text-align: center;"><strong>RAZÃO SOCIAL EMITENTE</strong></td>
        <th style="height: 25px; width: 4%; text-align: center;"><strong>Nº</strong></td>
        <th style="height: 25px; width: 4%; text-align: center;"><strong>SÉRIE</strong></td>
        <th style="height: 25px; width: 8%; text-align: center;"><strong>EMISSÃO</strong></td>
        <th style="height: 25px; width: 9%; text-align: center;"><strong>TOTAL R$</strong></td>
        <th style="height: 25px; width: 10%; text-align: center;"><strong>MANIFESTO</strong></td>
        <th style="height: 25px; width: 28%; text-align: left; padding-left: 10px;"><strong>CHAVE</strong></td>
        </tr>
        <tr style="font-size: 12px;">
        <td style="height: 25px; width: 12%; text-align: center;">' . $v_xml_cnpj . '</td>
        <td style="height: 25px; width: 25%; text-align: center;">' . $v_xml_nome . '</td>
        <td style="height: 25px; width: 4%; text-align: center;">' . $v_xml_num . '</td>
        <td style="height: 25px; width: 4%; text-align: center;">' . $v_xml_serie . '</td>
        <td style="height: 25px; width: 8%; text-align: center;">' . $v_xml_dt_emit . '</td>
        <td style="height: 25px; width: 9%; text-align: center;">' . $v_xml_total . '</td>
        <td style="height: 25px; width: 10%; text-align: center;">CIÊNCIA</td>
        <td style="height: 25px; width: 28%; text-align: left; padding-left: 10px;">' . $v_xml_chave . '</td>
        </tr>';

        if (!empty($v_analise_texto)) {
            $v_pdf_html .= '<tr style="font-size: 12px;">
        <td colspan="8" style="height: auto; text-align: left; padding-left: 10px;">' . $v_analise_texto . '</td>
        </tr>';
        }

        $v_pdf_html .= '
        </tbody>
        </table>';
    }
    pg_close($conn);

    $_SESSION["vs_pdf_html"] = $v_pdf_logo . "|" . $v_pdf_titulo . "|" . $v_pdf_orientacao . "|" . $v_pdf_html;
}
