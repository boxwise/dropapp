<?php

function exception_error_handler($errno, $errstr, $errfile, $errline) {


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

	        echo '<h3>Waarschuwing</h3>'.$errstr;
			logfile('E_USER_WARNING: '.$errstr.', line '.$errline.', file '.$errfile);


	        break;

			echo "<h1>Er is iets foutgegaan</h1>";
	        echo '<h3>Opmerking</h3>'.$errstr.'<br />';
			logfile('E_USER_NOTICE: '.$errstr);

			array_walk(debug_backtrace(), create_function('$a,$b', 'print "<br /><b>". basename( $a[\'file\'] ). "</b> &nbsp; {$a[\'function\']} (), regel {$a[\'line\']} &nbsp; ". $a[\'file\'];'));

	        break;

		case E_WARNING:
		case E_NOTICE:
		case E_STRICT:
	        break;

	    default:
			echo "<h1>Er is iets foutgegaan</h1>";
	        echo '<h3>Fout</h3>'.$errstr.' ('.$errno.')<br />bestand: '.$errfile.'<br />regelnummer: '.$errline;
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
        $stack .= "<p>#$i ".$node['file'] ."(" .$node['line']."): ";
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

#error_reporting(E_ALL^E_NOTICE);
#ini_set('display_errors',1);
set_error_handler("exception_error_handler");
smarty::muteExpectedErrors();
