<?php


	$action = 'food_checkout';
	$table = 'food_distributions';
	$ajax = checkajax();

	if(!$ajax) {

		initlist();

		$cmsmain->assign('title','Food Distributions');
		
		#build query with 5 food columns
		$query='SELECT fd.id as id, fd.label as label, fd.created AS start_date,
			(SELECT COUNT(pc.id) 
				FROM people AS pc
				WHERE pc.camp_id = '.$_SESSION['camp']['id'].' AND COALESCE(pc.created,0) < fd.created AND ((NOT pc.deleted AND pc.visible) OR fd.created < pc.deleted) AND (YEAR(NOW()) - YEAR(pc.date_of_birth) - (DAYOFYEAR(NOW()) > DAYOFYEAR(pc.date_of_birth))) < '.$settings['adult-age'].') AS children,
			(SELECT COUNT(pa.id) 
				FROM people AS pa
				WHERE pa.camp_id = '.$_SESSION['camp']['id'].' AND pa.created < fd.created AND ((NOT pa.deleted AND pa.visible) OR fd.created < pa.deleted) AND (YEAR(NOW()) - YEAR(pa.date_of_birth) - (DAYOFYEAR(NOW()) > DAYOFYEAR(pa.date_of_birth))) > '.$settings['adult-age'].') AS adults';
		for ($i=1; $i<6; $i++){
			$query .= ', f'.$i.'.name AS food_'.$i.', ROUND(((SELECT children) * f'.$i.'.perchild) + ((SELECT adults) * f'.$i.'.peradult)) AS total_'.$i.',
				(SELECT ROUND(SUM(ft'.$i.'.count)*f'.$i.'.package,1) FROM food_transactions AS ft'.$i.' WHERE fd.food_'.$i.' = ft'.$i.'.food_id AND fd.id = ft'.$i.'.dist_id) AS collected_'.$i.', 
				CONCAT(COALESCE((SELECT collected_'.$i.'),"0"), " of ", (SELECT total_'.$i.'), " ", u'.$i.'.label) AS sec_line_'.$i; 
			$query .= ', CEIL((((SELECT children) * f'.$i.'.perchild) + ((SELECT adults) * f'.$i.'.peradult))/f'.$i.'.package) AS total_portions_'.$i.', 
				(SELECT SUM(ft'.$i.'.count) FROM food_transactions AS ft'.$i.' WHERE fd.food_'.$i.' = ft'.$i.'.food_id AND fd.id = ft'.$i.'.dist_id) AS collected_portions_'.$i.', 
				CONCAT(COALESCE((SELECT collected_portions_'.$i.'),"0"), " of ", (SELECT total_portions_'.$i.'), " given out") AS third_line_'.$i; 
		}
		$query .= ' FROM '.$table.' AS fd';
		for ($i=1; $i<6; $i++){
			$query .= ' LEFT JOIN food AS f'.$i.' ON (f'.$i.'.id=fd.food_'.$i.')';
			$query .= ' LEFT JOIN units AS u'.$i.' ON (u'.$i.'.id=f'.$i.'.unit_id)';
		}
		$query .= ' WHERE fd.deleted IS NULL 
			ORDER BY id DESC';
		$data = getlistdata($query);


		addcolumn('date','Start','start_date');
		addcolumn('text','Comment','label');
		for ($i=1; $i<7; $i++){
			#Status is shown in two lines
			#addcolumn('text_break','Food '.$i,'food_'.$i, array('sec_line' => 'sec_line_'.$i, 'third_line' => 'third_line_'.$i, 'bold'=>true, 'italic'=>true));
			addcolumn('text_break','Food '.$i,'food_'.$i, array('sec_line' => 'sec_line_'.$i, 'bold'=>true, 'italic'=>true));
		}

		listsetting('allowcopy', false);
		listsetting('allowshowhide', true);
		listsetting('add', 'Start a new Distribution');
		listsetting('delete', 'Delete');

		$cmsmain->assign('data',$data);
		$cmsmain->assign('listconfig',$listconfig);
		$cmsmain->assign('listdata',$listdata);
		$cmsmain->assign('include','cms_list.tpl');


	} else {
		switch ($_POST['do']) {
		    case 'delete':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listDelete($table, $ids);
		        break;

		    case 'copy':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listCopy($table, $ids, 'label');
		        break;

		    case 'hide':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listShowHide($table, $ids, 0);
		        break;

		    case 'show':
				$ids = explode(',',$_POST['ids']);
		    	list($success, $message, $redirect) = listShowHide($table, $ids, 1);
		        break;
		}

		$return = array("success" => $success, 'message'=> $message, 'redirect'=>$redirect, 'newvalue'=>$newvalue);

		echo json_encode($return);
		die();
	}
