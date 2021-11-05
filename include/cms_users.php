<?php

    $table = $action;
    $ajax = checkajax();

    if ($ajax) {
        require_once 'cms_users_handle_ajax_operations.php';
    } else {
        initlist();
        listsetting('add', $translate['cms_users_new']);
        listsetting('haspagemenu', true);
        addpagemenu('active', 'Active & Pending', ['link' => '?action=cms_users', 'active' => true, 'testid' => 'active_pending']);
        addpagemenu('expired', 'Expired', ['link' => '?action=cms_users_expired', 'testid' => 'expired']);
        addpagemenu('deactivated', 'Deactivated', ['link' => '?action=cms_users_deactivated', 'testid' => 'deactivated']);
        addbutton('extendActive', 'Extend Access', ['icon' => 'fa-history', 'confirm' => true, 'testid' => 'extend-active-cms-user', 'options' => ['1 week', '1 month', '2 months', 'No end date']]);
        addbutton('sendlogindata', $translate['cms_users_sendlogin'], ['icon' => 'fa-user', 'confirm' => true, 'disableif' => true]);
        if ($_SESSION['user']['is_admin'] && !$_SESSION['user2']) {
            addbutton('loginasuser', $translate['cms_users_loginas'], ['icon' => 'fa-users', 'confirm' => true, 'oneitemonly' => true, 'disableif' => true]);
        }

        $camps = db_value(
            '
			SELECT GROUP_CONCAT(c.id) 
			FROM cms_usergroups_camps AS uc, camps AS c 
			WHERE (NOT c.deleted OR c.deleted IS NULL) AND uc.camp_id = c.id AND uc.cms_usergroups_id = :usergroup',
            ['usergroup' => $_SESSION['usergroup']['id']]
        );

        // Execution of queries in cms_users_page.php
        $cms_users_lower_level_query = '
			SELECT u.*, NOT u.is_admin AS visible, g.label AS usergroup, 0 AS preventdelete, 0 as disableifistrue
			FROM cms_users AS u
			LEFT OUTER JOIN cms_usergroups AS g ON g.id = u.cms_usergroups_id 
			LEFT OUTER JOIN cms_usergroups_camps AS uc ON uc.cms_usergroups_id = g.id
			LEFT OUTER JOIN cms_usergroups_levels AS l ON l.id = g.userlevel
			WHERE 
				'.(!$_SESSION['user']['is_admin'] ? 'l.level <'.intval($_SESSION['usergroup']['userlevel']).' AND ' : '').'
				'.($_SESSION['user']['is_admin'] ? '' : '(uc.camp_id IN ('.($camps ? $camps : 0).')) AND ').' 
				(g.organisation_id = '.intval($_SESSION['organisation']['id']).($_SESSION['user']['is_admin'] ? ' OR u.is_admin' : '').')
				AND (NOT g.deleted OR g.deleted IS NULL) AND (NOT u.deleted OR u.deleted IS NULL)
				AND NOT (u.valid_lastday < CURDATE() AND UNIX_TIMESTAMP(u.valid_lastday) != 0) 
				AND UNIX_TIMESTAMP(u.deleted) = 0
			GROUP BY u.id
			ORDER BY UNIX_TIMESTAMP(u.valid_firstday)';

        // Do not forget to specify :usergroup and :user in the db call later
        $cms_users_same_level_query = '
			SELECT u.*, 0 AS visible, g.label AS usergroup, 1 AS preventdelete, 1 as disableifistrue
			FROM cms_users AS u
			LEFT OUTER JOIN cms_usergroups AS g ON g.id = u.cms_usergroups_id
			WHERE u.cms_usergroups_id = :usergroup AND u.id != :user
			AND NOT (u.valid_lastday < CURDATE() AND UNIX_TIMESTAMP(u.valid_lastday) != 0)
			AND UNIX_TIMESTAMP(u.deleted) = 0';
    }

    require_once 'cms_users_page.php';
