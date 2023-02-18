<?php

    $table = 'transactions';
    $action = 'give';

    if ($_POST) {
        $people = explode(',', $_POST['people']);

        /*
                if($_POST['startration']) {
                    db_query('INSERT INTO ration (startration) VALUES (NOW())');
                }
        */

        // Validate tokens input value to be a valid integer
        // Allowance for 0 is specifically for the "reset tokens" feature, as requested by Darbazar
        // Related to https://trello.com/c/Um44yV7y
        if (!preg_match('/[0-9]\d*/', $_POST['dropsadult']) || !preg_match('/[0-9]\d*/', $_POST['dropschild'])) {
            redirect('?action=give2all&warning=1&message=The number of tokens should be specified');
            trigger_error('The number of tokens should be specified', E_USER_NOTICE);
        }

        db_query('UPDATE camps SET 
			dropsperadult = :dropsperadult, 
			dropsperchild = :dropsperchild, 
			cyclestart = NOW()
		WHERE id = :camp', ['camp' => $_SESSION['camp']['id'],
            'dropsperadult' => $_POST['dropsadult'],
            'dropsperchild' => $_POST['dropschild'],
        ]);
        $_SESSION['camp'] = db_row('SELECT * FROM camps WHERE id = :camp', ['camp' => $_SESSION['camp']['id']]);

        foreach ($people as $person) {
            $f = db_row('SELECT * FROM people WHERE camp_id = :camp_id AND id = :id', ['id' => $person, 'camp_id' => $_SESSION['camp']['id']]);
            if (0 == $f['parent_id']) {
                $children = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE visible AND NOT deleted AND parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$_SESSION['camp']['adult_age'], ['id' => $person]);
                $children += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE visible AND NOT deleted AND id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$_SESSION['camp']['adult_age'], ['id' => $person]);
                $adults = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE visible AND NOT deleted AND parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$_SESSION['camp']['adult_age'], ['id' => $person]);
                $adults += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE visible AND NOT deleted AND id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$_SESSION['camp']['adult_age'], ['id' => $person]);
                $drops = intval($_POST['dropsadult']) * $adults;
                $drops += intval($_POST['dropschild']) * $children;

                $volunteers = db_value('SELECT COUNT(id) FROM people WHERE visible AND NOT deleted AND (id = :id OR parent_id = :id) AND volunteer', ['id' => $person]);

                $currentdrops = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :people_id', ['people_id' => $person]);

                if ($_SESSION['camp']['resettokens']) {
                    db_query('INSERT INTO transactions (people_id,description,drops,transaction_date,user_id) VALUES (:people_id,:description,:drops,NOW(),:user_id)', ['people_id' => $person, 'description' => 'Reset of remaining tokens (cycle description - '.$_POST['description'].')', 'drops' => (-1) * $currentdrops, 'user_id' => $_SESSION['user']['id']]);
                    $currentdrops = 0;
                }

                if (!$volunteers) {
                    $max = $adults * $_SESSION['camp']['dropcapadult'] + $children * $_SESSION['camp']['dropcapchild'];
                    $cap = -($currentdrops + $drops) + $max;
                    if ($cap < 0) {
                        $drops += $cap;
                        $_POST['description'] .= ' (capped to maximum)';
                    }
                }

                db_query('INSERT INTO transactions (people_id,description,drops,transaction_date,user_id) VALUES (:people_id,:description,:drops,NOW(),:user_id)', ['people_id' => $person, 'description' => $_POST['description'], 'drops' => $drops, 'user_id' => $_SESSION['user']['id']]);
            }
        }

        redirect('?action=people');
    }

    $result = db_query('SELECT * FROM people WHERE camp_id = :camp_id AND visible AND parent_id IS NULL AND NOT deleted', ['camp_id' => $_SESSION['camp']['id']]);
    while ($row = db_fetch($result)) {
        $ids[] = $row['id'];
    }
    $data['people'] = join(',', $ids);

    $data['names'] = 'All families';
    $data['description'] = 'New cycle started '.strftime('%A %e %B %Y');
    $translate['cms_form_submit'] = 'Give '.ucwords($_SESSION['camp']['currencyname']);
    $cmsmain->assign('translate', $translate);

    // open the template
    $cmsmain->assign('include', 'cms_form.tpl');

    // put a title above the form
    $cmsmain->assign('title', 'Give '.ucwords($_SESSION['camp']['currencyname']).' to all families');

    addfield('hidden', 'people', 'people');

    addfield('custom', '', '<div class="noprint tipofday"><h3>👨‍🏫 Be careful</h3><p>If you press the "Give '.ucwords($_SESSION['camp']['currencyname']).'" button on the right, you can\'t turn back anymore!</p></div>');

    addfield('text', 'Families', 'names', ['readonly' => true]);
    addfield('line', '', '');
    $data['hidecancel'] = true;
    $data['dropsadult'] = $_SESSION['camp']['dropsperadult'];
    $data['dropschild'] = $_SESSION['camp']['dropsperchild'];

    addfield('text', 'Give '.ucwords($_SESSION['camp']['currencyname']).' per adult', 'dropsadult', ['required' => true]);
    addfield('text', 'Give '.ucwords($_SESSION['camp']['currencyname']).' per child', 'dropschild', ['required' => true]);
    // 	$data['startration'] = 1;
    // 	addfield('checkbox','Reset ration period','startration');
    addfield('line', '', '');
    addfield('text', 'Description', 'description');

    // addfield('checkbox','Zichtbaar','visible',array('aside'=>true));
    // addfield('line','','',array('aside'=>true));
    // addfield('created','Created','created',array('aside'=>true));

    // place the form elements and data in the template
    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
    $cmsmain->assign('formbuttons', $formbuttons);
