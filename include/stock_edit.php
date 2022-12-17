<?php

    $table = 'stock';
    $action = 'stock_edit';

    if ($_POST) {
        [$id, $newbox] = db_transaction(function () use ($table) {
            if (!$_POST['box_id']) {
                $newbox = true;
                $limit = 10;
                do {
                    $_POST['box_id'] = generateBoxID();
                    --$limit;
                } while (0 !== $limit && db_value('SELECT COUNT(id) FROM stock WHERE box_id = :box_id', ['box_id' => $_POST['box_id']]));

                if (0 === $limit) {
                    trigger_error('Lookup failed in 10 attempts to find a unique box identifier', E_USER_ERROR);

                    throw new Exception('There is an issue creating box identifier. Please try again in a few minutes', 409);
                }
            }
            $box = db_row('SELECT 
                            stock.*, 
                            bs.id as box_state_id, 
                            bs.label as box_state_name 
                           FROM stock 
                           INNER JOIN box_state bs ON bs.id = stock.box_state_id
                           WHERE stock.id = :id', ['id' => $_POST['id']]);

            // Getting the new box state id based on the location
            $newboxstate = db_row('SELECT bs.id as box_state_id, bs.label as box_state_name FROM locations l INNER JOIN box_state bs ON bs.id = l.box_state_id WHERE l.id = :id', ['id' => $_POST['location_id'][0]]);

            $is_scrap = (!empty($_POST['scrap'][0]) && 1 == $_POST['scrap'][0]);
            $is_lost = (!empty($_POST['lost'][0]) && 1 == $_POST['lost'][0]);

            //  when checked scrap or lost in the form
            if ($is_scrap) {
                $newboxstate['box_state_id'] = 6;
                $newboxstate['box_state_name'] = 'Scrap';
            } elseif ($is_lost) {
                $newboxstate['box_state_id'] = 2;
                $newboxstate['box_state_name'] = 'Lost';
            }

            if ($box && ($box['location_id'] != $_POST['location_id'][0] && !$is_lost && !$is_scrap)) {
                // Boxes should not be relocated to virtual locations
                // related to https://trello.com/c/Ci74t1Wj
                if (in_array($newboxstate['box_state_name'], ['Lost', 'Scrap'])) {
                    $_POST['location_id'][0] = $box['location_id'];
                    if ('Lost' == $newboxstate['box_state_name']) {
                        $is_lost = true;
                    } elseif ('Scrap' == $newboxstate['box_state_name']) {
                        $is_scrap = true;
                    }
                } else {
                    db_query('UPDATE stock SET ordered = NULL, ordered_by = NULL, picked = NULL, picked_by = NULL WHERE id = :id', ['id' => $_POST['id']]);
                    db_query('INSERT INTO itemsout (product_id, size_id, count, movedate, from_location, to_location) VALUES ('.$box['product_id'].','.$box['size_id'].','.$box['items'].',NOW(),'.$box['location_id'].','.$_POST['location_id'][0].')');
                }
            }

            // After a state is changed to scrap or lost, changes are not allowed until the state is changed again
            if (!$is_lost && !$is_scrap) {
                $handler = new formHandler($table);

                $savekeys = ['box_id', 'product_id', 'size_id', 'items', 'location_id', 'comments'];
                $id = $handler->savePost($savekeys);

                db_query('DELETE FROM tags_relations WHERE object_id = :stock_id AND object_type = "Stock"', [':stock_id' => $id]);

                $params = [];
                $tags = $_POST['tags'];
                if (sizeof($tags) > 0) {
                    $query = 'INSERT IGNORE INTO tags_relations (tag_id, object_type, `object_id`) VALUES ';

                    for ($i = 0; $i < sizeof($tags); ++$i) {
                        $query .= "(:tag_id{$i}, 'Stock', :stock_id)";
                        $params = array_merge($params, ['tag_id'.$i => $tags[$i]]);
                        if ($i !== sizeof($tags) - 1) {
                            $query .= ',';
                        }
                    }

                    $params = array_merge($params, ['stock_id' => $id]);
                    db_query($query, $params);
                }
            }

            // Update the box state if the state changes
            if (!$newbox && $newboxstate['box_state_id'] != $box['box_state_id']) {
                db_query('UPDATE stock SET box_state_id = :box_state_id, ordered = NULL, ordered_by = NULL, picked = NULL, picked_by = NULL, modified = NOW(), modified_by = :user_id WHERE id = :id', ['box_state_id' => $newboxstate['box_state_id'],  'id' => $_POST['id'], 'user_id' => $_SESSION['user']['id']]);
                simpleSaveChangeHistory('stock', $box['id'], 'changed box state from '.$box['box_state_name'].' to '.$newboxstate['box_state_name']);
            } elseif ($newbox && 'Instock' !== $newboxstate['box_state_name']) {
                db_query('UPDATE stock SET box_state_id = :box_state_id, ordered = NULL, ordered_by = NULL, picked = NULL, picked_by = NULL, modified = NOW(), modified_by = :user_id WHERE id = :id', ['box_state_id' => $newboxstate['box_state_id'],  'id' => $id, 'user_id' => $_SESSION['user']['id']]);
                simpleSaveChangeHistory('stock', $box['id'], 'changed box state to '.$newboxstate['box_state_name']);
            }

            return [$id, $newbox];
        });

        if ('submitandnew' == $_POST['__action']) {
            $created = true;
            redirect('?action=stock_edit&created='.$created.'&created_id='.$id);
        }

        if ($newbox) {
            redirect('?action=stock_confirm&id='.$id);
        } else {
            redirect('?action='.$_POST['_origin']);
        }
    }

    if (1 == $_GET['created']) {
        $smarty = new Zmarty();
        $box = db_row('
            SELECT 
                s.*, 
                CONCAT(p.name," ",g.label) AS product, 
                l.label AS location,
                GROUP_CONCAT(tags.label ORDER BY tags.seq SEPARATOR 0x1D) AS taglabels,
                GROUP_CONCAT(tags.color ORDER BY tags.seq) AS tagcolors,
                bs.label AS statelabel,
                bs.id AS stateid
            FROM stock AS s 
                LEFT OUTER JOIN box_state AS bs ON bs.id = stock.box_state_id
                LEFT OUTER JOIN products AS p ON p.id = s.product_id 
                LEFT OUTER JOIN genders AS g ON g.id = p.gender_id 
                LEFT OUTER JOIN locations AS l ON l.id = s.location_id
                LEFT JOIN tags_relations ON tags_relations.object_id = s.id AND tags_relations.object_type = "Stock"
                LEFT JOIN tags ON tags.id = tags_relations.tag_id AND tags.deleted IS NULL
            WHERE (NOT s.deleted OR s.deleted IS NULL) AND s.id = :id', ['id' => $_GET['created_id']]);
        $smarty->assign('box', $box);
        $htmlaside = $smarty->fetch('stock_confirm_new.tpl');
        addfield('html', '', $htmlaside);
    }

    $data = db_row('SELECT 
                        stock.*, 
                        CONCAT(p.name," ",g.label) AS product, 
                        l.label AS location,
                        l.type As locationType,
                        GROUP_CONCAT(tags.label ORDER BY tags.seq SEPARATOR 0x1D) AS taglabels,
                        GROUP_CONCAT(tags.color ORDER BY tags.seq) AS tagcolors,
                        DATE_FORMAT(stock.modified,"%Y/%m/%d") AS statemodified,
                        bs.label AS statelabel,                        
                        bs.id as stateid
                    FROM stock 
                        INNER JOIN box_state AS bs ON bs.id = stock.box_state_id
                        LEFT OUTER JOIN products AS p ON p.id = stock.product_id 
                        LEFT OUTER JOIN genders AS g ON g.id = p.gender_id 
                        LEFT OUTER JOIN locations AS l ON l.id = stock.location_id
                        LEFT JOIN tags_relations ON tags_relations.object_id = stock.id AND tags_relations.object_type = "Stock"
                        LEFT JOIN tags ON tags.id = tags_relations.tag_id AND tags.deleted IS NULL
                    WHERE (NOT stock.deleted OR stock.deleted IS NULL) AND stock.id = :id', ['id' => $id]);

    if ($data['taglabels']) {
        $taglabels = explode(chr(0x1D), $data['taglabels']);
        $tagcolors = explode(',', $data['tagcolors']);
        foreach ($taglabels as $tagkey => $taglabel) {
            $data['tags'][$tagkey] = ['label' => $taglabel, 'color' => $tagcolors[$tagkey], 'textcolor' => get_text_color($tagcolors[$tagkey])];
        }
    }

    if ($id) {
        mobile_distro_check($data['locationType'], false);
    }
    verify_campaccess_location($data['location_id']);

    if (!$id) {
        $data['visible'] = 1;
    }

    $disabled = false;
    if (in_array($data['statelabel'], ['Lost', 'Scrap'])) {
        $disabled = true;
        $data['scrap'] = (in_array($data['statelabel'], ['Scrap']));
        $disabled_scrap = (in_array($data['statelabel'], ['Lost']));
        $data['lost'] = (in_array($data['statelabel'], ['Lost']));
        $disabled_lost = (in_array($data['statelabel'], ['Scrap']));
    }
    // open the template
    $cmsmain->assign('include', 'cms_form.tpl');
    addfield('hidden', '', 'id');
    // put a title above the form
    $cmsmain->assign('titlewithtags', 'Box ');
    // $cmsmain->assign('title', 'Box');

    if ($id) {
        addfield('text', 'Box ID', 'box_id', ['readonly' => true, 'width' => 2]);
        addfield('line');
    }

    addfield('select', 'Location', 'location_id', ['required' => true,  'multiple' => false,  'onchange' => 'getNewBoxState();',
    'query' => 'SELECT 
                    l.id AS value, 
                    if(l.box_state_id <> '.$data['stateid'].', concat(l.label," -  Boxes are ",bs.label),l.label) as label
                FROM
                    locations l
                    LEFT OUTER JOIN box_state bs ON bs.id = l.box_state_id
                WHERE
                    l.deleted IS NULL AND l.camp_id =  '.$_SESSION['camp']['id'].' 
                        AND l.type = "Warehouse" ORDER BY seq', ]);

    addfield('select', 'Product', 'product_id', ['disabled' => $disabled, 'test_id' => 'product_id', 'required' => true, 'multiple' => false, 'query' => 'SELECT p.id AS value, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS label FROM products AS p LEFT OUTER JOIN genders AS g ON p.gender_id = g.id WHERE (NOT p.deleted OR p.deleted IS NULL) AND p.camp_id = '.$_SESSION['camp']['id'].($_SESSION['camp']['separateshopandwhproducts'] ? ' AND NOT p.stockincontainer' : '').' ORDER BY name', 'onchange' => 'getSizes()']);

    addfield('select', 'Size', 'size_id', ['disabled' => $disabled, 'required' => true, 'width' => 2, 'multiple' => false, 'query' => 'SELECT *, id AS value FROM sizes WHERE sizegroup_id = '.intval(db_value('SELECT sizegroup_id FROM products WHERE id = :id', ['id' => $data['product_id']])).' ORDER BY seq', 'tooltip' => 'If the right size for your box is not here, don\'t put it in comments, but first double check if you have the right product. For example: Long sleeves for babies, we call them tops.']);

    addfield('number', 'Items', 'items', ['testid' => 'items_id', 'readonly' => $disabled]);

    if ($data['qr_id']) {
        $qr = db_row('SELECT code, legacy FROM qr WHERE id = :id', ['id' => $data['qr_id']]);

        $qrPng = generateQrPng($qr['code'], $qr['legacy'])[0];

        addfield('html', '', '<img src="'.$qrPng.'" /><br /><br />', ['aside' => true, 'asidetop' => true]);
    }

    addfield('line');
    addfield('select', 'Tag(s)', 'tags', [
        'testid' => 'tag_id',
        'multiple' => true,
        'disabled' => $disabled,
        'query' => 'SELECT 
                        tags.id AS value, 
                        tags.label, 
                        IF(tags_relations.object_id IS NOT NULL, 1,0) AS selected 
                    FROM tags 
                        LEFT JOIN tags_relations 
                            ON tags.id = tags_relations.tag_id 
                                AND tags_relations.object_id = '.intval($id).' 
                                AND tags_relations.object_type = "Stock" 
                    WHERE tags.camp_id = '.$_SESSION['camp']['id'].' AND tags.deleted IS NULL AND tags.type IN ("All","Stock")',
    ]);
    addfield('textarea', 'Comments', 'comments', ['testid' => 'comments_id',  'readonly' => $disabled]);
    if ($id) {
        addfield('line');
        addfield('checkbox', 'I can’t find this box', 'lost', ['onclick' => 'setBoxState("lost")', 'value' => 1, 'checked' => ($data['lost'])]);

        addfield('checkbox', 'Scrap this box?', 'scrap', ['onclick' => 'setBoxState("scrap")', 'value' => 1, 'checked' => ($data['scrap'])]);
        addfield('line');
        addfield('html', 'Box History', showHistory('stock', $data['id']), ['width' => 10, 'disabled' => $disabled]);

        addfield('line', '', '', ['aside' => true]);
    }
    addfield('created', 'Created', 'created', ['aside' => true]);
    addformbutton('submitandnew', 'Save and new item');

    // place the form elements and data in the template
    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
    $cmsmain->assign('formbuttons', $formbuttons);
