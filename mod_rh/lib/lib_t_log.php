<?php
header("Content-Type: text/plain");
header("Content-Type: application/json; charset=utf-8");
// include_once("../../class/php/class_conect_db.php");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);
$v_id_empresa = addslashes(intval($_SESSION["vs_id_empresa"]));


// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_EMPRESAS") {
    // var_dump("LISTAR_EMPRESAS");
    // die;
    // $v_tab_campo = addslashes($F_POST["v_tab_campo"]);
    // $v_tab_ordem = addslashes($_POST["v_tab_ordem"]);
    // $v_tab_busca_campo = explode("|", addslashes($_POST["v_tab_busca_campo"]))[0];
    // $v_tab_busca_campo_tipo = explode("|", addslashes($_POST["v_tab_busca_campo"]))[1];
    // $v_tab_busca_texto = addslashes($_POST["v_tab_busca_texto"]);
    // $v_tab_sql_limit_in = addslashes($_POST["v_tab_sql_limit_in"]);
    // $v_limit = addslashes($_POST["v_limit"]);
    $v_linhas = 0;

    // CONSTRUINDO OS FILTROS
    // $v_filtro = "";
    // if (!empty($v_tab_busca_texto)) {

    //     if ($v_tab_busca_campo_tipo == "txt") {
    //         $v_filtro = "WHERE " . $v_tab_busca_campo . " like '%" . $v_tab_busca_texto . "%'";
    //     } else {
    //         $v_filtro = "WHERE " . $v_tab_busca_campo . " = " . $v_tab_busca_texto;
    //     }
    // }

    // CONTANDO REGISTROS PARA GERAR OS BOTÕES DE NAVEGAÇÃO
    $v_sql = "SELECT count(id_user) as linhas from db_adm_rh.t_log";
    if ($result = pg_query($conn, $v_sql)) {
        $row = pg_fetch_assoc($result);
        $v_linhas = $row["linhas"];
    }


    // GERANDO A LISTA
    // $v_sql = "SELECT Id, Nome, Cnpj, TO_CHAR(fisco_cert_dt_validade, 'DD/MM/YYYY') as fisco_cert_dt_validade, St_Cadastro AS St_Cadastro_id, CASE St_Cadastro WHEN 0 THEN 'PENDENTE' WHEN 1 THEN 'ATIVO' WHEN 2 THEN 'SUSPENSO' ELSE 'CANCELADO' END AS St_Cadastro FROM db_adm.t_empresas ";
    // . $v_filtro . " ORDER BY " . $v_tab_campo . " " . $v_tab_ordem . " OFFSET " . $v_tab_sql_limit_in . " LIMIT " . $v_limit;
    // $_SESSION["database_adm"] = "S";

    // $v_sql = "select id_user,data_hora,id_empresa,id_processo,descricao from db_adm_rh.t_log";
    // $v_id = addslashes();


    $v_sql = "SELECT tu.nome usuario, log.data_hora, te.nome empresa, tlp.descricao tipo_log, log.descricao, log.id_user  
              from db_adm_rh.t_log log 
              	join db_adm.t_empresas as te 
              		on te.id = log.id_empresa 
              	join db_adm_rh.t_log_processo as tlp
              		on log.id_processo = tlp.id 
              	join db_adm.t_user as tu 
              		on tu.id = log.id_user  
              where te.id = " . $v_id_empresa;

    // var_dump($v_id_empresa);

    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "usuario" => $row["usuario"],
            "id_user" => $row["id_user"],
            "data_hora" => $row["data_hora"],
            "empresa" => $row["empresa"],
            "tipo_log" => $row["tipo_log"],
            "descricao" => $row["descricao"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// BUSCA DB_EMP
if ($v_acao == "EV_DB_EMP") {
    var_dump("EV_DB_EMP");
    die;

    $v_cnpj = preg_replace('/[^0-9]/', '', addslashes($_POST["v_cnpj"]));
    $v_sql = "select db_emp from db_adm.t_empresas where substr(LPAD(CAST(cnpj AS VARCHAR),14,'0'), 0, 13) = concat(substr(LPAD(CAST('" . $v_cnpj . "' AS VARCHAR),14,'0'), 0, 9), '0001')";
    $result = pg_query($conn, $v_sql);

    if ($row = pg_fetch_assoc($result)) {
        echo $row["db_emp"];
    } else {
        echo "0";
    }
}



// SELECIONANDO REGISTRO DO BANCO
if ($v_acao == "EV_SELECT") { //OK
    // var_dump("EV_SELECT");
    // die;

    $v_id = addslashes($_POST["v_id"]);
    $v_data_hora = addslashes($_POST["v_data_hora"]);


    // $v_sql = "select emp.nome, emp_int.empresa_erp, emp_int.empresa_portal 
    //             from db_adm.t_empresas emp
    //                 join db_adm_rh.t_empresas_integracao as emp_int on emp.id = emp_int.empresa_portal
    //                     where emp.id = $v_id";


    $v_sql = "SELECT tu.nome usuario, log.data_hora, te.nome empresa, tlp.descricao tipo_log, log.descricao 
              from db_adm_rh.t_log log 
              	join db_adm.t_empresas as te 
              		on te.id = log.id_empresa 
              	join db_adm_rh.t_log_processo as tlp
              		on log.id_processo = tlp.id 
              	join db_adm.t_user as tu 
              		on tu.id = log.id_user  
                where te.id = {$v_id_empresa} and log.id_user = {$v_id} and log.data_hora = '{$v_data_hora}';";


    // var_dump($v_sql);
    // die;
    $result = pg_query($conn, $v_sql);


    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "usuario" => $row["usuario"],
            "data_hora" => $row["data_hora"],
            "empresa" => $row["empresa"],
            "tipo_log" => $row["tipo_log"],
            "descricao" => $row["descricao"]
        );
    }
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// CADASTRANDO NOVO REGISTRO
if ($v_acao == "EV_NOVO") {
    var_dump("EV_NOVO");
    die;

    // $v_tipo = strtoupper(addslashes($_POST["v_tipo"]));
    // $v_cnpj = preg_replace('/[^0-9]/', '', addslashes($_POST["v_cnpj"]));

    $v_empresa_portal = strtoupper(addslashes($_POST["empresa_portal"]));
    $v_empresa_erp = strtoupper(addslashes($_POST["empresa_erp"]));
    $v_nome = strtoupper(addslashes($_POST["nome"]));

    // $v_st_cadastro = 1;
    // $v_uf = strtoupper(addslashes($_POST["v_uf"]));
    // $v_fisco_certi_senha = addslashes($_POST["v_fisco_certi_senha"]);
    // $v_fisco_cnpj_agrocontar = addslashes($_POST["v_fisco_cnpj_agrocontar"]);
    // $v_atividade_principal = addslashes($_POST["v_atividade_principal"]);
    // $v_telefone = preg_replace('/[^0-9]/', '', addslashes($_POST["v_telefone"]));
    // $v_email =  addslashes($_POST["v_email"]);
    // $v_cep = preg_replace('/[^0-9]/', '', addslashes($_POST["v_cep"]));
    // $v_logradouro = strtoupper(addslashes($_POST["v_logradouro"]));
    // $v_complemento = strtoupper(addslashes($_POST["v_complemento"]));
    // $v_numero = addslashes($_POST["v_numero"]);
    // $v_bairro = strtoupper(addslashes($_POST["v_bairro"]));
    // $v_municipio = strtoupper(addslashes($_POST["v_municipio"]));
    // $v_insc_estadual = addslashes($_POST["v_insc_estadual"]);
    // $v_insc_municipal = addslashes($_POST["v_insc_municipal"]);
    // $v_natureza_juridica = strtoupper(addslashes($_POST["v_natureza_juridica"]));
    // $v_atividades_secundarias = addslashes($_POST["v_atividades_secundarias"]);
    // $v_abertura = implode("-", array_reverse(explode("/", addslashes($_POST["v_abertura"]))));
    // $v_mod_fisco = addslashes($_POST["v_mod_fisco"]);
    // $v_mod_rh = addslashes($_POST["v_mod_rh"]);
    // $v_mod_adm = addslashes($_POST["v_mod_adm"]);
    // $v_mod_cons = addslashes($_POST["v_mod_cons"]);
    // $v_db_emp = addslashes($_POST["v_db_emp"]);
    // $v_abertura = implode("-", array_reverse(explode("/", $v_abertura)));

    ////////////////////////////////////////////////////
    // CASO A EMPRESA SEJA UMA CONTÁBIL, MATRIZ OU CPF
    ////////////////////////////////////////////////////
    // if ($v_tipo == 1 || $v_tipo == 2 || $v_tipo == 5) {
    //     // OBTENDO O NOVO CODIGO EMP
    //     $v_sql = "select MAX(db_emp)+1 as new_db_emp from db_adm.t_empresas";
    //     $result = pg_query($conn, $v_sql);
    //     if ($row = pg_fetch_assoc($result)) {
    //         $v_db_emp = str_pad($row["new_db_emp"], 4, '0', STR_PAD_LEFT);
    //     }
    // }

    // if ($v_db_emp > 0) {

    //     // SALVANDO CERTIFICADO QUANDO HOUVER
    //     if (isset($_SESSION["vs_certificado_dig"]) && !empty($_SESSION["vs_certificado_dig"])) {

    //         $v_sql = "INSERT INTO db_adm.t_empresas (db_emp, tipo, nome, descricao, fisco_cnpj_agrocontar, st_cadastro, cnpj, uf, " .
    //             "ativ_principal, telefone, email, cep, logradouro, complemento, numero, bairro, municipio, " .
    //             "insc_estadual, insc_municipal, natureza_juridica, ativ_secundarias, dat_abertura, modulo_fisco, modulo_rh, modulo_adm, modulo_cons, fisco_cert_pem, fisco_cert_dt_validade, fisco_certi_senha)" . "\n" .
    //             "VALUES(" . $v_db_emp . ", " . $v_tipo . ",'" . $v_nome . "','" . $v_fantasia . "'," . $v_fisco_cnpj_agrocontar . "," . $v_st_cadastro . " , $v_cnpj ,'" . $v_uf . "','" . $v_atividade_principal . "', '" . $v_telefone . "','" . $v_email . "', $v_cep,'" . $v_logradouro . "','" . $v_complemento . "', '" . $v_numero . "' ,'" . $v_bairro . "','" . $v_municipio . "','" . $v_insc_estadual . "','" . $v_insc_municipal . "','" . $v_natureza_juridica . "','" . $v_atividades_secundarias . "','" . $v_abertura . "', '" . $v_mod_fisco . "' ,'" . $v_mod_rh . "' ,'" . $v_mod_adm . "' ,'" . $v_mod_cons . "', '" . $_SESSION["vs_certificado_dig"] . "', '" . $_SESSION["vs_venc_certificado_dig"] . "', '" . $v_fisco_certi_senha . "')";
    //     } else {

    //         $v_sql = "INSERT INTO db_adm.t_empresas (db_emp, tipo, nome, descricao, fisco_cnpj_agrocontar, st_cadastro, cnpj, uf, " .
    //             "ativ_principal, telefone, email, cep, logradouro, complemento, numero, bairro, municipio, " .
    //             "insc_estadual, insc_municipal, natureza_juridica, ativ_secundarias, dat_abertura, modulo_fisco, modulo_rh, modulo_adm, modulo_cons)" . "\n" .
    //             "VALUES(" . $v_db_emp . ", " . $v_tipo . ",'" . $v_nome . "','" . $v_fantasia . "'," . $v_fisco_cnpj_agrocontar . "," . $v_st_cadastro . " , $v_cnpj ,'" . $v_uf . "','" . $v_atividade_principal . "', '" . $v_telefone . "','" . $v_email . "', $v_cep,'" . $v_logradouro . "','" . $v_complemento . "', '" . $v_numero . "' ,'" . $v_bairro . "','" . $v_municipio . "','" . $v_insc_estadual . "','" . $v_insc_municipal . "','" . $v_natureza_juridica . "','" . $v_atividades_secundarias . "','" . $v_abertura . "', '" . $v_mod_fisco . "' ,'" . $v_mod_rh . "' ,'" . $v_mod_adm . "' ,'" . $v_mod_cons . "')";
    //     }

    //     if (pg_query($conn, $v_sql)) {

    //         if ($v_tipo == 1 || $v_tipo == 2 || $v_tipo == 5) {

    //             // CRIANDO BANCO DA EMPRESA
    //             $v_sql = "CREATE SCHEMA db_emp_" . $v_db_emp . " AUTHORIZATION postgres";
    //             pg_query($conn, $v_sql);

    //             $v_ano = date('Y');
    //             $v_sql = "CREATE TABLE db_emp_" . $v_db_emp . ".t_fisco_" . $v_ano . "_nfe_analises (
    //                 id int8 NOT NULL GENERATED BY DEFAULT AS IDENTITY,
    //                 data_hora timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    //                 chave_01 varchar(15) NOT NULL DEFAULT '000000000000000'::character varying,
    //                 chave_02 varchar(15) NOT NULL DEFAULT '000000000000000'::character varying,
    //                 chave_03 varchar(14) NOT NULL DEFAULT '00000000000000'::character varying,
    //                 nfe_tipo int4 NOT NULL DEFAULT 0,
    //                 id_user int4 NOT NULL DEFAULT 0,
    //                 analise_status varchar(1) NOT NULL DEFAULT '-'::character varying,
    //                 analise_texto text NULL DEFAULT '-'::text,
    //                 CONSTRAINT t_fisco_" . $v_ano . "_nfe_analises_pkey PRIMARY KEY (id)
    //                 )";
    //             pg_query($conn, $v_sql);

    //             $v_sql = "CREATE TABLE db_emp_" . $v_db_emp . ".t_fisco_" . ($v_ano - 1) . "_nfe_analises (
    //                 id int8 NOT NULL GENERATED BY DEFAULT AS IDENTITY,
    //                 data_hora timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    //                 chave_01 varchar(15) NOT NULL DEFAULT '000000000000000'::character varying,
    //                 chave_02 varchar(15) NOT NULL DEFAULT '000000000000000'::character varying,
    //                 chave_03 varchar(14) NOT NULL DEFAULT '00000000000000'::character varying,
    //                 nfe_tipo int4 NOT NULL DEFAULT 0,
    //                 id_user int4 NOT NULL DEFAULT 0,
    //                 analise_status varchar(1) NOT NULL DEFAULT '-'::character varying,
    //                 analise_texto text NULL DEFAULT '-'::text,
    //                 CONSTRAINT t_fisco_" . ($v_ano - 1) . "_nfe_analises_pkey PRIMARY KEY (id)
    //                 )";
    //             pg_query($conn, $v_sql);


    //             $v_sql = "CREATE TABLE db_emp_" . $v_db_emp . ".t_fisco_" . $v_ano . "_nfeproc (
    //                 data_hora timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    //                 chave_01 varchar(15) NOT NULL DEFAULT '000000000000000'::character varying,
    //                 chave_02 varchar(15) NOT NULL DEFAULT '000000000000000'::character varying,
    //                 chave_03 varchar(14) NOT NULL DEFAULT '00000000000000'::character varying,
    //                 nfe_tipo int4 NOT NULL DEFAULT 0,
    //                 nsu int4 NULL DEFAULT 0,
    //                 dt_emit timestamp NULL,
    //                 cnpj_coleta int8 NOT NULL DEFAULT '0'::bigint,
    //                 cnpj_emit int8 NOT NULL DEFAULT '0'::bigint,
    //                 cnpj_dest int8 NOT NULL DEFAULT '0'::bigint,
    //                 razao_social_nfe varchar(150) NULL DEFAULT '-'::character varying,
    //                 tipo_pessoa varchar(1) NULL DEFAULT 'J'::character varying,
    //                 mod_schema varchar(40) NULL DEFAULT '-'::character varying,
    //                 versao varchar(10) NULL DEFAULT '-'::character varying,
    //                 quant_prod int4 NULL DEFAULT 0,
    //                 vbc numeric(13,2) NULL DEFAULT 0,
    //                 vicms numeric(13,2) NULL DEFAULT 0,
    //                 vicmsdeson numeric(13,2) NULL DEFAULT 0,
    //                 vfcp numeric(13,2) NULL DEFAULT 0,
    //                 vbcst numeric(13,2) NULL DEFAULT 0,
    //                 vst numeric(13,2) NULL DEFAULT 0,
    //                 vfcpst numeric(13,2) NULL DEFAULT 0,
    //                 vfcpstret numeric(13,2) NULL DEFAULT 0,
    //                 vprod numeric(13,2) NULL DEFAULT 0,
    //                 vfrete numeric(13,2) NULL DEFAULT 0,
    //                 vseg numeric(13,2) NULL DEFAULT 0,
    //                 vdesc numeric(13,2) NULL DEFAULT 0,
    //                 vii numeric(13,2) NULL DEFAULT 0,
    //                 vipi numeric(13,2) NULL DEFAULT 0,
    //                 vipidevol numeric(13,2) NULL DEFAULT 0,
    //                 vpis numeric(13,2) NULL DEFAULT 0,
    //                 vcofins numeric(13,2) NULL DEFAULT 0,
    //                 voutro numeric(13,2) NULL DEFAULT 0,
    //                 vnf numeric(13,2) NULL DEFAULT 0,
    //                 xml_nfe text NULL DEFAULT '-'::text,
    //                 analise_status varchar(1) NULL DEFAULT '-'::text,
    //                 nfe_manifesto int4 NULL DEFAULT 0,
    //             CONSTRAINT t_fisco_" . $v_ano . "_nfeproc_pkey PRIMARY KEY (chave_01, chave_02, chave_03, nfe_tipo)
    //         )";
    //             pg_query($conn, $v_sql);

    //             $v_sql = "CREATE TABLE db_emp_" . $v_db_emp . ".t_fisco_" . ($v_ano - 1) . "_nfeproc (
    //                 data_hora timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    //                 chave_01 varchar(15) NOT NULL DEFAULT '000000000000000'::character varying,
    //                 chave_02 varchar(15) NOT NULL DEFAULT '000000000000000'::character varying,
    //                 chave_03 varchar(14) NOT NULL DEFAULT '00000000000000'::character varying,
    //                 nfe_tipo int4 NOT NULL DEFAULT 0,
    //                 nsu int4 NULL DEFAULT 0,
    //                 dt_emit timestamp NULL,
    //                 cnpj_coleta int8 NOT NULL DEFAULT '0'::bigint,
    //                 cnpj_emit int8 NOT NULL DEFAULT '0'::bigint,
    //                 cnpj_dest int8 NOT NULL DEFAULT '0'::bigint,
    //                 razao_social_nfe varchar(150) NULL DEFAULT '-'::character varying,
    //                 tipo_pessoa varchar(1) NULL DEFAULT 'J'::character varying,
    //                 mod_schema varchar(40) NULL DEFAULT '-'::character varying,
    //                 versao varchar(10) NULL DEFAULT '-'::character varying,
    //                 quant_prod int4 NULL DEFAULT 0,
    //                 vbc numeric(13,2) NULL DEFAULT 0,
    //                 vicms numeric(13,2) NULL DEFAULT 0,
    //                 vicmsdeson numeric(13,2) NULL DEFAULT 0,
    //                 vfcp numeric(13,2) NULL DEFAULT 0,
    //                 vbcst numeric(13,2) NULL DEFAULT 0,
    //                 vst numeric(13,2) NULL DEFAULT 0,
    //                 vfcpst numeric(13,2) NULL DEFAULT 0,
    //                 vfcpstret numeric(13,2) NULL DEFAULT 0,
    //                 vprod numeric(13,2) NULL DEFAULT 0,
    //                 vfrete numeric(13,2) NULL DEFAULT 0,
    //                 vseg numeric(13,2) NULL DEFAULT 0,
    //                 vdesc numeric(13,2) NULL DEFAULT 0,
    //                 vii numeric(13,2) NULL DEFAULT 0,
    //                 vipi numeric(13,2) NULL DEFAULT 0,
    //                 vipidevol numeric(13,2) NULL DEFAULT 0,
    //                 vpis numeric(13,2) NULL DEFAULT 0,
    //                 vcofins numeric(13,2) NULL DEFAULT 0,
    //                 voutro numeric(13,2) NULL DEFAULT 0,
    //                 vnf numeric(13,2) NULL DEFAULT 0,
    //                 xml_nfe text NULL DEFAULT '-'::text,
    //                 analise_status varchar(1) NULL DEFAULT '-'::text,
    //                 nfe_manifesto int4 NULL DEFAULT 0,
    //             CONSTRAINT t_fisco_" . ($v_ano - 1) . "_nfeproc_pkey PRIMARY KEY (chave_01, chave_02, chave_03, nfe_tipo)
    //         )";
    //             pg_query($conn, $v_sql);


    //             $v_sql = "CREATE TABLE db_emp_" . $v_db_emp . ".t_fisco_" . $v_ano . "_proceventonfe (
    //                 data_hora timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    //                 chave_01 varchar(15) NOT NULL DEFAULT '000000000000000'::character varying,
    //                 chave_02 varchar(15) NOT NULL DEFAULT '000000000000000'::character varying,
    //                 chave_03 varchar(14) NOT NULL DEFAULT '00000000000000'::character varying,
    //                 nsu int4 NOT NULL DEFAULT 0,
    //                 dt_emit timestamp NULL,
    //                 cnpj int8 NOT NULL DEFAULT '0'::bigint,
    //                 cod_evento int8 NOT NULL DEFAULT '0'::bigint,
    //                 evento varchar(50) NOT NULL DEFAULT '-'::character varying,
    //                 xml_nfe text NULL DEFAULT '-'::text,
    //             CONSTRAINT t_fisco_" . $v_ano . "_proceventonfe_pkey PRIMARY KEY (chave_01, chave_02, chave_03, nsu)
    //         )";
    //             pg_query($conn, $v_sql);

    //             $v_sql = "CREATE TABLE db_emp_" . $v_db_emp . ".t_fisco_" . ($v_ano - 1) . "_proceventonfe (
    //                 data_hora timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    //                 chave_01 varchar(15) NOT NULL DEFAULT '000000000000000'::character varying,
    //                 chave_02 varchar(15) NOT NULL DEFAULT '000000000000000'::character varying,
    //                 chave_03 varchar(14) NOT NULL DEFAULT '00000000000000'::character varying,
    //                 nsu int4 NOT NULL DEFAULT 0,
    //                 dt_emit timestamp NULL,
    //                 cnpj int8 NOT NULL DEFAULT '0'::bigint,
    //                 cod_evento int8 NOT NULL DEFAULT '0'::bigint,
    //                 evento varchar(50) NOT NULL DEFAULT '-'::character varying,
    //                 xml_nfe text NULL DEFAULT '-'::text,
    //             CONSTRAINT t_fisco_" . ($v_ano - 1) . "_proceventonfe_pkey PRIMARY KEY (chave_01, chave_02, chave_03, nsu)
    //         )";
    //             pg_query($conn, $v_sql);
    //         }

    //         // REMOVENDO A VARIAVEL DE SESSÃO DO CERTIFICADO
    //         if (isset($_SESSION["vs_certificado_dig"])) {
    //             unset($_SESSION["vs_certificado_dig"]);
    //             unset($_SESSION["vs_venc_certificado_dig"]);
    //         }
    //         $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Cadastro realizado com sucesso."}';
    //     } else {
    //         $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    //     }
    //     pg_close($conn);
    // } else {
    //     $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível localizar a matriz desta empresa."}';
    // }

    $v_json = json_encode($json_msg);
    echo $v_json;
}



