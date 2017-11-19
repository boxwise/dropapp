<?php
define('PDFSCRIPT',true);
define('FPDF_FONTPATH','fonts/');

if($_GET['ids']) require('flip.php'); else require('flipsite.php');

require($_SERVER['DOCUMENT_ROOT'].'/site/lib/content.php');
require('fpdf.php');
require('fpdi.php');

setlocale (LC_ALL, '');
setlocale (LC_TIME, 'nl_NL');

ini_set('display_errors',1);
error_reporting(E_ALL);
#if(!$_SESSION['user']) die();

if($_SESSION['user'] && $_GET['ids']) {
	$idarray = explode(',',$_GET['ids']);
	foreach($idarray as $id) $ids[] = intval($id);
	$result = db_query('SELECT id FROM orders WHERE NOT deleted AND id IN ('.join(',',$ids).')');
} else {
	$result = db_query('SELECT id FROM orders WHERE NOT deleted AND hash = :hash',array('hash'=>$_GET['hash']));
}

if(!$result->rowCount()) die();

$pdf=new PDF();
$pdf->Open();
$pdf->SetMargins(30,30);
define('EURO',chr(128),TRUE);
$line = 4.6;
$pdf->line = $line;

$pdf->AddFont('dagny','','dagny.php');
$pdf->AddFont('dagny','B','dagnybold.php');

