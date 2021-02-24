<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once('../../vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$uploaddir = '../../tmp/';

$v_acao = addslashes($_POST["v_acao"]);

if($v_acao == 'UPLOAD_DADOS'){
    try {
        $uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);
        move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile);
        // $individual = new \stdClass();
    
        
    
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
    
        $spreadsheet = $reader->load($uploadfile);
    
        $worksheet = $spreadsheet->getActiveSheet();
        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
    
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                if($col == 4){
                    $email = $value;
                }
                if($col == 3){
                    // $nome = $value;
                    $nome = explode("-", $value);
                    $nome = trim($nome[0]);
                }
            }
            $v_sql = "INSERT INTO db_adm.t_rel_dados_gclick
                (email, nome)
                VALUES('{$email}', '{$nome}')
                ON CONFLICT (email) DO UPDATE SET
                nome = '{$nome}'";
            pg_query($conn, $v_sql);
        }
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Atualização de dados realizada com sucesso!"}';
        unlink($uploadfile);
        
    } catch (\Throwable $th) {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Falha no upload, por favor, tente novamente mais tarde!"}';
    }
    
    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

if($v_acao == 'UPLOAD_TAREFAS'){

    $v_sql = "WITH RECURSIVE hierarquia AS (
        SELECT
            gh.id_usuario ,
            gh.id_lider ,
            gh.nome,
            gh.id
        FROM
            db_adm.t_rh_funcao_gh gh
        where gh.data_finalizacao is null and gh.data_troca_lider is null and gh.id_usuario is not null
        UNION
            SELECT
                gh1.id_usuario ,
                gh1.id_lider ,
                gh1.nome,
                gh1.id
            FROM
                db_adm.t_rh_funcao_gh gh1
            INNER JOIN hierarquia h ON h.id = gh1.id_lider
    ) SELECT
        *
    FROM
        hierarquia";

    $result = pg_query($conn, $v_sql);
    $id_lideres = [];
    while($row = pg_fetch_assoc($result)){
        array_push($id_lideres, $row["id_usuario"]);
    }

    $v_competencia = addslashes($_POST["v_competencia"]);

    $individual = new \stdClass();
    $equipe = new \stdClass();

    $uploadfile = $uploaddir . basename($_FILES['arquivo_tarefas']['name']);
    move_uploaded_file($_FILES['arquivo_tarefas']['tmp_name'], $uploadfile);

    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
    $reader->setReadDataOnly(TRUE);
    
    try {
        $spreadsheet = $reader->load($uploadfile);

        $worksheet = $spreadsheet->getActiveSheet();
        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();

                //obtem o status da tarefa
                if($col == 2 && $row > 1){
                    $status = trim($value);
                }

                if($col == 3 && $row > 1){
                    $time = $value;
                }

                //obtem o colaborador resposavel pela tarefa
                if($col == 8 && $row > 1){
                    $colab = explode(') ',$value);
                    //percorre os colaboradores caso a tarefa tenha mais de um
                    $flag_lider = true;
                    foreach ($colab as $c) {
                        //faz a tratativa no nome dos colaboradores da tarefa
                        $colab = explode('-', $c);
                        $colab = explode('(', $colab[0]);
                        $index = trim($colab[0]);
                        //verifica se aquele colaborador ja esta incluso na contagem
                        if(property_exists($individual, $index)){
                            $individual->$index->total = $individual->$index->total + 1;
                            if($status == 'Concluído' || $status == 'Fora Meta'){
                                $individual->$index->concluidas = $individual->$index->concluidas + 1;
                            }else if($status == 'Atrasado após Venc' || $status == 'Fora Prazo' || $status == 'Último dia'){
                                $individual->$index->atrasadas = $individual->$index->atrasadas + 1;
                            }else if($status == 'Dispensado'){
                                $individual->$index->dispensadas = $individual->$index->dispensadas + 1;
                                $individual->$index->total = $individual->$index->total - 1;
                            }
                        }else{
                            $individual->$index = new \stdClass();
                            $individual->$index->total = 1;
                            $individual->$index->concluidas = 0;
                            $individual->$index->atrasadas = 0;
                            $individual->$index->dispensadas = 0;
                            $individual->$index->time = $time;
                            $individual->$index->lider = '';
                            if($status == 'Concluído' || $status == 'Fora Meta'){
                                $individual->$index->concluidas = $individual->$index->concluidas + 1;
                            }else if($status == 'Atrasado após Venc' || $status == 'Fora Prazo' || $status == 'Último dia'){
                                $individual->$index->atrasadas = $individual->$index->atrasadas + 1;
                            }else if($status == 'Dispensado'){
                                $individual->$index->dispensadas = $individual->$index->dispensadas + 1;
                                $individual->$index->total = $individual->$index->total - 1;
                            }
                        }

                        //verifica os lideres para lançar as tarefas da equipe para aquele lider
                        $v_sql = "select us.id, gc.email, gc.nome from db_adm.t_rel_dados_gclick gc
                        join db_adm.t_user us on us.email = gc.email 
                        where gc.nome = '{$index}'";
                        $result = pg_query($conn, $v_sql);
                        $id = pg_fetch_result($result, 'id');
                        if($id){
                            
                            $v_sql = "select us.id, us.email from db_adm.t_hist_gh hgh
                            join db_adm.t_rh_funcao_gh trfg on trfg.id = hgh.id_gh 
                            join db_adm.t_user us on us.id = trfg.id_usuario 
                            where hgh.id_usuario = {$id}";

                            $result = pg_query($conn, $v_sql);
                            $lider = pg_fetch_result($result, 'email');
                            if($lider && $flag_lider){
                                $flag_lider = false;

                                $lider = pg_fetch_object($result, 0);
                                $email = $lider->email;
                                $id_lider = $lider->id;

                                $individual->$index->lider = $email;
    
                                //verifica contagem para equipe
                                if(property_exists($equipe, $email)){
                                    $equipe->$email->total = $equipe->$email->total + 1;
                                    if($status == 'Concluído' || $status == 'Fora Meta'){
                                        $equipe->$email->concluidas = $equipe->$email->concluidas + 1;
                                    }else if($status == 'Atrasado após Venc' || $status == 'Fora Prazo' || $status == 'Último dia'){
                                        $equipe->$email->atrasadas = $equipe->$email->atrasadas + 1;
                                    }else if($status == 'Dispensado'){
                                        $equipe->$email->dispensadas = $equipe->$email->dispensadas + 1;
                                        $equipe->$email->total = $equipe->$email->total - 1;
                                    }
                                }else{
                                    $equipe->$email = new \stdClass();
                                    $equipe->$email->total = 1;
                                    $equipe->$email->concluidas = 0;
                                    $equipe->$email->atrasadas = 0;
                                    $equipe->$email->dispensadas = 0;
                                    $equipe->$email->id_lider = $id_lider;
                                    if($status == 'Concluído' || $status == 'Fora Meta'){
                                        $equipe->$email->concluidas = $equipe->$email->concluidas + 1;
                                    }else if($status == 'Atrasado após Venc' || $status == 'Fora Prazo' || $status == 'Último dia'){
                                        $equipe->$email->atrasadas = $equipe->$email->atrasadas + 1;
                                    }else if($status == 'Dispensado'){
                                        $equipe->$email->dispensadas = $equipe->$email->dispensadas + 1;
                                        $equipe->$email->total = $equipe->$email->total - 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //inserção de porcentagem referente ao colaborador não lider
        $insert_line = "INSERT INTO db_adm.t_indicador_usuario20211
        (id_usuario, id_indicador, competencia, porcentagem, tarefas_atrasadas, tarefas_concluidas)
        VALUES ";
        $lideres = array();
        foreach ($individual as $key => $val) {
            $v_sql = "select us.id, gc.email, gc.nome from db_adm.t_rel_dados_gclick gc
            join db_adm.t_user us on us.email = gc.email 
            where gc.nome = '{$key}'";
            $result = pg_query($conn, $v_sql);
            $id = pg_fetch_result($result, 'id');

            //verifica se não é uma lider, caso nao seja pode inserir as tarefas individuais no banco
            if($id && !in_array($id, $id_lideres)){

                $time = $val->time;
                $lider = $val->lider;

                if($lider != ''){
                    $perc_time = ($equipe->$lider->atrasadas / $equipe->$lider->total)*100;

                    if($perc_time <= 0.5){
                        $perc_total = 4;
                    }else if($perc_time >0.5 && $perc_time < 1){
                        $perc_total = 2;
                    }else{
                        $perc_total = 0;
                    }
                    $insert_line .= "({$id}, 4, '{$v_competencia}', {$perc_total}, NULL, NULL),";
                }

                $perc_conclusao = ($val->atrasadas / $val->total)*100;

                if($perc_conclusao <= 0.5){
                    $perc_total = 4;
                }else if($perc_conclusao >0.5 && $perc_conclusao < 1){
                    $perc_total = 2;
                }else{
                    $perc_total = 0;
                }
                $insert_line .= "({$id}, 1, '{$v_competencia}', {$perc_total}, {$val->atrasadas}, {$val->concluidas}),";

                //insere multas para colaboradores que não sõa do TI
                if($time != "TI"){
                    $insert_line .= "({$id}, 2, '{$v_competencia}', 3, NULL, NULL),";
                }
            }
            if($id && in_array($id, $id_lideres)){
                $aux_array = array($key => $id);
                $lideres = array_merge($lideres, $aux_array);
            }
        }
        
        if (pg_query($conn, substr($insert_line, 0, -1))) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Tarefas processadas com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }

        //inserçao de porcentagens referentes aos lideres
        $insert_line = "INSERT INTO db_adm.t_indicador_usuario20211
        (id_usuario, id_indicador, competencia, porcentagem, tarefas_atrasadas, tarefas_concluidas)
        VALUES ";
        //percorrendo lideres para contagem de tarefas da equipe
        foreach ($equipe as $key => $value) {
            
            $perc_time = ($value->atrasadas / $value->total)*100;

            if($perc_time <= 0.5){
                $perc_total = 8;
            }else if($perc_time >0.5 && $perc_time < 1){
                $perc_total = 4;
            }else{
                $perc_total = 0;
            }
            $insert_line .= "({$value->id_lider}, 13, '{$v_competencia}', {$perc_total}, {$value->atrasadas}, {$value->concluidas}),";
        }

        if (pg_query($conn, substr($insert_line, 0, -1))) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Tarefas processadas com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }

        // inserção de porcentagem referentes as equipes
        $insert_line = "INSERT INTO db_adm.t_indicador_tarefa20211
        (id_indicador, competencia, equipe, tarefas_atrasadas, tarefas_concluidas)
        VALUES ";
        
        foreach ($equipe as $key => $value) {
            $insert_line .= "(4, '{$v_competencia}', '{$key}', {$value->atrasadas}, {$value->concluidas}),";
        }

        if (pg_query($conn, substr($insert_line, 0, -1))) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Tarefas processadas com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }

        unlink($uploadfile);
    } catch (\Throwable $th) {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Falha no upload, por favor, tente novamente mais tarde!"}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

if($v_acao == 'UPLOAD_MKT'){
    $v_competencia = addslashes($_POST["v_competencia"]);
    try {
        $uploadfile = $uploaddir . basename($_FILES['arquivo_mkt']['name']);
        move_uploaded_file($_FILES['arquivo_mkt']['tmp_name'], $uploadfile);
        // $individual = new \stdClass();
    
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
    
        $spreadsheet = $reader->load($uploadfile);
    
        $worksheet = $spreadsheet->getActiveSheet();
        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

        $myobj = new \stdClass();
        $myobj->total = 0;
        $myobj->atrasadas = 0;
        $myobj->concluidas = 0;
        $myobj->dispensadas = 0;
        $colaboradores = [];
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                if($col == 2 && $row > 1){
                    $status = trim($value);
                    $myobj->total = $myobj->total + 1;
                    if($status == 'Concluído' || $status == 'Finalizado'){
                        if(property_exists($myobj, "concluidas")){
                            $myobj->concluidas = $myobj->concluidas + 1;
                        }
                    }else if($status == 'Atrasado após Venc' || $status == 'Fora Prazo' || $status == 'Último dia'){
                        if(property_exists($myobj, "atrasadas")){
                            $myobj->atrasadas = $myobj->atrasadas + 1;
                        }
                    }else if($status == 'Cancelado'){
                        if(property_exists($myobj, "dispensadas")){
                            $myobj->dispensadas = $myobj->dispensadas + 1;
                            $myobj->total = $myobj->total - 1;
                        }
                    }
                }
                if($col == 7 && $row > 1 && $value != ''){
                    array_push($colaboradores, $value);
                }
            }
        }

        $perc_time = ($myobj->concluidas / $myobj->total) * 100;

        if($perc_time >= 85){
            $perc_total = 8;
        }else if($perc_time >=70 && $perc_time < 85){
            $perc_total = 4;
        }else{
            $perc_total = 0;
        }

        $insert_individual = "INSERT INTO db_adm.t_indicador_usuario20211
        (id_usuario, id_indicador, competencia, porcentagem, tarefas_atrasadas, tarefas_concluidas)
        VALUES ";

        foreach ($colaboradores as $colab) {
            $v_sql = "select id from db_adm.t_user where email = '{$colab}'";

            $result = pg_query($conn, $v_sql);

            $id = pg_fetch_result($result, 'id');

            $insert_individual .= "({$id}, 9, '{$v_competencia}', {$perc_total}, {$myobj->atrasadas}, {$myobj->concluidas}),";

            $v_sql = "select tu.email, tu.id from db_adm.t_rh_funcao_gh trfg 
            join db_adm.t_user tu on tu.id = trfg.id_usuario 
            where tu.email = '{$colab}'";

            $lider = pg_fetch_object(pg_query($conn, $v_sql));
            if($lider){
                $email_lider = $lider->email;
            }
        }

        if (pg_query($conn, substr($insert_individual, 0, -1))) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Tarefas processadas com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }

        $insert_equipe = "INSERT INTO db_adm.t_indicador_tarefa20211
        (id_indicador, competencia, equipe, tarefas_atrasadas, tarefas_concluidas)
        VALUES(4, '{$v_competencia}', '{$email_lider}', {$myobj->atrasadas}, {$myobj->concluidas})";

        if (pg_query($conn, $insert_equipe)) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Tarefas processadas com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }

        unlink($uploadfile);
        
    } catch (\Throwable $th) {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Falha no upload, por favor, tente novamente mais tarde!"}';
    }
    
    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

if($v_acao == 'UPLOAD_JURIDICO'){
    $v_competencia = addslashes($_POST["v_competencia"]);
    try {
        $uploadfile = $uploaddir . basename($_FILES['arquivo_juridico']['name']);
        move_uploaded_file($_FILES['arquivo_juridico']['tmp_name'], $uploadfile);
        // $individual = new \stdClass();
    
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
    
        $spreadsheet = $reader->load($uploadfile);
    
        $worksheet = $spreadsheet->getActiveSheet();
        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

        $myobj = new \stdClass();
        $myobj->total = 0;
        $myobj->atrasadas = 0;
        $myobj->concluidas = 0;
        $myobj->dispensadas = 0;
        $colaboradores = [];
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                if($col == 2 && $row > 1 && $value != ""){
                    $status = trim($value);
                    $myobj->total = $myobj->total + 1;
                    if($status == 'Concluído' || $status == 'Finalizado'){
                        if(property_exists($myobj, "concluidas")){
                            $myobj->concluidas = $myobj->concluidas + 1;
                        }
                    }else if($status == 'Atrasado após Venc' || $status == 'Fora Prazo' || $status == 'Último dia'){
                        if(property_exists($myobj, "atrasadas")){
                            $myobj->atrasadas = $myobj->atrasadas + 1;
                        }
                    }else if($status == 'Cancelado'){
                        if(property_exists($myobj, "dispensadas")){
                            $myobj->dispensadas = $myobj->dispensadas + 1;
                            $myobj->total = $myobj->total - 1;
                        }
                    }
                }
                if($col == 8 && $row > 1 && $value != ''){
                    array_push($colaboradores, $value);
                }
            }
        }

        $perc_time = ($myobj->concluidas / $myobj->total) * 100;

        if($perc_time >= 85){
            $perc_total = 8;
        }else if($perc_time >=70 && $perc_time < 85){
            $perc_total = 4;
        }else{
            $perc_total = 0;
        }

        $insert_individual = "INSERT INTO db_adm.t_indicador_usuario20211
        (id_usuario, id_indicador, competencia, porcentagem, tarefas_atrasadas, tarefas_concluidas)
        VALUES ";
        $email_lider = '';
        foreach ($colaboradores as $colab) {
            $v_sql = "select id from db_adm.t_user where email = '{$colab}'";

            $result = pg_query($conn, $v_sql);

            $id = pg_fetch_result($result, 'id');

            $insert_individual .= "({$id}, 9, '{$v_competencia}', {$perc_total}, {$myobj->atrasadas}, {$myobj->concluidas}),";

            $v_sql = "select tu.email, tu.id from db_adm.t_rh_funcao_gh trfg 
            join db_adm.t_user tu on tu.id = trfg.id_usuario 
            where tu.email = '{$colab}'";

            $lider = pg_fetch_object(pg_query($conn, $v_sql));
            if($lider){
                $email_lider = $lider->email;
            }
        }

        if (pg_query($conn, substr($insert_individual, 0, -1))) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Tarefas processadas com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }
        if($email_lider != ''){
            $insert_equipe = "INSERT INTO db_adm.t_indicador_tarefa20211
            (id_indicador, competencia, equipe, tarefas_atrasadas, tarefas_concluidas)
            VALUES(4, '{$v_competencia}', '{$email_lider}', {$myobj->atrasadas}, {$myobj->concluidas})";
    
            if (pg_query($conn, $insert_equipe)) {
                $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Tarefas processadas com sucesso."}';
            } else {
                $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
            }
        }
        unlink($uploadfile);
        
    } catch (\Throwable $th) {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Falha no upload, por favor, tente novamente mais tarde!"}';
    }
    
    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

