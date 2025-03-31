<?php

use OpenCensus\Trace\Tracer;

Tracer::inSpan(
    ['name' => 'include/people.php'],
    function () use ($action, &$cmsmain) {
        global $settings, $table, $listconfig, $listdata;

        $table = $action;
        $ajax = checkajax();

        if (!$ajax) {
            if (!$_SESSION['camp']['id']) {
                throw new Exception('The list of beneficiaries is not available when there is no camp selected');
            }

            // Title
            $cmsmain->assign('title', 'Beneficiaries');

            initlist();

            // Filter
            $tags = db_simplearray('SELECT id, label FROM tags WHERE camp_id = :camp_id AND deleted IS NULL AND type in ("All","People") ORDER BY seq', ['camp_id' => $_SESSION['camp']['id']]);
            if (!empty($tags)) {
                $tagfilter = ['id' => 'tagfilter', 'placeholder' => 'Tag filter', 'options' => db_array('SELECT id, id AS value, label, color FROM tags WHERE camp_id = :camp_id AND deleted IS NULL AND type in ("All","People") ORDER BY seq', ['camp_id' => $_SESSION['camp']['id']])];
                listsetting('multiplefilter', $tagfilter);
            }

            $statusarray = ['day' => 'New today', 'week' => 'New this week', 'month' => 'New this month', 'inactive' => 'Inactive', 'approvalsigned' => 'No signature', 'notregistered' => 'Not registered'];
            if ($_SESSION['camp']['beneficiaryisregistered']) {
                $statusarray['notregistered'] = 'Not registered';
            }
            if ($_SESSION['camp']['beneficiaryisvolunteer']) {
                $statusarray['volunteer'] = 'Volunteers';
            }
            listfilter3(['label' => 'Quick filters', 'options' => $statusarray, 'filter' => '"show"']);

            // Search
            listsetting('manualquery', true);
            $search_fields = ['firstname', 'lastname', 'container', 'comments'];
            // Add search fields for the additional custom fields if enabled
            if ($_SESSION['camp']['email_enabled']) {
                $search_fields[] = 'email';
            }
            if ($_SESSION['camp']['phone_enabled']) {
                $search_fields[] = 'phone';
            }
            if ($_SESSION['camp']['additional_field1_enabled']) {
                $search_fields[] = 'customfield1_value';
            }
            if ($_SESSION['camp']['additional_field2_enabled']) {
                $search_fields[] = 'customfield2_value';
            }
            listsetting('search', $search_fields);
            $search = substr((string) db_escape(trim((string) $listconfig['searchvalue'])), 1, strlen((string) db_escape(trim((string) $listconfig['searchvalue']))) - 2);

            $is_filtered = (isset($listconfig['filtervalue3']) || isset($listconfig['multiplefilter_selected']) || isset($listconfig['searchvalue'])) ? true : false;

            // filter for up to 500 records
            if (!$is_filtered) {
                $number_of_people = db_value('SELECT COUNT(id) FROM people WHERE camp_id = :camp_id AND deleted IS NULL', ['camp_id' => $_SESSION['camp']['id']]);
                if ($number_of_people > 500) {
                    listfilter(['label' => 'List size', 'options' => ['all' => 'Show all'], 'filter' => '"show"']);

                    if ('all' != $listconfig['filtervalue']) {
                        // limits the number of rows displayed
                        listsetting('maxlimit', 500);
                        // Notify the user of the limit on the number of records
                        $cmsmain->assign('notification', 'Only the first 500 beneficiaries are shown. Use the filter and search to find the rest.');
                    }
                }
            }

            // make sorting optional
            listfilter2(['label' => 'List settings', 'options' => ['sort' => 'Make sortable'], 'filter' => '"show"']);

            listsetting('allowcopy', false);
            listsetting('allowshowhide', false);
            listsetting('add', 'New person');
            listsetting('delete', 'Deactivate');
            if (isset($listconfig['filtervalue2']) && 'sort' === $listconfig['filtervalue2']) {
                listsetting('allowsort', true);
                listsetting('allowmove', false);
                listsetting('noindent', true);
            } else {
                listsetting('allowsort', false);
                listsetting('allowmove', true);
                listsetting('allowmoveto', 1);
            }

            // Toplevel tabs
            listsetting('haspagemenu', true);
            addpagemenu('all', 'All', ['link' => '?action=people', 'active' => true]);
            addpagemenu('deactivated', 'Deactivated', ['link' => '?action=people_deactivated']);

            // List Buttons
            addbutton('export', 'Export', ['icon' => 'fa-download', 'showalways' => false, 'testid' => 'exportBeneficiariesButton']);

            // Show export all button if there are more than 500 beneficiaries
            if ($number_of_people > 500) {
                addbutton('export_all', 'Export All', ['icon' => 'fa-download', 'showalways' => true, 'testid' => 'exportAllBeneficiariesButton']);
            }
            if (!empty($tags)) {
                addbutton('tag', 'Add Tag', ['icon' => 'fa-tag', 'options' => $tags]);
                addbutton('rtag', 'Remove Tag', ['icon' => 'fa-tags', 'options' => $tags]);
            }
            addbutton('give', 'Give '.ucwords((string) $_SESSION['camp']['currencyname']), ['image' => 'one_coin.png', 'imageClass' => 'coinsImage', 'oneitemonly' => false, 'testid' => 'giveTokensListButton']);
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

            // Columns
            addcolumn('text', 'Surname', 'lastname');
            addcolumn('text', 'Firstname', 'firstname');
            addcolumn('text', 'Gender', 'gender');
            addcolumn('text', 'Age', 'age');
            addcolumn('text', $_SESSION['camp']['familyidentifier'], 'container');
            addcolumn('text', ucwords((string) $_SESSION['camp']['currencyname']), 'tokens');
            if (!empty($tags)) {
                addcolumn('tag', 'Tags', 'tags');
            }
            addcolumn('text', 'Comments', 'comments');
            if ($is_filtered) {
                addcolumn('text', 'Last Activity', 'last_activity');
            }
            // Display additional custom fields if enabled
            if ($_SESSION['camp']['email_enabled']) {
                addcolumn('text', 'Email address', 'email');
            }
            if ($_SESSION['camp']['phone_enabled']) {
                addcolumn('text', 'Phone number', 'phone');
            }
            if ($_SESSION['camp']['additional_field1_enabled']) {
                addcolumn('text', $_SESSION['camp']['additional_field1_label'], 'customfield1_value');
            }
            if ($_SESSION['camp']['additional_field2_enabled']) {
                addcolumn('text', $_SESSION['camp']['additional_field2_label'], 'customfield2_value');
            }
            if ($_SESSION['camp']['additional_field3_enabled']) {
                addcolumn('text', $_SESSION['camp']['additional_field3_label'], 'customfield3_value');
            }
            if ($_SESSION['camp']['additional_field4_enabled']) {
                addcolumn('text', $_SESSION['camp']['additional_field4_label'], 'customfield4_value');
            }

            addcolumn('html', '&nbsp;', 'icons');

            // Query
            $data = getlistdata('
            SELECT
                people_filtered_with_tags.*,
                IFNULL(SUM(CASE WHEN people_filtered_with_tags.level = 0 THEN transactions.drops ELSE 0 END),0) AS tokens,
                MAX(transactions.transaction_date) AS last_activity
            FROM
                (SELECT
                    people_filtered.*,
                    GROUP_CONCAT(tags.label ORDER BY tags.seq SEPARATOR 0x1D) AS taglabels,
                    GROUP_CONCAT(tags.color ORDER BY tags.seq) AS tagcolors
                FROM
                    (SELECT 
                        IF(people.parent_id,1,0) AS level,
                        people.id,
                        people.parent_id,
                        people.lastname,
                        people.firstname,
                        IF(people.gender="M","Male",IF(people.gender="F","Female","")) AS gender, 
                        IF(DATEDIFF(NOW(), people.date_of_birth)>730,CONCAT(TIMESTAMPDIFF(YEAR, people.date_of_birth, NOW()), " yrs"), CONCAT(TIMESTAMPDIFF(MONTH, people.date_of_birth, NOW()), IF(TIMESTAMPDIFF(MONTH, people.date_of_birth, NOW())>1," mos"," mo"))) AS age, 
                        people.container,
                        people.comments,
                        people.created,
                        people.modified,
                        people.approvalsigned,
                        people.email,
                        people.phone,
                        people.customfield1_value,
                        people.customfield2_value,
                        people.customfield3_value,
                        people.customfield4_value
                    FROM
                        people'
                    // Join tags here only if a tag filter is selected and only people with a certain tag should be returned
                    .($listconfig['multiplefilter_selected'] ? '
                        LEFT JOIN
                            tags_relations AS people_tags_filter ON people_tags_filter.object_id = people.id AND people_tags_filter.object_type = "People" AND people_tags_filter.deleted_on IS NULL
                        LEFT JOIN
                            tags AS tags_filter ON tags_filter.id = people_tags_filter.tag_id AND tags_filter.deleted IS NULL AND tags_filter.camp_id = '.$_SESSION['camp']['id'] : '').'
                    WHERE
                        people.deleted IS NULL AND
                        people.camp_id = '.$_SESSION['camp']['id']
                        .('day' == $listconfig['filtervalue3'] ? ' AND DATE(NOW()) = DATE(people.created) ' : '')
                        .('week' == $listconfig['filtervalue3'] ? ' AND DATE_FORMAT(NOW(),"%v-%x") = DATE_FORMAT(people.created,"%v-%x") ' : '')
                        .('month' == $listconfig['filtervalue3'] ? ' AND DATE_FORMAT(NOW(),"%m-%Y") = DATE_FORMAT(people.created,"%m-%Y") ' : '')
                        .('volunteer' == $listconfig['filtervalue3'] ? ' AND people.volunteer ' : '')
                        .('notregistered' == $listconfig['filtervalue3'] ? ' AND people.notregistered ' : '')
                        .($listconfig['searchvalue'] ? ' AND
                            (people.lastname LIKE "%'.$search.'%" OR 
                            people.firstname LIKE "%'.$search.'%" OR 
                            people.container = "'.$search.'" OR 
                            people.comments LIKE "%'.$search.'%"'
                            // Update query to include search fields for the additional custom fields if enabled
                            .($_SESSION['camp']['email_enabled'] ? ' OR people.email LIKE "%'.$search.'%"' : '')
                            .($_SESSION['camp']['phone_enabled'] ? ' OR people.phone LIKE "%'.$search.'%"' : '')
                            .($_SESSION['camp']['additional_field1_enabled'] ? ' OR people.customfield1_value LIKE "%'.$search.'%"' : '')
                            .($_SESSION['camp']['additional_field2_enabled'] ? ' OR people.customfield2_value LIKE "%'.$search.'%"' : '')
                            .')' : ' ')
                        // filter for selected tags
                        .($listconfig['multiplefilter_selected'] ? ' AND tags_filter.id IN ('.implode(',', $listconfig['multiplefilter_selected']).') ' : '').'
                    GROUP BY 
                        people.id
                    ) AS people_filtered
                LEFT JOIN
                    tags_relations 
                    ON tags_relations.object_id = people_filtered.id AND tags_relations.object_type = "People" AND tags_relations.deleted_on IS NULL
                LEFT JOIN
                    tags 
                    ON tags.id = tags_relations.tag_id 
                    AND tags.deleted IS NULL 
                    AND tags.camp_id = '.$_SESSION['camp']['id'].'
                GROUP BY 
                    people_filtered.id
                ) AS people_filtered_with_tags
            LEFT JOIN
                people AS parent ON people_filtered_with_tags.parent_id = parent.id
            LEFT JOIN
                transactions ON transactions.people_id = people_filtered_with_tags.id '
            .(
                'approvalsigned' == $listconfig['filtervalue3'] ? '
                WHERE 
                    ((NOT people_filtered_with_tags.approvalsigned AND people_filtered_with_tags.parent_id IS NULL) OR NOT parent.approvalsigned)' : ''
            ).'
            GROUP BY
                people_filtered_with_tags.id
            ORDER BY
                -- sort by *parent* first & last name (or own first/last if no parent)
                IF(people_filtered_with_tags.parent_id, parent.lastname, people_filtered_with_tags.lastname),
                IF(people_filtered_with_tags.parent_id, parent.firstname, people_filtered_with_tags.firstname),
                -- children should be grouped with their parents
                If(people_filtered_with_tags.parent_id, parent.id, people_filtered_with_tags.id),
                -- parents should appear before children
                IF(people_filtered_with_tags.parent_id, 1, 0),
                -- children ordered by first name & last name too
                IF(people_filtered_with_tags.parent_id, people_filtered_with_tags.lastname, ""),
                IF(people_filtered_with_tags.parent_id, people_filtered_with_tags.firstname, "")');

            // Prepare data
            $daysinactive = db_value('SELECT delete_inactive_users/2 FROM camps WHERE id = '.$_SESSION['camp']['id']);

            Tracer::inSpan(
                ['name' => 'include/people.php:inactive'],
                function () use (&$data, $daysinactive) {
                    global $listconfig;

                    foreach ($data as $key => $value) {
                        $created = is_null($data[$key]['created']) ? null : new DateTime($data[$key]['created']);
                        $modified = is_null($data[$key]['modified']) ? $created : new DateTime($data[$key]['modified']);
                        $last_activity = is_null($data[$key]['last_activity']) ? $created : new DateTime($data[$key]['last_activity']);
                        $data[$key]['last_activity'] = is_null($last_activity) ? null : $last_activity->format('Y-m-d');
                        // If custom date field is not empty, and enabled in base settings, format to date format
                        if ($_SESSION['camp']['additional_field4_enabled'] && !empty($data[$key]['customfield4_value'])) {
                            $customfield4_value = is_null($data[$key]['customfield4_value']) ? null : new DateTime($data[$key]['customfield4_value']);
                            $data[$key]['customfield4_value'] = is_null($customfield4_value) ? null : $customfield4_value->format('Y-m-d');
                        }
                        $data[$key]['days_last_active'] = max($created, $modified, $last_activity)->diff(new DateTime())->format('%a');
                        $data[$key]['tokens'] = $data[$key]['level'] ? null : $data[$key]['tokens'];

                        if ($data[$key]['days_last_active'] > $daysinactive) {
                            $data[$key]['icons'] = '<i class="fa fa-exclamation-triangle warning tooltip-this" title="This family hasn\'t been active for at least '.floor($daysinactive).' days."></i> ';
                        } else {
                            if ('inactive' == $listconfig['filtervalue3']) {
                                unset($data[$key]);

                                continue;
                            }
                            $data[$key]['icons'] = '';
                        }
                    }
                }
            );

            Tracer::inSpan(
                ['name' => 'include/people.php:approvalsigned_volunteer_unregistered'],
                function () use (&$data) {
                    foreach ($data as $key => $value) {
                        if (0 == $data[$key]['level'] && !$data[$key]['approvalsigned']) {
                            $data[$key]['icons'] .= '<a href="?action=people_edit&id='.$data[$key]['id'].'&active=signature"><i class="fa fa-edit warning tooltip-this" title="Please have the familyhead/beneficiary read and sign the approval form for storing and processing their data."></i></a> ';
                        }
                    }
                }
            );

            Tracer::inSpan(
                ['name' => 'include/people.php:idcard'],
                function () use (&$data) {
                    global $settings;

                    if ($_SESSION['camp']['idcard']) {
                        foreach ($data as $key => $value);
                        // if (file_exists($settings['upload_dir'].'/people/'.$data[$key]['id'].'.jpg')) {
                        //     $data[$key]['icons'] .= '<i class="fa fa-id-card-o tooltip-this" title="This person has a picture."></i> ';
                        // }
                    }
                }
            );

            Tracer::inSpan(
                ['name' => 'include/people.php:idcard'],
                function () use (&$data) {
                    foreach ($data as $key => $value) {
                        if ($data[$key]['taglabels']) {
                            $taglabels = explode(chr(0x1D), (string) $data[$key]['taglabels']);
                            $tagcolors = explode(',', (string) $data[$key]['tagcolors']);
                            foreach ($taglabels as $tagkey => $taglabel) {
                                $data[$key]['tags'][$tagkey] = ['label' => $taglabel, 'color' => $tagcolors[$tagkey], 'textcolor' => get_text_color($tagcolors[$tagkey])];
                            }
                        }
                    }
                }
            );

            Tracer::inSpan(
                ['name' => 'people.php:addtemplatedata'],
                function () use ($cmsmain, $data) {
                    global $listdata, $listdata, $listconfig;

                    // Pass information to template
                    $cmsmain->assign('data', $data);
                    $cmsmain->assign('listconfig', $listconfig);
                    $cmsmain->assign('listdata', $listdata);
                    $cmsmain->assign('include', 'cms_list.tpl');
                }
            );
        } elseif ('export_all' == $_POST['do']) {
            // Add support for exporting all beneficiaries
            // since the redirect only works if the user is authorized, we do not have to validate anything else
            $_SESSION['export_ids_people'] = 'all';

            echo json_encode(['success' => true, 'redirect' => '?action=people_export']);

            exit;
        } else {
            $valid_ids = array_column(db_array('SELECT id from people as p where p.camp_id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]), 'id');
            $ids = [];
            if ('move' == $_POST['do']) { // move passes the ids in pairs with the level the id is moved to. Therefore, it needs to be handled differently.
                foreach (json_decode((string) $_POST['ids']) as $pair) {
                    $ids[] = $pair[0];
                }
            } else {
                $ids = explode(',', (string) $_POST['ids']);
            }
            $delta = array_diff($ids, $valid_ids);
            if (0 != count($delta)) {
                $message = 'You do not have access to this beneficiary record!';
                trigger_error($message, E_USER_ERROR);
                $success = false;
            } else {
                switch ($_POST['do']) {
                    case 'merge':
                        $ids = explode(',', (string) $_POST['ids']);
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
                            // Transaction block added over update queriesTransaction block added over update and insert queries
                            db_transaction(function () use ($ids, $oldest) {
                                foreach ($ids as $id) {
                                    if ($id != $oldest) {
                                        db_query('UPDATE people SET parent_id = :oldest WHERE id = :id', ['oldest' => $oldest, 'id' => $id]);
                                        db_query('UPDATE transactions SET people_id = :oldest WHERE people_id = :id', ['oldest' => $oldest, 'id' => $id]);
                                    }
                                }
                            });
                            $success = true;
                            $message = 'The merge has be successfully applied';
                            $redirect = true;
                            correctchildren();
                        }

                        break;

                    case 'detach':
                        $ids = explode(',', (string) $_POST['ids']);
                        foreach ($ids as $key => $value) {
                            if (!db_value('SELECT parent_id FROM people WHERE id = :id', ['id' => $value])) {
                                $containsmembers = true;
                            }
                        }
                        if ($containsmembers) {
                            $message = 'Please select only members of a family, not family heads';
                            $success = false;
                        } else {
                            // Transaction block added over update queries
                            db_transaction(function () use ($ids) {
                                foreach ($ids as $id) {
                                    db_query('UPDATE people SET parent_id = NULL WHERE id = :id', ['id' => $id]);
                                }
                            });
                            $redirect = true;
                            $success = true;
                            $message = ($success) ? 'Selected people have been detached' : 'Something went wrong';
                        }

                        break;

                    case 'give':
                        $ids = $_POST['ids'];
                        $success = true;
                        $redirect = '?action=give&ids='.$ids;

                        break;

                    case 'move':
                        $ids = json_decode((string) $_POST['ids']);
                        // list($success, $message, $redirect, $aftermove) = listMove($table, $ids, true, 'correctdrops');
                        // Refactored list move method to use a transaction block and bulk insert for the correctdrops method
                        [$success, $message, $redirect, $aftermove] = listBulkMove($table, $ids, true, 'bulkcorrectdrops', true);

                        break;

                    case 'delete':
                        $ids = explode(',', (string) $_POST['ids']);
                        [$success, $message, $redirect] = listDelete($table, $ids);

                        break;

                    case 'copy':
                        $ids = explode(',', (string) $_POST['ids']);
                        [$success, $message, $redirect] = listCopy($table, $ids, 'name');

                        break;

                    case 'hide':
                        $ids = explode(',', (string) $_POST['ids']);
                        [$success, $message, $redirect] = listShowHide($table, $ids, 0);

                        break;

                    case 'show':
                        $ids = explode(',', (string) $_POST['ids']);
                        [$success, $message, $redirect] = listShowHide($table, $ids, 1);

                        break;

                    case 'touch':
                        $ids = explode(',', (string) $_POST['ids']);
                        $userId = $_SESSION['user']['id'];
                        // Query speed optimised for 500 records from 6.2 seconds to 0.54 seconds using  transaction blocks over UPDATE and bulk inserts
                        db_transaction(function () use ($ids, $userId) {
                            foreach ($ids as $id) {
                                db_query('UPDATE people SET modified = NOW(), modified_by = :user WHERE id = :id', ['id' => $id, 'user' => $userId]);
                            }
                        });
                        // Bulk insert used to insert into history table
                        simpleBulkSaveChangeHistory('people', $ids, 'Touched');

                        $success = true;
                        $message = 'Selected people have been touched';
                        $redirect = false;

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
                        if ('undefined' == $_POST['option']) {
                            $success = false;
                            $message = 'No tags exist. Please go to "Manage tags" to create tags.';
                            $redirect = false;
                        } else {
                            // set tag id
                            $tag_id = $_POST['option'];
                            // validate input
                            $people_ids = array_filter($ids, fn ($id) => ctype_digit($id));
                            if (is_array($people_ids) && sizeof($people_ids) > 0) {
                                $people_with_this_tag = db_simplearray('SELECT object_id FROM tags_relations WHERE tag_id = :tag_id AND object_type = "People" AND object_id IN ('.implode(',', $people_ids).') AND deleted_on IS NULL', ['tag_id' => $tag_id], false, false);
                                $people_ids_to_add = array_values(array_diff($people_ids, $people_with_this_tag));

                                if (sizeof($people_ids_to_add) > 0) {
                                    // Query speed optimised for 500 records from 3.2 seconds to 0.039 seconds using bulk inserts
                                    $query = 'INSERT INTO tags_relations (tag_id, object_type, `object_id`, created_on, created_by_id) VALUES ';
                                    $now = (new DateTime())->format('Y-m-d H:i:s');
                                    $user_id = $_SESSION['user']['id'];
                                    $params = ['tag_id' => $tag_id, 'created_on' => $now, 'created_by' => $user_id];

                                    for ($i = 0; $i < sizeof($people_ids_to_add); ++$i) {
                                        $query .= "(:tag_id, 'People', :people_id{$i}, :created_on, :created_by)";
                                        $params = array_merge($params, ['people_id'.$i => $people_ids_to_add[$i]]);
                                        if ($i !== sizeof($people_ids_to_add) - 1) {
                                            $query .= ',';
                                        }
                                    }
                                    db_query($query, $params);
                                }

                                $success = true;
                                $message = 'Tags added';
                                $redirect = true;
                            } else {
                                $success = false;
                                $message = 'To apply the tag, the beneficiary must be checked';
                                $redirect = false;
                            }
                        }

                        break;

                    case 'rtag':
                        if ('undefined' == $_POST['option']) {
                            $success = false;
                            $message = 'No tags exist. Please go to "Manage tags" to create tags.';
                            $redirect = false;
                        } else {
                            // set tag id
                            $tag_id = $_POST['option'];
                            $people_ids = $ids;
                            if (is_array($people_ids) && sizeof($people_ids) > 0) {
                                // Query speed optimised using transaction block and bulk delete
                                // related to this trello card https://trello.com/c/g24mIVb8
                                db_transaction(function () use ($tag_id, $people_ids) {
                                    $now = (new DateTime())->format('Y-m-d H:i:s');
                                    $user_id = $_SESSION['user']['id'];
                                    $deleteClause = [];
                                    foreach ($people_ids as $people_id) {
                                        $deleteClause[] = sprintf('(%d, "%s", %d)', $tag_id, 'People', $people_id);
                                    }
                                    if (sizeof($deleteClause) > 0) {
                                        db_query('UPDATE tags_relations SET deleted_on = :deleted_on, deleted_by_id = :deleted_by WHERE deleted_on IS NULL AND (tag_id, object_type, `object_id`) IN ('.join(',', $deleteClause).')', ['deleted_on' => $now, 'deleted_by' => $user_id]);
                                    }
                                });
                                $success = true;
                                $message = 'Tags removed';
                                $redirect = true;
                            } else {
                                $success = false;
                                $message = 'To remove the tag, the beneficiary must be checked';
                                $redirect = false;
                            }
                        }

                        break;
                }
            }

            $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect, 'action' => $aftermove];

            echo json_encode($return);

            exit;
        }
    }
);

function correctchildren()
{
    $result = db_query('SELECT (SELECT p2.parent_id FROM people AS p2 WHERE p2.id = p1.parent_id) AS newparent, p1.id FROM people AS p1 WHERE p1.parent_id > 0 AND (SELECT p2.parent_id FROM people AS p2 WHERE p2.id = p1.parent_id) AND NOT deleted');
    // Optimized update queries by adding transaction blocks around update statements
    db_transaction(function () use ($result) {
        while ($row = db_fetch($result)) {
            db_query('UPDATE people SET parent_id = :newparent WHERE id = :id', ['newparent' => $row['newparent'], 'id' => $row['id']]);
        }
    });
}

function correctdrops($id)
{
    $drops = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id', ['id' => intval($id)]);
    $person = db_row('SELECT * FROM people AS p WHERE id = :id', ['id' => $id]);

    if ($drops && $person['parent_id']) {
        // Combining insert values to create bulk inserts instead of two insert statements
        db_query('INSERT INTO transactions (people_id, drops, description, transaction_date, user_id) VALUES 
                ('.$person['parent_id'].', '.$drops.', "'.ucwords((string) $_SESSION['camp']['currencyname']).' moved from family member to family head", NOW(), '.$_SESSION['user']['id'].'), 
                ('.$person['id'].', -'.$drops.', "'.ucwords((string) $_SESSION['camp']['currencyname']).' moved to new family head", NOW(), '.$_SESSION['user']['id'].')');

        $newamount = db_value('SELECT SUM(drops) FROM transactions WHERE people_id = :id', ['id' => $person['parent_id']]);
        $aftermove = 'correctDrops({id:'.$person['id'].', value: ""}, {id:'.$person['parent_id'].', value: '.$newamount.'})';

        return $aftermove;
    }
}

function bulkcorrectdrops($ids = [])
{
    $query = '';
    $aftermove = '';
    $finalIds = [];
    for ($i = 0; $i < sizeof($ids); ++$i) {
        $id = $ids[$i];
        $drops = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id', ['id' => intval($id)]);
        $person = db_row('SELECT * FROM people AS p WHERE id = :id', ['id' => $id]);

        if ($drops && $person['parent_id']) {
            $finalIds[] = $id;
            $query .= '('.$person['parent_id'].', '.$drops.', "'.ucwords((string) $_SESSION['camp']['currencyname']).' moved from family member to family head", NOW(), '.$_SESSION['user']['id'].'), ';
            $query .= '('.$person['id'].', -'.$drops.', "'.ucwords((string) $_SESSION['camp']['currencyname']).' moved to new family head", NOW(), '.$_SESSION['user']['id'].'), ';
        }
    }
    if ('' !== $query) {
        // Removing an extra comma from the end of query
        $query = substr($query, 0, strlen($query) - 2);
        db_query("INSERT INTO transactions (people_id, drops, description, transaction_date, user_id) VALUES {$query}");
    }

    // Correction of the dropped values - new values must be retrieved through a query
    for ($i = 0; $i < sizeof($finalIds); ++$i) {
        $id = $finalIds[$i];
        $drops = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id', ['id' => intval($id)]);
        $person = db_row('SELECT * FROM people AS p WHERE id = :id', ['id' => $id]);

        $newamount = db_value('SELECT SUM(drops) FROM transactions WHERE people_id = :id', ['id' => $person['parent_id']]);
        $aftermove .= 'correctDrops({"id":'.$person['id'].', "value": ""}, {"id":'.$person['parent_id'].', "value": '.$newamount.'}); ';
    }

    return $aftermove;
}
