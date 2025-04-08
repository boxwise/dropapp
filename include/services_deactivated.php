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
    listsetting('allowdelete', false);
    listsetting('allowedit', false);
    listsetting('search', ['label', 'description']);
    listsetting('allowadd', false);
    listsetting('haspagemenu', true);
    addbutton('undelete', 'Activate', ['icon' => 'fa-history', 'confirm' => true]);
    addpagemenu('active', 'Active', ['link' => '?action=services']);
    addpagemenu('deactivated', 'Deactivated', ['link' => '?action=services_deactivated', 'active' => true]);
    $cmsmain->assign('title', 'Services');

    $data = getlistdata('
            SELECT 
                services.*
            FROM 
                services
            WHERE 
                services.deleted IS NOT NULL AND
                services.camp_id = '.$_SESSION['camp']['id'].'
            GROUP BY
                services.id
            ORDER BY 
                services.seq');

    foreach ($data as $key => $value) {
        $data[$key]['services'] = [['label' => $data[$key]['label'], 'color' => $data[$key]['color'], 'textcolor' => get_text_color($data[$key]['color'])]];
    }

    addcolumn('text', 'Name', 'label');
    addcolumn('text', 'Description', 'description');

    $cmsmain->assign('data', $data);
    $cmsmain->assign('listconfig', $listconfig);
    $cmsmain->assign('listdata', $listdata);
    $cmsmain->assign('include', 'cms_list.tpl');
} else {
    switch ($_POST['do']) {
        case 'undelete':
            $ids = explode(',', (string) $_POST['ids']);
            [$success, $message, $redirect] = listUndelete($table, $ids, false);
            $redirect = true;

            break;
    }

    $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

    echo json_encode($return);

    exit;
}
