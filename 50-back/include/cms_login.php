<?	
	setlocale(LC_ALL, 'nl_NL');
	
	require('flip.php');
	
	$email = (isset($_COOKIE['saveemail'])?$_COOKIE['saveemail']:'');

	$smarty = new Zmarty;
		
	$smarty->display('cms_login.tpl');