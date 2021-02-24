<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use Dompdf\Dompdf;
require_once("autoload.inc.php");
$dompdf = new DOMPDF();
$dompdf->setPaper("A4", "portrait");
$v_pdf = $_SESSION["vs_pdf"];

$dompdf->load_html($v_pdf);

$dompdf->render();
$dompdf->stream("relatorio.pdf", array("Attachment" => false));

?>
