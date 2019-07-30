<?php
use OpenCensus\Trace\Tracer;
// report errors, but ignore E_STRICT and E_NOTICE
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING);

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

// start session to ensure it's available when setting scope for sentry
session_start();
Sentry\configureScope(function (Sentry\State\Scope $scope): void {
    if (array_key_exists('user',$_SESSION))
        $scope->setUser(['id' => $_SESSION['user']['id']]);
});
