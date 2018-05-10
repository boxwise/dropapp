<?php
	setlocale(LC_ALL, 'nl_NL');
require_once('library/core.php');
	$smarty = new Zmarty;
	$smarty->display('cms_reset.tpl');
