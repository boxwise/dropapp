<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../library/config.php';
require_once __DIR__.'/../library/lib/smarty.php';
$smarty = new Zmarty;
$smarty->compileAllTemplates('.tpl', true, 0, null);
