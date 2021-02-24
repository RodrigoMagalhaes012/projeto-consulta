<?php
header('Content-Type: image/png');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}



// GERANDO CÓDIGO CAPTCHA DE 6 DIGITOS
$basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$%&';
$v_id_captcha = "";
for ($count = 0; 6 > $count; $count++) {
    $v_id_captcha .= $basic[rand(0, strlen($basic) - 1)];
}

$_SESSION["vs_captcha"] = $v_id_captcha;
$v_img_captcha = imagecreatefrompng("../../img/index/img_captcha.png");
$v_fonte = imageloadfont("../../fonts/font_captcha_anonymous.gdf");
$v_cor = imagecolorallocate($v_img_captcha, 0, 0, 0);
imagestring($v_img_captcha, $v_fonte, 15, 5, $v_id_captcha, $v_cor);
imagepng($v_img_captcha);
imagedestroy($v_img_captcha);
