<?php

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
                $found_id = db_value('SELECT id FROM cms_users WHERE email = :email', ['email' => $email]);
                //test necessary, because otherwise null is added to ids
                if ($found_id) {
                    array_push($ids, $found_id);
                }
            }
        } else {
            $ids = explode(',', $_POST['ids']);
        }
        //if ids that are submitted are in database, delete them, otherwise just return true
        if ($ids != []) {
            //Define all ids which are allowed to be deleted
            $allowed['people'] = array_column(db_array(
                'SELECT p.id
            FROM people p
            LEFT JOIN camps c ON p.camp_id = c.id
            WHERE c.organisation_id = 100000000'
            ), 'id');
            $allowed['stock'] = array_column(db_array(
                'SELECT s.id
            FROM stock s
            LEFT JOIN locations l ON s.location_id = l.id
            LEFT JOIN camps c ON l.camp_id = c.id
            WHERE c.organisation_id = 100000000'
            ), 'id');
            $allowed['cms_users'] = array_column(db_array(
                'SELECT u.id
            FROM cms_users u
            LEFT JOIN cms_usergroups g ON u.cms_usergroups_id = g.id
            WHERE g.organisation_id = 100000000'
            ), 'id');
            // Test if table is key in $allowed and the ids can be deleted
            foreach ($ids as $id) {
                $permission = ($return['success'] && isset($allowed[$_POST['table']]) && in_array($id, $allowed[$_POST['table']]));
                if ($permission) {
                    listRealDelete($_POST['table'], $ids);
                    $return = 'true';
                } else {
                    $return = 'false';

                    break;
                }
            }
        } else {
            $return = 'true';
        }
    }

    echo $return;
