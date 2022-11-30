<?php

require_once __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../library/lib/smarty.php';

$smarty = new Zmarty();
precompileTemplates($smarty);

function precompileTemplates($smarty)
{
    $smarty->clearCompiledTemplate();
    $smarty->compileAllTemplates('.tpl', true, 0, null);
}
