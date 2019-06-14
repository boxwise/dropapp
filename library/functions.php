<?
	
	# returns true if the current user is allowed to give drops (he is allowed when his usergroup has access to Give Drops to all function)
	function allowgivedrops() {
		return $_SESSION['user']['is_admin']||db_value('SELECT id FROM cms_functions AS f, cms_usergroups_functions AS uf WHERE uf.cms_functions_id = f.id AND f.include = "give2all" AND uf.cms_usergroups_id = :usergroup',array('usergroup'=>$_SESSION['usergroup']['id']));
	}

	function camplist($short = false) {
		if($_SESSION['user']['is_admin']) {
			$camplist = db_array('SELECT c.* FROM camps AS c WHERE (NOT c.deleted OR c.deleted IS NULL) AND organisation_id = :organisation_id ORDER BY c.seq', array('organisation_id'=>$_SESSION['organisation']['id']));
		} else {
			$camplist = db_array('SELECT c.* FROM camps AS c, cms_usergroups_camps AS x WHERE (NOT c.deleted OR c.deleted IS NULL) AND c.organisation_id = :organisation_id AND x.camp_id = c.id AND x.cms_usergroups_id = :usergroup ORDER BY c.seq',array('usergroup'=>$_SESSION['usergroup']['id'], 'organisation_id'=>$_SESSION['organisation']['id']));
		}
		if($short) {
			foreach($camplist as $c) $list[] = $c['id'];
			return $list;
		}
		return $camplist;
	}