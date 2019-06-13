<?
	
	# returns true if the current user is allowed to give drops (he is allowed when his usergroup has access to Give Drops to all function)
	function allowGiveDrops() {
		return $_SESSION['user']['is_admin']||db_value('SELECT id FROM cms_functions AS f, cms_usergroups_functions AS uf WHERE uf.cms_functions_id = f.id AND f.include = "give2all" AND uf.cms_usergroups_id = :usergroup',array('usergroup'=>$_SESSION['usergroup']['id']));
	}
