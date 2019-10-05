<?php

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
        listsetting('allowsort', false);
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
					WHERE t.people_id = people.id AND people.parent_id IS NULL AND product_id != 0 
					ORDER BY transaction_date DESC LIMIT 1),0), COALESCE(people.created,0))) AS lastactive, 
				people.*, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), people.date_of_birth)), "%Y")+0 AS age, IF(gender="M","Male","Female") AS gender2, IF(people.parent_id,"",SUM(t2.drops)) AS drops 
				FROM people 
				LEFT OUTER JOIN transactions AS t2 ON t2.people_id = people.id 
				WHERE people.deleted > DATE_SUB(NOW(), INTERVAL '.$_SESSION['camp']['daystokeepdeletedpersons'].' DAY) AND people.camp_id = '.$_SESSION['camp']['id'].' 
				GROUP BY people.id
				ORDER BY deleted DESC');

        addcolumn('text', 'Lastname', 'lastname');
        addcolumn('text', 'Firstname', 'firstname');
        addcolumn('text', 'Gender', 'gender2');
        addcolumn('text', 'Age', 'age');
        addcolumn('text', $_SESSION['camp']['familyidentifier'], 'container');
        addcolumn('text', ucwords($_SESSION['camp']['currencyname']), 'drops');
        addcolumn('datetime', 'Last active', 'lastactive');

        addbutton('undelete', 'Recover', ['icon' => 'fa-history', 'oneitemonly' => false]);
        addbutton('realdelete', 'Full delete', ['icon' => 'fa-trash', 'oneitemonly' => false, 'confirm' => true]);

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
                list($success, $message, $redirect) = listUnDelete($table, $ids);

                break;
            case 'realdelete':
                $ids = explode(',', $_POST['ids']);
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
