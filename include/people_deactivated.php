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
    // listsetting('allowselect',array(1));
    $search_fields = ['firstname', 'lastname', 'container'];
    // Add search fields for the additional custom fields if enabled
    if ($_SESSION['camp']['email_enabled']) {
        $search_fields[] = 'email';
    }
    if ($_SESSION['camp']['phone_enabled']) {
        $search_fields[] = 'phone';
    }
    if ($_SESSION['camp']['additional_field1_enabled']) {
        $search_fields[] = 'customfield1_value';
    }
    if ($_SESSION['camp']['additional_field2_enabled']) {
        $search_fields[] = 'customfield2_value';
    }
    listsetting('search', $search_fields);
    listsetting('allowadd', false);
    listsetting('haspagemenu', true);

    addpagemenu('all', 'All', ['link' => '?action=people']);
    addpagemenu('deactivated', 'Deactivated', ['link' => '?action=people_deactivated', 'active' => true]);

    // listfilter(array('label'=>'Filter op afdeling','query'=>'SELECT id AS value, title AS label FROM people_cats WHERE visible AND NOT deleted ORDER BY seq','filter'=>'c.id'));
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
    addcolumn('text', 'Head of Family', 'family_head');
    addcolumn('text', 'Gender', 'gender2');
    addcolumn('text', 'Age', 'age');
    addcolumn('text', $_SESSION['camp']['familyidentifier'], 'container');
    addcolumn('text', ucwords((string) $_SESSION['camp']['currencyname']), 'drops');
    addcolumn('datetime', 'Last active', 'lastactive');
    // Display additional custom fields if enabled
    if ($_SESSION['camp']['email_enabled']) {
        addcolumn('text', 'Email address', 'email');
    }
    if ($_SESSION['camp']['phone_enabled']) {
        addcolumn('text', 'Phone number', 'phone');
    }
    if ($_SESSION['camp']['additional_field1_enabled']) {
        addcolumn('text', $_SESSION['camp']['additional_field1_label'], 'customfield1_value');
    }
    if ($_SESSION['camp']['additional_field2_enabled']) {
        addcolumn('text', $_SESSION['camp']['additional_field2_label'], 'customfield2_value');
    }
    if ($_SESSION['camp']['additional_field3_enabled']) {
        addcolumn('text', $_SESSION['camp']['additional_field3_label'], 'customfield3_value');
    }
    if ($_SESSION['camp']['additional_field4_enabled']) {
        addcolumn('date', $_SESSION['camp']['additional_field4_label'], 'customfield4_value');
    }

    addbutton('undelete', 'Activate', ['icon' => 'fa-history', 'oneitemonly' => false, 'testId' => 'recoverDeactivatedUser']);
    addbutton('realdelete', 'Full delete', ['icon' => 'fa-trash', 'oneitemonly' => false, 'confirm' => true, 'testId' => 'fullDeleteUser']);
    addcolumn('html', '&nbsp;', 'icons');

    Tracer::inSpan(
        ['name' => 'include/people_deactivated.php:hasActiveParent'],
        function () use (&$data) {
            global $settings;

            foreach ($data as $key => $value) {
                if ('1' == $data[$key]['has_not_active_parent']) {
                    $data[$key]['icons'] .= sprintf('<i class="fa fa-exclamation-triangle warning tooltip-this" title="To reactivate %s please make sure you reactivate their family head (%s) first."></i>', $data[$key]['firstname'].' '.$data[$key]['lastname'], $data[$key]['family_head']);
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
        case 'undelete':
            $ids = explode(',', (string) $_POST['ids']);
            $finalIds = [];
            $errorMessage = '';
            foreach ($ids as $id) {
                $person = db_row('SELECT concat(firstname," ",lastname) as fullname, parent_id FROM people WHERE id = :id', ['id' => $id]);
                $parentId = $person['parent_id'];
                $parent = ($parentId) ? db_row('SELECT (NOT deleted OR deleted IS NULL) as has_active_parent, concat(firstname," ",lastname) as family_head  FROM people WHERE id = :id', ['id' => $parentId]) : null;
                $hasActiveParent = $parent['has_active_parent'] ?? false;
                if ($parentId && !in_array($parentId, $ids) && !boolval($hasActiveParent)) {
                    $errorMessage .= sprintf('Family head %s must be active before %s can be reactivated.<br>', $parent['family_head'], $person['fullname']);

                    continue;
                }
                $finalIds[] = $id;
            }
            if (empty($errorMessage)) {
                // Optimised by using bulk inserts and transactions over update queries
                [$success, $message, $redirect] = listBulkUndelete($table, $finalIds, false, false);
                $redirect = true;
            } else {
                $success = false;
                $redirect = false;
                $message = (!empty($errorMessage)) ? $errorMessage : '';
            }

            break;

        case 'realdelete':
            $ids = explode(',', (string) $_POST['ids']);
            // Transaction block added over update queries
            [$success, $message, $redirect] = db_transaction(function () use ($ids, $table) {
                $now = (new DateTime())->format('Y-m-d H:i:s');
                $user_id = $_SESSION['user']['id'];
                foreach ($ids as $id) {
                    // unlink transactions
                    db_query('UPDATE transactions SET people_id = NULL WHERE people_id = :id', ['id' => $id]);
                    // unlink parent from children
                    db_query('UPDATE people SET parent_id = NULL WHERE parent_id = :id AND deleted', ['id' => $id]);
                    // remove tags already assigned to the beneficiary
                    db_query('UPDATE tags_relations SET deleted_on = :deleted_on, deleted_by_id = :deleted_by WHERE `object_id` = :id AND object_type = "People" AND deleted_on IS NULL', ['id' => $id, 'deleted_on' => $now, 'deleted_by' => $user_id]);
                }

                // Optimized by using bulk inserts and transactions over delete queries
                [$success, $message, $redirect] = listBulkRealDelete($table, $ids);

                return [$success, $message, $redirect];
            });
            $redirect = true;

            break;
    }

    $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect, 'action' => $aftermove];

    echo json_encode($return);

    exit;
}

function correctdrops($id)
{
    // $action = 'correctDrops({id:847, value: 1400}, {id:14, value: 1900})';

    $drops = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id', ['id' => intval($id)]);
    $person = db_row('SELECT * FROM people AS p WHERE id = :id', ['id' => $id]);

    if ($drops && $person['parent_id']) {
        db_query('INSERT INTO transactions (people_id, drops, description, transaction_date, user_id) VALUES ('.$person['parent_id'].', '.$drops.', "'.ucwords((string) $_SESSION['camp']['currencyname']).' moved from family member to family head", NOW(), '.$_SESSION['user']['id'].')');
        db_query('INSERT INTO transactions (people_id, drops, description, transaction_date, user_id) VALUES ('.$person['id'].', -'.$drops.', "'.ucwords((string) $_SESSION['camp']['currencyname']).' moved to new family head", NOW(), '.$_SESSION['user']['id'].')');
        $newamount = db_value('SELECT SUM(drops) FROM transactions WHERE people_id = :id', ['id' => $person['parent_id']]);
        $aftermove = 'correctDrops({id:'.$person['id'].', value: ""}, {id:'.$person['parent_id'].', value: '.$newamount.'})';

        return $aftermove;
    }
}
