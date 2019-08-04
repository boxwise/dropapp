<?php
use OpenCensus\Trace\Tracer;
if (@parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST) == "localhost") {
    // report all errors in local development
    error_reporting(E_ALL);
} else {
    // report errors, but ignore E_STRICT and E_NOTICE
    error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING);
}

// configure sentry
if (!array_key_exists("sentry_key",$settings)) {
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
        ]);
    }
);
