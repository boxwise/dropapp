<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

$_cmsdir = str_replace('/lib', '', str_replace("\n", '', `pwd`));
require('flip.php');
require('lib/PHPExcel.php');
require('lib/PHPExcel/Writer/Excel2007.php');

function Excel2() {
	$e = new PHPExcel();

  $ids = mysql_escape_string($_GET['ids']);
	$query = 'SELECT p.menutitle AS product, o.* FROM orders AS o, products AS p WHERE o.products_id = p.id AND NOT o.deleted AND o.id IN (' . $ids . ') ORDER BY id';

	$res = db_query($query);
	$fields = db_row($query);

	$count = $res->rowCount();

	$e->getProperties()->setCreator("Zinnebeeld FLIP");
	$e->getProperties()->setTitle('Cultuur+Ondernemen bestellingen '.strftime('%A %d %B %Y, %H:%M'));
	$e->setActiveSheetIndex(0);

  $removeKey = array('id', 'parent_id', 'invoice_number', 'products_id',
                     'p_pagetitle', 'p_dateinfo', 'p_location', 'p_btw_id', 
                     'p_artist_nobtw', 'quantity', 'deleted', 'seq', 'hash',
                     'p_productcode', 'price', 'btw', 'amount_paid', 
                     'paymentstatus', 'paymenttest', 'paymentdetails',
                     'discount_id', 'independent', 'progressalert',
                     'ip', 'created_by', 'modified', 'modified_by');
  
	$i = 0;
	foreach($fields as $field=>$void) {
		$columns[$i] = $field;
		$e->getActiveSheet()->setCellValueByColumnAndRow($i,1,$field);
		$meta = $res->getColumnMeta($i);
		$types[$i] = $meta['native_type'];
		$i++;
	}
	$fields = array_keys($fields);

  $countColumns = count($fields);
  $lastColumn = $countColumns > 26 ? ($countColumns%26 > 0 ? chr(floor($countColumns/26) +64) : chr(floor($countColumns/26) -1 +64)) . chr(($countColumns%26 > 0 ? $countColumns%26 : 26) +64) : chr($countColumns +64);
  
	$style = array('font' => array('bold' => true));
	$e->getActiveSheet()->getStyle('A1:'. $lastColumn .'1')->applyFromArray($style);

	$j=2;
	while($r = db_fetch($res)){
		$j++;
		$i=0;
		$iMod=0;
		$char = false;
		foreach($r as $value){
		  $iMod = $i % 26;
			//Works as long as the column name contains two characters (AA, AB, etc.)
			if ($i>25) {
				$char = chr(63 + ceil(($i+1)/26));
			}
			$cell = ($char ? $char : '') . chr(65+$iMod) . $j;
			$e->getActiveSheet()->setCellValueExplicit($cell,$value,(in_array($types[$i],array('VAR_STRING','BLOB'))?PHPExcel_Cell_DataType::TYPE_STRING:PHPExcel_Cell_DataType::TYPE_NUMERIC));

			$i++;
		}
	}

	foreach(range('A',chr($count+65)) as $c) {
	    $e->getActiveSheet()->getColumnDimension($c)->setAutoSize(true);
	}

  header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Bestellingen.xlsx"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($e, 'Excel2007');
	$tmp = sys_get_temp_dir().'/'.md5(time());
	$objWriter->save($tmp);
	readfile($tmp);
	unlink($tmp);

}


Excel2();

