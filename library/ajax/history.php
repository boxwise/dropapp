<?php

    if ('_edit' == substr($_GET['table'], -5)) {
        $_GET['table'] = substr($_GET['table'], 0, -5);
    }

    echo showHistory($_GET['table'], $_GET['id']);
