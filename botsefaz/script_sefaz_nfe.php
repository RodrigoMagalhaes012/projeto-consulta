<?php
set_time_limit(0);
include_once("../class/php/class_conect_db.php");
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
// OBTENDO A AÇÃO A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// INICIANDO A EXECUÇÃO DO SCRIPT
if ($v_acao == "START_SCRIPT") {

  // ###################################################### //
  // VALIDANDO REGRAS PARA A EXECUÇÃO DO SCRIPT DATA X HORA
  // ###################################################### //
  $hoje = getdate();
  $cod_dia_samana = $hoje["wday"];
  $exec_permissao = "N";
  date_default_timezone_set('America/Sao_Paulo');
  $hora_atual = strtotime(date('H:i:s'));
  $hora_limite_in = strtotime("00:00:00");
  $hora_limite_fim = strtotime("00:00:00");

  if ($cod_dia_samana == 0 || $cod_dia_samana == 6) {
    // VALIDANDO JANELA PARA EXECUÇÃO NOS FINAIS DE SEMANA
    $hora_limite_in = strtotime("05:30:00");
    $hora_limite_fim = strtotime("22:00:00");
  } else {
    // VALIDANDO JANELA PARA EXECUÇÃO NOS DIAS ÚTEIS
    $hora_limite_in = strtotime("05:30:00");
    $hora_limite_fim = strtotime("22:00:00");
  }
  // VALIDANDO JANELA PARA EXECUÇÃO NOS HORÁRIOS
  if ($hora_atual >= $hora_limite_in && $hora_atual <= $hora_limite_fim) {
    $exec_permissao = "S";
  }



  // ###################################################### //
  // VERIFICANDO A EXECUÇÃO DE OUTRO SCRIPT
  // ###################################################### //
  if ($exec_permissao == "S") {
    $v_sql = "select count(fisco_status_coleta_nfe) as tt from db_adm.t_empresas 
  where fisco_status_coleta_nfe = 2 and fisco_nsu_log >= current_timestamp - interval '5 minutes'";
    pg_query($conn, $v_sql);
    $v_result_check = pg_query($conn, $v_sql);
    if ($v_row_check = pg_fetch_assoc($v_result_check)) {
      if ($v_row_check["tt"] > 0) {
        $exec_permissao = "N";
      }
    }
  }



  // ###################################################### //
  // REGISTRANDO O INÍCIO DA EXECUÇÃO DO SCRIPT
  // ###################################################### //
  if ($exec_permissao == "S") {

    $v_data_atual = date("d/m/Y");
    print("EXECTANDO IMPORTAÇÃO DE NFE DA SEFAZ: " . $v_data_atual);
    // ###################################################### //
    // REGISTRANDO O INÍCIO DA EXECUÇÃO DO SCRIPT
    // ###################################################### //
    $v_sql_log = "INSERT INTO db_adm.t_fisco_log (cnpj, nsu, cod_retorno, motivo, msg_curl) VALUES(0, 0, 1, 'INÍCIO DA EXECUÇÃO DO SCRIPT DE COLETA DE NFEs', 'INÍCIO DA EXECUÇÃO DO SCRIPT DE COLETA DE NFEs')";
    pg_query($conn, $v_sql_log);



    // ###################################################### //
    // DETERMINANDO VALOR PADRÃO PARA VARIÁVEIS
    // ###################################################### //
    $v_error_id = 0;
    $v_error_motivo = "FALHA";
    $v_sleep = 4;
    $v_sleep_break = 0;
    $v_sleep_break_num = 0;
    $v_break_total = "false";
    $v_break_empresa = "false";
    $v_ano_atual = date("Y");
    $v_mes_atual = date("m");
    $v_ano_passado = $v_ano_atual - 1;



    // ###################################################### //
    // CARREGANDO ARRAY DE EMPRESAS
    // ###################################################### //
    $v_array_empresas = array();
    $v_sql = "SELECT LPAD(CAST(db_emp AS VARCHAR),4,'0') AS db_emp, CASE WHEN tipo in (5,6) then LPAD(CAST(cnpj AS VARCHAR),11,'0') else LPAD(CAST(cnpj AS VARCHAR),14,'0') end AS cnpj_emp FROM db_adm.t_empresas";
    $v_result_empresas = pg_query($conn, $v_sql);
    while ($v_row_empresas = pg_fetch_assoc($v_result_empresas)) {
      $v_array_empresas[$v_row_empresas["cnpj_emp"]] = str_pad($v_row_empresas["db_emp"], 4, '0', STR_PAD_LEFT);
    }



    // ###################################################### //
    // CARREGANDO ARRAY DE RETORNOS DA SEFAZ
    // ###################################################### //
    $v_array_retornos = array();
    $v_sql = "SELECT id, csleep, break_total, break_empresa FROM db_adm.t_fisco_lista_retornos WHERE tipo = 'ERROR' ORDER BY id";
    $v_result_retornos = pg_query($conn, $v_sql);
    while ($v_row_retorno = pg_fetch_assoc($v_result_retornos)) {
      $v_array_retornos[$v_row_retorno["id"]] = $v_row_retorno["csleep"] . ";" . $v_row_retorno["break_total"] . ";" . $v_row_retorno["break_empresa"];
    }



    // ###################################################### //
    // ZERANDO CONTADORES
    // ###################################################### //
    $v_sql = "UPDATE db_adm.t_empresas SET fisco_script_order = fisco_status_coleta_nfe";
    pg_query($conn, $v_sql);
    $v_sql = "UPDATE db_adm.t_empresas SET qtd_error = 0, qtd_nfeproc = 0, qtd_proceventonfe = 0, fisco_status_coleta_nfe = 0";
    pg_query($conn, $v_sql);


    // ###################################################### //
    // IDENTIFICANDO EMPRESAS PARA COLETA
    // ###################################################### //
    $v_sql = "update db_adm.t_empresas t_empresas set 
  fisco_status_coleta_nfe = 1, fisco_nsu_log = current_timestamp 
  from 
  ((select t_empresas.id 
  FROM db_adm.t_empresas 
  JOIN db_adm.t_fisco_lista_nfe_cuf ON db_adm.t_empresas.uf = t_fisco_lista_nfe_cuf.nome 
  WHERE t_empresas.modulo_fisco = 'S' and t_empresas.tipo in (1,2,3,5) and fisco_cert_dt_validade > current_timestamp) 
  UNION 
  (SELECT 
  t_empresas.id 
  FROM db_adm.t_empresas 
  JOIN db_adm.t_fisco_lista_nfe_cuf ON db_adm.t_empresas.uf = t_fisco_lista_nfe_cuf.nome 
  JOIN (select db_emp, fisco_certi_senha, fisco_cert_pem FROM db_adm.t_empresas where tipo in (1,2) and fisco_cert_dt_validade > current_timestamp) tab_emp_certs 
  ON db_adm.t_empresas.db_emp = tab_emp_certs.db_emp 
  WHERE t_empresas.modulo_fisco = 'S' and t_empresas.tipo in (4,6))) tempx 
  WHERE t_empresas.id = tempx.id";
    pg_query($conn, $v_sql);



    // ###################################################### //
    // LISTANDO EMPRESAS PARA CONSULTA JUNTO A SEFAZ
    // ###################################################### //
    $v_sql = "(SELECT 
  CASE WHEN t_empresas.tipo in (5,6) then LPAD(CAST(t_empresas.cnpj AS VARCHAR),11,'0') else LPAD(CAST(t_empresas.cnpj AS VARCHAR),14,'0') end as cnpj, 
  CASE WHEN t_empresas.tipo in (5,6) then 'CPF' else 'CNPJ' end as cnpj_tipo_doc, 
  LPAD(CAST(t_empresas.db_emp AS VARCHAR),4,'0') AS db_emp, 
  t_empresas.tipo, 
  t_empresas.fisco_script_order, 
  t_empresas.nome, 
  t_empresas.fisco_certi_senha, 
  t_empresas.fisco_cert_pem, 
  t_empresas.fisco_nsu, 
  t_fisco_lista_nfe_cuf.id AS cuf 
  FROM db_adm.t_empresas 
  JOIN db_adm.t_fisco_lista_nfe_cuf ON db_adm.t_empresas.uf = t_fisco_lista_nfe_cuf.nome 
  WHERE t_empresas.tipo in (1,2,3,5) and fisco_status_coleta_nfe = 1) 
  UNION 
  (SELECT 
  CASE WHEN t_empresas.tipo in (5,6) then LPAD(CAST(t_empresas.cnpj AS VARCHAR),11,'0') else LPAD(CAST(t_empresas.cnpj AS VARCHAR),14,'0') end as cnpj, 
  CASE WHEN t_empresas.tipo in (5,6) then 'CPF' else 'CNPJ' end as cnpj_tipo_doc, 
  LPAD(CAST(t_empresas.db_emp AS VARCHAR),4,'0') AS db_emp, 
  t_empresas.tipo, 
  t_empresas.fisco_script_order, 
  t_empresas.nome, 
  tab_emp_certs.fisco_certi_senha, 
  tab_emp_certs.fisco_cert_pem, 
  t_empresas.fisco_nsu, 
  t_fisco_lista_nfe_cuf.id AS cuf 
  FROM db_adm.t_empresas 
  JOIN db_adm.t_fisco_lista_nfe_cuf ON db_adm.t_empresas.uf = t_fisco_lista_nfe_cuf.nome 
  JOIN (select db_emp, fisco_certi_senha, fisco_cert_pem FROM db_adm.t_empresas where tipo in (1,2) and fisco_cert_dt_validade > current_timestamp) tab_emp_certs 
  ON db_adm.t_empresas.db_emp = tab_emp_certs.db_emp 
  WHERE t_empresas.tipo in (4,6) and fisco_status_coleta_nfe = 1) 
  ORDER BY fisco_script_order desc, tipo asc, nome asc";
    $v_result_empresas = pg_query($conn, $v_sql);
    while ($v_row_empresas = pg_fetch_assoc($v_result_empresas)) {

      // ###################################################### //
      // PARANDO O SCRIPT EM CASO DE ERRO SEVERO
      // ###################################################### //
      if ($v_break_total == "true") {
        $v_sql = "update db_adm.t_empresas set fisco_status_coleta_nfe = 4, fisco_nsu_log = current_timestamp WHERE fisco_status_coleta_nfe in (1,2)";
        pg_query($conn, $v_sql);
        exit;
      }

      // ###################################################### //
      // COLETANDO DADOS SOBRE A EMPRESA
      // ###################################################### //
      $v_break_empresa = "false";
      $v_cnpj_tipo = $v_row_empresas["tipo"];
      $v_cnpj_emp = $v_row_empresas["cnpj"];
      $v_cnpj_tipo_doc = $v_row_empresas["cnpj_tipo_doc"];
      $v_db_emp = $v_row_empresas["db_emp"];
      $v_certPassword = $v_row_empresas["fisco_certi_senha"];
      $v_cuf = $v_row_empresas["cuf"];
      $v_nfe_nsu = $v_row_empresas["fisco_nsu"];



      // ###################################################### //
      // CLASSIFICANDO A EMPRESA COMO PROCESSANDO
      // ###################################################### //
      $v_sql = "update db_adm.t_empresas set fisco_status_coleta_nfe = 2, fisco_nsu_log = current_timestamp WHERE cnpj = " . $v_cnpj_emp;
      pg_query($conn, $v_sql);



      // ###################################################### //
      // CONSULTANDO CERTIFICADO PEM E CARREGANDO NA MEMÓRIA
      // ###################################################### //
      $v_cert_pem_file = tmpfile();
      fwrite($v_cert_pem_file, $v_row_empresas["fisco_cert_pem"]);
      $v_cert_pem_path = stream_get_meta_data($v_cert_pem_file);
      $v_cert_pem_path = $v_cert_pem_path['uri'];


      // ###################################################### //
      // GERANDO A LISTA DE NFE DA EMPRESA
      // ###################################################### //
      while ($v_break_empresa == "false" && $v_break_total == "false") {

        // ###################################################### //
        // REALIZANDO A PAUSA
        // ###################################################### //
        sleep($v_sleep + $v_sleep_break);
        $v_sleep_break = 0;



        // ###################################################### //
        // CONSTRUINDO O XML DA REQUISIÇÃO //
        // ###################################################### //
        $v_xml = '<?xml version="1.0" encoding="UTF-8"?>'
          . '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://www.w3.org/2003/05/soap-envelope">'
          . '<soap:Header/>'
          . '<soap:Body>'
          . '<nfeDistDFeInteresse xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/NFeDistribuicaoDFe">'
          . '<nfeDadosMsg xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/NFeDistribuicaoDFe">'
          . '<distDFeInt xmlns="http://www.portalfiscal.inf.br/nfe" versao="1.01">'
          . '<tpAmb>1</tpAmb>'
          // . '<cUFAutor>' . $v_cuf . '</cUFAutor>'
          . '<' . $v_cnpj_tipo_doc . '>' . $v_cnpj_emp . '</' . $v_cnpj_tipo_doc . '>'
          . '<distNSU>'
          . '<ultNSU>' . str_pad($v_nfe_nsu, 15, '0', STR_PAD_LEFT) . '</ultNSU>'
          . '</distNSU>'
          . '</distDFeInt>'
          . '</nfeDadosMsg>'
          . '</nfeDistDFeInteresse>'
          . '</soap:Body>'
          . '</soap:Envelope>';
        $v_url = 'https://www1.nfe.fazenda.gov.br/NFeDistribuicaoDFe/NFeDistribuicaoDFe.asmx';
        $v_tamanho = strlen($v_xml);
        $v_parametros = array(
          'Content-Type: application/soap+xml;charset=utf-8',
          'SOAPAction: "nfeDistDFeInteresse"',
          "Content-length: $v_tamanho"
        );

        // ###################################################### //
        // EXECUTANDO A SOLICITAÇÃO VIA CURL
        // ###################################################### //
        $v_oCurl = curl_init();
        curl_setopt($v_oCurl, CURLOPT_URL, $v_url);
        curl_setopt($v_oCurl, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($v_oCurl, CURLOPT_TIMEOUT, 60);
        curl_setopt($v_oCurl, CURLOPT_VERBOSE, 1);
        curl_setopt($v_oCurl, CURLOPT_SSLVERSION, '1.00');
        curl_setopt($v_oCurl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($v_oCurl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($v_oCurl, CURLOPT_SSLCERT, $v_cert_pem_path);
        curl_setopt($v_oCurl, CURLOPT_SSLCERTTYPE, "PEM");
        curl_setopt($v_oCurl, CURLOPT_POST, 1);
        curl_setopt($v_oCurl, CURLOPT_POSTFIELDS, $v_xml);
        curl_setopt($v_oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($v_oCurl, CURLOPT_HTTPHEADER, $v_parametros);
        $v_resposta = curl_exec($v_oCurl);
        $v_info = curl_getinfo($v_oCurl, CURLINFO_HTTP_CODE); //informações da conexão
        $v_curl_error = curl_error($v_oCurl);
        $v_curl_errno = curl_errno($v_oCurl);
        curl_close($v_oCurl);



        // ###################################################### //
        // VERIFICANDO SE HOUVE RETORNO DE ERRO
        // ###################################################### //
        $v_error_id = 3000;
        $v_error_motivo = "OUTROS";

        if (substr($v_resposta, 0, 5) == "<?xml") {

          // ###################################################### //
          // CAPTURANDO O POSSÍVEL ERRO
          // ###################################################### //
          $v_campo = "cStat";
          $v_item_in = strpos($v_resposta, "<" . $v_campo . ">") + strlen("<" . $v_campo . ">");
          $v_item_fim = strpos($v_resposta, "</" . $v_campo . ">") - $v_item_in;
          $v_error_id = substr($v_resposta, $v_item_in, $v_item_fim);
          // OBTENDO O MOTIVO DO ERRO NO XML
          $v_campo = "xMotivo";
          $v_item_in = strpos($v_resposta, "<" . $v_campo . ">") + strlen("<" . $v_campo . ">");
          $v_item_fim = strpos($v_resposta, "</" . $v_campo . ">") - $v_item_in;
          $v_error_motivo = substr($v_resposta, $v_item_in, $v_item_fim);

          if ($v_item_in == 0) {
            $v_error_id = 3000;
            // OBTENDO O MOTIVO DO ERRO NO XML
            $v_item_in = strpos($v_resposta, "<soap:Text xml:lang=\"en\">") + strlen("<soap:Text xml:lang=\"en\">");
            $v_item_fim = strpos($v_resposta, "</soap:Text>") - $v_item_in;
            $v_error_motivo = substr($v_resposta, $v_item_in, $v_item_fim);
          }

          // ###################################################### //
          // CLASSIFICANDO O ERRO
          // ###################################################### //
          if (array_key_exists($v_error_id, $v_array_retornos)) {

            // ###################################################### //
            // SUCESSO PARA O CASO DE NÃO HAVEREM NOTAS FISCAIS
            // ###################################################### //
            if ($v_error_id == 137) {
              $v_sql = "update db_adm.t_empresas set fisco_status_coleta_nfe = 3, fisco_nsu_log = current_timestamp WHERE cnpj = " . $v_cnpj_emp;
              pg_query($conn, $v_sql);
              $v_break_empresa = "true";
            } else {

              // ###################################################### //
              // OBTENDO OS PARAMETROS DO RETORNO
              // ###################################################### //
              $v_retornos = explode(";", $v_array_retornos[$v_error_id]);
              $v_sleep_break = (int)$v_retornos[0];
              $v_break_total = $v_retornos[1];
              //$v_break_empresa = $v_retornos[2];
              $v_break_empresa = "true";

              // ###################################################### //
              // REGISTRANDO ERRO IDENTIFICADO PELA SEFAZ
              // ###################################################### //
              $v_curl_error .= $v_resposta;
              $v_sql_log = "INSERT INTO db_adm.t_fisco_log (cnpj, nsu, cod_retorno, motivo, msg_curl) VALUES(" . $v_cnpj_emp . ", " . $v_nfe_nsu . ", " . $v_error_id . ", '" . $v_error_motivo . "', '" . $v_curl_error . "')";
              pg_query($conn, $v_sql_log);

              // ###################################################### //
              // REGISTRANDO NÚMERO DE ERROS HOJE NA EMPRESA
              // ###################################################### //
              $v_sql = "UPDATE db_adm.t_empresas SET fisco_status_coleta_nfe = 5, fisco_nsu_log = current_timestamp, qtd_error = qtd_error + 1 WHERE cnpj = " . $v_cnpj_emp;
              pg_query($conn, $v_sql);
            }
          }
        } else {
          $v_break_empresa = "true";
          // ###################################################### //
          // REGISTRANDO ERRO NÃO IDENTIFICADO PELA SEFAZ
          // ###################################################### //
          $v_curl_error .= $v_resposta;
          $v_sql_log = "INSERT INTO db_adm.t_fisco_log (cnpj, nsu, cod_retorno, motivo, msg_curl) VALUES(" . $v_cnpj_emp . ", " . $v_nfe_nsu . ", " . $v_error_id . ", '" . $v_curl_error . "', '" . $v_curl_error . "')";
          pg_query($conn, $v_sql_log);

          //##############################################
          // REGISTRANDO NÚMERO DE ERROS HOJE NA EMPRESA
          //##############################################
          $v_sql = "UPDATE db_adm.t_empresas SET fisco_status_coleta_nfe = 5, fisco_nsu_log = current_timestamp, qtd_error = qtd_error + 1 WHERE cnpj = " . $v_cnpj_emp;
          pg_query($conn, $v_sql);
        }



        // ###################################################### //
        // CHECKANDO ERROS PARA CRIAR A ARRAY DO XML
        // ###################################################### //
        if ($v_break_total == "false" && $v_break_empresa == "false") {

          // ###################################################### //
          // CONSTRUINDO O ARRAY DO XML DE RETORNO DA SEFAZ
          // ###################################################### //
          $v_response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $v_resposta);
          $array_xml = new SimpleXMLElement($v_response);



          // ###################################################### //
          // // CHECKANDO ERROS PARA CRIAR A ARRAY DAS NFES
          // ###################################################### //
          if ($v_break_total == "false" && $v_break_empresa == "false") {

            // ###################################################### //
            // CRIANDO A ARRAY DAS NFES
            // ###################################################### //
            foreach ($array_xml->soapBody->nfeDistDFeInteresseResponse->nfeDistDFeInteresseResult->retDistDFeInt->loteDistDFeInt->docZip as $v_conteudo) {

              // ###################################################### //
              // OBTENDO TITULOS DA NFE
              // ###################################################### //
              $v_nfe_nsu = (int)$v_conteudo->attributes()->NSU;
              $v_schema = substr($v_conteudo->attributes()->schema, 0, -10);
              $v_nfe_versao = substr($v_conteudo->attributes()->schema, -9, -4);

              /////////////////////////////////////////////////////////////////////////
              // DECODIFICANDO O XML DA NFE
              /////////////////////////////////////////////////////////////////////////
              $v_xml = gzdecode(base64_decode($v_conteudo));
              $v_sefaz_xml = simplexml_load_string($v_xml);



              /////////////////////////////////////////////////////////////////////////
              // OBTENDO NOTAS FISCAIS GERADAS NA SEFAZ COMO NÃO MANIFESTADAS
              /////////////////////////////////////////////////////////////////////////
              if ($v_schema == "resNFe") {

                // OBTENDO A RAZÃO SOCIAL DO EMISSOR DA NFE
                if (isset($v_sefaz_xml->tpNF)) {
                  $v_nfe_tipo =  $v_sefaz_xml->tpNF;
                } else {
                  $v_nfe_tipo = "0";
                }
                // OBTENDO O CNPJ/CPF DO EMISSOR DA NFE
                if (isset($v_sefaz_xml->CNPJ)) {
                  $v_Cnpj_cpf_emit =  $v_sefaz_xml->CNPJ;
                  $v_chNFe_pessoa_emit = "J";
                } else {
                  $v_Cnpj_cpf_emit =  $v_sefaz_xml->CPF;
                  $v_chNFe_pessoa_emit = "F";
                }

                /////////////////////////////////////////////////////////////////////////
                // REGISTRANDO APENAS NOTAS DE ENTRADA DE FORNECEDORES NÃO MANIFESTADAS
                /////////////////////////////////////////////////////////////////////////
                if ($v_nfe_tipo == 1) {

                  // OBTENDO A CHAVE DA NFE
                  $Chave = substr($v_xml, (strpos($v_xml, '<chNFe>') + 7), 44);
                  $v_chNFe_01 = substr($Chave, 0, 15);
                  $v_chNFe_02 = substr($Chave, 15, 15);
                  $v_chNFe_03 = substr($Chave, 30, 14);
                  // MODELO DA CHAVE
                  $v_chNFe_mod = substr($Chave, 20, 2);

                  // OBTENDO A RAZÃO SOCIAL DO EMISSOR DA NFE
                  if (isset($v_sefaz_xml->xNome)) {
                    $v_razao_social_emit =  $v_sefaz_xml->xNome;
                  } else {
                    $v_razao_social_emit = "";
                  }
                  // OBTENDO VALORES DA NFE
                  if (isset($v_sefaz_xml->dhEmi)) {
                    $v_dt_emit = $v_sefaz_xml->dhEmi;
                    $v_dt_emit = str_replace("T", " ", $v_dt_emit);
                    $v_nfe_ano = substr($v_dt_emit, 0, 4);
                  } else {
                    $v_dt_emit = "";
                    $v_nfe_ano = 0;
                  }

                  // OBTENDO A RAZÃO SOCIAL DO EMISSOR DA NFE
                  if (isset($v_sefaz_xml->vNF)) {
                    $v_vNF =  $v_sefaz_xml->vNF;
                  } else {
                    $v_vNF = "";
                  }

                  // OBTENDO A RAZÃO SOCIAL DO EMISSOR DA NFE
                  if (isset($v_sefaz_xml->cSitNFe)) {
                    $v_cSitNFe =  $v_sefaz_xml->cSitNFe;
                  } else {
                    $v_cSitNFe = "0";
                  }





                  // CAPTURANDO E TRANTANDO TODO O XML DA NFE
                  $v_xml = str_replace("'", "", $v_xml);
                  $v_xml = str_replace("|", "-", $v_xml);
                  $v_xml = str_replace("\\", "", $v_xml);

                  if ($v_cSitNFe == 1) {
                    $v_sql = "INSERT INTO db_emp_" . $v_db_emp . ".t_fisco_" . $v_nfe_ano . "_nfeProc (chave_01, chave_02, chave_03, nsu, dt_emit, nfe_tipo, cnpj_coleta, cnpj_emit, cnpj_dest, razao_social_nfe, tipo_pessoa, mod_schema, versao, vnf, xml_nfe, nfe_manifesto) 
                  VALUES ('" . $v_chNFe_01 . "', '" . $v_chNFe_02 . "', '" . $v_chNFe_03 . "', " . $v_nfe_nsu . ", '" . $v_dt_emit . "', " . $v_nfe_tipo . ", " . $v_cnpj_emp . ", " . $v_Cnpj_cpf_emit . ", " . $v_cnpj_emp . ", '" . $v_razao_social_emit . "', '" . $v_chNFe_pessoa_emit . "', '" . $v_schema . "', '" . $v_nfe_versao . "', " . $v_vNF . ", '" . $v_xml . "', 0) 
                  ON CONFLICT ON CONSTRAINT t_fisco_" . $v_nfe_ano . "_nfeProc_pkey 
                  DO NOTHING";
                    pg_query($conn, $v_sql);

                    $v_sql = "update db_adm.t_empresas set qtd_nfeproc = qtd_nfeproc + 1 WHERE cnpj = " . $v_cnpj_emp;
                    pg_query($conn, $v_sql);
                  }
                }
              } // OBTENDO O DOC TIPO resNFe



              /////////////////////////////////////////////////////////////////////////
              // OBTENDO O DOC TIPO procEventoNFe
              /////////////////////////////////////////////////////////////////////////
              if ($v_schema == "procEventoNFe") {
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->evento->infEvento->CNPJ)) {
                  $v_Cnpj_cpf_emit =  $v_sefaz_xml->evento->infEvento->CNPJ;
                } else {
                  $v_Cnpj_cpf_emit = 0;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->evento->infEvento->CPF)) {
                  $v_Cnpj_cpf_emit =  $v_sefaz_xml->evento->infEvento->CPF;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->evento->infEvento->chNFe)) {
                  $v_chNFe =  $v_sefaz_xml->evento->infEvento->chNFe;
                } else {
                  $v_chNFe = 0;
                }
                $v_chave_01 = substr($v_chNFe, 0, 15);
                $v_chave_02 = substr($v_chNFe, 15, 15);
                $v_chave_03 = substr($v_chNFe, 30, 14);
                $v_Cnpj_cpf_chNFe =  substr($v_chNFe, 6, 14);
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->evento->infEvento->dhEvento)) {
                  $v_dt_emit = $v_sefaz_xml->evento->infEvento->dhEvento;
                  $v_dt_emit = str_replace("T", " ", $v_dt_emit);
                  $v_nfe_ano = substr($v_dt_emit, 0, 4);
                } else {
                  $v_dt_emit = "";
                  $v_nfe_ano = "";
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->evento->infEvento->tpEvento)) {
                  $v_cod_evento = $v_sefaz_xml->evento->infEvento->tpEvento;
                } else {
                  $v_cod_evento = 0;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->evento->infEvento->CNPJ)) {
                  $v_Cnpj_cpf_emit =  $v_sefaz_xml->evento->infEvento->CNPJ;
                } else {
                  $v_Cnpj_cpf_emit = 0;
                }
                if (isset($v_sefaz_xml->evento->infEvento->CPF)) {
                  $v_Cnpj_cpf_emit =  $v_sefaz_xml->evento->infEvento->CPF;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->retEvento->infEvento->CNPJDest)) {
                  $v_Cnpj_cpf_dest =  $v_sefaz_xml->retEvento->infEvento->CNPJDest;
                } else {
                  $v_Cnpj_cpf_dest = 0;
                }
                if (isset($v_sefaz_xml->retEvento->infEvento->CPFDest)) {
                  $v_Cnpj_cpf_dest =  $v_sefaz_xml->retEvento->infEvento->CPFDest;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->retEvento->infEvento->CPFDest)) {
                  $v_Cnpj_cpf_dest =  $v_sefaz_xml->retEvento->infEvento->CPFDest;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->retEvento->infEvento->xEvento)) {
                  $v_xEvento = $v_sefaz_xml->retEvento->infEvento->xEvento;
                } else {
                  $v_xEvento = "-";
                }
                $v_xEvento = strtoupper($v_xEvento);

                if (array_key_exists((string)$v_Cnpj_cpf_emit, $v_array_empresas)) {
                  $v_db_emp_emit = $v_array_empresas[(string)$v_Cnpj_cpf_emit];
                  $v_sql = "INSERT INTO db_emp_" . $v_db_emp_emit . ".t_fisco_" . $v_nfe_ano . "_procEventoNFe (chave_01, chave_02, chave_03, nsu, cnpj, cod_evento, evento, dt_emit, xml_nfe) 
                VALUES ('" . $v_chave_01 . "', '" . $v_chave_02 . "', '" . $v_chave_03 . "', " . $v_nfe_nsu . ", " . $v_Cnpj_cpf_emit . ", " . $v_cod_evento . ", '" . $v_xEvento . "', '" . $v_dt_emit . "', '" . $v_xml . "') 
                ON CONFLICT ON CONSTRAINT t_fisco_" . $v_nfe_ano . "_procEventoNFe_pkey 
                DO NOTHING";
                  pg_query($conn, $v_sql);

                  $v_sql = "update db_adm.t_empresas set qtd_proceventonfe = qtd_proceventonfe + 1 WHERE cnpj = " . $v_Cnpj_cpf_emit;
                  pg_query($conn, $v_sql);
                }

                if (array_key_exists((string)$v_Cnpj_cpf_dest, $v_array_empresas)) {
                  $v_db_emp_emit = $v_array_empresas[(string)$v_Cnpj_cpf_dest];
                  $v_sql = "INSERT INTO db_emp_" . $v_db_emp_emit . ".t_fisco_" . $v_nfe_ano . "_procEventoNFe (chave_01, chave_02, chave_03, nsu, cnpj, cod_evento, evento, dt_emit, xml_nfe) 
                VALUES ('" . $v_chave_01 . "', '" . $v_chave_02 . "', '" . $v_chave_03 . "', " . $v_nfe_nsu . ", " . $v_Cnpj_cpf_dest . ", " . $v_cod_evento . ", '" . $v_xEvento . "', '" . $v_dt_emit . "', '" . $v_xml . "') 
                ON CONFLICT ON CONSTRAINT t_fisco_" . $v_nfe_ano . "_procEventoNFe_pkey 
                DO NOTHING";
                  pg_query($conn, $v_sql);

                  $v_sql = "update db_adm.t_empresas set qtd_proceventonfe = qtd_proceventonfe + 1 WHERE cnpj = " . $v_Cnpj_cpf_dest;
                  pg_query($conn, $v_sql);
                }
              } //OBTENDO O DOC TIPO procEventoNFe



              /////////////////////////////////////////////////////////////////////////
              // OBTENDO O DOC TIPO procNFe
              /////////////////////////////////////////////////////////////////////////
              if ($v_schema == "procNFe") {
                // OBTENDO A CHAVE DA NFE
                $Chave = substr($v_xml, (strpos($v_xml, '<chNFe>') + 7), 44);
                $v_chNFe_01 = substr($Chave, 0, 15);
                $v_chNFe_02 = substr($Chave, 15, 15);
                $v_chNFe_03 = substr($Chave, 30, 14);
                // MODELO DA CHAVE
                $v_chNFe_mod = substr($Chave, 20, 2);
                // QUANTIDADE DE PRODUTOS
                $v_quant_prod = substr_count($v_xml, 'det nItem=');
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vBC)) {
                  $v_vBC =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vBC;
                } else {
                  $v_vBC = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vICMS)) {
                  $v_vICMS =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vICMS;
                } else {
                  $v_vICMS = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vICMSDeson)) {
                  $v_vICMSDeson =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vICMSDeson;
                } else {
                  $v_vICMSDeson = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vFCP)) {
                  $v_vFCP =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vFCP;
                } else {
                  $v_vFCP = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vBCST)) {
                  $v_vBCST =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vBCST;
                } else {
                  $v_vBCST = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vST)) {
                  $v_vST =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vST;
                } else {
                  $v_vST = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vFCPST)) {
                  $v_vFCPST =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vFCPST;
                } else {
                  $v_vFCPST = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vFCPSTRet)) {
                  $v_vFCPSTRet =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vFCPSTRet;
                } else {
                  $v_vFCPSTRet = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vProd)) {
                  $v_vProd =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vProd;
                } else {
                  $v_vProd = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vFrete)) {
                  $v_vFrete =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vFrete;
                } else {
                  $v_vFrete = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vSeg)) {
                  $v_vSeg =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vSeg;
                } else {
                  $v_vSeg = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vDesc)) {
                  $v_vDesc =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vDesc;
                } else {
                  $v_vDesc = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vII)) {
                  $v_vII =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vII;
                } else {
                  $v_vII = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vIPI)) {
                  $v_vIPI =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vIPI;
                } else {
                  $v_vIPI = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vIPIDevol)) {
                  $v_vIPIDevol =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vIPIDevol;
                } else {
                  $v_vIPIDevol = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vPIS)) {
                  $v_vPIS =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vPIS;
                } else {
                  $v_vPIS = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vCOFINS)) {
                  $v_vCOFINS =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vCOFINS;
                } else {
                  $v_vCOFINS = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vOutro)) {
                  $v_vOutro =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vOutro;
                } else {
                  $v_vOutro = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vNF)) {
                  $v_vNF =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vNF;
                } else {
                  $v_vNF = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->total->ICMSTot->vNF)) {
                  $v_vNF =  $v_sefaz_xml->NFe->infNFe->total->ICMSTot->vNF;
                } else {
                  $v_vNF = 0.00;
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->ide->dhEmi)) {
                  $v_dt_emit =  $v_sefaz_xml->NFe->infNFe->ide->dhEmi;
                } else {
                  $v_dt_emit = "";
                }
                // OBTENDO VALORES DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->ide->dhEmi)) {
                  $v_dt_emit = $v_sefaz_xml->NFe->infNFe->ide->dhEmi;
                  $v_dt_emit = str_replace("T", " ", $v_dt_emit);
                  $v_nfe_ano = substr($v_dt_emit, 0, 4);
                } else {
                  $v_dt_emit = "";
                  $v_nfe_ano = 0;
                }
                // OBTENDO O CNPJ/CPF DO EMISSOR DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->emit->CNPJ)) {
                  $v_Cnpj_cpf_emit =  $v_sefaz_xml->NFe->infNFe->emit->CNPJ;
                  $v_chNFe_pessoa_emit = "J";
                } else {
                  $v_Cnpj_cpf_emit =  $v_sefaz_xml->NFe->infNFe->emit->CPF;
                  $v_chNFe_pessoa_emit = "F";
                }
                // OBTENDO O CNPJ/CPF DO DESTINATÁRIO DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->dest->CNPJ)) {
                  $v_Cnpj_cpf_dest =  $v_sefaz_xml->NFe->infNFe->dest->CNPJ;
                  $v_chNFe_pessoa_dest = "J";
                } else {
                  $v_Cnpj_cpf_dest =  $v_sefaz_xml->NFe->infNFe->dest->CPF;
                  $v_chNFe_pessoa_dest = "F";
                }
                // OBTENDO A RAZÃO SOCIAL DO EMISSOR DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->emit->xNome)) {
                  $v_razao_social_emit =  $v_sefaz_xml->NFe->infNFe->emit->xNome;
                } else {
                  $v_razao_social_emit = "";
                }
                // OBTENDO O CNPJ/CPF DO DESTINATÁRIO DA NFE
                if (isset($v_sefaz_xml->NFe->infNFe->dest->xNome)) {
                  $v_razao_social_dest =  $v_sefaz_xml->NFe->infNFe->dest->xNome;
                } else {
                  $v_razao_social_dest =  "";
                }
                // CAPTURANDO E TRANTANDO TODO O XML DA NFE
                $v_xml = str_replace("'", "", $v_xml);
                $v_xml = str_replace("|", "-", $v_xml);
                $v_xml = str_replace("\\", "", $v_xml);



                /////////////////////////////////////////////////////////////////////////
                // REMOVENDO NFE SEM MANIFESTO
                /////////////////////////////////////////////////////////////////////////
                $v_sql = "DELETE FROM db_emp_" . $v_db_emp . ".t_fisco_" . $v_nfe_ano . "_nfeProc WHERE chave_01 = '" . $v_chNFe_01 . "' and chave_02 = '" . $v_chNFe_02 . "' and chave_03 = '" . $v_chNFe_03 . "' and nfe_manifesto = 0";
                pg_query($conn, $v_sql);



                /////////////////////////////////////////////////////////////////////////
                // REGISTRANDO A NFE COMO ENTRADA
                /////////////////////////////////////////////////////////////////////////
                $v_nfe_tipo = 1;
                if (array_key_exists((string)$v_Cnpj_cpf_dest, $v_array_empresas)) {
                  $v_db_emp_dest = $v_array_empresas[(string)$v_Cnpj_cpf_dest];
                  $v_sql = "INSERT INTO db_emp_" . $v_db_emp_dest . ".t_fisco_" . $v_nfe_ano . "_nfeProc (chave_01, chave_02, chave_03, nsu, dt_emit, nfe_tipo, cnpj_coleta, cnpj_emit, cnpj_dest, razao_social_nfe, tipo_pessoa, mod_schema, versao, quant_prod, vBC,vICMS,vICMSDeson,vFCP,vBCST,vST,vFCPST,vFCPSTRet,vProd,vFrete,vSeg,vDesc,vII,vIPI,vIPIDevol,vPIS,vCOFINS,vOutro,vNF, xml_nfe, nfe_manifesto) 
                VALUES ('" . $v_chNFe_01 . "', '" . $v_chNFe_02 . "', '" . $v_chNFe_03 . "', " . $v_nfe_nsu . ", '" . $v_dt_emit . "', " . $v_nfe_tipo . ", " . $v_cnpj_emp . ", " . $v_Cnpj_cpf_emit . ", " . $v_Cnpj_cpf_dest . ", '" . $v_razao_social_emit . "', '" . $v_chNFe_pessoa_emit . "', '" . $v_schema . "', '" . $v_nfe_versao . "', " . $v_quant_prod . ", " . $v_vBC . ", " . $v_vICMS . ", " . $v_vICMSDeson . ", " . $v_vFCP . ", " . $v_vBCST . ", " . $v_vST . ", " . $v_vFCPST . ", " . $v_vFCPSTRet . ", " . $v_vProd . ", " . $v_vFrete . ", " . $v_vSeg . ", " . $v_vDesc . ", " . $v_vII . ", " . $v_vIPI . ", " . $v_vIPIDevol . ", " . $v_vPIS . ", " . $v_vCOFINS . ", " . $v_vOutro . ", " . $v_vNF . ", '" . $v_xml . "', 210210) 
                ON CONFLICT ON CONSTRAINT t_fisco_" . $v_nfe_ano . "_nfeProc_pkey 
                DO NOTHING";
                  pg_query($conn, $v_sql);

                  $v_sql = "update db_adm.t_empresas set qtd_nfeproc = qtd_nfeproc + 1 WHERE cnpj = " . $v_Cnpj_cpf_dest;
                  pg_query($conn, $v_sql);
                }

                /////////////////////////////////////////////////////////////////////////
                // REGISTRANDO A NFE COMO SAIDA
                /////////////////////////////////////////////////////////////////////////
                $v_nfe_tipo = 2;
                if (array_key_exists((string)$v_Cnpj_cpf_emit, $v_array_empresas)) {
                  $v_db_emp_emit = $v_array_empresas[(string)$v_Cnpj_cpf_emit];
                  $v_sql = "INSERT INTO db_emp_" . $v_db_emp_emit . ".t_fisco_" . $v_nfe_ano . "_nfeProc (chave_01, chave_02, chave_03, nsu, dt_emit, nfe_tipo, cnpj_coleta, cnpj_emit, cnpj_dest, razao_social_nfe, tipo_pessoa, mod_schema, versao, quant_prod, vBC,vICMS,vICMSDeson,vFCP,vBCST,vST,vFCPST,vFCPSTRet,vProd,vFrete,vSeg,vDesc,vII,vIPI,vIPIDevol,vPIS,vCOFINS,vOutro,vNF, xml_nfe, nfe_manifesto) 
                VALUES ('" . $v_chNFe_01 . "', '" . $v_chNFe_02 . "', '" . $v_chNFe_03 . "', " . $v_nfe_nsu . ", '" . $v_dt_emit . "', " . $v_nfe_tipo . ", " . $v_cnpj_emp . ", " . $v_Cnpj_cpf_emit . ", " . $v_Cnpj_cpf_dest . ", '" . $v_razao_social_dest . "', '" . $v_chNFe_pessoa_dest . "', '" . $v_schema . "', '" . $v_nfe_versao . "', " . $v_quant_prod . ", " . $v_vBC . ", " . $v_vICMS . ", " . $v_vICMSDeson . ", " . $v_vFCP . ", " . $v_vBCST . ", " . $v_vST . ", " . $v_vFCPST . ", " . $v_vFCPSTRet . ", " . $v_vProd . ", " . $v_vFrete . ", " . $v_vSeg . ", " . $v_vDesc . ", " . $v_vII . ", " . $v_vIPI . ", " . $v_vIPIDevol . ", " . $v_vPIS . ", " . $v_vCOFINS . ", " . $v_vOutro . ", " . $v_vNF . ", '" . $v_xml . "', 210210) 
                ON CONFLICT ON CONSTRAINT t_fisco_" . $v_nfe_ano . "_nfeProc_pkey 
                DO NOTHING";
                  pg_query($conn, $v_sql);

                  $v_sql = "update db_adm.t_empresas set qtd_nfeproc = qtd_nfeproc + 1 WHERE cnpj = " . $v_Cnpj_cpf_emit;
                  pg_query($conn, $v_sql);
                }
              } // OBTENDO O DOC TIPO procNFe



              // ###################################################### //
              // REGISTRANDO A ULTIMA NSU LIDA
              // ###################################################### //
              $v_sql = "update db_adm.t_empresas set fisco_nsu = " . $v_nfe_nsu . " WHERE cnpj = " . $v_cnpj_emp;
              pg_query($conn, $v_sql);
            } // CRIANDO A ARRAY DAS NFES - CHECK CAMINHO
          } // // CHECKANDO ERROS PARA CRIAR A ARRAY DAS NFES
        } // CHECKANDO ERROS PARA CRIAR A ARRAY DO XML
      } // GERANDO A LISTA DE NFE DA EMPRESA

      // ###################################################### //
      // CLASSIFICANDO A EMPRESA COMO SUCESSO
      // ###################################################### //
      if ($v_break_total == "false" && $v_break_empresa == "false") {
        $v_sql = "update db_adm.t_empresas set fisco_status_coleta_nfe = 3, fisco_nsu_log = current_timestamp WHERE cnpj = " . $v_cnpj_emp;
        pg_query($conn, $v_sql);
      }



      // ###################################################### //
      // ATUALIZANDO MENIFESTO DE NFE DESTE ANO
      // ###################################################### //
      $v_sq = "update db_emp_" . $v_db_emp . ".t_fisco_" . $v_ano_atual . "_nfeproc as t_fisco_" . $v_ano_atual . "_nfeproc set 
    nfe_manifesto = tab_temp.cod_evento 
    from db_emp_" . $v_db_emp . ".t_fisco_" . $v_ano_atual . "_proceventonfe as tab_temp 
    where t_fisco_" . $v_ano_atual . "_nfeproc.nfe_manifesto > 0 
    and t_fisco_" . $v_ano_atual . "_nfeproc.chave_01 = tab_temp.chave_01 
    and t_fisco_" . $v_ano_atual . "_nfeproc.chave_02 = tab_temp.chave_02 
    and t_fisco_" . $v_ano_atual . "_nfeproc.chave_03 = tab_temp.chave_03 
    and to_date(to_char(t_fisco_" . $v_ano_atual . "_nfeproc.data_hora, 'YYYY-MM-DD'), 'YYYY-MM-DD') = CURRENT_DATE";
      pg_query($conn, $v_sq);

      // ###################################################### //
      // ATUALIZANDO MENIFESTO DE NFE ANO PASSADO
      // ###################################################### //
      if ($v_mes_atual == 1) {
        $v_sq = "update db_emp_" . $v_db_emp . ".t_fisco_" . $v_ano_passado . "_nfeproc as t_fisco_" . $v_ano_passado . "_nfeproc set 
      nfe_manifesto = tab_temp.cod_evento 
      from db_emp_" . $v_db_emp . ".t_fisco_" . $v_ano_passado . "_proceventonfe as tab_temp 
      where t_fisco_" . $v_ano_passado . "_nfeproc.nfe_manifesto > 0 
      and t_fisco_" . $v_ano_passado . "_nfeproc.chave_01 = tab_temp.chave_01 
      and t_fisco_" . $v_ano_passado . "_nfeproc.chave_02 = tab_temp.chave_02 
      and t_fisco_" . $v_ano_passado . "_nfeproc.chave_03 = tab_temp.chave_03 
      and to_date(to_char(t_fisco_" . $v_ano_passado . "_nfeproc.data_hora, 'YYYY-MM-DD'), 'YYYY-MM-DD') = CURRENT_DATE";
        pg_query($conn, $v_sq);
      }

      fclose($v_cert_pem_file);
    } // LISTANDO EMPRESAS PARA CONSULTA JUNTO A SEFAZ



    /////////////////////////////////////////////////////
    // REGISTRANDO O LOG DE FINALIZAÇÃO
    /////////////////////////////////////////////////////
    $v_sql = "select 
  to_char(min(fisco_nsu_log), 'HH24:MI:SS') as script_in, 
  to_char(max(fisco_nsu_log), 'HH24:MI:SS') as script_fim, 
  sum(qtd_error) as tt_erros, 
  sum(qtd_proceventonfe) as tt_eventos, 
  sum(qtd_nfeproc) as tt_nfes 
  from db_adm.t_empresas 
  where to_char(fisco_nsu_log, 'YYYY-MM-DD') = to_char(CURRENT_DATE, 'YYYY-MM-DD') and fisco_status_coleta_nfe > 0";
    $v_result_resumo = pg_query($conn, $v_sql);

    $v_texto_log = "LOG NFEs - " . $v_data_atual . "\n";
    if ($v_row_resumo = pg_fetch_assoc($v_result_resumo)) {
      $v_texto_log .= "INÍCIO: " . $v_row_resumo["script_in"] . "\n";
      $v_texto_log .= "FIM: " . $v_row_resumo["script_fim"] . "\n";
      $v_texto_log .= "ERROS: " . $v_row_resumo["tt_erros"] . "\n";
      $v_texto_log .= "EVENTOS: " . $v_row_resumo["tt_eventos"] . "\n";
      $v_texto_log .= "NFEs: " . $v_row_resumo["tt_nfes"];
    }

    $v_sql = "INSERT INTO db_adm.t_fisco_log (cnpj, nsu, cod_retorno, motivo, msg_curl) VALUES(0, 0, 2, 'FIM DA EXECUÇÃO DO SCRIPT DE COLETA DE NFEs', '" . $v_texto_log . "')";
    pg_query($conn, $v_sql);
    pg_close($conn);
  } else {
    $v_sql = "INSERT INTO db_adm.t_fisco_log (cnpj, nsu, cod_retorno, motivo, msg_curl) VALUES(0, 0, 4, 'FORA DO PERÍODO DE COLETA', 'FORA DO PERÍODO DE COLETA')";
    pg_query($conn, $v_sql);
    pg_close($conn);
  }

  echo "OK";
}
