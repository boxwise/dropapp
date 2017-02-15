<?
	
	$filename = basename($_POST['file']);
	$resizeProperties = unserialize(str_replace("'",'"',stripslashes($_POST['paths'])));
	
	if ($filename && $resizeProperties) {
		foreach ($resizeProperties AS $item) {
			$aPathInfo = pathinfo($_SERVER['DOCUMENT_ROOT'].$item['target'].$filename);
			$aImage = glob($_SERVER['DOCUMENT_ROOT'].$item['target'].$aPathInfo['filename'].'.*');			
			unlink($aImage[0]);
		}
		$success = true;
		$message = translate('cms_form_file_deletesuccess');		
	} else {
		$success = false;
		$message = translate('cms_form_file_deletefailure');				
	}

	$return = array("success" => $success, 'message'=> $message);
	echo json_encode($return);	