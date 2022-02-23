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

$rolesTemplate = ['coordinator', 'warehouse_volunteer', 'free_shop_volunteer', 'library_volunteer', 'label_creation'];

$methods = [];

foreach ($rolesToActions as $k => $v) {
    foreach (array_values($rolesActions[$k]) as $k) {
        $methods[] = $k;
    }
}

$methods = array_unique($methods);

// createResources('test1', 'test1-api', $methods);
// $resource = getResource('test1-api');
// var_dump($resource);
// updateResources('5ef3760527b0da00215e6209', $methods);

// $resource = getResource('boxtribute-dev-api');
// var_dump($resource);

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

// $result = db_query('SELECT
//                         o.id,
//                         o.label,
//                         c.id base_id,
//                         c.name
//                     FROM camps c INNER JOIN organisations o ON o.id = c.organisation_id
//                     WHERE (NOT c.deleted OR c.deleted IS NULL) AND (NOT o.deleted OR o.deleted IS NULL)
//                     ORDER BY o.id, c.id DESC');

// while ($row = db_fetch($result)) {
//     var_dump($row);
//     foreach ($rolesTemplate as $roleName) {
//         $currentRole = 'base_'.$row['base_id'].'_'.$roleName;
//         $currentRoleDescription = ucwords($row['label']).' - Base '.$row['base_id'].' ('.$row['name'].') - '.ucwords(preg_replace('/\_/', ' ', $roleName));
//         $role = getRolesByName($currentRole);
//         usleep(50000);
//         if (null === $role) {
//             $role = createRole($currentRole);
//             usleep(50000);
//         }
//         updateRole($role['id'], $currentRole, $currentRoleDescription);
//         usleep(50000);
//         if ($role) {
//             $methods = $rolesActions[$roleName];
//             $res = updateRolePermissions($role['id'], 'boxtribute-dev-api', $methods);
//             usleep(50000);
//         }
//     }
// }

// function createRolesForBaseOld($orgId, $orgName, $baseId, $baseName, array &$rolesToActions, array &$menusToActions)
// {
//     $rolesTemplate = [
//         'Administrator' => ['Administrator'],
//         'Coordinator' => ['Coordinator'],
//         'Volunteer' => ['warehouse_volunteer', 'free_shop_volunteer'],
//         'Warehouse Volunteer' => ['warehouse_volunteer'],
//         'Free Shop Volunteer' => ['free_shop_volunteer'],
//         'Library Volunteer' => ['library_volunteer'],
//         'Label Creation Volunteer' => ['label_creation'],
//     ];
//     // $rolesTemplate = ['administrator', 'authenticated_user', 'coordinator', 'warehouse_volunteer', 'free_shop_volunteer', 'library_volunteer', 'label_creation'];
//     $userGroupIdValues = [];
//     $functionsIds = [];
//     $userGroupsRoles = [];

//     foreach ($rolesTemplate as $roleName => $auth0Roles) {
//         echo $roleName;

//         $userLevel = 1;
//         $userLevel = (preg_match('/coordinator/', $roleName)) ? 2 : $userLevel;
//         $userLevel = (preg_match('/.*volunteer/', $roleName)) ? 1 : $userLevel;

//         $userGroupIdValues[] = " (NULL, '{$roleName}', CURRENT_TIME(), ".$_SESSION['user']['id'].", NULL, NULL, '{$orgId}', '{$userLevel}', '0', '0', '0', NULL) ";

//         foreach ($auth0Roles as $auth0Role) {
//             $currentRole = 'base_'.$baseId.'_'.$auth0Role;
//             $currentRoleDescription = ucwords($orgName).' - Base '.$baseId.' ('.$baseName.') - '.ucwords(preg_replace('/\_/', ' ', $auth0Role));

//             $userGroupsRoles[$auth0Role][] = [
//                 'roleName' => $currentRole,
//                 'roleDescription' => $currentRoleDescription,
//             ];
//             $functionsIds[$roleName] = isset($functionsIds[$roleName]) ? $functionsIds[$roleName] : [];
//             $functionsIds[$roleName] = array_merge($functionsIds[$roleName], getMenusByRole($roleName, $rolesToActions, $menusToActions));
//         }
//     }

//     db_transaction(function () use ($userGroupIdValues, $functionsIds, $baseId, $userGroupsRoles) {
//         db_query('INSERT INTO `cms_usergroups` (`id`, `label`, `created`, `created_by`, `modified`, `modified_by`, `organisation_id`, `userlevel`, `allow_laundry_startcycle`, `allow_laundry_block`, `allow_borrow_adddelete`, `deleted`) VALUES '.implode(', ', $userGroupIdValues).';');
//         $userGroups = db_array('SELECT * from `cms_usergroups` WHERE id > (LAST_INSERT_ID() - ROW_COUNT()) ORDER BY id ASC;');
//         $index = 0;
//         $functionIdValues = [];
//         $roles = array_values($functionsIds);
//         $baseUserGroupValues = [];
//         foreach ($userGroups as $userGroup) {
//             foreach ($functionsIds[$roles[$index]] as $val) {
//                 $functionIdValues[] = " ('{$val}', '".$userGroup['id']."') ";
//             }
//             $baseUserGroupValues[] = "({$baseId}, '".$userGroup['id']."') ";
//             ++$index;
//         }
//         db_query('INSERT INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES '.implode(', ', $functionIdValues).';');
//         db_query('INSERT INTO `cms_usergroups_camps` (`camp_id`, `cms_usergroups_id`) VALUES '.implode(', ', $baseUserGroupValues).';');
//         foreach($userGroupsRoles){

//             $auth0Role = createOrUpdateRoleAndPermission($auth0Role, $currentRole, $currentRoleDescription);

//             db_query('INSERT INTO `cms_usergroups_roles` (`cms_usergroups_id`, `auth0_role_id`, `auth0_role_name`) VALUES '.implode(', ', $functionIdValues).';');

//         }
//     });

//     // TODO: external service - fast-fail / should be sync
// }

//createRolesForBase('100000003', 'BT', '100000003', 'Developer', $rolesToActions, $menusToActions);
// $roles = getRolesByBaseIds([1, 3]);
var_dump($roles);
