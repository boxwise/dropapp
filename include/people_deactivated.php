<?php

use OpenCensus\Trace\Tracer;

    $table = 'people';
    $action = 'people';
    $ajax = checkajax();

    if (!$ajax) {
        $translate['cms_list_confirm_title'] = 'Are you sure? You can not undo this anymore';

        initlist();

        $cmsmain->assign('title', 'Beneficiaries');

        listsetting('allowcopy', false);
        listsetting('allowmove', false);
        listsetting('allowmoveto', 1);
        listsetting('allowsort', true);
        listsetting('allowshowhide', false);
        listsetting('allowdelete', false);
        listsetting('allowedit', false);
        //listsetting('allowselect',array(1));
        listsetting('search', ['firstname', 'lastname', 'container']);
        listsetting('allowadd', false);
        listsetting('haspagemenu', true);

        addpagemenu('all', 'All', ['link' => '?action=people']);
        addpagemenu('deactivated', 'Deactivated', ['link' => '?action=people_deactivated', 'active' => true]);

        //listfilter(array('label'=>'Filter op afdeling','query'=>'SELECT id AS value, title AS label FROM people_cats WHERE visible AND NOT deleted ORDER BY seq','filter'=>'c.id'));
        $data = getlistdata('SELECT IF(people.parent_id,NULL,GREATEST(COALESCE((SELECT transaction_date 
					FROM transactions AS t 
					WHERE t.people_id = people.id AND people.parent_id IS NULL AND product_id IS NOT NULL 
					ORDER BY transaction_date DESC LIMIT 1),0), COALESCE(people.created,0))) AS lastactive, 
				people.*, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), people.date_of_birth)), "%Y")+0 AS age, IF(gender="M","Male","Female") AS gender2, IF(people.parent_id,"",SUM(t2.drops)) AS drops ,
                        IF(EXISTS(SELECT t3.deleted 
                            FROM people as t3 
                            WHERE t3.id = people.parent_id 
                                AND people.parent_id IS NOT NULL
                            AND t3.parent_id IS NULL 
                            AND t3.deleted > DATE_SUB(NOW(), INTERVAL 9999 DAY)),
                    1,0) as has_not_active_parent,
                    (SELECT concat(t4.firstname," ",t4.lastname)
                            FROM people as t4 
                            WHERE t4.id = people.parent_id 
                                AND people.parent_id IS NOT NULL
                            AND t4.parent_id IS NULL 
                    ) as family_head
				FROM people 
				LEFT OUTER JOIN transactions AS t2 ON t2.people_id = people.id 
				WHERE people.deleted > DATE_SUB(NOW(), INTERVAL '.$_SESSION['camp']['daystokeepdeletedpersons'].' DAY) AND people.camp_id = '.$_SESSION['camp']['id'].' 
				GROUP BY people.id
				ORDER BY deleted DESC');

        addcolumn('text', 'Surname', 'lastname');
        addcolumn('text', 'Firstname', 'firstname');
        addcolumn('text', 'Gender', 'gender2');
        addcolumn('text', 'Age', 'age');
        addcolumn('text', $_SESSION['camp']['familyidentifier'], 'container');
        addcolumn('text', ucwords($_SESSION['camp']['currencyname']), 'drops');
        addcolumn('datetime', 'Last active', 'lastactive');

        addbutton('undelete', 'Activate', ['icon' => 'fa-history', 'oneitemonly' => false, 'testId' => 'recoverDeactivatedUser']);
        addbutton('realdelete', 'Full delete', ['icon' => 'fa-trash', 'oneitemonly' => false, 'confirm' => true, 'testId' => 'fullDeleteUser']);
        addcolumn('html', '&nbsp;', 'icons');

        Tracer::inSpan(
            ['name' => ('include/people_deactivated.php:hasActiveParent')],
            function () use (&$data) {
                global $settings;

                foreach ($data as $key => $value) {
                    if ('1' == $data[$key]['has_not_active_parent']) {
                        $data[$key]['icons'] .= sprintf('<i class="fa fa-exclamation-triangle warning tooltip-this" title="%s\'s family head (%s) is not active"></i>', $data[$key]['firstname'], $data[$key]['family_head']);
                    }
                }
            }
        );
        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    } else {
        switch ($_POST['do']) {
            case 'give':
                $ids = ($_POST['ids']);
                $success = true;
                $redirect = '?action=give&ids='.$ids;

                break;
            case 'move':
                $ids = json_decode($_POST['ids']);
                list($success, $message, $redirect, $aftermove) = listMove($table, $ids, true, 'correctdrops');

                break;
            case 'delete':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listDelete($table, $ids);

                break;
            case 'undelete':
                $ids = explode(',', $_POST['ids']);
                $finalIds = [];
                $errorMessage = '';
                foreach ($ids as $id) {
                    $person = db_row('SELECT concat(firstname," ",lastname) as fullname, parent_id FROM people WHERE id = :id', ['id' => $id]);
                    $parentId = $person['parent_id'];
                    $hasActiveParent = ($parentId) ? db_value('SELECT (NOT deleted OR deleted IS NULL) as parant FROM people WHERE id = :id', ['id' => $parentId]) : null;

                    if ($parentId && !in_array($parentId, $ids) && !boolval($hasActiveParent)) {
                        $errorMessage .= $person['fullname'].' does not have an active family head.<br>';

                        continue;
                    }
                    array_push($finalIds, $id);
                }
                if (empty($errorMessage)) {
                    list($success, $message, $redirect) = listUnDelete($table, $finalIds);
                } else {
                    $success = false;
                    $redirect = false;
                    $message = (!empty($errorMessage)) ? $errorMessage : '';
                }

                break;
            case 'realdelete':
                $ids = explode(',', $_POST['ids']);
                foreach ($ids as $id) {
                    // unlink transactions
                    db_query('UPDATE transactions SET people_id = NULL WHERE people_id = :id', ['id' => $id]);
                    // unlink parent from children
                    db_query('UPDATE people SET parent_id = NULL WHERE parent_id = :id AND deleted', ['id' => $id]);
                }
                list($success, $message, $redirect) = listRealDelete($table, $ids);

                break;
            case 'copy':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listCopy($table, $ids, 'name');

                break;
            case 'hide':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listShowHide($table, $ids, 0);

                break;
            case 'show':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listShowHide($table, $ids, 1);

                break;
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect, 'action' => $aftermove];

        echo json_encode($return);
        die();
    }

    function correctdrops($id)
    {
        //$action = 'correctDrops({id:847, value: 1400}, {id:14, value: 1900})';

        $drops = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id', ['id' => intval($id)]);
        $person = db_row('SELECT * FROM people AS p WHERE id = :id', ['id' => $id]);

        if ($drops && $person['parent_id']) {
            db_query('INSERT INTO transactions (people_id, drops, description, transaction_date, user_id) VALUES ('.$person['parent_id'].', '.$drops.', "'.ucwords($_SESSION['camp']['currencyname']).' moved from family member to family head", NOW(), '.$_SESSION['user']['id'].')');
            db_query('INSERT INTO transactions (people_id, drops, description, transaction_date, user_id) VALUES ('.$person['id'].', -'.$drops.', "'.ucwords($_SESSION['camp']['currencyname']).' moved to new family head", NOW(), '.$_SESSION['user']['id'].')');
            $newamount = db_value('SELECT SUM(drops) FROM transactions WHERE people_id = :id', ['id' => $person['parent_id']]);
            $aftermove = 'correctDrops({id:'.$person['id'].', value: ""}, {id:'.$person['parent_id'].', value: '.$newamount.'})';

            return $aftermove;
        }
    }
