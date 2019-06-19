<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'library/config.php';
use OpenCensus\Trace\Exporter\StackdriverExporter;
use OpenCensus\Trace\Tracer;
use Google\Cloud\Storage\StorageClient;
function registerGoogleCloudServices($projectId)
{ 
    global $settings;
    
    $exporter = new StackdriverExporter([
        'clientConfig' => [
            'projectId' => $projectId
        ]
    ]);
    Tracer::start($exporter);

    $client = new StorageClient(['projectId' => $projectId]);
    $client->registerStreamWrapper();

    $settings['smarty_dir'] = "gs://$projectId.appspot.com/smarty/compile";
    $settings['upload_dir'] = "gs://$projectId.appspot.com/uploads";
}

$googleProjectId = getenv('GOOGLE_CLOUD_PROJECT');
if ($googleProjectId) {
    registerGoogleCloudServices($googleProjectId);
} else {
    throw new Exception("GOOGLE_CLOUD_PROJECT environment variable must be set to work in GAE environment");
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
case '/mobile.php':
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