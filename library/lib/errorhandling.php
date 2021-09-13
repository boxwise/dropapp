<?php

use Google\Cloud\ErrorReporting\Bootstrap;

/**
 * set the scope for sentry exception and error handler.
 */
function boxwise_sentry_scope(Sentry\State\Scope $scope): void
{
    $session = isset($_SESSION) ? $_SESSION : [];
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
    // list of standard http errors
    $http_status_codes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => "I'm a teapot",
        419 => 'Authentication Timeout',
        420 => 'Enhance Your Calm',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        424 => 'Method Failure',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        444 => 'No Response',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        451 => 'Unavailable For Legal Reasons',
        494 => 'Request Header Too Large',
        495 => 'Cert Error',
        496 => 'No Cert',
        497 => 'HTTP to HTTPS',
        499 => 'Client Closed Request',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        598 => 'Network read timeout error',
        599 => 'Network connect timeout error',
    ];
    // this will only work if there hasn't already been response output
    http_response_code($ex->getCode());
    $error = new Zmarty();
    if (0 === $ex->getCode() && 'Invalid state' === $ex->getMessage()) {
        // This will call logout and redirect to renew the session when the authentication login page remains longer than expected
        trigger_error('The user received an invalid state error since the state code is outdated', E_USER_NOTICE);
        logoutWithRedirect();
    } elseif (500 === $ex->getCode() || !$http_status_codes[$ex->getCode()]) {
        $error->assign('title', 'Sorry, something went wrong');
        $error->assign('error', "Sorry, an unexpected error occured and has been reported. Please quote Sentry #{$eventId}.");
    } else {
        $error->assign('title', $ex->getCode().' - '.$http_status_codes[$ex->getCode()]);
        $error->assign('error', "{$ex->getMessage()}");
        $error->assign('sentry', "For additional information, please contact support and quote Sentry #{$eventId}.");
    }

    global $settings;
    if ('development' == $settings['app_env']) {
        $error->assign('exception', $ex);
    }

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
