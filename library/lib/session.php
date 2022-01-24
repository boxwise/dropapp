<?php

require 'vendor/autoload.php';
use Auth0\SDK\API\Management;
use Auth0\SDK\Auth0;
use Auth0\SDK\Configuration\SdkConfiguration;
use Auth0\SDK\Exception\Auth0Exception;
use Auth0\SDK\Exception\StateException;
use Auth0\SDK\Store\SessionStore;
use Auth0\SDK\Utility\HttpResponse;
use Buzz\Client\MultiCurl;
use Nyholm\Psr7\Factory\Psr17Factory;

function getAuth0SdkConfiguration($settings)
{
    $Psr17Library = new Psr17Factory();
    $Psr18Library = new MultiCurl($Psr17Library);
    // in the auth0 php sdk 8 configration pass as class
    return new SdkConfiguration([
        'httpClient' => $Psr18Library,
        'httpRequestFactory' => $Psr17Library,
        'httpResponseFactory' => $Psr17Library,
        'httpStreamFactory' => $Psr17Library,
        'domain' => $settings['auth0_domain'],
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

function getAuth0($settings)
{
    $configuration = getAuth0SdkConfiguration($settings);
    // set session sotarage
    $configuration->setSessionStorage(new SessionStore($configuration));

    return new Auth0($configuration);
}

function getAuth0Management($settings)
{
    $configuration = getAuth0SdkConfiguration($settings);

    return new Management($configuration);
}

function getAuth0Authentication($settings)
{
    $auth0 = getAuth0($settings);

    return $auth0->authentication();
}

function authenticate($settings, $ajax)
{
    $isAuth0Callback = 'auth0callback' == $_REQUEST['action'];
    $auth0 = getAuth0($settings);

    $session = $auth0->getCredentials();

    // Dealing with the auth0 rules error -- this should be the first thing to check
    if ($isAuth0Callback && null === $session && $_REQUEST['error']) {
        throw new Exception($_REQUEST['error_description'], 401);

        exit();
    }

    if (isset($session)) {
        // Authentication complet -- getting the user info from auth0
        $userInfo = $auth0->getUser();
        // ideally we wouldn't need to do this, but because loadSessionData
        // is crazy and looks for $_GET parameters hidden here to
        // change current org or camp, we have to load this every request
        loadSessionData($userInfo);
    }

    // Is this end-user already signed in?
    if (null === $session && isset($_REQUEST['code'], $_REQUEST['state']) && $isAuth0Callback) {
        try {
            // We must store redirect url after state code verification because state code verification clears session
            $redirectUrl = $_SESSION['auth0_callback_redirect_uri'] ?? '/';
            $auth0->exchange();
            unset($_SESSION['auth0_callback_redirect_uri']);
            redirect($redirectUrl);
        } catch (StateException $e) {
            // clear the session if state code failed
            $auth0->clear();

            $_SESSION['auth0_callback_redirect_uri'] = $redirectUrl;
            $loginUrl = $auth0->login($settings['auth0_redirect_uri'].'/?action=auth0callback');

            // Finally, redirect the user to the Auth0 Universal Login Page.
            redirect($loginUrl);

            exit;
        }
    }

    // If refresh_token is enabled at auth0, this will renew the token; offlice_access scope is required
    if ($session->accessTokenExpired) {
        try {
            // Token has expired, attempt to renew it.
            $auth0->renew();
        } catch (StateException $e) {
            // There was an error trying to renew the token. Clear the session.
            $auth0->clear();
        }
    }

    // Lastly, if session is not set, the login page should be displayed
    if (null === $session) {
        if ($ajax) {
            http_response_code(401);
            echo json_encode(['success' => false]);
        }
        // because auth0 will only callback to specific known urls
        // we record the full redirect url in a session variable
        // and redirect there once we've had the auth0 callback
        $_SESSION['auth0_callback_redirect_uri'] = $_SERVER['REQUEST_URI'];
        // we use the prompt=login paramter to ensure the login screen
        // always displays - rather than redirecting back
        // mainly, this is because we're using an auth0 rule to raise errors
        // and this then leaves us forever stuck without this
        // see https://community.auth0.com/t/can-i-show-errors-raised-in-rules-in-the-login-page/51143
        $loginUrl = $auth0->login($settings['auth0_redirect_uri'].'/?action=auth0callback');

        // Finally, redirect the user to the Auth0 Universal Login Page.
        redirect($loginUrl);

        exit;
    }
}

function loadSessionData($userInfo)
{
    $userId = ($userInfo['email'] !== $_SESSION['user']['email']) ? preg_replace('/auth0\|/', '', $userInfo['sub']) : $_SESSION['user']['id'];
    // update local user info with auth0 info
    $user = db_row('SELECT id, naam, email, is_admin, lastlogin, lastaction, created, created_by, modified, modified_by, language, deleted, cms_usergroups_id, valid_firstday, valid_lastday FROM cms_users WHERE id = :id', ['id' => $userId]);
    // does user exist in the app db and in the auth0 db
    if ($user) {
        // update last login date and last action
        $lastLogin = date('Y-m-d H:i:s', $userInfo['iat']);
        db_query('UPDATE cms_users SET lastaction = NOW(), lastlogin = :lastLogin WHERE id = :id', ['id' => $user['id'], 'lastLogin' => $lastLogin]);

        loadSessionDataForUser($user);
    } else {
        logout();

        throw new Exception('User not found in database', 404);
    }
}

function deleteAuth0User($user_id)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);
    $auth0UserId = 'auth0|'.intval($user_id);
    $mgmtAPI->users()->delete($auth0UserId);
}

// because users are updated in all kinds of ways and the
// changes are buried within things like generic formhandlers
// we just fetch the user record from the database again
// (after any changes are made), and push the whole lot to auth0
// rather than having any specific actions like change email, disable account etc
// note: you should wrap calls to this in a db transaction so if the API
// call fails, the previous db update is not applied
function updateAuth0UserFromDb($user_id, $set_pwd = false)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);
    $auth0UserId = 'auth0|'.intval($user_id);
    $dbUserData = db_row('
        SELECT 
            u.email, u.naam, u.deleted, u.is_admin, u.cms_usergroups_id, u.valid_firstday, u.valid_lastday,
            ug.organisation_id
        FROM 
            cms_users u
        LEFT JOIN 
            cms_usergroups ug ON u.cms_usergroups_id=ug.id
        WHERE 
            u.id=:id', ['id' => $user_id]);
    $dbUserData['base_ids'] = db_simplearray('SELECT camp_id FROM cms_usergroups_camps WHERE cms_usergroups_id=:ugid', ['ugid' => $dbUserData['cms_usergroups_id']], false, false);

    $auth0UserData = [
        'email' => $dbUserData['email'],
        'name' => $dbUserData['naam'],
        'blocked' => '0000-00-00 00:00:00' != $dbUserData['deleted'] && !is_null($dbUserData['deleted']),
        'app_metadata' => [
            'is_god' => $dbUserData['is_admin'],
            'usergroup_id' => $dbUserData['cms_usergroups_id'],
            'organisation_id' => $dbUserData['organisation_id'],
            'base_ids' => $dbUserData['base_ids'],
        ],
        'connection' => 'Username-Password-Authentication',
    ];
    if ('0000-00-00 00:00:00' != $dbUserData['deleted'] && !is_null($dbUserData['deleted'])) {
        $auth0UserData['app_metadata']['last_blocked_date'] = $dbUserData['deleted'];
        $auth0UserData['email'] = preg_replace('/\.deleted\.\d+/', '', $dbUserData['email']);
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
        throw new Exception($response->getStatusCode(), $response->getReasonPhrase());
    }

    if ($set_pwd) {
        // needed for reseeding test env
        $mgmtAPI->users()->update($auth0UserId, ['password' => $set_pwd]);
    }
}