// SALVANDO REGISTRO
if ($v_acao == "EV_SALVAR") {
    var_dump("EV_SALVAR");
    die;

    $v_tipo = strtoupper(addslashes($_POST["v_tipo"]));

    $v_cnpj = preg_replace('/[^0-9]/', '', addslashes($_POST["v_cnpj"]));
    // $v_nome = strtoupper(addslashes($_POST["v_nome"]));
    $v_fantasia = strtoupper(addslashes($_POST["v_fantasia"]));
    $v_st_cadastro = 1;
    $v_uf = strtoupper(addslashes($_POST["v_uf"]));
    $v_fisco_certi_senha = addslashes($_POST["v_fisco_certi_senha"]);
    $v_fisco_cnpj_agrocontar = addslashes($_POST["v_fisco_cnpj_agrocontar"]);
    $v_atividade_principal = addslashes($_POST["v_atividade_principal"]);
    $v_telefone = preg_replace('/[^0-9]/', '', addslashes($_POST["v_telefone"]));
    $v_email =  addslashes($_POST["v_email"]);
    $v_cep = preg_replace('/[^0-9]/', '', addslashes($_POST["v_cep"]));
    $v_logradouro = strtoupper(addslashes($_POST["v_logradouro"]));
    $v_complemento = strtoupper(addslashes($_POST["v_complemento"]));
    $v_numero = addslashes($_POST["v_numero"]);
    $v_bairro = strtoupper(addslashes($_POST["v_bairro"]));
    $v_municipio = strtoupper(addslashes($_POST["v_municipio"]));
    $v_insc_estadual = addslashes($_POST["v_insc_estadual"]);
    $v_insc_municipal = addslashes($_POST["v_insc_municipal"]);
    $v_natureza_juridica = strtoupper(addslashes($_POST["v_natureza_juridica"]));
    $v_atividades_secundarias = addslashes($_POST["v_atividades_secundarias"]);
    $v_abertura = implode("-", array_reverse(explode("/", addslashes($_POST["v_abertura"]))));
    $v_mod_fisco = addslashes($_POST["v_mod_fisco"]);
    $v_mod_rh = addslashes($_POST["v_mod_rh"]);
    $v_mod_adm = addslashes($_POST["v_mod_adm"]);
    $v_mod_cons = addslashes($_POST["v_mod_cons"]);
    $v_abertura = implode("-", array_reverse(explode("/", $v_abertura)));

    $v_sql = "UPDATE db_adm.t_empresas SET \n" .
        "tipo = " . $v_tipo . ", \n" .
        "nome = '" . $v_nome . "', \n" .
        "descricao = '" . $v_fantasia . "', \n" .
        "st_cadastro = " . $v_st_cadastro . ", \n" .
        "uf = '" . $v_uf . "', \n" .
        "fisco_cnpj_agrocontar = " . $v_fisco_cnpj_agrocontar . ", \n";

    // SALVANDO CERTIFICADO QUANDO HOUVER
    if (isset($_SESSION["vs_certificado_dig"]) && !empty($_SESSION["vs_certificado_dig"])) {
        $v_sql .= "fisco_cert_pem = '" . $_SESSION["vs_certificado_dig"] . "', \n" .
            "fisco_cert_dt_validade = '" . $_SESSION["vs_venc_certificado_dig"] . "', \n" .
            "fisco_certi_senha = '" . $v_fisco_certi_senha . "', \n";
    }

    $v_sql .= "modulo_fisco = '" . $v_mod_fisco . "', \n" .
        "modulo_adm = '" . $v_mod_adm . "', \n" .
        "modulo_rh = '" . $v_mod_rh . "', \n" .
        "modulo_cons = '" . $v_mod_cons . "', \n" .
        "ativ_principal = '" . $v_atividade_principal . "', \n" .
        "telefone = '" . $v_telefone . "', \n" .
        "email = '" . $v_email . "', \n" .
        "cep = " . $v_cep . ", \n" .
        "logradouro = '" . $v_logradouro . "', \n" .
        "complemento = '" . $v_complemento . "', \n" .
        "numero = '" . $v_numero . "', \n" .
        "bairro = '" . $v_bairro . "', \n" .
        "municipio = '" . $v_municipio . "', \n" .
        "insc_estadual = '" . $v_insc_estadual . "', \n" .
        "insc_municipal = '" . $v_insc_municipal . "', \n" .
        "natureza_juridica = '" . $v_natureza_juridica . "', \n" .
        "ativ_secundarias = '" . $v_atividades_secundarias . "', \n";
    if ($v_abertura == "") {
        $v_sql .= "dat_abertura = null \n" .
            "WHERE cnpj = " . $v_cnpj;
    } else
        $v_sql .= "dat_abertura = '" . $v_abertura . "' \n" .
            "WHERE cnpj = " . $v_cnpj;

    if (pg_query($conn, $v_sql)) {
        unset($_SESSION["vs_certificado_dig"]);
        unset($_SESSION["vs_venc_certificado_dig"]);
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }
    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}



