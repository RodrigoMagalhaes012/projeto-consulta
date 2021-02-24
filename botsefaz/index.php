<!DOCTYPE html>
<html>

<head>
  <title>Unifica</title>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Fullscreen Background Image Slideshow with CSS3 - A Css-only fullscreen background image slideshow" />
  <meta name="keywords" content="css3, css-only, fullscreen, background, slideshow, images, content" />
  <meta name="author" content="RENALU-TECH Soluções web">
  <link rel="icon" href="../img/home/favicon.png" type="image/ico" />
  <link rel="stylesheet" href="class/alert/css/class_alert.css" id="theme-styles">
  <link href="../class/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../class/font-awesome/css/font-awesome.min.css" rel="stylesheet">

</head>

<body style="background-color: black;">
  <div class="container">
    <div class="row w-100" style="margin-top: 30px;">
      <h1 class="text-center" style="color: gray;">SCRIPT SEFAZ NACIONAL</h1>
    </div>
    <div class="row w-100">
      <div class="col-sm-4">
      </div>
      <div class="col-sm-4">
        <img src="img/logo_app_agrocontar.png" class="img-responsive" alt="Imagem Responsiva">
      </div>
      <div class="col-sm-4">
      </div>
    </div>
    <div class="row w-100">
      <h1 class="text-center" style="color: gray;">Consulta de NFEs</h1>
    </div>

    <div class="row w-100 text-center">
    </div>

  </div>

</body>
<script src="../class/jquery/dist/jquery.min.js"></script>
<script src="../class/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../class/jquery/dist/jquery.mask.min.js"></script>
<script src="../class/alert/js/class_alert.js"></script>

<script>
  $(document).ready(function() {
    run_script();
  });


  function run_script() {
    var v_acao = "START_SCRIPT";
    setTimeout(function() {
      $("#btn_script").prop("disabled", false);
    }, 3000);

    $.ajax({
      type: "POST",
      url: "script_sefaz_nfe.php",
      dataType: "json",
      data: {
        "v_acao": v_acao
      }
    });
  }

</script>




</html>