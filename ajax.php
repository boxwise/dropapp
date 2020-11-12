<?php

    $ajax = true;
    // Only if the Login form calls ajax --> $login is set true
    $login = (('login' == $_GET['file']) || ('reset' == $_GET['file']) || ('reset2' == $_GET['file']) ? true : false);
    $checksession_result = [];
    require_once 'library/core.php';

    if ($checksession_result['success']) {
        require_once 'library/ajax/'.preg_replace('/[^a-z0-9-]/', '', $_GET['file']).'.php';
    } else {
        $return = ['success' => false, 'message' => $checksession_result['message'], 'redirect' => $checksession_result['redirect']];
        echo json_encode($return);
    }
