<?php

    $table = 'cms_users';
    $ajax = checkajax();
    if ($ajax) {
        require_once 'cms_users_handle_ajax_operations.php';
    } else {
        initlist();
        listsetting('allowadd', false);
        listsetting('allowedit', false);
        listsetting('allowdelete', false);
        listsetting('haspagemenu', true);
        addpagemenu('active', 'Active & Pending', ['link' => '?action=cms_users', 'testid' => 'active_pending']);
        addpagemenu('expired', 'Expired', ['link' => '?action=cms_users_expired', 'testid' => 'expired']);
        addpagemenu('deactivated', 'Deactivated', ['link' => '?action=cms_users_deactivated', 'active' => true, 'testid' => 'deactivated']);
        addbutton('undelete', 'Activate', ['icon' => 'fa-history', 'confirm' => true]);

        $camps = db_value(
            '
			SELECT GROUP_CONCAT(c.id) 
			FROM cms_usergroups_camps AS uc, camps AS c 
			WHERE (NOT c.deleted OR c.deleted IS NULL) AND uc.camp_id = c.id AND uc.cms_usergroups_id = :usergroup',
            ['usergroup' => $_SESSION['usergroup']['id']]
        );

        // Execution of queries in cms_users_page.php
        $cms_users_lower_level_query = 'SELECT u.id, u.naam, SUBSTR(u.email, 1, LENGTH(u.email)-LENGTH(".deleted.")-LENGTH(u.id)) AS email, u.valid_firstday, u.valid_lastday, NOT u.is_admin AS visible, g.label AS usergroup, 0 AS preventdelete, 1 as disableifistrue
			FROM cms_users AS u
			LEFT OUTER JOIN cms_usergroups AS g ON g.id = u.cms_usergroups_id 
			LEFT OUTER JOIN cms_usergroups_camps AS uc ON uc.cms_usergroups_id = g.id
			LEFT OUTER JOIN cms_usergroups_levels AS l ON l.id = g.userlevel
			WHERE 
				'.(!$_SESSION['user']['is_admin'] ? 'l.level <'.intval($_SESSION['usergroup']['userlevel']).' AND ' : '').'
				'.($_SESSION['user']['is_admin'] ? '' : '(uc.camp_id IN ('.($camps ? $camps : 0).')) AND ').' 
				(g.organisation_id = '.intval($_SESSION['organisation']['id']).($_SESSION['user']['is_admin'] ? ' OR u.is_admin' : '').')
				AND (NOT g.deleted OR g.deleted IS NULL)
				AND u.deleted
            GROUP BY u.id
            ORDER BY UNIX_TIMESTAMP(u.valid_lastday)';

        // Do not forget to specify :usergroup and :user in the db call later
        $cms_users_same_level_query = '
			SELECT u.id, u.naam, SUBSTR(u.email, 1, LENGTH(u.email)-LENGTH(".deleted.")-LENGTH(u.id)) AS email, u.valid_firstday, u.valid_lastday, 0 AS visible, g.label AS usergroup, 1 AS preventdelete, 1 as disableifistrue
			FROM cms_users AS u
			LEFT OUTER JOIN cms_usergroups AS g ON g.id = u.cms_usergroups_id
			WHERE u.cms_usergroups_id = :usergroup
			AND u.deleted
			AND u.id != :user';

        require_once 'cms_users_page.php';
    }
