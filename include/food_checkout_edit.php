<?php

	require_once($_SERVER["DOCUMENT_ROOT"].'/'.$settings['rootdir'].'library/core.php');

	$ajax = checkajax();
	$action = 'food_checkout_edit';

	if(!$ajax) {

		if($_POST){

			#Prepare POST for formhandler
			$post = $_POST;
			#transactions
			$ttmp = filter_input(INPUT_POST, ftran, FILTER_SANITIZE_STRING,FILTER_REQUIRE_ARRAY);

			#save data of distribution table
			$_POST = array_map('intval', filter_input(INPUT_POST, dist, FILTER_SANITIZE_STRING,FILTER_REQUIRE_ARRAY));
			if($post['id']) $_POST['id'] = $post['id'];
			$_POST['label'] = $post['label'];
			$table = 'food_distributions';
			$handler = new formHandler($table);
			$handler->savePost(array_keys($_POST));
		
			#save data of food transactions
			$table = 'food_transactions';
			$_POST = array();
			if($post['id']) {$_POST['dist_id'] = $post['id'];}
			else {
				$_POST['dist_id'] = intval(db_value('SELECT id from food_distributions ORDER BY id DESC LIMIT 1'));
			}
			foreach($ttmp as $pkey => $farray){
				foreach ($farray as $fkey => $fcount) {
					if($fcount) {
						$_POST['people_id'] = $pkey;
						$_POST['food_id'] = $fkey; 
						$_POST['count'] = $fcount;	
						$handler = new formHandler($table);
						$handler->savePost(array_keys($_POST));

						#Reduce food stock in food products menu
						$fstock = db_value('SELECT stock from food WHERE id = '.$fkey);
						if(($fstock - $fcount)>0) {
							$_POST['stock'] = $fstock -$fcount;
							$_POST['id'] = $fkey;
							$foodstock_handler = new formHandler('food');
							$foodstock_handler->savePost(array('stock'));	
							unset($_POST['stock']);
							unset($_POST['id']);
						}
					}
				}
			}
			$cmsmain->assign('post',$post);

			if($post['__action']=='submitandcancel') redirect('?action='.$post['_origin']);
			if(!$id) redirect('?action=food_checkout_edit&origin='.$post['_origin'].'&id='.$_POST['dist_id']);
		}

		#Form
		$cmsmain->assign('include','cms_form.tpl');
		$cmsmain->assign('title','Food Distribution');

		#-------Top Part of Form for Distribution changes
		$table = 'food_distributions';
		$data = db_row('SELECT * FROM '.$table.' WHERE id = :id', array('id'=>$id)); 
		addfield('hidden','','id');
		addfield('text', 'Comment', 'label', array('required' => true));

		#formelements for cms_form_fooddist.tpl
		for($i = 1; $i < 7; $i++){
			if($i==1) {addcolumn('select','Food '.$i ,'food_'.$i, array('required' => true));}
			else {addcolumn('select','Food '.$i ,'food_'.$i);}
		}
		#Triple string for Java Script
		$onchange = <<<'EOD'
			selectFood(["food_1", "food_2", "food_3", "food_4", "food_5", "food_6"], $("[name = 'id']").val()) 
EOD;
		addfield('fooddist_select', '','', array('listdata' => $listdata, 'placeholder' => 'Select', 'options'=> db_array('SELECT f.id AS value, f.name AS label FROM food AS f WHERE f.visible AND NOT COALESCE(f.deleted,0) ORDER BY f.name'), 'onchange' => $onchange, 'head' => false));
		$listdata = array();
		#addfield('line','','');


		#----------Ajax Placeholder for food transactions
		addfield('ajaxstart','', '', array('id'=>'ajax-content'));
		if($id)	$perc = ftran_list(array_diff(array_slice($data,2,5, true),["0"]), $id, $_SESSION['camp']['id'], $settings['adult-age']);
		addfield('ajaxend');
		if($id) addfield('custom', '', '<font size=4><b>'.$perc['hidden'].' of '.$perc['all'].' ('.round($perc['hidden']/$perc['all']*100).'%) families <br> '.$perc['hid_peo'].' of '.$perc['all_peo'].' ('.round($perc['hid_peo']/$perc['all_peo']*100).'%) people</b><br>collected their food </font>.', array('aside' => true, 'asidetop' => true));

		$cmsmain->assign('data',$data);
		$cmsmain->assign('formelements',$formdata);
		addformbutton('submitandcancel', 'Save & Go back'); 
		$cmsmain->assign('formbuttons',$formbuttons);
		$translate['cms_form_submit'] = 'Save';
		$translate['cms_form_cancel'] = 'Go back';
		$cmsmain->assign('translate',$translate);
		$cmsmain->assign('checkConnectionOnSubmit', 'true');

	} else {
		
		#------Ajax after selecting a food in Selection 
		$ajaxform = new Zmarty;
		$table = 'food_transactions';

		#input from ajax
		$food_cols = array_map('intval',filter_input(INPUT_POST, foods, FILTER_SANITIZE_NUMBER_INT,FILTER_REQUIRE_ARRAY));
		$id = filter_input(INPUT_POST, dist_id, FILTER_SANITIZE_NUMBER_INT);
		
		ftran_list(array_diff($food_cols,[0]), $id, $_SESSION['camp']['id'], $settings['adult-age']);

		$ajaxform->assign('formelements',$formdata);
		$htmlcontent = $ajaxform->fetch('cms_form_ajax.tpl');
		$success = true;
		$return = array("success" => $success, 'htmlcontent' => $htmlcontent, 'message'=> $message);
		echo json_encode($return);
	}


