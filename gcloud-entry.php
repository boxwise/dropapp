<?php
define('LOADED_VIA_SINGLE_ENTRY_POINT', true);
require_once 'vendor/autoload.php';
# load configuration file
require_once 'library/config.php';
require_once 'library/gcloud.php';
# load error handling as soon as possible
require_once 'library/error-reporting.php';
// The GAE environment requires a single entry point, so we're
// doing basic routing from here
$parsedUrl = @parse_url($_SERVER['REQUEST_URI'])['path'];
switch ($parsedUrl) {
case '/':
case '/index.php':
    require 'index.php';
    break;
case '/login.php':
case '/ajax.php':
case '/mobile.php':
case '/reset.php':
case '/pdf/workshopcard.php':
case '/pdf/bicyclecard.php':
case '/pdf/qr.php':
case '/pdf/dryfood.php':
case '/reseed-db.php':
    require substr($parsedUrl,1); // trim /
    break;
default:
    http_response_code(404);
    exit('Not Found');
}