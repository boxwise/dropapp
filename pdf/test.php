<?php
include('zinnebeeldcms.php');

define('FPDF_FONTPATH','fonts/');
require('fpdf.php');
require('fpdi.php');

setlocale (LC_ALL, '');
setlocale (LC_TIME, 'nl_NL');
 
$pdf=new fpdi();
$pdf->Open();
$pdf->AddFont('zinnebeeld','','fontzinnebeeld.php');
$pdf->AddFont('zinnebeeld','B','fontzinnebeeldbold.php');
$pdf->AddFont('zinnebeeld','I','fontzinnebeelditalic.php');
$pdf->AddFont('zinnebeeld','BI','fontzinnebeeldbolditalic.php');


	
	$pdf->AddPage();
	
	$pdf->SetFont('zinnebeeld','B',12);
	$pdf->SetTextColor(204,0,0);
	$pdf->Text(30,94,'Link naar nu.nl');
	$pdf->Text(30,104,'Link naar file');
	
	$pdf->Link(30,90,30,5,'http://www.nu.nl');
	$pdf->Link(30,100,30,5,'file://./test.html');
	
$pdf->Output('Test.pdf',true);
?>
