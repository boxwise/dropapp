<?

	$table = $action;
	$ajax = checkajax();
	if(!DEFINED('FLIP')) include('flip.php');
			
	$count = db_numrows('SELECT * FROM '.$table);
	if(db_fieldexists('cms_functions','alert')) db_query('UPDATE cms_functions SET alert = '.($count?1:0).' WHERE include = "brokenlinks"');

	
	if(!$ajax) {
		
		initlist();
				
		$cmsmain->assign('title',$translate['cms_brokenlinks_url']);

		$data = getlistdata('SELECT * FROM '.$table);

		addcolumn('text',$translate['cms_brokenlinks_url'],'link');
		addcolumn('text',$translate['cms_brokenlinks_location'],'location');
		
		listsetting('allowadd', false);
		
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
		    	list($success, $message, $redirect) = listCopy($table, $ids, 'code');
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

