<?php

use Google\Cloud\ErrorReporting\Bootstrap;

function bootstrap_exception_handler(Throwable $ex)
{
    if (getenv('GOOGLE_CLOUD_PROJECT')) {
        // report to google stackdriver
        Bootstrap::init();
        Bootstrap::exceptionHandler($ex);
    }
    // report to sentry
    Sentry\configureScope(function (Sentry\State\Scope $scope): void {
        if (isset($_SESSION['user'])) {
            $scope->setTag('logged in', 'true');
            $scope->setUser([
                'id' => $_SESSION['user']['id'],
            ]);
        } else {
            $scope->setTag('logged in', 'false');
        }
        $scope->setExtra('session', $_SESSION);
    });
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

set_exception_handler('bootstrap_exception_handler');
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
