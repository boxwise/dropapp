<?php

if ($ajax) {
    $data = null;
    switch ($_POST['do']) {
        case 'delete':
            $ids = explode(',', $_POST['ids']);
            list($success, $message, $redirect) = listDelete($table, $ids);
            if ($success) {
                foreach ($ids as $id) {
                    db_query('UPDATE '.$table.' SET email = CONCAT(email,".deleted.",id) WHERE id = :id', ['id' => $id]);
                }
            }

            break;
        case 'undelete':
            $ids = explode(',', $_POST['ids']);
            list($success, $message, $redirect) = listUndelete($table, $ids);
            if ($success) {
                foreach ($ids as $id) {
                    db_query('UPDATE '.$table.' SET email = SUBSTR(email, 1, LENGTH(email)-LENGTH(".deleted.")-LENGTH(id)) WHERE id = :id', ['id' => $id]);
                }
            }

            break;
        case 'extendActive':
        case 'extend':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect, $data) = listExtend($table, $ids, $_POST['option']);

                break;
            $ids = explode(',', $_POST['ids']);
            break;
        case 'sendlogindata':
            $ids = explode(',', $_POST['ids']);
            list($success, $message, $redirect) = sendlogindata($table, $ids);
            // defining own message because returned one sounds as if user resetted the password themselves
            $message = 'User will receive an email with instructions and their password within couple of minutes!';

            break;
        case 'loginasuser':
            $ids = explode(',', $_POST['ids']);
            list($success, $message, $redirect) = loginasuser($table, $ids);

            break;
    }

    $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect, 'data' => $data];

    echo json_encode($return);
    die();
}