// GERANDO LISTA DE EMPRESAS CONTABEIS
if ($v_acao == "LISTA_EMP_CONTABIL") {
    var_dump("LISTA_EMP_CONTABIL");
    die;


    // $v_sql = "SELECT cnpj, nome FROM db_adm.t_empresas where tipo = 1 ORDER BY Nome ";

    // $result = pg_query($conn, $v_sql);

    // $v_dados = array();
    // while ($row = pg_fetch_assoc($result)) {
    //     $v_dados[] = array("cnpj" => $row["cnpj"], "nome" => $row["nome"]);
    // }
    $v_sql = "SELECT count(id_user) as linhas from db_adm_rh.t_log";
    if ($result = pg_query($conn, $v_sql)) {
        $row = pg_fetch_assoc($result);
        $v_linhas = $row["linhas"];
    }


    $v_sql = "select * from db_adm_rh.t_log";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    $v_dados[] = array("linhas" => $v_linhas);
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("id_user" => $row["id_user"], "data_hora" => $row["data_hora"], "id_empresa" => $row["id_empresa"], "id_processo" => $row["id_processo"], "descricao" => $row["descricao"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}




// EXCLUINDO REGISTRO
if ($v_acao == "EV_DESABILITAR_EMPRESA") {
    var_dump("EV_DESABILITAR_EMPRESA");
    die;

    $v_cnpj = preg_replace('/[^0-9]/', '', addslashes($_POST["v_cnpj"]));

    $v_sql = "UPDATE db_adm.t_empresas SET st_cadastro = 3 WHERE cnpj = " . $v_cnpj;

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Empresa desativada com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}



// BUSCA CNPJ
if ($v_acao == "buscar_cnpj") {
    var_dump("buscar_cnpj");
    die;
    //Capturar CNPJ
    $cnpj = preg_replace('/[^0-9]/', '', addslashes($_POST["v_cnpj"]));
    $cnpj = preg_replace('/\D/', '', $cnpj); //Retira os caracteres que não são dígitos

    ///Criando Comunicação cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.receitaws.com.br/v1/cnpj/" . $cnpj);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Comente esta linha quando o seu site estiver rodando em https
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $retorno = curl_exec($ch);
    curl_close($ch);

    $retorno = json_decode($retorno); //Ajuda a ser lido mais rapidamente
    echo json_encode($retorno, JSON_PRETTY_PRINT);
}







// BUSCA CNPJ
if ($v_acao == "V_UPLOAD_CERT") {
    var_dump("V_UPLOAD_CERT");
    die;

    $v_certPassword = addslashes($_POST["v_certPassword"]);
    if (!empty($v_certPassword)) {

        $v_cnpj_cert = preg_replace('/[^0-9]/', '', addslashes($_POST["v_cnpj"]));
        $arquivo_tmp = $_FILES['arquivo_cert']['tmp_name'];
        $v_data = file_get_contents($arquivo_tmp);

        // ###################################################### //
        // VALIDANDO A SENHA DO SERTIFICADO
        // ###################################################### //
        if (openssl_pkcs12_read($v_data, $v_certs, $v_certPassword)) {
            $CertPriv = array();
            $CertPriv = openssl_x509_parse(openssl_x509_read($v_certs['cert']));
            $v_priKey = $v_certs['pkey'];
            $v_certKey = $v_certs['cert'];
            $v_certKey_cnpj = explode(":", $CertPriv['subject']['CN'])[1];
            $validade = date('Y-m-d', $CertPriv['validTo_time_t']);

            // ###################################################### //
            // VALIDANDO A DATA DE VALIDADE
            // ###################################################### //
            if (date($validade) > date('Y-m-d')) {
                if ($v_certKey_cnpj == $v_cnpj_cert) {
                    // ###################################################### //
                    // GERANDO O CERTIFICADO PEM //
                    // ###################################################### //                      
                    $_SESSION["vs_certificado_dig"] = $v_priKey . "\n\n" . $v_certKey;
                    $_SESSION["vs_venc_certificado_dig"] = $validade;

                    $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Certificado capturado com sucesso.  Após reallizar as alterações cadastrais, clique no botão SALVAR para concluir o upload."}';
                } else {
                    // ###################################################### //
                    // CNPJ NÃO É COMPATIVEL COM O DA EMPRESA
                    // ###################################################### //
                    $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"CNPJ incompatível com o certificado."}';
                }
            } else {
                // ###################################################### //
                // CERTIFICADO COM DATA DE VALIDADE EXPIRADA
                // ###################################################### //
                $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Cartificado com a data de validade expirada."}';
            }
        } else {
            // ###################################################### //
            // A SENHA DO CERTIFICADO ESTÁ INCORRETA
            // ###################################################### //
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"A senha do certificado está incorreta."}';
        }
    } else {
        // ###################################################### //
        // A SENHA DO CERTIFICADO ESTÁ INCORRETA
        // ###################################################### //
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Informe a senha do certificado antes de importa-lo."}';
    }
    $v_json = json_encode($json_msg);
    echo $v_json;
}
