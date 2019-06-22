<?php
$table = 'cms_users';

if ($_SESSION['user']['is_admin'] || $_SESSION['usergroup']['userlevel'] > db_value('SELECT MIN(level) FROM cms_usergroups_levels')) {

	if ($_POST) {

		# Validate if E-mail is already active
		$existinguser = db_row('
			SELECT u.id, ug.organisation_id, ugl.level AS userlevel
			FROM cms_users u
			LEFT JOIN cms_usergroups ug ON ug.id=u.cms_usergroups_id
			LEFT JOIN cms_usergroups_levels AS ugl ON ugl.id=ug.userlevel
			WHERE (NOT deleted OR deleted = NULL) AND email = :email', array('email' => $_POST['email']));
		if ($existinguser && ($existinguser['id'] != $_POST['id'])) {
			if ($existinguser['organisation_id'] != $_SESSION['organisation']['id']) {
				redirect('?action=cms_users&warning=1&message=This email already exists in another organisation.');
			} elseif (!$_SESSION['user']['is_admin'] && ($_SESSION['usergoup']['userlevel'] <= $existinguser['userlevel'])) {
				redirect('?action=cms_users&warning=1&message=This email already exists in your organisation. You do not have access to this account.');
			} else {
				redirect('?action=cms_users_edit&id=' . $existinguser['id'] . '&origin=' . $_POST['_origin'] . '&warning=1&message=This email already exists. You are forwarded to the corresponding account.');
			}
		}

		# Validate if user can access this user account
		# TODO Coordinator can see volunteers of other bases of the same organization.
		$posteduser = db_row('
			SELECT ug.organisation_id, ugl.level AS userlevel
			FROM cms_usergroups AS ug
			LEFT JOIN cms_usergroups_levels AS ugl ON ugl.id=ug.userlevel
			WHERE ug.id = :id', array('id' => $_POST['cms_usergroups_id']));
		if ($_SESSION['user']['is_admin'] || (($_SESSION['organisation']['id'] != $posteduser['organisation_id']) || ($_SESSION['usergroup']['userlevel'] <= $posteduser['userlevel']))) {
			$keys = array('naam', 'email', 'cms_usergroups_id', 'valid_firstday', 'valid_lastday');

			$handler = new formHandler($table);
			$userId = $handler->savePost($keys);
			$row = db_row('SELECT * FROM ' . $table . ' WHERE id = :id ', array('id' => $_SESSION['user']['id']));
			$_SESSION['user'] = array_merge($_SESSION['user'], $row);
			if(!$existinguser){
				sendlogindata($_POST['_origin'], array('id'=>$userId));
			}
			redirect('?action=' . $_POST['_origin']);
		} else {
			redirect('?action=' . $_POST['_origin'] . '&warning=1&message=You do not have the rights to change this user!');
		}
	}

	// collect data for the form
	$data = db_row('SELECT * FROM ' . $table . ' WHERE id = :id', array('id' => $id));

	# Check if user can access this user account
	# TODO Coordinator can see volunteers of other bases of the same organization.
	$requesteduser = db_row('
		SELECT ug.organisation_id, ugl.level AS userlevel
		FROM cms_usergroups AS ug
		LEFT OUTER JOIN cms_usergroups_levels AS ugl ON ugl.id=ug.userlevel
		WHERE ug.id = :id', array('id' => $data['cms_usergroups_id']));
	if (!$_SESSION['user']['is_admin'] && ($data && ($data['is_admin'] || ($_SESSION['organisation']['id'] != $requesteduser['organisation_id']) || ($_SESSION['usergroup']['userlevel'] <= $requesteduser['userlevel'])))) {
		redirect('?action=' . $_GET['origin']);
	}

	// open the template
	$cmsmain->assign('include', 'cms_form.tpl');

	// put a title above the form
	$cmsmain->assign('title', $translate['cms_user']);

	// define tabs
	addfield('text', $translate['cms_users_naam'], 'naam', array('required' => true));
	addfield('email', $translate['cms_users_email'], 'email', array('required' => true, 'tooltip' => $translate['cms_users_email_tooltip']));

	$usergroups = db_array('
		SELECT ug.id AS value, ug.label 
		FROM cms_usergroups AS ug
		LEFT OUTER JOIN cms_usergroups_levels AS ugl ON (ugl.id=ug.userlevel)
		WHERE ug.organisation_id = :organisation_id AND (ugl.level < :userlevel OR :is_admin)
		ORDER BY ug.label', array('organisation_id' => $_SESSION['organisation']['id'], 'userlevel' => $_SESSION['usergroup']['userlevel'], 'is_admin' => $_SESSION['user']['is_admin']));
	addfield('select', 'Select user group', 'cms_usergroups_id', array('required' => true, 'options' => $usergroups));

	addfield('line');
	addfield('date', 'Valid from', 'valid_firstday', array('date' => true, 'time' => false));
	addfield('date', 'Valid until', 'valid_lastday', array('date' => true, 'time' => false));

	addfield('line');
	if ($data['lastlogin'] == '0000-00-00 00:00:00') $data['lastlogin'] = '';
	addfield('info', $translate['cms_users_lastlogin'], 'lastlogin', array('date' => 'true', 'time' => 'true'));
	addfield('line');

	addfield('created', 'Gemaakt', 'created', array('aside' => true));

	$cmsmain->assign('data', $data);
	$cmsmain->assign('formelements', $formdata);
} else {
	trigger_error('You do not have access to this menu. Please ask your admin to change this!');
}
