<?php
use OpenCensus\Trace\Tracer;
use OpenCensus\Trace\Exporter\StackdriverExporter;
use OpenCensus\Trace\Integrations\PDO;
use OpenCensus\Trace\Integrations\Mysql;
use OpenCensus\Trace\Sampler\AlwaysSampleSampler;
use OpenCensus\Trace\Sampler\NeverSampleSampler;
use Google\Cloud\Storage\StorageClient;
function registerGoogleCloudServices($projectId)
{ 
    global $settings;
    
    $exporter = new StackdriverExporter([
        'clientConfig' => [
            'projectId' => $projectId
        ]
    ]);
    // opentrace seems to have a 1+ second overhead right now
    // so only trace when specifically requested
    $sampler = isset($_GET['trace']) ? new AlwaysSampleSampler() : new NeverSampleSampler();
    Tracer::start($exporter, [
        'sampler' => $sampler
    ]);
    Mysql::load();
    PDO::load();

    $client = new StorageClient(['projectId' => $projectId]);
    $client->registerStreamWrapper();
    
    $hostName = @parse_url('http://'.$_SERVER['HTTP_HOST'], PHP_URL_HOST);
    $version = $settings['version'] ?? '0';
    $settings['smarty_dir'] = "gs://$projectId.appspot.com/$hostName/v$version/smarty/compile/";
    $settings['upload_dir'] = "gs://$projectId.appspot.com/$hostName/uploads";
}

$googleProjectId = getenv('GOOGLE_CLOUD_PROJECT');
if ($googleProjectId) {
    registerGoogleCloudServices($googleProjectId);
}