<?php

	$table = 'qr';
	$action = 'qr';

	if($_POST) {

		$data['fulllabel'] = $_POST['fulllabel'];

		if($_POST['label']) {
			$i=0;
			$labels = explode(',',$_POST['label']);
			foreach($labels as $l) {
				$data['labels'][$i] = db_row('
				SELECT s.box_id, qr.code AS hash, CONCAT(p.name," (",s.items,"x)") AS product, g.shortlabel AS gender, s2.label AS size
				FROM stock AS s
				LEFT OUTER JOIN products AS p ON p.id = s.product_id
				LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
				LEFT OUTER JOIN sizes AS s2 ON s2.id = s.size_id
				LEFT OUTER JOIN qr ON s.qr_id = qr.id
				WHERE s.id = :id',array('id'=>$l));
				if(!$data['labels'][$i]['hash']) {
					$id = db_value('SELECT id FROM qr ORDER BY id DESC LIMIT 1')+1;
					$data['labels'][$i]['hash'] = md5($id);
					db_query('INSERT INTO qr (id, code, created) VALUES ('.$id.',"'.$data['labels'][$i]['hash'].'",NOW())');
					db_query('UPDATE stock AS s SET qr_id = :qr_id, modified = NOW() WHERE id = :id',array('id'=>$l,'qr_id'=>$id));
				}
				$i++;
			}

		} else {
			for($i=0;$i<$_POST['count'];$i++) {
				$id = db_value('SELECT id FROM qr ORDER BY id DESC LIMIT 1')+1;
				$data['labels'][$i]['hash'] = md5($id);
				db_query('INSERT INTO qr (id, code, created) VALUES ('.$id.',"'.$data['labels'][$i]['hash'].'",NOW())');
			}
		}
		$cmsmain->assign('include','boxlabels.tpl');

		$cmsmain->assign('data',$data);

	} else {

		// open the template
		$cmsmain->assign('include','cms_form.tpl');
		$cmsmain->assign('title','New QR Box Labels');

		$data['count'] = 1;

		$translate['cms_form_submit'] = 'Make labels';
		$cmsmain->assign('translate',$translate);

		if($_GET['label']) {
			$data['label'] = $_GET['label'];
			addfield('hidden','','count');
			addfield('hidden','','label');
		} else {
			$data['count'] = 24;
			addfield('number','Number of labels','count',array('min'=>0,'max'=>999));
		}
		addfield('checkbox','Make big labels including fields for box number and contents','fulllabel');

		// place the form elements and data in the template
		$cmsmain->assign('data',$data);
		$cmsmain->assign('formelements',$formdata);
		$cmsmain->assign('formbuttons',$formbuttons);

	}
