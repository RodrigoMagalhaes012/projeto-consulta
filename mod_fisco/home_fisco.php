<?php
include_once("../class/php/class_conect_db.php");

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
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
  <link href="css/home.css" rel="stylesheet">
  <link href="../class/DataTables/datatables.min.css" rel="stylesheet">
  <link href="../class/DataTables/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../class/DataTables/buttons.dataTables.min.css" rel="stylesheet">

  <title>Unifica</title>
  <style>
    .form-check-label{
      margin-left: 26px;
    }

    .form-check-input{
      margin-left: 5px !important;
      left: 15px !important;
    }
    /* RESET RULES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
    :root {
      --white: white;
      --gradient: linear-gradient(-45deg, #ffa600 0%, #ff5e03 50%);
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

    /* FORM
–––––––––––––––––––––––––––––––––––––––––––––––––– */
    [type="checkbox"] {
      position: absolute;
      left: -9999px;
    }

    .feedback-label,
    .form {
      position: fixed;
      top: 35%;
      right: 0;
      backface-visibility: hidden;
    }

    .feedback-label {
      transform-origin: top right;
      transform: rotate(-90deg) translate(50%, -100%);
      /*uncomment this if you want to reverse the order of the characters*/
      /*transform: rotate(90deg) translateX(50%);*/
      z-index: 2;
    }

    .form {
      width: var(--form-width);
      max-height: 90vh;
      transform: translate(100%, -50%);
      padding: 30px;
      overflow: auto;
      background: var(--form);
      z-index: 1;
    }

    .feedback-label,
    .form,
    .form button {
      transition: all 0.35s ease-in-out;
    }

    .feedback-label,
    .form input,
    .form textarea,
    .form button {
      border-radius: var(--border-radius);
    }

    .feedback-label,
    .form button {
      background: var(--gradient);
      color: var(--white);
    }

    .feedback-label:hover,
    .form button:hover {
      filter: hue-rotate(-45deg);
    }

    .feedback-label {
      padding: 5px 10px;
      border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    form div:not(:last-child) {
      margin-bottom: 20px;
    }

    form div:last-child {
      text-align: right;
    }

    .form input,
    .form textarea {
      padding: 0 5px;
      width: 100%;
    }

    .form button {
      padding: 10px 20px;
      width: 50%;
      max-width: 120px;
    }

    .form input {
      height: 40px;
    }

    .form textarea {
      height: 220px;
    }

    [type="checkbox"]:checked+.feedback-label {
      /*EDGE is buggy with calc + variables + transform - use a hardcoded value instead of variables*/
      transform: rotate(-90deg) translate(50%, calc((var(--form-width) + 100%) * -1));
      /*uncomment this if you want to reverse the order of the characters*/
      /*transform: rotate(90deg) translate(50%, var(--form-width));*/
    }

    [type="checkbox"]:focus+.feedback-label {
      outline: 2px solid rgb(77, 144, 254);
    }

    [type="checkbox"]:checked~.form {
      transform: translate(0, -50%);
    }

    /* MQ
–––––––––––––––––––––––––––––––––––––––––––––––––– */
    @media screen and (max-width: 600px) {
      /* body {
    font-size: 16px;
  } */

      .form {
        padding: 15px;
        width: var(--form-mob-width);
      }

      form div:not(:last-child) {
        margin-bottom: 10px;
      }

      [type="checkbox"]:checked+.feedback-label {
        transform: rotate(-90deg) translate(50%, calc((var(--form-mob-width) + 100%) * -1));
        /*uncomment this if you want to reverse the order of the characters*/
        /*transform: rotate(90deg) translate(50%, var(--form-mob-width));*/
      }
    }

    /* FOOTER
–––––––––––––––––––––––––––––––––––––––––––––––––– */
    .page-footer {
      position: absolute;
      right: 10px;
      bottom: 10px;
      font-size: 0.85rem;
      color: var(--black);
    }

    .page-footer span {
      color: #e31b23;
    }
    
  </style>
  <!-- home Theme Style -->

</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">
            <a onclick="window.location='home.php'" class="site_title" style="font-size: 16px;"><img style="width: 58px; height: 55px;" src="img/home/2-0.png" alt=""> <span style="font-size: 21px;">Agro Fisco</span></a>
          </div>

          <div class="clearfix"></div>

          <!-- menu profile quick info
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="img/home/img.jpg" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Bem vindo,</span>
                <h2>Rodrigo Amorim</h2>
              </div>
            </div>
            /menu profile quick info

            <br /> -->

          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
              <h3></h3>
              <ul class="nav side-menu">
                <li><a><i class="fa fa-home"></i> Início <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a href="">Home</a></li>
                    <li><a href="">Ajuda</a></li>
                  </ul>
                </li>
                <li><a><i class="fa fa-home"></i> NFe <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a onclick="$('#div_tela').load('danfe_cons_entrada.php');">Danfes de Entrada</a></li>
                    <li><a onclick="$('#div_tela').load('danfe_cons_saida.php');">Danfes de Saída</a></li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
          <!-- /sidebar menu -->

          <!-- /menu footer buttons -->
          <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
              <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
              <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
              <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout" onclick="window.location='home.php'">
              <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
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
                  <img src=<?php echo "../mod/img/user_foto/{$_SESSION["vs_id"]}.jpg " ?> alt=""><?php echo ucwords(mb_strtolower($_SESSION["vs_nome"])); ?>
                  <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu pull-right">
                  <li><a onclick="$('#div_tela').load('../mod/cad_meus_dados.php');"> Meu Cadastro</a></li>
                  <li>
                    <a href="javascript:;">
                      <span class="badge bg-red pull-right">50%</span>
                      <span>Configurações</span>
                    </a>
                  </li>
                  <li><a href="javascript:;">Ajuda</a></li>
                  <li><a onclick="window.location.href='app.agrocontar.com.br'"><i class="fa fa-sign-out pull-right"></i> Sair</a></li>
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
              <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu" style="margin: 0px; padding: 0px;">
                <div class="text-center" data-example-id="simple-button-group" style="margin: 0px; padding: 0px;">
                  <div class="btn-group" role="group" aria-label="Basic example" style="margin: 0px; padding: 0px;">
                    <button type="button" class="btn btn-success" style="margin: 5px; height: 60px; width:60px;" onclick="window.location='../mod_fisco/home_fisco.php'">FSC</button>
                    <button type="button" class="btn btn-success" style="margin: 5px; height: 60px; width:60px;" onclick="window.location='../mod_rh/home_rh.php'">RH</button>
                    <button type="button" class="btn btn-success" style="margin: 5px; height: 60px; width:60px;" onclick="window.location='../mod/home.php'">ADM</button>
                    <button disabled type="button" class="btn btn-success" style="margin: 5px; height: 60px; width:60px;">CON</button>
                  </div>
                </div>
              </ul>
            </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- /top navigation -->
      <input type="checkbox" id="mycheckbox">
      <label for="mycheckbox" class="feedback-label">SUGESTÕES</label>
      <form class="form">
        <div>
          <label for="comment">Deixe aqui sua sugestão ou crítica</label>
          <textarea id="comment"></textarea>
        </div>
        <div>
          <button onclick="func_envia_sugestao(event)">Enviar</button>
        </div>
      </form>
      <!-- page content -->
      <div id="div_tela" class="right_col" role="main">

        <!-- #################### -->
        <!-- QUI FICARÁ O INCLUDE -->
        <!-- #################### -->



      </div>
      <!-- /page content -->

      <!-- footer content -->
      <footer>
        <div class="pull-right">
          <a href="http://agrocontar.com.br" target="_blank">Agrocontar - Suporte ao Cliente</a>
        </div>
        <div class="clearfix"></div>
      </footer>
      <!-- /footer content -->
    </div>
  </div>

  <script src="../class/jquery/dist/jquery.min.js"></script>
  <script src="../class/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../class/alert/js/class_alert.js"></script>
  <script src="../class/jquery/dist/jquery.mask.min.js"></script>
  <script src="js/home.js"></script>

</body>

<script language="JavaScript">
  $(document).ready(function() {

  });

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

  function func_rota(v_id_rota) {

    var v_id_rota = v_id_rota;

    $.ajax({
      type: "POST",
      url: "lib/lib_rotas.php",
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
</script>






</html>