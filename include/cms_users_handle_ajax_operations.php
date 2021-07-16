<?php

if ($ajax) {
    $data = null;
    switch ($_POST['do']) {
        case 'delete':
            $ids = explode(',', $_POST['ids']);
            list($success, $message, $redirect) = db_transaction(function () use ($table, $ids) {
                $result = listDelete($table, $ids);
                if ($result['success']) {
                    foreach ($ids as $id) {
                        db_query('UPDATE '.$table.' SET email = CONCAT(email,".deleted.",id) WHERE id = :id', ['id' => $id]);
                        updateAuth0UserFromDb($id);
                    }
                }

                return $result;
            });

            break;
        case 'undelete':
            $ids = explode(',', $_POST['ids']);
            list($success, $message, $redirect) = db_transaction(function () use ($table, $ids) {
                $result = listUndelete($table, $ids);
                if ($result['success']) {
                    foreach ($ids as $id) {
                        db_query('UPDATE '.$table.' SET email = SUBSTR(email, 1, LENGTH(email)-LENGTH(".deleted.")-LENGTH(id)) WHERE id = :id', ['id' => $id]);
                        updateAuth0UserFromDb($id);
                    }
                }

                return $result;
            });

            break;
        case 'extendActive':
        case 'extend':
            $ids = explode(',', $_POST['ids']);
            list($success, $message, $redirect, $data) = db_transaction(function () use ($table, $ids) {
                $result = listExtend($table, $ids, $_POST['option']);
                if ($result['success']) {
                    foreach ($ids as $id) {
                        updateAuth0UserFromDb($id);
                    }
                }

                return $result;
            });

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
