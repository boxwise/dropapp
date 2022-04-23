<?php

if (!array_key_exists('HTTP_X_APPENGINE_CRON', $_SERVER)) {
    throw new Exception('Not called from AppEngine cron service');
}

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
    // create standard user groups and also roles in auth0
    createRolesForBase($row['id'], $row['label'], $row['base_id'], $row['name'], $rolesToActions, $menusToActions);
    usleep(500);
}
