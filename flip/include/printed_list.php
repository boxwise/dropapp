<?
	include($_SERVER['DOCUMENT_ROOT'].'/flip/lib/functions.php');

	$containers = db_array('SELECT people.container, count(*) AS count FROM people WHERE visible AND NOT deleted AND NOT container = "AAA1" GROUP BY container ORDER BY SUBSTRING(container, 1,1), SUBSTRING(container, 2, 10)*1');

	if($_GET['export']) {

		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename=container-list.csv');
		header('Pragma: no-cache');
		echo "Container,People\n";
		foreach($containers as $c) {
			echo $c['container'].','.$c['count']."\n";
		}	
		die();	
	} else {
		
		$cmsmain->assign('include','printed_list.tpl');
	
		$cmsmain->assign('containers',$containers);
	
	
		// place the form elements and data in the template
		$cmsmain->assign('data',$data);
		$cmsmain->assign('formelements',$formdata);
		$cmsmain->assign('formbuttons',$formbuttons);

	}
