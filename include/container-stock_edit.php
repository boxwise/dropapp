<?php

    $table = 'stock';
    $action = 'container-stock';
    $ajax = checkajax();

    if (!$ajax) {
        initlist();

        list($product, $gender, $size, $color, $overunder) = explode('-', $_GET['id']);

        $listconfig['origin'] = $action.'&id='.$_GET['id'];

        $cmsmain->assign('title', 'Boxes for: '.db_value('SELECT name FROM products WHERE id = :id', ['id' => $product]).', '.db_value('SELECT label FROM genders WHERE id = :id', ['id' => $gender]).', '.db_value('SELECT label FROM sizes WHERE id = :id', ['id' => $size]).' <div class="need-indicator need-'.$color.'"><i class="fa fa-'.('red' == $color ? 'sign-in' : ('blue' == $color ? 'sign-out' : 'check')).'"></i>&nbsp;'.('green' != $color ? $overunder : '').'</div>');

        $data = getlistdata('
			SELECT 
				stock.*, 
				SUBSTRING(stock.comments,1, 25) AS shortcomment, 
                g.label AS gender, 
				p.name AS product, 
				s.label AS size, 
				l.label AS location, 
				l.camp_id = '.$_SESSION['camp']['id'].' AS visible,
				l.camp_id != '.$_SESSION['camp']['id'].' AS preventdelete,
				l.camp_id != '.$_SESSION['camp']['id'].' AS preventedit,
				stock.box_state_id IN (3,4) AS disableifistrue,
				IF(DATEDIFF(now(),stock.created) = 1, "1 day", CONCAT(DATEDIFF(now(),stock.created), " days")) AS boxage
			FROM 
				(products AS p, 
				locations AS l, 
				genders AS g, 
				sizes AS s, 
				stock,
				camps AS c)
			WHERE 
                l.type = "Warehouse" AND
				l.camp_id = c.id AND 
				stock.size_id = s.id AND 
				p.gender_id = g.id AND 
				stock.product_id = p.id AND 
				p.name = (SELECT name FROM products WHERE id = '.intval($product).') AND 
				p.gender_id = '.intval($gender).' '.
                ($size ? ' AND s.id = '.intval($size) : '').' AND 
				(NOT stock.deleted OR stock.deleted IS NULL) AND 
				stock.location_id = l.id AND 
                l.camp_id = '.$_SESSION['camp']['id'].'
				AND stock.box_state_id NOT IN (2,6,5) ');

        foreach ($data as $key => $value) {
            if ($data[$key]['box_state_id'] == 3) {
                // ordered
                $data[$key]['order'] = '<span class="hide">1</span><i class="fa fa-truck tooltip-this" title="This box is marked for a shipment."></i>';
            } elseif ($data[$key]['box_state_id'] == 4) {
                // picked
                $data[$key]['order'] = '<span class="hide">2</span><i class="fa fa-truck green tooltip-this" title="This box is being shipped."></i>';
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
        listsetting('allowedit', false);
        listsetting('allowdelete', false);
        listsetting('allowselectall', true);
        listsetting('allowselect', true);
        listsetting('allowselectinvisible', false);

        $locations = db_simplearray('
            SELECT 
                l.id, 
                IF(l.box_state_id <> 1, concat(l.label," - Boxes are ",bs.label), l.label) as label
            FROM locations l
            INNER JOIN box_state bs ON l.box_state_id=bs.id
            WHERE 
                l.deleted IS NULL AND 
                l.type = "Warehouse" AND 
                l.camp_id = '.$_SESSION['camp']['id'].' 
            ORDER BY seq');
        addbutton('movebox', 'Move', ['icon' => 'fa-truck', 'options' => $locations, 'disableif' => true]);

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
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

        echo json_encode($return);

        exit();
    }
