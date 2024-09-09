<?php

require 'vendor/autoload.php';
use Auth0\SDK\Auth0;
use Auth0\SDK\Configuration\SdkConfiguration;
use Auth0\SDK\Exception\Auth0Exception;
use Auth0\SDK\Store\SessionStore;
use Auth0\SDK\Utility\HttpResponse;
use Buzz\Client\MultiCurl;
use Nyholm\Psr7\Factory\Psr17Factory;

/**
 * Getting Auth0 configurations.
 *
 * @param bool $apiCall Optional. This will be used to distingush some configuration when custom domain is being used, as API management call does not work with custom domain.
 */
function getAuth0SdkConfiguration(mixed $settings, $apiCall = false)
{
    $Psr17Library = new Psr17Factory();
    $Psr18Library = new MultiCurl($Psr17Library);

    // in the auth0 php sdk 8 configration pass as class
    return new SdkConfiguration([
        'httpClient' => $Psr18Library,
        'httpRequestFactory' => $Psr17Library,
        'httpResponseFactory' => $Psr17Library,
        'httpStreamFactory' => $Psr17Library,
        'domain' => $apiCall ? $settings['auth0_api_domain'] : $settings['auth0_domain'],
        'clientId' => $settings['auth0_client_id'],
        'clientSecret' => $settings['auth0_client_secret'],
        'cookieSecret' => $settings['auth0_cookie_secret'],
        'redirectUri' => $settings['auth0_redirect_uri'],
        // form_post response mode is no longer working in insecure dev env
        // 'responseMode' => $settings['auth0_response_mode'],
        // in order to support refresh-token offline_access needed
        'scope' => ['openid', 'profile', 'email'],
    ]);
}

/**
 * Getting Auth0 instance.
 */
function getAuth0(mixed $settings)
{
    $configuration = getAuth0SdkConfiguration($settings);
    // set session sotarage
    $configuration->setSessionStorage(new SessionStore($configuration));

    return new Auth0($configuration);
}

/**
 * Getting Auth0 API Management instance.
 */
function getAuth0Management(mixed $settings)
{
    $configuration = getAuth0SdkConfiguration($settings, true);

    $auth0 = new Auth0($configuration);

    return $auth0->management(); // new Management($configuration);
}

/**
 * Getting Auth0 Authentication instance.
 */
function getAuth0Authentication(mixed $settings)
{
    $auth0 = getAuth0($settings);

    return $auth0->authentication();
}

/**
 * Deleting user from Auth0.
 *
 * @param int $userId
 */
function deleteAuth0User($userId)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);
    $auth0UserId = 'auth0|'.intval($userId);
    $mgmtAPI->users()->delete($auth0UserId);
}

