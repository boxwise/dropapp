<?php

	$table = 'transactions';
	$action = 'give';

	if($_POST) {

		$people = explode(',',$_POST['people']);

/*
		if($_POST['startration']) {
			db_query('INSERT INTO ration (startration) VALUES (NOW())');
		}
*/

		db_query('UPDATE camps SET 
			dropsperadult = :dropsperadult, 
			dropsperchild = :dropsperchild, 
			cyclestart = NOW()
		WHERE id = :camp',array('camp'=>$_SESSION['camp']['id'], 
			'dropsperadult'=>$_POST['dropsadult'], 
			'dropsperchild'=>$_POST['dropschild']
		));
		$_SESSION['camp'] = db_row('SELECT * FROM camps WHERE id = :camp',array('camp'=>$_SESSION['camp']['id']));

		foreach($people as $person) {
			$f = db_row('SELECT * FROM people WHERE camp_id = :camp_id AND id = :id',array('id'=>$person,'camp_id'=>$_SESSION['camp']['id']));
			if($f['parent_id']==0) {
				$children = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE visible AND NOT deleted AND parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$settings['adult-age'],array('id'=>$person));
				$children += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE visible AND NOT deleted AND id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$settings['adult-age'],array('id'=>$person));
				$adults = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE visible AND NOT deleted AND parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$settings['adult-age'],array('id'=>$person));
				$adults += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE visible AND NOT deleted AND id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$settings['adult-age'],array('id'=>$person));
				$drops = intval($_POST['dropsadult'])*$adults;
				$drops += intval($_POST['dropschild'])*$children;

				$volunteers = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND (id = :id OR parent_id = :id) AND volunteer',array('id'=>$person));

				$currentdrops = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :people_id',array('people_id'=>$person));
				
				if(!$volunteers) {
					$max = $adults * $_SESSION['camp']['dropcapadult'] + $children * $_SESSION['camp']['dropcapchild'];
					$cap = -($currentdrops+$drops)+$max;
					if($cap<0) {
						$drops += $cap;
						$_POST['description'] .= " (capped to maximum)";
					}
					
				}


				if(isset($settings['no_rollover_points']) && ($settings['no_rollover_points'] == 1)) {
					db_query('INSERT INTO transactions (people_id,description,drops,transaction_date,user_id) VALUES (:people_id,:description,:drops,NOW(),:user_id)',array('people_id'=>$person,'description'=>'Reset','drops'=>($currentdrops * -1),'user_id'=>$_SESSION['user']['id']));
				}

				db_query('INSERT INTO transactions (people_id,description,drops,transaction_date,user_id) VALUES (:people_id,:description,:drops,NOW(),:user_id)',array('people_id'=>$person,'description'=>$_POST['description'],'drops'=>$drops,'user_id'=>$_SESSION['user']['id']));

			}
		}


		redirect('?action=people');
	}

	$result = db_query('SELECT * FROM people WHERE camp_id = :camp_id AND visible AND parent_id = 0 AND NOT deleted',array('camp_id'=>$_SESSION['camp']['id']));
	while($row = db_fetch($result)) {
		$ids[] = $row['id'];
	}
	$data['people'] = join(',',$ids);

	$data['names'] = 'All families';
	$data['description'] = 'New cycle started '.strftime('%A %e %B %Y');
	$translate['cms_form_submit'] = 'Give '. ucwords($translate['market_coins_short']);
	$cmsmain->assign('translate',$translate);

	// open the template
	$cmsmain->assign('include','cms_form.tpl');

	// put a title above the form
	$cmsmain->assign('title','Give '.ucwords($translate['market_coins_short']).' to all families');

	addfield('hidden','people','people');


	if(isset($settings['no_rollover_points']) && ($settings['no_rollover_points'] == 1)) {
		addfield('custom','','<div class="noprint tipofday"><h3>👨‍🏫 Be careful</h3><p>If you press the "Give '.ucwords($translate['market_coins_short']).'" button on the right, you can\'t turn back anymore! All the current '.$translate['market_coins_short'].' will be reset and new '.$translate['market_coins_short'].' will be given to all families!</p></div>');		
	} else {
		addfield('custom','','<div class="noprint tipofday"><h3>👨‍🏫 Be careful</h3><p>If you press the "Give '.ucwords($translate['market_coins_short']).'" button on the right, you can\'t turn back anymore!</p></div>');		
	}

	addfield('text','Families','names',array('readonly'=>true));
	addfield('line','','');
	$data['hidecancel'] = true;
	$data['dropsadult'] = $_SESSION['camp']['dropsperadult'];
	$data['dropschild'] = $_SESSION['camp']['dropsperchild'];

	addfield('text','Give '.ucwords($translate['market_coins_short']).' per adult','dropsadult', array('required'=>true));
	addfield('text','Give '.ucwords($translate['market_coins_short']).' per child','dropschild', array('required'=>true));
// 	$data['startration'] = 1;
// 	addfield('checkbox','Reset ration period','startration');
	addfield('line','','');
	addfield('text','Description','description');


	#addfield('checkbox','Zichtbaar','visible',array('aside'=>true));
	#addfield('line','','',array('aside'=>true));
	#addfield('created','Created','created',array('aside'=>true));


	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
