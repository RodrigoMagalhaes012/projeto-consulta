<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (strpos($_SESSION["vs_array_access"], "T0029") > 0) {

    $v_pos = strpos($_SESSION["vs_array_access"], "T0029");
    $v_up_hol_ler = substr($_SESSION["vs_array_access"], $v_pos + 8, 1);
    $v_up_hol_criar = substr($_SESSION["vs_array_access"], $v_pos + 12, 1);
    $v_up_hol_gravar = substr($_SESSION["vs_array_access"], $v_pos + 16, 1);
    $v_up_hol_excluir = substr($_SESSION["vs_array_access"], $v_pos + 20, 1);
}



$arquivo_tmp = $_FILES['arquivo']['tmp_name'];

$dados = file($arquivo_tmp);

$v_id_empresa = intval($_SESSION["vs_id_empresa"]);
$v_rubricas = "";
$v_verbas = "";
$v_bases = "";
$v_competencia_hol = "";
$v_tipo_folha_hol = "";
$v_cnpj_emp = "";
$consulta_emp = "";
$layout_arquivo = "";



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




////////////////////////////////////////////////////////////////
///////ARRAY PARA BUSCAR ID DO COLABORADOR COM A MATRICULA//////
////////////////////////////////////////////////////////////////

$colaborador = array();
$v_sql = "SELECT matricula, id  FROM db_adm_rh.t_rh_colaborador WHERE id_empresa = {$v_id_empresa} ";
$result2 = pg_query($conn, $v_sql);