if($v_acao == 'UPLOAD_TI'){

    $v_competencia = addslashes($_POST["v_competencia"]);
    $myobj = new \stdClass();
    try {
        $uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);
        move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile);        
    
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
    
        $spreadsheet = $reader->load($uploadfile);
    
        $worksheet = $spreadsheet->getActiveSheet();
        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
    
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                //obtem o colaborador da tarefa
                if($col == 3 && $row > 1){
                    $colab = explode('-', $value);
                    $colab = trim($colab[1]);
                }

                //obtem as avaliações dos atendimentos de cada usuario 
                if($col == 4 && $row > 1){
                    //verifica se aquele colaborador ja esta incluso na contagem
                    if(property_exists($myobj, $colab)){
                        $myobj->$colab->atendimentos += 1;
                        $myobj->$colab->avaliacao += $value;

                    }else{
                        $myobj->$colab = new \stdClass();
                        $myobj->$colab->atendimentos = 1;
                        $myobj->$colab->avaliacao = $value;
                    }
                }
            }
        }

        foreach ($myobj as $key => $value) {
            $v_sql = "select id from db_adm.t_user where email = '{$key}'";
            $result = pg_query($conn, $v_sql);
            $id = pg_fetch_result($result, 'id');

            //verifica se não é uma lider, caso nao seja pode inserir as tarefas individuais no banco
            if($id){

                $perc = ($value->avaliacao / ($value->atendimentos * 5)) * 100;
                if($perc >= 85){
                    $perc_total = 3;
                } else if ($perc >= 70 && $perc < 85){
                    $perc_total = 1.5;
                }else{
                    $perc_total = 0;
                }

                $v_sql = "INSERT INTO db_adm.t_indicador_usuario20211
                (id_usuario, id_indicador, competencia, porcentagem, tarefas_atrasadas, tarefas_concluidas)
                VALUES({$id}, 10, '{$v_competencia}', {$perc_total}, NULL, NULL)";
                pg_query($conn, $v_sql);
            }
        }
        
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Dados importados com sucesso!"}';
        unlink($uploadfile);
        
    } catch (\Throwable $th) {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Falha no upload, por favor, tente novamente mais tarde!"}';
    }
    
    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}

