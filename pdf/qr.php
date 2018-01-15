<?php
require('../library/core.php');

ini_set('display_errors',1);
error_reporting(E_ALL);

require('pdf.php');

define('PDFSCRIPT',true);
define('FPDF_FONTPATH','fonts/');

$pdf=new PDF();

$pdf->SetLineWidth(0.1);

$pdf->AddFont('helvetica','','helvetica.php');
$pdf->AddFont('helvetica','B','helveticab.php');
$pdf->AddFont('helvetica','I','helveticai.php');
$pdf->AddFont('helvetica','BI','helveticabi.php');

$pdf->SetFont('helvetica','',9);
$pdf->SetAutoPageBreak(false);

for($i=0;$i<intval($_GET['count']);$i++) {
	if(!($i%2)) {
		$pdf->AddPage();
		$y = 0;
	} else {
		#$pdf->Line(0,148.5,210,148.5);
		$y = 148.5;
	}

	$id = db_value('SELECT id FROM qr ORDER BY id DESC LIMIT 1')+1;
	$hash = md5($id);
	db_query('INSERT INTO qr (id, code, created) VALUES ('.$id.',"'.$hash.'",NOW())');
	
	$pdf->Image('https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://market.drapenihavet.no/'.$settings['rootdir'].'/mobile.php?barcode='.$hash, 165, 12+$y, 35, 35, 'png');

	$pdf->Image('logo.png', 92, 107+$y, 26, 31);


	$pdf->Line(50,$y+80,160,$y+80);
	$pdf->Text(50,$y+84,'Contents');

	$pdf->Line(10,$y+30,70,$y+30);
	$pdf->Text(10,$y+34,'Box Number');

	$pdf->Line(10,$y+130,70,$y+130);
	$pdf->Text(10,$y+134,'Gender');

	$pdf->Line(140,$y+130,200,$y+130);
	$pdf->Text(194,$y+134,'Size');

}

$pdf->Output('I');

