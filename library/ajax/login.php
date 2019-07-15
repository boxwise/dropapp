<?php

echo json_encode(login($_POST['email'], $_POST['pass'], $_POST['autologin']));
