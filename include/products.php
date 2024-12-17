<?php

$table = $action;
$ajax = checkajax();
if (!$ajax) {
    initlist();

    $cmsmain->assign('title', 'Products');
    listsetting('search', ['name', 'g.label', 'products.comments']);

    listfilter(['label' => 'By category', 'query' => 'SELECT id, label FROM product_categories ORDER BY seq', 'filter' => 'products.category_id']);

    $data = getlistdata('
            SELECT 
                products.*, 
                sg.label AS sizegroup, 
                g.label AS gender, 
                CONCAT(products.value," '.$_SESSION['camp']['currencyname'].'") AS drops, 
                COALESCE(SUM(s.items),0) AS items, 
                IF(SUM(s.items),1,0) AS preventdelete 
            FROM products
			LEFT OUTER JOIN genders AS g ON g.id = products.gender_id
			LEFT OUTER JOIN sizegroup AS sg ON sg.id = products.sizegroup_id
			LEFT OUTER JOIN stock AS s 
                ON s.product_id = products.id AND 
                NOT s.deleted AND 
                s.box_state_id = 1
			WHERE 
                (NOT products.deleted OR products.deleted IS NULL) AND 
                products.camp_id = '.intval($_SESSION['camp']['id']).'
			GROUP BY products.id
		');

    foreach ($data as $d) {
        $count += $d['items'];
    }

    addcolumn('text', 'Product name', 'name');
    addcolumn('text', 'Gender', 'gender');
    addcolumn('text', 'Sizegroup', 'sizegroup');
    if ($count) {
        addcolumn('text', 'Items', 'items');
    }
    if ($_SESSION['camp']['market']) {
        addcolumn('text', ucfirst((string) $_SESSION['camp']['currencyname']), 'drops');
    }
    addcolumn('text', 'Description', 'comments');
    if (db_value('SELECT id FROM locations WHERE camp_id = '.intval($_SESSION['camp']['id']).' AND container_stock AND type = "Warehouse"') || $_SESSION['camp']['separateshopandwhproducts']) {
        addcolumn('toggle', 'Pin in Stockroom?', 'stockincontainer', ['do' => 'togglecontainer']);
    }

    addbutton('export', 'Export', ['icon' => 'fa-download', 'showalways' => false]);

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

        case 'togglecontainer':
            [$success, $message, $redirect, $newvalue] = listSwitch($table, 'stockincontainer', $_POST['id']);

            break;

        case 'export':
            $success = true;
            $_SESSION['export_ids_products'] = $_POST['ids'];
            $redirect = '?action=products_export';

            break;
    }

    $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect, 'newvalue' => $newvalue];

    echo json_encode($return);

    exit;
}
