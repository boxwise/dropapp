<?php

$action = 'tags';
$table = 'tags';
$ajax = checkajax();

if (!$ajax) {
    initlist();

    $cmsmain->assign('title', 'Tags');

    $data = getlistdata('
            SELECT 
                tags.*,
                CASE 
                    WHEN(tags.type = "All") THEN "Boxes + Beneficiaries"
                    WHEN(tags.type = "People") THEN "Beneficiaries"
                    WHEN(tags.type = "Stock") THEN "Boxes"
                END AS typelabel,
                COUNT(tags_relations.object_id) AS tagscount,
                0 as level
            FROM 
                tags
            LEFT JOIN
                tags_relations ON tags_relations.tag_id = tags.id AND tags_relations.deleted_on IS NULL

            WHERE 
                tags.deleted IS NULL AND
                tags.camp_id = '.$_SESSION['camp']['id'].'
            GROUP BY
                tags.id
            ORDER BY 
                tags.seq');

    foreach ($data as $key => $value) {
        $data[$key]['tag'] = [['label' => $data[$key]['label'], 'color' => $data[$key]['color'], 'textcolor' => get_text_color($data[$key]['color'])]];
    }

    addcolumn('tag', 'Name', 'tag');
    addcolumn('text', 'Apply to', 'typelabel');
    addcolumn('text', 'Description', 'description');
    addcolumn('text', 'Total tags', 'tagscount');

    listsetting('allowsort', false);
    listsetting('add', 'Add a tag');

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

        case 'move':
            $ids = json_decode((string) $_POST['ids']);
            [$success, $message, $redirect] = listMove($table, $ids);

            break;
    }

    $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

    echo json_encode($return);

    exit;
}
