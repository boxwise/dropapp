<?php

$outputFile = 'assets/css/minified.css';
$inputPath = 'assets/css';
$macro = [];

$files = [
    'bootstrap.min.css',
    'font-awesome.min.css',
    'jquery.qtip.min.css',
    'pushy.css',
    'pnotify.custom.min.css',
    'jquery.fancybox.css',
    'bootstrap-datetimepicker.min.css',
    'select2.css',
    'select2-bootstrap.css',
    'animate.css',
    'spectrum.css',
];

$css = '';
foreach ($files as $file) {
    $c = file_get_contents($inputPath.'/'.$file);
    // remove comments
    $c = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $c);
    // remove tabs, spaces, newlines, etc.
    $c = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $c);
    $css .= '/* '.$file." */\n\n".$c."\n\n\n";
}

$css = str_replace(array_keys($macro), array_values($macro), $css);

file_put_contents($outputFile, $css);
echo "Minified CSS to {$outputFile}\n";
