<?php

    $action = 'tags';
    $table = 'tags';
    $ajax = checkajax();

    if (!$ajax) {
        initlist();

        $cmsmain->assign('title', 'Tags');

        $data = getlistdata('
            SELECT 
                * 
            FROM 
                tags 
            WHERE 
                deleted IS NULL AND
                camp_id = '.$_SESSION['camp']['id']);

        addcolumn('text', 'Name', 'label');

        listsetting('allowsort', true);
        listsetting('add', 'Add a tag');

        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    } else {
        switch ($_POST['do']) {
            case 'delete':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listDelete($table, $ids, false, ['cms_usergroups', 'cms_users', 'camps']);

                break;
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

        echo json_encode($return);
        die();
    }
