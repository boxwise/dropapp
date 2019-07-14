<?php

	$table = $action;

	listsetting('haspagemenu', true);
	addpagemenu('all', 'All', array('link'=>'?action=cms_users', 'active'=>true));
	addpagemenu('deactivated', 'Deactivated', array('link'=>'?action=cms_users_deactivated'));

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
		GROUP BY u.id';

	$cms_users_nonadmin_query = '
		SELECT u.*, 0 AS visible, g.label AS usergroup, 1 AS preventdelete
		FROM cms_users AS u
		LEFT OUTER JOIN cms_usergroups AS g ON g.id = u.cms_usergroups_id
		WHERE u.cms_usergroups_id = :usergroup AND u.id != :user';

	require_once('cms_users_page.php');