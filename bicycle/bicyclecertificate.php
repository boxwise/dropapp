<?

	error_reporting(E_ALL^E_NOTICE);
	ini_set('display_errors',1);
	
	//die('Please use the online CMS!');
	require_once('../library/core.php');
	
	if(!DEFINED('CORE')) {
		trigger_error('Core is not available - probably the library folder is not in the include_path');
		die();
	}

	date_default_timezone_set('Europe/Athens');
	db_query('SET time_zone = "+02:00"');

	$id = intval($_GET['id']);
	
	$data = db_row('SELECT * FROM people WHERE id = :id',array('id'=>$id));
	$data['picture'] = (file_exists($_SERVER['DOCUMENT_ROOT'].'/images/people/'.$id.'.jpg')?$id:0);
	$exif = exif_read_data($_SERVER['DOCUMENT_ROOT'].'/images/people/'.$id.'.jpg');
	$data['rotate'] = ($exif['Orientation']==3?180:($exif['Orientation']==6?90:($exif['Orientation']==8?270:0)));

	if(!$data['picture']) {
		$error = new Zmarty;
		$error->assign('error','Without a picture we cannot give out a certificate. You can easily upload a picture from your mobile phone directly into the Drop App.');

		$error->display('cms_error.tpl');
		die();
	}

	if(!$data['bicycletraining']) {
		$error = new Zmarty;
		$error->assign('error','This person has not yet finished the bicycle training.');

		$error->display('cms_error.tpl');
		die();
	}

	$cmsmain = new Zmarty;
	$cmsmain->assign('data',$data);
	$cmsmain->display('bicyclecertificate.tpl');

