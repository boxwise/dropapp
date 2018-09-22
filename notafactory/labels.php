<?php
	require('../library/core.php');
	
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	
	require('pdf-card.php');
	
	define('PDFSCRIPT',true);
	define('FPDF_FONTPATH','fonts/');

	$pdf=new PDF();
	
	$pdf->AddFont('ubuntu','','Ubuntu-R.php');
	
	$pdf->SetFont('ubuntu','',10);
	$pdf->LeftMargin = 15; 
	$pdf->TopMargin = 15; 
	$pdf->W = 85; 
	$pdf->H = 55; 
	$pdf->SetAutoPageBreak(false);
	$pdf->SetLineWidth(0.1);
	
	$result = db_query('SELECT n.*, c.full AS country FROM notafactory AS n LEFT OUTER JOIN notafactory_countries AS c ON n.`Shipping Address Country` = c.code WHERE `Line Item Product ID` NOT IN (51) GROUP BY `Order ID` ORDER BY `Shipping Address Country`,`Shipping Address Postcode`');
	while($row = db_fetch($result)) {
		$products = db_query('SELECT `Line Item Quantity` AS count, `Line Item Meta` AS meta FROM notafactory WHERE `Order ID` = :id AND `Line Item Product ID` NOT IN (51)',array('id'=>$row['Order ID']));
		$orderline = array();
		while($p = db_fetch($products)) {
			$p['meta'] = str_replace("pa_",'',$p['meta']);
			$p['meta'] = str_replace(" ",'',strtoupper($p['meta']));
			$p['meta'] = str_replace("\n",'',$p['meta']);
			$p['meta'] = str_ireplace('size:','',$p['meta']);
			$p['meta'] = trim(str_ireplace('color:','/',$p['meta']));
			$p['meta'] = str_ireplace('Lightblue','blue',$p['meta']);
			$p['meta'] = str_ireplace('light-blue','blue',$p['meta']);
			$p['meta'] = str_ireplace('Lightgrey','grey',$p['meta']);
			$p['meta'] = str_ireplace('light-grey','grey',$p['meta']);
			$p['meta'] = str_ireplace('White','white',$p['meta']);
			$orderline[] = $p['count'].'*'.$p['meta'];
		}		

		$orderline1 = array_slice($orderline,0,5);
		$orderline2 = array_slice($orderline,5,5);
		$orderline3 = array_slice($orderline,10,5);
		
		$l = array();
		$ls = array();

		if($orderline1) $ls[] = $row['Order ID'].' '.join(' ',$orderline1);
		if($orderline2) $ls[] = join(' ',$orderline2);
		if($orderline3) $ls[] = join(' ',$orderline3);
		
		if($row['Shipping Address Country']=='NL') {
			$row['Shipping Address Postcode'] = strtoupper(str_replace(' ','',$row['Shipping Address Postcode']));
			$row['Shipping Address Postcode'] = substr($row['Shipping Address Postcode'],0,4).' '.substr($row['Shipping Address Postcode'],4,2);
		}
		
		if($row['Shipping Address Company']) $l[] = ucfirst($row['Shipping Address Company']);
		$l[] = ucfirst($row['Shipping Address First Name']).' '.ucfirst($row['Shipping Address Last Name']);
		if($row['Shipping Address Address 1']) $l[] = ucfirst($row['Shipping Address Address 1']);
		if($row['Shipping Address Address 2']) $l[] = ucfirst($row['Shipping Address Address 2']);
		$l[] = strtoupper($row['Shipping Address Postcode']).($row['Shipping Address Postcode']?'   ':'').ucfirst($row['Shipping Address City']).($row['Shipping Address State']?' '.$row['Shipping Address State']:'');

		if($row['country']) {
			$l[] = $row['country']; 
		} else { 
			$l[] = $row['Shipping Address Country'];
		}
		
		$label[] = $l;
		$smalllabel[] = $ls;

	}
	
	$top = 13.1;
	$left = 8;
	$h = 67.7;
	$w = 97;
	$spacing = 0;
	
	for($i=0;$i<count($label);$i++) {
		$l = $label[$i];
		$ls = $smalllabel[$i];
		
		if(!($i%8)) {
			$pdf->AddPage();
			$y = $top;
		} else {
			$y = $top + (floor($i/2)%4) * $h;
		}
		$x = $left + (($i%2)*($w+$spacing));

		$pdf->Image('notafactory.png', $x+4, $y+4, 30, 18.5, 'png');

		$pdf->SetFont('ubuntu','',6.5);
		$pdf->SetTextColor(0);
		$pdf->Text($x+$w-58,$y+6,'Return address:');
		#$pdf->Text($x+40,$y+9,'Walenburgerweg 88-a, 3021 NB  Rotterdam, Netherlands');
		$pdf->Text($x+$w-58,$y+9,'Kadijkselaan 13, 2861 CG  Bergambacht, Netherlands');
		$pdf->SetFont('ubuntu','',10);
	
		$pdf->Rect($x,$y,$w, $h);
		
		$l = array_reverse($l);
		$ls = array_reverse($ls);
		$ypos = $y+$h-8;
		foreach($l as $key=>$line) {
			$pdf->Text($x+15,$ypos,utf8_decode($line));
			$ypos -= 4.5;
		}
		
		$ypos -= 2;
		$pdf->SetTextColor(127,127,127);
		$pdf->SetFont('ubuntu','',6.5);
		
		foreach($ls as $key=>$line) {
			$pdf->Text($x+15,$ypos,utf8_decode($line));
			$ypos -= 3;
		}
		
	}
	
	$pdf->Output('I');
