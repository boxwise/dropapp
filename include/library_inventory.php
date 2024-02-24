<?php

$table = 'library';
$ajax = checkajax();

if (!$ajax) {
    initlist();

    $cmsmain->assign('title', 'Library');
    listsetting('search', ['code', 'booktitle_en', 'booktitle_ar', 'author']);

    listfilter(['label' => 'By category', 'query' => 'SELECT id, label FROM library_type ORDER BY label', 'filter' => 'library.type_id']);

    $data = getlistdata('SELECT * FROM library WHERE camp_id = '.intval($_SESSION['camp']['id']));

    addcolumn('text', 'Code', 'code');
    addcolumn('text', 'English title', 'booktitle_en');
    addcolumn('html', 'Original title', 'booktitle_ar');
    addcolumn('text', 'Author', 'author');

    listsetting('allowsort', true);
    listsetting('allowcopy', false);
    listsetting('allowshowhide', true);
    listsetting('add', 'Add a book');
    listsetting('delete', 'Delete');
    listsetting('show', 'Available');
    listsetting('hide', 'Not available');

    addbutton('libraryhistory', 'View history', ['icon' => 'fa-history', 'oneitemonly' => true]);

    $cmsmain->assign('data', $data);
    $cmsmain->assign('listconfig', $listconfig);
    $cmsmain->assign('listdata', $listdata);
    $cmsmain->assign('include', 'cms_list.tpl');
} else {
    switch ($_POST['do']) {
        case 'libraryhistory':
            $id = intval($_POST['ids']);
            $success = true;
            $redirect = '?action=libraryhistory&id='.$id;

            break;

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
            [$success, $message, $redirect] = listCopy($table, $ids, 'menutitle');

            break;

        case 'hide':
            $ids = explode(',', $_POST['ids']);
            [$success, $message, $redirect] = listShowHide($table, $ids, 0);

            break;

        case 'show':
            $ids = explode(',', $_POST['ids']);
            [$success, $message, $redirect] = listShowHide($table, $ids, 1);

            break;

        case 'togglecontainer':
            [$success, $message, $redirect, $newvalue] = listSwitch($table, 'stockincontainer', $_POST['id']);

            break;

        case 'export':
            $success = true;
            $redirect = '?action=products_export';

            break;
    }

    $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect, 'newvalue' => $newvalue];

    echo json_encode($return);

    exit;
}
