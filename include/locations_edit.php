<?php

    $table = 'locations';
    $action = 'locations_edit';
    $is_admin = false;

    if ($_SESSION['user']['is_admin']) {
        $is_admin = true;
    }

    if ($_POST) {
        verify_campaccess_location($_POST['id']);
        if (!in_array($_POST['camp_id'], camplist(true))) {
            throw new Exception("You don't have access to this record");
        }

        $handler = new formHandler($table);

        $savekeys = ['label', 'visible', 'camp_id', 'container_stock', 'is_market', 'is_donated', 'is_lost'];
        $id = $handler->savePost($savekeys);

        redirect('?action='.$_POST['_origin']);
    }

    $data = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $id]);

    //verify_campaccess_location($id);

    if (!$id) {
        $data['visible'] = 0;
        $data['container_stock'] = 1;
        $data['camp_id'] = $_SESSION['camp']['id'];
    }

    // open the template
    $cmsmain->assign('include', 'cms_form.tpl');

    // put a title above the form
    $cmsmain->assign('title', 'Warehouse Location');

    addfield('hidden', '', 'id');
    addfield('hidden', '', 'camp_id');
    addfield('text', 'Label', 'label');
    if ($is_admin) {
        addfield('checkbox', 'Stockroom', 'container_stock');
        addfield('checkbox', 'Market', 'is_market');
        addfield('checkbox', 'Donated', 'is_donated');
        addfield('checkbox', 'Lost', 'is_lost');
        addfield('checkbox', 'This warehouse location is an outgoing location', 'visible', ['aside' => true, 'tooltip' => 'Items in outgoing warehouse locations are not counted as part of your stock.']);
    }
    addfield('line', '', '', ['aside' => true]);
    addfield('created', 'Created', 'created', ['aside' => true]);

    // place the form elements and data in the template
    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
    $cmsmain->assign('formbuttons', $formbuttons);
