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

    $finalRoles = [];

    while ($row = db_fetch($result)) {
        foreach ($rolesTemplate as $roleName) {
            $currentRole = 'base_'.$row['base_id'].'_'.$roleName;
            $currentRoleDescription = ucwords($row['label']).' - Base '.$row['base_id'].' ('.$row['name'].') - '.ucwords(preg_replace('/\_/', ' ', $roleName));
            $role = getRolesByName($currentRole);
            if (null === $role) {
                $role = createRole($currentRole);
            }
            updateRole($role['id'], $currentRole, $currentRoleDescription);

            $finalRoles[] = [
                'organisation_id' => $row['id'],
                'organization' => $row['label'],
                'base_id' => $row['base_id'],
                'base' => $row['name'],
                'auth0_role_label' => $currentRole,
                'auth0_role_id' => $role['id'],
            ];

            if ($role) {
                $methods = $rolesActions[$roleName];
                $res = updateRolePermissions($role['id'], $settings['auth0_api_audience'], $methods);
            }
        }
    }
// print out result
print_r($finalRoles);
