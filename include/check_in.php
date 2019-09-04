<?php

    $ajax = checkajax();

    $table = 'transactions';
    $action = 'check_in';

    if (!$ajax) {
        $data = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $id]);
        if (!$id) {
            $data['visible'] = 1;
            $data['count'] = 1;
            $data['people_id'] = intval($_GET['people_id']);
        }

        verify_campaccess_people($data['people_id']);
        verify_deletedrecord('people', $data['people_id']);

        $translate['cms_form_submit'] = 'Edit';
        $cmsmain->assign('translate', $translate);

        // open the template
        $cmsmain->assign('include', 'cms_form.tpl');
        addfield('hidden', '', 'id');

        $data['hidesubmit'] = true;
        // put a title above the form
        $cmsmain->assign('title', 'Check In');

        addfield('select', 'Find '.$_SESSION['camp']['familyidentifier'], 'people_id', ['onchange' => 'selectFamily("people_id",false,"check_in")', 'required' => true, 'multiple' => false, 'query' => '
			SELECT p.id AS value, CONCAT(p.container, " ",p.firstname, " ", p.lastname) AS label, NOT visible AS disabled 
			FROM people AS p 
			WHERE parent_id = 0 AND (NOT p.deleted OR p.deleted IS NULL) AND camp_id = '.$_SESSION['camp']['id'].' 
			GROUP BY p.id 
			ORDER BY label']);

        addfield('ajaxstart', '', '', ['aside' => true, 'asidetop' => true, 'id' => 'ajax-aside']);
        addfield('ajaxend', '', '', ['aside' => true]);

        //addfield('created','Created','created',array('aside'=>true));

        // place the form elements and data in the template
        $cmsmain->assign('data', $data);
        $cmsmain->assign('formelements', $formdata);
        $cmsmain->assign('formbuttons', $formbuttons);
    } else {
        $ajaxform = new Zmarty();

        // vanaf hier

        $data['people_id'] = intval($_POST['people_id']);
        $data['allowdrops'] = allowGiveDrops();

        $table = 'transactions';

        $ajaxform->assign('data', $data);
        $ajaxform->assign('formelements', $formdata);
        $ajaxform->assign('formbuttons', $formbuttons);
        $htmlcontent = $ajaxform->fetch('cms_form_ajax.tpl');

        // the aside
        $ajaxaside = new Zmarty();

        $data['people'] = db_array('SELECT *, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age FROM people WHERE id = :id OR parent_id = :id AND visible AND NOT deleted ORDER BY parent_id, seq', ['id' => $data['people_id']]);

        $data['dropcoins'] = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id', ['id' => $data['people_id']]);
        $data['givedropsurl'] = '?action=give&ids='.$data['people_id'];
        $data['approvalsigned'] = db_value('SELECT approvalsigned FROM people WHERE id = :id', ['id' => $data['people_id']]);
        $data['lasttransaction'] = displaydate(db_value('SELECT transaction_date FROM transactions WHERE product_id > 0 AND people_id = :id ORDER BY transaction_date DESC LIMIT 1', ['id' => $data['people_id']]), true);

        $ajaxaside->assign('data', $data);
        $ajaxaside->assign('currency', $_SESSION['camp']['currencyname']);
        $htmlaside = $ajaxaside->fetch('info_aside_purchase.tpl');

        $success = true;
        $return = ['success' => $success, 'htmlcontent' => $htmlcontent, 'htmlaside' => $htmlaside, 'message' => $message];
        echo json_encode($return);
    }
