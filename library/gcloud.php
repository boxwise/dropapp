<?php

use Google\Cloud\Datastore\DatastoreClient;
use Google\Cloud\Datastore\DatastoreSessionHandler;
use Google\Cloud\Storage\StorageClient;
// use OpenCensus\Trace\Exporter\StackdriverExporter;
// use OpenCensus\Trace\Integrations\Mysql;
// use OpenCensus\Trace\Integrations\PDO;
// use OpenCensus\Trace\Sampler\AlwaysSampleSampler;
// use OpenCensus\Trace\Sampler\NeverSampleSampler;
// use OpenCensus\Trace\Tracer;

function registerGoogleCloudServices($projectId)
{
    global $settings;

    // this will throw an error if GOOGLE_CLOUD_PROJECT
    // has not been defined
    require_once __DIR__.'/../vendor/google/cloud-error-reporting/src/prepend.php';

    // $exporter = new StackdriverExporter([
    //     'clientConfig' => [
    //         'projectId' => $projectId,
    //     ],
    // ]);
    // // opentrace seems to have a 1+ second overhead right now
    // // so only trace when specifically requested
    // $sampler = isset($_GET['trace']) ? new AlwaysSampleSampler() : new NeverSampleSampler();
    // Tracer::start($exporter, [
    //     'sampler' => $sampler,
    // ]);

    // if (extension_loaded('opencensus')) {
    //     Mysql::load();
    //     PDO::load();
    // }

    $client = new StorageClient(['projectId' => $projectId]);
    $client->registerStreamWrapper();

    $hostName = @parse_url('http://'.$_SERVER['HTTP_HOST'], PHP_URL_HOST);
    $settings['upload_dir'] = "gs://{$projectId}.appspot.com/{$hostName}/uploads";

    // configure session storage
    $datastore = new DatastoreClient();
    ini_set('session.save_path', "sessions-{$projectId}-{$hostName}");
    session_set_save_handler(new DatastoreSessionHandler($datastore), true);
}

$googleProjectId = getenv('GOOGLE_CLOUD_PROJECT');
if ($googleProjectId) {
    registerGoogleCloudServices($googleProjectId);
}