while ($row2 = pg_fetch_assoc($result2)) {
    $colaborador[$row2["matricula"]] = $row2["id"];
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

//BUSCA A COMPETENCIA NO ARQUIVO
foreach ($dados as $linha) {
    $linha = utf8_encode($linha);
    $linha = trim($linha);
    $v_linha_txt = explode(';', $linha);
    //VERIFICANDO A COMPETENCIA
    if ($v_linha_txt[0] == "t_rh_holerite") {
        $v_competencia_hol = $v_linha_txt[1];
    }
}

//BUSCA A COMPETENCIA NO ARQUIVO
foreach ($dados as $linha) {
    $linha = utf8_encode($linha);
    $linha = trim($linha);
    $v_linha_txt = explode(';', $linha);
    //VERIFICANDO A COMPETENCIA
    if ($v_linha_txt[0] == "t_rh_holerite") {
        $v_tipo_folha_hol = $v_linha_txt[2];
    }
}

//BUSCA CNPJ NO ARQUIVO
foreach ($dados as $linha) {
    $linha = utf8_encode($linha);
    $linha = trim($linha);
    $v_linha_txt = explode(';', $linha);
    //VERIFICANDO A COMPETENCIA
    if ($v_linha_txt[0] == "t_rh_holerite_bases") {
        $v_cnpj_carga = $v_linha_txt[17];
    }
}




//VERIFICA SE O LAYOUT DO ARQUIVO ESTÁ CORRETO
if ($layout_arquivo == "holerite") {


    ////////////////////////////////////////////////////////////////
    ///////ARRAY PARA BUSCAR COMPETENCIA DE FOLHA IMPORTADA  //////
    ////////////////////////////////////////////////////////////////

    $verifica_import = array();
    $v_sql = "SELECT competencia, tipo_folha  
               FROM db_adm_rh.t_rh_holerite 
               WHERE competencia = {$v_competencia_hol} 
                 and id_empresa =  {$_SESSION["vs_id_empresa"]}
                 and tipo_folha = {$v_tipo_folha_hol};";
    $result2 = pg_query($conn, $v_sql);

    //VERIFICA SE ESTÁ NA EMPRESA CORRETA
    if ((int)$v_cnpj_carga == (int)$v_cnpj_emp) {

        foreach ($dados as $linha) {
            $linha = utf8_encode($linha);
            $linha = trim($linha);
            $v_linha_txt = explode(';', $linha);

            //RECEBENDO DADOS DE RUBRICAS DO ARQUIVOS
            if ($v_linha_txt[0] == "t_rh_holerite_rubricas") {
                $v_rubricas .= "(" . $v_linha_txt[1] . "," . $v_linha_txt[2] . ",'" . $v_linha_txt[3] .  "'," . $v_linha_txt[4] . "," . $v_linha_txt[5] . ", 1), ";
            }

            //RECEBENDO DADOS DA FOLHA DO ARQUIVO
            if ($v_linha_txt[0] == "t_rh_holerite") {

                $verifica_import = array();
                $v_sql = "SELECT competencia, tipo_folha  
                           FROM db_adm_rh.t_rh_holerite 
                           WHERE competencia = {$v_competencia_hol} 
                           and id_empresa =  {$_SESSION["vs_id_empresa"]}                           
                           and tipo_folha = {$v_tipo_folha_hol};";

                
                $result2 = pg_query($conn, $v_sql);
                

                if (pg_num_rows($result2) == 0) {

                    if (array_key_exists($v_linha_txt[3], $colaborador)) {
                        $id =  $colaborador[$v_linha_txt[3]];
                        $v_verbas .= "(" . $v_linha_txt[1] . "," . $v_linha_txt[2] . "," . $v_linha_txt[3] . "," . $v_linha_txt[4] . "," . $v_linha_txt[5] . "," . $v_linha_txt[6] . "," .  $id . "," . $v_id_empresa . ", 1), ";

                        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Importação realizada com sucesso!"}';
                    }
                } else {
                    $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Já existe uma importação para essa competencia, favor excluir e tentar novamente!"}';
                    // break;
                }
            }



            //RECEBENDO DADOS DE BASE DA FOLHA DO ARQUIVO
            if ($v_linha_txt[0] == "t_rh_holerite_bases") {

                $verifica_import = array();
                $v_sql = "SELECT competencia, tipo_folha  
                            FROM db_adm_rh.t_rh_holerite 
                            WHERE competencia = {$v_competencia_hol} 
                            and id_empresa =  {$_SESSION["vs_id_empresa"]}
                            and tipo_folha = {$v_tipo_folha_hol};";
                $result2 = pg_query($conn, $v_sql);

                if (pg_num_rows($result2) == 0) {
                    if (array_key_exists($v_linha_txt[3], $colaborador)) {
                        $v_bases .= "(" . $v_linha_txt[1] . "," . $v_linha_txt[2] . "," . $v_linha_txt[3] . "," . $v_linha_txt[4] . "," . $v_linha_txt[5] . "," . $v_linha_txt[6] . "," . $v_linha_txt[7] . "," . $v_linha_txt[8] . "," . $v_linha_txt[9] . "," . $v_linha_txt[10] . "," . $v_linha_txt[11] . "," . $v_linha_txt[12] . "," . $v_linha_txt[13] . "," . $v_linha_txt[14] . "," . $v_linha_txt[15] . "," . $v_linha_txt[16] . "," . $v_id_empresa . "), ";
                    }
                } else {
                    $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Já existe uma importação para essa competencia, favor excluir e tentar novamente!"}';
                    // break;
                }
            }
        }


        if ($v_verbas  && $v_bases != "") {

            $v_rubricas = " VALUES " . substr($v_rubricas, 0, -2) . " ON CONFLICT DO NOTHING" . ";";
            $v_sql0 = "INSERT INTO db_adm_rh.t_rh_holerite_rubricas (rubrica, tipo, descricao, caracteristica, tipo_lancamento, id_tabela)" . $v_rubricas;
            $result1 = pg_query($conn, $v_sql0);



            $v_bases = " VALUES " . substr($v_bases, 0, -2) . " ON CONFLICT DO NOTHING" .  ";";
            $v_sql2 = "INSERT INTO db_adm_rh.t_rh_holerite_bases (Competencia, Tipo_folha, matricula, Data_pagamento, Horas_mes, Dependentes_ir, Dependentes_sf, Salario_base, Base_fgts, Valor_inss,Base_inss, Valor_fgts,  Base_irrf, Total_vencimentos, Total_descontos, Total_liquido, id_empresa)" . $v_bases;
            $result2 = pg_query($conn, $v_sql2);


            $v_verbas = " VALUES " . substr($v_verbas, 0, -2) . " ON CONFLICT DO NOTHING" .  ";";
            $v_sql3 = "INSERT INTO  db_adm_rh.t_rh_holerite (Competencia, Tipo_folha, matricula, Rubrica, Referencia, Valor, id_colaborador, id_empresa, id_tabela_rubrica)" . $v_verbas;
            $result3 = pg_query($conn, $v_sql3);


            // GRAVANDO O HISTORICO DE IMPORTAÇÃO
            $timeZone = new DateTimeZone('America/Sao_Paulo');
            $v_data = new DateTime('now', $timeZone);
            $v_data = $v_data->format('Y-m-d H:i:s');

            $v_sql = "INSERT INTO db_adm_rh.t_rh_hist_holerite_upload 
                       (id_user, data_hora, competencia, status, tipo_folha, id_emp) 
                       VALUES ({$_SESSION["vs_id"]}, '{$v_data}', {$v_competencia_hol}, '1', {$v_tipo_folha_hol}, {$v_id_empresa}) 
                       ON CONFLICT DO NOTHING;";
            $result = pg_query($conn, $v_sql);

            // GRAVANDO O LOG DE IMPORTAÇÃO
            $v_sql = "INSERT INTO db_adm_rh.t_log
                        (id_user, data_hora, id_empresa, id_processo, descricao)
                        VALUES({$_SESSION["vs_id"]}, '$v_data', {$_SESSION["vs_id_empresa"]}, 3, 'Importação de Holerites.')
                        ON CONFLICT DO NOTHING;";
            $result = pg_query($conn, $v_sql);
        } else {
        }
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Você não está na empresa correta, favor verificar!"}';
    }
} else {
    $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"O Layout do arquivo está incorreto, favor verificar!"}';
}

pg_close($conn);


$v_json = json_encode($json_msg);
echo $v_json;
