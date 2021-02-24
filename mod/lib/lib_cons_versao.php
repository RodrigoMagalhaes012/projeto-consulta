<?php
header("Content-Type: application/json; charset=utf-8");
include_once( "../../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// OBTENDO o comando A SER EXECUTADA
$v_acao = addslashes($_POST["v_acao"]);

// GERANDO LISTA DE RECIBOS
if ($v_acao == "LISTAR") {

  // GERANDO A LISTA
  $v_sql = "SELECT Versao, Titulo from db_adm.t_versao ORDER BY  versao";
  //var_dump($v_sql);            
  $result = pg_query($conn, $v_sql);

  $v_dados = array();
  while ($row = pg_fetch_assoc($result)) {
   $v_dados[] = array("Versao" => $row["versao"], "Titulo" => $row["titulo"]);    
  }

  // ENVIANDO DADOS
	pg_close($conn);  
  $v_json = json_encode($v_dados);
  echo $v_json;
}


// SELECIONANDO REGISTRO
if ($v_acao == "EV_CARREGA_VERSAO") {

  $v_versao = addslashes($_POST["v_versao"]);

  // SELECIONANDO RELEASES DAS VERSOES DISPONIVEIS
  $v_sql = "select ver.versao Versao, rel.releasse Alteracao,ver.titulo Descricao, rel.data Data_release, mod.nome Modulo 
              from db_adm.t_versao ver, db_adm.t_versao_release rel, db_adm.t_modulos mod
             where ver.versao = rel.versao 
               and rel.id_modulo = mod.id 
               and ver.versao = '". $v_versao."'"
           ."Order by ver.versao,rel.releasse";

  $result2 = pg_query($conn, $v_sql);

  while ($row = pg_fetch_assoc($result2)) {
    $v_dados[] = array("Versao" => $row["versao"], "Alteracao" => $row["alteracao"], "Descricao" => $row["descricao"], "Data_release" => $row["data_release"], "Modulo" => $row["modulo"]);

  }

  // ENVIANDO DADOS2
  pg_close($conn);
  $v_json = json_encode($v_dados);
  echo $v_json;
}

// SELECIONANDO REGISTRO
if ($v_acao == "EV_CARREGA_INFORMACAO") {

  $v_versao = addslashes($_POST["v_versao"]);

  // SELECIONANDO RELEASES DAS VERSOES DISPONIVEIS
  $v_sql = "SELECT Versao, releasse Alteracao,Descricao from db_adm.t_versao_release 
             WHERE ver.versao = '". $v_versao."'"
           ."Order by versao,releasse";

  $result2 = pg_query($conn, $v_sql);

  while ($row = pg_fetch_assoc($result2)) {
    $v_dados[] = array("Versao" => $row["versao"], "Alteracao" => $row["alteracao"], "Descricao" => $row["descricao"]);
  }

  // ENVIANDO DADOS2
  pg_close($conn);
  $v_json = json_encode($v_dados);
  echo $v_json;
}