<?

	$table = $action;
	$ajax = checkajax();
	if(!DEFINED('CORE')) include('core.php');
			
	if(!$ajax) {
		
		initlist();
				
		$cmsmain->assign('title','Niet gevonden pagina\'s (404)');

		$data = getlistdata('SELECT *, count(id) AS count FROM '.$table.' WHERE referer != "" GROUP BY url, referer ORDER BY count(id) DESC LIMIT 500 ');
		
		listsetting('allowcopy', false);
		listsetting('allowedit', false);			
		listsetting('allowadd', false);			
		listsetting('sortlist','[[3,0]]');

		addcolumn('datetime','Datum','reqDate', array('width'=>400));
		addcolumn('text','URL','url');
		addcolumn('text','Bron','referer');
		addcolumn('text','Aantal','count');
			
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
		    	list($success, $message, $redirect) = listCopy($table, $ids, 'source');
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