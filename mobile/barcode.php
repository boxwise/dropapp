<?php

    $data['barcode'] = $_GET['barcode'];

    if ($_GET['barcode'] && !db_value('SELECT id FROM qr WHERE code = :code AND legacy = :legacy', ['code' => $_GET['barcode'], 'legacy' => (isset($_GET['qrlegacy']) ? 1 : 0)])) {
        $data['warning'] = true;
        $data['message'] = 'This is not a valid QR-code for '.$_SESSION['organisation']['label'];
        $data['barcode'] = '';
        // Check if it is a legacy error
        if (db_value('SELECT id FROM qr WHERE code = :code AND legacy = :legacy', ['code' => $_GET['barcode'], 'legacy' => (!isset($_GET['qrlegacy']) ? 1 : 0)])) {
            Sentry\captureMessage('Scanned QR-code exist in qr-table, but with different legacy value!');
        }
    } else {
        if ($_GET['boxid']) {
            $box = db_row('SELECT s.*, c.id AS camp_id, c.name AS campname, CONCAT(p.name," ",g.label," ",IFNULL(s2.label, "")) AS product, p.name AS product2, g.label AS gender, IFNULL(s2.label, "") AS size, l.label AS location FROM stock AS s
		LEFT OUTER JOIN products AS p ON p.id = s.product_id
		LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
		LEFT OUTER JOIN sizes AS s2 ON s2.id = s.size_id
		LEFT OUTER JOIN locations AS l ON l.id = s.location_id
		LEFT OUTER JOIN qr AS q ON q.id = s.qr_id
		LEFT OUTER JOIN camps AS c ON c.id = l.camp_id
		WHERE s.id = :id', ['id' => $_GET['boxid']]);
        } else {
            $qr_id = db_value('SELECT id FROM qr WHERE code = :code AND legacy = :legacy', ['code' => $_GET['barcode'], 'legacy' => (isset($_GET['qrlegacy']) ? 1 : 0)]);
            $box = db_row('SELECT s.*, c.id AS camp_id, c.name AS campname, CONCAT(p.name," ",g.label," ",IFNULL(s2.label, "")) AS product, p.name AS product2, g.label AS gender, IFNULL(s2.label, "") AS size, l.label AS location FROM stock AS s
		LEFT OUTER JOIN products AS p ON p.id = s.product_id
		LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
		LEFT OUTER JOIN sizes AS s2 ON s2.id = s.size_id
		LEFT OUTER JOIN locations AS l ON l.id = s.location_id
		LEFT OUTER JOIN qr AS q ON q.id = s.qr_id
		LEFT OUTER JOIN camps AS c ON c.id = l.camp_id
		WHERE q.id = :qrid', ['qrid' => $qr_id]);
        }
        if ('0000-00-00 00:00:00' != $box['deleted'] && !is_null($box['deleted'])) {
            $tpl->assign('include', 'mobile_editbox.tpl');
            redirect('?editbox='.$box['id']);
        } elseif ($box['camp_id'] != $_SESSION['camp']['id'] && $box['campname']) {
            $tpl->assign('include', 'mobile_newbox.tpl');
            redirect('?editbox='.$box['id'].'&warning=true&notificationFunction=editingBoxOfAnotherOrganisation&campName='.$box['campname'].'&currentCampName='.$_SESSION['camp']['name'].'.');
        } else {
            if ($box['id']) {
                $orders = db_value('SELECT COUNT(s.id) FROM stock AS s LEFT OUTER JOIN locations AS l ON s.location_id = l.id WHERE l.camp_id = :camp AND (NOT s.deleted OR s.deleted IS NULL) AND s.ordered', ['camp' => $_SESSION['camp']['id']]);
                $tpl->assign('orders', $orders);

                $locations = db_array('SELECT id AS value, label, IF(id = :location, true, false) AS selected FROM locations WHERE deleted IS NULL AND camp_id = :camp_id ORDER BY seq', ['camp_id' => $_SESSION['camp']['id'], 'location' => $box['location_id']]);
                $history = showHistory('stock', $box['id']);
                $tpl->assign('box', $box);
                $tpl->assign('history', $history);
                $tpl->assign('locations', $locations);
                $tpl->assign('include', 'mobile_scan.tpl');
            } else {
                redirect('?newbox='.$data['barcode'].(isset($_GET['qrlegacy']) ? '&qrlegacy=1' : ''));
            }
        }
    }
