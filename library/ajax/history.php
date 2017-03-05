<?php
	if(substr($_GET['table'],-5)=='_edit') $_GET['table'] = substr($_GET['table'],0,-5);

	echo showHistory($_GET['table'],$_GET['id']);
