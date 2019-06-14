<?php

function usererror($errstr) {
	exception_error_handler(E_USER_ERROR,$errstr);
}

function exception_error_handler($errno, $errstr, $errfile = '', $errline = '') {
    switch ($errno) {
	    case E_USER_ERROR:
	    case E_USER_WARNING:
	    case E_USER_NOTICE:

			$error = new Zmarty;
			$error->assign('error',$errstr);
			$error->assign('line',$errline);
			$error->assign('file',$errfile);


			$error->assign('backtrace',debug_backtrace_string());

			$error->display('cms_error.tpl');

			die();

		case E_WARNING:
		case E_NOTICE:
		case E_STRICT:
	        break;

	    default:
			echo "<h1>Something went wrong</h1>";
	        echo '<h3>Error</h3>'.$errstr.' ('.$errno.')<br />file: '.$errfile.'<br />line: '.$errline;
			logfile('ERROR: '.$errstr.', line '.$errline.', file '.$errfile);
	        exit($errno);
	        break;
    }
}

function debug_backtrace_string() {
    $stack = '';
    $i = 1;
    $trace = debug_backtrace();
    unset($trace[0]); //Remove call to this function from stack trace
    foreach($trace as $node) {
        $stack .= "<p>#$i ".(isset($node['file'])?$node['file']:'') ."(" .(isset($node['line'])?$node['line']:'')."): ";
        if(isset($node['class'])) {
            $stack .= $node['class'] . "->";
        }
        $stack .= $node['function'] . "()" . '</p>';
        $i++;
    }
    return $stack;
}

function logfile($content) {
	$content = str_replace("<br />"," ",$content);
	db_query('INSERT INTO log (logdate, content, ip, user) VALUES (NOW(), :content, :ip, :user)', array('content'=>$content, 'ip'=>$_SERVER['REMOTE_ADDR'], 'user'=>$_SESSION['user']['naam']));
}

function dump(&$var) {
	echo '<pre>';
	var_export($var);
	echo '</pre>';
}

set_error_handler("exception_error_handler");
smarty::muteExpectedErrors();
