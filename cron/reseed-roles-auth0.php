<?php

if (!array_key_exists('HTTP_X_APPENGINE_CRON', $_SERVER)) {
    throw new Exception('Not called from AppEngine cron service');
}

// update Auth0
$bypassAuthentication = true;

require_once 'library/core.php';

// we don't need to create administrator and boxtribute_god
$rolesTemplate = ['coordinator', 'warehouse_volunteer', 'free_shop_volunteer', 'library_volunteer', 'label_creation'];

$methods = [];

foreach ($rolesToActions as $k => $v) {
    foreach (array_values($rolesToActions[$k]) as $k) {
        $methods[] = $k;
    }
}

$methods = array_unique($methods);

// add action permissions in the auth0 API end-point
updateResources($settings['auth0_api_id'], $methods);

// creating roles in auth0 and also create usergroups in drop app - one time only
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
        $prefixedRole = 'base_'.$row['base_id'].'_'.$roleName;
        $prefixedRoleDescription = ucwords($row['label']).' - Base '.$row['base_id'].' ('.$row['name'].') - '.ucwords(preg_replace('/\_/', ' ', $roleName));
        // create roles in auth0
        $auth0Role = createOrUpdateRoleAndPermission($roleName, $prefixedRole, $prefixedRoleDescription);

        $finalRoles[] = [
            'organisation_id' => $row['id'],
            'organization' => $row['label'],
            'base_id' => $row['base_id'],
            'base' => $row['name'],
            'auth0_role_label' => $auth0Role['name'],
            'auth0_role_id' => $auth0Role['id'],
            'created_at' => date('Y-m-d H:i:s'),
        ];
    }
}

// store result in the csv file
$fp = fopen($settings['upload_dir'].'/auth0-roles.csv', 'a');

fputcsv($fp, ['organisation_id', 'organization', 'base_id', 'base', 'auth0_role_label', 'auth0_role_label', 'auth0_role_id', 'created_at']);
foreach ($finalRoles as $role) {
    fputcsv($fp, array_values($role));
}
fclose($fp);
