<?php
session_start();
require_once( dirname( __FILE__ ).'/reports/dompdf/autoload.inc.php' );
$report_name = $_POST['pdf_report_name'];
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$dompdf = new Dompdf($options);
$options->setDefaultFont('Helvetica');
$options->setIsRemoteEnabled('true');

ob_start();
require dirname( __FILE__ ).'/reports/templates/pdf-template.php';
$template = ob_get_clean();

$dompdf->loadHtml($template);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream($report_name.".pdf");

unset($_SESSION['nth_report']);