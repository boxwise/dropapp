<?
/*
$e = new PHPExcel();
	$e->getProperties()->setCreator($_SESSION['organisation']['label']);
	$e->getProperties()->setTitle('Boxes export '.strftime('%A %d %B %Y, %H:%M'));
	$e->setActiveSheetIndex(0);

	$keys = array('box_id'=>'Box number', 'product'=>'Product', 'gender'=>'Gender', 'size'=>'Size', 'location'=>'Location');
	
	$i=0;
	foreach($keys as $key=>$name) {
		$columns[$i] = $name;
		$e->getActiveSheet()->setCellValueByColumnAndRow($i,1,$name);
		$i++;
	}

		header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Bestellingen.xlsx"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($e, 'Excel2007');
	$tmp = sys_get_temp_dir().'/'.md5(time());
	$objWriter->save($tmp);
	readfile($tmp);
	unlink($tmp);
*/

    

	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename="Stock..csv"');
	header("Pragma: no-cache");

	$ajax = checkajax();
    /*
    $data = getlistdata('SELECT stock.*, cu.naam AS ordered_name, cu2.naam AS picked_name, SUBSTRING(stock.comments,1, 25) AS shortcomment, g.label AS gender, p.name AS product, s.label AS size, l.label AS location, IF(DATEDIFF(now(),stock.modified) > 90,1,0) AS oldbox FROM '.$table.'
			LEFT OUTER JOIN cms_users AS cu ON cu.id = stock.ordered_by
			LEFT OUTER JOIN cms_users AS cu2 ON cu2.id = stock.picked_by
			LEFT OUTER JOIN products AS p ON p.id = stock.product_id
			LEFT OUTER JOIN locations AS l ON l.id = stock.location_id
			LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
			LEFT OUTER JOIN sizes AS s ON s.id = stock.size_id 
		WHERE l.camp_id = '.$_SESSION['camp']['id'].
		
		($listconfig['searchvalue']?' AND (box_id LIKE "%'.$listconfig['searchvalue'].'%" OR l.label LIKE "%'.$listconfig['searchvalue'].'%" OR s.label LIKE "%'.$listconfig['searchvalue'].'%" OR g.label LIKE "%'.$listconfig['searchvalue'].'%" OR p.name LIKE "%'.$listconfig['searchvalue'].'%" OR stock.comments LIKE "%'.$listconfig['searchvalue'].'%")':'').
		
		($_SESSION['filter2']['stock']=='ordered'?' AND (stock.ordered OR stock.picked) AND l.visible':($_SESSION['filter2']['stock']=='dispose'?' AND DATEDIFF(now(),stock.modified) > 90 AND l.visible':(!$_SESSION['filter2']['stock']?' AND l.visible':''))).
		
		($_SESSION['filter3']['stock']?' AND (p.gender_id = '.intval($_SESSION['filter3']['stock']).')':'').

        ($_SESSION['filter']['stock']?' AND (stock.location_id = '.$_SESSION['filter']['stock'].')':''));
*/
    $data = db_query('SELECT stock.*, cu.naam AS ordered_name, cu2.naam AS picked_name, SUBSTRING(stock.comments,1, 25) AS shortcomment, g.label AS gender, p.name AS product, s.label AS size, l.label AS location, IF(DATEDIFF(now(),stock.modified) > 90,1,0) AS oldbox FROM stock
    LEFT OUTER JOIN cms_users AS cu ON cu.id = stock.ordered_by
    LEFT OUTER JOIN cms_users AS cu2 ON cu2.id = stock.picked_by
    LEFT OUTER JOIN products AS p ON p.id = stock.product_id
    LEFT OUTER JOIN locations AS l ON l.id = stock.location_id
    LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
    LEFT OUTER JOIN sizes AS s ON s.id = stock.size_id ');

    $data = db_query('SELECT stock.*')

	echo '"Box number";"Product";"Gender";"Gender";"Size";"Location"'."\n";
	
	while($f = db_fetch($data)) {
		echo '"'.$f['container'].'";"'.$f['firstname'].'";"'.$f['lastname'].'";"'.$f['gender'].'";"'.$f['age'].'";"'.$f['languages'].'"'."\n";
		
		$result2 = db_query('SELECT *, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 AS age, GROUP_CONCAT(l.name SEPARATOR ", ") AS languages 
		FROM people AS p
		LEFT OUTER JOIN x_people_languages AS x ON x.people_id = p.id LEFT OUTER JOIN languages AS l ON l.id = x.language_id 
		WHERE camp_id = :campid AND NOT deleted AND parent_id = :parent_id 
		GROUP BY p.id
		ORDER BY date_of_birth DESC',array('campid'=>$_SESSION['camp']['id'],'parent_id'=>$f['id']));
		while($s = db_fetch($result2)) {
			echo '"";"'.$s['firstname'].'";"'.$s['lastname'].'";"'.$s['gender'].'";"'.$f['age'].'";"'.$f['languages'].'"'."\n";
		}
	}
    die();
    
/*
	$keys = array('box_id'=>'Box number', 'product'=>'Product', 'gender'=>'Gender', 'size'=>'Size', 'location'=>'Location');
	
	$i=0;
	foreach($keys as $key=>$name) {
		$columns[$i] = $name;
		$e->getActiveSheet()->setCellValueByColumnAndRow($i,1,$name);
		$i++;
	}

		header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Bestellingen.xlsx"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($e, 'Excel2007');
	$tmp = sys_get_temp_dir().'/'.md5(time());
	$objWriter->save($tmp);
	readfile($tmp);
	unlink($tmp);
*/