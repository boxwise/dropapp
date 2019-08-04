<?php

echo json_encode(login($_POST['email'], $_POST['pass'], isset($_POST['autologin'])));
