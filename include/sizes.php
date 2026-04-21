<?php

$table = $action;
$ajax = checkajax();

if (!$ajax) {
    $result = db_query('SELECT s.id, s.label, ss.sizegroup_id, ss.seq FROM sizes AS s JOIN sizes_sizegroup AS ss ON ss.size_id = s.id JOIN sizegroup AS sg ON sg.id = ss.sizegroup_id ORDER BY sg.seq, ss.seq');
    $i = 0;
    while ($row = db_fetch($result)) {
        if ($row['sizegroup_id'] != $oldgroup) {
            $i += 10;
        } else {
            ++$i;
        }

        db_query('UPDATE sizes_sizegroup SET seq = :seq WHERE size_id = :id AND sizegroup_id = :sizegroup_id', ['seq' => $i, 'id' => $row['id'], 'sizegroup_id' => $row['sizegroup_id']]);

        $oldgroup = $row['sizegroup_id'];
    }

    initlist();

    $cmsmain->assign('title', 'Sizes');
    listsetting('search', ['sizes.label']);

    $data = getlistdata('SELECT sizes.id, sizes.label, GROUP_CONCAT(g.label ORDER BY g.label SEPARATOR \', \') AS sizegroup FROM sizes LEFT JOIN sizes_sizegroup AS ss ON ss.size_id = sizes.id LEFT JOIN sizegroup AS g ON g.id = ss.sizegroup_id GROUP BY sizes.id, sizes.label');

    addcolumn('text', 'Sizes', 'label');
    addcolumn('text', 'Size group', 'sizegroup');

    listsetting('allowsort', false);
    listsetting('allowcopy', true);
    listsetting('add', 'Add a size');

    $cmsmain->assign('data', $data);
    $cmsmain->assign('listconfig', $listconfig);
    $cmsmain->assign('listdata', $listdata);
    $cmsmain->assign('include', 'cms_list.tpl');
} else {
    switch ($_POST['do']) {
        case 'move':
            $ids = json_decode((string) $_POST['ids']);
            [$success, $message, $redirect] = listMove($table, $ids);

            break;

        case 'delete':
            $ids = explode(',', (string) $_POST['ids']);
            [$success, $message, $redirect] = listDelete($table, $ids);

            break;

        case 'copy':
            $ids = explode(',', (string) $_POST['ids']);
            [$success, $message, $redirect] = listCopy($table, $ids, 'menutitle');

            break;

        case 'hide':
            $ids = explode(',', (string) $_POST['ids']);
            [$success, $message, $redirect] = listShowHide($table, $ids, 0);

            break;

        case 'show':
            $ids = explode(',', (string) $_POST['ids']);
            [$success, $message, $redirect] = listShowHide($table, $ids, 1);

            break;
    }

    $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

    echo json_encode($return);

    exit;
}
