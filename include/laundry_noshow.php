<?php

	$table = 'laundry_appointments';
	$action = 'laundry';
		
	$timeslot = intval($_GET['timeslot']);
	$offset = intval($_GET['offset']);
	$cyclestart = strftime('%Y-%m-%d',strtotime('+'.$offset.' days', strtotime($settings['laundry_cyclestart'])));

	$data = db_row('SELECT la.*, ls.day FROM laundry_slots AS ls LEFT OUTER JOIN laundry_appointments AS la ON la.timeslot = ls.id AND cyclestart = :cyclestart LEFT OUTER JOIN people AS p ON p.id = la.people_id WHERE timeslot = :id',array('cyclestart'=>$cyclestart, 'id'=>$timeslot));
	
	$field = safestring($_GET['toggle']);

	switch($field) {
		case 'noshow':
		case 'dropoff':
		case 'collected':
			db_query('UPDATE laundry_appointments SET '.$field.' = NOT '.$field.' WHERE id = :id',array('id'=>$data['id']));
	}
	
	redirect('?action=laundry#'.$data['day']);
