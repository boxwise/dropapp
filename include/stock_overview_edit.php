<?php

    $table = 'stock';
    $action = 'stock';
    $ajax = checkajax();

    if (!$ajax) {
        initlist();

        [$boxstate, $locationfromfilter, $category, $product, $gender, $size, $location] = explode('-', $_GET['id']);

        $productname = ($product ? db_value('SELECT name FROM products WHERE id = :id AND camp_id = :camp_id AND (NOT deleted OR deleted IS NULL)', ['id' => $product, 'camp_id' => $_SESSION['camp']['id']]) : '');
        $cmsmain->assign('title', ($boxstate ? db_value('SELECT label FROM box_state WHERE id = :id', ['id' => $boxstate]).' ' : '').'Boxes for: '.
            db_value('SELECT label FROM product_categories WHERE id = :id', ['id' => $category]).
            ($product ? ', '.$productname : '').
            ($gender ? ', '.db_value('SELECT label FROM genders WHERE id = :id', ['id' => $gender]) : '').
            ($size ? ', '.db_value('SELECT label FROM sizes WHERE id = :id', ['id' => $size]) : '').
            ($location ? ', '.db_value('SELECT label FROM locations WHERE id = :id AND camp_id = :camp_id AND type = "Warehouse"', ['id' => $location, 'camp_id' => $_SESSION['camp']['id']]) :
                (0 != $locationfromfilter ? ', '.db_value('SELECT label FROM locations WHERE id = :id AND camp_id = :camp_id AND type = "Warehouse"', ['id' => $locationfromfilter, 'camp_id' => $_SESSION['camp']['id']]) : ' ')));

        $data = getlistdata('
        SELECT 	
            stock_filtered.*,
            GROUP_CONCAT(tags.label ORDER BY tags.seq  SEPARATOR 0x1D) AS taglabels,
            GROUP_CONCAT(tags.color ORDER BY tags.seq) AS tagcolors
        FROM
            (SELECT
        		stock.*,
        		SUBSTRING(stock.comments,1, 25) AS shortcomment, 
                g.label AS gender,
        		p.name AS product,
        		s.label AS size,
        		l.label AS location,
        		stock.box_state_id IN (3,4,7,8) AS disableifistrue,
        		IF(DATEDIFF(now(),stock.created) = 1, "1 day", CONCAT(DATEDIFF(now(),stock.created), " days")) AS boxage
        	FROM
        		(product_categories AS pc,
                products AS p,
        		locations AS l,
        		genders AS g,
        		sizes AS s,
        		stock)
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
                ($location ? 'l.id = '.intval($location).' AND ' :
                    (0 != $locationfromfilter ? 'l.id = '.intval($locationfromfilter).' AND ' : '')).
                ($boxstate ? 'stock.box_state_id='.db_value('SELECT id FROM box_state WHERE id = :id', ['id' => $boxstate]).' AND ' : '').
                '(NOT stock.deleted OR stock.deleted IS NULL)) AS stock_filtered
            LEFT JOIN 
                tags_relations ON tags_relations.object_id = stock_filtered.id AND tags_relations.object_type = "Stock"
            LEFT JOIN
                tags ON tags.id = tags_relations.tag_id AND tags.deleted IS NULL AND tags.camp_id = '.$_SESSION['camp']['id'].'
            GROUP BY
                stock_filtered.id');

        foreach ($data as $key => $value) {
            if (3 == $data[$key]['box_state_id']) {
                $data[$key]['order'] = '<span class="hide">1</span><i class="fa fa-truck tooltip-this" title="This box is marked for a shipment."></i>';
            } elseif (in_array(intval($data[$key]['box_state_id']), [4, 7])) {
                $data[$key]['order'] = '<span class="hide">2</span><i class="fa fa-truck green tooltip-this" title="This box is being shipped."></i>';
            } elseif (in_array(intval($data[$key]['box_state_id']), [2, 6])) {
                $modifiedtext = $data[$key]['modified'] ? 'on '.strftime('%d-%m-%Y', strtotime($data[$key]['modified'])) : '';
                $icon = 2 === intval($data[$key]['box_state_id']) ? 'fa-ban' : 'fa-chain-broken';
                $statelabel = 2 === intval($data[$key]['box_state_id']) ? 'lost' : 'scrapped';
                $data[$key]['order'] = sprintf('<span class="hide">3</span><i class="fa %s tooltip-this" style="color: red" title="This box was %s %s"></i>', $icon, $statelabel, $modifiedtext);
            } else {
                $data[$key]['order'] = '<span class="hide">0</span>';
            }

            if ($data[$key]['taglabels']) {
                $taglabels = explode(chr(0x1D), $data[$key]['taglabels']);
                $tagcolors = explode(',', $data[$key]['tagcolors']);
                foreach ($taglabels as $tagkey => $taglabel) {
                    $data[$key]['tags'][$tagkey] = ['label' => $taglabel, 'color' => $tagcolors[$tagkey], 'textcolor' => get_text_color($tagcolors[$tagkey])];
                }
            }
        }

        addcolumn('text', 'Box ID', 'box_id');
        addcolumn('text', 'Product', 'product');
        addcolumn('text', 'Gender', 'gender');
        addcolumn('text', 'Size', 'size');
        addcolumn('tag', 'Tags', 'tags');
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
