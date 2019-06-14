<?php
setlocale(LC_ALL, 'nl_NL');

foreach (glob("assets/img/background-*.jpg") as $filename) {
	$backgrounds[] = $filename;
}
$data['background'] = $backgrounds[array_rand($backgrounds)];

$email = (isset($_COOKIE['saveemail']) ? $_COOKIE['saveemail']:'');
$smarty = new Zmarty;
$smarty->assign('data', $data);
$smarty->display('cms_login.tpl');
