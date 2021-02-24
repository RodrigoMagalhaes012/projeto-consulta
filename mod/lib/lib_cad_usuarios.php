<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);



// GERANDO LISTA DE EMPRESAS
if ($v_acao == "LISTAR_USUARIOS") {

    if (strpos($_SESSION["vs_array_access"], "T0011") > 0) {

        $v_pos = strpos($_SESSION["vs_array_access"], "T0011");
        $v_cad_usu_perm_ler = substr($_SESSION["vs_array_access"], $v_pos + 8, 1);
        $v_cad_usu_perm_criar = substr($_SESSION["vs_array_access"], $v_pos + 12, 1);
        $v_cad_usu_perm_gravar = substr($_SESSION["vs_array_access"], $v_pos + 16, 1);
        $v_cad_usu_perm_excluir = substr($_SESSION["vs_array_access"], $v_pos + 20, 1);
    }


    // GERANDO A LISTA
    $v_sql = " SELECT 
                    tu.Id, tu.Nome, tu.Email, tu.St_Cadastro AS St_Cadastro_id, 
                    case WHEN tu.st_cadastro=0 THEN 'BLOQUEADO' ELSE 'ATIVO' END AS St_Cadastro,
                    case 
                        when te.cnpj is null then 'Não possui' 
                        when te.cnpj = 0 then 'Não possui'          
                        else  te.nome  
                    end as nome_emp, te.cnpj      
                FROM db_adm.t_user as tu
                left join db_adm.t_empresas as te 
                on te.cnpj = tu.cnpj_emp 
                order by tu.nome";

    // SELECT Id, Nome, Email, St_Cadastro AS St_Cadastro_id, case WHEN st_cadastro=0 THEN 'BLOQUEADO' ELSE 'ATIVO' END AS St_Cadastro FROM db_adm.t_user ";
    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    $v_dados[] = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Id" => $row["id"],
            "Nome" => $row["nome"],
            "Email" => $row["email"],
            "cnpj" => $row["cnpj"],
            "nome_emp" => $row["nome_emp"],
            "St_Cadastro" => $row["st_cadastro"]
        );
        /*        $v_dados[] = array("Id" => $row["id"], "Nome" => $row["nome"], "Celular" => $row["celular"], "Email" => $row["email"], "St_Cadastro" => $row["st_cadastro"]);*/
    }


    // ENVIANDO DADOS
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// SELECIONANDO REGISTRO
if ($v_acao == "EV_SELECT") {

    $v_id = addslashes($_POST["v_id"]);

    //$v_sql = "SELECT Id,Nome,Cpf,E_Gestor,Email,St_Cadastro,Celular,Sexo,Id_GH,Id_Dpto,Id_Cargo, to_char(dt_nasc, 'YYYY-MM-DD') as dt_nasc FROM db_adm.t_user WHERE Id = " . $v_id;
    $v_sql = "SELECT tu.Id, tu.Nome, tu.Cpf, tu.St_Cadastro, tu.Email, tu.cnpj_emp, te.nome nome_emp
                FROM db_adm.t_user as tu
                LEFT JOIN db_adm.t_empresas as te 
                on te.cnpj = tu.cnpj_emp 
                WHERE tu.Id = {$v_id}";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array(
            "Id" => $row["id"],
            "Nome" => $row["nome"],
            "Cpf" => $row["cpf"],
            "St_Cadastro" => $row["st_cadastro"],
            "Email" => $row["email"],
            "nome_emp" => $row["nome_emp"],
            "Cnpj_emp" => $row["cnpj_emp"]

        );
    }
    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// CADASTRANDO NOVO REGISTRO
if ($v_acao == "EV_NOVO") {

    $v_nome = strtoupper(addslashes($_POST["v_nome"]));
    $v_email = strtolower(addslashes($_POST["v_email"]));
    $v_cnpj_emp = preg_replace('/[^0-9]/', '', addslashes($_POST["v_cnpj_emp"]));
    $v_cpf = preg_replace('/[^0-9]/', '', addslashes($_POST["v_cpf"]));



    $v_chave = randString(60);


    $v_sql = "INSERT INTO db_adm.t_user
                (nome, cpf, email, st_cadastro, chave, cnpj_emp)" .
        "VALUES('" . $v_nome . "','" . $v_cpf . "','" . $v_email . "',1,'" . $v_chave . "','" . $v_cnpj_emp . "')";


    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Cadastro realizado com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }



    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}


