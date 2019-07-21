<?php
	$table = $action;
	$ajax = checkajax();

	if ($ajax) {
		switch ($_POST['do']) {
			case 'move':
				$ids = json_decode($_POST['ids']);
				list($success, $message, $redirect) = listMove($table, $ids);
				break;
	
			case 'delete':
				$ids = explode(',',$_POST['ids']);
				list($success, $message, $redirect) = listDelete($table, $ids);
				if($success) {
					foreach ($ids as $id) {
						db_query('UPDATE cms_users SET email = CONCAT(email,".deleted.",id) WHERE id = :id', array('id'=>$id));
					}
				}
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
	
			case 'sendlogindata':
				$ids = explode(',',$_POST['ids']);
				list($success, $message, $redirect) = sendlogindata($table, $ids);
				break;
	
			case 'loginasuser':
				$ids = explode(',',$_POST['ids']);
				list($success, $message, $redirect) = loginasuser($table,$ids);
				break;
		}

		$return = array("success" => $success, 'message'=> $message, 'redirect'=>$redirect);

		echo json_encode($return);
		die();
	} else {
		initlist();
		listsetting('haspagemenu', true);
		listsetting('add', $translate['cms_users_new']);
		addpagemenu('all', 'Active', array('link'=>'?action=cms_users', 'active'=>true));
		addpagemenu('deactivated', 'Deactivated', array('link'=>'?action=cms_users_deactivated'));
		addpagemenu('deleted', 'Deleted', array('link'=>'?action=cms_users_deleted'));
		
		$cms_users_admin_query = '
			SELECT u.*, NOT u.is_admin AS visible, g.label AS usergroup, 0 AS preventdelete
			FROM cms_users AS u
			LEFT OUTER JOIN cms_usergroups AS g ON g.id = u.cms_usergroups_id 
			LEFT OUTER JOIN cms_usergroups_camps AS uc ON uc.cms_usergroups_id = g.id
			LEFT OUTER JOIN cms_usergroups_levels AS l ON l.id = g.userlevel
			WHERE 
				'.(!$_SESSION['user']['is_admin']?'l.level <'.intval($_SESSION['usergroup']['userlevel']).' AND ':'').'
				'.($_SESSION['user']['is_admin']?'':'(uc.camp_id IN ('.($camps?$camps:0).')) AND ').' 
				(g.organisation_id = '.intval($_SESSION['organisation']['id']).($_SESSION['user']['is_admin']?' OR u.is_admin':'').')
				AND (NOT g.deleted OR g.deleted IS NULL) AND (NOT u.deleted OR u.deleted IS NULL)
				AND NOT (
					(u.valid_lastday < NOW() AND UNIX_TIMESTAMP(u.valid_lastday) != 0) 
					OR (u.valid_firstday > NOW())
				)
			GROUP BY u.id';
	
		$cms_users_nonadmin_query = '
			SELECT u.*, 0 AS visible, g.label AS usergroup, 1 AS preventdelete
			FROM cms_users AS u
			LEFT OUTER JOIN cms_usergroups AS g ON g.id = u.cms_usergroups_id
			WHERE u.cms_usergroups_id = :usergroup AND u.id != :user
			AND NOT (
				(u.valid_lastday < NOW() AND UNIX_TIMESTAMP(u.valid_lastday) != 0) 
				OR (u.valid_firstday > NOW())
			)';	
	}
	require_once('cms_users_page.php');

	
	function loginasuser($table,$ids) {

		$id = $ids[0];
		if($_SESSION['user2'] or !$_SESSION['user']['is_admin']) {
			$success = false;
			$message = '"Login als" is alleen voor admingebruikers';
		} else {
			$_SESSION['user2'] = $_SESSION['user'];
			$_SESSION['camp2'] = $_SESSION['camp'];
			$_SESSION['usergroup2'] = $_SESSION['usergroup'];
			$_SESSION['organisation2'] = $_SESSION['organisation'];
			$_SESSION['user'] = db_row('SELECT * FROM cms_users WHERE id=:id',array('id'=>$id));
			$_SESSION['usergroup'] = db_row('SELECT ug.*, (SELECT level FROM cms_usergroups_levels AS ul WHERE ul.id = ug.userlevel) AS userlevel FROM cms_usergroups AS ug WHERE ug.id = :id AND (NOT ug.deleted OR ug.deleted IS NULL)',array('id'=>$_SESSION['user']['cms_usergroups_id']));
			$_SESSION['organisation'] = db_row('SELECT * FROM organisations AS o WHERE id = :id AND (NOT o.deleted OR o.deleted IS NULL)',array('id'=>$_SESSION['usergroup']['organisation_id']));
			$camplist = camplist();
			$_SESSION['camp'] = $camplist[0];
			$success = true;
			$message = 'Nu ingelogd als '.$_SESSION['user']['naam'];
		}

		return array($success,$message,true);
	}
