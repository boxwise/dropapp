<?php
require('../library/core.php');

ini_set('display_errors',1);
error_reporting(E_ALL);

require('pdf.php');

define('PDFSCRIPT',true);
define('FPDF_FONTPATH','fonts/');

$pdf=new PDF();

$pdf->AddFont('helvetica','','helvetica.php');
$pdf->AddFont('helvetica','B','helveticab.php');
$pdf->AddFont('helvetica','I','helveticai.php');
$pdf->AddFont('helvetica','BI','helveticabi.php');

$pdf->SetFont('helvetica','',10);
$pdf->LeftMargin = 20; 
$pdf->Lineheight = 4.5;
$pdf->TopMargin = 20; 
$pdf->Column = 85;
$pdf->SetAutoPageBreak(false);

$result = db_query('
	SELECT id, people.container, COUNT(*) AS number, SUM(extraportion)+SUM(notregistered) AS extra, 
		SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < '.$settings['adult-age'].', 0, 1)) AS adults, 
		SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < 3, 1, 0)) AS baby, 
		SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < '.$settings['adult-age'].' AND NOT (DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) <3, 1, 0)) AS children
	FROM people 
	WHERE visible AND camp_id = '.$_SESSION['camp']['id'].' AND NOT deleted 
	GROUP BY container 
	ORDER BY SUBSTRING(REPLACE(container,"PK","Z"),1,1), SUBSTRING(REPLACE(container,"PK","Z"), 2, 10)*1, seq');

while($container = db_fetch($result)) {
	
	$letter = strtoupper(substr($container['container'],0,1));
	if($letter!=$oldletter) $pdf->newPage($container['container']);
	if($pdf->Y+(5*$container['number']) > 275) {
		$pdf->NewColumn($container['container']);
	}
	if(!$pdf->haspage) $pdf->NewColumn('A');
	$pdf->SetFont('helvetica','B',10);
	$pdf->Print($container['container']);
	$pdf->Print($container['number'].' people ('.($container['adults']?$container['adults'].' adults':'').($container['children']?', '.($container['children']+$container['baby']).' children':'').')',15);
	$pdf->NewLine();
	$pdf->Line($pdf->X, $pdf->Y-2.5, $pdf->X+$pdf->Column-$pdf->Lineheight, $pdf->Y-2.5);
	$pdf->Y+=2;
	
	$result2 = db_query('SELECT p.parent_id, p.id, CONCAT(TRIM(p.lastname),", ",TRIM(p.firstname)) AS name, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age, IF(gender="M","male","female") AS gender, (notregistered+extraportion) AS extra FROM people AS p WHERE visible AND camp_id = '.$_SESSION['camp']['id'].' AND NOT deleted AND container = "'.$container['container'].'" AND parent_id = 0 ORDER BY parent_id, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 DESC');
	while($person = db_fetch($result2)) {
		Writename($person);
		$result3 = db_query('SELECT p.parent_id, CONCAT(TRIM(p.lastname),", ",TRIM(p.firstname)) AS name, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age, IF(gender="M","male","female") AS gender, (notregistered+extraportion) AS extra FROM people AS p WHERE visible AND camp_id = '.$_SESSION['camp']['id'].' AND NOT deleted AND container = "'.$container['container'].'" AND parent_id = '.intval($person['id']).' ORDER BY parent_id, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 DESC');
		while($person2 = db_fetch($result3)) {
			Writename($person2);
		}

	}
	$pdf->Line($pdf->X, $pdf->Y-2.5, $pdf->X+$pdf->Column-$pdf->Lineheight, $pdf->Y-2.5);
	$pdf->Y+=2;
	$oldletter = $letter;	
}

	
$pdf->Output('I','Distribution List.pdf');

function Writename($person) {
	global $pdf;
	if(is_null($person['age'])) $person['age'] = '?';
	$pdf->SetFont('helvetica',($person['parent_id']?'':'B'),10);
	
	$parent = ($person['parent_id']?4:0);
	
	$pdf->SetXY($pdf->X-1+$parent,$pdf->Y-3);
	$w = $pdf->Column-28;
	if($person['extra']) $w-=10;
	if($person['age']<2) $w-=11;

	$pdf->CellFit($w-$parent,4,$person['name'],0,0,'L',false,'',true,false);
	if($person['extra'] && $_GET['double']) {
		$pdf->SetXY($pdf->X+$pdf->Column-39,$pdf->Y-3);
		$pdf->SetFont('helvetica','',7);
		$pdf->SetFillColor(240,240,240);
		$pdf->Cell(10,3.5,'Double',1,0,'C',1);
	}
	if($person['age']!='?' && $person['age']<3 && $_GET['diapers']) {
		$pdf->SetXY($person['extra']?$pdf->X+$pdf->Column-56:$pdf->X+$pdf->Column-39,$pdf->Y-3);
		$pdf->SetFont('helvetica','',7);
		$pdf->SetFillColor(240,240,240);
		$pdf->Cell(10,3.5,'Diapers',1,0,'C',1);
	}
	$pdf->SetFont('helvetica','',10);
	$pdf->Print($person['age'],$pdf->Column-27);
	$pdf->Print($person['gender'],$pdf->Column-18);
	$pdf->NewLine();
}