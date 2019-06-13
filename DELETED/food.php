<?php

	$table = $action;
	$ajax = checkajax();

	if(!$ajax) {

		initlist();

		$cmsmain->assign('title','Food products');
		listsetting('search', array('name', 'g.label'));

		$data = getlistdata('SELECT food.*, CONCAT(food.stock, " units") AS stock, u.label, CONCAT(food.package," ",u.label) AS packagesize, CONCAT(food.peradult," ",u.label) AS peradultsize, CONCAT(food.perchild," ",u.label) AS perchildsize FROM food LEFT OUTER JOIN units AS u ON u.id = food.unit_id');
		foreach($data as $key=>$d) {
			$units = 0;
			$unitsrounded = 0;
			$result = db_query('SELECT CEIL(((SELECT count(id) FROM people AS p2 WHERE visible AND camp_id = '.$_SESSION['camp']['id'].' AND NOT deleted AND p2.container = p.container AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 >= '.$_SESSION['camp']['adult-age'].')*'.$d['peradult'].' + ((SELECT count(id) FROM people AS p2 WHERE visible AND camp_id = '.$_SESSION['camp']['id'].' AND NOT deleted AND p2.container = p.container AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p2.date_of_birth)), "%Y")+0 < '.$_SESSION['camp']['adult-age'].')*'.$d['perchild'].'))/'.$d['package'].')*'.$d['package'].' AS rounded,

count(*) AS count

FROM people AS p WHERE visible AND camp_id = '.$_SESSION['camp']['id'].' AND NOT deleted GROUP BY container ORDER BY SUBSTRING(container, 1,1), SUBSTRING(container, 2, 10)*1');
			while($row = db_fetch($result)) {
				$unitsrounded += $row['rounded'];
			}
			$data[$key]['cost'] = '&euro;&nbsp;'.number_format($unitsrounded*$d['price']/$d['package'],2,',','.');
			if($d['visible']) $totalcost += intval($unitsrounded*$d['price']/$d['package']);
			$data[$key]['unitsrounded'] = ($unitsrounded/$d['package']).' units';
		}
		$totalcost = '&euro;&nbsp;'.number_format($totalcost,2,',','.');


		addcolumn('text','Product name','name');
		addcolumn('text','Package size','packagesize');
		addcolumn('text','Per adult','peradultsize');
		addcolumn('text','Per child','perchildsize');
 		addcolumn('text','Total units','unitsrounded');
 		addcolumn('text','Units in stock','stock');
 		addcolumn('text','Total cost','cost');

		listsetting('allowsort',true);
		listsetting('allowcopy',true);
		listsetting('add', 'Add a product');

		$cmsmain->assign('data',$data);
		$cmsmain->assign('listconfig',$listconfig);
		$cmsmain->assign('listdata',$listdata);
		$cmsmain->assign('include','cms_list.tpl');
		$cmsmain->assign('listfooter',array('','','','','','',$totalcost));


	} else {
		switch ($_POST['do']) {
		    case 'move':
				$ids = json_decode($_POST['ids']);
		    	list($success, $message, $redirect) = listMove($table, $ids);
		        break;

		    case 'delete':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listDelete($table, $ids);
		        break;

		    case 'copy':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listCopy($table, $ids, 'menutitle');

		        break;

		    case 'hide':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listShowHide($table, $ids, 0);
		        break;

		    case 'show':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listShowHide($table, $ids, 1);
		        break;
		}

		$return = array("success" => $success, 'message'=> $message, 'redirect'=>$redirect);

		echo json_encode($return);
		die();
	}
