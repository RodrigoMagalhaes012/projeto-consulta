<?php
include_once("../class/php/class_conect_db.php");
require "../class/php/class_criptografia.php";

if(!isset($_SESSION["vs_id"])){
  header("Location: ../");
  die;
}

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (strpos($_SESSION["vs_array_access"], "M0004") === 0) {
  print('<script> alert(\'Favor realizar login novamente!\'); location.href = \'https://app.agrocontar.com.br\'; </script>');
}

//Instanciando classe
$mc = new MyCripty();
//Texto a ser criptogrfado
$enc = $mc->enc(str_pad($_SESSION["vs_id"], 6, "0", STR_PAD_LEFT) . $_SESSION["vs_db_empresa"]);

$_SESSION["vs_chave_chat"] = $mc->enc(str_pad($_SESSION["vs_id"], 6, "0", STR_PAD_LEFT) . $_SESSION["vs_db_empresa"]);

?>



<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../img/home/favicon.png" type="image/ico" />
  <link rel="stylesheet" href="../class/alert/css/class_alert.css" id="theme-styles">
  <link href="../class/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../class/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../class/DataTables/datatables.min.css" rel="stylesheet">
  <link href="../class/dual-listbox/src/bootstrap-duallistbox.css" rel="stylesheet">
  <link href="../css/home.css" rel="stylesheet">
  <style>
    .form-check-label {
      margin-left: 26px;
    }

    .form-check-input {
      margin-left: 5px !important;
      left: 15px !important;
    }

    /* RESET RULES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
    :root {
      --white: white;
      /* --gradient: linear-gradient(-45deg, #ffa600 0%, #ff5e03 50%); COR-ALTERADA */
      --gradient: linear-gradient(-45deg, #925fb3 0%, #271f4e 100%);
      --form: #eeefef;
      --border-radius: 4px;
      --form-width: 500px;
      --form-mob-width: 320px;
    }

    * {
      padding: 0;
      margin: 0;
      box-sizing: border-box;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    button,
    label {
      cursor: pointer;
    }

    label {
      display: block;
    }

    button,
    input,
    textarea {
      font-family: inherit;
      font-size: 100%;
      border: none;
      text-transform: none !important;
    }

    textarea {
      resize: none;
    }

    h1 {
      /* font-size: 2rem; */
      text-align: center;
      margin-top: 20vh;
    }


    /* FADE SELETOR DE MÓDULOS
–––––––––––––––––––––––––––––––––––––––––––––––––– */
    .fade {
      opacity: 0.5;
    }

    .fade:hover {
      opacity: 1;
    }
  </style>

  <!-- home Theme Style -->

</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
          <div class="navbar nav_title text-center" style="border: 0;">
            <a onclick="window.location=''" class="site_title" style="font-size: 16px;"><img style="width: 58px; height: 55px;" src="../img/home/logo.png" alt=""> </a>
          </div>

          <div class="clearfix"></div>

          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
              <h3></h3>
              <ul class="nav side-menu">
                <ul class="nav"">
                  <li><a href="" style=" color: white;"><i class="fa fa-home"></i> Início </a>
                </ul>
                <!-- <li><a href=""><i class="fa fa-home"></i> Início <span class="fa fa-chevron-down"></span></a>
                </li> -->
                </li>
                <li <?php if (strpos($_SESSION["vs_array_access"], "T0040") == 0) {
                      print('style="display: none;');
                    } ?>><a><i class="fa fa-lock"></i> Área Restrita <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <?php if (strpos($_SESSION["vs_array_access"], "T0089") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'cons_sugestoes.php\');">Sugestões</a></li>');
                    } ?>
                    <?php if (strpos($_SESSION["vs_array_access"], "T0056") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'cad_grupos_noticias.php\');">Grupos de Notícias</a></li>');
                    } ?>
                    <?php if (strpos($_SESSION["vs_array_access"], "T0055") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'cad_liberacao_politica.php\');">Liberação publicar politica</a></li>');
                    } ?>
                    <?php if (strpos($_SESSION["vs_array_access"], "T0043") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'cad_grupos_gh.php\');">Grupos de Gestão Hieráquica</a></li>');
                    } ?>
                    <?php if (strpos($_SESSION["vs_array_access"], "T0006") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'cad_empresas.php\');">Cadastro de Empresas</a></li>');
                    } ?>
                    <?php if (strpos($_SESSION["vs_array_access"], "T0008") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'cad_grupo_emp.php\');">Cadastro de Visibilidade</a></li>');
                    } ?>
                    <?php if (strpos($_SESSION["vs_array_access"], "T0032") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'cad_grupo_telas.php\');">Grupo de Telas</a></li>');
                    } ?>
                    <?php if (strpos($_SESSION["vs_array_access"], "T0009") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'cad_acessos_usuarios.php\');">Acessos do Usuário</a></li>');
                    } ?>
                    <?php if (strpos($_SESSION["vs_array_access"], "T0012") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'cons_versao.php\');">Consulta Versões</a></li>');
                    } ?>
                  </ul>
                </li>
                <li <?php if (strpos($_SESSION["vs_array_access"], "T0041") == 0) {
                      print('style="display: none;');
                    } ?>><a><i class="fa fa-edit"></i> Gestão de Usuários <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <?php if (strpos($_SESSION["vs_array_access"], "T0011") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'cad_usuarios.php\');">Cadastro de Usuários</a></li>');
                    } ?>
                    <?php if (strpos($_SESSION["vs_array_access"], "T0010") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'redefine_senha.php\');">Redefinição de senha</a></li>');
                    } ?>
                    <?php if (strpos($_SESSION["vs_array_access"], "T0013") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'cfg_senhas.php\');">Políticas de Senha</a></li>');
                    } ?>
                  </ul>
                </li>


                <?php if (strpos($_SESSION["vs_array_access"], "T0045") > 0) {
                  print('<a style="visibility: hidden;" id="id_lgpd_menu_politica" onclick="$(\'#div_tela\').load(\'../mod_lgpd/lgpd_politica_privacidade.php\');"></a>');
                  print('<a style="visibility: hidden;" id="id_lgpd_menu_termo_uso" onclick="$(\'#div_tela\').load(\'../mod_lgpd/lgpd_termo_uso.php\');"></a>');
                } ?>
                <li>
                  <a><i class="fa fa-gavel"></i> LGPD <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <?php if (strpos($_SESSION["vs_array_access"], "T0048") > 0) {
                      print('<li><a id="id_lgpd_menu_sobre" onclick="$(\'#div_tela\').load(\'../mod_lgpd/home.php\');">Sobre a LGPD</a></li>');
                    } ?>
                    <?php if (strpos($_SESSION["vs_array_access"], "T0045") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'../mod_lgpd/pdo_config.php\');">DPO - Configurações</a></li>');
                    } ?>
                    <?php if (strpos($_SESSION["vs_array_access"], "T0046") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'../mod_lgpd/pdo_cons_dados_users.php\');">DPO - Consultar Dados</a></li>');
                    } ?>
                    <?php if (strpos($_SESSION["vs_array_access"], "T0046") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'../mod_lgpd/pdo_fila_sol.php\');">DPO - Fila de Pedidos</a></li>');
                    } ?>
                    <?php if (strpos($_SESSION["vs_array_access"], "T0047") > 0) {
                      print('<li><a onclick="$(\'#div_tela\').load(\'../mod_lgpd/pdo_sol_user.php\');">Solicitar Exclusão de Dados</a></li>');
                    } ?>
                  </ul>
                </li>

                <?php if (strpos($_SESSION["vs_array_access"], "T0002") > 0) {
                  $mc = new MyCripty();
                  print('<li>');
                  print('<a onclick="window.open(\'http://192.168.62.234:8090/chat/index_atendente.php?chave1=' . $mc->enc($_SESSION["vs_id"]) . "&chave2=" . $mc->enc($_SESSION["vs_nome"]) . "&chave3=" . $mc->enc($_SESSION["vs_db_empresa"]) . '\', \'_blank\');"><i class="fa fa-comments"></i> Prestar suporte via CHAT </a>');
                  print('</li>');
                } ?>

              </ul>
            </div>

          </div>
          <!-- /sidebar menu -->

          <!-- /menu footer buttons -->
          <div class="sidebar-footer hidden-small">
            <!-- <a data-toggle="tooltip" data-placement="top" title="Settings">
              <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
              <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
              <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout" href="#">
              <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a> -->
            <h6></h6>
          </div>
          <!-- /menu footer buttons -->
        </div>
      </div>

      <!-- top navigation -->
      <div class="top_nav">
        <div class="nav_menu">
          <nav>
            <div class="nav toggle">
              <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
              <li class="">
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <img src=<?php $v_sql = "SELECT url_arquivo
                                        from db_adm.t_user
                                        where id = {$_SESSION["vs_id"]}";
                            $url_arquivo = pg_fetch_array(pg_query($conn, $v_sql), 0)[0];

                            if ($url_arquivo != "") {
                              echo $url_arquivo;
                            } else {
                              echo "https://testephp.s3.amazonaws.com/usuario_padrao.png";
                            } ?> alt=""><?php echo ucwords(mb_strtolower($_SESSION["vs_nome"])); ?>
                  <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu pull-right">
                  <li><a onclick="$('#div_tela').load('cad_meus_dados.php');"> Meu Cadastro</a></li>
                  <li>
                    <a href="javascript:;">
                      <span class="badge bg-red pull-right">50%</span>
                      <span>Configurações</span>
                    </a>
                  </li>
                  <li><a href="https://app.agrocontar.com.br/ajuda_menu.php" target="_blank">Ajuda</a></li>
                  <li><a onclick="func_sair()"><i class="fa fa-sign-out pull-right"></i> Sair</a></li>
                </ul>
              </li>

              <li role="presentation" class="dropdown">
                <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-envelope-o"></i>
                  <span class="badge bg-green"></span>
                </a>
                <!-- <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                  <li>
                    <a>
                      <span class="image"><img src="img/home/img.jpg" alt="Profile Image" /></span>
                      <span>
                        <span>Rodrigo Amorim</span>
                        <span class="time">3 mins ago</span>
                      </span>
                      <span class="message">
                        Film festivals used to be do-or-die moments for movie makers. They were where...
                      </span>
                    </a>
                  </li>
                  <li>
                    <a>
                      <span class="image"><img src="img/home/img.jpg" alt="Profile Image" /></span>
                      <span>
                        <span>Rodrigo Amorim</span>
                        <span class="time">3 mins ago</span>
                      </span>
                      <span class="message">
                        Film festivals used to be do-or-die moments for movie makers. They were where...
                      </span>
                    </a>
                  </li>
                  <li>
                    <a>
                      <span class="image"><img src="img/home/img.jpg" alt="Profile Image" /></span>
                      <span>
                        <span>Rodrigo Amorim</span>
                        <span class="time">3 mins ago</span>
                      </span>
                      <span class="message">
                        Film festivals used to be do-or-die moments for movie makers. They were where...
                      </span>
                    </a>
                  </li>
                  <li>
                    <a>
                      <span class="image"><img src="img/home/img.jpg" alt="Profile Image" /></span>
                      <span>
                        <span>Rodrigo Amorim</span>
                        <span class="time">3 mins ago</span>
                      </span>
                      <span class="message">
                        Film festivals used to be do-or-die moments for movie makers. They were where...
                      </span>
                    </a>
                  </li>
                  <li>
                    <div class="text-center">
                      <a>
                        <strong>See All Alerts</strong>
                        <i class="fa fa-angle-right"></i>
                      </a>
                    </div>
                  </li>
                </ul> -->
              </li>

              <li role="presentation" class="dropdown">
                <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-birthday-cake"></i>

                </a>
                <!-- <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                  <li>
                    <a>
                      <span class="message">
                        Rodrigo Amorim 21/04
                      </span>
                    </a>
                  </li>
                  </a>
              </li>
            </ul> -->
              </li>

              <li role="presentation" class="dropdown">
                <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-server"></i>
                </a>
                <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu" style="margin: 0px; padding: 5px; width: 244px;">
                  <div class="text-center" data-example-id="simple-button-group" style="margin: 0px; padding: 0px;">
                    <div class="btn-group" role="group" aria-label="Basic example" style="margin: 0px; padding: 0px;">
                      <button><?php if (strpos($_SESSION["vs_array_access"], "M0003") > 0) {
                                echo '<div class="fade" style="margin: 5px; height: 60px; width:60px;">';
                                echo '<i class="" aria-hidden="true" id="btn_mod_rh" class="btn btn-success" onclick="location.href = \'../mod_fisco\';">';
                                echo '<img src="../img/modulos/gestao_fiscal.png" height="70" width="50" >';
                                echo '</i>';
                                echo '</div>';
                              } ?>
                      </button>

                      <button><?php if (strpos($_SESSION["vs_array_access"], "M0002") > 0) {
                                echo '<div class="fade" style="margin: 5px; height: 60px; width:60px;">';
                                echo '<i class="" aria-hidden="true" id="btn_mod_rh" class="btn btn-success" onclick="location.href = \'../mod_rh\';">';
                                echo '<img src="../img/modulos/portal_do_colaborador.png" height="70" width="50" >';
                                echo '</i>';
                                echo '</div>';
                              } ?>
                      </button>

                      <button><?php if (strpos($_SESSION["vs_array_access"], "M0004") > 0) {
                                echo '<div class="fade" style="margin: 5px; height: 60px; width:60px;">';
                                echo '<i class="" aria-hidden="true" id="btn_mod_rh" class="btn btn-success" onclick="location.href = \'../mod\';">';
                                echo '<img src="../img/modulos/administrador_local.png" height="70" width="50" >';
                                echo '</i>';
                                echo '</div>';
                              } ?>
                      </button>

                    </div>
                  </div>
                </ul>
              </li>
              <li role="presentation" class="dropdown">
                <select onchange="func_muda_empresa()" id="c_select_empresa" class="form-control class_inputs" style="margin-top: 4%; max-width: 300px;">

                </select>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- /top navigation -->


      <!-- page content -->
      <div id="div_tela" class="right_col" role="main">



        <!-- #################### -->
        <!-- QUI FICARÁ O INCLUDE -->
        <!-- #################### -->



      </div>


      <!-- Caixa de sugestões -->






      <footer style="height: 90px;">

        <div class="clearfix">
          <?php if (!isset($_COOKIE["cookie_lgpd"])) {
            print_r('  <div id="cookie-law-info-bar" class="row" data-nosnippet="true" style="padding: 30px; padding-top: 10px; padding-bottom: 10px; margin-bottom: 10px; margin-right: 5px; background-color: #D1cfe5; color: rgb(63, 64, 64); font-family: Helvetica, Arial, sans-serif; bottom: 0px; position: fixed; display: block;">');
            print_r('    <div class="col-sm-11" style="font-size: 13px;">');
            print_r('      <b>Importante: </b>Este site usa cookies para lhe fornecer um serviço mais ágil e personalizado. Ao usar este site, você concorda com o uso de cookies. Para obter mais informações, leia nossa POLÍTICA DE PRIVACIDADE.');
            print_r('      <a aria-label="learn more about cookies" role="button" tabindex="0" class="cc-link sbAccessibilityFontSize " onclick="func_lgpd_ler_politica_privaci();" rel="noopener noreferrer nofollow" target="_blank">Política de Privacidade</a>');
            print_r('    </div>');
            print_r('    <div class="col-sm-1">');
            print_r('      <button class="btn btn-info" style="background-color: #523B8F; border-color: #523B8F" onclick="func_lgpd_cookie_ok();">Entendi !</button>');
            print_r('     </div>');
            print_r('  </div>');
          }
          ?>
        </div>
        <div class="text-center" style="color: black;">
          <b>Direitos reservados Plataforma Unifica 2021</b>
        </div>
      </footer>

      <!-- /footer content -->
    </div>
  </div>
  <script src="../class/jquery/dist/jquery.min.js"></script>
  <script src="../class/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../class/alert/js/class_alert.js"></script>
  <script src="../class/jquery/dist/jquery.mask.min.js"></script>
  <script src="../js/home.js"></script>
