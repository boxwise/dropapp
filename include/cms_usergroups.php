<?php

$table = $action;
$ajax = checkajax();

if ($_SESSION['user']['is_admin'] || $_SESSION['usergroup']['userlevel'] > db_value('SELECT MIN(level) FROM cms_usergroups_levels')) {
    if (!$ajax) {
        initlist();

        $cmsmain->assign('title', 'User groups');
        listsetting('search', ['g.label']);

        $data = getlistdata('
			SELECT g.*, 
				IF(
					IFNULL(
						GROUP_CONCAT(
							IF(c.deleted IS NULL,c.name,\'\') ORDER BY c.seq SEPARATOR
							", "
						),
					"") = ",", "", IFNULL(GROUP_CONCAT( IF(c.deleted IS NULL,c.name,\'\') ORDER BY c.seq SEPARATOR ", " ), "")
				) AS camps,
				l.shortlabel AS userlevel 
			FROM cms_usergroups AS g 
			LEFT OUTER JOIN cms_usergroups_camps AS x ON x.cms_usergroups_id = g.id
			LEFT OUTER JOIN cms_usergroups_levels AS l ON l.id = g.userlevel
			LEFT OUTER JOIN camps AS c ON x.camp_id = c.id
			WHERE (NOT g.deleted OR g.deleted IS NULL) AND g.organisation_id = '.$_SESSION['organisation']['id'].'
				'.(!$_SESSION['user']['is_admin'] ? ' AND l.level < '.intval($_SESSION['usergroup']['userlevel']) : '').'
			GROUP BY g.id');

        addcolumn('text', 'Name', 'label');
        addcolumn('text', 'Level', 'userlevel');

        $num_camps_user_can_access = db_value('
			SELECT 
				COUNT(c.name)
			FROM cms_usergroups_camps AS x
			INNER JOIN cms_usergroups AS g ON x.cms_usergroups_id = g.id
			INNER JOIN cms_users AS us ON (us.cms_usergroups_id = g.id)
			INNER JOIN camps as c ON c.id = x.camp_id 
			WHERE us.id = '.$_SESSION['user']['id'].' ');

        if ($num_camps_user_can_access > 1 or $_SESSION['user']['is_admin']) {
            addcolumn('text', 'Bases', 'camps');
        }

        listsetting('allowsort', true);
        listsetting('allowadd', false);
        listsetting('allowdelete', false);
        listsetting('allowselectall', false);
        listsetting('allowselect', false);
        // disable usergroup in line with new Boxtribute 2.0
        // listsetting('add', 'Add a User Group');

        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    } else {
        switch ($_POST['do']) {
            case 'move':
                // $ids = json_decode($_POST['ids']);
                // list($success, $message, $redirect) = listMove($table, $ids);

                break;

            case 'delete':
                $ids = explode(',', (string) $_POST['ids']);
                // list($success, $message, $redirect) = listDelete($table, $ids, false, ['cms_users']);
                // disabling delete of usergroups according this trello card https://trello.com/c/HgKoMDov
                $success = false;
                $message = 'User Groups cannot be deleted';

                break;

            case 'copy':
                // $ids = explode(',', $_POST['ids']);
                // list($success, $message, $redirect) = listCopy($table, $ids, 'menutitle');

                break;

            case 'hide':
                // $ids = explode(',', $_POST['ids']);
                // list($success, $message, $redirect) = listShowHide($table, $ids, 0);

                break;

            case 'show':
                // $ids = explode(',', $_POST['ids']);
                // list($success, $message, $redirect) = listShowHide($table, $ids, 1);

                break;
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

        echo json_encode($return);

        exit;
    }
} else {
    throw new Exception('You do not have access to this menu. Please ask your admin to change this!');
}