if ($v_acao == 'EV_LISTA_EMPRESAS') {

    $v_sql = "SELECT id, cnpj, nome
                FROM db_adm.t_empresas 
                    ORDER BY nome";

    $v_result_access_empresas = pg_query($conn, $v_sql);

    $v_dados = array();
    while ($row = pg_fetch_assoc($v_result_access_empresas)) {
        $v_dados[] = array(
            "nome" => $row["nome"],
            "cnpj" => $row["cnpj"],
            "id" => $row["id"]
        );
    }

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}


// SALVANDO REGISTRO
if ($v_acao == "EV_SALVAR") {

    $v_id = addslashes($_POST["v_id"]);
    // $v_cpf = addslashes($_POST["v_cpf"]);
    $v_nome = strtoupper(addslashes($_POST["v_nome"]));
    $v_cpf = preg_replace('/[^0-9]/', '', addslashes($_POST["v_cpf"]));
    $v_cnpj_emp = preg_replace('/[^0-9]/', '', addslashes($_POST["v_cnpj_emp"]));
    $v_email = strtolower(addslashes($_POST["v_email"]));
    // $v_celular = addslashes($_POST["v_celular"]);
    // $v_sexo = addslashes($_POST["v_sexo"]);
    // $v_dt_nasc = addslashes(implode('-', array_reverse(explode('/', $_POST["v_dt_nasc"]))));
    // $v_e_gestor = addslashes($_POST["v_e_gestor"]);

    // $v_id_gh = addslashes($_POST["v_id_gh"]);
    // $v_id_dpto = addslashes($_POST["v_id_dpto"]);
    // $v_Id_Cargo = addslashes($_POST["v_Id_Cargo"]);

    $v_sql = "UPDATE db_adm.t_user SET \n" .
        "cpf = " . $v_cpf . ", \n" .
        "nome = '" . $v_nome . "', \n" .
        // "Celular = '" . $v_celular . "', \n" .
        // "Sexo = '" . $v_sexo . "', \n" .
        // "Dt_Nasc = '" . $v_dt_nasc . "', \n" .
        // "E_Gestor = " . $v_e_gestor . ", \n" .
        // "Id_GH = " . $v_id_gh . ", \n" .
        // "Id_Dpto = " . $v_id_dpto . ", \n" .
        // "Id_Cargo = " . $v_Id_Cargo . ", \n" .
        "email = '" . $v_email . "', \n" .
        "cnpj_emp = " . $v_cnpj_emp . " \n" .
        "WHERE id = " . $v_id;

    // var_dump($v_sql);
    // die;


    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro salvo com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}



