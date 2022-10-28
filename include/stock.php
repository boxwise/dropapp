<?php

use OpenCensus\Trace\Tracer;

Tracer::inSpan(
    ['name' => ('stock.php')],
    function () use ($action, &$cmsmain) {
        global $table, $listconfig, $listdata, $settings;

        $table = $action;
        $ajax = checkajax();

        if (!$ajax) {
            initlist();
            listsetting('manualquery', true);

            //title
            $cmsmain->assign('title', 'Manage Boxes');

            //search box
            listsetting('search', ['box_id', 'l.label', 's.label', 'g.label', 'p.name', 'stock.comments']);

            //Location filter
            listfilter(['label' => 'By Location', 'query' => 'SELECT id, label FROM locations WHERE deleted IS NULL AND visible = 1 AND camp_id = '.$_SESSION['camp']['id'].' AND type = "Warehouse" ORDER BY seq', 'filter' => 'l.id']);

            // Status Filter
            $outgoinglocations = db_simplearray('SELECT id AS value, label FROM locations WHERE deleted IS NULL AND NOT visible AND NOT is_lost AND NOT is_scrap AND NOT is_market AND camp_id = '.$_SESSION['camp']['id'].' AND type = "Warehouse" ORDER BY seq');
            $statusarray = [
                'boxes_in_stock' => 'In Stock',
                'showall' => 'Everything',
                'ordered' => 'Ordered',
                'dispose' => 'Untouched for 3 months',
                'shop' => 'Moved to Free Shop',
                'lost_boxes' => 'Lost',
                'scrap' => 'Scrap',
            ];
            $statusarray += (is_null($outgoinglocations) ? [] : $outgoinglocations);
            listfilter2(['label' => 'Boxes', 'options' => $statusarray, 'filter' => '"show"']);

            function get_filter2_query($applied_filter, $custom_outgoing_locations)
            {
                if (!is_null($custom_outgoing_locations) && array_key_exists($applied_filter, $custom_outgoing_locations)) {
                    return ' AND l.id = '.$applied_filter;
                }

                switch ($applied_filter) {
                case 'boxes_in_stock':
                    return ' AND l.visible';

                case 'ordered':
                    return ' AND (stock.ordered OR stock.picked) AND l.visible';

                case 'dispose':
                    return ' AND DATEDIFF(now(),stock.modified) > 90 AND l.visible';

                case 'lost_boxes':
                    return ' AND l.is_lost';

                case 'shop':
                    return ' AND l.is_market';

                case 'scrap':
                    return ' AND l.is_scrap';

                case 'showall':
                    return ' ';

                default:
                    return ' AND l.visible';
            }
            }
            $applied_filter2_query = get_filter2_query($_SESSION['filter2']['stock'], $outgoinglocations);

            // Gender Filter
            $genders = db_simplearray('SELECT id AS value, label FROM genders ORDER BY seq');
            listfilter3(['label' => 'Gender', 'options' => $genders, 'filter' => '"s.gender_id"']);

            //Category Filter
            $itemlist = db_simplearray('SELECT pc.id, pc.label from products AS p INNER JOIN product_categories AS pc ON pc.id = p.category_id WHERE (camp_id = '.$_SESSION['camp']['id'].')');
            listfilter4(['label' => 'Category', 'options' => $itemlist, 'filter' => 'p.category_id']);

            // Tag Filter
            $tags = db_simplearray('SELECT id, label FROM tags WHERE camp_id = :camp_id AND deleted IS NULL AND `type` IN ("All", "Stock") ORDER BY seq', ['camp_id' => $_SESSION['camp']['id']]);
            if (!empty($tags)) {
                $tagfilter = ['id' => 'tagfilter', 'placeholder' => 'Tag filter', 'options' => db_array('SELECT id, id AS value, label, color FROM tags WHERE camp_id = :camp_id AND deleted IS NULL AND `type` in ("All","Stock") ORDER BY seq', ['camp_id' => $_SESSION['camp']['id']])];
                listsetting('multiplefilter', $tagfilter);
            }

            // Note for boxage: same day creation gets logged as 0 days
            $query = '
            SELECT
                    stock_filtered.*,
                    GROUP_CONCAT(tags.label ORDER BY tags.seq  SEPARATOR 0x1D) AS taglabels,
                    GROUP_CONCAT(tags.color ORDER BY tags.seq) AS tagcolors
                FROM
                    (SELECT 
                            stock.*, 
                            cu.naam AS ordered_name, 
                            cu2.naam AS picked_name, 
                            SUBSTRING(stock.comments,1, 25) AS shortcomment, 
                            g.label AS gender, p.name AS product, 
                            s.label AS size, l.label AS location, 
                            IF(DATEDIFF(now(),stock.created) = 1, "1 day", CONCAT(DATEDIFF(now(),stock.created), " days")) AS boxage,
                            IF(NOT l.visible OR stock.ordered OR stock.ordered IS NOT NULL OR l.container_stock,True,False) AS disableifistrue
                        FROM 
                            stock '.
                            // Join tags here only if a tag filter is selected and only boxes with a certain tag should be returned
                            ($listconfig['multiplefilter_selected'] ? '
                                LEFT JOIN
                                    tags_relations AS stock_tags_filter ON stock_tags_filter.object_id = stock.id AND stock_tags_filter.object_type = "Stock"
                                LEFT JOIN
                                    tags AS tags_filter ON tags_filter.id = stock_tags_filter.tag_id AND tags_filter.deleted IS NULL AND tags_filter.camp_id = '.$_SESSION['camp']['id'] : '').'
                        LEFT OUTER JOIN 
                            cms_users AS cu ON cu.id = stock.ordered_by
                        LEFT OUTER JOIN 
                            cms_users AS cu2 ON cu2.id = stock.picked_by
                        LEFT OUTER JOIN 
                            products AS p ON p.id = stock.product_id
                        LEFT OUTER JOIN 
                            locations AS l ON l.id = stock.location_id
                        LEFT OUTER JOIN 
                            genders AS g ON g.id = p.gender_id
                        LEFT OUTER JOIN 
                            sizes AS s ON s.id = stock.size_id
                        WHERE 
                            l.type = "Warehouse" AND
                            (NOT stock.deleted OR stock.deleted IS NULL) AND
                            l.deleted IS NULL AND 
                            l.camp_id = '.$_SESSION['camp']['id'].

                            ($listconfig['searchvalue'] ? ' AND (box_id LIKE "%'.$listconfig['searchvalue'].'%" OR l.label LIKE "%'.$listconfig['searchvalue'].'%" OR s.label LIKE "%'.$listconfig['searchvalue'].'%" OR g.label LIKE "%'.$listconfig['searchvalue'].'%" OR p.name LIKE "%'.$listconfig['searchvalue'].'%" OR stock.comments LIKE "%'.$listconfig['searchvalue'].'%")' : '').

                            $applied_filter2_query.

                            ($_SESSION['filter3']['stock'] ? ' AND (p.gender_id = '.intval($_SESSION['filter3']['stock']).')' : '').

                            ($_SESSION['filter']['stock'] ? ' AND (stock.location_id = '.$_SESSION['filter']['stock'].')' : '').
                            ($_SESSION['filter4']['stock'] ? ' AND (p.category_id = '.$_SESSION['filter4']['stock'].')' : '').
                            // filter for boxes tags
                            ($listconfig['multiplefilter_selected'] ? ' AND tags_filter.id IN ('.implode(',', $listconfig['multiplefilter_selected']).') ' : '').'
                        GROUP BY 
                            stock.id ) AS stock_filtered
                    LEFT JOIN 
                        tags_relations ON tags_relations.object_id = stock_filtered.id AND tags_relations.object_type = "Stock"
                    LEFT JOIN
                        tags ON tags.id = tags_relations.tag_id AND tags.deleted IS NULL AND tags.camp_id = '.$_SESSION['camp']['id'].'
                    GROUP BY
                        stock_filtered.id
            ';
            $data = getlistdata($query);

            $totalboxes = 0;
            $totalitems = 0;
            foreach ($data as $key => $value) {
                if ($data[$key]['ordered']) {
                    $data[$key]['order'] = '<span class="hide">1</span><i class="fa fa-shopping-cart tooltip-this" title="This box is ordered for the shop by '.$data[$key]['ordered_name'].' on '.strftime('%d-%m-%Y', strtotime($data[$key]['ordered'])).'"></i>';
                } elseif ($data[$key]['picked']) {
                    $data[$key]['order'] = '<span class="hide">2</span><i class="fa fa-truck green tooltip-this" title="This box is picked for the shop by '.$data[$key]['picked_name'].' on '.strftime('%d-%m-%Y', strtotime($data[$key]['picked'])).'"></i>';
                } else {
                    $data[$key]['order'] = '<span class="hide">0</span>';
                }
                ++$totalboxes;
                $totalitems += $value['items'];

                if ($data[$key]['taglabels']) {
                    $taglabels = explode(chr(0x1D), $data[$key]['taglabels']);
                    $tagcolors = explode(',', $data[$key]['tagcolors']);
                    foreach ($taglabels as $tagkey => $taglabel) {
                        $data[$key]['tags'][$tagkey] = ['label' => $taglabel, 'color' => $tagcolors[$tagkey], 'textcolor' => get_text_color($tagcolors[$tagkey])];
                    }
                }

                // add link to new app
                $data[$key]['href'] = $settings['v2_base_url'].'/bases/'.$_SESSION['camp']['id'].'/boxes/'.$data[$key]['box_id'];
            }

            addcolumn('text', 'Box ID', 'box_id');
            addcolumn('text', 'Product', 'product');
            addcolumn('text', 'Gender', 'gender');
            addcolumn('text', 'Size', 'size');
            if (!empty($tags)) {
                addcolumn('tag', 'Tags', 'tags');
            }
            addcolumn('text', 'Comments', 'shortcomment');
            addcolumn('text', 'Items', 'items');
            addcolumn('text', 'Location', 'location');
            addcolumn('text', 'Age', 'boxage');
            addcolumn('html', '&nbsp;', 'order');

            listsetting('allowsort', true);
            listsetting('allowcopy', false);
            listsetting('add', 'Add');

            // enable forward to new app only for beta users
            if (in_array('beta_user', $_SESSION['auth0_user'][$settings['jwt_claim_prefix'].'/roles'])) {
                listsetting('beta_box_view_edit', true);
            }

            $locations = db_simplearray('SELECT id, label FROM locations WHERE deleted IS NULL AND camp_id = '.$_SESSION['camp']['id'].' AND type = "Warehouse" ORDER BY seq');
            addbutton('export', 'Export', ['icon' => 'fa-download', 'showalways' => false]);
            if (!empty($tags)) {
                addbutton('tag', 'Add Tag', ['icon' => 'fa-tag', 'options' => $tags]);
                addbutton('rtag', 'Remove Tag', ['icon' => 'fa-tags', 'options' => $tags]);
            }
            addbutton('movebox', 'Move', ['icon' => 'fa-truck', 'options' => $locations]);
            addbutton('qr', 'Make label', ['icon' => 'fa-print']);
            addbutton('order', 'Order from warehouse', ['icon' => 'fa-shopping-cart', 'disableif' => true]);
            addbutton('undo-order', 'Undo order', ['icon' => 'fa-undo']);

            $cmsmain->assign('firstline', ['Total', '', '', '', $totalboxes.' boxes', $totalitems.' items', '', '', '', '']);
            $cmsmain->assign('listfooter', ['Total', '', '', '', $totalboxes.' boxes', $totalitems.' items', '', '', '', '']);

            $cmsmain->assign('data', $data);
            $cmsmain->assign('listconfig', $listconfig);
            $cmsmain->assign('listdata', $listdata);
            $cmsmain->assign('include', 'cms_list.tpl');
        } else {
            switch ($_POST['do']) {
            case 'movebox':
                $ids = explode(',', $_POST['ids']);
                $count = 0;
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
                $stock_ids = explode(',', $_POST['ids']);
                [$success, $message, $redirect] = db_transaction(function () use ($table, $stock_ids) {
                    list($success, $message, $redirect) = listDelete($table, $stock_ids);

                    $params = [];
                    $query = 'DELETE FROM tags_relations WHERE object_type = "Stock" AND (`object_id`) IN (';
                    foreach ($stock_ids as $index => $stock_id) {
                        $query .= sprintf(' (:stock_id_%s) ', $index);

                        if (sizeof($stock_ids) - 1 !== $index) {
                            $query .= ', ';
                        } else {
                            $query .= ') ';
                        }

                        $params = array_merge($params, ['stock_id_'.$index => $stock_id]);
                    }
                    if (sizeof($params) > 0) {
                        db_query($query, $params);
                    }

                    return [$success, $message, $redirect];
                });

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
                $_SESSION['export_ids_stock'] = $_POST['ids'];
                list($success, $message, $redirect) = [true, '', '?action=stock_export'];

                break;

            case 'tag':
                $ids = explode(',', $_POST['ids']);
                if ('undefined' == $_POST['option']) {
                    $success = false;
                    $message = 'No tags exist. Please go to "Manage tags" to create tags.';
                    $redirect = false;
                } else {
                    // set tag id
                    $tag_id = $_POST['option'];
                    $stock_ids = $ids;
                    if (sizeof($stock_ids) > 0) {
                        // Query speed optimised for 500 records from 3.2 seconds to 0.039 seconds using bulk inserts
                        $query = 'INSERT IGNORE INTO tags_relations (tag_id, object_type, `object_id`) VALUES ';

                        $params = [];

                        for ($i = 0; $i < sizeof($stock_ids); ++$i) {
                            $query .= "(:tag_id, 'Stock', :stock_id{$i})";
                            $params = array_merge($params, ['stock_id'.$i => $stock_ids[$i]]);
                            if ($i !== sizeof($stock_ids) - 1) {
                                $query .= ',';
                            }
                        }

                        $params = array_merge($params, ['tag_id' => $tag_id]);
                        db_query($query, $params);

                        $success = true;
                        $message = 'Tags added';
                        $redirect = true;
                    } else {
                        $success = false;
                        $message = 'To apply the tag, the beneficiary must be checked';
                        $redirect = false;
                    }
                }

                break;

            case 'rtag':
                $ids = explode(',', $_POST['ids']);
                if ('undefined' == $_POST['option']) {
                    $success = false;
                    $message = 'No tags exist. Please go to "Manage tags" to create tags.';
                    $redirect = false;
                } else {
                    // set tag id
                    $tag_id = $_POST['option'];
                    $stock_ids = $ids;
                    if (sizeof($stock_ids) > 0) {
                        db_transaction(function () use ($tag_id, $stock_ids) {
                            $query = 'DELETE FROM tags_relations WHERE object_type = "Stock" AND (tag_id, `object_id`) IN (';

                            $params = [];

                            for ($i = 0; $i < sizeof($stock_ids); ++$i) {
                                $query .= "(:tag_id, :stock_id{$i})";
                                $params = array_merge($params, ['stock_id'.$i => $stock_ids[$i]]);
                                if ($i !== sizeof($stock_ids) - 1) {
                                    $query .= ',';
                                } else {
                                    $query .= ')';
                                }
                            }

                            $params = array_merge($params, ['tag_id' => $tag_id]);
                            db_query($query, $params);
                        });
                        $success = true;
                        $message = 'Tags removed';
                        $redirect = true;
                    } else {
                        $success = false;
                        $message = 'To remove the tag, the boxes must be checked';
                        $redirect = false;
                    }
                }

                break;
        }

            $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

            echo json_encode($return);

            exit();
        }
    }
);
