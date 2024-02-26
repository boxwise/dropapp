<?php

$table = $action;
$ajax = checkajax();

if (!$ajax) {
    initlist();

    $cmsmain->assign('title', 'Downloads');

    $data = getlistdata('SELECT *, SUBSTRING_INDEX(file, "/", -1) AS filename, IFNULL(modified, created) AS lastmod FROM downloads ORDER BY downloads DESC LIMIT 500 ');

    listsetting('allowcopy', false);
    listsetting('allowedit', false);
    listsetting('allowadd', false);
    listsetting('allowdelete', false);
    listsetting('allowselect', false);
    listsetting('allowsort', true);
    // listsetting('sortlist','[[3,0]]');

    addcolumn('text', 'Bestand', 'filename', ['breakall' => true]);
    addcolumn('text', 'Aantal downloads', 'downloads');
    addcolumn('datetime', 'Laatst gedownload', 'lastmod', ['width' => 200]);

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
            [$success, $message, $redirect] = listCopy($table, $ids, 'source');

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

    exit;
}
