<?php
	
	require_once('library/core.php');
	date_default_timezone_set('Europe/Athens');
	db_query('SET time_zone = "+'.(date('Z')/3600).':00"');
	
	# action set by POST will override GET
	$action = (isset($_POST['action'])?$_POST['action']:(isset($_GET['action'])?$_GET['action']:'start'));
	$id = intval($_GET['id']);
	if ($action == 'logout') logout();

	// dailyroutine is performed when the last action of any user is not of today
	if(strftime('%Y-%m-%d')!=$settings['dailyroutine'] || $action == 'dailyroutine') {
		db_query('UPDATE cms_settings SET value = "'.strftime('%Y-%m-%d').'" WHERE code = "dailyroutine"');
	 	require('dailyroutine.php');
	}

	$cmsmain = new Zmarty;

	/* make an organisation menu, if the user is system admin */
	if($_SESSION['user']['is_admin']) {
		if($_GET['organisation']) {
			unset($_SESSION['camp']);
			$_SESSION['organisation'] = db_row('SELECT * FROM organisations WHERE id = :id AND (NOT organisations.deleted OR organisations.deleted IS NULL)',array('id'=>$_GET['organisation']));
		}
		$organisations = db_array('SELECT * FROM organisations 
			WHERE (NOT organisations.deleted OR organisations.deleted IS NULL) 
			ORDER BY label');
		$cmsmain->assign('organisations',$organisations);
	}
	
	# This fills the camp menu in the top bar (only if the user has access to more than 1 camp
	if($_GET['camp']) getcampdata($_GET['camp']);
	
	$camplist = camplist();
	if(!isset($_SESSION['camp'])) $_SESSION['camp'] = $camplist[0];
	$cmsmain->assign('camps',$camplist);
	$cmsmain->assign('currentcamp',$_SESSION['camp']);
	$cmsmain->assign('currentOrg', $_SESSION['organisation']);
	$cmsmain->assign('campaction',strpos($action,'_edit')?substr($action,0,-5):$action);
	
	$cmsmain->assign('menu',CMSmenu());
	
	# checks if the requested action is allowed for the user's usergroup and camp
	$allowed = db_numrows('SELECT f.id, f.title_en, IF(f2.parent_id != 0,"3","2") FROM cms_functions AS f 
LEFT OUTER JOIN cms_usergroups_functions AS uf ON uf.cms_functions_id = f.id
LEFT OUTER JOIN cms_functions_camps AS fc ON fc.cms_functions_id = f.id
LEFT OUTER JOIN cms_functions AS f2 ON f.parent_id = f2.id
LEFT OUTER JOIN cms_usergroups_functions AS uf2 ON uf2.cms_functions_id = f2.id
LEFT OUTER JOIN cms_functions_camps AS fc2 ON fc2.cms_functions_id = f2.id
WHERE 
(f.include = :action OR CONCAT(f.include,"_edit") = :action OR CONCAT(f.include,"_trash") = :action) 
AND (f.allusers OR (f2.parent_id = 0 AND uf.cms_usergroups_id = :usergroup AND (fc.camps_id = :camp_id OR f.allcamps)) OR f2.allusers OR (f2.parent_id != 0 AND uf2.cms_usergroups_id = :usergroup AND (fc2.camps_id = :camp_id OR f2.allcamps)))
',array('usergroup'=>$_SESSION['usergroup']['id'],'camp_id'=>$_SESSION['camp']['id'], 'action'=>$action));

	# if the action is allowed or if the user is a system admin, we load it
	if  ($allowed || $_SESSION['user']['is_admin']) {
		@include('include/'.$action.'.php');
	}

	$cmsmain->assign('action',$action);
	$cmsmain->display('cms_index.tpl');

