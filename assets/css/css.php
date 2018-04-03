<?php

	header('Content-type: text/css');

	error_reporting(0);
	ini_set('display_errors',false);

// 	added for cache
    header("Cache-Control: must-revalidate");
    $offset = 60 * 60 * 24 * 30;
    $ExpStr = "Expires: " .
    gmdate("D, d M Y H:i:s",
    time() + $offset) . " GMT";
    header($ExpStr);


	@$date = filectime('minified.css');

	$debug = ($_GET['debug']==true) ? true : false;
	$rewrite = ($_GET['rewrite']==true) ? true : false;

	$macro = array();

	$files = array(
		'bootstrap.min.css',
// 		'fontawesome.min.css',
		'jquery.qtip.min.css',
		'pushy.css',
		'pnotify.custom.min.css',
		'jquery.fancybox.css',
		'bootstrap-datetimepicker.min.css',
		'select2.css',
		'select2-bootstrap.css',
		'animate.css',
		'style.css'
	);

	foreach($files as $file) {
		if(filectime((substr($file,0,1)=='_'?substr($file,1):$file))>$date) {
			$rewrite = true;
		}
	}

	if($rewrite) {
		$css = '';
		echo "/* Rewrite */\n\n";
		foreach($files as $file) {
			$c = file_get_contents($file);
			/* remove comments */
			$c = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $c);
			/* remove tabs, spaces, newlines, etc. */
			$c = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $c);
			$css .= "/* ".$file." */\n\n".$c."\n\n\n";
		}

		$css = str_replace(array_keys($macro), array_values($macro), $css);

		echo $css;
		file_put_contents('minified.css',$css);
		chmod('minified.css', 0777);
	} else {
		echo "/* Cached ".strftime('%c',$date)." */\n\n";
		echo file_get_contents('minified.css');

	}
