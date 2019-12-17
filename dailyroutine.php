<?php

    require_once 'library/core.php';
    // This file is called about one time daily

    // people that have not been active for a longer time will be deleted(Changed to deactivated in visible text, variables remain under the name deleted, as does the databasse)
    // the amount of days of inactivity is set in the camp table
    $result = db_query('
        SELECT p.id, p.lastname, p.created, p.modified, c.delete_inactive_users AS treshold 
        FROM people AS p 
        LEFT OUTER JOIN camps AS c ON c.id = p.camp_id 
        WHERE (NOT p.deleted OR p.deleted IS NULL) AND p.parent_id IS NULL');
    while ($row = db_fetch($result)) {
        $row['touch'] = db_value('
            SELECT GREATEST(COALESCE((
                SELECT transaction_date 
				FROM transactions AS t 
				WHERE t.people_id = people.id AND people.parent_id IS NULL AND product_id IS NOT NULL 
                ORDER BY transaction_date DESC LIMIT 1
            ),0),
            COALESCE((
                SELECT transaction_date 
				FROM library_transactions AS t 
				WHERE t.people_id = people.id AND people.parent_id IS NULL  
                ORDER BY transaction_date DESC LIMIT 1
            ),0),
            COALESCE(people.modified,0),COALESCE(people.created,0))
			FROM people
			WHERE id = :id', ['id' => $row['id']]);
        if ($row['touch']) {
            $date1 = new DateTime($row['touch']);
            $date2 = new DateTime();
            $row['diff'] = $date2->diff($date1)->format('%a');

            if ($row['diff'] > $row['treshold']) {
                db_query('UPDATE people SET deleted = NOW() WHERE id = :id', ['id' => $row['id']]);
                simpleSaveChangeHistory('people', $row['id'], 'Record deleted by daily routine');
                db_touch('people', $row['id']);
            }
        }
    }

    // family members of deleted parents should also be deleted
    $result = db_query('
        SELECT p2.id 
        FROM people AS p1, people AS p2 
        WHERE p2.parent_id = p1.id AND p1.deleted AND (NOT p2.deleted OR p2.deleted IS NULL)');
    while ($row = db_fetch($result)) {
        db_query('UPDATE people SET deleted = NOW() WHERE id = :id', ['id' => $row['id']]);
        simpleSaveChangeHistory('people', $row['id'], 'Record deleted by daily routine because head of family/beneficiary was deleted');
        db_touch('people', $row['id']);
    }

    // this notifies us when a new installation of the Drop App is made
    if (!isset($settings['installed'])) {
        foreach ($_SERVER as $key => $value) {
            $mail .= $key.' -> '.$value.'<br />';
        }
        $result = sendmail('hans@boxwise.co', 'hans@boxwise.co', 'New installation of Boxwise', $mail);
        db_query('INSERT INTO settings (category_id, type, code, description_en, value) VALUES (1,"text","installed","Date and time of installation and first run",NOW())');
    }

    header('/');