// EXCLUINDO REGISTRO
if ($v_acao == "EV_EXCLUIR") {

    $v_id = addslashes($_POST["v_id"]);

    $v_sql = "DELETE FROM db_adm.t_user WHERE Id = " . $v_id;

    if (pg_query($conn, $v_sql)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Registro excluído com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}



// BLOQUEANDO CONTA DO USUÁRIO
if ($v_acao == "EV_BLOQ") {

    $v_id = addslashes($_POST["v_id"]);
    $v_chave = randString(60);

    $v_sql = "UPDATE db_adm.t_user 
                 SET  Chave = '{$v_chave}', 
                      St_Cadastro = 0, 
                      St_bloqueio = 1         
              WHERE Id =  {$v_id}";

    // var_dump($v_sql);

    $result = pg_query($conn, $v_sql);
    $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Conta bloqueada com sucesso."}';

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}



// EXCLUINDO REGISTRO
if ($v_acao == "EV_EMAIL") {

    $v_id = addslashes($_POST["v_id"]);
    $v_chave = randString(60);

    // DESABILITANDO ACESSO ATÉ QUE A NOVA SENHA SEJA CADASTRADA PELO USUÁRIO
    $v_sql = "UPDATE db_adm.t_user SET 
        Chave = '{$v_chave}', 
        St_Cadastro = 1,
        St_bloqueio = 0 
        WHERE Id = {$v_id}";
    //var_dump($v_sql);


    $resultA = pg_query($conn, $v_sql);

    enviar_email($v_id, $conn);
    pg_close($conn);
    $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Confirmação de cadastro enviada com sucesso."}';
    $v_json = json_encode($json_msg);
    echo $v_json;
}



// function enviar_email($id)
// {

//     include_once("../../class/php/class_conect_db.php");

//     //    $v_sql = "SELECT Id, Substring_index(Nome,' ',1) as Nome, Email, Chave FROM t_user WHERE Id = " . $id;
//     $v_sql = "SELECT Id, Substring(Nome,1,position(' ' in Nome)) as Nome, Email, Chave FROM db_adm.t_user WHERE Id = {$id}";
//     //$result = pg_query($v_sql);    
//     $result = pg_query($conn, $v_sql);

//     if ($row = pg_fetch_assoc($result)) {

//         $v_nome = $row["Nome"];
//         $v_email = $row["Email"];
//         $v_chave = $row["Chave"];


//         $v_hora = date("H");
//         if ($v_hora >= 12 && $v_hora < 18) {
//             $v_welcome = "Boa tarde";
//         } else if ($v_hora >= 0 && $v_hora < 12) {
//             $v_welcome = "Bom dia";
//         } else {
//             $v_welcome = "Boa noite";
//         }

//         require "../../class/gmail/ClassEmail.php";
//         $msg = "    <style>
//         #btn_ativar {
//           background-color: #4caf50;
//           border: none;
//           color: white;
//           padding: 15px 32px;
//           text-align: center;
//           text-decoration: none;
//           display: inline-block;
//           font-size: 16px;
//           margin: 4px 2px;
//           cursor: pointer;
//           border-radius: 12px;
//         }
//       </style>
//       <center>
//         <img src='https://uploaddeimagens.com.br/imagens/O7ByZN8' alt='Logo Agrocontar' />
//         <h1 style='color: #15b168;'>" . $v_welcome . " " . strtolower($v_nome) . ",</h1>        
//         <h2 style='color: #15b168;'>Etapa de confirmação de segurança:</h2>
//         <p>
//           Esta é a ultima etapa para que o seu acesso seja ativado.Clique no botão
//           abaixo e cadastre a sua senha de acesso:
//         </p>
//         <p>
//           <a
//             id='btn_ativar'
//             href='https://app.agrocontar.com.br/validaccess.php?chave=" . $row["Chave"] . "'
//             target='_blank'
//             >Ativar agora</a
//           >
//         </p>
//         <p></p>
//         <p><strong></strong></p>
//         <p><strong>Att,</strong></p>
//         <p><strong>Suporte Inovação | Agrocontar</strong></p>
//         <p></p>
//       </center>";


//         $email = $row["Email"];
//         $assunto = "Sistema Agrocontar2.0: Confirmação de usuário";
//         $EnviaEmail = new Email_api();
//         $EnviaEmail->send_email($msg, $email, $assunto);
//     }

//     pg_close($conn);
// }



function enviar_email($id, $conn)
{

    // include_once("../../class/php/class_conect_db.php");



    //    $v_sql = "SELECT Id, Substring_index(Nome,' ',1) as Nome, Email, Chave FROM t_user WHERE Id = " . $id;
    $v_sql = "SELECT Id, Substring(Nome,1,position(' ' in Nome)) as Nome, Email, Chave FROM db_adm.t_user WHERE Id = {$id}";
    //$result = pg_query($v_sql);    
    $result = pg_query($conn, $v_sql);
    // var_dump($result);
    // die;
    // $v_dados[] = array();
    // while ($row = pg_fetch_assoc($result)) {
    //     $v_dados[] = array("nome" => $row["nome"]);
    // }








    // var_dump($v_nome);
    // die;
    // $v_email = $row["Email"];
    // $v_chave = $row["Chave"];





    $v_sql = "SELECT Id, Nome, Email, St_Cadastro AS St_Cadastro_id, case WHEN st_cadastro=0 THEN 'BLOQUEADO' ELSE 'ATIVO' END AS St_Cadastro FROM db_adm.t_user ";
    $result = pg_query($conn, $v_sql);

    $v_dados = array();

    $v_dados[] = array();
    while ($row = pg_fetch_assoc($result)) {
        $v_dados[] = array("Id" => $row["id"], "Nome" => $row["nome"], "Email" => $row["email"], "St_Cadastro" => $row["st_cadastro"]);
    }


    if ($row = pg_fetch_assoc($result)) {

        // $v_nome = $row["Nome"];
        // $v_email = $row["Email"];
        // $v_chave = $row["Chave"];


        $v_hora = date("H");
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
        <img src='https://uploaddeimagens.com.br/imagens/O7ByZN8' alt='Logo Agrocontar' />
        <h1 style='color: #15b168;'>" . $v_welcome . " " . strtolower($v_nome) . ",</h1>        
        <h2 style='color: #15b168;'>Etapa de confirmação de segurança:</h2>
        <p>
          Esta é a ultima etapa para que o seu acesso seja ativado.Clique no botão
          abaixo e cadastre a sua senha de acesso:
        </p>
        <p>
          <a
            id='btn_ativar'
            href='https://app.agrocontar.com.br/validaccess.php?chave=" . $row["Chave"] . "'
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


        $email = $row["Email"];
        $assunto = "Sistema Agrocontar2.0: Confirmação de usuário";
        $EnviaEmail = new Email_api();
        $EnviaEmail->send_email($msg, $email, $assunto);
    }
}
// pg_close($conn);




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
