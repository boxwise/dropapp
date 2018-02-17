<?php

	$weekdays = array('الأحد','الإثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','يوم السبت');
	$months = array('كانون الثاني','فبراير','مارس','أبريل','قد','يونيو','يوليو','أغسطس','سبتمبر','شهر اكتوبر','تشرين الثاني','ديسمبر');

	$weekdays_french = array('Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi');
	$months_french = array('Janvier','Février','Mars','Avril','Mai','Juin','Julliet','Août','Septembre','Octobre','Novembre','Decembre');

	$action = 'market_schedule';
	
	if($_POST) {

		$starttime = intval(substr($_POST['starttime'],0,strpos($_POST['starttime'],':')));
		$starttime += floatval(substr($_POST['starttime'],strpos($_POST['starttime'],':')+1)/60);
		$endtime = intval(substr($_POST['endtime'],0,strpos($_POST['endtime'],':')));
		$endtime += floatval(substr($_POST['endtime'],strpos($_POST['endtime'],':')+1)/60);
		$lunchtime = intval(substr($_POST['lunchtime'],0,strpos($_POST['lunchtime'],':')));
		$lunchtime += floatval(substr($_POST['lunchtime'],strpos($_POST['lunchtime'],':')+1)/60);

		$data['startdate'] = strftime('%A %e %B %Y',strtotime('+'.min($_POST['dates']).' Days'));
		$data['enddate'] = strftime('%A %e %B %Y',strtotime('+'.max($_POST['dates']).' Days'));

		db_query('UPDATE camps SET 
			schedulestart = :start, 
			schedulestop = :stop,
			schedulebreak = :break,
			schedulebreakstart = :breaktime,
			schedulebreakduration = :breakduration, 
			scheduletimeslot = :timeslot 
		WHERE id = :camp',array('camp'=>$_SESSION['camp']['id'], 
			'start'=>$_POST['starttime'], 
			'stop'=>$_POST['endtime'],
			'break'=>intval($_POST['lunchbreak']),
			'breaktime'=>$_POST['lunchtime'],
			'breakduration'=>$_POST['lunchduration'][0],
			'timeslot'=>$_POST['timeslot'][0]
		));
		
		$_SESSION['camp'] = db_row('SELECT * FROM camps WHERE id = :camp',array('camp'=>$_SESSION['camp']['id']));
/*
		$data['starttime'] = ($_SESSION['camp']['schedulestart']?$_SESSION['camp']['schedulestart']:'10:00');
		$data['endtime'] = ($_SESSION['camp']['schedulestop']?$_SESSION['camp']['schedulestop']:'16:00');
		$data['lunchbreak'] = ($_SESSION['camp']['schedulebreak']?$_SESSION['camp']['schedulebreak']:'1');
		$data['lunchtime'] = ($_SESSION['camp']['schedulebreakstart']?$_SESSION['camp']['schedulebreakstart']:'13:00');
		$data['lunchduration'] = ($_SESSION['camp']['schedulebreakduration']?$_SESSION['camp']['schedulebreakduration']:'1');
		$data['timeslot'] = ($_SESSION['camp']['scheduletimeslot']?$_SESSION['camp']['scheduletimeslot']:'0.5');
*/

		$slots = array();
		foreach($_POST['dates'] as $day) {
			$date = strftime('%A %e %B %Y',strtotime('+'.$day.' Days'));

   			$lunch = false;
   			for($time=$starttime;$time<$endtime;$time+=$_POST['timeslot'][0]) {

	            switch ($time-floor($time)) {
		            case '0.0':
		            	$minutes = '00';
		            	break;
		            case '0.25':
		            	$minutes = '15';
		            	break;
		            case '0.5':
		            	$minutes = '30';
		            	break;
		            case '0.75':
		            	$minutes = '45';
		            	break;
	            }

	            $slots[$date][floor($time).":".$minutes]['time'] = ($time>12.5?floor($time)-12:floor($time)).":".$minutes.($time>12.5?' pm':' am');

				if(!$_POST['lunchbreak'] || ($time < $_POST['lunchtime'] || $time >= ($_POST['lunchtime']+$_POST['lunchduration'][0]))) {

		            $slots[$date][floor($time).":".$minutes]['count'] = 0;
		            $slots[$date][floor($time).":".$minutes]['containers'] = array();
	/*
		            $slots[$date][floor($time).":".($time-floor($time)?'30':'00')]['count'] = 0;
		            $slots[$date][floor($time).":".($time-floor($time)?'30':'00')]['containers'] = '';
	*/
				} else {
					if(!$lunch) $slots[$date][floor($time).":".$minutes]['lunch'] = true;
					$lunch = true;
				}
            }

		}
		

		$result = db_query('SELECT container, COUNT(id) AS count FROM people WHERE visible AND camp_id = '.$_SESSION['camp']['id'].' AND NOT deleted GROUP BY container');
		while($row = db_fetch($result)) {
			$slot = randomtimeslot($slots);
			$slots[$slot['date']][$slot['time']]['count'] += $row['count'];
			$slots[$slot['date']][$slot['time']]['containers'][] = $row['container'];			
		}


