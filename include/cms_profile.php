<?php

    $table = 'cms_users';

    if ($_POST) {
        // remove leading and trailing whitespace from name
        if ($_POST['naam']) {
            $_POST['naam'] = trim($_POST['naam']);
        }
        // lowercase email and remove leading and trailing whitespace
        if ($_POST['email']) {
            $_POST['email'] = strtolower(trim($_POST['email']));
        }

        $keys = ['naam', 'email', 'language'];

        // check the password is equal to confirm password
        if ($_POST['pass'] && ($_POST['pass'] !== $_POST['pass2'])) {
            redirect('?action=cms_profile&origin='.$_POST['_origin'].'&warning=1&message=The password does not match with your confirmed password');
        }
        //  check the password strenght
        if ($_POST['pass'] && !checkPasswordStrength($_POST['pass'])) {
            redirect('?action=cms_profile&origin='.$_POST['_origin'].'&warning=1&message=Your password must be at least 12 characters including at least 3 of the following 4 types of characters: a lowercase letter, an uppercase letter, a number, a special character (such as !@#$%&/=?_.,:;-).');
        }

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

    addfield('text', $translate['cms_users_naam'], 'naam', ['required' => true, 'readonly' => ('family' == $_SESSION['user']['usertype']), 'testid' => 'user_name']);
    addfield('line');

    addfield('email', $translate['cms_users_email'], 'email', ['required' => true, 'testid' => 'user_email']);
    addfield('password', $translate['cms_users_password'], 'pass', ['repeat' => true, 'pwcheck' => true, 'testid' => 'user_pass']);

    //addfield('line');
    //addfield('select',$translate['cms_settings_language'],'language',array('query'=>'SELECT id AS value, name AS label FROM languages WHERE visible ORDER BY seq'));

    addfield('delete_user', 'Deactivate', '', ['aside' => true]);
    addfield('created', 'Created', 'created', ['aside' => true]);

    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
