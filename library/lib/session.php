<?php

function authorize()
{
    $result = ['success' => true];

    $user = false;
    if (isset($_SESSION['auth0__user']['email'])) {// user has been authenticated
        $user = db_row('SELECT id, naam, organisation_id, email, is_admin, lastlogin, lastaction, created, created_by, modified, modified_by, language, deleted, cms_usergroups_id, valid_firstday, valid_lastday FROM cms_users WHERE email = :email', ['email' => $_SESSION['auth0__user']['email']]);
        if ($user) { // does user exist in the app db and in the auth0 db
            if (check_valid_from_until_date($user['valid_firstday'], $user['valid_lastday'])) { // is the user account still valid?
                loadSessionData($user);
            } else {
                throw new Exception(GENERIC_LOGIN_ERROR);
            }
        } else {
            throw new Exception('No user found connected to authenticated email!');
        }
    } else {
        throw new Exception('You are not authenticated!');
    }

    return $result;
}

function logout($redirect = false)
{
    global $settings;

    session_unset();
    session_destroy();

    header('Location: https://'.$settings['auth0_domain'].'/v2/logout', true, 301); //$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']
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

function checkUserAuth0($token, $domain, $email)
{
    $data = ['email' => $email];
    $postvars = '';

    foreach ($data as $key => $value) {
        $postvars .= '?'.$key.'='.$value;
    }

    //$postvars = json_encode($data);
    $cu = curl_init();

    $urlstring = 'https://'.$domain.'/api/v2/users-by-email'.str_replace('@', '%40', $postvars);
    curl_setopt($cu, CURLOPT_URL, $urlstring);
    curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cu, CURLOPT_HTTPHEADER, [
            'content-type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$token, ]);
    $userstring = curl_exec($cu);

    return json_decode($userstring, true);
}

function registerUserAuth0($auth0_domain, $client_id, $client_secret, $email, $connection)
{
    $management_token = getManagmentToken($client_id, $client_secret, $auth0_domain);
    $token = $management_token['access_token'];
    $created_user = createUserAuth0($token, $email, 'As1234asdf!', $connection, $auth0_domain);
    $user_id = $created_user['user_id'];
    $password_reset = resetPasswordAuth0($auth0_domain, $client_id, $email, $connection);
}
function getManagmentToken($client_id, $client_secret, $auth0_domain)
{
    $curlcon = curl_init();
    $data = ['client_id' => $client_id,
            'client_secret' => $client_secret,
            'audience' => 'https://'.$auth0_domain.'/api/v2/',
            'grant_type' => 'client_credentials', ];
    $postvars = '';
    foreach ($data as $key => $value) {
        $postvars .= $key.'='.$value.'&';
    }
    curl_setopt($curlcon, CURLOPT_URL, 'https://'.$auth0_domain.'/oauth/token');
    curl_setopt($curlcon, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlcon, CURLOPT_POST, 1);
    curl_setopt($curlcon, CURLOPT_POSTFIELDS, $postvars);
    curl_setopt($curlcon, CURLOPT_ENCODING, '');
    curl_setopt($curlcon, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded', ]);
    //'Authorization: Bearer '.$_POST['access_token'], ]);
    $userstring = curl_exec($curlcon);

    return json_decode($userstring, true);
}
function createUserAuth0($token, $email, $password, $connection, $auth0_domain)
{
    $data = ['email' => $email,
            'connection' => $connection,
            'password' => $password, ];
    $postvars = '';
    /*
    foreach ($data as $key => $value) {
        $postvars .= $key.'='.$value.'&';
    }
    */
    $postvars = json_encode($data);
    $cu = curl_init();
    curl_setopt($cu, CURLOPT_URL, 'https://'.$auth0_domain.'/api/v2/users');
    curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cu, CURLOPT_POST, 1);
    curl_setopt($cu, CURLOPT_POSTFIELDS, $postvars);
    curl_setopt($cu, CURLOPT_ENCODING, '');
    curl_setopt($cu, CURLOPT_HTTPHEADER, [
        'Content-type: application/json',
        'Authorization: Bearer '.$token, ]);
    $info = curl_getinfo($cu);
    $userstring = curl_exec($cu);

    return json_decode($userstring, true);
}

function resetPasswordAuth0($auth0_domain, $auth0_client_id, $email, $connection)
{
    $data = ['connection' => $connection,
            'email' => $email,
            'client_id' => $auth0_client_id, ];
    $postvars = '';

    foreach ($data as $key => $value) {
        $postvars .= $key.'='.$value.'&';
    }

    $postvars = json_encode($data);
    $cu = curl_init();
    curl_setopt($cu, CURLOPT_URL, 'https://'.$auth0_domain.'/dbconnections/change_password');
    curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cu, CURLOPT_POST, 1);
    curl_setopt($cu, CURLOPT_POSTFIELDS, $postvars);
    curl_setopt($cu, CURLOPT_ENCODING, '');
    curl_setopt($cu, CURLOPT_HTTPHEADER, [
        'content-type: application/json', ]);
    //'Authorization: Bearer '.$token, ]);
    $userstring = curl_exec($cu);

    return json_decode($userstring, true);
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
// organistion and usergroup is optional for Boxwise Gods
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

    if ($user['is_admin'] && isset($_SESSION['camp']['id']) && !isset($_SESSION['organisation']['id'])) { //Boxwise God who selected a camp before an organisation was specified.
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
        throw new Exception('You don\'t have access. Either you are not a Boxwise God or you are already logged in as a different user!');
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
