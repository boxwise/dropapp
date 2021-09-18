<?php

    // if (!array_key_exists('HTTP_X_APPENGINE_CRON', $_SERVER) && 'dropapp_dev' != $settings['db_database']) {
    //     throw new Exception('Not called from AppEngine cron service');
    // }
    // $permittedDatabases = ['dropapp_dev', 'dropapp_demo', 'dropapp_staging'];
    // if (!in_array($settings['db_database'], $permittedDatabases)) {
    //     throw new Exception('Not permitting a reset of '.$settings['db_database']);
    // }

    // update Auth0
    $bypassAuthentication = true;
    require_once 'library/core.php';
    $db_users = db_query('SELECT id FROM cms_users;');
    while ($db_user = db_fetch($db_users)) {
        updateAuth0UserFromDb($db_user['id'], $settings['test_pwd']);
        usleep(500000);
    }
