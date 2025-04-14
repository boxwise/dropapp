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
    listsetting('search', ['label', 'description']);
    listsetting('allowadd', true);
    listsetting('delete', 'Deactivate');
    addpagemenu('active', 'Active', ['link' => '?action=services', 'active' => true]);
    addpagemenu('deactivated', 'Deactivated', ['link' => '?action=services_deactivated']);
    $cmsmain->assign('title', 'Services');

    $data = getlistdata('
            SELECT 
                s.id,
                s.label,
                s.description,
                s.created,
                s.modified,
                COUNT(sr.id) AS total_usage,
                COUNT(DISTINCT sr.people_id) AS unique_usage,
                MAX(sr.created) AS last_used
            FROM 
                services s
            LEFT JOIN
                services_relations sr ON sr.service_id = s.id
            WHERE 
                s.deleted IS NULL AND
                s.camp_id = '.$_SESSION['camp']['id'].'
            GROUP BY
                s.id
            ORDER BY 
                s.seq');

    addcolumn('text', 'Name', 'label');
    addcolumn('text', 'Description', 'description');
    addcolumn('text', 'Total Usage', 'total_usage');
    addcolumn('text', 'Unique Usage', 'unique_usage');
    addcolumn('datetime', 'Created', 'created');
    addcolumn('datetime', 'Last Used', 'last_used');

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
