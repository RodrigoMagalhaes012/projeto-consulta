<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);

// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_RUBRICAS") {

    $v_sql = "SELECT rubrica,tipo,descricao,case when (tipo_lancamento = 1) then 'checked' else '' end as tipo_lancamento,case when (caracteristica = 1) then 'checked' else '' end as caracteristica,id_tabela FROM db_adm_rh.t_rh_holerite_rubricas";
    $result = pg_query($conn, $v_sql);

    $v_dados[] = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("Rubrica" => $row["rubrica"], "Tipo" => $row["tipo"], "Descricao" => $row["descricao"], "Tipo_lancamento" => $row["tipo_lancamento"], "Caracteristica" => $row["caracteristica"], "Id_tabela" => $row["id_tabela"]);
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}

// SALVANDO INFORMAÇÕES NO BANCO
if ($v_acao == 'EV_SALVAR') {

    $v_vet_dados = $_POST['v_vet_dados'];
    foreach ($v_vet_dados as $dados) {

        $v_sql = "update db_adm_rh.t_rh_holerite_rubricas set
         tipo_lancamento = " . $dados['v_tipo_lancamento'] . ",
         caracteristica = " . $dados['v_caracteristica'] . " 
         where rubrica = " . $dados['v_rubrica'];

        pg_query($conn, $v_sql);
    }

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Salvo com sucesso!"}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

// SELECIONANDO REGISTRO
if ($v_acao == "EV_SELECT") {

    $v_rubrica = addslashes($_POST["v_rubrica"]);

    $v_sql = "SELECT rubrica,tipo,descricao,tipo_lancamento,caracteristica,id_tabela FROM db_adm_rh.t_rh_holerite_rubricas WHERE Rubrica = " . $v_rubrica;

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Rubrica" => $row["rubrica"],
            "Tipo" => $row["tipo"],
            "Descricao" => $row["descricao"],
            "Tipo_lancamento" => $row["tipo_lancamento"],
            "Caracteristica" => $row["caracteristica"],
            "Id_tabela" => $row["id_tabela"]
        );
    }
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}
