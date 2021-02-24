<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);
$vs_id = $_SESSION["vs_id"];




// SELECIONANDO REGISTRO
if ($v_acao == "EV_CARREGA_DADOS") {

    $v_sql = "SELECT colab.id, usu.nome, usu.email, colab.dt_nasc , colab.id_sexo, colab.cpf,
                     colab.celular, colab.cel_emergencia, colab.email_pessoal, colab.linkedin,
                     colab.instagram, colab.facebook, colab.twitter, usu.url_arquivo
                from db_adm.t_user usu 
             inner join db_adm_rh.t_rh_colaborador colab on usu.id = colab.id_usuario 
            where usu.id = {$_SESSION["vs_id"]}";
    $result = pg_query($conn, $v_sql);


    $v_dados = array(
        "Id_usuario" => $_SESSION["vs_id"]
    );

    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Id" => $row["id"],
            "Nome" => $row["nome"],
            "Cpf" => $row["cpf"],
            "Email" => $row["email"],
            "Email_Pessoal" => $row["email_pessoal"],
            "Celular" => $row["celular"],
            "Contato_Emergencia" => $row["cel_emergencia"],
            "Linkedin" => $row["linkedin"],
            "Instagram" => $row["instagram"],
            "Twitter" => $row["twitter"],
            "Facebook" => $row["facebook"],
            "Sexo" => $row["id_sexo"],
            "url_arquivo" => $row["url_arquivo"],
            "dt_nasc" => $row["dt_nasc"]
        );
    }
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// SALVANDO REGISTRO
if ($v_acao == "EV_SALVAR") {

    ################ Busca Id Tabela Departamento
    $v_id = addslashes($_POST["v_id"]);
    $v_celular = addslashes($_POST["v_celular"]);
    $v_email_pessoal = strtolower(addslashes($_POST["v_email_pessoal"]));
    $v_contato_emergencia = addslashes($_POST["v_contato_emergencia"]);
    $v_linkedin = addslashes($_POST["v_linkedin"]);
    $v_facebook = addslashes($_POST["v_faceboook"]);
    $v_instagram = addslashes($_POST["v_instagram"]);
    $v_twitter = addslashes($_POST["v_twitter"]);

    $v_sql = "SELECT cpf
    from db_adm.t_user
    where id = {$vs_id}";

    $v_cpf = pg_fetch_array(pg_query($conn, $v_sql), 0)[0];

    $v_sql = "UPDATE db_adm_rh.t_rh_colaborador 
                    SET 
                    celular = '{$v_celular}',
                    email_pessoal = '{$v_email_pessoal}',
                    cel_emergencia = '{$v_contato_emergencia}',
                    linkedin = '{$v_linkedin}',
                    facebook = '{$v_facebook}',
                    instagram = '{$v_instagram}',
                    twitter = '{$v_twitter}'
             WHERE cpf = {$v_cpf}";

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}
