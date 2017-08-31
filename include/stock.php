<?php

	$table = $action;
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');

	if(!$ajax) {

		initlist();

		$cmsmain->assign('title','Boxes');
		listsetting('search', array('box_id', 'l.label', 's.label', 'g.label', 'p.name','stock.comments'));


 		listfilter(array('label'=>'By location','query'=>'SELECT id, label FROM locations WHERE camp_id = '.$_SESSION['camp']['id'].' ORDER BY seq','filter'=>'l.id'));
		$statusarray = array('show'=>'All boxes');
		listfilter2(array('label'=>'Only active boxes','options'=>$statusarray,'filter'=>'"show"'));

		$data = getlistdata('SELECT stock.*, SUBSTRING(stock.comments,1, 25) AS shortcomment, g.label AS gender, p.name AS product, s.label AS size, l.label AS location, IF(DATEDIFF(now(),stock.modified) > 90,1,0) AS deprecatable FROM '.$table.'
			LEFT OUTER JOIN products AS p ON p.id = stock.product_id
			LEFT OUTER JOIN locations AS l ON l.id = stock.location_id
			LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
			LEFT OUTER JOIN sizes AS s ON s.id = stock.size_id '.(!$_SESSION['filter2']['stock']?' WHERE l.visible AND l.camp_id = '.$_SESSION['camp']['id'].'':''));
			
		foreach($data as $key=>$value) if($data[$key]['deprecatable']) $data[$key]['deprecatable'] = '<i class="fa fa-exclamation-triangle warning tooltip-this" title="This box hasn\'t been touched in 3 months or more and may be disposed"></i>'; else $data[$key]['deprecatable'] ='';

		addcolumn('text','Box ID','box_id');
		addcolumn('text','Product','product');
		addcolumn('text','Gender','gender');
		addcolumn('text','Size','size');
		addcolumn('text','Comments','shortcomment');
		addcolumn('text','Items','items');
		addcolumn('text','Location','location');
		addcolumn('html','>3mon','deprecatable');

		listsetting('allowsort',true);
		listsetting('allowcopy',false);
		listsetting('add', 'Add');

		$locations = db_simplearray('SELECT id, label FROM locations WHERE camp_id = '.$_SESSION['camp']['id'].' ORDER BY seq');
		addbutton('movebox','Move',array('icon'=>'fa-arrows', 'options'=>$locations));
		addbutton('qr','Make label',array('icon'=>'fa-print'));

		$cmsmain->assign('data',$data);
		$cmsmain->assign('listconfig',$listconfig);
		$cmsmain->assign('listdata',$listdata);
		$cmsmain->assign('include','cms_list.tpl');


	} else {
		switch ($_POST['do']) {
			case 'movebox':
				$ids = explode(',',$_POST['ids']);
				foreach($ids as $id) {
					db_query('UPDATE stock SET location_id = :location WHERE id = :id',array('location'=>$_POST['option'],'id'=>$id));
					simpleSaveChangeHistory('stock', $id, 'Box moved to '.db_value('SELECT label FROM locations WHERE id = :id',array('id'=>$_POST['option'])));
					$count++;
				}
				$success = $count;
				$message = ($count==1?'1 box is':$count.' boxes are').' moved';
				$redirect = '?action='.$_GET['action'];
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
