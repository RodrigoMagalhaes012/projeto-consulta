<?php
header("Content-Type: application/json; charset=utf-8");
include_once( "../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);

if($v_acao == 'EV_CARREGA_GH'){

    if (strpos($_SESSION["vs_array_access"], "T0023") > 0){

        $v_pos = strpos($_SESSION["vs_array_access"], "T0023");
        $v_gh_ler = substr($_SESSION["vs_array_access"],$v_pos+8,1);
        $v_gh_criar = substr($_SESSION["vs_array_access"],$v_pos+12,1);
        $v_gh_gravar = substr($_SESSION["vs_array_access"],$v_pos+16,1);
        $v_gh_excluir = substr($_SESSION["vs_array_access"],$v_pos+20,1);
        // var_dump($v_danfe_perm_ler."-".$v_danfe_perm_criar."-".$v_danfe_perm_gravar."-".$v_danfe_perm_excluir); die;

    }

    $v_nivel = addslashes($_POST["v_nivel"]);
    $v_grupo = addslashes($_POST["v_grupo"]);

    $v_sql = "select func.id_lider, func.nome, func.id, usu.nome colaborador, func1.nome lider,usu_lider.nome colaborador_lider, nivel.descricao nivel from db_adm.t_rh_funcao_gh func
                full join db_adm.t_user usu on usu.id = func.id_usuario
                inner join db_adm.t_rh_funcao_gh func1 on func.id_lider = func1.id
                inner join db_adm.t_rh_nivel_gh nivel on nivel.nivel = func.id_nivel and nivel.id_grupo = {$v_grupo}
                full join db_adm.t_user usu_lider on usu_lider.id = func1.id_usuario 
            where func.id_nivel = {$v_nivel} and func.data_finalizacao is null and func.id_grupo = {$v_grupo}";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    while($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Id" => $row["id"],
            "Nome" => $row["nome"],
            "Nivel" => $row["nivel"],
            "Lider" => $row["lider"],
            "Id_lider" => $row["id_lider"],
            "Colaborador" => $row["colaborador"],
            "Colaborador_lider" => $row['colaborador_lider']
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}

if ($v_acao == "LISTA_GH_LIDER") {

    $v_nivel = addslashes($_POST["v_nivel"]);
    $v_grupo = addslashes($_POST["v_grupo"]);

    $v_sql = "select func.id, func.nome from db_adm.t_rh_funcao_gh func where func.id_nivel < {$v_nivel} and func.data_finalizacao is null and id_grupo = {$v_grupo}";
    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while($row = pg_fetch_assoc($result)) {
        $v_dados[]=array(
            "Id" => $row["id"],
            "Nome" => $row["nome"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}

// GERANDO LISTA DE GESTORES
if ($v_acao == "LISTA_GESTORES") {

    $v_grupo = addslashes($_POST["v_grupo"]);

    $v_sql = "select db_emp from db_adm.t_empresas emp where id_grupo_gh = {$v_grupo}";

    $result = pg_query($conn, $v_sql);
    $v_dados = array();
    //pegar usuarios de empresas de determinado grupo de GH
    while($row = pg_fetch_assoc($result)) {

        $db_emp = str_pad($row["db_emp"], 4, "0", STR_PAD_LEFT);

        $v_sql1 = "SELECT colab.id_usuario, colab.nome FROM db_emp_{$db_emp}.t_rh_colaborador colab order by nome";
        $result1 = pg_query($conn, $v_sql1);

        // $v_usuarios = array();
        while($row = pg_fetch_assoc($result1)) {
            $v_dados[]=array(
                "Nome" => $row["nome"],
                "Id_usuario" => $row["id_usuario"]
            );
        }
        // array_push($v_dados, $v_usuarios);
    }

    sort($v_dados);
    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}

// GERANDO LISTA DE LIDERADOS
if ($v_acao == "LISTA_LIDERADOS") {

    $v_id = addslashes($_POST["v_id"]);
    $v_nivel = addslashes($_POST["v_nivel"]);
    $v_grupo = addslashes($_POST["v_grupo"]);

    $v_sql = "select func.id_lider, func.nome, func.id, usu.nome colaborador, func1.nome lider,usu_lider.nome colaborador_lider, nivel.descricao nivel from db_adm.t_rh_funcao_gh func
    inner join db_adm.t_user usu on usu.id = func.id_usuario
    inner join db_adm.t_rh_funcao_gh func1 on func.id_lider = func1.id
    inner join db_adm.t_rh_nivel_gh nivel on nivel.nivel = func.id_nivel 
    inner join db_adm.t_user usu_lider on usu_lider.id = func1.id_usuario 
    where func.id_nivel > {$v_nivel} and func.data_finalizacao is null and func.id_lider = {$v_id} and func.id_grupo = {$v_grupo}";
    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while($row = pg_fetch_assoc($result)) {
        $v_dados[]=array(
            "Id" => $row["id"],
            "Nome" => $row["nome"],
            "Colaborador" => $row["colaborador"]
        );
    }

    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}

// CADASTRANDO NOVO REGISTRO
if ($v_acao == "EV_NOVO") {

    $v_gh_nome = strtoupper(addslashes($_POST["v_gh_nome"]));
    $v_id_user_gestor = addslashes($_POST["v_id_user_gestor"]);
    $v_id_gh_lider = addslashes($_POST["v_id_gh_lider"]);
    $v_id_nivel = addslashes($_POST["v_id_nivel"]);
    $v_liderados = $_POST["v_id_liderados"];
    $v_data_inicio = addslashes($_POST["v_data_inicio"]);
    $v_grupo = addslashes($_POST["v_grupo"]);

    $v_trata_data_inicio = explode('-', $v_data_inicio);

    $v_data_inicio = "{$v_trata_data_inicio[0]}-{$v_trata_data_inicio[1]}-01";

    $v_sql = "select * from db_adm.t_rh_funcao_gh gh where id_usuario = {$v_id_user_gestor} and data_finalizacao is null and id_grupo = {$v_grupo}";
    $result = pg_query($conn, $v_sql);
    if(pg_fetch_assoc($result)){
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Usuário já cadastrado como gestor! Por favor, selecione outro gestor."}';
    } else {
        if($v_id_nivel == 0){
    
            $v_sql = "insert into db_adm.t_rh_funcao_gh (id_nivel, nome, id_usuario, data_inicio, id_grupo) values ({$v_id_nivel}, '{$v_gh_nome}', {$v_id_user_gestor}, '{$v_data_inicio}', {$v_grupo})";
    
            if (pg_query($conn, $v_sql)) {
                $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Cadastro realizado com sucesso."}';
            } else {
                $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
            }
    
        } else {
            $v_sql = "insert into db_adm.t_rh_funcao_gh (id_nivel, nome, id_usuario, id_lider, data_inicio, id_grupo) values ({$v_id_nivel}, '{$v_gh_nome}', {$v_id_user_gestor}, {$v_id_gh_lider}, '{$v_data_inicio}', {$v_grupo}) returning id";
            
            if ($result = pg_query($conn, $v_sql)) {
                $v_id_gh = pg_fetch_array($result,0)[0];
                $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Cadastro realizado com sucesso."}';
            } else {
                $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados. Entre em contato com o suporte local."}';
            }
        }
    
        if(sizeof($v_liderados) > 0 and $v_liderados[0] != 'null'){
            for ( $i=0 ; $i<sizeof($v_liderados) ; $i++ ) {
                $v_sql = "update db_adm.t_rh_funcao_gh
                set id_lider = {$v_id_gh} where id = {$v_liderados[$i]} and id_grupo = {$v_grupo}";
                pg_query($conn, $v_sql);
            }
        }
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}

// SELECIONANDO REGISTRO
if ($v_acao == "EV_SELECT") {

    $v_id = addslashes($_POST["v_id"]);
    $v_grupo = addslashes($_POST["v_grupo"]);

    $v_sql = "select func.nome, func.id, usu.nome colaborador, func1.nome lider, nivel.descricao nivel from db_adm.t_rh_funcao_gh func
                inner join db_adm.t_user usu on usu.id = func.id_usuario
                inner join db_adm.t_rh_funcao_gh func1 on func.id_lider = func1.id
                inner join db_adm.t_rh_nivel_gh nivel on nivel.nivel = func.id_nivel 
               where func.id = {$v_id} and func.data_finalizacao is null and id_grupo = {$v_grupo}";
    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while($row = pg_fetch_assoc($result)) {
        $v_dados=array(
            "Id" => $row["id"],
            "Nome" => $row["nome"],
            "Nivel" => $row["nivel"],
            "Lider" => $row["lider"],
            "Colaborador" => $row["colaborador"]
        );
    }
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}

if($v_acao == 'EV_FINALIZA_GESTAO'){

    $v_id = addslashes($_POST["v_id"]);
    $v_data_finalizacao = addslashes($_POST["v_data_finalizacao"]);
    $v_novo_gestor = addslashes($_POST["v_novo_gestor"]);
    $v_id_lider = addslashes($_POST["v_id_lider"]);
    $v_id_nivel = addslashes($_POST["v_id_nivel"]);
    $v_gh_nome = addslashes($_POST["v_gh_nome"]);
    $v_grupo = addslashes($_POST["v_grupo"]);

    $v_trata_data_inicio = explode('-', $v_data_finalizacao);

    $ultimo_dia = cal_days_in_month(CAL_GREGORIAN, $v_trata_data_inicio[1], $v_trata_data_inicio[0]);

    $v_data_finalizacao = "{$v_trata_data_inicio[0]}-{$v_trata_data_inicio[1]}-{$ultimo_dia}";

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
        arvore.nome_func,
        arvore.desc,
        arvore.desc_id,
        arvore.id_lider,
        arvore.data_finalizacao,
        arvore.id_usuario,
        tu.nome nome_usuario
        FROM
        arvore
        inner join db_adm.t_user tu on arvore.id_usuario = tu.id
        where desc_id like '%{$v_id}%' or arvore.id_lider is null
        ORDER BY
        arvore.desc;";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while($row = pg_fetch_assoc($result)){
        $v_dados[] = array(
            "descricao" => $row["desc"],
            "funcao" => $row["nome_func"],
            "id_lider" => $row["id_lider"],
            "id_usuario" => $row["id_usuario"]
        );
    }

    $insert_line = "insert into db_adm.t_rh_historico_gh (id_finalizacao, descricao_hierarquia, funcao, id_lider, id_usuario) values ";
    $lider = null;
    foreach ($v_dados as $dados) {
        $dados["id_lider"] ? $lider = $dados["id_lider"] : $lider = 'null';
        $insert_line .= "({$v_id}, '{$dados["descricao"]}', '{$dados["funcao"]}', {$lider}, {$dados["id_usuario"]}),";
    };

    pg_query($conn, substr($insert_line, 0, -1));

    $v_sql = "update db_adm.t_rh_funcao_gh
        set data_finalizacao = '{$v_data_finalizacao}'
        where id = {$v_id}
        ";
    
    pg_query($conn, $v_sql);

    //caso nao tenha um novo gestor para assumir a gestão
    if($v_novo_gestor == 0){
        $v_sql = "update db_adm.t_rh_funcao_gh
        set id_lider = {$v_id_lider} where id_lider = {$v_id}";

        pg_query($conn, $v_sql);

        //passa coloaboradores para a proxiam gestão
        $v_sql = "select * from db_adm.t_hist_gh where id_gh = {$v_id}";

        $result = pg_query($conn, $v_sql);

        $v_dados = array();
        while($row = pg_fetch_assoc($result)){
            $v_dados[] = array(
                "id_colaborador" => $row["id_colaborador"]
            );
        }

        $data = new DateTime($v_data_finalizacao);
        $data = $data->modify("+1 month");
        $data = $data->format('Y-m-d');
        //var_dump(gettype($data));
        $v_trata_data_inicio = explode('-', $data);

        $proximo_mes = "{$v_trata_data_inicio[0]}-{$v_trata_data_inicio[1]}-01";

        if(sizeof($v_dados) > 0){
            $insert_line = "insert into db_adm.t_hist_gh (id_gh, data, id_colaborador) values ";
            foreach ($v_dados as $dado) {
                $insert_line .= "({$v_id_lider}, '{$proximo_mes}', {$dado["id_colaborador"]}),";
            }
    
            pg_query($conn, substr($insert_line, 0, -1));

        }
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Gestão finalizada com sucesso."}';
    } else {

        $v_sql = "select * from db_adm.t_rh_funcao_gh gh where id_usuario = {$v_novo_gestor} and data_finalizacao is null";
        $result = pg_query($conn, $v_sql);
        if(pg_fetch_assoc($result)){
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Usuário já cadastrado como gestor! Por favor, selecione outro gestor."}';
        } else {

            $data = new DateTime($v_data_finalizacao);
            $data = $data->modify("+1 month");
            $data = $data->format('Y-m-d');
            //var_dump(gettype($data));
            $v_trata_data_inicio = explode('-', $data);



            $proximo_mes = "{$v_trata_data_inicio[0]}-{$v_trata_data_inicio[1]}-01";
        
            $v_sql = "insert into db_adm.t_rh_funcao_gh (id_nivel, nome, id_usuario, id_lider, data_inicio, id_grupo) values ({$v_id_nivel}, '{$v_gh_nome}', {$v_novo_gestor}, {$v_id_lider}, '{$proximo_mes}', {$v_grupo}) returning id ";

            $result = pg_query($conn, $v_sql);
            $v_id_gh = pg_fetch_array($result,0)[0];

            $v_sql = "update db_adm.t_rh_funcao_gh
            set id_lider = {$v_id_gh} where id_lider = {$v_id}";

            // var_dump($v_sql);

            $result = pg_query($conn, $v_sql);

            //passa coloaboradores para a proxiam gestão
            $v_sql = "select * from db_adm.t_hist_gh where id_gh = {$v_id}";

            $result = pg_query($conn, $v_sql);

            $v_dados = array();
            while($row = pg_fetch_assoc($result)){
                $v_dados[] = array(
                    "id_colaborador" => $row["id_colaborador"]
                );
            }

            if(sizeof($v_dados) > 0){
                $insert_line = "insert into db_adm.t_hist_gh (id_gh, data, id_colaborador) values ";
                foreach ($v_dados as $dado) {
                    $insert_line .= "({$v_id_gh}, '{$proximo_mes}', {$dado["id_colaborador"]}),";
                }
        
                pg_query($conn, substr($insert_line, 0, -1));

            }

            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Gestão finalizada com sucesso."}';
        }

    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

if($v_acao == 'EV_ATUALIZA_LIDER'){

    $v_id = addslashes($_POST["v_id"]);
    $v_id_lider = addslashes($_POST["v_id_lider"]);
    $v_data_nova_lideranca = addslashes($_POST["v_data_nova_lideranca"]);
    $v_grupo = addslashes($_POST["v_grupo"]);

    $v_trata_data_inicio = explode('-', $v_data_nova_lideranca);

    $v_data_nova_lideranca = "{$v_trata_data_inicio[0]}-{$v_trata_data_inicio[1]}-01";

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
        arvore.nome_func,
        arvore.desc,
        arvore.desc_id,
        arvore.id_lider,
        arvore.data_finalizacao,
        arvore.id_usuario,
        tu.nome nome_usuario
        FROM
        arvore
        inner join db_adm.t_user tu on arvore.id_usuario = tu.id
        where desc_id like '%{$v_id}%' or arvore.id_lider is null
        ORDER BY
        arvore.desc;";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while($row = pg_fetch_assoc($result)){
        $v_dados[] = array(
            "descricao" => $row["desc"],
            "funcao" => $row["nome_func"],
            "id_lider" => $row["id_lider"],
            "id_usuario" => $row["id_usuario"]
        );
    }

    $insert_line = "insert into db_adm.t_rh_historico_gh (id_finalizacao, descricao_hierarquia, funcao, id_lider, id_usuario) values ";
    $lider = null;
    foreach ($v_dados as $dados) {
        $dados["id_lider"] ? $lider = $dados["id_lider"] : $lider = 'null';
        $insert_line .= "({$v_id}, '{$dados["descricao"]}', '{$dados["funcao"]}', {$lider}, {$dados["id_usuario"]}),";
    };

    pg_query($conn, substr($insert_line, 0, -1));

    $v_sql = "update db_adm.t_rh_funcao_gh 
    set id_lider = {$v_id_lider},
    data_troca_lider = '{$v_data_nova_lideranca}'
    where id = {$v_id}";

    pg_query($conn, $v_sql);

    $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Lider modificado com sucesso."}';
    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}