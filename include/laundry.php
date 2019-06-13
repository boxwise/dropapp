<?
	if(!isset($_SESSION['laundrystation'])) $_SESSION['laundrystation'] = db_value('SELECT id FROM laundry_stations ORDER BY id LIMIT 1');
	if($_GET['station']) $_SESSION['laundrystation'] = intval($_GET['station']);
	
	$data['stationlist'] = db_simplearray('SELECT id, label FROM laundry_stations WHERE camp_id = :camp_id ORDER BY id',array('camp_id'=>$_SESSION['camp']['id']));
	
	$data['station'] = $_SESSION['laundrystation'];
	$data['stationname'] = db_value('SELECT label FROM laundry_stations WHERE id = :id',array('id'=>$data['station']));
	
	$data['offset'] = ($_GET['cycle']=='next'?14:($_GET['cycle']=='current'?0:$_SESSION['laundryoffset']));
	if(!isset($data['offset'])) $data['offset'] = 0;

	$_SESSION['laundryoffset'] = $data['offset'];
		
	$cyclestart = strftime('%Y-%m-%d',strtotime('+'.$data['offset'].' days', strtotime($_SESSION['camp']['laundry_cyclestart'])));
	
	$data['times'] = db_simplearray('SELECT DISTINCT ls.time, lt.label FROM laundry_slots AS ls, laundry_times AS lt WHERE lt.id = ls.time');
	
	$result = db_query('SELECT DISTINCT day FROM laundry_slots');
	while($day = db_fetch($result)) {
		$t = strtotime('+'.$day['day'].' days',strtotime($cyclestart));
		$data['dates'][$day['day']]['label'] = strftime('%A %d %B %Y',$t);
		if($t < strtotime(strftime('%Y-%m-%d'))) $data['dates'][$day['day']]['past'] = true;
	}
	
	$data['machines'] = db_simplearray('SELECT id, label FROM laundry_machines WHERE station = :station ORDER BY id',array('station'=>$data['station']));
	
	$result = db_query('SELECT ls.id AS timeslot, la.people_id, ls.day, ls.time, ls.machine, lm.label AS machinename, la.noshow, la.dropoff, la.collected, la.comment, p.id, p.firstname, p.lastname, p.container FROM laundry_slots AS ls LEFT OUTER JOIN laundry_appointments AS la ON la.timeslot = ls.id AND cyclestart = :cyclestart LEFT OUTER JOIN people AS p ON p.id = la.people_id LEFT OUTER JOIN laundry_machines AS lm ON lm.id = ls.machine WHERE lm.station = :station ORDER BY ls.id',array('cyclestart'=>$cyclestart, 'station'=>$data['station']));
	while($row = db_fetch($result)) {
		if($row['people_id']==-1) $row['firstname'] = 'Drop Laundry 💧';
		$data['slots'][$row['day']][$row['time']][$row['machine']] = $row;
	}
	
	$cmsmain->assign('data',$data);
	$cmsmain->assign('include','laundry.tpl');
