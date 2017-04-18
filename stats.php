<?php
	require('core.php');
	
	for($i=0;$i<150;$i++) {
		echo strftime('%d-%m-%y',strtotime('-'.$i.' days')).'<br />';

		$date = strftime('%y-%m-%d 00:00',strtotime('-'.$i.' days')).'<br />';
		echo db_value('SELECT count(id) FROM people WHERE (created <= "'.$date.'" OR created IS NULL) AND (deleted > "'.$date.'" OR deleted = "0000-00-00")').' (visible: ';
		echo db_value('SELECT count(id) FROM people WHERE (created <= "'.$date.'" OR created IS NULL) AND visible AND (deleted > "'.$date.'" OR deleted = "0000-00-00")').')<br /><br />';
	}
