<?php

$table = 'stock';
$action = 'container-stock';
$ajax = checkajax();

if (!$ajax) {
    initlist();

    [$product, $gender, $size, $color, $overunder] = explode('-', (string) $_GET['id']);

    $listconfig['origin'] = $action.'&id='.$_GET['id'];

    $cmsmain->assign('title', 'Boxes for: '.db_value('SELECT name FROM products WHERE id = :id', ['id' => $product]).', '.db_value('SELECT label FROM genders WHERE id = :id', ['id' => $gender]).', '.db_value('SELECT label FROM sizes WHERE id = :id', ['id' => $size]).' <div class="need-indicator need-'.$color.'"><i class="fa fa-'.('red' == $color ? 'sign-in' : ('blue' == $color ? 'sign-out' : 'check')).'"></i>&nbsp;'.('green' != $color ? $overunder : '').'</div>');

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
				l.camp_id = '.$_SESSION['camp']['id'].' AS visible,
				l.camp_id != '.$_SESSION['camp']['id'].' AS preventdelete,
				l.camp_id != '.$_SESSION['camp']['id'].' AS preventedit,
				stock.box_state_id IN (3,4,7,8) AS disableifistrue,
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
				AND stock.box_state_id = 1) AS stock_filtered
        LEFT JOIN 
            tags_relations ON tags_relations.object_id = stock_filtered.id AND tags_relations.object_type = "Stock"
        LEFT JOIN
            tags ON tags.id = tags_relations.tag_id AND tags.deleted IS NULL AND tags.camp_id = '.$_SESSION['camp']['id'].'
        GROUP BY
            stock_filtered.id ');

    foreach ($data as $key => $value) {
        if (3 == $data[$key]['box_state_id']) {
            $data[$key]['order'] = '<span class="hide">1</span><i class="fa fa-truck tooltip-this" title="This box is marked for a shipment."></i>';
        } elseif (in_array(intval($data[$key]['box_state_id']), [4, 7])) {
            $data[$key]['order'] = '<span class="hide">2</span><i class="fa fa-truck green tooltip-this" title="This box is being shipped."></i>';
        } elseif (in_array(intval($data[$key]['box_state_id']), [2, 6])) {
            $modifiedtext = $data[$key]['modified'] ? 'on '.strftime('%d-%m-%Y', strtotime((string) $data[$key]['modified'])) : '';
            $icon = 2 === intval($data[$key]['box_state_id']) ? 'fa-ban' : 'fa-chain-broken';
            $statelabel = 2 === intval($data[$key]['box_state_id']) ? 'lost' : 'scrapped';
            $data[$key]['order'] = sprintf('<span class="hide">3</span><i class="fa %s tooltip-this" style="color: red" title="This box was %s %s"></i>', $icon, $statelabel, $modifiedtext);
        } else {
            $data[$key]['order'] = '<span class="hide">0</span>';
        }

        if ($data[$key]['taglabels']) {
            $taglabels = explode(chr(0x1D), (string) $data[$key]['taglabels']);
            $tagcolors = explode(',', (string) $data[$key]['tagcolors']);
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
            $ids = explode(',', (string) $_POST['ids']);

            [$count, $message] = move_boxes($ids, $_POST['option']);

            $success = $count;
            $redirect = true;

            break;
    }

    $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

    echo json_encode($return);

    exit;
}
