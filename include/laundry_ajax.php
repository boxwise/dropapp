<?php

	if(!DEFINED('CORE')) include('../library/core.php');

	$ajax = checkajax();
	if(!$ajax) die();

	$ajaxform = new Zmarty;

	$data['people_id'] = intval($_POST['people_id']);

	$adults = db_value('SELECT SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < 13, 0, 1)) AS adults FROM people WHERE id = :id OR parent_id = :id AND NOT deleted ',array('id'=>$data['people_id']));
	$children = db_value('SELECT SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < 13, 1, 0)) AS adults FROM people WHERE id = :id OR parent_id = :id AND NOT deleted ',array('id'=>$data['people_id']));

	$element['field'] .= '<h2 class="light">This family has <span class="number">'.multiple($adults,'adult','adults').'</span> and <span class="number">'.multiple($children,'child','children').'</span> and is entitled to <span class="number">'.multiple(ceil((1*$adults)+(0.5*$children)),'washing machine slot','washing machine slots').'</span> every cycle.</h2>';
	
	$result = db_query("SELECT ls.*, lt.label, lm.label AS machine FROM laundry_appointments AS la, laundry_slots AS ls, laundry_times AS lt, laundry_machines AS lm WHERE lm.id = ls.machine AND lt.id = ls.time AND la.timeslot = ls.id AND la.people_id = :people_id AND la.cyclestart = :cyclestart ORDER BY timeslot",array('people_id'=>$data['people_id'],'cyclestart'=>$settings['laundry_cyclestart']));
#	if(db_numrows($result)) $element['field'] .= '<h2>Current appointments in this cycle';
	while($row = db_fetch($result)) {
		$app[] = ($row['id']==$_POST['timeslot']?'<span class="number">':'').strftime('%A %d %B %Y', strtotime('+'.$row['day'].' days', strtotime($settings['laundry_cyclestart']))).', '.$row['label'].' '.$row['machine'].($row['id']==$_POST['timeslot']?' (this one)</span>':'');
	}
	
	if(is_array($app))
		$element['field'] .= '<br /><h2 class="light">Current appointments in this cycle:<br />'.join($app,'<br />');

	$ajaxform->assign('element',$element);
	$htmlcontent = $ajaxform->fetch('cms_form_custom.tpl');

	$success = true;
	$return = array("success" => $success, 'htmlcontent' => $htmlcontent, 'message'=> $message);
	echo json_encode($return);

	function multiple($i, $single, $plural) {
		if($i==0) return 'no '.$plural;
		if($i==1) return 'one '.$single;
		return $i.' '.$plural;
	}