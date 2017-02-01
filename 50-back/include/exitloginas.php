<?
	
	$_SESSION['user'] = $_SESSION['user2'];
	unset($_SESSION['user2']);
	
	redirect($flipdir);
	