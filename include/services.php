<?php

$action = 'services';
$table = 'services';
$ajax = checkajax();

if (!$ajax) {
    initlist();
    listsetting('haspagemenu', true);
    listsetting('allowcopy', false);
    listsetting('allowmove', false);
    listsetting('allowsort', true);
    listsetting('allowshowhide', false);
    listsetting('allowdelete', true);
    listsetting('allowedit', false);
    listsetting('search', ['label', 'description']);
    listsetting('allowadd', true);
    listsetting('delete', 'Deactivate');
    addpagemenu('active', 'Active', ['link' => '?action=services', 'active' => true]);
    addpagemenu('deactivated', 'Deactivated', ['link' => '?action=services_deactivated']);
    $cmsmain->assign('title', 'Services');

    $data = getlistdata('
            SELECT 
                services.*
            FROM 
                services
            WHERE 
                services.deleted IS NULL AND
                services.camp_id = '.$_SESSION['camp']['id'].'
            GROUP BY
                services.id
            ORDER BY 
                services.seq');

    addcolumn('text', 'Name', 'label');
    addcolumn('text', 'Description', 'description');
    addcolumn('datetime', 'Created', 'created');

    listsetting('add', 'Add a service');

    $cmsmain->assign('data', $data);
    $cmsmain->assign('listconfig', $listconfig);
    $cmsmain->assign('listdata', $listdata);
    $cmsmain->assign('include', 'cms_list.tpl');
} else {
    switch ($_POST['do']) {
        case 'delete':
            [$success, $message, $redirect] = db_transaction(function () use ($table) {
                $ids = explode(',', (string) $_POST['ids']);
                [$success, $message, $redirect] = listDelete($table, $ids, false, ['cms_usergroups', 'cms_users', 'camps']);

                return [$success, $message, $redirect];
            });

            break;
    }

    $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

    echo json_encode($return);

    exit;
}
