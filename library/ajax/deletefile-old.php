<?php
	
	require('core.php');
	
	$filename = basename($_POST['file']);
	$resizeProperties = unserialize(str_replace("'",'"',stripslashes($_POST['paths'])));

	if ($filename && $resizeProperties) {
		foreach ($resizeProperties AS $item) {
			//unlink($_SERVER['DOCUMENT_ROOT'].$item['target'].$filename);
		}
		$success = true;
		$message = translate('cms_form_file_deletesuccess');		
	} else {
		$success = false;
		$message = translate('cms_form_file_deletefailure');				
	}

	$return = array("success" => $success, 'message'=> $message);
	echo json_encode($return);	