function updateAuth0Password($user_id, $password)
{
    global $settings;
    $mgmtAPI = getAuth0Management($settings);
    $mgmtAPI->users()->update('auth0|'.intval($user_id), ['password' => $password, 'connection' => 'Username-Password-Authentication']);
}

// Test function used in User Tests and in cron job
function isUserInSyncWithAuth0ByEmail($email)
{
    return isUserInSyncWithAuth0(db_value('SELECT id FROM cms_users WHERE email = :email OR (deleted IS NOT NULL AND email LIKE :deletedEmail)', ['email' => $email, 'deletedEmail' => $email.'.deleted%']));
}

function isUserInSyncWithAuth0($user_id)
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
            u.id=:id', ['id' => $user_id]);
    $dbUser['base_ids'] = db_simplearray('SELECT camp_id FROM cms_usergroups_camps WHERE cms_usergroups_id=:ugid', ['ugid' => $dbUser['cms_usergroups_id']], false, false);
    $auth0User = getAuth0User($user_id);

    if (!$dbUser && !$auth0User) {
        $return_value = true;
    } elseif ($dbUser && $auth0User) {
        $validationResult = [];
        array_push($validationResult, ($auth0User['identities'][0]['user_id'] == $user_id) ? 'true' : 'false');
        array_push($validationResult, ($auth0User['email'] == (preg_match('/\.deleted\.\d+/', $dbUser['email']) ? preg_replace('/\.deleted\.\d+/', '', $dbUser['email']) : $dbUser['email'])) ? 'true' : 'false');
        array_push($validationResult, ($auth0User['name'] == $dbUser['naam']) ? 'true' : 'false');
        array_push($validationResult, ($auth0User['app_metadata']['is_god'] == $dbUser['is_admin']) ? 'true' : 'false');
        array_push($validationResult, ($auth0User['app_metadata']['usergroup_id'] == $dbUser['cms_usergroups_id']) ? 'true' : 'false');
        array_push($validationResult, ($auth0User['app_metadata']['organisation_id'] == $dbUser['organisation_id']) ? 'true' : 'false');
        array_push($validationResult, ($auth0User['app_metadata']['base_ids'] == $dbUser['base_ids']) ? 'true' : 'false');

        if ($dbUser['valid_firstday'] && '0000-00-00' != $dbUser['valid_firstday']) {
            array_push($validationResult, (!empty($auth0User['app_metadata']['valid_firstday']) && $auth0User['app_metadata']['valid_firstday'] == $dbUser['valid_firstday']) ? 'true' : 'false');
        }

        if ($dbUser['valid_lastday'] && '0000-00-00' != $dbUser['valid_lastday']) {
            array_push($validationResult, (!empty($auth0User['app_metadata']['valid_lastday']) && $auth0User['app_metadata']['valid_lastday'] == $dbUser['valid_lastday']) ? 'true' : 'false');
        }

        if ('0000-00-00 00:00:00' != $dbUser['deleted'] && !is_null($dbUser['deleted'])) {
            array_push($validationResult, (!empty($auth0User['app_metadata']['last_blocked_date']) && $auth0User['app_metadata']['last_blocked_date'] == $dbUser['deleted']) ? 'true' : 'false');
            array_push($validationResult, (!empty($auth0User['blocked']) && $auth0User['blocked']) ? 'true' : 'false');
        }

        $return_value = in_array('false', $validationResult) ? false : true;
    } elseif ((!$dbUser || $auth0User) && ($dbUser || !$auth0User)) {
        $return_value = false;
    }

    if (!$return_value) {
        trigger_error('User with id '.$user_id.' is out of sync between DB and Auth0.', E_USER_ERROR);
    }

    return $return_value;
}

