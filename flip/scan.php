<?
	error_reporting(E_ALL);
	ini_set('display_errors',true);

	require('flipsite.php');

	date_default_timezone_set('Europe/Athens');
	db_query('SET time_zone = "+02:00"');

	$tpl = new Zmarty;

	checkmobilesession();
	if($_POST && $_POST['action']=='login') {
		require('scan/login.php');
	}

	/* new: fill the camp selection menu -------------------------------------------- */
	if($_GET['camp']) {
		if($_SESSION['user']['is_admin']) {
			$_SESSION['camp'] = db_row('SELECT c.* FROM camps AS c WHERE c.id = :camp',array('camp'=>$_GET['camp']));
		} else {
			$_SESSION['camp'] = db_row('SELECT c.* FROM camps AS c, cms_users_camps AS x WHERE c.id = x.camps_id AND c.id = :camp AND x.cms_users_id = :id',array('camp'=>$_GET['camp'], 'id'=>$_SESSION['user']['id']));
		}
	}
	if($_SESSION['user']['is_admin']) {
		$camplist = db_array('SELECT c.* FROM camps AS c');
	} else {
		$camplist = db_array('SELECT c.* FROM camps AS c, cms_users_camps AS x WHERE x.camps_id = c.id AND x.cms_users_id = :id',array('id'=>$_SESSION['user']['id']));
	}
	if(!isset($_SESSION['camp'])) $_SESSION['camp'] = $camplist[0];
	$tpl->assign('camps',$camplist);
	$tpl->assign('currentcamp',$_SESSION['camp']);
	/* end of the camp menu addition -------------------------------------------- */

	if($_GET['message']) $data['message'] = $_GET['message'];
	
	if(!$_SESSION['user']) {
		$data['destination'] = $_SERVER['REQUEST_URI'];
		$tpl->assign('include','mobile_login.tpl');
	} elseif(!$_SESSION['camp']['id']) { 
		$data['message'] = 'You don\'t have access to this camp. Ask your coordinator to correct this!';
		$tpl->assign('include','mobile_message.tpl');
	} else {
		# Boxlabel is scanned 
		if($_GET['barcode']!='') {
			require('scan/barcode.php');
			
		# Assign a QR code to existing box
		} elseif($_GET['assignbox']!='') {
			require('scan/assignbox.php');
			
		# Save assignbox selection
		} elseif($_GET['saveassignbox']!='') {
			require('scan/saveassignbox.php');
			
		# Make a new box with this QR code
		} elseif($_GET['newbox']!='') {
			require('scan/newbox.php');
			
		# Edit the info for existing box
		} elseif($_GET['editbox']!='') {
			require('scan/editbox.php');
			
		# Save a new box with this QR code
		} elseif($_GET['savebox']!='') {
			require('scan/savebox.php');
		
		# Move this box to a new location
		} elseif($_GET['move']!='') {
			require('scan/move.php');
	
		# Change the amount of items in this box
		} elseif($_GET['changeamount']!='') {
			require('scan/changeamount.php');
	
		# Save the new amount of items in this box
		} elseif($_GET['saveamount']!='') {
			require('scan/saveamount.php');
	
		# Find a box by manually entered number
		} elseif($_GET['findbox']!='') {
			require('scan/findbox.php');
	
		} elseif($_GET['logout']!='') {
			logout($settings['rootdir'].'/flip/scan.php');
	
		} else {
			require('scan/start.php');
		}	
	}


	$tpl->assign('data',$data);	
	$tpl->display('mobile.tpl');
	
function checkmobilesession() {
	global $settings;
	
	if(isset($_SESSION['user'])) { # a valid session exists
		db_query('UPDATE '.$settings['cms_usertable'].' SET lastaction = NOW() WHERE id = :id', array('id'=>$_SESSION['user']['id']));			
	} else { # no valid session exists	
		if(isset($_COOKIE['autologin_user'])) { # a autologin cookie exists
			$user = db_row('SELECT * FROM '.$settings['cms_usertable'].' WHERE email != "" AND email = :email AND pass = :pass',array('email'=>$_COOKIE['autologin_user'], 'pass'=>$_COOKIE['autologin_pass']));
			if($user) {
				$_SESSION['user'] = $user;
				db_query('UPDATE '.$settings['cms_usertable'].' SET lastlogin = NOW(), lastaction = NOW() WHERE id = :id',array('id'=>$_SESSION['user']['id']));
			}
		}
	}
}

