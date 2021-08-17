<?php

$table = 'cms_users';

if ($_SESSION['user']['is_admin'] || $_SESSION['usergroup']['userlevel'] > db_value('SELECT MIN(level) FROM cms_usergroups_levels')) {
    if ($_POST) {
        // Validate if E-mail is already active
        $existinguser = db_row('
			SELECT u.id, ug.organisation_id, ugl.level AS userlevel
			FROM cms_users u
			LEFT JOIN cms_usergroups ug ON ug.id=u.cms_usergroups_id
			LEFT JOIN cms_usergroups_levels AS ugl ON ugl.id=ug.userlevel
			WHERE (NOT u.deleted OR u.deleted = NULL) AND (NOT ug.deleted OR ug.deleted IS NULL) AND email = :email', ['email' => $_POST['email']]);
        if ($existinguser && ($existinguser['id'] != $_POST['id'])) {
            if ($existinguser['organisation_id'] != $_SESSION['organisation']['id']) {
                redirect('?action=cms_users&warning=1&message=This email already exists in another organisation.<br>Please use a different email to create a new user!');
                trigger_error('This email already exists in another organisation.<br>Please use a different email to create a new user!', E_USER_NOTICE);
            } elseif (!$_SESSION['user']['is_admin'] && ($_SESSION['usergroup']['userlevel'] <= $existinguser['userlevel'])) {
                redirect('?action=cms_users&warning=1&message=This email already exists in your organisation. You do not have access to this account.');
            } else {
                redirect('?action=cms_users_edit&id='.$existinguser['id'].'&origin='.$_POST['_origin'].'&warning=1&message=This email already exists in your organisation. You are forwarded to the corresponding account.');
            }
        }

        // Validate if E-mail is already deactivated and in used before
        $deactiveduser = db_row('SELECT u.id FROM cms_users u WHERE email LIKE :email', ['email' => $_POST['email'].'.deleted%']);
        if ($deactiveduser && ($deactiveduser['id'] != $_POST['id'])) {
            redirect('?action=cms_users_edit&origin='.$_POST['_origin'].'&warning=1&message=This email already exists in another organisation.<br>Please use a different email to create a new user!');
            trigger_error('This email already exists in another organisation.<br>Please use a different email to create a new user!', E_USER_NOTICE);
        }
        // check auth0 if an email addrress is already created but with different id
        global $settings;
        $mgmtAPI = getAuth0Management($settings);
        $authUser = $mgmtAPI->usersByEmail()->get([
            'email' => $_POST['email'],
        ]);

        if ($authUser && $authUser[0]['blocked']) {
            redirect('?action=cms_users_edit&origin='.$_POST['_origin'].'&warning=1&message=This email already exists in the system. Please use a different email to create a new user!');
            trigger_error('This email already exists in the system. Please use a different email to create a new user!', E_USER_NOTICE);
        } elseif (!$existinguser && $authUser && !$authUser[0]['blocked']) {
            throw new Exception('The user already exists in AUTH0 but its not sync', 409);
        }

        // Validate if E-mail address is correct
        if ($_POST['email'] && !checkEmail($_POST['email'])) {
            redirect('?action=cms_users_edit&id='.$existinguser['id'].'&origin='.$_POST['_origin'].'&warning=1&message=This email is not valid');
        }

        // Validate if user can access this user account
        // TODO Coordinator can see volunteers of other bases of the same organization.
        $posteduser = db_row('
			SELECT ug.organisation_id, ugl.level AS userlevel
			FROM cms_usergroups AS ug
			LEFT JOIN cms_usergroups_levels AS ugl ON ugl.id=ug.userlevel
            WHERE ug.id = :id AND (NOT ug.deleted OR ug.deleted IS NULL)', ['id' => $_POST['cms_usergroups_id'][0]]);
        $is_admin = $_SESSION['user']['is_admin'];
        $organisation_allowed = ($_SESSION['organisation']['id'] == $posteduser['organisation_id']);
        $userlevel_allowed = ($_SESSION['usergroup']['userlevel'] > $posteduser['userlevel']);

        if ($is_admin || ($organisation_allowed && $userlevel_allowed)) {
            $keys = ['naam', 'email', 'cms_usergroups_id', 'valid_firstday', 'valid_lastday'];
            $userId = db_transaction(function () use ($table, $keys, $userId) {
                $handler = new formHandler($table);
                $userId = $handler->savePost($keys);
                updateAuth0UserFromDb($userId);

                return $userId;
            });
            $row = db_row('SELECT * FROM '.$table.' WHERE id = :id ', ['id' => $_SESSION['user']['id']]);
            $_SESSION['user'] = array_merge($_SESSION['user'], $row);
            if (!$existinguser) {
                [$success, $message] = sendlogindata($_POST['_origin'], ['id' => $userId]);
                if ($success) {
                    // defining own message because returned one sounds as if user resetted the password themselves
                    $message = 'User will receive an email with instructions and their password within couple of minutes!';
                }
                redirect('?action='.$_POST['_origin'].'&message='.$message);
            } else {
                redirect('?action='.$_POST['_origin']);
            }
        } else {
            throw new Exception('You do not have the rights to change this user!', 403);
        }
    }

    // collect data for the form
    $data = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $id]);

    // Check if user can access this user account
    // TODO Coordinator can see volunteers of other bases of the same organization.
    $requesteduser = db_row('
		SELECT ug.organisation_id, ugl.level AS userlevel
		FROM cms_usergroups AS ug
		LEFT OUTER JOIN cms_usergroups_levels AS ugl ON ugl.id=ug.userlevel
		WHERE ug.id = :id AND (NOT ug.deleted OR ug.deleted IS NULL)', ['id' => $data['cms_usergroups_id']]);
    if (!$_SESSION['user']['is_admin'] && ($data && ($data['is_admin'] || ($_SESSION['organisation']['id'] != $requesteduser['organisation_id']) || ($_SESSION['usergroup']['userlevel'] <= $requesteduser['userlevel'])))) {
        throw new Exception('You do not have access to this user!', 403);
    }

    // open the template
    $cmsmain->assign('include', 'cms_form.tpl');

    // put a title above the form
    $cmsmain->assign('title', $translate['cms_user']);

    // define tabs
    addfield('text', $translate['cms_users_naam'], 'naam', ['required' => true, 'testid' => 'user_name']);
    addfield('email', $translate['cms_users_email'], 'email', ['required' => true, 'tooltip' => $translate['cms_users_email_tooltip'], 'testid' => 'user_email', 'repeat' => !$data]);

    $usergroups = db_array('
		SELECT ug.id AS value, ug.label 
		FROM cms_usergroups AS ug
		LEFT OUTER JOIN cms_usergroups_levels AS ugl ON (ugl.id=ug.userlevel)
		WHERE ug.organisation_id = :organisation_id AND (ugl.level < :userlevel OR :is_admin) AND (NOT ug.deleted OR ug.deleted IS NULL)
		ORDER BY ug.label', ['organisation_id' => $_SESSION['organisation']['id'], 'userlevel' => $_SESSION['usergroup']['userlevel'], 'is_admin' => $_SESSION['user']['is_admin']]);
    addfield('select', 'Select user group', 'cms_usergroups_id', ['required' => true, 'options' => $usergroups, 'testid' => 'user_group']);

    addfield('line');
    addfield('date', 'Valid from', 'valid_firstday', ['date' => true, 'time' => false, 'testid' => 'user_valid_from']);
    addfield('date', 'Valid until', 'valid_lastday', ['date' => true, 'time' => false, 'testid' => 'user_valid_to']);

    addfield('line');
    if ('0000-00-00 00:00:00' == $data['lastlogin']) {
        $data['lastlogin'] = '';
    }
    addfield('info', $translate['cms_users_lastlogin'], 'lastlogin', ['date' => 'true', 'time' => 'true', 'testid' => 'user_last_login']);
    addfield('line');

    addfield('created', 'Gemaakt', 'created', ['aside' => true, 'testid' => 'user_created_data']);

    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
} else {
    trigger_error('You do not have access to this menu. Please ask your admin to change this!', 403);
}
