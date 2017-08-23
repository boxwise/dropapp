<?php
	
	error_reporting(E_ALL^E_NOTICE);
	ini_set('display_errors',1);
	
	//die('Please use the online CMS!');
	require_once($_SERVER["DOCUMENT_ROOT"].'/library/core.php');

	error_reporting(E_ALL);
	ini_set('display_errors',true);

	if(!DEFINED('CORE')) {
		trigger_error('Core is not available - probably the library folder is not in the include_path');
		die();
	}

	date_default_timezone_set('Europe/Athens');
	db_query('SET time_zone = "+02:00"');
	
	# action set by POST will override GET
	$action = (isset($_POST['action'])?$_POST['action']:(isset($_GET['action'])?$_GET['action']:'start'));
	$id = intval($_GET['id']);

	if ($action == 'logout') logout();

	// dailyroutine is performed when the last action of any user is not of today
	
	$dailyroutine = db_value('SELECT IF(DATEDIFF(NOW(), lastaction)>0,1,0), lastaction FROM cms_users ORDER BY lastaction DESC LIMIT 1');
	
	if($dailyroutine) {
		$result = db_query('SELECT id, parent_id, people.container FROM people WHERE NOT deleted AND parent_id = 0 ORDER BY IF(container="AAA1",1,0), IF(container="?",1,0), SUBSTRING(container, 1,1), SUBSTRING(container, 2, 10)*1');
	
		while($row = db_fetch($result)) {
			$i++;
			db_query('UPDATE people SET seq = '.$i.' WHERE id = '.$row['id']);
	
			$j=0;
	
			$result2 = db_query('SELECT id FROM people WHERE NOT deleted AND parent_id = '.$row['id'].' ORDER BY DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 DESC');
			while($row2 = db_fetch($result2)) {
				$j++;
				db_query('UPDATE people SET seq = '.$j.' WHERE id = '.$row2['id']);
			}
		}	
	}


	$cmsmain = new Zmarty;
	
	/* new: fill the camp selection menu -------------------------------------------- */
	if($_GET['camp']) {
		if($_SESSION['user']['is_admin']) {
			$_SESSION['camp'] = db_row('SELECT c.* FROM camps AS c WHERE c.id = :camp',array('camp'=>$_GET['camp']));
		} else {
			$_SESSION['camp'] = db_row('SELECT c.* FROM camps AS c, cms_users_camps AS x WHERE c.id = x.camps_id AND c.id = :camp AND x.cms_users_id = :id',array('camp'=>$_GET['camp'], 'id'=>$_SESSION['user']['id']));
		}
	}
	if($_SESSION['user']['is_admin']) {
		$camplist = db_array('SELECT c.* FROM camps AS c');
	} else {
		$camplist = db_array('SELECT c.* FROM camps AS c, cms_users_camps AS x WHERE x.camps_id = c.id AND x.cms_users_id = :id',array('id'=>$_SESSION['user']['id']));
	}
	if(!isset($_SESSION['camp'])) $_SESSION['camp'] = $camplist[0];
	$cmsmain->assign('camps',$camplist);
	$cmsmain->assign('currentcamp',$_SESSION['camp']);
	$cmsmain->assign('campaction',strpos($action,'_edit')?substr($action,0,-5):$action);
	/* end of the camp menu addition -------------------------------------------- */
	
	
	$cmsmain->assign('menu',CMSmenu());

	$allowed = db_numrows('SELECT id FROM cms_functions AS f LEFT OUTER JOIN cms_access AS x ON x.cms_functions_id = f.id WHERE (x.cms_users_id = :user_id OR f.allusers) AND (f.include = :action OR CONCAT(f.include,"_edit") = :action OR CONCAT(f.include,"_trash") = :action)',array('user_id'=>$_SESSION['user']['id'], 'action'=>$action));
	#$allowed = true;
	
	$allowedincamp = db_numrows('SELECT id FROM cms_functions AS f LEFT OUTER JOIN cms_functions_camps AS x ON x.cms_functions_id = f.id WHERE (x.camps_id = :camp_id OR f.allusers) AND (f.include = :action OR CONCAT(f.include,"_edit") = :action OR CONCAT(f.include,"_trash") = :action)',array('camp_id'=>$_SESSION['camp']['id'], 'action'=>$action));

	if  (($allowed&&$allowedincamp) || $_SESSION['user']['is_admin']) {
		@include('include/'.$action.'.php');
	}


	$cmsmain->assign('action',$action);
	$cmsmain->display('cms_index.tpl');

