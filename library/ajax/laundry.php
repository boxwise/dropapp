<?php

    $ajaxform = new Zmarty();

    $data['people_id'] = intval($_POST['people_id']);
    $offset = intval($_POST['offset']);
    $cyclestart = strftime('%Y-%m-%d', strtotime('+'.$offset.' days', strtotime($_SESSION['camp']['laundry_cyclestart'])));

    if (-1 == $data['people_id']) {
        $element['field'] .= '<h2 class="light"><span class="number">Laundry for the Drop Shop</span></h2>';
    } else {
        $adults = db_value('SELECT SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < 13, 0, 1)) AS adults FROM people WHERE id = :id OR parent_id = :id AND NOT deleted ', ['id' => $data['people_id']]);
        $children = db_value('SELECT SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < 13, 1, 0)) AS adults FROM people WHERE id = :id OR parent_id = :id AND NOT deleted ', ['id' => $data['people_id']]);

        $element['field'] .= '<h2 class="light">This family/beneficiary has <span class="number">'.multiple($adults, 'adult', 'adults').'</span> and <span class="number">'.multiple($children, 'child', 'children').'</span> and is entitled to <span class="number">'.multiple(ceil((1 * $adults) + (0.5 * $children)), 'washing machine slot', 'washing machine slots').'</span> every cycle.</h2>';
    }

    $data['approvalsigned'] = db_value('SELECT approvalsigned FROM people WHERE id = :id', ['id' => $data['people_id']]);
    if (!$data['approvalsigned']) {
        $element['field'] .= "<br /><div class='warningbox2'>Please have the familyhead/beneficiary read and sign the approval form for storing and processing their data.</div><br />";
    }

    $result = db_query('SELECT ls.*, lt.label, lm.label AS machine, la.noshow FROM laundry_appointments AS la, laundry_slots AS ls, laundry_times AS lt, laundry_machines AS lm WHERE lm.id = ls.machine AND lt.id = ls.time AND la.timeslot = ls.id AND la.people_id = :people_id AND la.cyclestart = :cyclestart ORDER BY timeslot', ['people_id' => $data['people_id'], 'cyclestart' => $cyclestart]);
    //	if(db_numrows($result)) $element['field'] .= '<h2>Current appointments in this cycle';
    while ($row = db_fetch($result)) {
        $app[] = ($row['id'] == $_POST['timeslot'] ? '<span class="number">' : '').strftime('%A %d %B %Y', strtotime('+'.$row['day'].' days', strtotime($cyclestart))).', '.$row['label'].' <span class="machine">'.$row['machine'].'</span>'.($row['id'] == $_POST['timeslot'] ? ' (this one)</span>' : '').($row['noshow'] ? ' NO-SHOW' : '');
    }

    if (is_array($app)) {
        $element['field'] .= '<br /><h2 class="light">Current appointments in this cycle:<br />'.join($app, '<br />');
    }

    $ajaxform->assign('element', $element);
    $htmlcontent = $ajaxform->fetch('cms_form_custom.tpl');

    $success = true;
    $return = ['success' => $success, 'htmlcontent' => $htmlcontent, 'message' => $message];
    echo json_encode($return);

    function multiple($i, $single, $plural)
    {
        if (0 == $i) {
            return 'no '.$plural;
        }
        if (1 == $i) {
            return 'one '.$single;
        }

        return $i.' '.$plural;
    }
