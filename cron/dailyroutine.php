<?php

if (!array_key_exists('HTTP_X_APPENGINE_CRON', $_SERVER) && 'dropapp_dev' != $settings['db_database']) {
    throw new Exception('Not called from AppEngine cron service');
}

$permittedDatabases = ['dropapp_dev', 'dropapp_demo', 'dropapp_staging', 'dropapp_production'];
if (!in_array($settings['db_database'], $permittedDatabases)) {
    throw new Exception('Not permitting to run dailyroutine for '.$settings['db_database']);
}

$bypassAuthentication = true;

require_once 'library/core.php';
// This file is called about one time daily

if ('db' === $_GET['action']) {
    // people that have not been active for a longer time will be deleted(Changed to deactivated in visible text, variables remain under the name deleted, as does the databasse)
    // the amount of days of inactivity is set in the camp table
    $result = db_query('
    SELECT p.id, p.lastname, p.created, p.modified, c.delete_inactive_users AS treshold 
    FROM people AS p 
    LEFT OUTER JOIN camps AS c ON c.id = p.camp_id 
    WHERE (NOT p.deleted OR p.deleted IS NULL) AND p.parent_id IS NULL');
    while ($row = db_fetch($result)) {
        $row['touch'] = db_value('
        SELECT GREATEST(COALESCE((
            SELECT transaction_date 
            FROM transactions AS t 
            WHERE t.people_id = people.id AND people.parent_id IS NULL AND product_id IS NOT NULL 
            ORDER BY transaction_date DESC LIMIT 1
        ),0),
        COALESCE((
            SELECT transaction_date 
            FROM library_transactions AS t 
            WHERE t.people_id = people.id AND people.parent_id IS NULL  
            ORDER BY transaction_date DESC LIMIT 1
        ),0),
        COALESCE(people.modified,0),COALESCE(people.created,0))
        FROM people
        WHERE id = :id', ['id' => $row['id']]);
        if ($row['touch']) {
            $date1 = new DateTime($row['touch']);
            $date2 = new DateTime();
            $row['diff'] = $date2->diff($date1)->format('%a');

            if ($row['diff'] > $row['treshold']) {
                db_query('UPDATE people SET deleted = NOW() WHERE id = :id', ['id' => $row['id']]);
                simpleSaveChangeHistory('people', $row['id'], 'Record deleted by daily routine');
                db_touch('people', $row['id']);
            }
        }
    }

    // family members of deleted parents should also be deleted
    $result = db_query('
    SELECT p2.id 
    FROM people AS p1, people AS p2 
    WHERE p2.parent_id = p1.id AND p1.deleted AND (NOT p2.deleted OR p2.deleted IS NULL)');
    while ($row = db_fetch($result)) {
        db_query('UPDATE people SET deleted = NOW() WHERE id = :id', ['id' => $row['id']]);
        simpleSaveChangeHistory('people', $row['id'], 'Record deleted by daily routine because head of family/beneficiary was deleted');
        db_touch('people', $row['id']);
    }

    // this notifies us when a new installation of the Drop App is made
    if (!isset($settings['installed'])) {
        foreach ($_SERVER as $key => $value) {
            $mail .= $key.' -> '.$value.'<br />';
        }
        $result = sendmail('admin@boxtribute.org', 'admin@boxtribute.org', 'New installation of Boxtribute', $mail);
        db_query('INSERT INTO settings (category_id, type, code, description_en, value) VALUES (1,"text","installed","Date and time of installation and first run",NOW())');
    }
}

if ('auth0_inactive_users' === $_GET['action'] || 'auth0_active_users' === $_GET['action']) {
    // Test if Auth0 and the users in the DB are in the same state
    if ('auth0_inactive_users' === $_GET['action']) {
        $query = 'SELECT id FROM cms_users WHERE deleted != 0;';
    } else {
        $query = 'SELECT id FROM cms_users WHERE NOT deleted OR deleted = 0;';
    }
    $db_users = db_query($query);
    while ($db_user = db_fetch($db_users)) {
        isUserInSyncWithAuth0($db_user['id']);
        usleep(500000);
    }
}

if ('auth0_check_synk' === $_GET['action']) {
    // Test if Auth0 holds more active users than the app
    $db_users = db_query('SELECT id, email FROM cms_users WHERE NOT deleted OR deleted = 0;');
    $index = 0;
    $query = 'blocked:false ';
    while ($db_user = db_fetch($db_users)) {
        // $email = $db_user['email'];
        // $email = preg_replace('/\.deleted\.\d+/', '', $email);
        $query .= '-user_id:"auth0|'.$db_user['id'].'" ';
    }

    // $query = substr($query, 0, strlen($query) - 4);
    $users = getAllUsers($query);

    foreach ($users as $user) {
        trigger_error('Not-Blocked User with id '.$user['user_id'].' exists in Auth0, but not in DB.', E_USER_ERROR);
    }
}
