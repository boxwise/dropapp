<?php

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
}