// because users are updated in all kinds of ways and the
// changes are buried within things like generic formhandlers
// we just fetch the user record from the database again
// (after any changes are made), and push the whole lot to auth0
// rather than having any specific actions like change email, disable account etc
// note: you should wrap calls to this in a db transaction so if the API
// call fails, the previous db update is not applied
function updateAuth0UserFromDb($userId, $setPwd = false)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);
    $auth0UserId = 'auth0|'.intval($userId);
    $dbUserData = db_row('
        SELECT 
            u.email, u.naam, u.deleted, u.cms_usergroups_id, u.valid_firstday, u.valid_lastday,
            ug.organisation_id
        FROM 
            cms_users u
        LEFT JOIN 
            cms_usergroups ug ON u.cms_usergroups_id=ug.id
        WHERE 
            u.id=:id', ['id' => $userId]);
    $dbUserData['base_ids'] = db_simplearray('SELECT camp_id FROM cms_usergroups_camps WHERE cms_usergroups_id=:ugid', ['ugid' => $dbUserData['cms_usergroups_id']], false, false);

    $auth0UserData = [
        'email' => $dbUserData['email'],
        'name' => $dbUserData['naam'],
        'blocked' => '0000-00-00 00:00:00' != $dbUserData['deleted'] && !is_null($dbUserData['deleted']),
        'app_metadata' => [
            'usergroup_id' => $dbUserData['cms_usergroups_id'],
            'organisation_id' => $dbUserData['organisation_id'],
            'base_ids' => $dbUserData['base_ids'],
        ],
        'connection' => 'Username-Password-Authentication',
    ];
    if ('0000-00-00 00:00:00' != $dbUserData['deleted'] && !is_null($dbUserData['deleted'])) {
        $auth0UserData['app_metadata']['last_blocked_date'] = $dbUserData['deleted'];
        $auth0UserData['email'] = preg_replace('/\.deleted\.\d+/', '', (string) $dbUserData['email']);
    }
    if ($dbUserData['valid_firstday'] && '0000-00-00' != $dbUserData['valid_firstday']) {
        $auth0UserData['app_metadata']['valid_firstday'] = $dbUserData['valid_firstday'];
    } else {
        $auth0UserData['app_metadata']['valid_firstday'] = null;
    }

    if ($dbUserData['valid_lastday'] && '0000-00-00' != $dbUserData['valid_lastday']) {
        $auth0UserData['app_metadata']['valid_lastday'] = $dbUserData['valid_lastday'];
    } else {
        $auth0UserData['app_metadata']['valid_lastday'] = null;
    }

    $response = $mgmtAPI->users()->update($auth0UserId, $auth0UserData);

    // user doesn't exist, so try creating it instead
    if (404 === $response->getStatusCode()) {
        $auth0UserData['user_id'] = preg_replace('/auth0\|/', '', $auth0UserId);
        $auth0UserData['password'] = generateSecureRandomString(); // user will need to reset password anyway
        $response = $mgmtAPI->users()->create($settings['auth0_db_connection_id'], $auth0UserData);

        // the status code will be 201 if the user created successfully
        if (201 !== $response->getStatusCode()) {
            throw new Exception($response->getStatusCode(), $response->getReasonPhrase());
        }
    } elseif (200 !== $response->getStatusCode()) {
        throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
    }

    // set user roles in auth0
    $dbUserRoles = db_array('
            SELECT 
                ugr.auth0_role_id
            FROM 
                cms_usergroups_roles ugr
            WHERE 
                ugr.cms_usergroups_id=:userGroupsId', ['userGroupsId' => $dbUserData['cms_usergroups_id']]);

    // assign user roles into auth0
    $roles = [];
    foreach ($dbUserRoles as $role) {
        $roles[] = $role['auth0_role_id'];
    }
    if (sizeof($roles) > 0) {
        assignRolesToUser($auth0UserId, $roles);
    }

    if ($setPwd) {
        // needed for reseeding test env
        $mgmtAPI->users()->update($auth0UserId, ['password' => $setPwd]);
    }
}

/**
 * Update user password in Auth0.
 *
 * @param int    $userId
 * @param string $password
 */
function updateAuth0Password($userId, $password)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);
    $mgmtAPI->users()->update('auth0|'.intval($userId), ['password' => $password, 'connection' => 'Username-Password-Authentication']);
}

/**
 * Checking if user in DB is in sync with Auth0.
 *
 * @param int $userId
 */
