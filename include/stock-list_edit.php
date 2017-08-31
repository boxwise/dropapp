<?php

	$table = 'stock';
	$action = 'stock';
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');

	if(!$ajax) {

		

		initlist();

		list($product,$gender,$size) = explode('-',$_GET['id']);

		$listconfig['origin'] = $action.'&id='.$_GET['id'];

		$cmsmain->assign('title','Boxes for: '.db_value('SELECT name FROM products WHERE id = :id',array('id'=>$product)).', '.db_value('SELECT label FROM genders WHERE id = :id',array('id'=>$gender)).', '.db_value('SELECT label FROM sizes WHERE id = :id',array('id'=>$size)));

/*
		$statusarray = array('show'=>'Boxes in market are visible');
		if($_SESSION['filter']['stock-list']==$_SESSION['camp']['id']) listfilter2(array('label'=>'Boxes in market are hidden','options'=>$statusarray,'filter'=>'"show"'));
*/

		$data = getlistdata('SELECT stock.*, SUBSTRING(stock.comments,1, 25) AS shortcomment, g.label AS gender, p.name AS product, s.label AS size, l.label AS location, l.visible FROM '.$table.'
			LEFT OUTER JOIN products AS p ON p.id = stock.product_id
			LEFT OUTER JOIN locations AS l ON l.id = stock.location_id
			LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
			LEFT OUTER JOIN sizes AS s ON s.id = stock.size_id WHERE 1=1'.(!$_SESSION['filter2']['stock']?' AND l.id != 4':'').'
			AND p.id = '.intval($product).' AND g.id = '.intval($gender).($size?' AND s.id = '.intval($size):''));

		$data = getlistdata('
SELECT 
	stock.*, 
	SUBSTRING(stock.comments,1, 25) AS shortcomment, 
	g.label AS gender, 
	p.name AS product, 
	s.label AS size, 
	IF(l.camp_id = '.$_SESSION['camp']['id'].',l.label,c.name) AS location, 
	l.camp_id = '.$_SESSION['camp']['id'].' AS visible,
	l.camp_id != '.$_SESSION['camp']['id'].' AS preventdelete,
	l.camp_id != '.$_SESSION['camp']['id'].' AS preventedit
FROM 
	products AS p, 
	locations AS l, 
	genders AS g, 
	sizes AS s, 
	stock,
	camps AS c
WHERE 
	l.camp_id = c.id AND 
	stock.size_id = s.id AND 
	p.gender_id = g.id AND 
	stock.product_id = p.id AND 
	p.name = (SELECT name FROM products WHERE id = '.intval($product).') AND 
	p.gender_id = '.intval($gender).' '.
	($size?' AND s.id = '.intval($size):'').' AND 
	NOT stock.deleted AND 
	stock.location_id = l.id 
	AND l.visible');
		
		addcolumn('text','Box ID','box_id');
		addcolumn('text','Product','product');
		addcolumn('text','Gender','gender');
		addcolumn('text','Size','size');
		addcolumn('text','Comments','shortcomment');
		addcolumn('text','Items','items');
		addcolumn('text','Location','location');

		listsetting('allowsort',true);
		listsetting('allowadd',false);
		listsetting('allowselectall',false);
		listsetting('allowselect',false);
		#listsetting('allowedit',$_SESSION['filter']['stock-list']==$_SESSION['camp']['id']);

		#$locations = db_simplearray('SELECT id, label FROM locations ORDER BY seq');
		#addbutton('movebox','Move box',array('icon'=>'fa-arrows', 'options'=>$locations));
		#addbutton('qr','Make label',array('icon'=>'fa-print','oneitemonly'=>true));

		$cmsmain->assign('data',$data);
		$cmsmain->assign('listconfig',$listconfig);
		$cmsmain->assign('listdata',$listdata);
		$cmsmain->assign('include','cms_list.tpl');


	} else {
		switch ($_POST['do']) {
			case 'movebox':
				$box = db_row('SELECT * FROM stock WHERE id = :id',array('id'=>$id));

				$ids = explode(',',$_POST['ids']);
				foreach($ids as $id) {
					db_query('UPDATE stock SET location_id = :location WHERE id = :id',array('location'=>$_POST['option'],'id'=>$id));
					$count++;
				}

				if($box['location_id']!=$_POST['option']) {
					db_query('INSERT INTO itemsout (product_id, size_id, count, movedate, from_location, to_location) VALUES ('.$box['product_id'].','.$box['size_id'].','.$box['items'].',NOW(),'.$box['location_id'].','.$_POST['option'].')');						
				}

				$success = $count;
				$message = ($count==1?'1 box is':$count.' boxes are').' moved';
				$redirect = '?action='.$_GET['action'];
				break;
			case 'qr':
				$id = $_POST['ids'];
				$boxid = db_value('SELECT box_id FROM stock WHERE id = :id',array('id'=>$id));
				$success = true;
				$message = '';
				$redirect = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=http://'.$_SERVER['HTTP_HOST'].$settings['rootdir'].'/mobile.php?barcode='.$boxid;
				break;
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
