<?

# returns true if the current user is allowed to give drops (he is allowed when his usergroup has access to Give Drops to all function)
function allowgivedrops()
{
	return $_SESSION['user']['is_admin'] || db_value('SELECT id FROM cms_functions AS f, cms_usergroups_functions AS uf WHERE uf.cms_functions_id = f.id AND f.include = "give2all" AND uf.cms_usergroups_id = :usergroup', array('usergroup' => $_SESSION['usergroup']['id']));
}

function camplist($short = false)
{
	if ($_SESSION['user']['is_admin']) {
		$camplist = db_array('SELECT c.* FROM camps AS c WHERE (NOT c.deleted OR c.deleted IS NULL) AND organisation_id = :organisation_id ORDER BY c.seq', array('organisation_id' => $_SESSION['organisation']['id']));
	} else {
		$camplist = db_array('SELECT c.* FROM camps AS c, cms_usergroups_camps AS x WHERE (NOT c.deleted OR c.deleted IS NULL) AND c.organisation_id = :organisation_id AND x.camp_id = c.id AND x.cms_usergroups_id = :usergroup ORDER BY c.seq', array('usergroup' => $_SESSION['usergroup']['id'], 'organisation_id' => $_SESSION['organisation']['id']));
	}
	if ($short) {
		foreach ($camplist as $c) $list[] = $c['id'];
		return $list;
	}
	return $camplist;
}

function getcampdata($id)
{
	if ($_SESSION['user']['is_admin']) {
		$_SESSION['camp'] = db_row('SELECT c.* FROM camps AS c WHERE (NOT c.deleted OR c.deleted IS NULL) AND organisation_id = :organisation_id AND c.id = :camp ORDER BY c.seq', array('camp' => $id, 'organisation_id' => $_SESSION['organisation']['id']));
	} else {
		$_SESSION['camp'] = db_row('SELECT c.* FROM camps AS c, cms_usergroups_camps AS x WHERE (NOT c.deleted OR c.deleted IS NULL) AND organisation_id = :organisation_id AND c.id = x.camp_id AND c.id = :camp AND x.cms_usergroups_id = :usergroup ORDER BY c.seq', array('camp' => $id, 'usergroup' => $_SESSION['usergroup']['id'], 'organisation_id' => $_SESSION['organisation']['id']));
	}
}

#this function verifies if a people id belongs to a camp that the current user has access to.
function verify_campaccess_people($id)
{
	if (!$id) return;
	$camp_id = db_value('SELECT camp_id FROM people WHERE id = :id', array('id' => $id));
	$camps = camplist(true);
	if (!in_array($camp_id, $camps)) {
		trigger_error("You don't have access to this record");
	}
}

#this function verifies if a location id belongs to a camp that the current user has access to.
function verify_campaccess_location($id)
{
	if (!$id) return;
	$camp_id = db_value('SELECT camp_id FROM locations WHERE id = :id', array('id' => $id));
	$camps = camplist(true);
	if (!in_array($camp_id, $camps)) {
		trigger_error("You don't have access to this record");
	}
}

function verify_deletedrecord($table, $id)
{
	if (db_value('SELECT IF(NOT deleted OR deleted IS NULL,0,1) FROM ' . $table . ' WHERE id = :id', array('id' => $id))) trigger_error("This record does not exist");
}

function check_valid_from_until_date($valid_from, $valid_until)
{
	$today = new DateTime();
	$success = true;
	$message = '';

	if ($valid_from && (substr($valid_from, 0, 9) === '0000-00-00')) {
		$valid_firstday = new DateTime($valid_from);
		if ($today < $valid_firstday) {
			$success = false;
			$message = "This user account is not yet valid.";
		}
	}
	if ($valid_until && (substr($valid_until, 0, 9) === '0000-00-00')) {
		$valid_lastday = new DateTime($valid_until);
		if ($today > $valid_lastday) {
			$success = false;
			$message = "This user account is expired.";
		}
	}
	return array('success' => $success, 'message' => $message);
}

function sendlogindata($table, $ids) {
	global $translate, $settings;
	
	foreach ($ids as $id) {
		$row = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));

		$newpassword = createPassword();

		$mail = $translate['cms_sendlogin_mail'];
		$mail = str_ireplace('{sitename}',$translate['site_name'].' ('.$_SERVER['HTTP_HOST'].$settings['rootdir'].')',$mail);
		$mail = str_ireplace('{password}',$newpassword,$mail);

		$result = sendmail($row['email'], $row['naam'], $translate['cms_sendlogin_mailsubject'], $mail);
		if($result) {
			$message = $result;
			$succes = false;
		} else {
			$success = true;
			db_query('UPDATE '.$table.' SET pass = :pass WHERE id = :id',array('pass'=>md5($newpassword),'id'=>$id));
			$message = "ahoj";
		}
	}

	return array($success,$message);
}

function createPassword($length = 10, $possible = '23456789AaBbCcDdEeFfGgHhijJkKLMmNnoPpQqRrSsTtUuVvWwXxYyZz!$-_@#%^*()+=') {
	$password = "";
	 $i = 0;
	while ($i < $length) {
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		if (!strstr($password, $char)) {
			$password .= $char;
			$i++;
		}
	}
	return $password;
}