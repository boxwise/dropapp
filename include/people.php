<?php

use OpenCensus\Trace\Tracer;

$table = $action;
    $ajax = checkajax();

    if (!$ajax) {
        if (!$_SESSION['camp']['id']) {
            throw new Exception('The list of beneficiaries is not available when there is no camp selected');
        }
        initlist();

        $cmsmain->assign('title', 'Beneficiaries');

        listsetting('allowcopy', false);
        listsetting('allowmove', true);
        listsetting('allowmoveto', 1);
        listsetting('allowsort', false);
        listsetting('allowshowhide', false);
        listsetting('search', ['firstname', 'lastname', 'container', 'comments']);
        listsetting('add', 'New person');
        listsetting('delete', 'Deactivate');

        listsetting('haspagemenu', true);
        addpagemenu('all', 'All', ['link' => '?action=people', 'active' => true]);
        addpagemenu('deactivated', 'Deactivated', ['link' => '?action=people_deactivated']);

        $tagfilter = ['id' => 'tagfilter', 'placeholder' => 'Select Tags', 'options' => db_array('SELECT id, id AS value, label, color FROM tags WHERE camp_id = :camp_id AND deleted IS NULL', ['camp_id' => $_SESSION['camp']['id']])];
        listsetting('multiplefilter', $tagfilter);
        $statusarray = ['week' => 'New this week', 'month' => 'New this month', 'expired' => 'Inactive', 'approvalsigned' => 'No signature', 'volunteer' => 'Volunteers', 'notregistered' => 'Not registered'];
        listfilter(['label' => 'Show all people', 'options' => $statusarray, 'filter' => '"show"']);

        listsetting('manualquery', true);

        $search = substr(db_escape(trim($listconfig['searchvalue'])), 1, strlen(db_escape(trim($listconfig['searchvalue']))) - 2);

        $data = getlistdata('
            SELECT 
                IF(DATEDIFF(NOW(),
                IF(people.parent_id,NULL,GREATEST(COALESCE((
                    SELECT 
                        transaction_date 
                    FROM 
                        transactions AS t 
                    WHERE 
                        t.people_id = people.id AND 
                        people.parent_id IS NULL AND 
                        product_id IS NOT NULL 
                    ORDER BY 
                        transaction_date DESC LIMIT 1),0),
                        COALESCE(people.modified,0),
                        COALESCE(people.created,0))
                )) > (
                    SELECT 
                        delete_inactive_users/2 
                    FROM 
                        camps 
                    WHERE 
                        id = '.$_SESSION['camp']['id'].'),1,NULL) AS expired,
                people.*, 
                IF(DATEDIFF(NOW(), people.date_of_birth)>730,CONCAT(TIMESTAMPDIFF(YEAR, people.date_of_birth, NOW()), " yrs"), CONCAT(TIMESTAMPDIFF(MONTH, people.date_of_birth, NOW()), IF(TIMESTAMPDIFF(MONTH, people.date_of_birth, NOW())>1," mos"," mo"))) AS age, 
                IF(people.gender="M","Male",IF(people.gender="F","Female","")) AS gender2, 
                IF(people.parent_id,"",(SUM(t2.drops)) AS drops,  
                IF(people.notregistered,"NR","") AS nr,
                people.comments,
                IF(people.parent_id,1,0) AS level,
                (
                    SELECT 
                        GROUP_CONCAT(tags.label) 
                    FROM 
                        tags 
                    LEFT JOIN 
                        people_tags ON people_tags.tag_id = tags.id 
                    WHERE 
                        people_tags.people_id = people.id 
                    GROUP BY 
                        people_tags.people_id) AS taglabels
            FROM 
                people 
            LEFT OUTER JOIN 
                transactions AS t2 ON t2.people_id = people.id 
            LEFT OUTER JOIN 
                people AS parent ON parent.id = people.parent_id
            WHERE 
                NOT people.deleted AND 
                '.('week' == $listconfig['filtervalue'] ? ' DATE_FORMAT(NOW(),"%v-%x") = DATE_FORMAT(people.created,"%v-%x") AND ' : '').
                ('month' == $listconfig['filtervalue'] ? ' DATE_FORMAT(NOW(),"%m-%Y") = DATE_FORMAT(people.created,"%m-%Y") AND ' : '').
                ('approvalsigned' == $listconfig['filtervalue'] ? ' ((NOT people.approvalsigned AND people.parent_id IS NULL) OR people.parent_id IN 
                    (SELECT peop.id
                    FROM people peop
                    WHERE peop.parent_id IS NULL AND NOT peop.approvalsigned)) AND ' : '').
                ('volunteer' == $listconfig['filtervalue'] ? ' people.volunteer AND ' : '').
                ('notregistered' == $listconfig['filtervalue'] ? ' people.notregistered AND ' : '').'
                people.camp_id = '.$_SESSION['camp']['id'].
                ($listconfig['searchvalue'] ? ' AND
                    (people.lastname LIKE "%'.$search.'%" OR 
                    people.firstname LIKE "%'.$search.'%" OR 
                    people.container = "'.$search.'" OR 
                    people.comments LIKE "%'.$search.'%" OR 
                        (SELECT 
                            COUNT(id)
                        FROM people AS p 
                        WHERE 
                            (p.lastname LIKE "%'.$search.'%" OR 
                            p.firstname LIKE "%'.$search.'%" OR 
                            p.container = "'.$search.'" OR 
                            p.comments LIKE "%'.$search.'%") AND 
                            p.parent_id = people.id AND NOT p.deleted AND p.camp_id = '.$_SESSION['camp']['id'].'
                        )
                    )
                ' : ' ')
            .'GROUP BY people.id '.
            ('expired' == $listconfig['filtervalue'] ? 'HAVING expired ' : '').
            'ORDER BY 
                -- sort by *parent* first & last name (or own first/last if no parent)
                IF(people.parent_id, parent.lastname, people.lastname), 
                IF(people.parent_id, parent.firstname, people.firstname),
                -- children should be grouped with their parents
                If(people.parent_id, parent.id, people.id),
                -- parents should appear before children
                IF(people.parent_id, 1, 0),
                -- children ordered by first name & last name too
                IF(people.parent_id, people.lastname, ""), 
                IF(people.parent_id, people.firstname, "")');

        $daysinactive = db_value('SELECT delete_inactive_users/2 FROM camps WHERE id = '.$_SESSION['camp']['id']);

        Tracer::inSpan(
            ['name' => ('people.php:addhtmldata')],
            function () use (&$data) {
                foreach ($data as $key => $value) {
                    if ($data[$key]['expired']) {
                        $data[$key]['expired'] = '<i class="fa fa-exclamation-triangle warning tooltip-this" title="This family hasn\'t been active for at least '.floor($daysinactive).' days."></i> ';
                    } else {
                        $data[$key]['expired'] = '';
                    }
                    if (0 == $data[$key]['parent_id'] && !$data[$key]['approvalsigned']) {
                        $data[$key]['expired'] .= '<i class="fa fa-edit warning tooltip-this" title="Please have the familyhead/beneficiary read and sign the approval form for storing and processing their data."></i> ';
                    }
                    if ($data[$key]['bicycletraining'] && $_SESSION['camp']['bicycle']) {
                        $data[$key]['expired'] .= '<i class="fa fa-bicycle tooltip-this" title="This person has a bicycle certificate."></i> ';
                    }
                    if ($data[$key]['workshoptraining'] && $_SESSION['camp']['workshop']) {
                        $data[$key]['expired'] .= '<i class="fa fa-wrench tooltip-this '.($data[$key]['workshopsupervisor'] ? 'blue' : '').'" title="This person has a workshop certificate."></i> ';
                    }

                    if (file_exists($settings['upload_dir'].'/people/'.$data[$key]['id'].'.jpg') && $_SESSION['camp']['idcard']) {
                        $data[$key]['expired'] .= '<i class="fa fa-id-card-o tooltip-this" title="This person has a picture."></i> ';
                    }
                    if ($data[$key]['volunteer']) {
                        $data[$key]['expired'] .= '<i class="fa fa-heart blue tooltip-this" title="This beneficiary is a volunteer."></i> ';
                    }
                    if ($data[$key]['notregistered']) {
                        $data[$key]['expired'] .= '<i class="fa fa-times blue tooltip-this" title="This beneficiary is not officially registered."></i> ';
                    }
                    if ($data[$key]['taglabels']) {
                        $data[$key]['taglabels'] = explode(',', $data[$key]['taglabels']);
                    }
                }
            }
        );

        Tracer::inSpan(
            ['name' => ('people.php:addtemplatedata')],
            function () use ($cmsmain) {
                global $listdata, $data, $listdata, $listconfig;
                addcolumn('text', 'Surname', 'lastname');
                addcolumn('text', 'Firstname', 'firstname');
                addcolumn('text', 'Gender', 'gender2');
                addcolumn('text', 'Age', 'age');
                //addcolumn('text','NR','nr');
                addcolumn('text', $_SESSION['camp']['familyidentifier'], 'container');
                addcolumn('text', ucwords($_SESSION['camp']['currencyname']), 'drops');
                addcolumn('tag', 'Tags', 'taglabels');
                addcolumn('text', 'Comments', 'comments');
                addcolumn('html', '&nbsp;', 'expired');
                if ($listconfig['filtervalue']) {
                    addcolumn('datetime', 'Created', 'created');
                }
                addbutton('export', 'Export', ['icon' => 'fa-download', 'showalways' => false, 'testid' => 'exportBeneficiariesButton']);
                $tags = db_simplearray('SELECT id, label FROM tags WHERE camp_id = :camp_id AND deleted IS NULL', ['camp_id' => $_SESSION['camp']['id']]);
                addbutton('tag', 'Add Tag', ['icon' => 'fa-tag', 'options' => $tags]);
                addbutton('give', 'Give '.ucwords($_SESSION['camp']['currencyname']), ['image' => 'one_coin.png', 'imageClass' => 'coinsImage', 'oneitemonly' => false, 'testid' => 'giveTokensListButton']);
                addbutton('merge', 'Merge to family', ['icon' => 'fa-link', 'oneitemonly' => false, 'testid' => 'mergeToFamily']);
                addbutton('detach', 'Detach from family', ['icon' => 'fa-unlink', 'oneitemonly' => false, 'testid' => 'detachFromFamily']);

                if ($_SESSION['camp']['bicycle']) {
                    $printoptions['bicycle'] = 'Bicycle card';
                }
                if ($_SESSION['camp']['bicycle']) {
                    $printoptions['workshop'] = 'Workshop card';
                }
                if ($_SESSION['camp']['idcard']) {
                    $printoptions['id'] = 'ID Card';
                }
                if (isset($printoptions)) {
                    addbutton('print', 'Print', ['icon' => 'fa-print', 'options' => $printoptions]);
                }

                addbutton('touch', 'Touch', ['icon' => 'fa-hand-pointer-o']);

                $cmsmain->assign('data', $data);
                $cmsmain->assign('listconfig', $listconfig);
                $cmsmain->assign('listdata', $listdata);
                $cmsmain->assign('include', 'cms_list.tpl');
            }
        );
    } else {
        $valid_ids = array_column(db_array('SELECT id from people as p where p.camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]), 'id');
        $ids = [];
        if ('move' == $_POST['do']) { // move passes the ids in pairs with the level the id is moved to. Therefore, it needs to be handled differently.
            foreach (json_decode($_POST['ids']) as $pair) {
                $ids[] = $pair[0];
            }
        } else {
            $ids = explode(',', $_POST['ids']);
        }
        $delta = array_diff($ids, $valid_ids);
        if (0 != count($delta)) {
            $message = 'You do not have access to this beneficiary record!';
            trigger_error($message, E_USER_ERROR);
            $success = false;
        } else {
            switch ($_POST['do']) {
            case 'merge':
                $ids = explode(',', $_POST['ids']);
                foreach ($ids as $key => $value) {
                    if (db_value('SELECT parent_id FROM people WHERE id = :id', ['id' => $value])) {
                        $containsmembers = true;
                    }
                }
                if ($containsmembers) {
                    $message = 'Please select only individuals or family heads to merge';
                    $success = false;
                } elseif (1 == count($ids)) {
                    $message = 'Please select more than one person to merge them into a family';
                    $success = false;
                } else {
                    $oldest = db_value('SELECT id FROM people WHERE id IN ('.$_POST['ids'].') ORDER BY date_of_birth ASC LIMIT 1');
                    $extradrops = db_value('SELECT SUM(drops) FROM transactions WHERE people_id IN ('.$_POST['ids'].') AND people_id != :oldest', ['oldest' => $oldest]);
                    foreach ($ids as $id) {
                        if ($id != $oldest) {
                            db_query('UPDATE people SET parent_id = :oldest WHERE id = :id', ['oldest' => $oldest, 'id' => $id]);
                            $drops = db_value('SELECT SUM(drops) FROM transactions WHERE people_id = :id', ['id' => $id]);
                            db_query('INSERT INTO transactions (people_id, drops, description, transaction_date, user_id) VALUES ('.$id.', -'.intval($drops).', "'.ucwords($_SESSION['camp']['currencyname']).' moved to new family head", NOW(), '.$_SESSION['user']['id'].')');
                        }
                    }
                    db_query('INSERT INTO transactions (people_id, drops, description, transaction_date, user_id) VALUES ('.$oldest.', '.intval($extradrops).', "'.ucwords($_SESSION['camp']['currencyname']).' moved from family member to family head", NOW(), '.$_SESSION['user']['id'].')');
                    $success = true;
                    $redirect = true;
                    correctchildren();
                }

                break;
            case 'detach':
                $ids = explode(',', $_POST['ids']);
                foreach ($ids as $key => $value) {
                    if (!db_value('SELECT parent_id FROM people WHERE id = :id', ['id' => $value])) {
                        $containsmembers = true;
                    }
                }
                if ($containsmembers) {
                    $message = 'Please select only members of a family, not family heads';
                    $success = false;
                } else {
                    foreach ($ids as $id) {
                        db_query('UPDATE people SET parent_id = NULL WHERE id = :id', ['id' => $id]);
                    }
                    $redirect = true;
                    $success = true;
                }

                break;
            case 'give':
                $ids = ($_POST['ids']);
                $success = true;
                $redirect = '?action=give&ids='.$ids;

                break;
            case 'move':
                $ids = json_decode($_POST['ids']);
                list($success, $message, $redirect, $aftermove) = listMove($table, $ids, true, 'correctdrops');

                break;
            case 'delete':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listDelete($table, $ids);

                break;
            case 'copy':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listCopy($table, $ids, 'name');

                break;
            case 'hide':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listShowHide($table, $ids, 0);

                break;
            case 'show':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listShowHide($table, $ids, 1);

                break;
            case 'touch':
                $ids = explode(',', $_POST['ids']);
                foreach ($ids as $id) {
                    db_query('UPDATE people SET modified = NOW(), modified_by = :user WHERE id = :id', ['id' => $id, 'user' => $_SESSION['user']['id']]);
                    simpleSaveChangeHistory('people', $id, 'Touched');
                }
                $success = true;
                $message = 'Selected people have been touched';
                $redirect = true;

                break;
            case 'print':
                $success = true;
                $redirect = '/pdf/'.$_POST['option'].'card.php?id='.$_POST['ids'];

                break;
            case 'export':
                $success = true;
                $_SESSION['export_ids_people'] = $_POST['ids'];
                $redirect = '?action=people_export';

                break;
            case 'tag':
                // set tag id
                $tag_id = $_POST['option'];
                $people_ids = $ids;

                foreach ($people_ids as $people_id) {
                    if (!db_numrows('SELECT * FROM people_tags WHERE tag_id=:tag_id AND people_id=:people_id', ['tag_id' => $tag_id, 'people_id' => $people_id])) {
                        db_query('INSERT INTO people_tags SET tag_id = :tag_id, people_id = :people_id', ['tag_id' => $tag_id, 'people_id' => $people_id]);
                    }
                }

                $success = true;
                $message = 'Tag added';
                $redirect = true;

                break;
            }
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect, 'action' => $aftermove];

        echo json_encode($return);
        die();
    }

    function correctchildren()
    {
        $result = db_query('SELECT (SELECT p2.parent_id FROM people AS p2 WHERE p2.id = p1.parent_id) AS newparent, p1.id FROM people AS p1 WHERE p1.parent_id > 0 AND (SELECT p2.parent_id FROM people AS p2 WHERE p2.id = p1.parent_id) AND NOT deleted');
        while ($row = db_fetch($result)) {
            db_query('UPDATE people SET parent_id = :newparent WHERE id = :id', ['newparent' => $row['newparent'], 'id' => $row['id']]);
        }
    }

    function correctdrops($id)
    {
        $drops = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id', ['id' => intval($id)]);
        $person = db_row('SELECT * FROM people AS p WHERE id = :id', ['id' => $id]);

        if ($drops && $person['parent_id']) {
            db_query('INSERT INTO transactions (people_id, drops, description, transaction_date, user_id) VALUES ('.$person['parent_id'].', '.$drops.', "'.ucwords($_SESSION['camp']['currencyname']).' moved from family member to family head", NOW(), '.$_SESSION['user']['id'].')');
            db_query('INSERT INTO transactions (people_id, drops, description, transaction_date, user_id) VALUES ('.$person['id'].', -'.$drops.', "'.ucwords($_SESSION['camp']['currencyname']).' moved to new family head", NOW(), '.$_SESSION['user']['id'].')');
            $newamount = db_value('SELECT SUM(drops) FROM transactions WHERE people_id = :id', ['id' => $person['parent_id']]);
            $aftermove = 'correctDrops({id:'.$person['id'].', value: ""}, {id:'.$person['parent_id'].', value: '.$newamount.'})';

            return $aftermove;
        }
    }
