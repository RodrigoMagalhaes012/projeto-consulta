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



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_EMPRESAS") {
    // $v_tab_campo = addslashes($_POST["v_tab_campo"]);
    // $v_tab_ordem = addslashes($_POST["v_tab_ordem"]);
    // $v_tab_busca_campo = explode("|", addslashes($_POST["v_tab_busca_campo"]))[0];
    // $v_tab_busca_campo_tipo = explode("|", addslashes($_POST["v_tab_busca_campo"]))[1];
    // $v_tab_busca_texto = addslashes($_POST["v_tab_busca_texto"]);
    // $v_tab_sql_limit_in = addslashes($_POST["v_tab_sql_limit_in"]);
    // $v_limit = addslashes($_POST["v_limit"]);
    // $v_linhas = 0;

    // CONSTRUINDO OS FILTROS
    $v_filtro = "";
    if (!empty($v_tab_busca_texto)) {

        if ($v_tab_busca_campo_tipo == "txt") {
            $v_filtro = "WHERE " . $v_tab_busca_campo . " like '%" . $v_tab_busca_texto . "%'";
        } else {
            $v_filtro = "WHERE " . $v_tab_busca_campo . " = " . $v_tab_busca_texto;
        }
    }

    // CONTANDO REGISTROS PARA GERAR OS BOTÕES DE NAVEGAÇÃO
    $v_sql = "SELECT count(id) as linhas from db_adm.t_empresas " . $v_filtro;
    $_SESSION["database_adm"] = "S";
    if ($result = pg_query($conn, $v_sql)) {
        $row = pg_fetch_assoc($result);
        $v_linhas = $row["linhas"];
    }

    // GERANDO A LISTA
    $v_sql = "SELECT Id, Nome, Cnpj, TO_CHAR(fisco_cert_dt_validade, 'DD/MM/YYYY') as fisco_cert_dt_validade, St_Cadastro AS St_Cadastro_id, CASE St_Cadastro WHEN 0 THEN 'PENDENTE' WHEN 1 THEN 'ATIVO' WHEN 2 THEN 'SUSPENSO' ELSE 'CANCELADO' END AS St_Cadastro FROM db_adm.t_empresas ";
    // . $v_filtro . " ORDER BY " . $v_tab_campo . " " . $v_tab_ordem . " OFFSET " . $v_tab_sql_limit_in . " LIMIT " . $v_limit;
    $_SESSION["database_adm"] = "S";
    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    $v_dados[] = array("linhas" => $v_linhas);
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Id" => $row["id"],
            "Nome" => $row["nome"],
            "Cnpj" => str_pad($row["cnpj"], 14, '0', STR_PAD_LEFT),
            "fisco_cert_dt_validade" => $row["fisco_cert_dt_validade"],
            "St_Cadastro" => $row["st_cadastro"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}



// BUSCA DB_EMP
if ($v_acao == "EV_DB_EMP") {

    $v_cnpj = preg_replace('/[^0-9]/', '', addslashes($_POST["v_cnpj"]));
    $v_sql = "select db_emp from db_adm.t_empresas where substr(LPAD(CAST(cnpj AS VARCHAR),14,'0'), 0, 13) = concat(substr(LPAD(CAST('" . $v_cnpj . "' AS VARCHAR),14,'0'), 0, 9), '0001')";
    $result = pg_query($conn, $v_sql);

    if ($row = pg_fetch_assoc($result)) {
        echo $row["db_emp"];
    } else {
        echo "0";
    }
}



