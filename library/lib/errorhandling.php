<?php

use Google\Cloud\ErrorReporting\Bootstrap;

function boxwise_sentry_scope(Sentry\State\Scope $scope): void
{
    $session = $_SESSION;
    if (isset($_SESSION['user'])) {
        // Do not pass private data
        unset($session['user']['email']);
        unset($session['user']['naam']);
        unset($session['user']['pass']);
        // set Sentry to logged in mode
        $scope->setTag('logged in', 'true');
        $scope->setUser([
            'id' => $session['user']['id'],
        ]);
    } else {
        $scope->setTag('logged in', 'false');
    }

    $scope->setExtra('session', $session);
};

function bootstrap_exception_handler(Throwable $ex)
{
    if (getenv('GOOGLE_CLOUD_PROJECT')) {
        // report to google stackdriver
        Bootstrap::init();
        Bootstrap::exceptionHandler($ex);
    }
    // report to sentry
    Sentry\configureScope('boxwise_sentry_scope');
    Sentry\captureException($ex);
    $eventId = Sentry\State\Hub::getCurrent()->getLastEventId();
    // this will only work if there hasn't already been response output
    http_response_code(500);
    $error = new Zmarty();
    $error->assign('error', "Sorry, an unexpected error occured and has been reported. Please quote Sentry #{$eventId}.");
    if ('localhost' == @parse_url('http://'.$_SERVER['HTTP_HOST'], PHP_URL_HOST)) {
        $error->assign('exception', $ex);
    }
    $error->assign('title', 'Sorry, an error occured');
    $error->display('cms_error.tpl');
    die();
}

function boxwise_error_handler($errorlevel, $message)
{
    Sentry\configureScope('boxwise_sentry_scope');

    return false;
}
set_exception_handler('bootstrap_exception_handler');
set_error_handler('boxwise_error_handler', error_reporting());
smarty::muteExpectedErrors();

function logfile($content)
{
    $content = str_replace('<br />', ' ', $content);
    db_query('INSERT INTO log (logdate, content, ip, user) VALUES (NOW(), :content, :ip, :user)', ['content' => $content, 'ip' => $_SERVER['REMOTE_ADDR'], 'user' => $_SESSION['user']['naam']]);
}

function dump(&$var)
{
    echo '<pre>';
    var_export($var);
    echo '</pre>';
}
