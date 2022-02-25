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

require_once 'library/constants.php';

$rolesTemplate = ['administrator', 'coordinator', 'warehouse_volunteer', 'free_shop_volunteer', 'library_volunteer', 'label_creation'];

$methods = [];

foreach ($rolesToActions as $k => $v) {
    foreach (array_values($rolesToActions[$k]) as $k) {
        $methods[] = $k;
    }
}

$methods = array_unique($methods);

// createResources('test1', 'test1-api', $methods);
// $resource = getResource('test1-api');
// var_dump($resource);
// updateResources('5ef3760527b0da00215e6209', $methods);

$resource = getResource($settings['auth0_api_audience']);
// var_dump($resource);
updateResources($resource['id'], $methods);

exit;
// $role = getRolesByName('authenticated_user');

// $methods = $rolesActions['authenticated_user'];

// $res = updateRolePermissions($role['id'], $resource['identifier'], $methods);

// var_dump($res);

// syncyning roles in auth0

// $role = getRolesByName('administrator');
// var_dump($role);
// usleep(50000);
// $methods = $rolesActions['administrator'];
// $res = updateRolePermissions($role['id'], 'boxtribute-dev-api', $methods);

$result = db_query('SELECT
                        o.id,
                        o.label,
                        c.id base_id,
                        c.name
                    FROM camps c INNER JOIN organisations o ON o.id = c.organisation_id
                    WHERE (NOT c.deleted OR c.deleted IS NULL) AND (NOT o.deleted OR o.deleted IS NULL)
                    ORDER BY o.id, c.id DESC');

while ($row = db_fetch($result)) {
    foreach ($rolesTemplate as $roleName) {
        $currentRole = 'base_'.$row['base_id'].'_'.$roleName;
        $currentRoleDescription = ucwords($row['label']).' - Base '.$row['base_id'].' ('.$row['name'].') - '.ucwords(preg_replace('/\_/', ' ', $roleName));
        $role = getRolesByName($currentRole);
        usleep(50000);
        if (null === $role) {
            $role = createRole($currentRole);
            usleep(50000);
        }
        updateRole($role['id'], $currentRole, $currentRoleDescription);
        usleep(50000);
        if ($role) {
            $methods = $rolesActions[$roleName];
            $res = updateRolePermissions($role['id'], $settings['auth0_api_audience'], $methods);
            usleep(50000);
        }
    }
}

//createRolesForBase('100000003', 'BT', '100000003', 'Developer', $rolesToActions, $menusToActions);
// $roles = getRolesByBaseIds([1, 3]);
var_dump($roles);
