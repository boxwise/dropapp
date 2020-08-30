<?php

    $table = 'people';
    $action = 'people_edit';

    if ($_POST) {
        // delete a transaction of a person
        if ('delete' == $_POST['do']) {
            $ids = explode(',', $_POST['ids']);
            list($success, $message, $redirect) = listDelete('transactions', $ids);

            $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect, 'action' => $aftermove];

            echo json_encode($return);
            die();
        }

        // save the People edit form
        if ($_POST['id']) {
            $oldcontainer = db_value('SELECT container FROM people WHERE id = :id', ['id' => $_POST['id']]);

            verify_campaccess_people($_POST['id']);
            verify_deletedrecord($table, $_POST['id']);
        }

        $handler = new formHandler($table);
        $handler->makeURL('fullname');
        $savekeys = ['parent_id', 'firstname', 'lastname', 'gender', 'container', 'date_of_birth', 'email', 'extraportion', 'comments', 'camp_id', 'bicycletraining', 'phone', 'notregistered', 'bicycleban', 'workshoptraining', 'workshopban', 'workshopsupervisor', 'bicyclebancomment', 'workshopbancomment', 'volunteer', 'approvalsigned', 'signaturefield', 'date_of_signature'];
        if ($_SESSION['usergroup']['allow_laundry_block'] || $_SESSION['user']['is_admin']) {
            $savekeys[] = 'laundryblock';
            $savekeys[] = 'laundrycomment';
        }
        $id = $handler->savePost($savekeys, ['parent_id']);
        $handler->saveMultiple('tags', 'people_tags', 'people_id', 'tag_id');
        $handler->saveMultiple('languages', 'x_people_languages', 'people_id', 'language_id');

        $postid = ($_POST['id'] ? $_POST['id'] : $id);
        if (is_uploaded_file($_FILES['picture']['tmp_name'])) {
            if ('image/jpeg' == $_FILES['picture']['type']) {
                $targetFile = $settings['upload_dir'].'/people/'.$postid.'.jpg';
                $res = move_uploaded_file($_FILES['picture']['tmp_name'], $targetFile);
                if (!$res) {
                    error_log("Could not save uploaded file to {$targetFile}");
                }
            } else {
                error_log('Skipped uploaded file of type '.$_FILES['picture']['type']);
            }
        }
        if ($_POST['picture_delete']) {
            unlink($settings['upload_dir'].'/people/'.$postid.'.jpg');
        }

        $message = $_POST['firstname'].($_POST['firstname'] ? ' '.$_POST['lastname'] : '').' was added.<br>'.$_SESSION['camp']['familyidentifier'].' is '.$_POST['container'].'.';

        // routing after submit
        if ('submitandedit' == $_POST['__action']) {
            redirect('?action='.$action.'&origin='.$_POST['_origin'].'&id='.$handler->id);
        } elseif ('submitandnew' == $_POST['__action']) {
            redirect('?action='.$action.'&origin='.$_POST['_origin'].'&message='.$message);
        } elseif ('' == $_POST['__action'] && '' == $_POST['_origin']) {
            // routing after adding beneficiary from menu option
            redirect('?action=people&message='.$message);
        } else {
            redirect('?action='.$_POST['_origin'].($_POST['id'] ? '' : '&message='.$message));
        }
    }

    $data = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $id]);
    verify_campaccess_people($id);
    verify_deletedrecord($table, $id);

    if (!$id) {
        $data['visible'] = 1;
        $data['camp_id'] = $_SESSION['camp']['id'];
    }

    $cmsmain->assign('include', 'cms_form.tpl');
    if ($data['firstname'] || $data['lastname']) {
        $cmsmain->assign('title', $data['firstname'].' '.$data['lastname']);
    } else {
        $cmsmain->assign('title', 'Add a new beneficiary');
    }
    $data['allowdrops'] = allowGiveDrops();

    if ($id) {
        $sideid = ($data['parent_id'] ? $data['parent_id'] : $id);
        $side['people_id'] = $id;
        $side['parent_id'] = $data['parent_id'];
        $ajaxaside = new Zmarty();

        $side['approvalsigned'] = $data['approvalsigned'];
        $side['date_of_signature'] = $data['date_of_signature'];
        $side['allowdrops'] = allowGiveDrops();

        $side['name'] = db_row('SELECT *, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age FROM people WHERE id = '.$sideid);

        $side['children'] = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$_SESSION['camp']['adult_age'].' AND visible AND NOT deleted', ['id' => $sideid]);
        $side['children'] += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$_SESSION['camp']['adult_age'].' AND visible AND NOT deleted', ['id' => $sideid]);
        $side['adults'] = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$_SESSION['camp']['adult_age'].' AND visible AND NOT deleted', ['id' => $sideid]);
        $side['adults'] += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$_SESSION['camp']['adult_age'].' AND visible AND NOT deleted', ['id' => $sideid]);

        $side['people'] = db_array('
            SELECT 
                people.*, 
                DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), people.date_of_birth)), "%Y")+0 AS age, 
                GROUP_CONCAT(tags.label) AS taglabels,
                GROUP_CONCAT(tags.color) AS tagcolors
            FROM 
                people 
            LEFT JOIN
                people_tags ON people_tags.people_id = people.id
            LEFT JOIN
                tags ON tags.id = people_tags.tag_id 
            WHERE 
                (people.parent_id = :id OR people.id = :id) AND 
                NOT people.deleted 
            GROUP BY
                people.id
            ORDER BY 
                people.parent_id, people.seq', ['id' => $sideid]);
        foreach ($side['people'] as $key => $person) {
            if ($side['people'][$key]['taglabels']) {
                $taglabels = explode(',', $side['people'][$key]['taglabels']);
                $tagcolors = explode(',', $side['people'][$key]['tagcolors']);
                foreach ($taglabels as $tagkey => $taglabel) {
                    $side['people'][$key]['tags'][$tagkey] = ['label' => $taglabel, 'color' => $tagcolors[$tagkey], 'textcolor' => get_text_color($tagcolors[$tagkey])];
                }
            }
        }

        $adults = $_SESSION['camp']['maxfooddrops_adult'] * db_value('SELECT SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < 13, 0, 1)) AS adults FROM people WHERE id = :id OR parent_id = :id AND NOT deleted ', ['id' => $sideid]);
        $children = $_SESSION['camp']['maxfooddrops_child'] * db_value('SELECT SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < 13, 1, 0)) AS adults FROM people WHERE id = :id OR parent_id = :id AND NOT deleted ', ['id' => $sideid]);

        $adults = $_SESSION['camp']['maxfooddrops_adult'] * db_value('SELECT SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < 13, 0, 1)) AS adults FROM people WHERE id = :id OR parent_id = :id AND NOT deleted ', ['id' => $sideid]);
        $children = $_SESSION['camp']['maxfooddrops_child'] * db_value('SELECT SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < 13, 1, 0)) AS adults FROM people WHERE id = :id OR parent_id = :id AND NOT deleted ', ['id' => $sideid]);

        $side['fooddrops'] = intval($adults) + intval($children);
        $side['foodspent'] = db_value('SELECT SUM(drops) FROM transactions AS t, products AS p WHERE t.product_id = p.id AND p.category_id = 11 AND t.people_id = :id AND DATE_FORMAT(t.transaction_date,"%Y-%m-%d") = DATE_FORMAT(NOW(),"%Y-%m-%d")', ['id' => $sideid]);
        $side['fooddrops'] += $data['foodspent'];

        $side['dropcoins'] = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id', ['id' => $sideid]);
        $side['givedropsurl'] = '?action=give&ids='.$sideid;

        $side['lasttransaction'] = displaydate(db_value('SELECT transaction_date FROM transactions WHERE product_id > 0 AND people_id = :id ORDER BY transaction_date DESC LIMIT 1', ['id' => $sideid]), true);

        $ajaxaside->assign('currency', $_SESSION['camp']['currencyname']);
        $ajaxaside->assign('data', $side);
        $htmlaside = $ajaxaside->fetch('info_aside_purchase.tpl');

        addfield('html', '', $htmlaside, ['aside' => true, 'asidetop' => true]);
    }

    addfield('line', '', '', ['aside' => true]);

    addfield('hidden', 'camp_id', 'camp_id');
    addfield('select', 'Familyhead', 'parent_id', ['multiple' => false, 'tab' => 'people', 'tooltip' => 'If noone is selected, the person is a familyhead.', 'onchange' => 'selectFamilyhead("parent_id","container")', 'query' => '
		SELECT p.id AS value, p.container AS value2, CONCAT(p.container, " ",p.firstname, " ", p.lastname) AS label, NOT visible AS disabled 
		FROM people AS p 
		WHERE parent_id IS NULL AND (NOT p.deleted OR p.deleted IS NULL) AND camp_id = '.$_SESSION['camp']['id'].' 
		GROUP BY p.id 
		ORDER BY label']);
    addfield('text', 'Firstname', 'firstname', ['testid' => 'firstname_id', 'tab' => 'people', 'required' => true]);
    addfield('text', 'Surname', 'lastname', ['testid' => 'lastname_id', 'tab' => 'people']);
    addfield('text', $_SESSION['camp']['familyidentifier'], 'container', ['testid' => 'container_id', 'tab' => 'people', 'required' => true, 'onchange' => 'capitalize("container")']);
    addfield('select', 'Gender', 'gender', ['testid' => 'gender_id', 'tab' => 'people',
        'options' => [['value' => 'M', 'label' => 'Male'], ['value' => 'F', 'label' => 'Female']], ]);
    addfield('date', 'Date of birth', 'date_of_birth', ['testid' => 'date_of_birth_id', 'tab' => 'people', 'date' => true, 'time' => false]);
    addfield('line', '', '', ['tab' => 'people']);
    addfield('select', 'Tag(s)', 'tags', ['testid' => 'tag_id', 'tab' => 'people', 'multiple' => true, 'query' => 'SELECT tags.id AS value, tags.label, IF(people_tags.people_id IS NOT NULL, 1,0) AS selected FROM tags LEFT JOIN people_tags ON tags.id = people_tags.tag_id AND people_tags.people_id = '.intval($id).' WHERE tags.camp_id = '.$_SESSION['camp']['id'].' AND tags.deleted IS NULL ORDER BY label']);
    addfield('select', 'Language(s)', 'languages', ['testid' => 'language_id', 'tab' => 'people', 'multiple' => true, 'query' => 'SELECT a.id AS value, a.name AS label, IF(x.people_id IS NOT NULL, 1,0) AS selected FROM languages AS a LEFT OUTER JOIN x_people_languages AS x ON a.id = x.language_id AND x.people_id = '.intval($id).' WHERE a.visible']);
    addfield('textarea', 'Comments', 'comments', ['testid' => 'comments_id', 'tab' => 'people']);
    addfield('line', '', '', ['tab' => 'people']);
    addfield('checkbox', 'This person is not officially registered.', 'notregistered', ['testid' => 'registered_id', 'tab' => 'people']);
    if ($_SESSION['camp']['extraportion'] && $_SESSION['camp']['food']) {
        addfield('checkbox', 'Extra food due to health condition (as indicated by Red Cross)', 'extraportion', ['tab' => 'people']);
    }
    addfield('checkbox', 'This beneficiary is a volunteer with <i>'.$_SESSION['organisation']['label'].'</i>', 'volunteer', ['testid' => 'volunteer_id', 'tab' => 'people']);

    if ($_SESSION['camp']['bicycle'] || $_SESSION['camp']['workshop'] || $_SESSION['camp']['idcard']) {
        $data['picture'] = (file_exists($settings['upload_dir'].'/people/'.$id.'.jpg') ? $id : 0);
        if ($data['picture']) {
            $exif = exif_read_data($settings['upload_dir'].'/people/'.$id.'.jpg');
            $data['rotate'] = (3 == $exif['Orientation'] ? 180 : (6 == $exif['Orientation'] ? 90 : (8 == $exif['Orientation'] ? 270 : 0)));
        }
        addfield('photo', 'Picture for cards', 'picture', ['tab' => 'bicycle']);
        addfield('line', '', '', ['tab' => 'bicycle']);
        addfield('text', 'Phone number', 'phone', ['tab' => 'bicycle']);
    }
    if ($_SESSION['camp']['bicycle']) {
        addfield('line', '', '', ['tab' => 'bicycle']);
        addfield('checkbox', 'This person succesfully passed the bicycle training', 'bicycletraining', ['tab' => 'bicycle']);
        addfield('bicyclecard', 'Card', 'bicyclecard', ['tab' => 'bicycle']);
        addfield('line', '', '', ['tab' => 'bicycle']);
        addfield('date', 'Bicycle ban until', 'bicycleban', ['tab' => 'bicycle', 'time' => false, 'date' => true, 'tooltip' => 'Ban this person from the borrowing system until (and including) this date. Empty this field to cancel the ban.']);
        addfield('textarea', 'Comment', 'bicyclebancomment', ['tab' => 'bicycle', 'width' => 6, 'tooltip' => 'Please always make a note with a bicycle ban, stating the reason of the ban, your name and the date the ban started.']);
    }
    if ($_SESSION['camp']['workshop']) {
        addfield('line', '', '', ['tab' => 'bicycle']);
        addfield('checkbox', 'This person succesfully passed the workshop training', 'workshoptraining', ['tab' => 'bicycle']);
        addfield('checkbox', 'This person is a workshop supervisor', 'workshopsupervisor', ['tab' => 'bicycle']);
        addfield('workshopcard', 'Card', 'workshopcard', ['tab' => 'bicycle']);
        addfield('line', '', '', ['tab' => 'bicycle']);
        addfield('date', 'Workshop ban until', 'workshopban', ['tab' => 'bicycle', 'time' => false, 'date' => true, 'tooltip' => 'Ban this person from the workshop until (and including) this date. Empty this field to cancel the ban.']);
        addfield('textarea', 'Comment', 'workshopbancomment', ['tab' => 'bicycle', 'width' => 6, 'tooltip' => 'Please always make a note with a workshop ban, stating the reason of the ban, your name and the date the ban started.']);
    }

    if (($_SESSION['usergroup']['allow_laundry_block'] || $_SESSION['user']['is_admin']) && (!$data['parent_id'] && $data['id']) && $_SESSION['camps']['laundry']) {
        addfield('checkbox', 'This family/beneficiary has no access to laundry', 'laundryblock', ['tab' => 'laundry']);
        addfield('text', 'Comment', 'laundrycomment', ['tab' => 'laundry']);

        $result = db_query("SELECT changedate, to_int, u.naam, (SELECT SUBSTR(changes,POSITION('\" to \"' IN changes)+5) FROM history WHERE tablename = 'people' AND record_id = :id AND changedate = h.changedate AND changes LIKE \"%laundrycomment%\") AS comment FROM history AS h, cms_users AS u WHERE tablename = 'people' AND record_id = :id AND changes = 'laundryblock' AND user_id = u.id ORDER BY changedate DESC", ['id' => $data['id']]);
        while ($row = db_fetch($result)) {
            $row['comment'] = substr($row['comment'], 1, strlen($row['comment']) - 4);
            $log[] = strftime('%d-%m-%Y %H:%M:%S', strtotime($row['changedate'])).' - '.$row['naam'].' '.($row['to_int'] ? 'enabled' : 'disabled').' the laundry block - '.($row['comment'] ? 'comment: '.$row['comment'] : '');
        }
        $data['log'] = join("\n", $log);
        addfield('textarea', 'Log', 'log', ['tab' => 'laundry', 'readonly' => true]);
    }

    addfield('hidden', 'Date Signature', 'date_of_signature', ['tab' => 'signature']);
    addfield('signature', 'Signature', 'signaturefield', ['tab' => 'signature']);
    addfield('checkbox', 'Form signed', 'approvalsigned', ['tab' => 'signature', 'hidden' => true]);

    if (0 == $data['parent_id']) {
        if ($id) {
            $table = 'transactions';
            addfield('list', 'Purchases', 'purch', ['tab' => 'transaction', 'width' => 10, 'query' => 'SELECT t.*, u.naam AS user, CONCAT(IF(drops>0,"+",""),drops) AS drops2, DATE_FORMAT(transaction_date,"%d-%m-%Y %H:%i") AS tdate, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS product 
				FROM transactions AS t 
				LEFT OUTER JOIN cms_users AS u ON u.id = t.user_id 
				LEFT OUTER JOIN products AS p ON p.id = t.product_id 
				LEFT OUTER JOIN genders AS g ON p.gender_id = g.id 
				WHERE people_id = '.$id.' AND t.product_id IS NOT NULL 
				ORDER BY transaction_date DESC
				LIMIT 20',
                'columns' => ['product' => 'Product', 'count' => 'Amount', 'drops2' => ucwords($_SESSION['camp']['currencyname']), 'description' => 'Note', 'user' => 'Purchase made by', 'tdate' => 'Date'],
                'allowedit' => false, 'allowadd' => true, 'add' => 'New Purchase', 'addaction' => 'check_out&people_id='.intval($id), 'allowselect' => true, 'allowselectall' => false, 'action' => 'people_edit', 'redirect' => true, 'allowsort' => false, 'modal' => false, ]);

            addfield('line', '', '', ['tab' => 'transaction']);

            $table = 'transactions';
            addfield('list', 'Transactions', 'trans', ['tab' => 'transaction', 'width' => 10, 'query' => 'SELECT t.*, u.naam AS user, CONCAT(IF(drops>0,"+",""),drops) AS drops2, DATE_FORMAT(transaction_date,"%d-%m-%Y %H:%i") AS tdate 
				FROM transactions AS t 
				LEFT OUTER JOIN cms_users AS u ON u.id = t.user_id 
				WHERE people_id = '.$id.' AND t.product_id IS NULL 
				ORDER BY transaction_date DESC
				LIMIT 5',
                'columns' => ['drops2' => ucwords($_SESSION['camp']['currencyname']), 'description' => 'Note', 'user' => 'Transaction made by', 'tdate' => 'Date'],
                'allowedit' => false, 'allowadd' => $data['allowdrops'], 'add' => 'Give '.ucwords($_SESSION['camp']['currencyname']), 'addaction' => 'give&ids='.intval($id), 'allowsort' => false, 'allowselect' => true, 'allowselectall' => false, 'action' => 'people_edit', 'redirect' => true, 'modal' => false, ]);

            //show borrow history
            addfield('line', '', '', ['tab' => 'bicycle']);
            if (db_value('SELECT id FROM borrow_transactions WHERE people_id ='.$id)) {
                addfield('list', 'Last 10 transactions', 'bicycles', ['tab' => 'bicycle', 'width' => 10, 'query' => '
					SELECT DATE_FORMAT(transaction_date,"%e-%m-%Y %H:%i:%S") AS dateout, 
(SELECT DATE_FORMAT(transaction_date,"%e-%m-%Y %H:%i:%S") FROM borrow_transactions AS t2 WHERE t.bicycle_id = t2.bicycle_id AND t2.transaction_date > t.transaction_date ORDER BY transaction_date LIMIT 1) AS datein, (SELECT label FROM borrow_items WHERE id = t.bicycle_id) AS name FROM borrow_transactions AS t LEFT OUTER JOIN people AS p ON p.id = t.people_id WHERE p.id = '.intval($id).' AND status = "out" ORDER BY transaction_date DESC LIMIT 10',
                    'columns' => ['name' => 'Bicycle', 'dateout' => 'Start', 'datein' => 'End'],
                    'allowedit' => false, 'allowadd' => false, 'allowsort' => false, 'allowselect' => false, 'allowselectall' => false, 'redirect' => false, 'modal' => false, ]);
            }
        }
    }
    addfield('created', 'Created', 'created', ['aside' => true]);
    if ($id) {
        addformbutton('submitandedit', $translate['cms_form_save']);
    } else {
        addformbutton('submitandnew', 'Save and new');
    }

    // Tabs
    $tabs['people'] = 'Personal';
    if ($_SESSION['camp']['bicycle'] && $_SESSION['camp']['workshop']) {
        $tabs['bicycle'] = 'Bicycle & Workshop';
    } elseif ($_SESSION['camp']['bicycle']) {
        $tabs['bicycle'] = 'Bicycle';
    } elseif ($_SESSION['camp']['workshop']) {
        $tabs['bicycle'] = 'Workshop';
    } elseif ($_SESSION['camp']['idcard']) {
        $tabs['bicycle'] = 'ID Card';
    }

    if (($_SESSION['usergroup']['allow_laundry_block'] || $_SESSION['user']['is_admin']) && !$data['parent_id'] && $data['id'] && $S_SESSION['camps']['laundry']) {
        $tabs['laundry'] = 'Laundry';
    }

    if (!$data['parent_id'] && $data['id']) {
        $tabs['transaction'] = 'Transactions';
    }

    $tabs['signature'] = 'Privacy declaration';

    $cmsmain->assign('tabs', $tabs);
    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
    $cmsmain->assign('formbuttons', $formbuttons);
