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
            $box = db_row('SELECT * FROM stock WHERE id = :id', ['id' => $_POST['id']]);
            if ($box && ($box['location_id'] != $_POST['location_id'][0])) {
                db_query('UPDATE stock SET ordered = NULL, ordered_by = NULL, picked = NULL, picked_by = NULL WHERE id = :id', ['id' => $_POST['id']]);
                db_query('INSERT INTO itemsout (product_id, size_id, count, movedate, from_location, to_location) VALUES ('.$box['product_id'].','.$box['size_id'].','.$box['items'].',NOW(),'.$box['location_id'].','.$_POST['location_id'][0].')');
            }

            $handler = new formHandler($table);

            $savekeys = ['box_id', 'product_id', 'size_id', 'items', 'location_id', 'comments'];
            $id = $handler->savePost($savekeys);

            db_query('DELETE FROM tags_relations WHERE object_id = :stock_id AND object_type = "Stock"', [':stock_id' => $id]);
            $query = 'INSERT IGNORE INTO tags_relations (tag_id, object_type, `object_id`) VALUES ';

            $params = [];
            $tags = $_POST['tags'];
            for ($i = 0; $i < sizeof($tags); ++$i) {
                $query .= "(:tag_id{$i}, 'Stock', :stock_id)";
                $params = array_merge($params, ['tag_id'.$i => $tags[$i]]);
                if ($i !== sizeof($tags) - 1) {
                    $query .= ',';
                }
            }

            $params = array_merge($params, ['stock_id' => $id]);
            db_query($query, $params);

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
                GROUP_CONCAT(tags.label) AS taglabels,
                GROUP_CONCAT(tags.color) AS tagcolors
            FROM stock AS s 
                LEFT OUTER JOIN products AS p ON p.id = s.product_id 
                LEFT OUTER JOIN genders AS g ON g.id = p.gender_id 
                LEFT OUTER JOIN locations AS l ON l.id = s.location_id 
                LEFT JOIN tags_relations ON tags_relations.object_id = stock.id AND tags_relations.object_type = "Stock"
                LEFT JOIN tags ON tags.id = tags_relations.tag_id
            WHERE (NOT s.deleted OR s.deleted IS NULL) AND s.id = :id', ['id' => $_GET['created_id']]);
        $smarty->assign('box', $box);
        $htmlaside = $smarty->fetch('stock_confirm_new.tpl');
        addfield('html', '', $htmlaside);
    }

    $data = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $id]);
    verify_campaccess_location($data['location_id']);

    if (!$id) {
        $data['visible'] = 1;
    }

    // open the template
    $cmsmain->assign('include', 'cms_form.tpl');
    addfield('hidden', '', 'id');
    // put a title above the form
    $cmsmain->assign('title', 'Box');

    if ($id) {
        addfield('text', 'Box ID', 'box_id', ['readonly' => true, 'width' => 2]);
        addfield('line');
    }

    addfield('select', 'Product', 'product_id', ['test_id' => 'product_id', 'required' => true, 'multiple' => false, 'query' => 'SELECT p.id AS value, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS label FROM products AS p LEFT OUTER JOIN genders AS g ON p.gender_id = g.id WHERE (NOT p.deleted OR p.deleted IS NULL) AND p.camp_id = '.$_SESSION['camp']['id'].($_SESSION['camp']['separateshopandwhproducts'] ? ' AND NOT p.stockincontainer' : '').' ORDER BY name', 'onchange' => 'getSizes()']);

    addfield('select', 'Size', 'size_id', ['required' => true, 'width' => 2, 'multiple' => false, 'query' => 'SELECT *, id AS value FROM sizes WHERE sizegroup_id = '.intval(db_value('SELECT sizegroup_id FROM products WHERE id = :id', ['id' => $data['product_id']])).' ORDER BY seq', 'tooltip' => 'If the right size for your box is not here, don\'t put it in comments, but first double check if you have the right product. For example: Long sleeves for babies, we call them tops.']);

    addfield('number', 'Items', 'items', ['testid' => 'items_id']);

    addfield('select', 'Location', 'location_id', ['required' => true, 'width' => 2, 'multiple' => false, 'query' => 'SELECT *, id AS value FROM locations WHERE deleted IS NULL AND camp_id = '.$_SESSION['camp']['id'].' ORDER BY seq']);

    if ($data['qr_id']) {
        $qr = db_row('SELECT code, legacy FROM qr WHERE id = :id', ['id' => $data['qr_id']]);

        addfield('html', '', '<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://'.$_SERVER['HTTP_HOST'].'/mobile.php?barcode='.$qr['code'].($qr['legacy'] ? '&qrlegacy=1' : '').'" /><br /><br />', ['aside' => true, 'asidetop' => true]);
    }

    addfield('line');
    addfield('select', 'Tag(s)', 'tags', [
        'testid' => 'tag_id',
        'multiple' => true,
        'query' => 'SELECT 
                        tags.id AS value, 
                        tags.label, 
                        IF(tags_relations.object_id IS NOT NULL, 1,0) AS selected 
                    FROM tags 
                        LEFT JOIN tags_relations 
                            ON tags.id = tags_relations.tag_id 
                                AND tags_relations.object_id = '.intval($id).' 
                                AND tags_relations.object_type = "Stock" 
                    WHERE tags.camp_id = '.$_SESSION['camp']['id'].' AND tags.deleted IS NULL AND tags.type IN ("All","Stock") 
                    ORDER BY label',
    ]);
    addfield('textarea', 'Comments', 'comments', ['testid' => 'comments_id']);
    addfield('line');
    addfield('html', 'Box History', showHistory('stock', $data['id']), ['width' => 10]);

    addfield('line', '', '', ['aside' => true]);
    addfield('created', 'Created', 'created', ['aside' => true]);
    addformbutton('submitandnew', 'Save and new item');

    // place the form elements and data in the template
    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
    $cmsmain->assign('formbuttons', $formbuttons);
