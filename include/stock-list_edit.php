<?php

    $table = 'stock';
    $action = 'stock';
    $ajax = checkajax();

    if (!$ajax) {
        initlist();

        list($product, $gender, $size, $color, $overunder) = explode('-', $_GET['id']);

        $listconfig['origin'] = $action.'&id='.$_GET['id'];

        $cmsmain->assign('title', 'Boxes for: '.db_value('SELECT name FROM products WHERE id = :id', ['id' => $product]).', '.db_value('SELECT label FROM genders WHERE id = :id', ['id' => $gender]).', '.db_value('SELECT label FROM sizes WHERE id = :id', ['id' => $size]).' <div class="need-indicator need-'.$color.'"><i class="fa fa-'.('red' == $color ? 'sign-in' : ('blue' == $color ? 'sign-out' : 'check')).'"></i>&nbsp;'.('green' != $color ? $overunder : '').'</div>');

        $data = getlistdata('
			SELECT 
				stock.*, 
				SUBSTRING(stock.comments,1, 25) AS shortcomment, cu.naam AS ordered_name, cu2.naam AS picked_name, 
				g.label AS gender, 
				p.name AS product, 
				s.label AS size, 
				IF(l.camp_id = '.$_SESSION['camp']['id'].',l.label,c.name) AS location, 
				l.camp_id = '.$_SESSION['camp']['id'].' AS visible,
				l.camp_id != '.$_SESSION['camp']['id'].' AS preventdelete,
				l.camp_id != '.$_SESSION['camp']['id'].' AS preventedit,
				IF(NOT l.visible OR stock.ordered OR stock.ordered IS NOT NULL OR l.container_stock,True,False) AS disableifistrue,
				IF(DATEDIFF(now(),stock.modified) > 90,1,0) AS oldbox
			FROM 
				(products AS p, 
				locations AS l, 
				genders AS g, 
				sizes AS s, 
				stock,
				camps AS c)
			LEFT OUTER JOIN cms_users AS cu ON cu.id = stock.ordered_by
			LEFT OUTER JOIN cms_users AS cu2 ON cu2.id = stock.picked_by
			WHERE 
				l.camp_id = c.id AND 
				stock.size_id = s.id AND 
				p.gender_id = g.id AND 
				stock.product_id = p.id AND 
				p.name = (SELECT name FROM products WHERE id = '.intval($product).') AND 
				p.gender_id = '.intval($gender).' '.
                ($size ? ' AND s.id = '.intval($size) : '').' AND 
				(NOT stock.deleted OR stock.deleted IS NULL) AND 
				stock.location_id = l.id 
				AND l.visible');

        foreach ($data as $key => $value) {
            if ($data[$key]['oldbox']) {
                $data[$key]['oldbox'] = '<span class="hide">1</span><i class="fa fa-exclamation-triangle warning tooltip-this" title="This box hasn\'t been touched in 3 months or more and may be disposed"></i>';
            } else {
                $data[$key]['oldbox'] = '<span class="hide">0</span>';
            }
            if ($data[$key]['ordered']) {
                $data[$key]['order'] = '<span class="hide">1</span><i class="fa fa-shopping-cart tooltip-this" title="This box is ordered for the shop by '.$data[$key]['ordered_name'].' on '.strftime('%d-%m-%Y', strtotime($data[$key]['ordered'])).'"></i>';
            } elseif ($data[$key]['picked']) {
                $data[$key]['order'] = '<span class="hide">2</span><i class="fa fa-truck green tooltip-this" title="This box is picked for the shop by '.$data[$key]['picked_name'].' on '.strftime('%d-%m-%Y', strtotime($data[$key]['picked'])).'"></i>';
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
        addcolumn('html', '&nbsp;', 'oldbox');
        addcolumn('html', '&nbsp;', 'order');

        listsetting('allowsort', true);
        listsetting('allowadd', false);
        listsetting('allowdelete', false);
        listsetting('allowselectall', true);
        listsetting('allowselect', true);
        listsetting('allowselectinvisible', false);

        $locations = db_simplearray('SELECT id, label FROM locations WHERE camp_id = '.$_SESSION['camp']['id'].' ORDER BY seq');
        addbutton('movebox', 'Move', ['icon' => 'fa-truck', 'options' => $locations]);
        addbutton('order', 'Order from warehouse', ['icon' => 'fa-shopping-cart', 'disableif' => true]);
        addbutton('undo-order', 'Undo order', ['icon' => 'fa-undo']);

        //$locations = db_simplearray('SELECT id, label FROM locations ORDER BY seq');
        //addbutton('movebox','Move box',array('icon'=>'fa-arrows', 'options'=>$locations));
        //addbutton('qr','Make label',array('icon'=>'fa-print','oneitemonly'=>true));

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
                $redirect = true;

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
                    db_query('UPDATE stock SET ordered = NULL, ordered_by = NULL WHERE id = '.$id);
                    simpleSaveChangeHistory('stock', $id, 'Box order made undone ');
                    $message = 'Boxes are unmarked as ordered';
                    $success = true;
                    $redirect = true;
                }

                break;
            case 'qr':
                $id = $_POST['ids'];
                $boxid = db_value('SELECT box_id FROM stock WHERE id = :id', ['id' => $id]);
                $success = true;
                $message = '';
                $redirect = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://'.$_SERVER['HTTP_HOST'].'/mobile.php?barcode='.$boxid;

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
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

        echo json_encode($return);
        die();
    }
