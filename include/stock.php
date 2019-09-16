<?php

    $table = $action;
    $ajax = checkajax();

    if (!$ajax) {
        initlist();

        $cmsmain->assign('title', 'Boxes');
        listsetting('search', ['box_id', 'l.label', 's.label', 'g.label', 'p.name', 'stock.comments']);

        listfilter(['label' => 'By location', 'query' => 'SELECT id, label FROM locations WHERE camp_id = '.$_SESSION['camp']['id'].' ORDER BY seq', 'filter' => 'l.id']);

        $statusarray = ['showall' => 'All boxes', 'ordered' => 'Ordered boxes', 'dispose' => 'Untouched for 3 month'];
        listfilter2(['label' => 'Only active boxes', 'options' => $statusarray, 'filter' => '"show"']);

        $genders = db_simplearray('SELECT id AS value, label FROM genders ORDER BY seq');
        listfilter3(['label' => 'Gender', 'options' => $genders, 'filter' => '"s.gender_id"']);

        $itemlist = db_simplearray('SELECT pc.id, pc.label from products AS p INNER JOIN product_categories AS pc ON pc.id = p.category_id WHERE (camp_id = '.$_SESSION['camp']['id'].')');
        listfilter4(['label' => 'Category', 'options' => $itemlist, 'filter' => 'p.category_id']);
        listsetting('manualquery', true);

        //dump($listconfig);
        $data = getlistdata('SELECT stock.*, cu.naam AS ordered_name, cu2.naam AS picked_name, SUBSTRING(stock.comments,1, 25) AS shortcomment, g.label AS gender, p.name AS product, s.label AS size, l.label AS location, IF(DATEDIFF(now(),stock.modified) > 90,1,0) AS oldbox ,
		IF(NOT l.visible OR stock.ordered OR stock.ordered IS NOT NULL OR l.container_stock,True,False) AS disableifistrue
		FROM '.$table.'
			LEFT OUTER JOIN cms_users AS cu ON cu.id = stock.ordered_by
			LEFT OUTER JOIN cms_users AS cu2 ON cu2.id = stock.picked_by
			LEFT OUTER JOIN products AS p ON p.id = stock.product_id
			LEFT OUTER JOIN locations AS l ON l.id = stock.location_id
			LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
			LEFT OUTER JOIN sizes AS s ON s.id = stock.size_id
		WHERE l.camp_id = '.$_SESSION['camp']['id'].

        ($listconfig['searchvalue'] ? ' AND (box_id LIKE "%'.$listconfig['searchvalue'].'%" OR l.label LIKE "%'.$listconfig['searchvalue'].'%" OR s.label LIKE "%'.$listconfig['searchvalue'].'%" OR g.label LIKE "%'.$listconfig['searchvalue'].'%" OR p.name LIKE "%'.$listconfig['searchvalue'].'%" OR stock.comments LIKE "%'.$listconfig['searchvalue'].'%")' : '').

        ('ordered' == $_SESSION['filter2']['stock'] ? ' AND (stock.ordered OR stock.picked) AND l.visible' : ('dispose' == $_SESSION['filter2']['stock'] ? ' AND DATEDIFF(now(),stock.modified) > 90 AND l.visible' : (!$_SESSION['filter2']['stock'] ? ' AND l.visible' : ''))).

        ($_SESSION['filter3']['stock'] ? ' AND (p.gender_id = '.intval($_SESSION['filter3']['stock']).')' : '').

        ($_SESSION['filter']['stock'] ? ' AND (stock.location_id = '.$_SESSION['filter']['stock'].')' : '').
        ($_SESSION['filter4']['stock'] ? ' AND (p.category_id = '.$_SESSION['filter4']['stock'].')' : ''));

        foreach ($data as $key => $value) {
            /*
                        if($data[$key]['oldbox']) {
                            $data[$key]['oldbox'] = '<span class="hide">1</span><i class="fa fa-exclamation-triangle warning tooltip-this" title="This box hasn\'t been touched in 3 months or more and may be disposed"></i>';
                        } else {
                            $data[$key]['oldbox'] ='<span class="hide">0</span>';
                        }
            */
            if ($data[$key]['ordered']) {
                $data[$key]['order'] = '<span class="hide">1</span><i class="fa fa-shopping-cart tooltip-this" title="This box is ordered for the shop by '.$data[$key]['ordered_name'].' on '.strftime('%d-%m-%Y', strtotime($data[$key]['ordered'])).'"></i>';
            } elseif ($data[$key]['picked']) {
                $data[$key]['order'] = '<span class="hide">2</span><i class="fa fa-truck green tooltip-this" title="This box is picked for the shop by '.$data[$key]['picked_name'].' on '.strftime('%d-%m-%Y', strtotime($data[$key]['picked'])).'"></i>';
            } else {
                $data[$key]['order'] = '<span class="hide">0</span>';
            }
        }

        foreach ($data as $key => $d) {
            ++$totalboxes;
            $totalitems += $d['items'];
        }

        addcolumn('text', 'Box ID', 'box_id');
        addcolumn('text', 'Product', 'product');
        addcolumn('text', 'Gender', 'gender');
        addcolumn('text', 'Size', 'size');
        addcolumn('text', 'Comments', 'shortcomment');
        addcolumn('text', 'Items', 'items');
        addcolumn('text', 'Location', 'location');
        // 		addcolumn('html','&nbsp;','oldbox');
        addcolumn('html', '&nbsp;', 'order');

        listsetting('allowsort', true);
        listsetting('allowcopy', false);
        listsetting('add', 'Add');

        $locations = db_simplearray('SELECT id, label FROM locations WHERE camp_id = '.$_SESSION['camp']['id'].' ORDER BY seq');
        addbutton('movebox', 'Move', ['icon' => 'fa-truck', 'options' => $locations]);
        addbutton('qr', 'Make label', ['icon' => 'fa-print']);
        addbutton('order', 'Order from warehouse', ['icon' => 'fa-shopping-cart', 'disableif' => true]);
        addbutton('undo-order', 'Undo order', ['icon' => 'fa-undo']);

        addbutton('export', 'Export', ['icon' => 'fa-download', 'showalways' => true]);

        $cmsmain->assign('firstline', ['Total', '', '', '', $totalboxes.' boxes', $totalitems.' items', '', '']);
        $cmsmain->assign('listfooter', ['Total', '', '', '', $totalboxes.' boxes', $totalitems.' items', '', '']);

        //dump($data);

        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    } else {
        switch ($_POST['do']) {
            case 'movebox':
                $ids = explode(',', $_POST['ids']);
                foreach ($ids as $id) {
                    $box = db_row('SELECT * FROM stock WHERE id = :id', ['id' => $id]);

                    db_query('UPDATE stock SET modified = NOW(), modified_by = '.$_SESSION['user']['id'].', ordered = NULL, ordered_by = NULL, picked = NULL, picked_by = NULL, location_id = :location WHERE id = :id', ['location' => $_POST['option'], 'id' => $id]);
                    $from['int'] = $box['location_id'];
                    $to['int'] = $_POST['option'];
                    simpleSaveChangeHistory('stock', $id, 'location_id', $from, $to);

                    if ($box['location_id'] != $_POST['option']) {
                        db_query('INSERT INTO itemsout (product_id, size_id, count, movedate, from_location, to_location) VALUES ('.$box['product_id'].','.$box['size_id'].','.$box['items'].',NOW(),'.$box['location_id'].','.$_POST['option'].')');
                    }

                    ++$count;
                }
                $success = $count;
                $message = (1 == $count ? '1 box is' : $count.' boxes are').' moved';
                $redirect = '?action='.$_GET['action'];

                break;
            case 'order':
                $ids = explode(',', $_POST['ids']);
                foreach ($ids as $id) {
                    db_query('UPDATE stock SET ordered = NOW(), ordered_by = :user, picked = NULL, picked_by = NULL WHERE id = '.intval($id), ['user' => $_SESSION['user']['id']]);
                    simpleSaveChangeHistory('stock', intval($id), 'Box ordered to shop ');
                    $message = 'Boxes are marked as ordered for you!';
                    $success = true;
                    $redirect = true;
                }

                break;
            case 'undo-order':
                $ids = explode(',', $_POST['ids']);
                foreach ($ids as $id) {
                    db_query('UPDATE stock SET ordered = NULL, ordered_by = NULL, picked = NULL, picked_by = NULL  WHERE id = '.$id);
                    simpleSaveChangeHistory('stock', $id, 'Box order made undone ');
                    $message = 'Boxes are unmarked as ordered';
                    $success = true;
                    $redirect = true;
                }

                break;
            case 'qr':
                $id = $_POST['ids'];
                $redirect = '/pdf/qr.php?label='.$id;

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

                break;
            case 'show':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listShowHide($table, $ids, 1);

                break;
            case 'export':
                list($success, $message, $redirect) = [true, '', '?action=stock_export&ids='.$_POST['ids']];

                break;
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

        echo json_encode($return);
        die();
    }
