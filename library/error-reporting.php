<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

function bootstrap_error_handler($errno, $errstr, $errfile, $errline)
{
    switch ($errno) {
    case E_WARNING:
    case E_NOTICE:
    case E_STRICT:
        // we should really not be supressing these :(
        // but maintaining backwards compatibility for now as
        // there are loads of problems if you don't
        break;
    default:
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
};
set_error_handler('bootstrap_error_handler');

// configure sentry
if (!array_key_exists("sentry_key",$settings)) {
    throw new Exception("sentry_key must be set to work in GAE environment");
}
Sentry\init(
    [
        'dsn' => $settings['sentry_key'],
        'environment' => $_SERVER['HTTP_HOST']
    ]);

// start session to ensure it's available when setting scope for sentry
session_start();
Sentry\configureScope(function (Sentry\State\Scope $scope): void {
    if (array_key_exists('user',$_SESSION))
        $scope->setUser(['id' => $_SESSION['user']['id']]);
});
