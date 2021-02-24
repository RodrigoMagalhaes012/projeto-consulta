<?php
header("Content-Type: application/json; charset=utf-8");
include_once( "../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO A AÇÃO A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// ATIVANDO ACESSO DO USUÁRIO
if ($v_acao == "ATIVACCESS") {
    // RECEBENDO VARIAVEIS DO FORMULÁRIO
    $v_senha = addslashes($_POST["v_senha"]);
    $v_chave = addslashes($_POST["v_chave"]);

    $v_sql = "SELECT Id, Nome FROM db_adm.t_user WHERE Chave = "."'" .$v_chave."'";

    // var_dump($v_sql);
    
    $_SESSION["database_adm"] = "S";
    $result = pg_query($conn, $v_sql);
    if ($result) {
        $row = pg_fetch_assoc($result);

        $v_senha_criptografada = password_hash($v_senha, PASSWORD_DEFAULT);

        if(!empty($row["id"])){

            $v_chave = randString(60);

            $timeZone = new DateTimeZone('America/Sao_Paulo');
            $v_data = new DateTime('now', $timeZone);
            $v_data = $v_data->format('Y-m-d H:i:s');

            $v_sql2 = "UPDATE db_adm.t_user SET \n".
            "St_Cadastro = 1, \n".
            "Chave = '". $v_chave ."' , \n".
            "Senha = '".$v_senha_criptografada."', \n".
            "Dt_Alter_Senha = '{$v_data}' \n".
            "WHERE Id = ".$row["id"];
            $_SESSION["database_adm"] = "S";
            // var_dump($v_sql2);
            pg_query($conn, $v_sql2);

            $json_msg = '{"msg_ev":"success", "msg":"index.php"}';
        } else {
            $json_msg = '{"msg_ev":"error", "msg":"Falha na autenticação.  Favor, acionar o seu gestor."}';
        }

    } else {
        $json_msg = '{"msg_ev":"error", "msg":"Falha na autenticação.  Favor, acionar o seu gestor."}';
    }
    pg_close($conn);
    echo json_encode($json_msg);
}

//Essa função gera um valor de String aleatório do tamanho recebendo por parametros
function randString($size)
{
    //String com valor possíveis do resultado, os caracteres pode ser adicionado ou retirados conforme sua necessidade
    $basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    $return = "";

    for ($count = 0; $size > $count; $count++) {
        //Gera um caracter aleatorio
        $return .= $basic[rand(0, strlen($basic) - 1)];
    }

    return $return;
}