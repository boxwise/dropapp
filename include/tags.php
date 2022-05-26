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
                COUNT(tags_relations.object_id) AS tagscount 
            FROM 
                tags
            LEFT JOIN
                tags_relations ON tags_relations.tag_id = tags.id

            WHERE 
                tags.deleted IS NULL AND
                tags.camp_id = '.$_SESSION['camp']['id'].'
            GROUP BY
                tags.id');

        foreach ($data as $key => $value) {
            $data[$key]['tag'] = [['label' => $data[$key]['label'], 'color' => $data[$key]['color'], 'textcolor' => get_text_color($data[$key]['color'])]];
        }

        addcolumn('tag', 'Name', 'tag');
        addcolumn('text', 'Apply to', 'typelabel');
        addcolumn('text', 'Description', 'description');
        addcolumn('text', 'Total tags', 'tagscount');

        listsetting('allowsort', true);
        listsetting('add', 'Add a tag');

        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    } else {
        switch ($_POST['do']) {
            case 'delete':
                [$success, $message, $redirect] = db_transaction(function () use ($table) {
                    $ids = explode(',', $_POST['ids']);
                    list($success, $message, $redirect) = listDelete($table, $ids, false, ['cms_usergroups', 'cms_users', 'camps']);
                    // bulk delete of tags relations
                    // related to trello card https://trello.com/c/XjNwO3sL
                    $deleteClause = [];
                    foreach ($ids as $tag_id) {
                        $deleteClause[] = sprintf('%d', $tag_id);
                    }
                    if (sizeof($deleteClause) > 0) {
                        db_query('DELETE FROM tags_relations WHERE tag_id IN ('.join(',', $deleteClause).')');
                    }

                    return [$success, $message, $redirect];
                });

                break;

            case 'checktags':
                // Show warning message if tag already applied to object
                // related trello https://trello.com/c/XjNwO3sL
                $id = $_POST['id'];
                $selectedType = $_POST['type'];
                $success = true;

                if ($id && $selectedType) {
                    $result = db_row("SELECT 
                                        tags.type AS origin_type,
                                        SUM(IF(tags_relations.object_type = 'Stock',
                                            1,
                                            0)) AS boxes_count,
                                        SUM(IF(tags_relations.object_type = 'People',
                                            1,
                                            0)) AS beneficiaries_count
                                    FROM
                                        tags
                                            LEFT JOIN
                                        tags_relations ON tags_relations.tag_id = tags.id
                                    WHERE
                                        tags.deleted IS NULL
                                            AND tags.camp_id = :campId
                                            AND tags.id = :tagId
                                    GROUP BY tags.id", ['campId' => $_SESSION['camp']['id'], 'tagId' => $id]);

                    $originType = $result['origin_type'];
                    $boxesCount = intval($result['boxes_count']);
                    $beneficiariesCount = intval($result['beneficiaries_count']);

                    define('BOX_WARNING', 'Changing the apply to will remove those tags once it has been saved, as there are about '.$boxesCount.' boxes that applied tags');
                    define('PEOPLE_WARINING', 'Changing the apply to will remove those tags once it has been saved, as there are about '.$beneficiariesCount.' beneficiaries that applied tags');

                    if ('All' === $originType) {
                        if ('Stock' === $selectedType && $beneficiariesCount > 0) {
                            $message = PEOPLE_WARINING;
                            $success = false;
                        } elseif ('People' === $selectedType && $boxesCount > 0) {
                            $message = BOX_WARNING;
                            $success = false;
                        }
                    } elseif ('Stock' === $originType) {
                        if ('People' === $selectedType && $boxesCount > 0) {
                            $message = BOX_WARNING;
                            $success = false;
                        }
                    } elseif ('People' === $originType) {
                        if ('Stock' === $selectedType && $beneficiariesCount > 0) {
                            $message = PEOPLE_WARINING;
                            $success = false;
                        }
                    }

                    $redirect = false;
                }
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

        echo json_encode($return);

        exit();
    }
