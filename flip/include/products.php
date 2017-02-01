<?

	$table = $action;
	$ajax = checkajax();
	if(!DEFINED('FLIP')) include('flip.php');

	if(!$ajax) {

		initlist();

		$cmsmain->assign('title','Products');
		listsetting('search', array('name', 'g.label'));

		$data = getlistdata('SELECT products.*, sg.label AS sizegroup, g.label AS gender, CONCAT(products.value," drop coins") AS drops, COALESCE(SUM(s.items),0) AS items FROM '.$table.'
			LEFT OUTER JOIN genders AS g ON g.id = products.gender_id
			LEFT OUTER JOIN sizegroup AS sg ON sg.id = products.sizegroup_id
			LEFT OUTER JOIN stock AS s ON s.product_id = products.id AND NOT s.deleted AND NOT s.location_id = 4
			WHERE NOT products.deleted 
			GROUP BY products.id
		');


		addcolumn('text','Product name','name');
		addcolumn('text','Gender','gender');
		addcolumn('text','Sizegroup','sizegroup');
		addcolumn('text','Items','items');
		addcolumn('text','Price','drops');
		addcolumn('toggle','In container','stockincontainer',array('do'=>'togglecontainer'));

		listsetting('allowsort',true);
		listsetting('allowcopy',true);
		listsetting('add', 'Add a product');

		$cmsmain->assign('data',$data);
		$cmsmain->assign('listconfig',$listconfig);
		$cmsmain->assign('listdata',$listdata);
		$cmsmain->assign('include','cms_list.tpl');


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

		    case 'togglecontainer':
		    	list($success, $message, $redirect, $newvalue) = listSwitch($table, 'stockincontainer', $_POST['id']);
		        break;
		}

		$return = array("success" => $success, 'message'=> $message, 'redirect'=>$redirect, 'newvalue'=>$newvalue);

		echo json_encode($return);
		die();
	}