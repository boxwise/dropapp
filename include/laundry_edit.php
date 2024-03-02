<?php

$table = 'laundry_appointments';
$action = 'laundry';
$offset = intval($_GET['offset']);

$cyclestart = strftime('%Y-%m-%d', strtotime('+'.$offset.' days', strtotime((string) $_SESSION['camp']['laundry_cyclestart'])));

if ($_POST) {
    $_POST['cyclestart'] = $cyclestart;

    db_query('DELETE FROM laundry_appointments WHERE cyclestart = :cyclestart AND timeslot = :timeslot', ['cyclestart' => $_POST['cyclestart'], 'timeslot' => $_POST['timeslot']]);

    unset($_POST['id']);

    $handler = new formHandler($table);
    $savekeys = ['cyclestart', 'timeslot', 'people_id', 'comment'];
    $id = $handler->savePost($savekeys);

    redirect('?action=laundry'.$data['day']);
}

$timeslot = intval($_GET['timeslot']);

$data = db_row('SELECT ls.id AS timeslot, ls.day, ls.time, la.people_id, la.comment, ls.machine, p.* FROM laundry_slots AS ls LEFT OUTER JOIN laundry_appointments AS la ON la.timeslot = ls.id AND cyclestart = :cyclestart LEFT OUTER JOIN people AS p ON p.id = la.people_id WHERE timeslot = :id', ['cyclestart' => $cyclestart, 'id' => $timeslot]);
if (!$data) {
    $data = db_row('SELECT ls.id AS timeslot, ls.day, ls.time, ls.machine FROM laundry_slots AS ls WHERE id = :id', ['id' => $timeslot]);
}

$data['station'] = db_value('SELECT station FROM laundry_machines WHERE id = :id', ['id' => $data['machine']]);
$data['access'] = db_value('SELECT access FROM laundry_stations WHERE id = :id', ['id' => $data['station']]);

// open the template
$cmsmain->assign('include', 'cms_form.tpl');

// put a title above the form
$cmsmain->assign('title', strftime('%A %d %B %Y', strtotime('+'.$data['day'].' days', strtotime($cyclestart))).'<br />'.db_value('SELECT label FROM laundry_times WHERE id = :time', ['time' => $data['time']]).' <span class="machine">'.db_value('SELECT label FROM laundry_machines WHERE id = :machine', ['machine' => $data['machine']]).'</span>');

$data['timeslot'] = $timeslot;
$data['day'] = $data['day'];
addfield('hidden', '', 'timeslot');
addfield('hidden', '', 'day');

$people = db_array('SELECT p.id AS value, laundryblock AS disabled, CONCAT(p.container, " ",p.firstname, " ", p.lastname,IF(laundryblock," - blocked, ask your supervisor","")) AS label FROM people AS p WHERE parent_id IS NULL AND NOT p.deleted AND camp_id = '.$_SESSION['camp']['id'].' AND '.$data['access'].' GROUP BY p.id ORDER BY SUBSTRING(REPLACE(container,"PK","Z"),1,1), SUBSTRING(REPLACE(container,"PK","Z"), 2, 10)*1');
array_unshift($people, ['value' => -1, 'label' => 'Drop Laundry', 'disabled' => 0]);
addfield('select', 'Find '.$_SESSION['camp']['familyidentifier'], 'people_id', ['onchange' => 'updateLaundry("people_id",'.$offset.')', 'multiple' => false, 'options' => $people]);

addfield('ajaxstart', '', '', ['id' => 'ajax-content']);
addfield('ajaxend');

addfield('textarea', 'Comments', 'comment', ['width' => 6]);

// place the form elements and data in the template
$cmsmain->assign('data', $data);
$cmsmain->assign('formelements', $formdata);
$cmsmain->assign('formbuttons', $formbuttons);
