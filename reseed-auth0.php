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

    require_once 'library/core.php';

    $result = db_query('SELECT
    o.id,
    o.label,
    c.id base_id,
    c.name
    FROM camps c INNER JOIN organisations o ON o.id = c.organisation_id
    WHERE (NOT c.deleted OR c.deleted IS NULL) AND (NOT o.deleted OR o.deleted IS NULL)
    ORDER BY o.id, c.id DESC');

    while ($row = db_fetch($result)) {
        // foreach ($rolesTemplate as $roleName) {
        //     $currentRole = 'base_'.$row['base_id'].'_'.$roleName;
        //     $currentRoleDescription = ucwords($row['label']).' - Base '.$row['base_id'].' ('.$row['name'].') - '.ucwords(preg_replace('/\_/', ' ', $roleName));
        //     $role = getRolesByName($currentRole);
        //     usleep(500);
        //     if (null === $role) {
        //         $role = createRole($currentRole);
        //         usleep(500);
        //     }
        //     updateRole($role['id'], $currentRole, $currentRoleDescription);
        //     usleep(500);
        //     if ($role) {
        //         $methods = $rolesActions[$roleName];
        //         $res = updateRolePermissions($role['id'], $settings['auth0_api_audience'], $methods);
        //         usleep(500);
        //     }
        // }
        // this will create roles for each base of an organization in auth0 and also corresponding standard usergroups in dropapp
        createRolesForBase($row['id'], $row['label'], $row['base_id'], $row['name'], $rolesToActions, $menusToActions);
    }

    $db_users = db_query('SELECT id FROM cms_users;');
    while ($db_user = db_fetch($db_users)) {
        updateAuth0UserFromDb($db_user['id'], $settings['test_pwd']);
        usleep(500000);
    }
