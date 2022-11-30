<?php

use Google\Cloud\Storage\StorageClient;
use OpenCensus\Trace\Exporter\StackdriverExporter;
use OpenCensus\Trace\Integrations\Mysql;
use OpenCensus\Trace\Integrations\PDO;
use OpenCensus\Trace\Sampler\AlwaysSampleSampler;
use OpenCensus\Trace\Sampler\NeverSampleSampler;
use OpenCensus\Trace\Tracer;

function registerGoogleCloudServices($projectId)
{
    global $settings;

    $exporter = new StackdriverExporter([
        'clientConfig' => [
            'projectId' => $projectId,
        ],
    ]);
    // opentrace seems to have a 1+ second overhead right now
    // so only trace when specifically requested
    $sampler = isset($_GET['trace']) ? new AlwaysSampleSampler() : new NeverSampleSampler();
    Tracer::start($exporter, [
        'sampler' => $sampler,
    ]);

    if (extension_loaded('opencensus')) {
        Mysql::load();
        PDO::load();
    }

    $client = new StorageClient(['projectId' => $projectId]);
    $client->registerStreamWrapper();

    $hostName = @parse_url('http://'.$_SERVER['HTTP_HOST'], PHP_URL_HOST);
    $version = $settings['version'] ?? '0';
    $settings['upload_dir'] = "gs://{$projectId}.appspot.com/{$hostName}/uploads";

    // configure session storage
    if (!getenv('DISABLE_GCLOUD')) {
        ini_set('session.save_path', "sessions-{$projectId}-{$hostName}");
        $datastore = new Google\Cloud\Datastore\DatastoreClient();
        session_set_save_handler(new Google\Cloud\Datastore\DatastoreSessionHandler($datastore), true);
    }
}

$googleProjectId = getenv('GOOGLE_CLOUD_PROJECT');
if (!$googleProjectId) {
    throw new Exception('No GOOGLE_CLOUD_PROJECT set');
}
registerGoogleCloudServices($googleProjectId);
