<?php

// if barcode is specified then redirect to the right camps
$camp = db_value('SELECT c.id AS camp_id
	FROM qr AS q, stock AS s, locations AS l, camps AS c 
	WHERE q.code = :barcode AND q.legacy = :legacy AND q.id = s.qr_id AND l.id = s.location_id AND c.id = l.camp_id', ['barcode' => $_GET['barcode'], 'legacy' => (isset($_GET['qrlegacy']) ? 1 : 0)]);
if ($camp) {
    $box = db_row('SELECT s.id, s.box_id FROM stock AS s 
	LEFT OUTER JOIN qr AS q ON q.id = s.qr_id
	WHERE q.code = :barcode AND q.legacy = :legacy', ['barcode' => $_GET['barcode'], 'legacy' => (isset($_GET['qrlegacy']) ? 1 : 0)]);
    redirect('?camp='.$camp.'&boxid='.$box['box_id']);
}

$organisations = db_array('SELECT id, label FROM organisations ');

$tpl->assign('organisations', $organisations);
$tpl->assign('include', 'mobile_selectorganisation.tpl');
