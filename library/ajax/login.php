<?php

header('Content-type:application/json;charset=utf-8');
echo json_encode(login($_POST['email'], $_POST['pass'], isset($_POST['autologin'])));
