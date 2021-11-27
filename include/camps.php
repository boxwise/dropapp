<?php

    $table = $action;
    $ajax = checkajax();

    if (!$ajax) {
        initlist();

        $cmsmain->assign('title', 'Bases');

        $data = getlistdata('SELECT *, IF('.(intval($_SESSION['camp']['id'])).'=id,1,0) AS preventdelete FROM camps WHERE organisation_id = '.intval($_SESSION['organisation']['id']));

        addcolumn('text', 'Name', 'name');

        listsetting('add', 'Add a base');

        $listconfig['allowselectall'] = false;

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
                list($success, $message, $redirect) = listDelete($table, $ids, false, ['library', 'people', 'products', 'locations']);

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

        exit();
    }
