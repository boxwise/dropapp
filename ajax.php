<?php

    $ajax = true;
    // Only if the Login form calls ajax --> $login is set true
    $login = (('cypresslogin' == $_GET['file']) ? true : false);
    require_once 'library/core.php';

    if ($checksession_result['success']) {
        require_once 'library/ajax/'.preg_replace('/[^a-z0-9-]/', '', $_GET['file']).'.php';
    } else {
        $return = ['success' => false, 'message' => $checksession_result['message'], 'redirect' => $checksession_result['redirect']];
        echo json_encode($return);
    }
