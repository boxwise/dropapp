<?php

// The GAE environment requires a single entry point, so we're
// doing basic routing from here

// // Allow dev localhost ports, and boxtribute subdomains as origins
// // As we need to call the dropapp from the v2 app we should set the allowed CORS urls
// $origins = [
//     'http://localhost:5005',
//     'http://localhost:3000',
//     'http://localhost:8100/?action=logout',
//     'https://v2-staging.boxtribute.org',
//     'https://v2-demo.boxtribute.org',
//     'https://v2.boxtribute.org',
//     'https://v2-staging-dot-dropapp-242214.ew.r.appspot.com',
//     'https://v2-demo-dot-dropapp-242214.ew.r.appspot.com',
//     'https://v2-production-dot-dropapp-242214.ew.r.appspot.com',
// ];

// $allowedOrigins = implode(',', $origins);

// // Set the Access-Control-Allow-Origin header to the comma-separated list of origins
// header('Access-Control-Allow-Origin: http://localhost:3000');

define('LOADED_VIA_SINGLE_ENTRY_POINT', true);

require_once 'vendor/autoload.php';

require_once 'library/config.php';

require_once 'library/gcloud.php';

require_once 'library/error-reporting.php';

require_once 'library/lib/smarty.php';

require_once 'library/lib/errorhandling.php';

require_once 'library/constants.php';

// use OpenCensus\Trace\Tracer;

// permanent redirect for old market.drapenihavet.no url
// ideally it wouldn't need to run PHP code to do this redirect
// but appengine standard doesn't give us alternatives
// it should probably also live in it's own seperate code,
// but this was easiest to control during the migration process
if (('market.drapenihavet.no' == $_SERVER['HTTP_HOST']) || ('www.market.drapenihavet.no' == $_SERVER['HTTP_HOST'])) {
    $parsedUrl = @parse_url($_SERVER['REQUEST_URI'])['path'];
    header('Location: https://app.boxtribute.org'.$_SERVER['REQUEST_URI'].(empty($_GET) ? '?' : '&').'qrlegacy=1', true, 301);

    return;
}

$parsedUrl = @parse_url($_SERVER['REQUEST_URI'])['path'];
// Tracer::inSpan(
//     ['name' => ('gcloud-entry:'.$parsedUrl)],
//     function () use ($parsedUrl) {
//         // this is horrible, but in order to wrap these includes in this tracing function
//         // we have to declare every possible global variable usage
//         // ideally we wouldn't be using globals at all, but that's for another day :)
//         global $settings,$translate,$action,$lan,$pdf,$_txt,$formbuttons;
//         global $error,$listdata,$data,$table,$listconfig,$thisfile,$formdata;
//         global $rolesToActions, $menusToActions;

switch ($parsedUrl) {
    case '/':
    case '/index.php':
        require 'index.php';

        break;

        // old path of QR-codes
    case '/flip/scan.php':
        require 'mobile.php';

        break;

    case '/ajax.php':
    case '/mobile.php':
    case '/cypress-session.php':
    case '/pdf/workshopcard.php':
    case '/pdf/bicyclecard.php':
    case '/pdf/idcard.php':
    case '/pdf/qr.php':
    case '/pdf/dryfood.php':
    case '/cron/dailyroutine.php':
    case '/cron/reseed-auth0.php':
    case '/cron/reseed-roles-auth0.php':
    case '/fake-error.php':
        require substr($parsedUrl, 1); // trim /

        break;

    case '/ping':
        http_response_code(200);

        exit('pong');

        break;

    default:
        http_response_code(404);

        exit('Not Found');
}

//     }
// );
