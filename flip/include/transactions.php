<?

	$table = $action;
	$ajax = checkajax();
	if(!DEFINED('FLIP')) include('flip.php');

	if(!$ajax) {

		initlist();

		$cmsmain->assign('title','Families');
		listsetting('search', array('name'));

		$data = getlistdata('SELECT f.*, CONCAT(IF(adults,n1.label,"No")," adults and ",IF(children,n2.label,"no")," children") AS contains, CONCAT(SUM(drops)," drops") AS dropcount 
		FROM '.$table.' as f 
		LEFT OUTER JOIN numbers AS n1 ON n1.value = f.adults 
		LEFT OUTER JOIN numbers AS n2 ON n2.value = f.children
		LEFT OUTER JOIN transactions AS t ON t.family_id = f.id
		GROUP BY f.id');

		addcolumn('text','Family name','name');
		addcolumn('text','Members','contains');
		addcolumn('text','Credits','dropcount');

/*
		listsetting('allowmovefrom', ($_SESSION['user']['is_admin']?0:1));
		listsetting('allowselect', array(($_SESSION['user']['is_admin']?true:false),true,true));
		listsetting('allowedit', array(($_SESSION['user']['is_admin']?true:false),true,true));
		listsetting('allowcopy',array(false,true));
*/


		#listsetting('allowsort', false);
		listsetting('allowcopy',true);
		listsetting('add', 'Add a family');

		addbutton('give','Give drops',array('icon'=>'fa-copy','oneitemonly'=>false));

		$cmsmain->assign('data',$data);
		$cmsmain->assign('listconfig',$listconfig);
		$cmsmain->assign('listdata',$listdata);
		$cmsmain->assign('include','cms_list.tpl');


	} else {
		switch ($_POST['do']) {
		    case 'give':
				$ids = ($_POST['ids']);
				$success = true;
				$redirect = '?action=give&ids='.$ids;
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
		    	list($success, $message, $redirect) = listCopy($table, $ids, 'name');
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

		$return = array("success" => $success, 'message'=> $message, 'redirect'=>true);

		echo json_encode($return);
		die();
	}