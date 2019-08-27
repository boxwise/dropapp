<?php

// if barcode is specified then redirect to the right camps
$camp = db_value('SELECT c.id AS camp_id
	FROM qr AS q, stock AS s, locations AS l, camps AS c 
	WHERE q.code = :barcode AND q.id = s.qr_id AND l.id = s.location_id AND c.id = l.camp_id', ['barcode' => $_GET['barcode']]);
if ($camp) {
    redirect('?camp='.$camp.'&barcode='.$_GET['barcode']);
}

$organisations = db_array('SELECT id, label FROM organisations ');

$tpl->assign('organisations', $organisations);
$tpl->assign('include', 'mobile_selectorganisation.tpl');
