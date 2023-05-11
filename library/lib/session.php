<?php

require 'vendor/autoload.php';

require_once 'auth0.php';
use Auth0\SDK\Exception\StateException;

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

// Test function used in User Tests and in cron job
function isUserInSyncWithAuth0ByEmail($email)
{
    return isUserInSyncWithAuth0(db_value('SELECT id FROM cms_users WHERE email = :email OR (deleted IS NOT NULL AND email LIKE :deletedEmail)', ['email' => $email, 'deletedEmail' => $email.'.deleted%']));
}

function logout($returnTo = null)
{
    global $settings;

    session_unset();
    session_destroy();
    $auth0 = getAuth0($settings);
    $url = $auth0->logout();

    if ($returnTo) {
        redirect($returnTo);
    }
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
