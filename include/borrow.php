<?php

    $table = 'borrow_items';
    $ajax = checkajax();

    if (!$ajax) {
        if (!$_SESSION['camp']['bicycle']) {
            throw new Exception('Bicycle borrowing is not enabled for your camp');
        }
        initlist();

        $cmsmain->assign('title', 'Borrow items');

        listfilter2(['label' => 'Location', 'query' => 'SELECT id, location FROM borrow_locations WHERE camp_id = '.intval($_SESSION['camp']['id']).' ORDER BY id', 'filter' => 'b.location_id']);
        listfilter(['label' => 'Category', 'query' => 'SELECT id, label FROM borrow_categories ORDER BY id', 'filter' => 'b.category_id']);
        listsetting('manualquery', true);

        $query = 'SELECT b.visible, b.visible AS editable, b.label, b.location_id, b.category_id, bc.label AS category, b.id,

	(SELECT status FROM borrow_transactions AS t WHERE t.bicycle_id = b.id ORDER BY transaction_date DESC LIMIT 1) AS status, 
	(SELECT IF(status="out",CONCAT((SELECT CONCAT(firstname," ",lastname," (",container,")") FROM people WHERE id = people_id),IF(t.lights," ***",""),IF(t.helmet," ###","")),b.comment) FROM borrow_transactions AS t WHERE t.bicycle_id = b.id ORDER BY transaction_date DESC LIMIT 1) AS user, 
	(SELECT IF(status="out",
	
	IF(TIME_TO_SEC(TIMEDIFF(NOW(),transaction_date))>'.(intval($_SESSION['camp']['bicyclerenttime']) * 3600).',CONCAT("<b class=\"warning\">",CONCAT(HOUR(TIMEDIFF(NOW(),transaction_date)),":",LPAD(MINUTE(TIMEDIFF(NOW(),transaction_date)),2,"0")),"&nbsp;<i class=\"fa fa-warning\"></i></b>"),DATE_FORMAT(TIMEDIFF(NOW(),transaction_date),"%H:%i"))
	
	
	,"") FROM borrow_transactions AS t WHERE t.bicycle_id = b.id ORDER BY transaction_date DESC LIMIT 1) AS date
FROM borrow_items AS b LEFT OUTER JOIN borrow_categories AS bc ON bc.id = b.category_id WHERE NOT b.deleted'.
        ($_SESSION['filter']['borrow'] ? ' AND (b.category_id = '.$_SESSION['filter']['borrow'].')' : '');

        if ($_SESSION['filter2']['borrow']) {
            $data = getlistdata($query);

            foreach ($data as $key => $d) {
                if ($d['location_id'] != $_SESSION['filter2']['borrow'] && 'out' != $d['status']) {
                    unset($data[$key]);
                }
                if (2 == $_SESSION['filter2']['borrow'] && (2 == $d['category_id'] || 4 == $d['category_id'])) {
                    unset($data[$key]);
                }
            }
        } else {
            $listfooter['label'] = 'Select your location in the top right before proceeding';
            $listfooter['user'] = '';
            $listfooter['date'] = '';
        }

        foreach ($data as $key => $value) {
            if (strpos($data[$key]['user'], '###')) {
                $data[$key]['user'] = str_replace('###', '🧢', $data[$key]['user']);
            }
            if (strpos($data[$key]['user'], '***')) {
                $data[$key]['user'] = str_replace('***', '💡', $data[$key]['user']);
            }
        }
        /*
                addcolumn('text','Location','location_id');
                addcolumn('text','Category','category_id');
        */
        addcolumn('text', 'Name', 'label', ['width' => 200]);
        addcolumn('html', 'Rented out to', 'user');
        addcolumn('html', 'Duration', 'date', ['width' => 80]);

        addbutton('edititem', 'Edit item', ['icon' => 'fa-edit', 'oneitemonly' => true]);
        addbutton('borrowhistory', 'View history', ['icon' => 'fa-history', 'oneitemonly' => true]);

        listsetting('allowsort', true);
        listsetting('allowdelete', $_SESSION['usergroup']['allow_borrow_adddelete'] || $_SESSION['user']['is_admin']);
        listsetting('allowshowhide', false);
        listsetting('allowadd', $_SESSION['usergroup']['allow_borrow_adddelete'] || $_SESSION['user']['is_admin']);
        listsetting('allowselect', true);
        listsetting('allowselectall', false);

        $listconfig['new'] = 'borrowedititem';

        $cmsmain->assign('data', $data);
        $cmsmain->assign('listfooter', $listfooter);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    } else {
        switch ($_POST['do']) {
            case 'edititem':
                $id = intval($_POST['ids']);
                $success = true;
                $redirect = '?action=borrowedititem&id='.$id;

                break;

            case 'borrowhistory':
                $id = intval($_POST['ids']);
                $success = true;
                $redirect = '?action=borrowhistory&id='.$id;

                break;

            case 'move':
                $ids = json_decode($_POST['ids']);
                list($success, $message, $redirect) = listMove($table, $ids);

                break;

            case 'delete':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listDelete($table, $ids);

                break;

            case 'copy':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listCopy($table, $ids, 'menutitle');

                break;

            case 'hide':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listShowHide($table, $ids, 0);
                $message = $_POST['ids'];

                break;

            case 'show':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listShowHide($table, $ids, 1);

                break;
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

        echo json_encode($return);

        exit;
    }
