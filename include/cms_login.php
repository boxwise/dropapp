<?php
setlocale(LC_ALL, 'nl_NL');

if(file_exists("uploads/background-1.jpg")){
	foreach (glob("uploads/background-*.jpg") as $filename) {
	    $backgrounds[] = $filename;
	}
} else {
	foreach (glob("assets/img/background-*.jpg") as $filename) {
	    $backgrounds[] = $filename;
	}	
}
$data['background'] = $backgrounds[array_rand($backgrounds)];

$data['favicon16'] = $settings['rootdir']. (file_exists("uploads/favicon-16x16.png") ? '/uploads/favicon-16x16.png' : '/assets/img/favicon-16x16.png');
$data['favicon32'] = $settings['rootdir']. (file_exists("uploads/favicon-32x32.png") ? '/uploads/favicon-32x32.png' : '/assets/img/favicon-32x32.png');
$data['faviconapple'] = $settings['rootdir']. (file_exists("uploads/apple-touch-icon.png") ? '/uploads/apple-touch-icon.png' : '/assets/img/apple-touch-icon.png');
	
$email = (isset($_COOKIE['saveemail']) ? $_COOKIE['saveemail']:'');
$smarty = new Zmarty;
$smarty->assign('data', $data);
$smarty->display('cms_login.tpl');
