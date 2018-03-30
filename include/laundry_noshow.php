<?php

	$table = 'laundry_appointments';
	$action = 'laundry';
		
	$timeslot = intval($_GET['timeslot']);

	$data = db_row('SELECT la.*, ls.day FROM laundry_slots AS ls LEFT OUTER JOIN laundry_appointments AS la ON la.timeslot = ls.id AND cyclestart = :cyclestart LEFT OUTER JOIN people AS p ON p.id = la.people_id WHERE timeslot = :id',array('cyclestart'=>$settings['laundry_cyclestart'], 'id'=>$timeslot));
	
	db_query('UPDATE laundry_appointments SET noshow = NOT noshow WHERE id = :id',array('id'=>$data['id']));
	
	redirect('?action=laundry#'.$data['day']);
