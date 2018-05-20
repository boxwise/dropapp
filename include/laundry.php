<?
	
	$data['offset'] = ($_GET['cycle']=='next'?14:($_GET['cycle']=='current'?0:$_SESSION['laundryoffset']));
	$_SESSION['laundryoffset'] = $data['offset'];
	
	
	$cyclestart = strftime('%Y-%m-%d',strtotime('+'.$data['offset'].' days', strtotime($settings['laundry_cyclestart'])));
	
	$data['times'] = db_simplearray('SELECT DISTINCT ls.time, lt.label FROM laundry_slots AS ls, laundry_times AS lt WHERE lt.id = ls.time');
	
	$result = db_query('SELECT DISTINCT day FROM laundry_slots');
	while($day = db_fetch($result)) {
		$t = strtotime('+'.$day['day'].' days',strtotime($cyclestart));
		$data['dates'][$day['day']]['label'] = strftime('%A %d %B %Y',$t);
		if($t < strtotime(strftime('%Y-%m-%d'))) $data['dates'][$day['day']]['past'] = true;
	}
	
	$data['machines'] = db_simplearray('SELECT id, label FROM laundry_machines ORDER BY id');
	
	
	$result = db_query('SELECT ls.id AS timeslot, la.people_id, ls.day, ls.time, ls.machine, lm.label AS machinename, la.noshow, la.dropoff, la.collected, la.comment, p.* FROM laundry_slots AS ls LEFT OUTER JOIN laundry_appointments AS la ON la.timeslot = ls.id AND cyclestart = :cyclestart LEFT OUTER JOIN people AS p ON p.id = la.people_id LEFT OUTER JOIN laundry_machines AS lm ON lm.id = ls.machine ORDER BY ls.id ',array('cyclestart'=>$cyclestart));
	while($row = db_fetch($result)) {
		if($row['people_id']==-1) $row['firstname'] = 'Drop Laundry 💧';
		$data['slots'][$row['day']][$row['time']][$row['machine']] = $row;
	}
	
	$cmsmain->assign('data',$data);
	$cmsmain->assign('include','laundry.tpl');
