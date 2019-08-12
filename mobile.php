<?php

# Check session
$mobile = true;
require_once('library/core.php');

date_default_timezone_set('Europe/Athens');
db_query('SET time_zone = "+02:00"');

$tpl = new Zmarty;

if ($_GET['logout'] != '') {
	logout('/mobile.php');
}

# Hangle login form
if ($_POST && $_POST['action'] == 'login') {
	require_once('mobile/login.php');
}

/* new: fill the camp selection menu -------------------------------------------- */

# Set organisation
if (!isset($_SESSION['organisation']['id']) && $_SESSION['user']['is_admin']) {
	if (!isset($_SESSION['camp']['id']) && $_GET['organisation']) {
		$_SESSION['organisation'] = db_row('SELECT o.* FROM organisations AS o WHERE (NOT o.deleted OR o.deleted IS NULL) AND o.id = :organisation', array('organisation' => $_GET['organisation']));
	} else {
		$_SESSION['organisation'] = db_row('SELECT * FROM organisations WHERE id=:id AND (NOT deleted OR deleted IS NULL)', array('id' => $_SESSION['camp']['organisation_id']));
	}
}
$tpl->assign('org', $_SESSION['organisation']);

$camplist = camplist();
if($_GET['camp']) $_SESSION['camp'] = $camplist[$_GET['camp']];
elseif(!isset($_SESSION['camp'])) $_SESSION['camp'] = reset($camplist);
$tpl->assign('camps', $camplist);
$tpl->assign('currentcamp', $_SESSION['camp']);
/* end of the camp menu addition -------------------------------------------- */

if ($_GET['message']) $data['message'] = $_GET['message'];
if ($_GET['warning']) $data['warning'] = true;

// Forward to login
if (!$checksession_result['success']) {
	if ($checksession_result['message']) {
		$data['message'] = $checksession_result['message'];
		$data['warning'] = true;
	}
	$data['destination'] = $_SERVER['REQUEST_URI'];
	$tpl->assign('include', 'mobile_login.tpl');
} elseif (!$_SESSION['camp']['id']) {
	//No organisation selected for admin
	if (!isset($_SESSION['organisation']['id']) && $_SESSION['user']['is_admin']) {
		require_once('mobile/selectorganisation.php');
	} else {
		trigger_error('You don\'t have access to this base. Ask your coordinator to correct this!');
		//$data['message'] = 'You don\'t have access to this base. Ask your coordinator to correct this!';
	}
} elseif (!db_value('SELECT id FROM locations WHERE locations.camp_id = ' . intval($_SESSION['camp']['id']) . ' LIMIT 1 ')) {
	redirect('/?action=start');
} else { # --------------- All routing happens here
	# Boxlabel is scanned 
	if ($_GET['barcode'] != '' || $_GET['boxid'] != '') {
		require_once('mobile/barcode.php');

		# Assign a QR code to existing box
	} elseif ($_GET['assignbox'] != '') {
		require_once('mobile/assignbox.php');

		# Save assignbox selection
	} elseif ($_GET['saveassignbox'] != '') {
		require_once('mobile/saveassignbox.php');

		# Make a new box with this QR code
	} elseif ($_GET['newbox'] != '') {
		require_once('mobile/newbox.php');

		# Edit the info for existing box
	} elseif ($_GET['editbox'] != '') {
		require_once('mobile/editbox.php');

		# Save a new box with this QR code
	} elseif ($_GET['savebox'] != '') {
		require_once('mobile/savebox.php');

		# Move this box to a new location
	} elseif ($_GET['move'] != '') {
		require_once('mobile/move.php');

		# Change the amount of items in this box
	} elseif ($_GET['changeamount'] != '') {
		require_once('mobile/changeamount.php');

		# Save the new amount of items in this box
	} elseif ($_GET['saveamount'] != '') {
		require_once('mobile/saveamount.php');

		# Save the new amount of items in this box
	} elseif (isset($_GET['vieworders'])) {
		require_once('mobile/vieworders.php');

		# Find a box by manually entered number
	} elseif ($_GET['findbox'] != '') {
		require_once('mobile/findbox.php');
	} else {
		require_once('mobile/start.php');
	}
}

$tpl->assign('data', $data);
$tpl->display('mobile.tpl');

function generateBoxID($length = 6, $possible = '0123456789')
{
	$password = "";
	$i = 0;
	while ($i < $length) {
		$char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
		if (!strstr($password, $char)) {
			$password .= $char;
			$i++;
		}
	}
	return $password;
}
