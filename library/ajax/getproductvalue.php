<?php

	$success = true;
	$product_id = $_POST['product_id'];

	$drops = db_value('SELECT value FROM products WHERE id = :id', array('id'=>$_POST['product_id']));
	$return = array("success" => $success, 'drops' => $drops, 'message'=> $message);
	echo json_encode($return);
