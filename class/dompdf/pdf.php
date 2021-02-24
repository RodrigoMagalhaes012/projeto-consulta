<?php
header("Content-Type: application/json; charset=utf-8");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$v_html = "";
$v_html_pdf = $_SESSION["vs_pdf_html"];
$v_html_pdf = explode("|", $v_html_pdf);
$v_html_pdf_titulo = $v_html_pdf[0];
$v_html_pdf_orientacao = $v_html_pdf[1];
$v_html_pdf_boby = $v_html_pdf[2];

if ($v_html_pdf_titulo != "FALSE") {
    $v_html = '<table style="width: 100%;">[
        <tr>
            <td style="text-align: center; width: 30%;">LOGO</td>
            <td style="text-align: center; width: 80%;">DESCRIÇÃO O RELATÓRIO</td>
        </tr>
        </table>' . $v_html_pdf_boby;
} else {
$v_html .= $v_html_pdf_boby;
}



use Dompdf\Dompdf;
// include autoloader
require_once("../dompdf/autoload.inc.php");

//Criando a Instancia
$dompdf = new Dompdf();

$dompdf->setBasePath(realpath('../../'));

$dompdf->loadHtml($v_html);

// Definindo o papel e a orientação
if ($v_html_pdf_orientacao == "PAISAGEM") {
    $dompdf->setPaper('A4', 'landscape');
} else {
    $dompdf->setPaper('A4', 'portrait');
}

//Renderizar o html
$dompdf->render();

//Para realizar o download somente alterar para true
$dompdf->stream("arquivo.pdf", ["Attachment" => false]);
