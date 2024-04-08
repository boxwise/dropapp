<?php

if (str_ends_with((string) $_GET['table'], '_edit')) {
    $_GET['table'] = substr((string) $_GET['table'], 0, -5);
}

echo showHistory($_GET['table'], $_GET['id']);
