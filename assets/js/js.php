<?
	require('jsmin-1.1.1.php');
	header('Content-type: text/javascript; charset: UTF-8');

// 	added for cache
    header("Cache-Control: must-revalidate");
    $offset = 60 * 60 * 24 * 30;
    $ExpStr = "Expires: " .
    gmdate("D, d M Y H:i:s",
    time() + $offset) . " GMT";
    header($ExpStr);

	$debug = ($_GET['debug']==true) ? true : false;
	$rewrite = ($_GET['rewrite']==true) ? true : false;

	$date = @filectime('minified.js');

	$files = array(
		'jquery-3.1.1.min.js',
		'bootstrap.min.js',
		'bootstrap-confirmation.js',
		'modernizr.custom.86620.js',
		'jquery.zortable.js',
		'jquery.validate.min.js',
		'localization/messages_en.js',
		'jquery.qtip.js',
		'jquery.are-you-sure.js',
		'jquery.fancybox.pack.js',
		'moment-with-langs.min.js',
		'bootstrap-datetimepicker.js',
		'pnotify.custom.min.js',
		'jquery.noty.packaged.min.js',
		'mousetrap.min.js',
		'mousetrap-global-bind.min.js',
		'select2.min.js',
		'pushy.min.js',
		'cms_form_format.js'
	);

	foreach($files as $file) {
		if(filectime((substr($file,0,1)=='_'?substr($file,1):$file))>$date) $rewrite = true;
	}

	if($rewrite || $debug) {
		echo "/* Rewrite */\n\n";

		foreach($files as $file) {
			if($debug) {
				if(substr($file,0,1)=='_') {
					$js .= "/* ".$file." */\n\n".file_get_contents(substr($file,1))."\n\n\n";
				} else {
					$js .= "/* ".$file." */\n\n".file_get_contents($file)."\n\n\n";
				}
			} else {
				if(substr($file,0,1)=='_') {
					$js .= file_get_contents(substr($file,1))."\n";
				} else {
					$js .= JSMin::minify(file_get_contents($file))."\n";
				}
			}
		}

		echo $js;
		file_put_contents('minified.js',$js);
		//chmod('minified.js', 0777);
	} else {
		echo "/* Cached */\n\n";
		echo file_get_contents('minified.js');

	}
	
