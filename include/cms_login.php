<?php
setlocale(LC_ALL, 'nl_NL');
require_once($_SERVER["DOCUMENT_ROOT"].'/'.$settings['rootdir'].'library/core.php');

//$email = (isset($_COOKIE['saveemail']) ? $_COOKIE['saveemail']:'');
$smarty = new Zmarty;
$smarty->display('cms_login.tpl');