function logout()
{
    global $settings;

    session_unset();
    session_destroy();
    $auth0 = getAuth0($settings);
    $auth0->clear();
    $auth0->logout();
}

function logoutWithRedirect()
{
    global $settings;
    $auth0 = getAuth0($settings);
    $session = $auth0->getCredentials();

    if ($session) {
        // Clear the end-user's session, and redirect them to the Auth0 /logout endpoint.
        redirect($auth0->logout());

        exit;
    }
    redirect($auth0->login($settings['auth0_redirect_uri'].'/?action=auth0callback'));

    exit;
}

function loginWithRedirect()
{
    global $settings;
    $auth0 = getAuth0($settings);

    // Clear the local session.
    $auth0->clear();

    // Redirect to Auth0's Universal Login page.
    redirect($auth0->login($settings['auth0_redirect_uri'].'/?action=auth0callback'));

    exit;
}

function sendlogindata($table, $ids)
{
    global $settings;

    $auth0Authentication = getAuth0Authentication($settings);

    foreach ($ids as $id) {
        $email = db_value('SELECT email FROM cms_users WHERE id=:id LIMIT 1', ['id' => $id]);
        // TODO: Auth 0 send for user id $id
        $auth0Authentication->dbconnectionsChangePassword($email, 'Username-Password-Authentication');
    }

    return [true, 'Passwords reset'];
}

