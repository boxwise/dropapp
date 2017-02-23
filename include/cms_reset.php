<?php
	setlocale(LC_ALL, 'nl_NL');
	require_once('core.php');
	$smarty = new Zmarty;
	$smarty->display('cms_reset.tpl');
