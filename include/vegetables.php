<?php

	$table = 'people';

	initlist();

	$cmsmain->assign('title','Vegetables');

	$result = db_query('SELECT FLOOR((COUNT(id) + SUM(extraportion))/3) AS green, (COUNT(id) + SUM(extraportion))%3 AS red FROM people WHERE visible AND NOT deleted GROUP BY container');
	while($row = db_fetch($result)) {
		$data[0]['green'] += $row['green'];
		$data[0]['red'] += $row['red'];
	}

	$data[0]['residents'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted ');
	$data[0]['adults'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted  AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 >= '.$settings['adult-age']);
	$data[0]['children'] = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 < '.$settings['adult-age']);
	$data[0]['containers'] = db_value('SELECT COUNT(DISTINCT(container)) FROM people WHERE visible AND NOT deleted');
	$data[0]['extraportion'] = db_value('SELECT SUM(extraportion) FROM people WHERE visible AND NOT deleted');

	#DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), t.date_of_birth)), "%Y")+0 AS age
	listsetting('allowcopy', false);
	listsetting('allowedit', false);
	listsetting('allowadd', false);
	listsetting('allowdelete', false);
	listsetting('allowselect', false);
	listsetting('allowselectall', false);
	listsetting('allowsort', false);
	listsetting('allowmove', false);

	addcolumn('text','Green bags (3)','green');
	addcolumn('text','Red bags (1)','red');
	addcolumn('text','Total residents','residents');
	addcolumn('text','Adults','adults');
	addcolumn('text','Children','children');
	addcolumn('text','Extra Portions','extraportion');
	addcolumn('text','Containers','containers');

	$cmsmain->assign('data',$data);
	$cmsmain->assign('listconfig',$listconfig);
	$cmsmain->assign('listdata',$listdata);
	$cmsmain->assign('include','cms_list.tpl');
