<?php
header("Content-Type: application/json; charset=utf-8");
include_once( "../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);

if($v_acao == 'CARREGA_USUARIOS'){

    $v_sql = "select tu.id, tu.nome, tu.email from db_adm_rh.t_rh_colaborador trc
    join dB_adm.t_user tu on tu.id = trc.id_usuario 
    where trc.id_empresa = {$_SESSION["vs_id_empresa"]} and tu.st_bloqueio = 0";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while($row = pg_fetch_assoc($result)){
        $v_dados[] = array(
            "id" => $row['id'],
            "nome" => $row['nome'],
            "email" => $row['email']
        );
    }

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
    
}

if($v_acao == 'ENVIAR_EMAIL'){

    $v_emails = $_POST["v_emails"];

    enviar_email($v_emails, $conn);

    $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"O envio foi realizado, por favor, confira as caixas de entrada dos emails."}';

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

function enviar_email($emails, $conn){
    require "../../class/gmail/ClassEmail.php";
    include_once("../../class/php/class_conect_db.php");
    $v_emails = array();
    foreach ($emails as $email) {
        # code...
        $v_sql = "SELECT Id, split_part(Nome,' ',1) as Nome, Email, Chave FROM db_adm.t_user WHERE email = '{$email}'";
        // var_dump($v_sql);
        $result = pg_query($conn, $v_sql);
    
        if ($row = pg_fetch_assoc($result)) {
    
            $timeZone = new DateTimeZone('America/Sao_Paulo');
            $v_data = new DateTime('now', $timeZone);
            $v_hora = $v_data->format('H');
            if ($v_hora >= 12 && $v_hora < 18) {
                $v_welcome = "Boa tarde";
            } else if ($v_hora >= 0 && $v_hora < 12) {
                $v_welcome = "Bom dia";
            } else {
                $v_welcome = "Boa noite";
            }
    
            $msg = "    <style>
            #btn_ativar {
              background-color: #4caf50;
              border: none;
              color: white;
              padding: 15px 32px;
              text-align: center;
              text-decoration: none;
              display: inline-block;
              font-size: 16px;
              margin: 4px 2px;
              cursor: pointer;
              border-radius: 12px;
            }
          </style>
          <center>
            <img src='https://uploaddeimagens.com.br/images/002/984/330/original/logo_%281%29.png?1606758905' alt='Logo Agrocontar' width='100' />
            <h1 style='color: #15b168;'>" . $v_welcome . " " . ucfirst(strtolower($row["nome"])) . ",</h1>
            <h2 style='color: #15b168;'>Etapa de confirmação de segurança:</h2>
            <p>
              Esta é a ultima etapa para que o seu acesso seja ativado.Clique no botão
              abaixo e cadastre a sua senha de acesso:
            </p>
            <p>
              Login: {$row["email"]}
            </p>
            <p>
              <a
                id='btn_ativar'
                href='https://app.agrocontar.com.br/validaccess.php?chave=" . $row["chave"] . "'
                target='_blank'
                >Ativar agora</a
              >
            </p>
            <p></p>
            <p><strong></strong></p>
            <p><strong>Att,</strong></p>
            <p><strong>Suporte Inovação | Agrocontar</strong></p>
            <p></p>
          </center>";
    
          $email = $row["email"];
          $assunto = "Portal Agrocontar: Redefinição de senha";
          $EnviaEmail = new Email_api();
          $EnviaEmail->send_email($msg, $email, $assunto);
        }
      }
      // var_dump($v_emails);die;
}