function isUserInSyncWithAuth0($userId)
{
    $return_value = true;

    $dbUser = db_row('
        SELECT 
            u.email, u.naam, u.deleted, u.is_admin, u.cms_usergroups_id, u.valid_firstday, u.valid_lastday,
            ug.organisation_id
        FROM 
            cms_users u
        LEFT JOIN 
            cms_usergroups ug ON u.cms_usergroups_id=ug.id
        WHERE 
            u.id=:id', ['id' => $userId]);

    // get active base ids for this user
    $dbUser['active_base_ids'] = db_simplearray(
        'SELECT 
            uc.camp_id 
         FROM cms_usergroups_camps uc 
         INNER JOIN camps c ON c.id= uc.camp_id 
         WHERE uc.cms_usergroups_id=:ugid AND (NOT c.deleted OR c.deleted IS NULL)',
        ['ugid' => $dbUser['cms_usergroups_id']],
        false,
        false
    );

    $auth0User = getAuth0User($userId);
    $hasActiveBase = (sizeof($dbUser['active_base_ids']) > 0);
    $validationResult = [];

    if (!$dbUser && !$auth0User) {
        $return_value = true;
    } elseif ($dbUser && $auth0User && $hasActiveBase) {
        $validationResult['id'] = ($auth0User['identities'][0]['user_id'] == $userId) ? 'true' : 'false';
        // deleted user no longer has name/email/usergroup/organisation/base
        // ref. trello card: https://trello.com/c/68xRRHny
        if ('0000-00-00 00:00:00' != $dbUser['deleted'] && !is_null($dbUser['deleted'])) {
            $validationResult['last_blocked_date'] = (!empty($auth0User['app_metadata']['last_blocked_date']) && $auth0User['app_metadata']['last_blocked_date'] == $dbUser['deleted']) ? 'true' : 'false';
            $validationResult['deleted'] = (!empty($auth0User['blocked']) && $auth0User['blocked']) ? 'true' : 'false';
        } else {
            $validationResult['email'] = ($auth0User['email'] == (preg_match('/\.deleted\.\d+/', (string) $dbUser['email']) ? preg_replace('/\.deleted\.\d+/', '', (string) $dbUser['email']) : $dbUser['email'])) ? 'true' : 'false';
            $validationResult['name'] = ($auth0User['name'] == $dbUser['naam']) ? 'true' : 'false';
            $validationResult['usergroup_id'] = ($auth0User['app_metadata']['usergroup_id'] == $dbUser['cms_usergroups_id'] || null == $dbUser['cms_usergroups_id']) ? 'true' : 'false';
            $validationResult['organisation_id'] = ($auth0User['app_metadata']['organisation_id'] == $dbUser['organisation_id'] || null == $dbUser['cms_organisation_id']) ? 'true' : 'false';
        }

        $auth0ActiveBaseIds = isset($auth0User['active_base_ids']) && is_array($auth0User['active_base_ids']) ? array_values($auth0User['active_base_ids']) : [];
        $dbUserActiveBaseIds = isset($dbUser['active_base_ids']) && is_array($dbUser['active_base_ids']) ? array_values($dbUser['active_base_ids']) : [];

        // Sort the base ids to ensure they can be compared regardless of order
        sort($auth0ActiveBaseIds);
        sort($dbUserActiveBaseIds);

        // check if all active bases are in sync
        $validationResult['base_ids'] = ($auth0ActiveBaseIds === $dbUserActiveBaseIds) ? 'true' : 'false';

        if ($dbUser['valid_firstday'] && '0000-00-00' != $dbUser['valid_firstday']) {
            $validationResult['valid_firstday'] = (!empty($auth0User['app_metadata']['valid_firstday']) && $auth0User['app_metadata']['valid_firstday'] == $dbUser['valid_firstday']) ? 'true' : 'false';
        }

        if ($dbUser['valid_lastday'] && '0000-00-00' != $dbUser['valid_lastday']) {
            $validationResult['valid_lastday'] = (!empty($auth0User['app_metadata']['valid_lastday']) && $auth0User['app_metadata']['valid_lastday'] == $dbUser['valid_lastday']) ? 'true' : 'false';
        }

        $false_key = array_search('false', $validationResult);
        $return_value = !$false_key;
    } elseif ($dbUser && $auth0User && !$hasActiveBase) {
        // check if deleted user without active base is in sync with Auth0
        if ('0000-00-00 00:00:00' != $dbUser['deleted'] && !is_null($dbUser['deleted'])) {
            $validationResult['last_blocked_date'] = (!empty($auth0User['app_metadata']['last_blocked_date']) && $auth0User['app_metadata']['last_blocked_date'] == $dbUser['deleted']) ? 'true' : 'false';
            $validationResult['deleted'] = (!empty($auth0User['blocked']) && $auth0User['blocked']) ? 'true' : 'false';
        } else {
            // God user, and users without active base but not deleted
            $validationResult['email'] = ($auth0User['email'] == (preg_match('/\.deleted\.\d+/', (string) $dbUser['email']) ? preg_replace('/\.deleted\.\d+/', '', (string) $dbUser['email']) : $dbUser['email'])) ? 'true' : 'false';
            $validationResult['name'] = ($auth0User['name'] == $dbUser['naam']) ? 'true' : 'false';
            $validationResult['usergroup_id'] = ($auth0User['app_metadata']['usergroup_id'] == $dbUser['cms_usergroups_id'] || null == $dbUser['cms_usergroups_id']) ? 'true' : 'false';
        }
        $false_key = array_search('false', $validationResult);
        $return_value = !$false_key;
    } elseif ((!$dbUser || $auth0User) && ($dbUser || !$auth0User)) {
        $return_value = false;
    }

    if (!$return_value) {
        trigger_error('User with id '.$userId.' is out of sync between DB and Auth0 '.($false_key ? 'at '.$false_key : ''), E_USER_ERROR);
    }

    $dbUserRoles = db_array('
            SELECT 
                ugr.auth0_role_id
            FROM 
                cms_usergroups_roles ugr
            WHERE 
                ugr.cms_usergroups_id=:userGroupsId', ['userGroupsId' => $dbUser['cms_usergroups_id']]);

    $auth0UserRoles = getUserAssignedRoles($userId);

    $messageAppendix = '';
    if (!$dbUserRoles && !$auth0UserRoles) {
        $return_value = true;
    } elseif ($dbUserRoles && $auth0UserRoles) {
        $return_value = true;
        $dbRoles = [];
        $auth0Roles = [];
        $auth0BasesInRoles = [];

        foreach ($dbUserRoles as $dbUserRole) {
            $dbRoles[] = $dbUserRole['auth0_role_id'];
        }

        foreach ($auth0UserRoles as $auth0UserRole) {
            if ('boxtribute_god' == $auth0UserRole['name']) {
                continue;
            }
            $auth0Roles[] = $auth0UserRole['id'];
            $tmp = explode('_', (string) $auth0UserRole['name'], 3);
            if ((bool) $tmp[1]) {
                $auth0BasesInRoles[] = $tmp[1];
            }
        }

        $dbRolesNotInAuth0 = array_diff($dbRoles, $auth0Roles);
        $auth0RolesNotInDB = array_diff($auth0Roles, $dbRoles);
        $auth0BasesInRolesNotInDB = array_diff($auth0BasesInRoles, $dbUser['active_base_ids']);

        if ((bool) $dbRolesNotInAuth0) {
            foreach ($dbRolesNotInAuth0 as $item) {
                $messageAppendix .= ' Role ID '.$item.',';
            }
            $messageAppendix .= ' is associated in DB, but not in Auth0.';
            $return_value = false;
        }

        if ((bool) $auth0RolesNotInDB) {
            foreach ($auth0RolesNotInDB as $item) {
                $messageAppendix .= ' Role ID '.$item.',';
            }
            $messageAppendix .= ' is associated in Auth0, but not in DB.';
            $return_value = false;
        }

        if ((bool) $auth0BasesInRolesNotInDB) {
            foreach ($auth0BasesInRolesNotInDB as $item) {
                $messageAppendix .= ' Base ID '.$item.',';
            }
            $messageAppendix .= ' is associated in Auth0 by the Roles, but not in DB.';
            $return_value = false;
        }
    } elseif (!$dbUserRoles && $auth0UserRoles) {
        if ('boxtribute_god' == $auth0UserRoles[0]['name']) {
            if ($dbUser['is_admin']) {
                $return_value = true;
            } else {
                $messageAppendix = ' User is not an admin in DB, but in Auth0!';
                $return_value = false;
            }
        }
        $messageAppendix = ' No Roles were associated in DB!';
        $return_value = false;
    } elseif ($dbUserRoles && !$auth0UserRoles) {
        $messageAppendix = ' No Roles were associated in Auth0!';
        $return_value = false;
    }

    if (!$return_value) {
        trigger_error('Roles of user with id '.$userId.' is out of sync between DB and Auth0.'.$messageAppendix, E_USER_ERROR);
    }

    return $return_value;
}

/**
 * Gett user by email.
 *
 * @param string $email
 */
function getAuth0UserByEmail($email)
{
    if (checkEmail($email)) {
        try {
            global $settings;
            $mgmtAPI = getAuth0Management($settings);
            $response = $mgmtAPI->usersByEmail()->get($email);

            if (HttpResponse::wasSuccessful($response)) {
                return HttpResponse::decodeContent($response);
            }

            throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
        } catch (Auth0Exception $e) {
            // user doesn't exist in auth0
            if (404 == $e->getCode()) {
                return null;
            }
        }
    } else {
        return null;
    }
}

/**
 * Getting user information from Auth0.
 *
 * @param int $userId
 */
function getAuth0User($userId)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);
    $response = $mgmtAPI->users()->get('auth0|'.intval($userId));

    if (HttpResponse::wasSuccessful($response)) {
        return HttpResponse::decodeContent($response);
    }

    throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
}

