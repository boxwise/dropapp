<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'config.php';
// register google cloud storage client
use Google\Cloud\Storage\StorageClient;
function register_stream_wrapper($projectId)
{   
    $client = new StorageClient(['projectId' => $projectId]);
    $client->registerStreamWrapper();
}
if ($settings['google_projectid']) {
    register_stream_wrapper($settings['google_projectid']);
} else {
    throw new Exception("google_projectid must be set to work in GAE environment");
}
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
case '/pdf/workshopcard.php':
case '/pdf/bicyclecard.php':
case '/pdf/qr.php':
case '/pdf/dryfood.php':
    require substr($parsedUrl,1); // trim /
    break;
default:
    http_response_code(404);
    exit('Not Found');
}