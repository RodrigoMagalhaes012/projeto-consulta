<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$vs_id = addslashes($_SESSION["vs_id"]);
$v_acao = addslashes($_POST["v_acao"]);

if ($v_acao == 'DOWNLOAD_INFORME') {

    $v_sql = "SELECT tu.cpf from db_adm.t_user tu 
                where id = {$_SESSION["vs_id"]}";
    $v_sql1 = "SELECT cnpj from db_adm.t_empresas te 
                where db_emp = {$_SESSION["vs_db_empresa"]}";

    $result = pg_query($conn, $v_sql);
    $result1 = pg_query($conn, $v_sql1);

    $cpf = pg_fetch_result($result, null, 'cpf');
    $cnpj = pg_fetch_result($result1, null, 'cnpj');

    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
    $cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT);

    $v_dados = array(
        "cpf" => $cpf,
        "cnpj" => $cnpj
    );

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}
//TESTE SENDO REALIZADO

if ($v_acao == 'LISTAR_DOCUMENTOS') {

    $v_sql = "SELECT cpf 
    from db_adm.t_user 
        where id = {$vs_id}";

    $v_cpf = pg_fetch_array(pg_query($conn, $v_sql), 0)[0];

    $v_sql = "SELECT trp.nome_documento , trp.descricao, trp.ip_autenticacao, trp.usuario_autenticacao, trp.data_autenticacao, trp.ano_referencia, trp.id, trp.cpf, te.cnpj, trc.nome, trp.url_arquivo
    from db_adm_rh.t_rh_informe_rendimento trp 
    join db_adm.t_empresas as te 
    on trp.id_empresa = te.id 
    join db_adm_rh.t_rh_colaborador as trc 
                on trp.cpf = trc.cpf 
            where trp.id_empresa = {$_SESSION["vs_id_empresa"]}";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "nome_documento" => $row["nome_documento"],
            "descricao" => $row["descricao"],
            "ip" => $row["ip_autenticacao"],
            "usuario" => $row["usuario_autenticacao"],
            "data_autenticacao" => $row["data_autenticacao"],
            "ano_referencia" => $row["ano_referencia"],
            "nome_col" => $row["nome"],
            "id" => $row["id"],
            "url" => $row["url_arquivo"],
            "cpf" =>  str_pad($row["cpf"], 11, '0', STR_PAD_LEFT),
            "cnpj" => $row["cnpj"]

        );
    }

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}

if ($v_acao == "EV_AUTENTICA") {

    $timeZone = new DateTimeZone('America/Sao_Paulo');
    $v_data_autenticacao = new DateTime('now', $timeZone);
    $v_data_autenticacao = $v_data_autenticacao->format('Y-m-d H:i:s');
    $v_ip = $_SERVER['REMOTE_ADDR']; // Salva o IP do visitante
    $v_id_informe =  addslashes($_POST["v_id_informe"]);

    $v_sql = "SELECT nome 
                from db_adm.t_user 
                    where id = {$vs_id}";

    $v_usuario = pg_fetch_array(pg_query($conn, $v_sql), 0)[0];
    // var_dump($v_usuario);

    $v_sql = "SELECT cpf 
                from db_adm.t_user 
                    where id = {$vs_id}";

    $v_cpf = pg_fetch_array(pg_query($conn, $v_sql), 0)[0];
    // var_dump($v_cpf);

    $v_sql = "UPDATE db_adm_rh.t_rh_informe_rendimento
	             SET data_autenticacao = '{$v_data_autenticacao}',
	                 ip_autenticacao = '{$v_ip}',
	                 usuario_autenticacao = '{$v_usuario}'
			   where id_empresa = {$_SESSION["vs_id_empresa"]} 
				 and cpf = '{$v_cpf}' 
                 and id = '{$v_id_informe}'
				 RETURNING data_autenticacao, ip_autenticacao, usuario_autenticacao";

    $v_autenticacao = pg_fetch_array(pg_query($conn, $v_sql), 0);

    // var_dump($v_autenticacao);


    $dados = array(
        "data_hora" => $v_autenticacao[0],
        "ip" => $v_autenticacao[1],
        "usuario" => $v_autenticacao[2]
    );

    echo json_encode($dados);
}
