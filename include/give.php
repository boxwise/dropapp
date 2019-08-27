<?php

    $table = 'transactions';
    $action = 'give';

    if ($_POST) {
        $people = explode(',', $_POST['people']);

        foreach ($people as $person) {
            $f = db_row('SELECT * FROM people WHERE id = :id', ['id' => $person]);
            if (0 == $f['parent_id']) {
                $children = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE NOT deleted AND parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$_SESSION['camp']['adult_age'], ['id' => $person]);
                $children += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE NOT deleted AND id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$_SESSION['camp']['adult_age'], ['id' => $person]);
                $adults = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE NOT deleted AND parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$_SESSION['camp']['adult_age'], ['id' => $person]);
                $adults += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE NOT deleted AND id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$_SESSION['camp']['adult_age'], ['id' => $person]);
                $drops = intval($_POST['dropsadult']) * $adults;
                $drops += intval($_POST['dropschild']) * $children;
                $drops += intval($_POST['dropsfamily']);

                if (isset($_POST['no_rollover']) && (1 == $_POST['no_rollover'])) {
                    $currentdrops = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :people_id', ['people_id' => $person]);
                    db_query('INSERT INTO transactions (people_id,description,drops,transaction_date,user_id) VALUES (:people_id,:description,:drops,NOW(),:user_id)', ['people_id' => $person, 'description' => 'Reset '.$_SESSION['camp']['currencyname'], 'drops' => ($currentdrops * -1), 'user_id' => $_SESSION['user']['id']]);
                }

                db_query('INSERT INTO transactions (people_id,description,drops,transaction_date,user_id) VALUES (:people_id,:description,:drops,NOW(),:user_id)', ['people_id' => $person, 'description' => $_POST['description'], 'drops' => $drops, 'user_id' => $_SESSION['user']['id']]);
            }
        }
        redirect('?action=people');
    }

    $data['people'] = $_GET['ids'];
    foreach (explode(',', $data['people']) as $familyhead) {
        $names[] = db_value('SELECT CONCAT(container, " ", firstname, " ", lastname) AS name FROM people WHERE id = :id', ['id' => $familyhead]);
    }
    $data['names'] = join(', ', $names);

    // open the template
    $cmsmain->assign('include', 'cms_form.tpl');

    // put a title above the form
    $cmsmain->assign('title', 'Give '.ucwords($_SESSION['camp']['currencyname']).' to selected families');

    addfield('hidden', 'people', 'people');

    addfield('text', 'Families', 'names', ['readonly' => true]);
    addfield('line', '', '');
    addfield('text', 'Give '.ucwords($_SESSION['camp']['currencyname']), 'dropsfamily');
    addfield('line', '', '');
    addfield('text', 'Give '.ucwords($_SESSION['camp']['currencyname']).' per adult', 'dropsadult');
    addfield('text', 'Give '.ucwords($_SESSION['camp']['currencyname']).' per child', 'dropschild');
    addfield('line', '', '');

    addfield('text', 'Comments', 'description');

    //addfield('checkbox','Zichtbaar','visible',array('aside'=>true));
    addfield('line', '', '', ['aside' => true]);
    addfield('created', 'Created', 'created', ['aside' => true]);

    // place the form elements and data in the template
    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
    $cmsmain->assign('formbuttons', $formbuttons);
