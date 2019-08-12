<?php

$login = login($_POST['email'], $_POST['pass'], $_POST['autologin'], $mobile = true);

if ($login['success']) {
	// WARNING: this is an open redirect, need to review the risk here
	redirect('/' . $_POST['destination']);
} else {
	redirect('?warning=true&message=' . $login['message']);
}
