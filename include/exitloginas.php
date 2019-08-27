<?php

    $_SESSION['user'] = $_SESSION['user2'];
    $_SESSION['camp'] = $_SESSION['camp2'];
    $_SESSION['usergroup'] = $_SESSION['usergroup2'];
    $_SESSION['organisation'] = $_SESSION['organisation2'];
    unset($_SESSION['user2'], $_SESSION['camp2'], $_SESSION['usergroup2'], $_SESSION['organisation2']);

    redirect('/');