/**
 * Getting roles by base ids.
 */
function getRolesByBaseIds(array $baseIds)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);

    $roles = [];

    foreach ($baseIds as $baseId) {
        $body = [
            'name_filter' => "base_{$baseId}_",
        ];

        $response = $mgmtAPI->roles()->getAll($body);

        if (HttpResponse::wasSuccessful($response)) {
            $res = HttpResponse::decodeContent($response);
            foreach ($res as $role) {
                if (!empty($role) && preg_match('/base_'.$baseId.'_.*/', (string) $role['name'])) {
                    array_push($roles, $role);
                }
            }
            usleep(2000);
        } else {
            throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
        }
    }

    return $roles;
}

/**
 * Getting roles by name.
 *
 * @param string $roleName
 */
function getRolesByName($roleName)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);

    $body = [
        'name_filter' => $roleName,
    ];

    $response = $mgmtAPI->roles()->getAll($body);

    if (HttpResponse::wasSuccessful($response)) {
        $res = HttpResponse::decodeContent($response);

        return $res[0] ?? null;
    }

    throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
}

/**
 * Create Role.
 *
 * @param string $roleName
 */
function createRole($roleName)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);
    $response = $mgmtAPI->roles()->create($roleName);

    if (HttpResponse::wasSuccessful($response)) {
        $res = HttpResponse::decodeContent($response);

        return $res;
    }

    throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
}

