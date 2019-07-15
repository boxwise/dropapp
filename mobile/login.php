<?php

$login = login($_POST['email'], $_POST['pass'], $_POST['autologin'], $mobile = true);

if ($login['success']) {
	redirect('http://' . $_SERVER['HTTP_HOST'] . $_POST['destination']);
} else {
	redirect('?warning=true&message=' . $message);
}
