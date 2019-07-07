<?php

$login = login($_POST['email'], $_POST['pass'], $_POST['autologin'], $mobile=true);


if ($login['success']) {
	if ($_GET['barcode']) $uri = 'barcode=' . $_GET['barcode'];

	redirect('?' . $uri);
} else {
	redirect('?warning=true&message=' . $login['message']);
}
