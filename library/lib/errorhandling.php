<?php

use Google\Cloud\ErrorReporting\Bootstrap;

/**
 * set the scope for sentry exception and error handler.
 */
function boxwise_sentry_scope(Sentry\State\Scope $scope): void
{
    $session = $_SESSION;
    if (isset($_SESSION['user'])) {
        // Do not pass private data
        unset($session['user']['email'], $session['user']['naam'], $session['user']['pass']);

        // set Sentry to logged in mode
        $scope->setTag('logged in', 'true');
        $scope->setUser([
            'id' => $session['user']['id'],
        ]);

        // add additional sentry tags to search in UI
        $scope->setTag('base', $session['camp']['id']);
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

// Set exception handler
set_exception_handler('bootstrap_exception_handler');

/**
 * boxwise error handler
 * a class is created to to pass the previous errorhandler down to the Boxwise errorhandler (http://php.net/set_error_handler).
 */
class boxwise_error_handler_class
{
    /**
     * error handler returned by set_error_handler() in self::add_error_handler.
     */
    private static $previous_error_handler = null;

    /**
     * add boxwise_error_handler to existing error handlers.
     */
    public static function add_error_handler()
    {
        $error_handler = ['boxwise_error_handler_class', 'boxwise_error_handler'];
        $previous = set_error_handler($error_handler, error_reporting());
        // avoid dead loops
        if ($previous !== $error_handler) {
            self::$previous_error_handler = $previous;
        }
    }

    /**
     * Error Handler to add sentry scope to errors.
     *
     * @see http://php.net/set_error_handler
     *
     * @param int $errno      Error level
     * @param     $errstr
     * @param     $errfile
     * @param     $errline
     * @param     $errcontext
     *
     * @return bool
     */
    public static function boxwise_error_handler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        Sentry\configureScope('boxwise_sentry_scope');

        if (self::$previous_error_handler) {
            return call_user_func(
                self::$previous_error_handler,
                $errno,
                $errstr,
                $errfile,
                $errline,
                $errcontext
            );
        }

        return false;
    }
}

// Set error handlers
smarty::muteExpectedErrors();
boxwise_error_handler_class::add_error_handler();

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
