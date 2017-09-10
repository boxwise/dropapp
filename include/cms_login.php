<?php
setlocale(LC_ALL, 'nl_NL');
require_once('library/core.php');

$email = (isset($_COOKIE['saveemail']) ? $_COOKIE['saveemail']:'');
$smarty = new Zmarty;
$smarty->display('cms_login.tpl');
