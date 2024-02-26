<?php

if ($_POST) {
    $_SESSION['camp']['laundry_cyclestart'] = $_POST['cyclestart'];

    db_query('UPDATE camps SET laundry_cyclestart = :cyclestart WHERE id  = :camp_id', ['cyclestart' => strftime('%Y-%m-%d', strtotime($_POST['cyclestart'])), 'camp_id' => $_SESSION['camp']['id']]);

    redirect('?action=laundry');
}

$data['cyclestart'] = strftime('%Y-%m-%d', strtotime('+14 days', strtotime($_SESSION['camp']['laundry_cyclestart'])));

// open the template
$cmsmain->assign('include', 'cms_form.tpl');

// put a title above the form
$cmsmain->assign('title', 'Start new cycle');

addfield('date', 'New cycle starts on', 'cyclestart', ['date' => true, 'time' => false]);

// place the form elements and data in the template
$cmsmain->assign('data', $data);
$cmsmain->assign('formelements', $formdata);
$cmsmain->assign('formbuttons', $formbuttons);
