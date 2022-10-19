<?php

    $table = 'qr';
    $action = 'qr';

    if ($_POST) {
        if ($_POST['count'] > 500) {
            throw new Exception('Please enter a value less than or equal to 500.');
        }

        $data['fulllabel'] = $_POST['fulllabel'];

        if ($_POST['fulllabel']) {
            $labels = explode(',', $_POST['label']);
            redirect('/pdf/qr.php?count='.$_POST['count']);
        } elseif ($_POST['label']) {
            $i = 0;
            $labels = explode(',', $_POST['label']);
            foreach ($labels as $l) {
                $data['labels'][$i] = db_row('
				SELECT s.box_id, qr.code AS hash, qr.legacy AS legacy, CONCAT(p.name," (",s.items,"x)") AS product, g.shortlabel AS gender, s2.label AS size
				FROM stock AS s
				LEFT OUTER JOIN products AS p ON p.id = s.product_id
				LEFT OUTER JOIN genders AS g ON g.id = p.gender_id
				LEFT OUTER JOIN sizes AS s2 ON s2.id = s.size_id
				LEFT OUTER JOIN qr ON s.qr_id = qr.id
				WHERE s.id = :id', ['id' => $l]);
                if ($data['labels'][$i]['legacy']) {
                    list($id, $data['labels'][$i]['hash']) = generateQRIDForDB();
                    db_query('UPDATE stock AS s SET qr_id = :qr_id, modified = NOW() WHERE id = :id', ['id' => $l, 'qr_id' => $id]);
                    simpleBulkSaveChangeHistory('qr', $id, 'New QR-code generated for existing box without QR-code');
                }
                list($data['labels'][$i]['qrPng'], $data['labels'][$i]['data-testurl']) = generateQrPng($data['labels'][$i]['hash']);
                ++$i;
            }
        } else {
            for ($i = 0; $i < $_POST['count']; ++$i) {
                list($id, $data['labels'][$i]['hash']) = generateQRIDForDB();
                list($data['labels'][$i]['qrPng'], $data['labels'][$i]['data-testurl']) = generateQrPng($data['labels'][$i]['hash']);
                simpleBulkSaveChangeHistory('qr', $id, 'New QR-code generated');
            }
        }

        $cmsmain->assign('include', 'boxlabels.tpl');

        $cmsmain->assign('data', $data);
    } else {
        // open the template
        $cmsmain->assign('include', 'cms_form.tpl');
        $cmsmain->assign('title', 'Print Box Labels');
        addfield('html', '', '<p>To register a new box to the system, scan the QR code on the label and follow the instructions from the mobile site.</p>');

        $data['count'] = 1;
        $data['fulllabel'] = 1;
        $data['hidecancel'] = true;

        $translate['cms_form_submit'] = 'Print label(s)';
        $cmsmain->assign('translate', $translate);

        if ($_GET['label']) {
            $data['label'] = $_GET['label'];
            addfield('hidden', '', 'count');
            addfield('hidden', '', 'label');
        } else {
            $data['count'] = 2;
            addfield('number', 'How many box labels do you need?', 'count', ['min' => 0, 'max' => 500, 'testid' => 'numberOfLabelsInput']);
        }
        addfield('checkbox', 'Make big labels including fields for box number and contents', 'fulllabel', ['testid' => 'field_fulllabel']);

        // place the form elements and data in the template
        $cmsmain->assign('data', $data);
        $cmsmain->assign('formelements', $formdata);
        $cmsmain->assign('formbuttons', $formbuttons);
    }
