<?php

if ($_POST) {
    $_SESSION['camp']['laundry_cyclestart'] = $_POST['cyclestart'];

    db_query('UPDATE camps SET laundry_cyclestart = :cyclestart WHERE id  = :camp_id', ['cyclestart' => (new DateTime($_POST['cyclestart']))->format('Y-m-d'), 'camp_id' => $_SESSION['camp']['id']]);

    redirect('?action=laundry');
}

$data['cyclestart'] = date('Y-m-d', strtotime('+14 days', strtotime((string) $_SESSION['camp']['laundry_cyclestart'])));

// open the template
$cmsmain->assign('include', 'cms_form.tpl');

// put a title above the form
$cmsmain->assign('title', 'Start new cycle');

addfield('date', 'New cycle starts on', 'cyclestart', ['date' => true, 'time' => false]);

// place the form elements and data in the template
$cmsmain->assign('data', $data);
$cmsmain->assign('formelements', $formdata);
$cmsmain->assign('formbuttons', $formbuttons);
