<?php

function csvexport($data, $filename, $keys)
{
    header('Content-type: text/csv');
    header("Content-Disposition: attachment; filename={$filename}".'_'.date('Y-m-d_his').'.csv');
    header('Pragma: no-cache');
    header('Expires: 0');

    foreach ($keys as $key => $value) {
        echo ucfirst((string) $value).',';
    }
    echo "\n";

    while ($f = db_fetch($data)) {
        foreach ($keys as $key => $value) {
            echo '"'.$f[$key].'",';
        }
        echo "\n";
    }

    exit;
}
