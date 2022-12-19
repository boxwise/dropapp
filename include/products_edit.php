<?php

    $table = 'products';
    $action = 'products_edit';

    if ($_POST) {
        $handler = new formHandler($table);

        $savekeys = ['name', 'gender_id', 'value', 'category_id', 'maxperadult', 'maxperchild', 'sizegroup_id', 'camp_id', 'comments', 'stockincontainer'];
        $id = $handler->savePost($savekeys);

        redirect('?action='.$_POST['_origin']);
    }

    $data = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $id]);

    if (!$id) {
        $data['camp_id'] = $_SESSION['camp']['id'];
    }

    // open the template
    $cmsmain->assign('include', 'cms_form.tpl');
    addfield('hidden', '', 'id');
    addfield('hidden', '', 'camp_id');

    // put a title above the form
    $cmsmain->assign('title', 'Product');

    addfield('text', 'Name', 'name');
    addfield('select', 'Category', 'category_id', ['required' => true, 'width' => 3, 'multiple' => false, 'query' => 'SELECT id AS value, label FROM product_categories ORDER BY seq']);
    if ($_SESSION['camp']['market']) {
        addfield('text', ucwords($_SESSION['camp']['currencyname']), 'value');
    }

    addfield('line', '', '');
    addfield('select', 'Gender', 'gender_id', ['required' => true, 'width' => 2, 'multiple' => false, 'query' => 'SELECT *, id AS value FROM genders ORDER BY seq']);
    addfield('select', 'Sizegroup', 'sizegroup_id', ['required' => true, 'width' => 2, 'multiple' => false, 'query' => 'SELECT *, id AS value FROM sizegroup ORDER BY seq']);
    addfield('line');
    addfield('textarea', 'Description', 'comments');
    addfield('line');
    $stockroomlocationexists = db_value('SELECT id FROM locations WHERE deleted IS NULL AND camp_id = '.intval($_SESSION['camp']['id']).' AND container_stock AND type = "Warehouse"');
    if ($stockroomlocationexists || $_SESSION['camp']['separateshopandwhproducts']) {
        if ($stockroomlocationexists) {
            addfield('checkbox', 'in Free Shop?', 'stockincontainer', ['tooltip' => 'Always show product in Stockroom menu?']);
        } else {
            addfield('checkbox', 'in Free Shop?', 'stockincontainer');
        }
    }

    if ($id) {
        addfield('list', 'In Stock', 'stock', ['width' => 10, 'query' => '
            SELECT 
                stock.id AS id, 
                stock.box_id, 
                stock.items, 
                stock.comments, 
                g.label AS gender, 
                p.name AS product, 
                p.name AS product, 
                s.label AS size, 
                l.label AS location
            FROM stock
            LEFT OUTER JOIN products AS p ON p.id = stock.product_id
            LEFT OUTER JOIN locations AS l ON l.id = stock.location_id
            LEFT OUTER JOIN genders AS g ON g.id = p.gender_id 
            LEFT OUTER JOIN sizes AS s ON s.id = stock.size_id 
            WHERE 
                l.type = "Warehouse" AND 
                (not stock.deleted or stock.deleted IS NULL) AND 
                stock.box_state_id NOT IN (2,5,6) AND 
                stock.product_id = '.$id,
            'columns' => ['box_id' => 'Box ID', 'product' => 'Product', 'gender' => 'Gender', 'size' => 'Size', 'items' => 'Items', 'location' => 'Location', 'comments' => 'Comments'],
            'allowedit' => true,
            'allowdelete' => false,
            'allowadd' => false,
            'allowselect' => false,
            'allowselectall' => false,
            'allowshowhide' => false,
            'action' => 'stock',
            'redirect' => true,
            'allowsort' => true, ]);
    }

    addfield('line', '', '', ['aside' => true]);
    addfield('created', 'Created', 'created', ['aside' => true]);

    // place the form elements and data in the template
    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
    $cmsmain->assign('formbuttons', $formbuttons);
