<?php

    $ajax = true;
    require_once 'library/core.php';
    require_once 'library/ajax/'.preg_replace('/[^a-z0-9-]/', '', $_GET['file']).'.php';
