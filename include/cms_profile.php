<?php

    $table = 'cms_users';

    if ($_POST) {
        $keys = ['naam', 'email', 'language'];

        db_transaction(function () use ($table, $keys) {
            $handler = new formHandler($table);
            $handler->savePost($keys, ['language']);
            $row = db_row('SELECT * FROM '.$table.' WHERE id = :id ', ['id' => $_SESSION['user']['id']]);
            $_SESSION['user'] = array_merge($_SESSION['user'], $row);

            updateAuth0UserFromDb($_SESSION['user']['id']);
            if ($_POST['pass']) {
                updateAuth0Password($_SESSION['user']['id'], $_POST['pass']);
            }
        });
        redirect('?action='.$action);
    }

    // collect data for the form

    $data = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $_SESSION['user']['id']]);

    // open the template
    $cmsmain->assign('include', 'cms_form.tpl');

    // put a title above the form
    $cmsmain->assign('title', $translate['cms_users_settings']);

    // define tabs

    addfield('text', $translate['cms_users_naam'], 'naam', ['required' => true, 'readonly' => ('family' == $_SESSION['user']['usertype'])]);
    addfield('line');

    addfield('email', $translate['cms_users_email'], 'email', ['required' => true]);
    addfield('password', $translate['cms_users_password'], 'pass', ['repeat' => true]);

    //addfield('line');
    //addfield('select',$translate['cms_settings_language'],'language',array('query'=>'SELECT id AS value, name AS label FROM languages WHERE visible ORDER BY seq'));

    addfield('delete_user', 'Deactivate', '', ['aside' => true]);
    addfield('created', 'Created', 'created', ['aside' => true]);

    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
