<?php

    $table = 'cms_settings';
    $ajax = checkajax();

    if (!$ajax) {
        initlist();

        $cmsmain->assign('title', $translate['cms_settings']);

        $hasBilanguage = db_fieldexists($table, 'description_nl');

        if ($hasBilanguage) {
            listsetting('search', ['code', 'description_'.$lan]);
        } else {
            listsetting('search', ['code', 'description']);
        }

        $data = getlistdata('SELECT t.* FROM '.$table.' AS t');

        if ($hasBilanguage) {
            addcolumn('text', $translate['cms_settings_description'], 'description_'.$lan, ['width' => '33%']);
        } else {
            addcolumn('text', $translate['cms_settings_description'], 'description', ['width' => '33%']);
        }
        addcolumn('text', $translate['cms_settings_value'], 'value', ['width' => '33%']);
        if ($_SESSION['user']['is_admin']) {
            addcolumn('text', $translate['cms_settings_code'], 'code', ['width' => '33%']);
        }

        listsetting('add', $translate['cms_settings_new']);
        listsetting('allowdelete', $_SESSION['user']['is_admin']);
        listsetting('allowadd', $_SESSION['user']['is_admin']);
        listsetting('allowcopy', $_SESSION['user']['is_admin']);

        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    } else {
        switch ($_POST['do']) {
            case 'move':
                $ids = json_decode($_POST['ids']);
                [$success, $message, $redirect] = listMove($table, $ids);

                break;

            case 'delete':
                $ids = explode(',', $_POST['ids']);
                [$success, $message, $redirect] = listDelete($table, $ids);

                break;

            case 'copy':
                $ids = explode(',', $_POST['ids']);
                [$success, $message, $redirect] = listCopy($table, $ids, 'code');

                break;

            case 'hide':
                $ids = explode(',', $_POST['ids']);
                [$success, $message, $redirect] = listShowHide($table, $ids, 0);

                break;

            case 'show':
                $ids = explode(',', $_POST['ids']);
                [$success, $message, $redirect] = listShowHide($table, $ids, 1);

                break;
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

        echo json_encode($return);

        exit();
    }
