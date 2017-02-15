<?

	$table = $action;
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');
			
	
	if(!$ajax) {
		
		if(!$_SESSION['user']['is_admin']) trigger_error('Alleen voor ADMIN',E_USER_ERROR);
		initlist();
				
		$cmsmain->assign('title',$translate['cms_functions']);
		
		$data = getlistdata('SELECT *, IF(parent_id=0,0,1) AS editable FROM '.$table);

		if(db_fieldexists($table,'title_'.$lan)) {
			addcolumn('text','Functie','title_'.$lan);		
		} else {
			addcolumn('text','Functie','title');
		}
		addcolumn('text','Include','include');
			
		listsetting('allowselect',array(0=>true,1=>true));
		listsetting('allowmovefrom',0);
		listsetting('allowmoveto',1);
			
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