if($v_acao == 'UPLOAD_VAGAS_RH'){
    $v_competencia = addslashes($_POST["v_competencia"]);
    $myobj = new \stdClass();
    $myobj->total = 0;
    $myobj->dentro_prazo = 0;
    $myobj->fora_prazo = 0;
    try {
        $uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);
        move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile);        
    
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
    
        $spreadsheet = $reader->load($uploadfile);
    
        $worksheet = $spreadsheet->getActiveSheet();
        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
        $colaboradores = [];
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                
                //obtem o colaborador da tarefa
                if($col == 9 && $row > 1){
                    if($value == 'Dentro do prazo'){
                        $myobj->total += 1;
                        $myobj->dentro_prazo += 1;
                    }else if ($value == 'Fora do prazo'){
                        $myobj->total += 1;
                        $myobj->fora_prazo += 1;
                    }
                }
                if($col == 10 && $row > 2 && $value != ''){
                    array_push($colaboradores, $value);
                }
            }
        }

        $perc = ($myobj->dentro_prazo / $myobj->total) * 100 ;

        if($perc >= 85){
            $perc_total = 8;
        }else if($perc >= 70 && $perc < 85){
            $perc_total = 4;
        }else{
            $perc_total = 0;
        }

        $insert_line = "INSERT INTO db_adm.t_indicador_usuario20211
        (id_usuario, id_indicador, competencia, porcentagem, tarefas_atrasadas, tarefas_concluidas)
        VALUES ";

        foreach ($colaboradores as $colab) {
            $v_sql = "select id from db_adm.t_user where email = '{$colab}'";

            $result = pg_query($conn, $v_sql);

            $id = pg_fetch_result($result, 'id');

            $insert_line .= "({$id}, 7, '{$v_competencia}', {$perc_total}, {$myobj->fora_prazo}, {$myobj->dentro_prazo}),";

            $v_sql = "select tu.email, tu.id from db_adm.t_rh_funcao_gh trfg 
            join db_adm.t_user tu on tu.id = trfg.id_usuario 
            where tu.email = '{$colab}'";

            $lider = pg_fetch_object(pg_query($conn, $v_sql));
            if($lider){
                $email_lider = $lider->email;
            }
        }

        if (pg_query($conn, substr($insert_line, 0, -1))) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Tarefas processadas com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }

        $insert_equipe = "INSERT INTO db_adm.t_indicador_tarefa20211
        (id_indicador, competencia, equipe, tarefas_atrasadas, tarefas_concluidas)
        VALUES(7, '{$v_competencia}', '{$email_lider}', {$myobj->fora_prazo}, {$myobj->dentro_prazo})";

        if (pg_query($conn, $insert_equipe)) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Tarefas processadas com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }

        unlink($uploadfile);
        
    } catch (\Throwable $th) {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Falha no upload, por favor, tente novamente mais tarde!"}';
    }
    
    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;
}

