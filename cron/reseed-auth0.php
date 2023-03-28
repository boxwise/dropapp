<?php

    if (!array_key_exists('HTTP_X_APPENGINE_CRON', $_SERVER) && 'dropapp_dev' != $settings['db_database']) {
        throw new Exception('Not called from AppEngine cron service');
    }
    $permittedDatabases = ['dropapp_dev', 'dropapp_demo', 'dropapp_staging'];
    if (!in_array($settings['db_database'], $permittedDatabases)) {
        throw new Exception('Not permitting a reset of '.$settings['db_database']);
    }

    // update Auth0
    $bypassAuthentication = true;

    set_time_limit(300);

    require_once 'library/core.php';

    db_transaction(function () use ($settings, $rolesToActions, $menusToActions) {
        $result = db_query('SELECT
            o.id,
            o.label,
            c.id base_id,
            c.name
            FROM camps c INNER JOIN organisations o ON o.id = c.organisation_id
            WHERE (NOT c.deleted OR c.deleted IS NULL) AND (NOT o.deleted OR o.deleted IS NULL)
            ORDER BY o.id, c.id DESC');

        while ($row = db_fetch($result)) {
            createRolesForBase($row['id'], $row['label'], $row['base_id'], $row['name'], $rolesToActions, $menusToActions);
            usleep(500000);
        }

        // connect cms_usergroups to roles in auth0
        $result = db_query('SELECT DISTINCT auth0_role_name FROM cms_usergroups_roles');
        while ($row = db_fetch($result)) {
            $role = getRolesByName($row['auth0_role_name']);
            db_query('UPDATE cms_usergroups_roles SET auth0_role_id = :id WHERE auth0_role_name = :rolename', ['id' => $role['id'], 'rolename' => $row['auth0_role_name']]);
        }

        $db_users = db_query('SELECT id FROM cms_users;');
        while ($db_user = db_fetch($db_users)) {
            updateAuth0UserFromDb($db_user['id'], $settings['test_pwd']);
            sleep(1);
        }
    });
