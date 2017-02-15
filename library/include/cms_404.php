<?	
	setlocale(LC_ALL, 'nl_NL');
	
	require('flip.php');

	$smarty = new Zmarty;		
	$smarty->display('cms_404.tpl');