</body>




<script language="JavaScript">
  $(document).ready(function() {
    func_lista_empresas();
  });

  function func_sair(){
      $.ajax({
        type: "POST",
        url: "../mod/lib/lib_login.php",
        dataType: "json",
        data: {
          "v_acao": "SAIR"
        },
        success: function(data) {
          window.location.href = '../'
        }
      });
    }

  function func_lgpd_cookie_ok() {
    $("#cookie-law-info-bar").hide();
    var v_acao = "REGISTRA_CIENCIA_COOKIES";
    $.ajax({
      type: "POST",
      url: "../mod_lgpd/lib/lib_lgpd_cookie.php",
      dataType: "json",
      data: {
        "v_acao": v_acao
      },
      success: function(data) {}
    });

  }



  function func_lgpd_ler_politica_privaci() {
    var v_acao = "REGISTRA_ACESSO_POLITICA_PRIVACI";

    $.ajax({
      type: "POST",
      url: "../mod_lgpd/lib/lib_lgpd_cookie.php",
      dataType: "json",
      data: {
        "v_acao": v_acao
      },
      complete: function(data) {
        window.open("../mod_lgpd/política_privacidade.pdf", "_blank");
      }
    });

  }



  function func_lista_empresas() {
    $.ajax({
      type: "POST",
      url: "lib/lib_selecao_empresas.php",
      data: {
        "v_acao": "EV_LISTA_EMPRESAS"
      },
      success: function(data) {
        let options = ''
        $("#c_select_empresa").empty()
        data.forEach(element => {
          options += `<option value="${element.id_emp} - ${element.db_emp}">${element.nome}</option>`
        });
        $("#c_select_empresa").html(options)
        $("#c_select_empresa").val(data[0].id_emp_atual + ' - ' + data[0].db_emp_atual)
        func_empresa_atual()
      }
    });
  }

  function func_muda_empresa() {
    let emp = $("#c_select_empresa").val()
    emp = emp.split(' - ')
    $.ajax({
      type: "POST",
      url: "lib/lib_selecao_empresas.php",
      data: {
        "v_acao": "EV_MUDA_EMPRESA",
        "v_db_empresa": emp[1],
        "v_id_empresa": emp[0]
      },
      success: function(data) {
        location.href = ''
      }
    });
  }

  function func_empresa_atual() {
    $.ajax({
      type: "POST",
      url: "lib/lib_selecao_empresas.php",
      data: {
        "v_acao": "EV_EMPRESA_ATUAL"
      },
      success: function(data) {
        let json = JSON.parse(data)
        $("#c_select_empresa").val(json.emp_atual)
      }
    });
  }

  function func_rota(v_id_rota) {

    var v_id_rota = v_id_rota;

    $.ajax({
      type: "POST",
      url: "../mod/lib/lib_rotas.php",
      dataType: 'json',
      data: {
        "v_id_rota": v_id_rota
      },
      success: function(data) {

        var v_json = JSON.parse(data);

        if (v_json.msg_ev == "success") {
          location.href = v_json.msg;
        } else {
          swal("SUCESSO!", v_json.msg, v_json.msg_ev);
        }
      }
    });
  }


  function func_envia_sugestao(event) {
    event.preventDefault()

    $.ajax({
      type: "POST",
      url: "lib/lib_sugestao.php",
      dataType: 'json',
      data: {
        "v_acao": "ENVIAR_SUGESTAO",
        "v_sugestao": $("#comment").val()
      },
      success: function(data) {

        $("#comment").val("")

        Swal.fire({
          icon: "success",
          title: "SUGESTÃO ENVIADA!",
          text: "Muito obrigado pela sua sugestão!"
        })

        // if (v_json.msg_ev == "success") {
        //   location.href = v_json.msg;
        // } else {
        //   swal("SUCESSO!", v_json.msg, v_json.msg_ev);
        // }
      }
    });
  }
</script>

</html>