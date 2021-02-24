<?php
    
    $img = $_POST['v_img'];
    $v_cpf = preg_replace("/[^0-9]/", "", $_POST["v_cpf"]);
    $v_pasta = $_POST['v_pasta'];
    

    $image_parts = explode(";base64,", $img);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
  
    $image_base64 = base64_decode($image_parts[1]);
    $file = "../../img/".$v_pasta."/".$v_cpf.".jpg";
    file_put_contents($file, $image_base64);

    
?>