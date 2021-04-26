<?php

require 'vendor/autoload.php';
use Auth0\SDK\Auth0;

function authorize($settings, $mobile, $ajax)
{
    global $settings;
    $result = ['success' => true];
    $auth0 = new Auth0([
        'domain' => $settings['auth0_domain'],
        'client_id' => $settings['auth0_client_id'],
        'client_secret' => $settings['auth0_client_secret'],
        'redirect_uri' => $settings['auth0_redirect_uri'],
    ]);

    $userInfo = $auth0->getUser();
    if (!$userInfo) {
        if ($ajax) {
            echo json_encode(['success' => false]);
        } elseif ($mobile) {
            $auth0->login(null, null, ['redirect_uri' => $settings['auth0_redirect_uri'].'/mobile.php']);
        } else {
            $auth0->login();
        }

        return;
    }
    $user = db_row('SELECT id, naam, email, is_admin, lastlogin, lastaction, created, created_by, modified, modified_by, language, deleted, cms_usergroups_id, valid_firstday, valid_lastday FROM cms_users WHERE email = :email', ['email' => $_SESSION['auth0__user']['email']]);
    if ($user) { // does user exist in the app db and in the auth0 db
        if (check_valid_from_until_date($user['valid_firstday'], $user['valid_lastday'])) { // is the user account still valid?
            loadSessionData($user);
        } else {
            throw new Exception(GENERIC_LOGIN_ERROR);
        }
    } else {
        throw new Exception('No user found connected to authenticated email!');
    }
}

function logout()
{
    global $settings;

    session_unset();
    session_destroy();
    $auth0 = new Auth0([
        'domain' => $settings['auth0_domain'],
        'client_id' => $settings['auth0_client_id'],
        'client_secret' => $settings['auth0_client_secret'],
        'redirect_uri' => $settings['auth0_redirect_uri'],
    ]);
    $auth0->logout();
    // the auth0 client method
    // $auth0->logout() simply clears local variables
    // so we also redirect to auth0
    redirect('https://'.$settings['auth0_domain'].'/v2/logout?client_id='.$settings['auth0_client_id'].'&returnTo='.urlencode($settings['auth0_redirect_uri']));
}

function check_valid_from_until_date($valid_from, $valid_until)
{
    $today = new DateTime();
    $success = true;

    if ($valid_from && ('0000-00-00' != substr($valid_from, 0, 10))) {
        $valid_firstday = new DateTime($valid_from);
        if ($today < $valid_firstday) {
            $success = false;
        }
    }
    if ($valid_until && ('0000-00-00' != substr($valid_until, 0, 10))) {
        $valid_lastday = new DateTime($valid_until);
        if ($today > $valid_lastday) {
            $success = false;
        }
    }

    return $success;
}

function sendlogindata($table, $ids)
{
    global $translate, $settings;

    foreach ($ids as $id) {
        $row = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $id]);

        $newpassword = createPassword();

        $mail = $translate['cms_sendlogin_mail'];
        $mail = str_ireplace('{sitename}', $_SERVER['HTTP_HOST'], $mail);
        $mail = str_ireplace('{password}', $newpassword, $mail);
        $mail = str_ireplace('{orgname}', $_SESSION['organisation']['label'], $mail);
        $mail = str_ireplace('{user}', $_SESSION['user']['naam'], $mail);

        $result = sendmail($row['email'], $row['naam'], $translate['cms_sendlogin_mailsubject'], $mail);
        if ($result) {
            $message = $result;
            $success = false;
        } else {
            $success = true;
            db_query('UPDATE '.$table.' SET pass = :pass WHERE id = :id', ['pass' => md5($newpassword), 'id' => $id]);
            $message = translate('cms_sendlogin_confirm');
        }
    }

    return [$success, $message];
}

function createPassword($length = 10, $possible = '23456789AaBbCcDdEeFfGgHhijJkKLMmNnoPpQqRrSsTtUuVvWwXxYyZz!$-_@#%^*()+=')
{
    $password = '';
    $i = 0;
    while ($i < $length) {
        $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
        if (!strstr($password, $char)) {
            $password .= $char;
            ++$i;
        }
    }

    return $password;
}

// session data requires user, usergroup, organisation, camp
// organistion and usergroup is optional for Boxtribute Gods
function loadSessionData($user)
{
    $_SESSION['user'] = $user;
    // update last action
    db_query('UPDATE cms_users SET lastaction = NOW() WHERE id = :id', ['id' => $user['id']]);

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
        throw new Exception('You don\'t have access. Either you are not a Boxtribute God or you are already logged in as a different user!');
    }
    $_SESSION['user2'] = $_SESSION['user'];
    $_SESSION['camp2'] = $_SESSION['camp'];
    $_SESSION['usergroup2'] = $_SESSION['usergroup'];
    $_SESSION['organisation2'] = $_SESSION['organisation'];
    $user = db_row('SELECT * FROM cms_users WHERE id=:id', ['id' => $id]);
    loadSessionData($user);
    $success = true;
    $message = 'Logged in as '.$_SESSION['user']['naam'];

    return [$success, $message, true];
}
