<?php

function login($email, $pass, $autologin, $mobile = false)
{
    global $settings;
    $pass = md5($pass);

    $user = db_row('SELECT *, "org" AS usertype FROM cms_users WHERE email != "" AND email = :email AND (NOT deleted OR deleted IS NULL)', ['email' => $email]);

    if ($user) { //e-mailaddress exists in database
        if ($user['pass'] == $pass) { // password is correct
            // Check if account is not expired
            $in_valid_dates = check_valid_from_until_date($user['valid_firstday'], $user['valid_lastday']);
            $success = $in_valid_dates['success'];
            $message = $in_valid_dates['message'];

            if ($success) {
                $loadsessionresult = loadSessionData($user);

                db_query('UPDATE cms_users SET lastlogin = NOW() WHERE id = :id', ['id' => $user['id']]);
                logfile(($mobile ? 'Mobile user ' : 'User ').'logged in with '.$_SERVER['HTTP_USER_AGENT']);

                if (isset($autologin)) {
                    setcookie('autologin_user', $email, time() + (3600 * 24 * 90), '/');
                    setcookie('autologin_pass', $pass, time() + (3600 * 24 * 90), '/');
                } else {
                    setcookie('autologin_user', null, time() - 3600, '/');
                    setcookie('autologin_pass', null, time() - 3600, '/');
                }

                if (isset($_GET['destination']) && $loadsessionresult) {
                    $redirect = '/'.urldecode($_GET['destination']);
                } else {
                    $redirect = '/?action=start';
                }
            }
        } else { // password is not correct
            $success = false;
            $message = INCORRECT_LOGIN_ERROR;
            $redirect = false;
            logfile('Attempt to login with '.($mobile ? 'mobile and ' : '').' wrong password for '.$email);
        }
    } else { // user not found
        $success = false;
        $redirect = false;
        $deleted = db_value('SELECT email FROM cms_users WHERE email != "" AND email LIKE "'.$_POST['email'].'%" AND deleted Limit 1');
        if ($deleted) {
            $message = GENERIC_LOGIN_ERROR;
            logfile('Attempt to login '.($mobile ? 'with mobile ' : '').'as deleted user '.$email);
        } else {
            $message = GENERIC_LOGIN_ERROR;
            logfile('Attempt to login '.($mobile ? 'with mobile ' : '').'as unknown user '.$email);
        }
    }

    return ['success' => $success, 'message' => $message, 'redirect' => $redirect];
}

function checksession()
{
    global $settings;
    $result = ['success' => true];

    $user = false;
    if (isset($_SESSION['user'])) { // a valid session exists
        $user = db_row('SELECT * FROM cms_users WHERE id = :id', ['id' => $_SESSION['user']['id']]);
    } elseif (isset($_COOKIE['autologin_user'])) { // a autologin cookie exists
        $user = db_row('SELECT * FROM cms_users WHERE email != "" AND email = :email AND pass = :pass', ['email' => $_COOKIE['autologin_user'], 'pass' => $_COOKIE['autologin_pass']]);
    }

    // check if user was loaded
    if ($user) {
        // Check if account is not expired
        $in_valid_dates = check_valid_from_until_date($user['valid_firstday'], $user['valid_lastday']);
        if (!$in_valid_dates['success']) {
            $result['success'] = false;
            $result['redirect'] = '/login.php?destination='.urlencode($_SERVER['REQUEST_URI']);
            $result['message'] = $in_valid_dates['message'];
        } elseif (!loadSessionData($user)) { // Check if camp and organisation SESSION variable was loaded
            $result['redirect'] = '/?action=start';
        }
    } else {
        $result['success'] = false;
        $result['redirect'] = '/login.php?destination='.urlencode($_SERVER['REQUEST_URI']);
        if (isset($_COOKIE['autologin_user'])) { //cookie is invalid
            setcookie('autologin_user', null, time() - 3600, '/');
            setcookie('autologin_pass', null, time() - 3600, '/');
        }
    }

    return $result;
}

function logout($redirect = false)
{
    global $settings;

    setcookie('autologin_user', null, time() - 3600, '/');
    setcookie('autologin_pass', null, time() - 3600, '/');

    session_unset();
    session_destroy();

    if (!$redirect) {
        $redirect = '?';
    }
    redirect($redirect);
}

function check_valid_from_until_date($valid_from, $valid_until)
{
    $today = new DateTime();
    $success = true;
    $message = '';

    if ($valid_from && ('0000-00-00' != substr($valid_from, 0, 10))) {
        $valid_firstday = new DateTime($valid_from);
        if ($today < $valid_firstday) {
            $success = false;
            $message = GENERIC_LOGIN_ERROR;
        }
    }
    if ($valid_until && ('0000-00-00' != substr($valid_until, 0, 10))) {
        $valid_lastday = new DateTime($valid_until);
        if ($today > $valid_lastday) {
            $success = false;
            $message = GENERIC_LOGIN_ERROR;
        }
    }

    return ['success' => $success, 'message' => $message];
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
//usergroup is optional for Boxwise Gods
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
    } elseif ($user['is_admin'] && isset($_GET['camp']) && !isset($camplist)) { // the user is a Boxwise God and camplist is not set ($_SESSION['organisation'] is not set) and the camp is specified in the url
        $_SESSION['camp'] = db_row('
                SELECT * 
                FROM camps
                WHERE id = :id AND (NOT camps.deleted OR camps.deleted IS NULL)', ['id' => $_GET['camp']]);
        $_SESSION['organisation'] = db_row('
                SELECT * 
                FROM organisations 
                WHERE id = :id AND (NOT organisations.deleted OR organisations.deleted IS NULL)', ['id' => $_SESSION['camp']['organisation_id']]);
    } elseif (!isset($_SESSION['camp']) && isset($camplist)) { // the session did expire and camplist is set
        $_SESSION['camp'] = reset($camplist);
    } elseif (isset($_SESSION['camp']['id'], $camplist[$_SESSION['camp']['id']])) { // the session did not expire and the user can access the camp
        $_SESSION['camp'] = $camplist[$_SESSION['camp']['id']];
    } else {
        // user has access to more than one camp AND
        // (no camp is specified in GET or the specified camp is not accessible by the user) AND
        // ((the session expired and camplist is not set (only possible for Boxwise Gods if $_SESSION['organisation'] is not set) OR
        // (the session did not expire, but the user has no access to the camp specified in $_SESSION))
        return false;
    }

    // Test if session is set properly
    if (!isset($_SESSION['organisation']) || !isset($_SESSION['camp'])) {
        throw new Exception('$_SESSION[organisation] or $_SESSION[camp] is not set!');
    }

    return true;
}

function loginasuser($table, $ids)
{
    $id = $ids[0];
    if ($_SESSION['user2'] or !$_SESSION['user']['is_admin']) {
        throw new Exception('You don\'t have access. Either you are not a Boxwise God or you are already logged in as a different user!');
    }
    $_SESSION['user2'] = $_SESSION['user'];
    $_SESSION['camp2'] = $_SESSION['camp'];
    $_SESSION['usergroup2'] = $_SESSION['usergroup'];
    $_SESSION['organisation2'] = $_SESSION['organisation'];
    $user = db_row('SELECT * FROM cms_users WHERE id=:id', ['id' => $id]);
    if (!loadSessionData($user)) {
        throw new Exception('$_SESSION data not fully loaded!');
    }
    $success = true;
    $message = 'Logged in as '.$_SESSION['user']['naam'];

    return [$success, $message, true];
}
