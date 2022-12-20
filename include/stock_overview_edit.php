<?php

    $table = 'stock';
    $action = 'stock';
    $ajax = checkajax();

    if (!$ajax) {
        initlist();

        list($category, $product, $gender, $size, $location) = explode('-', $_GET['id']);

        $productname = ($product ? db_value('SELECT name FROM products WHERE id = :id AND camp_id = :camp_id AND (NOT deleted OR deleted IS NULL)', ['id' => $product, 'camp_id' => $_SESSION['camp']['id']]) : '');
        $cmsmain->assign('title', 'Boxes for: '.
            db_value('SELECT label FROM product_categories WHERE id = :id', ['id' => $category]).
            ($product ? ', '.$productname : '').
            ($gender ? ', '.db_value('SELECT label FROM genders WHERE id = :id', ['id' => $gender]) : '').
            ($size ? ', '.db_value('SELECT label FROM sizes WHERE id = :id', ['id' => $size]) : '').
            ($location ? ', '.db_value('SELECT label FROM locations WHERE id = :id AND camp_id = :camp_id AND type = "Warehouse"', ['id' => $location, 'camp_id' => $_SESSION['camp']['id']]) : ''));

        $data = getlistdata('
        	SELECT
        		stock.*,
        		SUBSTRING(stock.comments,1, 25) AS shortcomment, cu.naam AS ordered_name, cu2.naam AS picked_name,
        		g.label AS gender,
        		p.name AS product,
        		s.label AS size,
        		l.label AS location,
        		IF(NOT stock.box_state_id IN (2,6,5) OR stock.ordered OR stock.ordered IS NOT NULL OR l.container_stock,True,False) AS disableifistrue,
        		IF(DATEDIFF(now(),stock.created) = 1, "1 day", CONCAT(DATEDIFF(now(),stock.created), " days")) AS boxage
        	FROM
        		(product_categories AS pc,
                products AS p,
        		locations AS l,
        		genders AS g,
        		sizes AS s,
        		stock)
        	LEFT OUTER JOIN cms_users AS cu ON cu.id = stock.ordered_by
        	LEFT OUTER JOIN cms_users AS cu2 ON cu2.id = stock.picked_by
            WHERE
                p.category_id = pc.id AND 
        		l.camp_id = '.$_SESSION['camp']['id'].' AND
                l.type = "Warehouse" AND
        		stock.size_id = s.id AND
        		p.gender_id = g.id AND
                stock.product_id = p.id AND 
                stock.location_id = l.id AND
                pc.id = '.intval($category).' AND '.
                ($product ? 'UPPER(p.name) = UPPER("'.$productname.'") AND ' : '').
                ($gender ? 'g.id = '.intval($gender).' AND ' : '').
                ($size ? 's.id = '.intval($size).' AND ' : '').
                ($location ? 'l.id = '.intval($location).' AND ' : '').
                '(NOT stock.deleted OR stock.deleted IS NULL) AND 
                stock.box_state_id NOT IN (2,6,5) ');

        foreach ($data as $key => $value) {
            if ($data[$key]['ordered']) {
                $data[$key]['order'] = '<span class="hide">1</span><i class="fa fa-shopping-cart tooltip-this" title="This box is ordered by '.$data[$key]['ordered_name'].' on '.strftime('%d-%m-%Y', strtotime($data[$key]['ordered'])).'"></i>';
            } elseif ($data[$key]['picked']) {
                $data[$key]['order'] = '<span class="hide">2</span><i class="fa fa-truck green tooltip-this" title="This box is picked by '.$data[$key]['picked_name'].' on '.strftime('%d-%m-%Y', strtotime($data[$key]['picked'])).'"></i>';
            } else {
                $data[$key]['order'] = '<span class="hide">0</span>';
            }
        }

        addcolumn('text', 'Box ID', 'box_id');
        addcolumn('text', 'Product', 'product');
        addcolumn('text', 'Gender', 'gender');
        addcolumn('text', 'Size', 'size');
        addcolumn('text', 'Comments', 'shortcomment');
        addcolumn('text', 'Items', 'items');
        addcolumn('text', 'Location', 'location');
        addcolumn('text', 'Age', 'boxage');
        addcolumn('html', '&nbsp;', 'order');

        listsetting('allowsort', true);
        listsetting('allowadd', false);
        listsetting('allowdelete', false);
        listsetting('allowselectall', true);
        listsetting('allowselect', true);
        listsetting('allowselectinvisible', true);

        $locations = db_simplearray('
            SELECT 
                l.id, 
                IF(l.box_state_id <> 1, concat(l.label, " - Boxes are ", bs.label), l.label) AS label 
            FROM locations l
            INNER JOIN box_state bs ON l.box_state_id=bs.id
            WHERE 
                l.deleted IS NULL AND 
                l.camp_id = '.$_SESSION['camp']['id'].' AND 
                type = "Warehouse" 
            ORDER BY seq');
        addbutton('movebox', 'Move', ['icon' => 'fa-truck', 'options' => $locations]);
        addbutton('order', 'Order from warehouse', ['icon' => 'fa-shopping-cart', 'disableif' => true]);
        addbutton('undo-order', 'Undo order', ['icon' => 'fa-undo']);

        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    } else {
        switch ($_POST['do']) {
            case 'movebox':
                $ids = explode(',', $_POST['ids']);

                [$count, $message] = move_boxes($ids, $_POST['option']);

                $success = $count;
                $redirect = true;

                break;

            case 'order':
                $ids = explode(',', $_POST['ids']);
                foreach ($ids as $id) {
                    db_query('UPDATE stock SET ordered = NOW(), ordered_by = :user, picked = NULL, picked_by = NULL WHERE id = '.intval($id), ['user' => $_SESSION['user']['id']]);
                    simpleSaveChangeHistory('stock', intval($id), 'Box is ordered.');
                    $message = 'Boxes are marked as ordered!';
                    $success = true;
                    $redirect = true;
                }

                break;

            case 'undo-order':
                $ids = explode(',', $_POST['ids']);
                foreach ($ids as $id) {
                    db_query('UPDATE stock SET ordered = NULL, ordered_by = NULL WHERE id = '.$id);
                    simpleSaveChangeHistory('stock', $id, 'Box order is undone.');
                    $message = 'Boxes are unmarked as ordered!';
                    $success = true;
                    $redirect = true;
                }

                break;
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

        echo json_encode($return);

        exit();
    }
