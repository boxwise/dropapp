<?php

    require_once 'jsmin-1.1.1.php';

    $outputFile = 'assets/js/minified.js';
    $inputPath = 'assets/js';
    $debug = false;

    $files = [
        'jquery-3.1.1.min.js',
        'bootstrap.min.js',
        'bootstrap-confirmation.js',
        'modernizr.custom.86620.js',
        'isInViewport.min.js',
        'jquery.zortable.js',
        'jquery.validate.min.js',
        'localization/messages_en.js',
        'jquery.qtip.js',
        'jquery.are-you-sure.js',
        'jquery.fancybox.pack.js',
        'spectrum.min.js',
        'bootstrap-datetimepicker.min.js',
        'pnotify.custom.min.js',
        'jquery.noty.packaged.min.js',
        'mousetrap.min.js',
        'mousetrap-global-bind.min.js',
        'select2.min.js',
        'pushy.min.js',
        'cms_form_format.js',
    ];
    $js = '';
    foreach ($files as $file) {
        if ($debug) {
            $js .= '/* '.$file." */\n\n".file_get_contents($inputPath.'/'.$file)."\n\n\n";
        } else {
            $js .= JSMin::minify(file_get_contents($inputPath.'/'.$file))."\n";
        }
    }

    file_put_contents($outputFile, $js);

    echo "Minified JS to {$outputFile}\n";
