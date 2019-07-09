<?

function csvexport($data, $filename, $keys)
{

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=$filename" . ".csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    foreach ($keys as $key => $value) {
        echo ucfirst($value) . ";";
    }
    echo "\n";

    while ($f = db_fetch($data)) {
        foreach ($keys as $key => $value) {
            echo '"' . $f[$key] . '"' . ";";
        }
        echo "\n";
    }
    die();
}