while($row = db_fetch($result)) {

	$order = db_row('SELECT * FROM orders AS o WHERE o.id = :id',array('id'=>$row['id']));
	$product = getProductData($order['products_id'],true);
	$owner = getOwnerData($product['owner_id']);
	$discount = getDiscountData($order['discount_id'],$order['price']);
  
  if ($order['p_artist_nobtw'] && $order['independent']) {
    $btw['rate'] = 0;
	} else {
    $btw = getBtwData($order['p_btw_id']);    
  }

	if($order['deleted']) die('Deze order bestaat niet meer');

	$pdf->AddPage();
	$pdf->setSourceFile('images/co-briefpapier.pdf');
	$tpl = $pdf->ImportPage(1);
	$pdf->useTemplate($tpl);

	$pdf->SetFont('dagny','',9);

	$pdf->Text(30,50+(0*$line),$order['company']);
	$pdf->Text(30,50+(1*$line),$order['firstname'].($order['inbetween']?' '.$order['inbetween']:'').' '.$order['lastname']);
	$pdf->Text(30,50+(2*$line),$order['street']);
	$pdf->Text(30,50+(3*$line),$order['postcode'].' '.$order['city']);

	$pdf->SetFont('dagny','B',12);
	$pdf->Text(30,93,'Factuur');

	$pdf->SetFont('dagny','',9);
	$pdf->Text(30,90+(2*$line),'Datum: '.trim(strftime('%e %B %Y',strtotime($order['created']))));
	$pdf->Text(30,90+(3*$line),'Ordernummer: '.$order['id']);
	$pdf->Text(30,90+(4*$line),'Factuurnummer: '.$order['invoice_number']);
	if ($order['p_productcode'] && $discount['code']) {
		$pdf->Text(30,90+(5*$line),'Productcode: '.$order['p_productcode']);
		$pdf->Text(30,90+(6*$line),'Kortingscode: '.$discount['code']);
	} else if ($order['p_productcode']) {
		$pdf->Text(30,90+(5*$line),'Productcode: '.$order['p_productcode']);
	} else if ($discount['code']) {
		$pdf->Text(30,90+(5*$line),'Kortingscode: '.$discount['code']);
	}
  
/*  if ($order['p_artist_nobtw'] && $order['independent']) {
		$pdf->Text(30,90+(7*$line), 'Kunstenaar vrijgesteld van BTW.');
  }*/

	$pdf->SetFont('dagny','B',12);
	$pdf->Text(30,130,$order['p_pagetitle']);

	$pdf->SetFont('dagny','',9);
	$pdf->Text(30,136,$order['p_dateinfo'].($order['p_dateinfo'] && $order['p_location']?', ':'').$order['p_location']);

	$pdf->SetFont('dagny','',9);
	if ($discount) {
    if ($order['p_artist_nobtw'] && $order['independent']) {
      $order['btw_discount'] = 0;
    } else {
      $order['btw_discount'] = (($order['price']-$discount['discount_sum']) * ($btw['rate']/100));
    }
    
		$pdf->TextRight(120,160,'Bedrag');
		$pdf->TextRight(120,165,'Korting');
    $pdf->TextRight(120,170,'BTW '.$btw['rate'].'%');
		$pdf->TextRight(120,180,'Totaal');
		$pdf->SetFont('dagny','',12);

		if ($order['paymentstatus']=='refunded') {
			$pdf->Text(130,160,EURO);
			$pdf->TextRight(150,160,'-'.number_format($order['price'],2,',','.'));
			$pdf->Text(130,165,EURO);
			$pdf->TextRight(150,165,'+'.number_format($discount['discount_sum'],2,',','.'));
			$pdf->Text(130,170,EURO);
			$pdf->TextRight(150,170,'-'.number_format($order['btw_discount'],2,',','.'));
			$pdf->Text(130,180,EURO);
			$pdf->TextRight(150,180,'-'.number_format(($order['price']-$discount['discount_sum'])+$order['btw_discount'],2,',','.'));
		} else {
			$pdf->Text(130,160,EURO);
			$pdf->TextRight(150,160,number_format($order['price'],2,',','.'));
			$pdf->Text(130,165,EURO);
			$pdf->TextRight(150,165,'-'.number_format($discount['discount_sum'],2,',','.'));
			$pdf->Text(130,170,EURO);
			$pdf->TextRight(150,170,number_format($order['btw_discount'],2,',','.'));
			$pdf->Text(130,180,EURO);
			$pdf->TextRight(150,180,number_format(($order['price']-$discount['discount_sum'])+$order['btw_discount'],2,',','.'));
		}
	} else {
    if ($order['p_artist_nobtw'] && $order['independent']) {
      $order['btw'] = 0;
    }
    
		$pdf->TextRight(120,160,'Bedrag');
		$pdf->TextRight(120,165,'BTW '.$btw['rate'].'%');
		$pdf->TextRight(120,175,'Totaal');
		$pdf->SetFont('dagny','',12);
		if ($order['paymentstatus']=='refunded') {
			$pdf->Text(130,160,EURO);
			$pdf->TextRight(150,160,'-'.number_format($order['price'],2,',','.'));
			$pdf->Text(130,165,EURO);
			$pdf->TextRight(150,165,'-'.number_format($order['btw'],2,',','.'));
			$pdf->Text(130,175,EURO);
			$pdf->TextRight(150,175,'-'.number_format($order['price']+$order['btw'],2,',','.'));
		} else {
			$pdf->Text(130,160,EURO);
			$pdf->TextRight(150,160,number_format($order['price'],2,',','.'));
			$pdf->Text(130,165,EURO);
			$pdf->TextRight(150,165,number_format($order['btw'],2,',','.'));
			$pdf->Text(130,175,EURO);
			$pdf->TextRight(150,175,number_format($order['price']+$order['btw'],2,',','.'));
		}
	}

	$pdf->SetFont('dagny','',9);
	$pd = explode("\n",$order['paymentdetails']);

  $details = array();
	foreach($pd as $d) {
		list($key,$value) = explode(': ',$d);
		$details[strtolower($key)] = $value;
	}

	if ($details['method']=='ideal') {
    $details['method'] = 'iDEAL';
  }

	if($order['paymentstatus']=='paid') {
		if($details['method']=='iDEAL') {
      $account = ', '.$details['consumeraccount'].' ('.$details['consumername'].')';
    } elseif ($details['method']=='creditcard') {
      $account = ', cardnumber ' . $details['cardnumber'] . ' (' . $details['cardholder'] . ')';
    } else {
      $account = 'Niet van toepassing';
    }
    
		$pdf->Text(30,200+(0*$line),'Betaald met '.$details['method'].$account);
	} else if ($order['paymentstatus']=='refunded') {
		$pdf->Text(30,200+(0*$line),'Deze order is gecrediteerd.');
	} else {
		$pdf->Text(30,200+(0*$line),'Deze order is nog niet betaald.');
	}
	$pdf->SetFont('dagny','B',9);
	if($details['mode']=='test') {
		$pdf->Text(30,200+(1*$line),'Testbetaling, geen bedrag ontvangen!');
	}
}

//independent, paymentstatus paid,, paymentdetails Method:
if(FLIP) {
	$pdf->Output('CO-Facturen.pdf','D');
} else {
	$pdf->Output('CO-Factuur-'.$order['id'].'.pdf','I');
}

/* no custom parts below? */

function utf8_decode_new($text) {
	$text = str_replace('€','EUR',$text);
	$text = str_replace(array("‘","’"),"'",$text);
	$text = utf8_decode($text);

	return $text;
}