// SELECIONANDO REGISTRO
if ($v_acao == "EV_SELECT") {

    $v_id = addslashes($_POST["v_id"]);

    $v_sql = "SELECT  id, tipo, nome, descricao, st_cadastro, cnpj, uf, url_arquivo,
        fisco_certi_senha, fisco_cnpj_agrocontar,
        id_tab_cargos, id_tab_departamentos, id_tab_rubricas, id_tab_politica_senhas,
        modulo_fisco, modulo_rh, modulo_adm, modulo_cons, ativ_principal, telefone,
        email, cep, logradouro, complemento, numero, bairro, municipio,
        insc_estadual, insc_municipal, natureza_juridica, ativ_secundarias, dat_abertura,
        TO_CHAR(fisco_cert_dt_validade, 'DD/MM/YYYY HH:II:SS') as fisco_cert_dthr_validade,
        case when length(fisco_cert_pem) > 30 then 'OK' else 'vazio' end as check_cert, 
        case when fisco_cert_dt_validade > CURRENT_TIMESTAMP then 'OK' else 'expirado' end as check_cert_dt_validade
        FROM db_adm.t_empresas WHERE id =  {$v_id}";
    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "id" => $row["id"],
            "tipo" => $row["tipo"],
            "nome" => $row["nome"],
            "descricao" => $row["descricao"],
            "st_cadastro" => $row["st_cadastro"],
            "cnpj" => str_pad($row["cnpj"], 14, '0', STR_PAD_LEFT),
            "uf" => $row["uf"],
            "fisco_certi_senha" => $row["fisco_certi_senha"],
            "fisco_cnpj_agrocontar" => $row["fisco_cnpj_agrocontar"],
            "modulo_fisco" => $row["modulo_fisco"],
            "modulo_rh" => $row["modulo_rh"],
            "modulo_adm" => $row["modulo_adm"],
            "modulo_cons" => $row["modulo_cons"],
            "ativ_principal" => $row["ativ_principal"],
            "telefone" => $row["telefone"],
            "email" => $row["email"],
            "cep" => $row["cep"],
            "logradouro" => $row["logradouro"],
            "complemento" => $row["complemento"],
            "numero" => $row["numero"],
            "bairro" => $row["bairro"],
            "municipio" => $row["municipio"],
            "insc_estadual" => $row["insc_estadual"],
            "insc_municipal" => $row["insc_municipal"],
            "natureza_juridica" => $row["natureza_juridica"],
            "ativ_secundarias" => $row["ativ_secundarias"],
            "dat_abertura" => $row["dat_abertura"],
            "url_arquivo" => $row["url_arquivo"],
            "fisco_cert_dthr_validade" => $row["fisco_cert_dthr_validade"],
            "check_cert" => $row["check_cert"],
            "check_cert_dt_validade" => $row["check_cert_dt_validade"],
            "id_tab_cargos" => $row["id_tab_cargos"],
            "id_tab_departamentos" => $row["id_tab_departamentos"],
            "id_tab_rubricas" => $row["id_tab_rubricas"],
            "id_tab_politica_senhas" => $row["id_tab_politica_senhas"]
        );
    }
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// CADASTRANDO NOVO REGISTRO
if ($v_acao == "EV_NOVO") {

    $v_tipo = strtoupper(addslashes($_POST["v_tipo"]));
    $v_cnpj = preg_replace('/[^0-9]/', '', addslashes($_POST["v_cnpj"]));
    $v_nome = strtoupper(addslashes($_POST["v_nome"]));
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
    $v_db_emp = addslashes($_POST["v_db_emp"]);
    $v_abertura = implode("-", array_reverse(explode("/", $v_abertura)));

    $v_cep ? $v_cep : $v_cep = "null";
    $v_abertura ? $v_abertura = "\'{$v_abertura}\'" : $v_abertura = "null";
    ////////////////////////////////////////////////////
    // CASO A EMPRESA SEJA UMA CONTÁBIL, MATRIZ OU CPF
    ////////////////////////////////////////////////////
    if ($v_tipo == 1 || $v_tipo == 2 || $v_tipo == 5) {
        // OBTENDO O NOVO CODIGO EMP
        $v_sql = "select MAX(db_emp)+1 as new_db_emp from db_adm.t_empresas";
        $result = pg_query($conn, $v_sql);
        if ($row = pg_fetch_assoc($result)) {
            $v_db_emp = str_pad($row["new_db_emp"], 4, '0', STR_PAD_LEFT);
        }
    }

    if ($v_db_emp > 0) {

        // SALVANDO CERTIFICADO QUANDO HOUVER
        if (isset($_SESSION["vs_certificado_dig"]) && !empty($_SESSION["vs_certificado_dig"])) {

            $v_sql = "INSERT INTO db_adm.t_empresas (db_emp, tipo, nome, descricao, fisco_cnpj_agrocontar, st_cadastro, cnpj, uf, " .
                "ativ_principal, telefone, email, cep, logradouro, complemento, numero, bairro, municipio, " .
                "insc_estadual, insc_municipal, natureza_juridica, ativ_secundarias, dat_abertura, modulo_fisco, modulo_rh, modulo_adm, modulo_cons, fisco_cert_pem, fisco_cert_dt_validade, fisco_certi_senha)" . "\n" .
                "VALUES(" . $v_db_emp . ", " . $v_tipo . ",'" . $v_nome . "','" . $v_fantasia . "'," . $v_fisco_cnpj_agrocontar . "," . $v_st_cadastro . " , $v_cnpj ,'" . $v_uf . "','" . $v_atividade_principal . "', '" . $v_telefone . "','" . $v_email . "', $v_cep,'" . $v_logradouro . "','" . $v_complemento . "', '" . $v_numero . "' ,'" . $v_bairro . "','" . $v_municipio . "','" . $v_insc_estadual . "','" . $v_insc_municipal . "','" . $v_natureza_juridica . "','" . $v_atividades_secundarias . "'," . $v_abertura . ", '" . $v_mod_fisco . "' ,'" . $v_mod_rh . "' ,'" . $v_mod_adm . "' ,'" . $v_mod_cons . "', '" . $_SESSION["vs_certificado_dig"] . "', '" . $_SESSION["vs_venc_certificado_dig"] . "', '" . $v_fisco_certi_senha . "')";
        } else {

            $v_sql = "INSERT INTO db_adm.t_empresas (db_emp, tipo, nome, descricao, fisco_cnpj_agrocontar, st_cadastro, cnpj, uf, " .
                "ativ_principal, telefone, email, cep, logradouro, complemento, numero, bairro, municipio, " .
                "insc_estadual, insc_municipal, natureza_juridica, ativ_secundarias, dat_abertura, modulo_fisco, modulo_rh, modulo_adm, modulo_cons)" . "\n" .
                "VALUES(" . $v_db_emp . ", " . $v_tipo . ",'" . $v_nome . "','" . $v_fantasia . "'," . $v_fisco_cnpj_agrocontar . "," . $v_st_cadastro . " , $v_cnpj ,'" . $v_uf . "','" . $v_atividade_principal . "', '" . $v_telefone . "','" . $v_email . "', $v_cep,'" . $v_logradouro . "','" . $v_complemento . "', '" . $v_numero . "' ,'" . $v_bairro . "','" . $v_municipio . "','" . $v_insc_estadual . "','" . $v_insc_municipal . "','" . $v_natureza_juridica . "','" . $v_atividades_secundarias . "'," . $v_abertura . ", '" . $v_mod_fisco . "' ,'" . $v_mod_rh . "' ,'" . $v_mod_adm . "' ,'" . $v_mod_cons . "')";
        }

        if (pg_query($conn, $v_sql)) {

            $v_sql = "select id from db_adm.t_empresas where cnpj = " . $v_cnpj;
            $result = pg_query($conn, $v_sql);
            if ($row = pg_fetch_assoc($result)) {

                $v_sql = "INSERT INTO db_adm_lgpd.t_lgpd_config_categorias (id_empresa, nome) VALUES
                (" . $row["id"] . ", 'ETNIA'),
                (" . $row["id"] . ", 'SAÚDE'),
                (" . $row["id"] . ", 'GENÉTICO'),
                (" . $row["id"] . ", 'IDENTIFICAÇÃO')";
                pg_query($conn, $v_sql);

                $v_sql = "INSERT INTO db_adm_lgpd.t_lgpd_config_req_legais (id_empresa, nome) VALUES
                (" . $row["id"] . ", 'CUMPRIMENTO DE OBRIGAÇÃO LEGAL OU REGULATÓRIA'),
                (" . $row["id"] . ", 'LEGÍTIMO INTERESSE'),
                (" . $row["id"] . ", 'CONSENTIMENTO')";
                pg_query($conn, $v_sql);

                $v_sql = "INSERT INTO db_adm_lgpd.t_lgpd_config_tipos (id_empresa, nome) VALUES
                (" . $row["id"] . ", 'DADO PESSOAL'),
                (" . $row["id"] . ", 'DADO PESSOAL SENSÍVEL'),
                (" . $row["id"] . ", 'DADO PROFISSIONAL'),
                (" . $row["id"] . ", 'PÚBLICO')";
                pg_query($conn, $v_sql);

                $v_sql = "INSERT INTO db_adm_lgpd.t_lgpd_dpo_config (id_empresa, campo, tipo, categoria, acao, finalidade, req_legal, campo_id) VALUES
                (" . $row["id"] . ", 'BANCO', 3, 3, 2, 'Dado utilizado para consulta pela empresa para realizar o pagamento.', 2, 1),
                (" . $row["id"] . ", 'CELULAR P/ EMERGÊNCIAS', 3, 4, 1, 'Dado utilizado para consulta pela empresa em situações emergenciais. ', 2, 2),
                (" . $row["id"] . ", 'CELULAR PESSOAL', 2, 4, 1, 'Dado utilizado para consulta pela empresa quando necessário entrar em contato com o colaborador fora do ambiente de trabalho. ', 2, 3),
                (" . $row["id"] . ", 'CIDADE DE NASCIMENTO', 2, 1, 2, 'Dado utilizado para preenchimento da obrigação E-social.', 1, 4),
                (" . $row["id"] . ", 'DATA DA ADMISSÃO', 3, 4, 2, 'Dado utilizado para preenchimento da obrigação E-social.', 1, 5),
                (" . $row["id"] . ", 'DATA DE NASCIMENTO', 3, 3, 2, 'Dado utilizado para preenchimento da obrigação E-social.', 1, 6),
                (" . $row["id"] . ", 'DIAS DE EXPERIÊNCIA', 3, 4, 2, 'Dado utilizado para preenchimento da obrigação E-social.', 1, 7),
                (" . $row["id"] . ", 'DOC. CERT. DE RESERVISTA', 1, 4, 2, 'Dado utilizado para preenchimento da obrigação E-social.', 1, 8),
                (" . $row["id"] . ", 'DOC. DE CNH', 1, 4, 2, 'Dado utilizado para preenchimento da obrigação E-social.', 1, 9),
                (" . $row["id"] . ", 'DOC. DE CPF', 1, 4, 2, 'Dado utilizado para preenchimento da obrigação E-social.', 1, 10),
                (" . $row["id"] . ", 'DOC. DE CTPS', 1, 4, 2, 'Dado utilizado para preenchimento da obrigação E-social.', 1, 11),
                (" . $row["id"] . ", 'DOC. DE PIS', 1, 4, 2, 'Dado utilizado para preenchimento da obrigação E-social.', 1, 12),
                (" . $row["id"] . ", 'DOC. DE RG', 1, 4, 2, 'Dado utilizado para preenchimento da obrigação E-social.', 1, 13),
                (" . $row["id"] . ", 'DOC. TITULO ELEITORAL', 1, 4, 2, '-', 1, 14),
                (" . $row["id"] . ", 'E-MAIL COMERCIAL', 3, 4, 2, '-', 1, 15),
                (" . $row["id"] . ", 'E-MAIL PESSOAL', 1, 4, 1, '-', 2, 16),
                (" . $row["id"] . ", 'ENDEREÇO PESSOAL', 2, 1, 2, '-', 1, 17),
                (" . $row["id"] . ", 'ESCOLARIDADE', 1, 4, 2, '-', 1, 18),
                (" . $row["id"] . ", 'ESTADO CIVIL', 1, 4, 2, 'A INFORMAÇÃO DO ESTADO CIVIL É UTILIZADA NO ENVIO DE INFORMAÇÕES PARA O E-SOCIAL, BEM COMO PARA DEFINIR SE O COLABORADOR', 1, 19),
                (" . $row["id"] . ", 'NOME', 1, 4, 2, '-', 1, 20),
                (" . $row["id"] . ", 'NOME DA MÃE', 2, 3, 2, '-', 1, 21),
                (" . $row["id"] . ", 'NOME DO PAI', 2, 3, 2, '-', 1, 22),
                (" . $row["id"] . ", 'PAÍS DE NASCIMENTO', 2, 1, 2, '-', 1, 23),
                (" . $row["id"] . ", 'PCD', 2, 2, 2, '-', 1, 24),
                (" . $row["id"] . ", 'PRORROGAÇÃO DE EXPERIÊNCIA', 3, 4, 2, '-', 1, 25),
                (" . $row["id"] . ", 'REDE SOCIAL FACEBOOK', 1, 4, 1, '-', 3, 26),
                (" . $row["id"] . ", 'REDE SOCIAL INSTAGRAM', 1, 4, 1, '-', 3, 27),
                (" . $row["id"] . ", 'REDE SOCIAL LINKEDIN', 1, 4, 1, '-', 3, 28),
                (" . $row["id"] . ", 'REDE SOCIAL TWITTER', 1, 4, 1, '-', 3, 29),
                (" . $row["id"] . ", 'SEXO', 2, 3, 2, '-', 1, 30),
                (" . $row["id"] . ", 'STATUS DO CADASTRO', 3, 4, 2, '-', 1, 31),
                (" . $row["id"] . ", 'TELEFONE PESSOAL', 1, 4, 1, '-', 2, 32),
                (" . $row["id"] . ", 'TIPO DO CONTRATO', 3, 4, 2, '-', 1, 33)";
                pg_query($conn, $v_sql);
            }

            // REMOVENDO A VARIAVEL DE SESSÃO DO CERTIFICADO
            if (isset($_SESSION["vs_certificado_dig"])) {
                unset($_SESSION["vs_certificado_dig"]);
                unset($_SESSION["vs_venc_certificado_dig"]);
            }
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Cadastro realizado com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }
        pg_close($conn);
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível localizar a matriz desta empresa."}';
    }

    $v_json = json_encode($json_msg);
    echo $v_json;
}



