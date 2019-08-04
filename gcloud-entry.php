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
use OpenCensus\Trace\Tracer;

$parsedUrl = @parse_url($_SERVER['REQUEST_URI'])['path'];
Tracer::inSpan(
    ['name' => ('gcloud-entry:'.$parsedUrl)],
    function() use ($parsedUrl) {
        // this is horrible, but in order to wrap these includes in this tracing function
        // we have to declare every possible global variable usage
        // ideally we wouldn't be using globals at all, but that's for another day :) 
        global $settings,$translate,$action,$lan,$pdf,$_txt;
        global $error,$listdata,$data,$table,$listconfig,$thisfile;
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
    }
);
