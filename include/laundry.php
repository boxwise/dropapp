<?
	
	$data['times'] = db_simplearray('SELECT DISTINCT ls.time, lt.label FROM laundry_slots AS ls, laundry_times AS lt WHERE lt.id = ls.time');
	
	$result = db_query('SELECT DISTINCT day FROM laundry_slots');
	while($day = db_fetch($result)) {
		$data['dates'][$day['day']] = strftime('%A %d %B %Y',strtotime('+'.$day['day'].' days',strtotime($settings['laundry_cyclestart'])));
	}
	
	$data['machines'] = db_simplearray('SELECT id, label FROM laundry_machines');
	
	$result = db_query('SELECT ls.id AS timeslot, ls.day, ls.time, ls.machine, lm.label AS machinename, p.* FROM laundry_slots AS ls LEFT OUTER JOIN laundry_appointments AS la ON la.timeslot = ls.id AND cyclestart = :cyclestart LEFT OUTER JOIN people AS p ON p.id = la.people_id LEFT OUTER JOIN laundry_machines AS lm ON lm.id = ls.machine ORDER BY ls.id ',array('cyclestart'=>$settings['laundry_cyclestart']));
	while($row = db_fetch($result)) {
		$data['slots'][$row['day']][$row['time']][$row['machine']] = $row;
	}
	
	$cmsmain->assign('data',$data);
	$cmsmain->assign('include','laundry.tpl');
