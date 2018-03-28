<?php
	require('library/core.php');
	
	db_query('DELETE FROM laundry_slots');
	$x = 1;
	for($day=0;$day<13;$day++) {
		if($day!=6) {
			for($time=1;$time<=5;$time++) {
				for($machine=2;$machine<=6;$machine++) {
					$x++;
					echo $day.' '.$time.' '.$machine.' '.'<br />';
					db_query('INSERT INTO laundry_slots (id, day, time, machine) VALUES (:id,:day,:time,:machine)',array('id'=>$x,'day'=>$day,'time'=>$time,'machine'=>$machine));
				}

			}
		}
	}