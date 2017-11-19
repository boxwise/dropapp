<?php
require('../library/core.php');

ini_set('display_errors',1);
error_reporting(E_ALL);

require('pdf.php');

define('PDFSCRIPT',true);
define('FPDF_FONTPATH','fonts/');

$title = $_GET['title'];

$pdf=new PDF();

$pdf->AddFont('helvetica','','helvetica.php');
$pdf->AddFont('helvetica','B','helveticab.php');
$pdf->AddFont('helvetica','I','helveticai.php');
$pdf->AddFont('helvetica','BI','helveticabi.php');

$pdf->SetFont('helvetica','',10);
$pdf->LeftMargin = 10; 
$pdf->TopMargin = 10; 
$pdf->Column = 95;
$pdf->SetAutoPageBreak(false);

$result = db_query('
	SELECT id, people.container, COUNT(*) AS number, SUM(extraportion) AS extra, 
		SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) >= '.$settings['adult-age'].', 1, 0)) AS adults, 
		SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < 2, 1, 0)) AS baby, 
		SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) >= '.$settings['adult-age'].' OR (DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) <= 2, 0, 1)) AS children
	FROM people 
	WHERE visible AND camp_id = '.$_SESSION['camp']['id'].' AND NOT deleted 
	GROUP BY container 
	ORDER BY SUBSTRING(REPLACE(container,"PK","Z"),1,1), SUBSTRING(REPLACE(container,"PK","Z"), 2, 10)*1');


while($container = db_fetch($result)) {
	$letter = strtoupper(substr($container['container'],0,1));
	if($letter!=$oldletter) $pdf->newPage();
	if($pdf->Y+(5*$container['number']) > 275) {
		$pdf->NewColumn();
	}
	$pdf->SetFont('helvetica','B',10);
	$pdf->Print($container['container']);
	$pdf->Print($container['number'].' people ('.($container['adults']?$container['adults'].' adults':'').($container['children']?', '.$container['children'].' children':'').($container['baby']?', '.$container['baby'].' babies':'').')',15);
	$pdf->NewLine();
	
	$result2 = db_query('SELECT p.parent_id, CONCAT(TRIM(p.lastname),", ",TRIM(p.firstname)) AS name, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age, IF(gender="M","male","female") AS gender, extraportion AS extra FROM people AS p WHERE visible AND camp_id = '.$_SESSION['camp']['id'].' AND NOT deleted AND container = "'.$container['container'].'" ORDER BY parent_id, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 DESC');
	while($person = db_fetch($result2)) {
		if(!$person['age']) $person['age'] = '?';
		$pdf->SetFont('helvetica',($person['parent_id']?'':'B'),10);
		
		$pdf->SetXY($pdf->X-1,$pdf->Y-3);
		$w = 67;
		if($title!='bread') {
			if($person['extra']) $w-=16;
			if($person['age']<2) $w-=11;
		}
		$pdf->CellFit($w,4,$person['name'],0,0,'L',false,'',true,false);
		if($person['extra'] && $title!='bread') {
			$pdf->SetXY($pdf->X+50,$pdf->Y-3);
			$pdf->SetFont('helvetica','',7);
			$pdf->SetFillColor(240,240,240);
			$pdf->Cell(16,3.5,'Extra portion',1,0,'C',1);
		}
		if($person['age']&&$person['age']<2 && $title!='bread') {
			$pdf->SetXY($person['extra']?$pdf->X+39:$pdf->X+56,$pdf->Y-3);
			$pdf->SetFont('helvetica','',7);
			$pdf->SetFillColor(240,240,240);
			$pdf->Cell(10,3.5,'Diapers',1,0,'C',1);
		}
		$pdf->SetFont('helvetica','',10);
		$pdf->Print($person['age'],67);
		$pdf->Print($person['gender'],77);
		$pdf->NewLine();
	}
	$pdf->Line($pdf->X, $pdf->Y-2.5, $pdf->X+$pdf->Column-5, $pdf->Y-2.5);
	$pdf->Y+=3;
	$oldletter = $letter;	
}

	
$pdf->Output('I');