if($v_acao == 'UPLOAD_TRELLO'){

    $v_competencia = addslashes($_POST["v_competencia"]);

    try {
        $myobj = new \stdClass();
    
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
    
        $uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);
        move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile);     

        $spreadsheet = $reader->load($uploadfile);
    
        $worksheet = $spreadsheet->getActiveSheet();
        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
    
        $atrasadas_equipe = 0;
        $concluidas_equipe = 0;
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
    
                //obtem o status da tarefa
                if($col == 3 && $row > 1){
                    $status = trim($value);
                }
    
                //obtem os resonsaveis pela tarefa
                if($col == 6 && $row > 1){
                    $colaboradores = explode(',', $value);
                    $flag_tarefas_equipe = true;
                    foreach ($colaboradores as $colab) {
                        if($flag_tarefas_equipe && $colab != 'uaslei.ribeiro@agrocontar.com.br'){
                            $flag_tarefas_equipe = false;
                            if($status == 'Concluído' || $status == 'Concluido'){
                                $concluidas_equipe += 1;
                            }else if($status == 'Fora do Prazo' || $status == 'Fora Prazo'){
                                $atrasadas_equipe += 1;
                            }
                        }
                        if($colab != 'uaslei.ribeiro@agrocontar.com.br'){
                            if(property_exists($myobj, $colab)){
                                $myobj->$colab->total = $myobj->$colab->total + 1;
                                if($status == 'Concluído' || $status == 'Concluido'){
                                    $myobj->$colab->concluidas = $myobj->$colab->concluidas + 1;
                                }else if($status == 'Fora do Prazo' || $status == 'Fora Prazo'){
                                    $myobj->$colab->atrasadas = $myobj->$colab->atrasadas + 1;
                                }
                            }else{
                                $myobj->$colab = new \stdClass();
                                $myobj->$colab->total = 1;
                                $myobj->$colab->concluidas = 0;
                                $myobj->$colab->atrasadas = 0;
                                if($status == 'Concluído' || $status == 'Concluido'){
                                    $myobj->$colab->concluidas = $myobj->$colab->concluidas + 1;
                                }else if($status == 'Fora do Prazo' || $status == 'Fora Prazo'){
                                    $myobj->$colab->atrasadas = $myobj->$colab->atrasadas + 1;
                                }
                            }
                        }
                    }
                }
            }
        }

        $insert_line = "INSERT INTO db_adm.t_indicador_usuario20211
            (id_usuario, id_indicador, competencia, porcentagem, tarefas_atrasadas, tarefas_concluidas)
            VALUES ";

        foreach ($myobj as $key => $value) {

            $v_sql = "select tu.email, tu.id from db_adm.t_rh_funcao_gh trfg 
            join db_adm.t_user tu on tu.id = trfg.id_usuario 
            where tu.email = '{$key}'";

            $lider = pg_fetch_object(pg_query($conn, $v_sql));
            if($lider){
                $email_lider = $lider->email;
                $id_lider = $lider->id;
            }

            $v_sql = "select id from db_adm.t_user where email = '{$key}'";
            $result = pg_query($conn, $v_sql);
            $id = pg_fetch_result($result, 'id');

            $perc_conclusao = ($value->concluidas / $value->total)*100;
            if($perc_conclusao >= 85){
                $perc_total = 8;
            }else if($perc_conclusao >=70 && $perc_conclusao < 85){
                $perc_total = 4;
            }else{
                $perc_total = 0;
            }

            if($id && !$lider){
                $insert_line .= "({$id}, 9, '{$v_competencia}', {$perc_total}, {$value->atrasadas}, {$value->concluidas}),";
            }
        }

        if (pg_query($conn, substr($insert_line, 0, -1))) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Tarefas processadas com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }

        $perc_conclusao = ( $concluidas_equipe / ( $concluidas_equipe + $atrasadas_equipe))*100;
        if($perc_conclusao >= 85){
            $perc_lider = 8;
        }else if($perc_conclusao >=70 && $perc_conclusao < 85){
            $perc_lider = 4;
        }else{
            $perc_lider = 0;
        }

        $insert_lider = "INSERT INTO db_adm.t_indicador_usuario20211
        (id_usuario, id_indicador, competencia, porcentagem, tarefas_atrasadas, tarefas_concluidas)
        VALUES ({$id_lider}, 9, '{$v_competencia}', {$perc_lider}, {$atrasadas_equipe}, {$concluidas_equipe})";

        if (pg_query($conn, $insert_lider)) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Tarefas processadas com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }

        $insert_equipe = "INSERT INTO db_adm.t_indicador_tarefa20211
        (id_indicador, competencia, equipe, tarefas_atrasadas, tarefas_concluidas)
        VALUES(9, '{$v_competencia}', '{$email_lider}', {$atrasadas_equipe}, {$concluidas_equipe})";

        if (pg_query($conn, $insert_equipe)) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Tarefas processadas com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }

        unlink($uploadfile);
    } catch (\Throwable $th) {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Falha no upload, por favor, tente novamente mais tarde!"}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}

if($v_acao == 'CARREGA_COLAB'){

    $v_competencia = addslashes($_POST["v_competencia"]);

    $v_sql = "select tu.id, gc.nome, gc.email, tiu.id_indicador, cargo.nome cargo, gh.nome gh from db_adm.t_rel_dados_gclick gc
    join db_adm.t_user tu on tu.email = gc.email 
    join db_adm_rh.t_rh_colaborador colab on colab.email = tu.email 
    full join db_adm.t_indicador_usuario20211 tiu on tiu.id_usuario = tu.id
       full JOIN db_adm.t_rh_funcao_gh gh 
         ON gh.Id = ( select hist_gh.id_gh 
                       from db_adm.t_hist_gh hist_gh 
                       where hist_gh.id_usuario = colab.id_usuario 
                         and hist_gh.data = ( select MAX(hist_gh2.data) 
                                                from db_adm.t_hist_gh hist_gh2 
                                                where hist_gh2.id_usuario = hist_gh.id_usuario 
                                                  and hist_gh2.data <= current_date ) ) 
       JOIN db_adm.t_empresas as emp 
         ON emp.id = colab.id_empresa 
       JOIN db_adm_rh.t_rh_tabela_departamento as tabDep 
         ON tabDep.id = emp.id_tab_departamentos 
       left JOIN db_adm_rh.t_rh_departamentos dep 
         ON dep.id_tabela = tabDep.id 
        and dep.Id = (select hist_dep.id_departamento 
                        from db_adm_rh.t_rh_hist_departamento hist_dep 
                        where hist_dep.matricula = colab.matricula  
                          and hist_dep.id_empresa  = colab.id_empresa 
                          and hist_dep.data = (select MAX(hist_dep2.data) 
                                                 from db_adm_rh.t_rh_hist_departamento hist_dep2 
                                                 where hist_dep2.matricula = hist_dep.matricula 
                                                   and hist_dep2.id_empresa = hist_dep.id_empresa 
                                                   and hist_dep2.data <= current_date)) 
       JOIN db_adm_rh.t_rh_tabela_cargo as tabCar ON tabCar.id  = emp.id_tab_cargos                                               
       JOIN db_adm_rh.t_rh_cargos cargo 
         ON cargo.id_tabela  = tabCar.id   
         AND cargo.Id = (select hist_cargo.id_cargo 
                          from db_adm_rh.t_rh_hist_cargo hist_cargo 
                          where hist_cargo.matricula = colab.matricula  
                            and hist_cargo.id_empresa = colab.id_empresa
                            and hist_cargo.data = (select MAX(hist_cargo2.data) 
                                                     from db_adm_rh.t_rh_hist_cargo hist_cargo2 
                                                     where hist_cargo2.matricula = hist_cargo.matricula 
                                                       and hist_cargo2.id_empresa  = hist_cargo.id_empresa 
                                                       and hist_cargo2.data <= current_date))  WHERE (tiu.id_indicador = 1 and tiu.competencia = '{$v_competencia}') or (tiu.competencia is null)";

    $result = pg_query($conn, $v_sql);

    $v_dados = array();
    while($row = pg_fetch_assoc($result)){
        $v_dados[] = array(
            "id" => $row["id"],
            "nome" => $row["nome"],
            "email" => $row["email"],
            "indicador" => $row["id_indicador"],
            "gh" => $row["gh"],
            "cargo" => $row["cargo"]
        );
    }

    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;
}

if($v_acao == 'INCLUIR_PORCENTAGEM_GCLICK'){

    $v_colab = addslashes($_POST["v_colaborador"]);
    $v_competencia = addslashes($_POST["v_competencia"]);

    $v_sql = "select tu.email, tit.tarefas_atrasadas, tit.tarefas_concluidas from db_adm.t_hist_gh thg 
    join db_adm.t_rh_funcao_gh trfg on thg.id_gh = trfg.id
    join db_adm.t_user tu on tu.id = trfg.id_usuario
    join db_adm.t_indicador_tarefa20211 tit on tit.equipe = tu.email 
    where thg.id_usuario = {$v_colab} and tit.competencia = '{$v_competencia}'";

    $result = pg_query($conn, $v_sql);

    $v_dados_equipe = pg_fetch_object($result);

    $insert_line = "INSERT INTO db_adm.t_indicador_usuario20211
        (id_usuario, id_indicador, competencia, porcentagem, tarefas_atrasadas, tarefas_concluidas)
        VALUES ";

    if($v_dados_equipe){
        $perc_time = ($v_dados_equipe->tarefas_atrasadas / ($v_dados_equipe->tarefas_atrasadas + $v_dados_equipe->tarefas_concluidas))*100;

        if($perc_time <= 0.5){
            $perc_total = 4;
        }else if($perc_time >0.5 && $perc_time < 1){
            $perc_total = 2;
        }else{
            $perc_total = 0;
        }

        $insert_line .= "({$v_colab}, 4, '{$v_competencia}', {$perc_total}, NULL, NULL),";
    }

    $insert_line .= "({$v_colab}, 1, '{$v_competencia}', 4, 0, 0),";
    $insert_line .= "({$v_colab}, 2, '{$v_competencia}', 3, NULL, NULL)";

    if (pg_query($conn, $insert_line)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Porcentagem inclusa com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}

if($v_acao == 'CALCULO_LIDERES'){

    $v_competencia = addslashes($_POST["v_competencia"]);

    $v_sql = "select nivel from db_adm.t_rh_nivel_gh trng where trng.nivel <> 0 and nivel < (select max(nivel) from db_adm.t_rh_nivel_gh) order by -nivel";

    $result = pg_query($conn, $v_sql);

    while($row = pg_fetch_assoc($result)){

        $v_sql = "select gh.id, gh.nome, gh.id_usuario, us.email from db_adm.t_rh_funcao_gh gh
                join db_adm.t_user us on us.id = gh.id_usuario 
                where gh.id_nivel = {$row["nivel"]} and gh.data_finalizacao is null and gh.data_troca_lider is null";

        $result1 = pg_query($conn, $v_sql);

        while($row1 = pg_fetch_assoc($result1)){
            
            $v_sql2 = "select us.email from db_adm.t_rh_funcao_gh gh
                    join db_adm.t_user us on us.id = gh.id_usuario 
                    where gh.id_lider = {$row1["id"]} and gh.data_finalizacao is null and gh.data_troca_lider is null";

            $result2 = pg_query($conn, $v_sql2);

            $atrasadas = 0;
            $concluidas = 0;
            $total = 0;

            while($row2 = pg_fetch_assoc($result2)){

                $v_sql3 = "select * from db_adm.t_indicador_tarefa20211 where equipe = '{$row2["email"]}' and competencia = '{$v_competencia}'";

                $result3 = pg_fetch_object(pg_query($conn, $v_sql3));
                if($result3){
                    $atrasadas += $result3->tarefas_atrasadas;
                    $concluidas += $result3->tarefas_concluidas;
                    $total += $result3->tarefas_atrasadas + $result3->tarefas_concluidas;
                }
            }

            if($total != 0){

                $v_sql6 = "select * from db_adm.t_indicador_usuario20211 tiu where id_usuario = {$row1["id_usuario"]}";

                $result6 = pg_fetch_object(pg_query($conn, $v_sql6));
                if($result6){
                    $atrasadas += $result6->tarefas_atrasadas;
                    $concluidas += $result6->tarefas_concluidas;
                }

                $perc = ($atrasadas / $total) * 100;
    
                if($perc <= 0.5){
                    $perc_total = 8;
                }else if($perc >0.5 && $perc < 1){
                    $perc_total = 4;
                }else{
                    $perc_total = 0;
                }

                $v_sql = "DELETE FROM db_adm.t_indicador_usuario20211
                WHERE id_usuario={$row1["id_usuario"]} AND id_indicador=13 AND competencia='{$v_competencia}'";

                pg_query($conn, $v_sql);

                $v_sql4 = "INSERT INTO db_adm.t_indicador_usuario20211
                (id_usuario, id_indicador, competencia, porcentagem, tarefas_atrasadas, tarefas_concluidas)
                VALUES({$row1["id_usuario"]}, 13, '{$v_competencia}', {$perc_total}, $atrasadas, $concluidas)";
    
                if (pg_query($conn, $v_sql4)) {
                    $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Lideres analilsados com sucesso!"}';
                } else {
                    $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
                }
    
                // $v_sql5 = "INSERT INTO db_adm.t_indicador_tarefa20211
                // (id_indicador, competencia, equipe, tarefas_atrasadas, tarefas_concluidas)
                // VALUES(4, '{$v_competencia}', '{$row1["email"]}', $atrasadas, $concluidas)";
    
                // if (pg_query($conn, $v_sql5)) {
                //     $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Lideres analisados com sucesso!"}';
                // } else {
                //     $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
                // }
            }
        }

    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}

if($v_acao == 'UPLOAD_INOVACAO_SATISFACAO'){

    $v_competencia = addslashes($_POST["v_competencia"]);
    $myobj = new \stdClass();
    try {
        $uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);
        move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile);        
    
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
    
        $spreadsheet = $reader->load($uploadfile);
    
        $worksheet = $spreadsheet->getActiveSheet();
        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
        
        $myobj->total = 0;
        $myobj->pontuacao = 0;
        $colaboradores = array();
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                //obtem o colaborador da tarefa
                if($col == 2 && $row > 1 && $value != ""){
                    array_push($colaboradores, $value);
                }

                //obtem as avaliações dos atendimentos de cada usuario 
                if($col == 1 && $row > 1 && $value != ""){
                    $myobj->total += 1;
                    $myobj->pontuacao += $value;
                }
            }
        }

        $perc = ($myobj->pontuacao / ($myobj->total * 5)) * 100;
        if($perc >= 85){
            $perc_total = 3;
        } else if ($perc >= 70 && $perc < 85){
            $perc_total = 1.5;
        }else{
            $perc_total = 0;
        }

        $insert_line = "INSERT INTO db_adm.t_indicador_usuario20211
        (id_usuario, id_indicador, competencia, porcentagem, tarefas_atrasadas, tarefas_concluidas) VALUES ";

        foreach ($colaboradores as $colab) {

            $v_sql = "select id from db_adm.t_user where email = '{$colab}'";
            $result = pg_query($conn, $v_sql);
            $id = pg_fetch_result($result, 'id');

            if($id){
                $insert_line .= "({$id}, 10, '{$v_competencia}', {$perc_total}, NULL, NULL),";
            }

        }

        if (pg_query($conn, substr($insert_line, 0, -1))) {
            $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Tarefas processadas com sucesso."}';
        } else {
            $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
        }
        
        unlink($uploadfile);
        
    } catch (\Throwable $th) {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Falha no upload, por favor, tente novamente mais tarde!"}';
    }
    
    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}

