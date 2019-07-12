<?php

function bootstrap_exception_handler(\Throwable $ex)
{
	if (isset($_SESSION['user'])) {
		Sentry\configureScope(function (Sentry\State\Scope $scope): void {
			$scope->setTag('logged in', 'true');
			$scope->setUser([
				'id' => $_SESSION['user']['id'],
			]);
			$scope->setExtra('session', $_SESSION);
		});
	} 
	Sentry\captureException($ex);
	$eventId = Sentry\State\Hub::getCurrent()->getLastEventId();

	$error = new Zmarty;
	$error->assign('error', "Sorry, an unexpected error occured and has been reported. Please quote Sentry #$eventId.");
	if (substr($_SERVER['HTTP_HOST'], 0, strlen("localhost")) === "localhost") {
		$error->assign('exception', $ex);
	}
	$error->display('cms_error.tpl');
	die();
}

set_exception_handler('bootstrap_exception_handler');
smarty::muteExpectedErrors();

function logfile($content)
{
	$content = str_replace("<br />", " ", $content);
	db_query('INSERT INTO log (logdate, content, ip, user) VALUES (NOW(), :content, :ip, :user)', array('content' => $content, 'ip' => $_SERVER['REMOTE_ADDR'], 'user' => $_SESSION['user']['naam']));
}

function dump(&$var)
{
	echo '<pre>';
	var_export($var);
	echo '</pre>';
}
