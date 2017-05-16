<?php

	$weekdays = array('الأحد','الإثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','يوم السبت');
	$months = array('كانون الثاني','فبراير','مارس','أبريل','قد','يونيو','يوليو','أغسطس','سبتمبر','شهر اكتوبر','تشرين الثاني','ديسمبر');

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
		

		$result = db_query('SELECT container, COUNT(id) AS count FROM people WHERE visible AND NOT deleted GROUP BY container');
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
		$cmsmain->assign('slots',$slots);


	} else {

		// open the template
		$cmsmain->assign('include','cms_form.tpl');
		$cmsmain->assign('title','Market schedule');

		$translate['cms_form_submit'] = 'Make schedule';
		$cmsmain->assign('translate',$translate);

		$data['starttime'] = '10:00';
		$data['endtime'] = '16:00';
		$data['lunchtime'] = '13:00';
		$data['lunchduration'] = '1';
		$data['timeslot'] = '0.5';

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
		addfield('date','Lunch time','lunchtime',array('date'=>false,'time'=>true, 'hidden'=>true));
		addfield('select','Lunch length','lunchduration',array('multiple'=>false, 'hidden'=>true, 'options'=>array(
			array('value'=> '2', 'label'=>'2 hours'),
			array('value'=> '1', 'label'=>'1 hour'),
			array('value'=> '0.5', 'label'=>'30 minutes') 
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
