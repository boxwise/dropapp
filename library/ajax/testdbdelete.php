<?php

    //header('Content-type:application/json;charset=utf-8');

    $return = ['success' => true];
    // allowed databases
    $devdbs = ['dropapp_dev', 'dropapp_staging'];
    // confirmed testusers
    $testusers = ['admin@admin.co', 'coordinator@coordinator.co', 'user@user.co'];

    // Test if user is a testusers and the database is a dev database
    if (!(in_array($settings['db_database'], $devdbs) && in_array($_SESSION['user']['email'], $testusers))) {
        $return = ['success' => false, 'message' => 'You do not have access to delete test data!'];
    } else {
        $ids = [];
        // get ids of user by emails
        if (isset($_POST['emails'])) {
            $emails = $_POST['emails'];
            foreach ($emails as $email) {
                array_push($ids, db_value('SELECT id FROM cms_users WHERE email = :email', ['email' => $email]));
            }
        } else {
            $ids = explode(',', $_POST['ids']);
        }

        //Define all ids which are allowed to be deleted
        $allowed['people'] = db_array(
            'SELECT p.id
            FROM people p
            LEFT JOIN camps c ON p.camp_id = c.id
            WHERE c.organisation_id = 100000000'
        );
        $allowed['stock'] = db_array(
            'SELECT s.id
            FROM stock s
            LEFT JOIN locations l ON s.location_id = l.id
            LEFT JOIN camps c ON l.camp_id = c.id
            WHERE c.organisation_id = 100000000'
        );
        $allowed['cms_users'] = db_array(
            'SELECT u.id
            FROM cms_users u
            LEFT JOIN cms_usergroups g ON u.cms_usergroups_id = g.id
            WHERE g.organisation_id = 100000000'
        );
        $allowed = array_column($allowed[$_POST['table']], 'id');
        // Test if table is key in $allowed and the ids can be deleted
        foreach ($ids as $id) {
            if ($return['success'] && isset($allowed[$_POST['table']]) && in_array($id, $allowed)) {
                listRealDelete($_POST['table'], $ids);
                $return = ['success' => true, 'message' => 'Successfully deleted id '.$id.' '];
            } else {
                $return = ['success' => false, 'message' => 'You cannot delete id '.$id.' from the '.$_POST['table'].' table!'];

                break;
            }
        }
    }

    echo json_encode($return);