/*
		foreach($slots as $date=>$d) {
			echo '<strong>'.$date.'</strong><br />';
			foreach($slots[$date] as $time=>$t) {
				echo $time.' '.join(', ',$slots[$date][$time]['containers']).'<br />';
			}
			echo '<br />';
		}
*/

		$cmsmain->assign('include','market_schedule.tpl');

		$cmsmain->assign('data',$data);

		$cmsmain->assign('weekdays',$weekdays);
		$cmsmain->assign('months',$months);
		$cmsmain->assign('weekdays_french',$weekdays_french);
		$cmsmain->assign('months_french',$months_french);

		$cmsmain->assign('slots',$slots);


	} else {

		// open the template
		$cmsmain->assign('include','cms_form.tpl');
		$cmsmain->assign('title','Market schedule');

		$translate['cms_form_submit'] = 'Make schedule';
		$cmsmain->assign('translate',$translate);

		$data['starttime'] = ($_SESSION['camp']['schedulestart']?$_SESSION['camp']['schedulestart']:'10:00');
		$data['endtime'] = ($_SESSION['camp']['schedulestop']?$_SESSION['camp']['schedulestop']:'16:00');
		$data['lunchbreak'] = intval($_SESSION['camp']['schedulebreak']);
		$data['lunchtime'] = ($_SESSION['camp']['schedulebreakstart']?$_SESSION['camp']['schedulebreakstart']:'13:00');
		$data['lunchduration'] = ($_SESSION['camp']['schedulebreakduration']?$_SESSION['camp']['schedulebreakduration']:'1');
		$data['timeslot'] = ($_SESSION['camp']['scheduletimeslot']?$_SESSION['camp']['scheduletimeslot']:'0.5');

dump($data['lunchbreak']);

		for($i=1;$i<60;$i++) {
			$datelist[] = array('value'=>$i,'label'=>strftime('%A %e %B %Y',strtotime('+'.$i.' Days')));
		}

		addfield('select','Dates for next cycle','dates',array('multiple'=>true,'options'=>$datelist));
		addfield('line');
		addfield('date','Daily start time','starttime',array('date'=>false,'time'=>true));
		addfield('date','Daily end time','endtime',array('date'=>false,'time'=>true));
		addfield('select','Length of timeslots','timeslot',array('multiple'=>false,'options'=>array(
			array('value'=> '3', 'label'=>'3 hours'),
			array('value'=> '2', 'label'=>'2 hours'),
			array('value'=> '1', 'label'=>'1 hour'),
			array('value'=> '0.5', 'label'=>'30 minutes'), 
			array('value'=> '0.25', 'label'=>'15 minutes')
			), 'required'=> true));

		// place the form elements and data in the template
		addfield('line');
		addfield('checkbox','Include lunch break','lunchbreak', array('onchange' => 'toggleLunch()'));
		addfield('date','Lunch time','lunchtime',array('date'=>false,'time'=>true, 'hidden'=>!$data['lunchbreak']));
		addfield('select','Lunch length','lunchduration',array('multiple'=>false, 'hidden'=>!$data['lunchbreak'], 'options'=>array(
			array('value'=> '0.5', 'label'=>'30 minutes'), 
			array('value'=> '1', 'label'=>'1 hour'),
			array('value'=> '1.5', 'label'=>'1,5 hour'),
			array('value'=> '2', 'label'=>'2 hours')
			), 'required'=> true));

		$cmsmain->assign('data',$data);
		$cmsmain->assign('formelements',$formdata);
		$cmsmain->assign('formbuttons',$formbuttons);

	}

	function randomtimeslot($slots) {
		$min = minpeople($slots);
		
		$set = array();
		foreach($slots as $date=>$dayslots) {
			foreach($slots[$date] as $time=>$s) {
				if($slots[$date][$time]['count'] == $min && !$slots[$date][$time]['lunch']) {
					$set[] = array('date'=>$date,'time'=>$time);
				}
			}
		}

		return $set[array_rand($set)];
	}

	function minpeople($slots) {
		foreach($slots as $date=>$dayslots) {
			foreach($slots[$date] as $time=>$s) {
				if(!$slots[$date][$time]['lunch']) {
					$count = $slots[$date][$time]['count'];
					if(!isset($min) || $count<$min) $min = $count;
				}
			}
		}
		return $min;
	}
