<?php

    $row = db_row('SELECT * FROM cms_users WHERE resetpassword != "" AND resetpassword = :hash AND id = :userid AND NOT deleted', ['hash' => $_POST['hash'], 'userid' => $_POST['userid']]);

    if ($row) { //e-mailaddress exists in database
        if ($_POST['pass'] != $_POST['pass2']) {
            $success = false;
            $message = translate('cms_reset_notmatching');
        } elseif (strlen($_POST['pass']) < 8) {
            $success = false;
            $message = translate('cms_reset_tooshort');
        } else {
            db_query('UPDATE cms_users SET pass = :pass, resetpassword = "" WHERE id = :userid', ['pass' => md5($_POST['pass']), 'userid' => $_POST['userid']]);
            $success = true;
            $message = translate('cms_reset_success');
        }
    } else { // user not found
        $success = false;
        $message = GENERIC_LOGIN_ERROR;
        logfile('Poging tot invoer nieuw wachtwoord '.join(' ', $_POST));
    }

    $return = ['success' => $success, 'message' => $message, 'redirect' => 'login.php'];

    echo json_encode($return);