/**
 * Update role in Auth0.
 *
 * @param string $roleId
 * @param string $roleName
 * @param string $roleDescription
 */
function updateRole($roleId, $roleName, $roleDescription)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);
    $response = $mgmtAPI->roles()->update($roleId, [
        'name' => $roleName,
        'description' => $roleDescription,
    ]);
    // https://auth0.com/docs/api/management/v2#!/Roles/post_roles
    if (HttpResponse::wasSuccessful($response)) {
        $res = HttpResponse::decodeContent($response);

        return $res;
    }

    throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
}

/**
 * Update role permissions.
 *
 * @param string $roleId
 * @param string $resourseServerIdentifier
 * @param array  $methods
 */
function updateRolePermissions($roleId, $resourseServerIdentifier, $methods)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);

    $permissions = [];

    foreach ($methods as $method) {
        $permissions[] = [
            'resource_server_identifier' => $resourseServerIdentifier,
            'permission_name' => $method,
        ];
    }
    $response = $mgmtAPI->roles()->addPermissions($roleId, $permissions);

    if (HttpResponse::wasSuccessful($response, 201)) {
        $res = HttpResponse::decodeContent($response);

        return $res;
    }

    throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
}

/**
 * Get all roles.
 */
function getRoles()
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);

    $response = $mgmtAPI->roles()->getAll();

    if (HttpResponse::wasSuccessful($response)) {
        $res = HttpResponse::decodeContent($response);

        return $res;
    }

    throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
}

