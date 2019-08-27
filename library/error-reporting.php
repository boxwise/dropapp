<?php

use OpenCensus\Trace\Tracer;

if (@parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST) == "localhost") {
    // report all errors in local development
    error_reporting(E_ALL);
    // dump errors to stderr (you can see in console)
    // this is so it doesn't interfere with the app interaction
    ini_set('display_errors', 'stderr');
    // as we're dumping to stderr, don't show the errors as html
    ini_set('html_errors', '0');
} else {
    // report errors, but ignore E_STRICT and E_NOTICE
    error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING);
}

// configure sentry
if (!array_key_exists("sentry_key", $settings)) {
    throw new Exception("sentry_key must be set to work in GAE environment");
}

Tracer::inSpan(
    ['name' => 'sentry.init'],
    function () {
        global $settings;
        Sentry\init(
            [
                'dsn' => $settings['sentry_key'],
                'environment' => $_SERVER['HTTP_HOST'],
                'error_types' => error_reporting()
            ]
        );
    }
);
