<?php

	ini_set('display_errors',true);
	error_reporting(E_ALL);


	require_once('core.php');

	$table = 'transactions';
	$action = 'transactions_edit';

	$ajaxform = new Zmarty;

	/* vanaf hier */

	$data['transaction_id'] = intval($_POST['transaction_id']);
	$data['updown'] = intval($_POST['updown']);
	$data['people_id'] = intval($_POST['people_id']);

	$dropcoins = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id',array('id'=>$data['people_id']));
	$transaction = db_row('SELECT * FROM transactions WHERE id = :id',array('id'=>$data['transaction_id']));
	$dropsoneitem = $transaction['drops'] / $transaction['count'];
	$newcount = $transaction['count'] + $data['updown'];

	$enoughdrops = ($dropcoins + ($dropsoneitem * $data['updown'])) >= 0;

	if($newcount == 0){
		$success = false;
		$message = "Oepsie, that's not possible, but you can delete this item by selecting it and clicking 'Delete'.";
	} else if ($enoughdrops){
		$drops = $newcount * $dropsoneitem;
		db_query('UPDATE transactions SET count = :count, drops = :drops, modified = NOW(), modified_by = :user WHERE id = :id',array('id'=>$data['transaction_id'],'count'=>$newcount, 'drops' => $drops,'user'=>$_SESSION['user']['id']));
		$data['dropcoins'] = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id',array('id'=>$data['people_id']));
		$success = true;
	} else {
		$success = false;
		$message = "I'm sorry, but there are not enough drops to add more items. Smile and be kind :-)";
	}
	
	$data['allowdrops'] = $_SESSION['user']['is_admin']||db_value('SELECT id FROM cms_functions AS f, cms_access AS a WHERE a.cms_functions_id = f.id AND f.include = "give2all" AND a.cms_users_id = :user_id',array('user_id'=>$_SESSION['user']['id']));

	$ajaxaside = new Zmarty;

	$data['people'] = db_array('SELECT *, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age FROM people WHERE parent_id = :id OR id = :id AND visible AND NOT deleted ORDER BY parent_id, seq',array('id'=>$data['people_id']));

	$data['givedropsurl'] = '?action=give&ids='.$data['people_id'];


	$ajaxaside->assign('data',$data);
	$htmlaside = $ajaxaside->fetch('info_aside_purchase.tpl');

	$return = array("success" => $success, 'htmlaside' => $htmlaside, 'amount' => $newcount, 'drops' => $drops, 'message'=> $message);
	echo json_encode($return);
