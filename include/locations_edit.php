<?php

    $table = 'locations';
    $action = 'locations_edit';
    $is_admin = ($_SESSION['user']['is_admin']);

    if ($_POST) {
        // check if you have access to the location you want to update
        verify_campaccess_location($_POST['id']);

        //Prepare POST
        $_POST['visible'] = in_array($_POST['box_state_id'][0], ['1', '3', '4']) ? 1 : 0;
        $_POST['is_donated'] = in_array($_POST['box_state_id'][0], ['5']) ? 1 : 0;
        $_POST['is_lost'] = in_array($_POST['box_state_id'][0], ['2']) ? 1 : 0;
        $_POST['is_scrap'] = in_array($_POST['box_state_id'][0], ['6']) ? 1 : 0;

        $_POST['camp_id'] = $_SESSION['camp']['id'];

        $handler = new formHandler($table);
        $savekeys = ['label', 'camp_id', 'box_state_id', 'visible', 'is_donated', 'is_lost', 'is_scrap', 'container_stock', 'is_market'];
        $id = $handler->savePost($savekeys);

        redirect('?action='.$_POST['_origin']);
    }

    $data = db_row('SELECT * FROM '.$table.' WHERE type = "Warehouse" AND id = :id', ['id' => $id]);

    if (!$id) {
        $data = db_defaults($table);
    } elseif (($data['is_market'] || $data['container_stock']) && !$is_admin) {
        throw new Exception("You don't have access to this record");
    }

    // open the template
    $cmsmain->assign('include', 'cms_form.tpl');

    // put a title above the form
    $cmsmain->assign('title', 'Warehouse Location');

    addfield('hidden', '', 'id');
    addfield('text', 'Label', 'label', ['required' => true]);
    addfield('select', 'Default Status of Boxes', 'box_state_id', ['required' => true, 'tooltip' => 'If a box is moved to this location it will be assigned this status by default.', 'query' => 'SELECT id AS value, label FROM box_state WHERE NOT id in (2,3,4) ORDER BY id']);
    addfield('html', 'About Locations', '<p>Locations are physical areas that hold stock. Giving locations a default status for Boxes will help you track of where your stock is going.<br />
                                        Create an <strong>Instock</strong> location for warehouse areas that hold your available boxes. <strong>Instock</strong> boxes will show up in your <strong>Stock Overview</strong>.<br />
                                        Create a <strong>Donated</strong> location to keep track of boxes you have sent to others.
                                        Move a box from an <strong>Instock</strong> location to a <strong>Donated</strong> location and watch your inventory counts update automatically!</p>');
    if ($is_admin) {
        addfield('checkbox', 'Stockroom', 'container_stock', ['tooltip' => 'God only']);
        addfield('checkbox', 'Shop', 'is_market', ['tooltip' => 'God only']);
    }
    addfield('line', '', '', ['aside' => true]);
    addfield('created', 'Created', 'created', ['aside' => true]);

    // place the form elements and data in the template
    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
    $cmsmain->assign('formbuttons', $formbuttons);
