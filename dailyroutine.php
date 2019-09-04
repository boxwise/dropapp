<?php
    // This file is called about one time daily

    //to debug through the browser
    require_once 'library/core.php';

    // this function caps the maximum of drops

// This function doesn't run properly because it requires adult_age from a camp setting, it is not yet available
// Rework: do this for all the camps at once and use the setting per camp.

/*
    $result = db_query('SELECT
    p.*, SUM(t.drops) AS drops,
    IF((SELECT COUNT(id) FROM people WHERE volunteer AND (id = p.id OR parent_id = p.id)),99999,dropcapadult * (SELECT COUNT(id) FROM people WHERE (id = p.id OR parent_id = p.id) AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),date_of_birth)),"%Y")+0 >= '.$_SESSION['camp']['adult_age'].') +
    dropcapchild * (SELECT COUNT(id) FROM people WHERE (id = p.id OR parent_id = p.id) AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),date_of_birth)),"%Y")+0 < '.$_SESSION['camp']['adult_age'].')) AS maxdrops
FROM
    people AS p,
    transactions AS t,
    camps AS c
WHERE
    t.people_id = p.id AND
    NOT p.deleted AND
    c.id = p.camp_id AND
    p.parent_id = 0
GROUP BY p.id
    ');

    while($row = db_fetch($result)) {
        if($row['drops']>$row['maxdrops']) {
            db_query('INSERT INTO transactions (people_id, description, drops, transaction_date) VALUES (:id, "Drops capped to maximum", :drops, NOW())',array('id'=>$row['id'],'drops'=>-$row['drops']+$row['maxdrops']));
        }
    }
*/

    // this function sorts the people list on container/household id, giving the best possible overview
    $result = db_query('SELECT id, parent_id, people.container FROM people WHERE NOT deleted AND parent_id = 0 ORDER BY camp_id, IF(LEFT(container,2)="PK" OR LEFT(container,1)="T",LEFT(container,2),LEFT(container,1)), IF(LEFT(container,2)="PK" OR LEFT(container,1)="T",SUBSTRING(container,3),SUBSTRING(container,2))*1
');

    while ($row = db_fetch($result)) {
        ++$i;
        db_query('UPDATE people SET seq = '.$i.' WHERE id = '.$row['id']);

        $j = 0;

        $result2 = db_query('SELECT id FROM people WHERE NOT deleted AND parent_id = '.$row['id'].' ORDER BY DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 DESC');
        while ($row2 = db_fetch($result2)) {
            ++$j;
            db_query('UPDATE people SET seq = '.$j.' WHERE id = '.$row2['id']);
        }
    }

    // people that have not been active for a longer time will be deleted(Changed to deactivated in visible text, variables remain under the name deleted, as does the databasse)
    // the amount of days of inactivity is set in the camp table
    $result = db_query('SELECT p.id, p.lastname, p.created, p.modified, c.delete_inactive_users AS treshold FROM people AS p LEFT OUTER JOIN camps AS c ON c.id = p.camp_id WHERE NOT p.deleted AND p.parent_id = 0');
    while ($row = db_fetch($result)) {
        /*
        if(strtotime($row['lastfoodtransaction'])>strtotime($row['lastransaction'])) {$row['lastransaction'] = $row['lastfoodtransaction'];}
        var_export($row['lastfoodtransaction']);
        $row['touch'] = (strtotime($row['lasttransaction'])>strtotime($row['modified'])?$row['lasttransaction']:$row['modified']);
        if(!$row['touch']) $row['touch'] = $row['created'];
         */
        $row['touch'] = db_value('SELECT GREATEST(COALESCE((SELECT transaction_date 
					FROM transactions AS t 
					WHERE t.people_id = people.id AND people.parent_id = 0 AND product_id != 0 
					ORDER BY transaction_date DESC LIMIT 1),0), 
					COALESCE(people.modified,0),COALESCE(people.created,0))
				FROM people
				WHERE id = :id', ['id' => $row['id']]);

        if ($row['touch']) {
            $date1 = new DateTime($row['touch']);
            $date2 = new DateTime();
            $row['diff'] = $date2->diff($date1)->format('%a');

            if ($row['diff'] > $row['treshold']) {
                db_query('UPDATE people SET deleted = NOW() WHERE id = :id', ['id' => $row['id']]);
                simple('people', $row['id'], 'Record deleted by daily routine');
                db_touch('people', $row['id']);
            }
        }
    }

    // cleaning up the database in case of errors
    // delete children without a parent
    db_query('UPDATE people p1 LEFT JOIN people p2 ON p1.parent_id = p2.id SET p1.deleted=NOW() WHERE p2.ID IS NULL AND p1.parent_id != 0');
    // family members of deleted people should also be deleted

    $result = db_query('SELECT p2.id FROM people AS p1, people AS p2 WHERE p2.parent_id = p1.id AND p1.deleted AND NOT p2.deleted');
    while ($row = db_fetch($result)) {
        db_query('UPDATE people SET deleted = NOW() WHERE id = :id', ['id' => $row['id']]);
        simpleSaveChangeHistory('people', $row['id'], 'Record deleted by daily routine because head of family/beneficiary was deleted');
        db_touch('people', $row['id']);
    }

    // delete children with a deleted parent
    db_query('UPDATE people p1 LEFT JOIN people p2 ON p1.parent_id = p2.id SET p1.deleted = NOW() WHERE p2.deleted AND !p1.deleted AND p1.parent_id != 0');

    // this notifies us when a new installation of the Drop App is made
    if (!isset($settings['installed'])) {
        foreach ($_SERVER as $key => $value) {
            $mail .= $key.' -> '.$value.'<br />';
        }
        $result = sendmail('hans@boxwise.co', 'hans@boxwise.co', 'New installation of Boxwise', $mail);
        db_query('INSERT INTO settings (category_id, type, code, description_en, value) VALUES (1,"text","installed","Date and time of installation and first run",NOW())');
    }

    header('/');
