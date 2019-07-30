<?php
use OpenCensus\Trace\Tracer;
use OpenCensus\Trace\Exporter\StackdriverExporter;
use OpenCensus\Trace\Integrations\PDO;
use OpenCensus\Trace\Integrations\Mysql;
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
    Mysql::load();
    PDO::load();

    $client = new StorageClient(['projectId' => $projectId]);
    $client->registerStreamWrapper();

    $settings['smarty_dir'] = "gs://$projectId.appspot.com/smarty/compile";
    $settings['upload_dir'] = "gs://$projectId.appspot.com/uploads";
}

$googleProjectId = getenv('GOOGLE_CLOUD_PROJECT');
if ($googleProjectId) {
    registerGoogleCloudServices($googleProjectId);
}