<?php

if ($ajax) {
    $data = null;

    switch ($_POST['do']) {
        case 'delete':
            $ids = explode(',', (string) $_POST['ids']);
            [$success, $message, $redirect] = db_transaction(function () use ($table, $ids) {
                [$success, $message, $redirect] = listDelete($table, $ids);
                if ($success) {
                    foreach ($ids as $id) {
                        // Only append .deleted suffix if the email doesn't already have it
                        // This prevents double-deletion when someone tries to delete an already-deleted user
                        // Pattern checks for .deleted.<digits> after the @ symbol (e.g., user@domain.com.deleted.123)
                        db_query('UPDATE '.$table.' SET email = CONCAT(email,".deleted.",id) WHERE id = :id AND email NOT REGEXP "@.*\.deleted\.[0-9]+$"', ['id' => $id]);
                        updateAuth0UserFromDb($id);
                    }
                }

                return [$success, $message, $redirect];
            });

            break;

        case 'undelete':
            $ids = explode(',', (string) $_POST['ids']);
            [$success, $message, $redirect] = db_transaction(function () use ($table, $ids) {
                [$success, $message, $redirect] = listUndelete($table, $ids);
                if ($success) {
                    foreach ($ids as $id) {
                        db_query('UPDATE '.$table.' SET email = SUBSTR(email, 1, LENGTH(email)-LENGTH(".deleted.")-LENGTH(id)) WHERE id = :id', ['id' => $id]);
                        updateAuth0UserFromDb($id);
                    }
                }

                return [$success, $message, $redirect];
            });

            break;

        case 'extendActive':
        case 'extend':
            $ids = explode(',', (string) $_POST['ids']);

            [$success, $message, $redirect, $data] = db_transaction(function () use ($table, $ids) {
                // check if user have proper permission to extend access of selected the account
                // related to this trello card https://trello.com/c/KI47eGPI
                $in = implode(',', array_map('intval', $ids));
                $result = db_query('SELECT 
                u.id, l.level
                FROM
                cms_users u
                    INNER JOIN
                cms_usergroups ug ON ug.id = u.cms_usergroups_id
                    INNER JOIN
                cms_usergroups_levels l ON l.id = ug.userlevel
                WHERE u.id in ('.$in.')');
                $allowed = true;
                while ($row = db_fetch($result)) {
                    if (intval($row['level']) > intval($_SESSION['usergroup']['userlevel'])) {
                        $allowed = false;

                        break;
                    }
                }
                if (!$allowed) {
                    return [false, 'You are not allowed to extend access of higher level users', false, null];
                }
                [$success, $message, $redirect, $data] = listExtend($table, $ids, $_POST['option']);

                if ($success) {
                    foreach ($ids as $id) {
                        updateAuth0UserFromDb($id);
                    }
                }

                return [$success, $message, $redirect, $data];
            });

            break;

        case 'sendlogindata':
            $ids = explode(',', (string) $_POST['ids']);
            [$success, $message, $redirect] = sendlogindata($table, $ids);
            // defining own message because returned one sounds as if user resetted the password themselves
            $message = 'User will receive an email with instructions and their password within couple of minutes!';

            break;

        case 'loginasuser':
            $ids = explode(',', (string) $_POST['ids']);
            [$success, $message, $redirect] = loginasuser($table, $ids);

            break;
    }

    $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect, 'data' => $data];

    echo json_encode($return);

    exit;
}
