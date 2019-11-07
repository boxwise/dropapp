<?php

    $ids = explode(',', $_POST['ids']);

    // confirmed testusers
    $testusers = ['some.admin@boxwise.co', 'admin@admin.co', 'coordinator@coordinator.co', 'user@user.co'];

    //Define all ids which are allowed to be deleted
    $allowed['people'] = db_array('
        SELECT p.id
        FROM people p
        LEFT JOIN camps c ON p.camp_id = c.id
        WHERE c.organisation_id = 100000000'
    );
    $allowed['stock'] = db_array('
        SELECT s.id
        FROM stock s
        LEFT JOIN locations l ON s.location_id = l.id
        LEFT JOIN camps c ON l.camp_id = c.id
        WHERE c.organisation_id = 100000000'
    );
    $allowed['cms_users'] = db_array('
        SELECT u.id
        FROM cms_users u
        LEFT JOIN cms_usergroups g ON u.cms_usergroups_id = g.id
        WHERE g.organisation_id = 100000000'
    );

    // Test if user is test user, if table is key in $allowed and the ids can be deleted
    $return = ['success' => true];
    foreach ($ids as $id) {
        if (in_array($_SESSION['user']['email'], $testusers) && isset($allowed[$_POST['table']]) && in_array($id, $allowed[$_POST['table']])) {
            listRealDelete($_POST['table'], $ids);
        } else {
            $return = ['success' => false, 'message' => 'You do not have access to delete Test data!'];
            break;
        }
    }

    echo json_encode($return);
