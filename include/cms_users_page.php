<?php

    if ($_SESSION['user']['is_admin'] || $_SESSION['usergroup']['userlevel'] > db_value('SELECT MIN(level) FROM cms_usergroups_levels')) {
        $cmsmain->assign('title', $translate['cms_users']);

        $data = getlistdata($cms_users_lower_level_query);
        if (!$_SESSION['user']['is_admin']) {
            $data2 = db_array($cms_users_same_level_query, ['user' => $_SESSION['user']['id'], 'usergroup' => $_SESSION['usergroup']['id']]);
            if (!empty($data2)) {
                if (isset($data)) {
                    $data = array_merge($data, $data2);
                } else {
                    $data = $data2;
                }
            }
        }

        addcolumn('text', $translate['cms_users_naam'], 'naam');
        addcolumn('text', $translate['cms_users_email'], 'email');
        addcolumn('text', 'Role', 'usergroup');
        addcolumn('date', 'Valid from', 'valid_firstday');
        addcolumn('date', 'Valid until', 'valid_lastday');

        listsetting('width', 12);
        listsetting('allowsort', true);
        listsetting('delete', 'Deactivate');

        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    } else {
        throw new Exception('You do not have access to this menu. Please ask your admin to change this!');
    }
