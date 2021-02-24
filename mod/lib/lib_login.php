<?php
set_time_limit(5);

header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");
require "../../class/php/class_criptografia.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO A AÇÃO A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// AUTENTICANDO USÁRIO
if ($v_acao == "LOGAR") {

    $vs_captcha = strtolower($_SESSION["vs_captcha"]);
    $v_captcha = strtolower(addslashes($_POST["v_captcha"]));

    if ($vs_captcha == $v_captcha) {

        // RECEBENDO VARIAVEIS DO FORMULÁRIO
        $v_usuario = strtolower(addslashes($_POST["v_user"]));
        $v_senha = addslashes($_POST["v_senha"]);
        $v_tipo_usuario = addslashes($_POST["v_tipo_usuario"]);

        if ($v_tipo_usuario == 'cpf') {
            $v_usuario = str_replace('.', "", $v_usuario);
            $v_usuario = str_replace('-', "", $v_usuario);
            $v_usuario = intval($v_usuario);

            $usuario = "t_user.cpf = {$v_usuario}";
        } else {
            $usuario = "t_user.email = '{$v_usuario}'";
        }

        $v_sql = "SELECT t_user.id, t_user.nome, t_user.senha, t_user.dt_lgpd_ciencia_cookies FROM db_adm.t_user
        join db_adm.t_empresas_access 
        on db_adm.t_user.id = db_adm.t_empresas_access.Id_user 
        WHERE t_user.St_Cadastro = 1 AND {$usuario} 
        group by t_user.id, t_user.nome, t_user.senha";

        // $v_sql = "SELECT Id, Nome FROM db_adm.t_user WHERE St_Cadastro = 1 AND Email = '" . $v_usuario . "' AND Senha = '" . $v_senha . "'";
        $result = pg_query($conn, $v_sql);
        if ($row = pg_fetch_assoc(pg_query($conn, $v_sql))) {

            if (password_verify($v_senha, $row["senha"])) {
                $_SESSION["vs_id"] = $row["id"];
                $_SESSION["vs_nome"] = $row["nome"];
                $_SESSION["vs_dt_lgpd_ciencia_cookies"] = $row["dt_lgpd_ciencia_cookies"];

                // ###################################################### //
                // CARREGANDO A LISTA DE EMPRESAS LIBERADAS
                // ###################################################### //
                $_SESSION["vs_array_access"] = " ";
                $v_sql = "select LPAD(CAST(t_empresas.id AS VARCHAR),4,'0') as id_emp, LPAD(CAST(t_empresas.db_emp AS VARCHAR),4,'0') as db_emp from db_adm.t_access 
				join db_adm.t_access_emp_01_grupo_emp on db_adm.t_access.id_grupo_emp = db_adm.t_access_emp_01_grupo_emp.id_grupo_emp
				join db_adm.t_empresas on db_adm.t_access_emp_01_grupo_emp.id_emp = db_adm.t_empresas.id 
                where t_access.id_user = " . $_SESSION["vs_id"] . " 
                group by t_empresas.id, t_empresas.db_emp 
                order by t_empresas.id";

                $v_result_access_empresas = pg_query($conn, $v_sql);
                while ($v_row_access_empresas = pg_fetch_assoc($v_result_access_empresas)) {
                    $_SESSION["vs_array_access"] .= "ID_EMP_" . $v_row_access_empresas["id_emp"] . "|DB_EMP_" . $v_row_access_empresas["db_emp"] . " ";
                    $_SESSION["vs_db_empresa"] = $v_row_access_empresas["db_emp"];
                    $_SESSION["vs_id_empresa"] = $v_row_access_empresas["id_emp"];
                }

                // var_dump($_SESSION["vs_array_access"]);

                // // ###################################################### //
                // // CARREGANDO A LISTA DE TELAS LIBERADAS
                // // ###################################################### //
                $v_sql = "select LPAD(CAST(t_access_telas_01_grupo_telas.id_tela AS VARCHAR),4,'0') as id_tela, LPAD(CAST(t_access_telas_03_cad_telas.id_modulo AS VARCHAR),4,'0') as id_modulo, t_access_telas_01_grupo_telas.perm_ler, t_access_telas_01_grupo_telas.perm_criar, t_access_telas_01_grupo_telas.perm_gravar, t_access_telas_01_grupo_telas.perm_excluir 
                from db_adm.t_access 
                join db_adm.t_access_telas_01_grupo_telas on db_adm.t_access.id_grupo_telas = db_adm.t_access_telas_01_grupo_telas.id_grupo 
                join db_adm.t_access_telas_03_cad_telas on db_adm.t_access_telas_01_grupo_telas.id_tela = db_adm.t_access_telas_03_cad_telas.id 
                where t_access.id_user = " . $_SESSION["vs_id"] . " 
                group by t_access_telas_01_grupo_telas.id_tela, t_access_telas_03_cad_telas.id_modulo, t_access_telas_01_grupo_telas.perm_ler, t_access_telas_01_grupo_telas.perm_criar, t_access_telas_01_grupo_telas.perm_gravar, t_access_telas_01_grupo_telas.perm_excluir 
                order by t_access_telas_01_grupo_telas.id_tela";

                $v_result_access_telas = pg_query($conn, $v_sql);
                while ($v_row_access_telas = pg_fetch_assoc($v_result_access_telas)) {
                    $_SESSION["vs_array_access"] .= "M" . $v_row_access_telas["id_modulo"] . "|T" . $v_row_access_telas["id_tela"] . "|L/" . $v_row_access_telas["perm_ler"] . "|C/" . $v_row_access_telas["perm_criar"] . "|G/" . $v_row_access_telas["perm_gravar"] . "|E/" . $v_row_access_telas["perm_excluir"] . " ";
                }



                // OBTENDO A PRIMEIRA EMPRESA CUJO O USUARIO POSSUI ACESSO
                $v_sql = "select LPAD(CAST(t_empresas.id AS VARCHAR),4,'0') as id_emp, LPAD(CAST(t_empresas.db_emp AS VARCHAR),4,'0') as db_emp from 
                db_adm.t_empresas join db_adm.t_user 
                on t_empresas.cnpj = t_user.cnpj_emp 
                where t_user.Id = " . $_SESSION["vs_id"] . " and t_user.st_cadastro = 1 order by t_user.dt_alter_senha desc limit 1";

                $result3 = pg_query($conn, $v_sql);
                if ($row = pg_fetch_assoc($result3)) {

                    $_SESSION["vs_db_empresa"] = $row["db_emp"];
                    $_SESSION["vs_id_empresa"] = $row["id_emp"];
                }

                $timeZone = new DateTimeZone('America/Sao_Paulo');
                $v_data = new DateTime('now', $timeZone);
                $v_data = $v_data->format('Y-m-d H:i:s');
                $v_sql = "update db_adm.t_user set
                        dt_ultimo_login = '{$v_data}'
                        where id = {$_SESSION["vs_id"]}";
                pg_query($conn, $v_sql);

                //Instanciando classe
                $mc = new MyCripty();
                //Texto a ser criptogrfado
                $enc = $mc->enc(str_pad($_SESSION["vs_id"], 6, "0", STR_PAD_LEFT) . $_SESSION["vs_db_empresa"]);
                $_SESSION["vs_chave_chat"] = $mc->enc(str_pad($_SESSION["vs_id"], 6, "0", STR_PAD_LEFT) . $_SESSION["vs_db_empresa"]);
                $json_msg = '{"msg_ev":"success", "msg":"modulos.php"}';
                
            } else {
                $json_msg = '{"msg_ev":"error", "msg":"Usuário ou Senha Inválidos."}';
            }
        } else {
            $json_msg = '{"msg_ev":"error", "msg":"Verifique seus dados ou se possui acesso a alguma empresa.  Caso não consiga acessar, entre em contato com o suporte."}';
        }
    } else {
        $json_msg = '{"msg_ev":"error", "msg":"O código captcha informado não é válido."}';
    }

    pg_close($conn);
    echo json_encode($json_msg);
}





