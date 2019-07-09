<?

function csvexport($data,$filename,$keys){

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=$filename".".csv");
header("Pragma: no-cache");
header("Expires: 0");

foreach ($keys as $value){echo ucfirst($value).";";}
echo "\n";

while($f = db_fetch($data)) {
    foreach($keys as $value){echo '"'.$f[$value].'"'.";";}
    echo "\n";

}
die();
}