// SALVANDO REGISTRO
if ($v_acao == "EV_SALVAR") {

    $v_tipo = strtoupper(addslashes($_POST["v_tipo"]));
    $v_cnpj = preg_replace('/[^0-9]/', '', addslashes($_POST["v_cnpj"]));
    $v_nome = strtoupper(addslashes($_POST["v_nome"]));
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
    $v_id_cargo = addslashes($_POST["v_id_cargo"]);
    $v_id_departamento = addslashes($_POST["v_id_departamento"]);
    $v_id_rubrica = addslashes($_POST["v_id_rubrica"]);
    $v_id_politica_senhas = addslashes($_POST["v_id_politica_senhas"]);


    $v_sql = "UPDATE db_adm.t_empresas SET \n" .
        "tipo = " . $v_tipo . ", \n" .
        "nome = '" . $v_nome . "', \n" .
        "descricao = '" . $v_fantasia . "', \n" .
        "st_cadastro = " . $v_st_cadastro . ", \n" .
        "uf = '" . $v_uf . "', \n" .
        "id_tab_cargos = " . $v_id_cargo . ", \n" .
        "id_tab_departamentos = " . $v_id_departamento . ", \n" .
        "id_tab_rubricas = " . $v_id_rubrica . ", \n" .
        "id_tab_politica_senhas = " . $v_id_politica_senhas . ", \n" .
        "fisco_cnpj_agrocontar = " . $v_fisco_cnpj_agrocontar . ", \n";

    // var_dump($v_sql);

    // SALVANDO CERTIFICADO QUANDO HOUVER
    if (isset($_SESSION["vs_certificado_dig"]) && !empty($_SESSION["vs_certificado_dig"])) {
        $v_sql .= "fisco_cert_pem = '" . $_SESSION["vs_certificado_dig"] . "', \n" .
            "fisco_cert_dt_validade = '" . $_SESSION["vs_venc_certificado_dig"] . "', \n" .
            "fisco_certi_senha = '" . $v_fisco_certi_senha . "', \n";
        // var_dump($v_sql);
    }

    $v_sql .= "modulo_fisco = '" . $v_mod_fisco . "', \n" .
        "modulo_adm = '" . $v_mod_adm . "', \n" .
        "modulo_rh = '" . $v_mod_rh . "', \n" .
        "modulo_cons = '" . $v_mod_cons . "', \n" .
        "ativ_principal = '" . $v_atividade_principal . "', \n" .
        "telefone = '" . $v_telefone . "', \n" .
        "email = '" . $v_email . "', \n" .
        "logradouro = '" . $v_logradouro . "', \n" .
        "complemento = '" . $v_complemento . "', \n" .
        "numero = '" . $v_numero . "', \n" .
        "bairro = '" . $v_bairro . "', \n" .
        "municipio = '" . $v_municipio . "', \n" .
        "insc_estadual = '" . $v_insc_estadual . "', \n" .
        "insc_municipal = '" . $v_insc_municipal . "', \n" .
        "natureza_juridica = '" . $v_natureza_juridica . "', \n" .
        "ativ_secundarias = '" . $v_atividades_secundarias . "', \n";
    // var_dump($v_sql);


    if ($v_cep == "") {
        $v_sql .= "cep = null, \n";
    } else {
        $v_sql .= "cep = " . $v_cep . ", \n";
    }
    if ($v_abertura == "") {
        $v_sql .= "dat_abertura = null \n" .
            "WHERE cnpj = " . $v_cnpj;
        // var_dump($v_sql);
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

    $v_sql = "SELECT cnpj, nome FROM db_adm.t_empresas where tipo = 1 ORDER BY Nome ";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("cnpj" => $row["cnpj"], "nome" => $row["nome"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// GERANDO LISTA DE CARGOS
if ($v_acao == "LISTA_CARGO") {

    $v_sql = "SELECT id, descricao FROM db_adm_rh.t_rh_tabela_cargo ORDER BY descricao";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("id" => $row["id"], "descricao" => $row["descricao"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// GERANDO LISTA DE DEPARTAMENTOS
if ($v_acao == "LISTA_DEPARTAMENTO") {

    $v_sql = "SELECT id,descricao FROM db_adm_rh.t_rh_tabela_departamento ORDER BY descricao";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("id" => $row["id"], "descricao" => $row["descricao"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// GERANDO LISTA DE RUBRICAS
if ($v_acao == "LISTA_RUBRICA") {

    $v_sql = "SELECT id,descricao FROM db_adm_rh.t_rh_holerite_rubricas_tabela ORDER BY descricao";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("id" => $row["id"], "descricao" => $row["descricao"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// GERANDO LISTA DE POLITICA DE SENHA
if ($v_acao == "LISTA_POLITICA_SENHA") {

    $v_sql = "SELECT id, descricao FROM db_adm_rh.t_rh_tabela_politica_senhas ORDER BY descricao";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("id" => $row["id"], "descricao" => $row["descricao"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// EXCLUINDO REGISTRO
if ($v_acao == "EV_DESABILITAR_EMPRESA") {

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