if ($v_acao == 'ESQUECI_SENHA') {

    $v_usuario = addslashes($_POST["v_user"]);

    if ($v_usuario != '') {

        // RECEBENDO VARIAVEIS DO FORMULÁRIO
        $v_sql = "SELECT id FROM db_adm.t_user WHERE Email = '" . $v_usuario . "'";

        // $v_sql = "SELECT Id, Nome FROM db_adm.t_user WHERE St_Cadastro = 1 AND Email = '" . $v_usuario . "' AND Senha = '" . $v_senha . "'";
        $result = pg_query($conn, $v_sql);
        if ($result) {
            $row = pg_fetch_assoc($result);
            enviar_email($row["id"], $conn);
            $json_msg = '{"msg_ev":"success", "msg":"Verifique o email para redefinir a senha."}';
        } else {
            $json_msg = '{"msg_ev":"error", "msg":"Email não existe."}';
        }
    } else {
        $json_msg = '{"msg_ev":"error", "msg":"Informe o email."}';
    }

    pg_close($conn);
    echo json_encode($json_msg);
}


function enviar_email($id, $conn)
{

    include_once("../../class/php/class_conect_db.php");

    $v_sql = "SELECT Id, split_part(Nome,' ',1) as Nome, Email, Cpf, Chave FROM db_adm.t_user WHERE id = " . $id;
    // var_dump($v_sql);
    $result = pg_query($conn, $v_sql);

    if ($row = pg_fetch_assoc($result)) {

        //Criando mácara para CPF
        $v_cpf_original = str_pad($row["cpf"], 11, '0', STR_PAD_LEFT);
        $v_cpf = substr($v_cpf_original, 0, 3);
        $v_cpf .= "." . substr($v_cpf_original, 3, 3);
        $v_cpf .= "." . substr($v_cpf_original, 6, 3);
        $v_cpf .= "-" . substr($v_cpf_original, 9, 2);

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

        require "../../class/gmail/ClassEmail.php";
        $msg = "    <style>
        #btn_ativar {
          background-color: #523B8F;
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
        <img src='https://uploaddeimagens.com.br/images/003/089/727/thumb/logo.png?1613672899' alt='Logo Agrocontar' />
        <h1 style='color: #523B8F;'>" . $v_welcome . " " . ucfirst(strtolower($row["nome"])) . ",</h1>
        <h2 style='color: #523B8F;'>Etapa de confirmação de segurança:</h2>
        <p>
          Esta é a ultima etapa para que o seu acesso seja ativado.Clique no botão
          abaixo e cadastre a sua senha de acesso:
        </p>
        <p>
          Login: {$row["email"]} OU
          CPF: {$v_cpf}
        </p>
        <p>
          <a
            id='btn_ativar'
            href='https://unifica.agrocontar.com.br/validaccess.php?chave=" . $row["chave"] . "'
            target='_blank'
            >Ativar agora</a
          >
        </p>
        <p></p>
        <p><strong></strong></p>
        <p><strong>Atenciosamente,</strong></p>
        <p><strong>Equipe Suporte | Plataforma Unifica</strong></p>
        <p></p>
      </center>";


        $email = $row["email"];
        $assunto = "Plafatorma Unifica: Redefinição de senha";
        $EnviaEmail = new Email_api();
        $EnviaEmail->send_email($msg, $email, $assunto);
    }

    // pg_close($conn);
}

if ($v_acao == 'SAIR') {

    session_destroy();

    $json_msg = '{"msg_ev":"success"}';

    echo json_encode($json_msg);
}