/**
 * Update End-Point in Auth0.
 *
 * @param string $resourseServerIdentifier
 * @param array  $methods
 */
function updateResources($resourseServerIdentifier, $methods)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);

    $body = [
        'scopes' => [],
    ];

    foreach ($methods as $method) {
        $body['scopes'][] = [
            'value' => $method,
            'description' => $method,
        ];
    }

    $response = $mgmtAPI->resourceServers()->update($resourseServerIdentifier, $body);

    if (HttpResponse::wasSuccessful($response)) {
        $res = HttpResponse::decodeContent($response);
    } else {
        throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
    }
}

/**
 * Create roles for all bases of an organization.
 *
 * @param int    $orgId
 * @param string $orgName
 * @param int    $baseId
 * @param string $baseName
 * @param bool   $isFirstBase
 */
function createRolesForBase($orgId, $orgName, $baseId, $baseName, array &$rolesToActions, array &$menusToActions, $isFirstBase = true)
{
    $rolesTemplate = [
        'Head of Operations' => ['administrator'],
        'Coordinator' => ['coordinator'],
        'Volunteer' => ['warehouse_volunteer', 'free_shop_volunteer'],
        'Volunteer (Warehouse)' => ['warehouse_volunteer'],
        'Volunteer (Free Shop)' => ['free_shop_volunteer'],
        // this feature removed for the new org
        // 'Volunteer (Library)' => ['library_volunteer'],
        'Label Creation' => ['label_creation'],
        // This role is manually created only
        // 'External Checkout (Free Shop)' => ['external_free_shop_checkout'],
    ];

    if (!$isFirstBase) {
        unset($rolesTemplate['Head of Operations']);
        $adminUserGroup = db_row("
                SELECT 
                    ug.id 
                FROM 
                    cms_usergroups ug
                INNER JOIN
                    cms_usergroups_levels ugl ON ug.userlevel = ugl.id
                WHERE 
                    ugl.shortlabel = 'Admin' AND ug.organisation_id = :orgId", ['orgId' => $orgId]);
        if (!empty($adminUserGroup)) {
            db_query("INSERT INTO `cms_usergroups_camps` (`camp_id`, `cms_usergroups_id`) VALUES ({$baseId}, ".$adminUserGroup['id'].')');
        } else {
            throw new Exception('There is no admin user group.', 409);
        }
    }

    $functionsIds = [];
    $userGroupsRoles = [];
    $baseFunctionIds = [];

    // adding usergroups in the Dropapp
    foreach ($rolesTemplate as $roleName => $auth0Roles) {
        $userLevel = 3;
        $userLevel = (preg_match('/coordinator/i', $roleName)) ? 2 : $userLevel;
        $userLevel = (preg_match('/head\ of\ operations/i', $roleName)) ? 1 : $userLevel;
        $userLevel = (preg_match('/(.*)?volunteer/i', $roleName)) ? 3 : $userLevel;
        $baseRoleName = 'Head of Operations' !== $roleName ? 'Base '.ucwords($baseName)." - {$roleName}" : $roleName;
        $userGroupIdValue = " (NULL, '{$baseRoleName}', CURRENT_TIME(), ".(!empty($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'NULL').", '{$orgId}', '{$userLevel}', NULL) ";
        // check if usergroup already created in dropapp
        $data = db_row('SELECT * FROM cms_usergroups WHERE label = :label AND organisation_id = :organisationId', ['label' => $baseRoleName, 'organisationId' => $orgId]);
        if (!$data) {
            db_query('INSERT INTO `cms_usergroups` (`id`, `label`, `created`, `created_by`, `organisation_id`, `userlevel`, `deleted`) VALUES '.$userGroupIdValue.';');
            $userGroupId = db_insertid();
        } else {
            $userGroupId = $data['id'];
        }

        // preparing $userGroupsRoles for db-table cms_usergroups_roles
        foreach ($auth0Roles as $auth0Role) {
            $currentRole = 'administrator' !== $auth0Role ? 'base_'.$baseId.'_'.$auth0Role : $auth0Role;
            $currentRoleDescription = 'administrator' !== $auth0Role ? ucwords($orgName).' - Base '.$baseId.' ('.ucwords($baseName).') - '.ucwords((string) preg_replace('/\_/', ' ', $auth0Role)) : 'Someone who manage all bases within an organization';

            if (false == array_search($currentRole, array_column($userGroupsRoles, 'roleName'))) {
                $userGroupsRoles[] = [
                    'roleName' => $auth0Role,
                    'currentRole' => $currentRole,
                    'currentRoleDescription' => $currentRoleDescription,
                    'userGroupId' => $userGroupId,
                ];
            }

            // get the functions to assign to this usergroup
            // TO-DO: make independant of the constant $menusToActions and use the action_permissions from cms_functions
            $functionsIds = array_unique(array_merge($functionsIds, getMenusByRole($auth0Role, $rolesToActions, $menusToActions)));
        }

        // Prepare Insert in cms_usergroups_functions and cms_functions_camps
        $userGroupFunctionIdValues = [];
        foreach ($functionsIds as $val) {
            $userGroupFunctionIdValues[] = " ('{$val}', '".$userGroupId."') ";
            $baseFunctionIds[] = $val;
        }

        // Prepare Insert in cms_usergroups_camps
        $baseUserGroupValue = "({$baseId}, '".$userGroupId."') ";

        $functionsIds = [];
        // adding usergroups' functions
        db_query('INSERT IGNORE INTO `cms_usergroups_functions` (`cms_functions_id`, `cms_usergroups_id`) VALUES '.implode(', ', $userGroupFunctionIdValues).';');
        // adding usergroup to base
        db_query('INSERT IGNORE INTO `cms_usergroups_camps` (`camp_id`, `cms_usergroups_id`) VALUES '.$baseUserGroupValue.';');
    }

    $baseFunctionIdValues = [];
    // remove duplicates function ids
    $baseFunctionIds = array_unique($baseFunctionIds);
    foreach ($baseFunctionIds as $val) {
        $baseFunctionIdValues[] = " ('{$val}', '".$baseId."') ";
    }
    // adding menus for the base
    db_query('INSERT IGNORE INTO `cms_functions_camps` (`cms_functions_id`, `camps_id`) VALUES '.implode(', ', $baseFunctionIdValues).';');

    // adding roles in the auth0 then also add the reference to cms_usergroups_roles
    foreach ($userGroupsRoles as $userGroupsRole) {
        // TODO: external service - fast-fail / should be sync
        $auth0Role = createOrUpdateRoleAndPermission($userGroupsRole['roleName'], $userGroupsRole['currentRole'], $userGroupsRole['currentRoleDescription']);
        $userGroupRoleValue = sprintf(" (%d,'%s', '%s') ", $userGroupsRole['userGroupId'], $auth0Role['id'], $auth0Role['name']);
        db_query('INSERT IGNORE INTO `cms_usergroups_roles` (`cms_usergroups_id`, `auth0_role_id`, `auth0_role_name`) VALUES '.$userGroupRoleValue.';');
    }

    return true;
}

function updateRolesForBase($baseId, $baseName)
{
    $result = db_query('
        SELECT 
            ug.id, ug.label, uc.camp_id, ug.created
        FROM
            cms_usergroups ug
                INNER JOIN
            cms_usergroups_camps uc ON uc.cms_usergroups_id = ug.id
                INNER JOIN
            cms_usergroups_levels ul ON ul.id = ug.userlevel
        WHERE
            uc.camp_id = :baseId and NOT ul.level = 100', ['baseId' => $baseId]);

    while ($usergroup = db_fetch($result)) {
        // check if the usergroup is based on new standard user groups
        $regx = '/Base (.*) - (Coordinator|Label Creation|Volunteer|Volunteer) ?(\(Free Shop\)|\(Warehouse\))?/m';
        if (preg_match($regx, (string) $usergroup['label'])) {
            $roleName = trim(preg_split('/-/', (string) $usergroup['label'])[1]);

            $newUserGroupLabel = sprintf('Base %s - %s', ucwords((string) $baseName), $roleName);
            db_query('UPDATE cms_usergroups SET label = :newUserGroupLabel WHERE id = :userGroupId', [
                'newUserGroupLabel' => $newUserGroupLabel,
                'userGroupId' => $usergroup['id'],
            ]);
        }
    }

    return true;
}

/**
 * Getting available actions with role name.
 *
 * @param string $roleName
 */
function getMethodByRole($roleName)
{
    global $rolesToActions;

    return $rolesToActions[$roleName];
}

/**
 * Create or update role and permissions in auth0.
 *
 * @param string $roleName
 * @param string $prefixedRole
 * @param string $prefixedRoleDescription
 */
function createOrUpdateRoleAndPermission($roleName, $prefixedRole, $prefixedRoleDescription)
{
    global $rolesToActions;
    global $settings;

    $role = getRolesByName($prefixedRole);
    usleep(500000);
    if (null === $role) {
        $role = createRole($prefixedRole);
        usleep(500000);
    }
    if (!in_array($roleName, ['administrator'])) {
        updateRole($role['id'], $prefixedRole, $prefixedRoleDescription);
        usleep(500000);
        if ($role) {
            $methods = $rolesToActions[$roleName];
            updateRolePermissions($role['id'], $settings['auth0_api_audience'], $methods);
            usleep(500000);
        }
    }

    return $role;
}

/**
 * Getting available menues by role.
 *
 * @param string $role
 */
function getMenusByRole($role, array &$rolesToActions, array &$menusToActions)
{
    $rolePermissions = $rolesToActions[$role];
    $menuIds = [];
    foreach ($menusToActions as $menu) {
        foreach ($rolePermissions as $rolePermission) {
            if (in_array($rolePermission, $menu['action_permission'])) {
                array_push($menuIds, $menu['id']);
            }
        }
    }

    return array_unique($menuIds);
}

/**
 * Assign roles to auth0 user. (it will remove all roles alreadys assigned then add new roles).
 *
 * @param int $userId
 */
function assignRolesToUser($userId, array $roleIds)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);
    // getting current roles for thr users
    $response = $mgmtAPI->users()->getRoles($userId);

    $removeRolesIds = [];

    if (HttpResponse::wasSuccessful($response)) {
        $res = HttpResponse::decodeContent($response);
        // preprering the role ids to be removed
        foreach ($res as $role) {
            $removeRolesIds[] = $role['id'];
        }
    } else {
        throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
    }

    if (sizeof($removeRolesIds) > 0) {
        // removing current roles
        // https://auth0.com/docs/api/management/v2#!/Users/delete_user_roles
        $response = $mgmtAPI->users()->removeRoles($userId, $removeRolesIds);
        if (!HttpResponse::wasSuccessful($response, 204)) {
            throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
        }
    }

    // assigning the new roles to the users
    $response = $mgmtAPI->users()->addRoles($userId, $roleIds);
    // refer to docs: https://auth0.com/docs/api/management/v2#!/Users/post_user_roles
    if (!HttpResponse::wasSuccessful($response, 204)) {
        throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
    }
}

/**
 * Getting user assigned roles.
 */
function getUserAssignedRoles(mixed $userId)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);
    $response = $mgmtAPI->users()->getRoles('auth0|'.intval($userId));

    if (HttpResponse::wasSuccessful($response)) {
        $res = HttpResponse::decodeContent($response);

        return $res;
    }

    throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
}

/**
 * Getting all the users.
 */
function getAllUsers(mixed $query)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);

    $params = [
        'q' => $query,
    ];

    $response = $mgmtAPI->users()->getAll($params);

    if (HttpResponse::wasSuccessful($response)) {
        $res = HttpResponse::decodeContent($response);

        return $res;
    }

    throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
}
