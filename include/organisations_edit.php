<?php

    $table = 'organisations';
    $action = 'organisations_edit';

    if ($_POST) {
        db_transaction(function () use ($id, $table, $settings, $rolesToActions, $menusToActions) {
            $organisation_is_new = !$id;

            $orgName = $_POST['label'];
            $baseName = $_POST['base'];

            $org_handler = new formHandler($table);
            $savekeys = ['label'];
            $id = $org_handler->savePost($savekeys);

            if ($organisation_is_new) {
                $_POST = ['organisation_id' => $id, 'name' => $_POST['base']];
                $base_handler = new formHandler('camps');
                $savekeys = ['name', 'organisation_id'];
                $baseId = $base_handler->savePost($savekeys);

                // create required roles in dropapp and auth0

                createRolesForBase($id, $orgName, $baseId, $baseName, $rolesToActions, $menusToActions);
            }
        });
        redirect('?action='.$_POST['_origin']);
    }

    $data = db_row('SELECT * FROM '.$table.' WHERE id = :id AND (NOT '.$table.'.deleted OR '.$table.'.deleted IS NULL) ', ['id' => $id]);

    // open the template
    $cmsmain->assign('include', 'cms_form.tpl');

    // put a title above the form
    $cmsmain->assign('title', 'Organisation');

    addfield('text', 'Name', 'label', ['required' => true]);

    // Add first base if organisation is newly created
    if (!$id) {
        addfield('line', '', '');
        addfield('text', 'Name of first Base', 'base', ['required' => true, 'tooltip' => 'This creates a Base with the default values and this name. Please configure the Base once it is created!']);
    }

    addfield('line', '', '', ['aside' => true]);
    addfield('created', 'Created', 'created', ['aside' => true]);

    // place the form elements and data in the template
    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
    $cmsmain->assign('formbuttons', $formbuttons);
