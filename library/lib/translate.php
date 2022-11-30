<?php

if (defined('CORE')) {
    $lan = $settings['cms_language'];

    // load global language array

    if (isset($_SESSION['user']['language'])) {
        $lan = db_value('SELECT code FROM languages WHERE id = :id', ['id' => $_SESSION['user']['language']]);
    }
    if (!$lan) {
        $lan = $settings['cms_language'];
    }
}

if (defined('COREMOBILE')) {
    $lan = $settings['cms_language'];
    $lanid = db_value('SELECT id FROM languages WHERE code = :code', ['code' => $lan]);
}

$translate = db_simplearray('SELECT code, '.$lan.' FROM translate WHERE NOT deleted');

$settings['languages'] = db_array('SELECT id,code,name,locale FROM languages WHERE visible ORDER BY seq');

function translate($code, $lan_override = false)
{
    global $settings, $lan, $translate;

    if ($lan_override) {
        $lan = $lan_override;
    }

    $result = db_value('SELECT '.$lan.' FROM translate WHERE code = :code', ['code' => $code]);
    if (!$result) {
        $result = $translate[$code];
    }

    return $result;
}
