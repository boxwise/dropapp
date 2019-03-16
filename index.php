<?php
	
	error_reporting(E_ALL^E_NOTICE);
	ini_set('display_errors',1);
	
	//die('Please use the online CMS!');
	require_once('library/core.php');

	if(!DEFINED('CORE')) {
		trigger_error('Core is not available - probably the library folder is not in the include_path');
		die();
	}

	date_default_timezone_set('Europe/Athens');
	db_query('SET time_zone = "+'.(date('Z')/3600).':00"');
	
	# action set by POST will override GET
	$action = (isset($_POST['action'])?$_POST['action']:(isset($_GET['action'])?$_GET['action']:'start'));
	$id = intval($_GET['id']);

	if ($action == 'logout') logout();

	// dailyroutine is performed when the last action of any user is not of today
	if(strftime('%Y-%m-%d')!=$settings['dailyroutine'] || $action == 'dailyroutine') {
		db_query('UPDATE settings SET value = "'.strftime('%Y-%m-%d').'" WHERE code = "dailyroutine"');
	 	require('dailyroutine.php');
	}


	$cmsmain = new Zmarty;
	
	/* new: fill the camp selection menu -------------------------------------------- */
	if($_GET['camp']) {
		if($_SESSION['user']['is_admin']) {
			$_SESSION['camp'] = db_row('SELECT c.* FROM camps AS c WHERE c.id = :camp ORDER BY c.seq',array('camp'=>$_GET['camp']));
		} else {
			$_SESSION['camp'] = db_row('SELECT c.* FROM camps AS c, cms_users_camps AS x WHERE c.id = x.camps_id AND c.id = :camp AND x.cms_users_id = :id ORDER BY c.seq',array('camp'=>$_GET['camp'], 'id'=>$_SESSION['user']['id']));
		}
	}
	if($_SESSION['user']['is_admin']) {
		$camplist = db_array('SELECT c.* FROM camps AS c ORDER BY c.seq');
	} else {
		$camplist = db_array('SELECT c.* FROM camps AS c, cms_users_camps AS x WHERE x.camps_id = c.id AND x.cms_users_id = :id ORDER BY c.seq',array('id'=>$_SESSION['user']['id']));
	}
	if(!isset($_SESSION['camp'])) $_SESSION['camp'] = $camplist[0];
	$cmsmain->assign('camps',$camplist);
	$cmsmain->assign('currentcamp',$_SESSION['camp']);
	$cmsmain->assign('campaction',strpos($action,'_edit')?substr($action,0,-5):$action);
	/* end of the camp menu addition -------------------------------------------- */

	$images['favicon16'] = $settings['rootdir']. (file_exists("uploads/favicon-16x16.png") ? '/uploads/favicon-16x16.png' : '/assets/img/favicon-16x16.png');
	$images['favicon32'] = $settings['rootdir']. (file_exists("uploads/favicon-32x32.png") ? '/uploads/favicon-32x32.png' : '/assets/img/favicon-32x32.png');
	$images['faviconapple'] = $settings['rootdir']. (file_exists("uploads/apple-touch-icon.png") ? '/uploads/apple-touch-icon.png' : '/assets/img/apple-touch-icon.png');

	$cmsmain->assign('images', $images);
	
	
	$cmsmain->assign('menu',CMSmenu());

	$allowed = db_numrows('SELECT id FROM cms_functions AS f LEFT OUTER JOIN cms_access AS x ON x.cms_functions_id = f.id WHERE (x.cms_users_id = :user_id OR f.allusers) AND (f.include = :action OR CONCAT(f.include,"_edit") = :action OR CONCAT(f.include,"_trash") = :action)',array('user_id'=>$_SESSION['user']['id'], 'action'=>$action));
	#$allowed = true;
	
	$allowedincamp = db_numrows('SELECT id FROM cms_functions AS f LEFT OUTER JOIN cms_functions_camps AS x ON x.cms_functions_id = f.id WHERE (x.camps_id = :camp_id OR f.allusers) AND (f.include = :action OR CONCAT(f.include,"_edit") = :action OR CONCAT(f.include,"_trash") = :action)',array('camp_id'=>$_SESSION['camp']['id'], 'action'=>$action));

	if  (($allowed&&$allowedincamp) || $_SESSION['user']['is_admin']) {
		@include('include/'.$action.'.php');
	}

	$cmsmain->assign('action',$action);
	$cmsmain->display('cms_index.tpl');