#------------Function for food_transactions list
	function ftran_list($food_array, $dist_id, $camp_id, $adult_age = 18){
		global $listdata, $formdata;

		#build query
		$query = 'SELECT 1 AS al, p.id AS id, p.container AS container, CONCAT(p.firstname, " ", lastname) AS name,
			(SELECT COUNT(pc.id) 
				FROM people AS pc
				WHERE pc.parent_id=p.id AND (YEAR(NOW()) - YEAR(pc.date_of_birth) - (DAYOFYEAR(NOW()) > DAYOFYEAR(pc.date_of_birth))) < '.$adult_age.') AS children,
			(SELECT COUNT(pa.id) + 1 
				FROM people AS pa
				WHERE pa.parent_id=p.id AND (YEAR(NOW()) - YEAR(pa.date_of_birth) - (DAYOFYEAR(NOW()) > DAYOFYEAR(pa.date_of_birth))) > '.$adult_age.') AS adults,
			(SELECT COALESCE(SUM(pe.extraportion),0) + p.extraportion
				FROM people AS pe
				WHERE pe.parent_id=p.id) AS extra,
			CONCAT((SELECT adults), "Ad", IF((SELECT children), CONCAT(", ", (SELECT children), "Kid"), ""), IF((SELECT extra), CONCAT(" +", (SELECT extra)),"")) AS family,
			(SELECT adults)+(SELECT children) AS al2';
		if(!$dist_id) {
			$query .=', 0 AS hidden, 0 AS hidden2';
		} else {
			$query .=', (SELECT IF(COUNT(ft.id), 1,0) FROM food_transactions AS ft WHERE ft.people_id = p.id AND ft.dist_id = '.$dist_id.') AS hidden,
(SELECT IF(COUNT(ft.id), (SELECT adults)+(SELECT children),0) FROM food_transactions AS ft WHERE ft.people_id = p.id AND ft.dist_id = '.$dist_id.') AS hidden2';
		}
		foreach($food_array as $fkey => $fval) {
			$query .= ', (CEIL(((SELECT children)*f'.$fkey.'.perchild + (SELECT adults)*f'.$fkey.'.peradult)/f'.$fkey.'.package) + (SELECT extra)) AS portion_'.$fkey;
		}
		$query .= ' FROM people AS p';
		if($dist_id) $query.=', food_distributions AS fd';
		foreach($food_array as $fkey => $fval) {
			$query .= ', food AS f'.$fkey;
		}
		$query .= ' WHERE p.parent_id=0 AND p.camp_id = '.$camp_id;
		if($dist_id) {
			$query .= ' AND fd.id='.$dist_id.' AND COALESCE(p.created, 0) < fd.created AND (NOT p.deleted OR fd.created < p.deleted)';
		} else {
			$query .= ' AND NOT p.deleted';
		}
		foreach($food_array as $fkey => $fval) {
			$query .= ' AND f'.$fkey.'.id = '.$fval; 
		}
		$query .= ' ORDER BY SUBSTRING(container, 1,1), SUBSTRING(container, 2, 10)*1';
		$ftdata =getlistdata($query);

		#Table Definitions
		addcolumn('text','Container','container');
		addcolumn('text','Name','name');
		addcolumn('text','Family','family');
		foreach($food_array as $fkey => $fval) {
			addcolumn('input', db_value('SELECT name FROM food WHERE id='.$fval), 'portion_'.$fkey, array('food_id' => $fval));
		}

		addfield('foodtran', '','', array('listdata'=> $listdata, 'data' => $ftdata, 'maxheight' => 'window'));
		return array('hidden' => array_sum(array_column($ftdata, 'hidden')), 'all' => array_sum(array_column($ftdata, 'al')), 'hid_peo' => array_sum(array_column($ftdata, 'hidden2')), 'all_peo' => array_sum(array_column($ftdata, 'al2')));
	}
