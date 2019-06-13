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
switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
case '/':
case '/index.php':
    require 'index.php';
    break;
case '/login.php':
    require 'login.php';
    break;
case '/ajax.php':
    require 'ajax.php';
    break;
default:
    http_response_code(404);
    exit('Not Found');
}