// session data requires user, usergroup, organisation, camp
// organistion and usergroup is optional for Boxtribute Gods
function loadSessionDataForUser($user)
{
    $_SESSION['user'] = $user;

    // ----------- load organisation and usergroup
    if ($user['is_admin']) {
        // set organisation depending on url
        if (isset($_GET['organisation'])) {
            $_SESSION['organisation'] = db_row('SELECT * FROM organisations WHERE id = :id AND (NOT organisations.deleted OR organisations.deleted IS NULL)', ['id' => $_GET['organisation']]);
            // unset camp if organisation is changed
            unset($_SESSION['camp']);
        }
    } else {
        $_SESSION['usergroup'] = db_row('
            SELECT ug.*, (SELECT level FROM cms_usergroups_levels AS ul WHERE ul.id = ug.userlevel) AS userlevel 
            FROM cms_usergroups AS ug 
            WHERE ug.id = :id AND (NOT ug.deleted OR ug.deleted IS NULL)', ['id' => $user['cms_usergroups_id']]);
        $_SESSION['organisation'] = db_row('
            SELECT * 
            FROM organisations 
            WHERE id = :id AND (NOT organisations.deleted OR organisations.deleted IS NULL)', ['id' => $_SESSION['usergroup']['organisation_id']]);
    }

    // ------------ load camp
    $camplist = camplist();
    if (1 == count($camplist)) { // an organisation has only one camp or a user has only access to one camp
        $_SESSION['camp'] = reset($camplist);
    } elseif (isset($_GET['camp'], $camplist[$_GET['camp']])) { // the camp is specified in url and the user has access to it
        $_SESSION['camp'] = $camplist[$_GET['camp']];
    } elseif (!isset($_SESSION['camp'])) { // the session did expire
        // the first camp of camplist is selected as a default.
        $_SESSION['camp'] = reset($camplist);
    } elseif (isset($_SESSION['camp']['id']) && $camplist[$_SESSION['camp']['id']]) { // the session did not expire and the user can access the camp
        $_SESSION['camp'] = $camplist[$_SESSION['camp']['id']];
    }

    if ($user['is_admin'] && isset($_SESSION['camp']['id']) && !isset($_SESSION['organisation']['id'])) { //Boxtribute God who selected a camp before an organisation was specified.
        // based on the selected camp the organisation is selected.
        $_SESSION['organisation'] = organisationlist()[$_SESSION['camp']['organisation_id']];
    }

    // Test if session is set properly
    $logout_needed = false;

    try {
        if (!isset($_SESSION['organisation']) || !isset($_SESSION['camp'])) {
            $logout_needed = true;

            throw new Exception('$_SESSION[organisation] or $_SESSION[camp] is not set!');
        }
        if ($_SESSION['organisation']['id'] != $_SESSION['camp']['organisation_id']) {
            $logout_needed = true;

            throw new Exception('$_SESSION[organisation] and $_SESSION[camp] do not match!');
        }
        if (!$user['is_admin']) {
            if ($_SESSION['user']['cms_usergroups_id'] != $_SESSION['usergroup']['id']) {
                $logout_needed = true;

                throw new Exception('$_SESSION[user] and $_SESSION[usergroup] do not match!');
            }
            if ($_SESSION['organisation']['id'] != $_SESSION['usergroup']['organisation_id']) {
                $logout_needed = true;

                throw new Exception('$_SESSION[organisation] and $_SESSION[usergroup] do not match!');
            }
        }
    } finally {
        // logout if SESSION is not set the right way.
        if ($logout_needed) {
            logout();
        }
    }
}

function loginasuser($table, $ids)
{
    $id = $ids[0];
    if ($_SESSION['user2'] or !$_SESSION['user']['is_admin']) {
        throw new Exception('You don\'t have access. Either you are not a Boxtribute God or you are already logged in as a different user!', 403);
    }
    $_SESSION['user2'] = $_SESSION['user'];
    $_SESSION['camp2'] = $_SESSION['camp'];
    $_SESSION['usergroup2'] = $_SESSION['usergroup'];
    $_SESSION['organisation2'] = $_SESSION['organisation'];

    $user = db_row('SELECT * FROM cms_users WHERE id=:id', ['id' => $id]);
    loadSessionDataForUser($user);

    $success = true;
    $message = 'Logged in as '.$_SESSION['user']['naam'];

    return [$success, $message, true];
}

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

function getAuth0User($userId)
{
    try {
        global $settings;
        $mgmtAPI = getAuth0Management($settings);
        $response = $mgmtAPI->users()->get('auth0|'.intval($userId));

        if (HttpResponse::wasSuccessful($response)) {
            return HttpResponse::decodeContent($response);
        }
    } catch (Auth0Exception $e) {
        // user doesn't exist in auth0
        if (404 == $e->getCode()) {
            return null;
        }
    }
}
