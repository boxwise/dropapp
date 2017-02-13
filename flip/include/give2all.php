<?

	include($_SERVER['DOCUMENT_ROOT'].'/flip/lib/functions.php');

	$table = 'transactions';
	$action = 'give';

	if($_POST) {

		$people = explode(',',$_POST['people']);
		
/*
		if($_POST['startration']) {
			db_query('INSERT INTO ration (startration) VALUES (NOW())');
		}
*/
		
		foreach($people as $person) {
			$f = db_row('SELECT * FROM people WHERE id = :id',array('id'=>$person));
			if($f['parent_id']==0) {
				$children = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE visible AND NOT deleted AND parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$settings['adult-age'],array('id'=>$person));
				$children += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE visible AND NOT deleted AND id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$settings['adult-age'],array('id'=>$person));
				$adults = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE visible AND NOT deleted AND parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$settings['adult-age'],array('id'=>$person));
				$adults += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE visible AND NOT deleted AND id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$settings['adult-age'],array('id'=>$person));
				$drops = intval($_POST['dropsadult'])*$adults;
				$drops += intval($_POST['dropschild'])*$children;
				db_query('INSERT INTO transactions (people_id,description,drops,transaction_date,user_id) VALUES (:people_id,:description,:drops,NOW(),:user_id)',array('people_id'=>$person,'description'=>$_POST['description'],'drops'=>$drops,'user_id'=>$_SESSION['user']['id']));
				
			}
		}
		
		
		redirect('?action=people');
	}

	$result = db_query('SELECT * FROM people WHERE visible AND parent_id = 0 AND NOT deleted');
	while($row = db_fetch($result)) {
		$ids[] = $row['id'];
	}
	$data['people'] = join(',',$ids);
	
	$data['names'] = 'All families';
	$data['description'] = 'New cycle started '.strftime('%A %e %B %Y');
	$translate['cms_form_submit'] = 'Give drops';
	$cmsmain->assign('translate',$translate);

	// open the template
	$cmsmain->assign('include','cms_form.tpl');

	// put a title above the form
	$cmsmain->assign('title','Give drops to all families');

	addfield('hidden','people','people');
	
	addfield('custom','','<div class="noprint tipofday"><h3>👨‍🏫 Be careful</h3><p>If you press the "Give drops" button on the right, you can\'t turn back anymore!</p></div>');
	
	addfield('text','Families','names',array('readonly'=>true));
	addfield('line','','');
	$data['dropsadult'] = $settings['drops_per_adult'];
	$data['dropschild'] = $settings['drops_per_child'];
	addfield('text','Give drops per adult','dropsadult');
	addfield('text','Give drops per child','dropschild');
// 	$data['startration'] = 1;
// 	addfield('checkbox','Reset ration period','startration');
	addfield('line','','');
	addfield('text','Description','description');

	#addfield('checkbox','Zichtbaar','visible',array('aside'=>true));
	addfield('line','','',array('aside'=>true));
	addfield('created','Created','created',array('aside'=>true));


	// place the form elements and data in the template
	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);

