<?php

	$table = $action;
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');

	if(!$ajax) {

		initlist();

		$cmsmain->assign('title','Boxes');
		listsetting('search', array('box_id', 'l.label', 's.label', 'g.label', 'p.name','stock.comments'));


 		listfilter(array('label'=>'By location','query'=>'SELECT id, label FROM locations WHERE camp_id = '.$_SESSION['camp']['id'].' ORDER BY seq','filter'=>'l.id'));
 		
		$statusarray = array('showall'=>'All boxes','ordered'=>'Ordered boxes','dispose'=>'Boxes to dispose');
		listfilter2(array('label'=>'Only active boxes','options'=>$statusarray,'filter'=>'"show"'));

		listsetting('manualquery',true);
		$data = getlistdata('SELECT stock.*, cu.naam AS ordered_name, cu2.naam AS picked_name, SUBSTRING(stock.comments,1, 25) AS shortcomment, g.label AS gender, p.name AS product, s.label AS size, l.label AS location, IF(DATEDIFF(now(),stock.modified) > 90,1,0) AS oldbox FROM '.$table.'
			LEFT OUTER JOIN cms_users AS cu ON cu.id = stock.ordered_by
			LEFT OUTER JOIN cms_users AS cu2 ON cu2.id = stock.picked_by
			LEFT OUTER JOIN products AS p ON p.id = stock.product_id
			LEFT OUTER JOIN locations AS l ON l.id = stock.location_id
			LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
			LEFT OUTER JOIN sizes AS s ON s.id = stock.size_id 
		WHERE l.camp_id = '.$_SESSION['camp']['id'].
		
		($listconfig['searchvalue']?' AND (box_id LIKE "%'.$listconfig['searchvalue'].'%" OR l.label LIKE "%'.$listconfig['searchvalue'].'%" OR s.label LIKE "%'.$listconfig['searchvalue'].'%" OR g.label LIKE "%'.$listconfig['searchvalue'].'%" OR p.name LIKE "%'.$listconfig['searchvalue'].'%" OR stock.comments LIKE "%'.$listconfig['searchvalue'].'%")':'').
		
		($_SESSION['filter2']['stock']=='ordered'?' AND (stock.ordered OR stock.picked) AND l.visible':($_SESSION['filter2']['stock']=='dispose'?' AND DATEDIFF(now(),stock.modified) > 90 AND l.visible':(!$_SESSION['filter2']['stock']?' AND l.visible':''))));
			
		foreach($data as $key=>$value) {
			if($data[$key]['oldbox']) {
				$data[$key]['oldbox'] = '<i class="fa fa-exclamation-triangle warning tooltip-this" title="This box hasn\'t been touched in 3 months or more and may be disposed"></i>'; 
			} else {
				$data[$key]['oldbox'] ='';
			}
			if($data[$key]['ordered']) $data[$key]['order'] = '<i class="fa fa-shopping-cart tooltip-this" title="This box is ordered for the market by '.$data[$key]['ordered_name'].' on '.strftime('%d-%m-%Y',strtotime($data[$key]['ordered'])).'"></i>';
			if($data[$key]['picked']) $data[$key]['order'] = '<i class="fa fa-truck green tooltip-this" title="This box is picked for the market by '.$data[$key]['picked_name'].' on '.strftime('%d-%m-%Y',strtotime($data[$key]['picked'])).'"></i>';
		}

		addcolumn('text','Box ID','box_id');
		addcolumn('text','Product','product');
		addcolumn('text','Gender','gender');
		addcolumn('text','Size','size');
		addcolumn('text','Comments','shortcomment');
		addcolumn('text','Items','items');
		addcolumn('text','Location','location');
		addcolumn('html','&nbsp;','oldbox');
		addcolumn('html','&nbsp;','order');

		listsetting('allowsort',true);
		listsetting('allowcopy',false);
		listsetting('add', 'Add');

		$locations = db_simplearray('SELECT id, label FROM locations WHERE camp_id = '.$_SESSION['camp']['id'].' ORDER BY seq');
		addbutton('movebox','Move',array('icon'=>'fa-arrows', 'options'=>$locations));
		addbutton('qr','Make label',array('icon'=>'fa-print'));
		addbutton('order','Order from warehouse',array('icon'=>'fa-shopping-cart'));
		addbutton('undo-order','Undo order',array('icon'=>'fa-undo'));

		$cmsmain->assign('data',$data);
		$cmsmain->assign('listconfig',$listconfig);
		$cmsmain->assign('listdata',$listdata);
		$cmsmain->assign('include','cms_list.tpl');


	} else {
		switch ($_POST['do']) {
			case 'movebox':
				$ids = explode(',',$_POST['ids']);
				foreach($ids as $id) {
					$box = db_row('SELECT * FROM stock WHERE id = :id',array('id'=>$id));

					db_query('UPDATE stock SET ordered = NULL, ordered_by = NULL, picked = NULL, picked_by = NULL, location_id = :location WHERE id = :id',array('location'=>$_POST['option'],'id'=>$id));
					simpleSaveChangeHistory('stock', $id, 'Box moved to '.db_value('SELECT label FROM locations WHERE id = :id',array('id'=>$_POST['option'])));
					
					if($box['location_id']!=$_POST['option']) {
						db_query('INSERT INTO itemsout (product_id, size_id, count, movedate, from_location, to_location) VALUES ('.$box['product_id'].','.$box['size_id'].','.$box['items'].',NOW(),'.$box['location_id'].','.$_POST['option'].')');						
					}
					
					$count++;
				}
				$success = $count;
				$message = ($count==1?'1 box is':$count.' boxes are').' moved';
				$redirect = '?action='.$_GET['action'];
				break;
			case 'order':
				$ids = explode(',',$_POST['ids']);
				foreach($ids as $id) {
					db_query('UPDATE stock SET ordered = NOW(), ordered_by = :user, picked = NULL, picked_by = NULL WHERE id = '.intval($id), array('user'=>$_SESSION['user']['id']));
					simpleSaveChangeHistory('stock', intval($id), 'Box ordered to warehouse ');
					$message = 'Boxes are marked as ordered for you!';
					$success = true;
					$redirect = true;
				}
				break;
			case 'undo-order':
				$ids = explode(',',$_POST['ids']);
				foreach($ids as $id) {
					db_query('UPDATE stock SET ordered = NULL, ordered_by = NULL, picked = NULL, picked_by = NULL  WHERE id = '.$id);
					simpleSaveChangeHistory('stock', $id, 'Box order made undone ');
					$message = 'Boxes are unmarked as ordered';
					$success = true;
					$redirect = true;
				}
				break;
			case 'qr':
				$id = $_POST['ids'];
				$redirect = '?action=qr&label='.$id;
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
