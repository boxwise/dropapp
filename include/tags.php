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
                COUNT(people.id) AS peoplecount 
            FROM 
                tags
            LEFT JOIN
                people_tags ON people_tags.tag_id = tags.id
            LEFT JOIN
                people ON people_tags.people_id= people.id
            WHERE 
                tags.deleted IS NULL AND
                tags.camp_id = '.$_SESSION['camp']['id'].'
            GROUP BY
                tags.id');

        foreach ($data as $key => $value) {
            $data[$key]['tag'] = [['label' => $data[$key]['label'], 'color' => $data[$key]['color'], 'textcolor' => get_text_color($data[$key]['color'])]];
        }

        addcolumn('tag', 'Name', 'tag');
        addcolumn('text', 'Description', 'description');
        addcolumn('text', 'People', 'peoplecount');

        listsetting('allowsort', true);
        listsetting('add', 'Add a tag');

        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    } else {
        switch ($_POST['do']) {
            case 'delete':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listDelete($table, $ids, false, ['cms_usergroups', 'cms_users', 'camps']);

                break;
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

        echo json_encode($return);

        exit();
    }
