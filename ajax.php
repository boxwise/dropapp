<?php
	$ajax=true;
	# Only if the Login form calls ajax --> $login is set true
	$login = (($_GET['file']=='login') || ($_GET['file']=='reset') || ($_GET['file']=='reset2') ? true: false);
	require_once('library/core.php');

	error_reporting(0);

	if($checksession_result['success']){
		require_once('library/ajax/'.preg_replace("/[^a-z0-9-]/", "", $_GET['file']).'.php');
	} else {
		$return = array("success" => FALSE, 'message' => $checksession_result['message'], 'redirect' => $checksession_result['redirect']);
		echo json_encode($return);
	}
