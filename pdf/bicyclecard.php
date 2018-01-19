<?php
require('../library/core.php');

ini_set('display_errors',1);
error_reporting(E_ALL);

require('pdf-card.php');

define('PDFSCRIPT',true);
define('FPDF_FONTPATH','fonts/');

$title = $_GET['title'];

$pdf=new PDF();

$pdf->AddFont('helvetica','','helvetica.php');
$pdf->AddFont('helvetica','B','helveticab.php');
$pdf->AddFont('helvetica','I','helveticai.php');
$pdf->AddFont('helvetica','BI','helveticabi.php');

$pdf->SetFont('helvetica','',10);
$pdf->LeftMargin = 15; 
$pdf->TopMargin = 15; 
$pdf->W = 85; 
$pdf->H = 55; 
$pdf->SetAutoPageBreak(false);
$pdf->SetLineWidth(0.1);

#$id = "1050,1067,1068,1084,1093";
$ids = explode(',',$_GET['id']);
foreach($ids as $key=>$id) $ids[$key] = intval($id); 
$ids = join(',',$ids);

$result = db_query('SELECT *, CONCAT(firstname," ",lastname) AS name, DATE_FORMAT(date_of_birth,"%d-%m-%Y") AS birthdate, DATE_FORMAT(NOW(),"%d-%m-%Y %H:%i") AS issued FROM people WHERE id IN ('.$ids.')');


while($p = db_fetch($result)) {
	$picture = (file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/people/'.intval($p['id']).'.jpg')?
		$_SERVER['DOCUMENT_ROOT'].'/uploads/people/'.intval($p['id']).'.jpg':'');
		
	if($p['bicycletraining'] && $picture) {
		$exif = exif_read_data($_SERVER['DOCUMENT_ROOT'].'/uploads/people/'.intval($p['id']).'.jpg');
		$rotate = ($exif['Orientation']==3?180:($exif['Orientation']==6?-90:($exif['Orientation']==8?90:0)));
		list($imgw,$imgh) = getimagesize($picture);
	
		$pdf->newCard();
		$pdf->Rect($pdf->X,$pdf->Y,$pdf->W,$pdf->H);
		$pdf->Rect($pdf->X,$pdf->Y+$pdf->H,$pdf->W,$pdf->H);
	
		$pdf->SetFont('helvetica','B',12.5);
		$pdf->SetTextColor(100,0,0,0);
		$pdf->Print(4,7,'Drop in the ocean');
		$pdf->SetTextColor(0);
		$pdf->Print(43,7,'Bicycle Certificate');
		
		$photow = 30;
		$photoh = 40;
		$photox = 4;
		$photoy = 10;
		
		#dump($imgw);
		$pdf->ClippingRect($pdf->X+$photox,$pdf->Y+$photoy,$photow,$photoh,1);
		if($rotate) $pdf->Rotate($rotate,$pdf->X+$photox+($photow/2),$pdf->Y+$photoy+($photoh/2));
		if($imgw/$imgh>$photow/$photoh) {
			$w = $photoh*$imgw/$imgh;
			$pdf->Image($picture,$pdf->X+$photox+($photow/2)-($w/2),$pdf->Y+$photoy,$w,$photoh);
		} else {
			$h = $photow*$imgh/$imgw;
			$pdf->Image($picture,$pdf->X+$photox,$pdf->Y+$photoy+($photoh/2)-($h/2),$photow,$h);
		}
		$pdf->Rotate(0);
		$pdf->UnsetClipping();
		
		$labels = array('name','container','date of birth','phone','issued');
		$data = array('name','address','birthdate','phone','issued');
		for($l=0;$l<5;$l++) {
			$pdf->line($pdf->X+38,$pdf->Y+15.5+($l*8),$pdf->X+81,$pdf->Y+15.5+($l*8));
			$pdf->SetTextColor(50);
			$pdf->SetFont('helvetica','',6.5);
			$pdf->Print(38,18+($l*8),$labels[$l]);
			$pdf->SetTextColor(0);
			$pdf->SetFont('helvetica','',9);
			$pdf->Print(38,14.5+($l*8),$p[$data[$l]]);
		}
		
		$pdf->Rotate(180,$pdf->X+42.5,$pdf->Y+82.5);
		$pdf->SetFont('helvetica','',11);
		$pdf->Print(4,106,'Drop In The Ocean');
		$pdf->Print(4,101,'In case of emergency');
		$pdf->SetFont('helvetica','B',11);
		$pdf->Print(39,106,'+30 694 6899518');
		$pdf->Print(43,101,'112');
		$pdf->SetFont('helvetica','',6.5);
		$pdf->SetXY($pdf->X+3,$pdf->Y+59);
		$pdf->MultiCell(80,3,$translate['bicycle-rules']);
		$pdf->Rotate(0);
	}

}

	
$pdf->Output('D','Bicycle Cards.pdf');

