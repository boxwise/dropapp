<?php

    $table = $action;
    $ajax = checkajax();
    if (!$ajax) {
        initlist();

        $cmsmain->assign('title', 'Products');
        listsetting('search', ['name', 'g.label', 'products.comments']);

        $locations = join(',', db_simplearray('SELECT id, id FROM locations WHERE visible AND camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]));
        if (!$locations) {
            $locations = 0;
        }

        listfilter(['label' => 'By category', 'query' => 'SELECT id, label FROM product_categories ORDER BY seq', 'filter' => 'products.category_id']);

        $data = getlistdata('SELECT products.*, sg.label AS sizegroup, g.label AS gender, CONCAT(products.value," '.$_SESSION['camp']['currencyname'].'") AS drops, COALESCE(SUM(s.items),0) AS items, IF(SUM(s.items),1,0) AS preventdelete FROM '.$table.'
			LEFT OUTER JOIN genders AS g ON g.id = products.gender_id
			LEFT OUTER JOIN sizegroup AS sg ON sg.id = products.sizegroup_id
			LEFT OUTER JOIN stock AS s ON s.product_id = products.id AND NOT s.deleted AND s.location_id IN ('.$locations.') 
			WHERE (NOT products.deleted OR products.deleted IS NULL) AND camp_id = '.intval($_SESSION['camp']['id']).'
			GROUP BY products.id
		');

        addcolumn('text', 'Product name', 'name');
        addcolumn('text', 'Gender', 'gender');
        addcolumn('text', 'Sizegroup', 'sizegroup');
        if (db_value('SELECT id FROM locations WHERE camp_id = '.intval($_SESSION['camp']['id']).' AND visible LIMIT 1 ')) {
            addcolumn('text', 'Items', 'items');
        }
        if ($_SESSION['camp']['market']) {
            addcolumn('text', ucfirst($_SESSION['camp']['currencyname']), 'drops');
        }
        addcolumn('text', 'Description', 'comments');
        if (db_value('SELECT id FROM locations WHERE locations.camp_id = '.intval($_SESSION['camp']['id']).' AND locations.container_stock ')) {
            addcolumn('toggle', 'In Stockroom?', 'stockincontainer', ['do' => 'togglecontainer']);
        }

        addbutton('export', 'Export', ['icon' => 'fa-download', 'showalways' => true]);

        foreach ($data as $d) {
            $count += $d['items'];
        }
        if ($_SESSION['camp']['market']) {
            $cmsmain->assign('listfooter', ['', '', '', $count, '', '']);
        } else {
            $cmsmain->assign('listfooter', ['', '', '', $count, '']);
        }

        listsetting('allowsort', true);
        listsetting('allowcopy', false);
        listsetting('allowshowhide', false);
        listsetting('add', 'Add a product');
        listsetting('delete', 'Delete');

        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    } else {
        switch ($_POST['do']) {
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

                break;
            case 'show':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listShowHide($table, $ids, 1);

                break;
            case 'togglecontainer':
                list($success, $message, $redirect, $newvalue) = listSwitch($table, 'stockincontainer', $_POST['id']);

                break;
            case 'export':
                $success = true;
                $redirect = '?action=products_export&ids='.$_POST['ids'];

                break;
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect, 'newvalue' => $newvalue];

        echo json_encode($return);
        die();
    }