if($v_acao == 'UPLOAD_BALANCETE'){

    $v_competencia = addslashes($_POST["v_competencia"]);
    $v_situacao = addslashes($_POST["v_situacao"]);

    $v_sql = "select id from db_adm.t_user where email = 'tatyane.martins@agrocontar.com.br'";
    $result = pg_query($conn, $v_sql);
    $id = pg_fetch_result($result, 'id');

    if($id){
        $v_situacao == 'concluido' ? $porcentagem = 3 : $porcentagem = 0;

        $insert_line = "INSERT INTO db_adm.t_indicador_usuario20211
        (id_usuario, id_indicador, competencia, porcentagem)
        VALUES({$id}, 6, '{$v_competencia}', $porcentagem)";
    }

    if (pg_query($conn, $insert_line)) {
        $json_msg = '{"msg_titulo":"SUCESSO!", "msg_ev":"success", "msg":"Tarefas processadas com sucesso."}';
    } else {
        $json_msg = '{"msg_titulo":"FALHA!", "msg_ev":"error", "msg":"Não foi possível executar o comando devido a uma falha na conexão com o banco de dados.  Entre em contato com o suporte local."}';
    }

    pg_close($conn);
    $v_json = json_encode($json_msg);
    echo $v_json;

}

if($v_acao == 'RELATORIO_FINANCEIRO'){

    $v_sql = "select colab.nome, cargo.nome cargo, sum(tiu.porcentagem) from db_adm_rh.t_rh_colaborador colab
	join db_adm.t_indicador_usuario20211 tiu on tiu.id_usuario = colab.id_usuario 
	JOIN db_adm_rh.t_rh_cargos cargo 
		on cargo.Id = (select hist_cargo.id_cargo 
						 from db_adm_rh.t_rh_hist_cargo hist_cargo 
						 where hist_cargo.matricula = colab.matricula  
						   and hist_cargo.id_empresa = colab.id_empresa
						   and hist_cargo.data = (select MAX(hist_cargo2.data) 
													from db_adm_rh.t_rh_hist_cargo hist_cargo2 
													where hist_cargo2.matricula = hist_cargo.matricula 
													  and hist_cargo2.id_empresa  = hist_cargo.id_empresa 
													  and hist_cargo.data <= current_date)) group by colab.nome, cargo.nome order by colab.nome";

    $result = pg_query($conn, $v_sql);
    $phpExcel = new Spreadsheet();
    $v_dados = array();
    $phpExcel->setActiveSheetIndex(0)
        ->setCellValue('A1','Colaborador')
        ->setCellValue('B1','Cargo')
        ->setCellValue('C1','Porcentagem')
        ->setCellValue('D1','Valor');
    $count = 2;
    while($row = pg_fetch_assoc($result)){
        $financeiro = 0;
		if(strpos($row["cargo"], "Auxiliar") !== false){
			$financeiro = 300;
		}
		if(strpos($row["cargo"], "Analista") !== false || strpos($row["cargo"], "Assistente") !== false || strpos($row["cargo"], "Assist") !== false){
			$financeiro = 750;
		}
		if(strpos($row["cargo"], "Especialista") !== false || strpos($row["cargo"], "Espec") !== false){
			$financeiro = 1000;
		}
		if(strpos($row["cargo"], "Coordenador") !== false || strpos($row["cargo"], "Coord") !== false){
			$financeiro = 1400;
		}
		if(strpos($row["cargo"], "Diretor") !== false || strpos($row["cargo"], "Socio Diretor") !== false || strpos($row["cargo"], "Ger ") !== false || strpos($row["cargo"], "Gerente") !== false || strpos($row["cargo"], "Sup") !== false || strpos($row["cargo"], "Supervisor") !== false){
			$financeiro = 1900;
		}
        $v_dados[] = array(
            "colaborador" => $row["nome"],
            "cargo" => $row["cargo"],
            "porcentagem" => $row["sum"],
            "financeiro" => ($financeiro*$row["sum"])/100
        );
        $phpExcel->setActiveSheetIndex(0)
        ->setCellValue("A{$count}",$row["nome"])
        ->setCellValue("B{$count}",$row["cargo"])
        ->setCellValue("C{$count}",$row["sum"])
        ->setCellValue("D{$count}",($financeiro*$row["sum"])/100);
        $count++;
    }

    $writer = new Xlsx($phpExcel);
    // header('Content-Description: File Transfer');
    // header('Content-Type: Application/vnd.ms-excel');
    // header('Content-Disposition: attachment; filename="downloadExcel.xlsx"');
    $writer->save('../../tmp/Relatorio_Financeiro_Premiaçao.xlsx');


    pg_close($conn);
    $v_json = json_encode($v_dados);
    echo $v_json;

}