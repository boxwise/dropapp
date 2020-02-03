<?php

    $table = $action;
    $ajax = checkajax();

    if (!$ajax) {
        if (!$_SESSION['camp']['id']) {
            throw new Exception('The list of locations is not available when there is no camp selected');
        }

        $is_admin = ($_SESSION['user']['is_admin']);

        initlist();

        $cmsmain->assign('title', 'Warehouse Locations');
        listsetting('search', ['sizes.label']);

        if (!$is_admin) {
            $data = getlistdata('SELECT *, (SELECT COUNT(id) FROM stock WHERE location_id = locations.id AND NOT deleted) AS boxcount,0 as level
            FROM locations
            WHERE deleted IS NULL
            AND visible = 1
            AND container_stock != 1
            AND is_market != 1
            AND is_donated != 1
            AND is_lost != 1
            AND camp_id = '.$_SESSION['camp']['id']);
        } else {
            $data = getlistdata('SELECT *, (SELECT COUNT(id) FROM stock WHERE location_id = locations.id AND NOT deleted) AS boxcount,0 as level 
            FROM locations 
            WHERE deleted IS NULL 
            AND camp_id = '.$_SESSION['camp']['id']);
        }

        addcolumn('text', 'Warehouse Locations', 'label');
        addcolumn('text', 'Number of boxes', 'boxcount');

        listsetting('allowsort', false);
        if (!$is_admin) {
            listsetting('allowshowhide', false);
        }
        listsetting('add', 'Add a location');

        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    } else {
        switch ($_POST['do']) {
            case 'move':
                $ids = json_decode($_POST['ids']);
                list($success, $message, $redirect) = listMove($table, $ids);

                break;
            case 'delete':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listDelete($table, $ids, false, ['stock']);

                break;
            case 'copy':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listCopy($table, $ids, 'menutitle');

                break;
            case 'hide':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listShowHide($table, $ids, 0);

                break;
            case 'show':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listShowHide($table, $ids, 1);

                break;
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

        echo json_encode($return);
        die();
    }
