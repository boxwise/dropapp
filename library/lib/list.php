<?php

function listMove($table, $ids, $regardparent = true, $hook = '')
{
    $hasSeq = db_fieldexists($table, 'seq');
    if (!$hasSeq) {
        return 'This table cannot be sorted';
    }
    if ($regardparent) {
        $hasParent = db_fieldexists($table, 'parent_id');
    }

    $i = 1;
    $seq = [];
    $return = '';

    foreach ($ids as $line) {
        ++$i;
        [$id, $level] = $line;
        $parent[$level] = $id;
        ++$seq[$level];

        if ($hasParent) {
            $parent_id = ($level ? $parent[$level - 1] : null);
            db_query('UPDATE '.$table.' SET parent_id = :parent_id, seq = :seq WHERE id = :id', ['parent_id' => $parent_id, 'seq' => $seq[$level], 'id' => $id]);
            if ($hook) {
                $result = $hook($id);
                if ($result) {
                    $aftermove = $result;
                }
            }
        } else {
            db_query('UPDATE '.$table.' SET seq = :seq WHERE id = :id', ['seq' => $seq[$level], 'id' => $id]);
            if ($hook) {
                $result = $hook($id);
                if ($result) {
                    $aftermove = $result;
                }
            }
        }
    }

    return [true, $return, false, $aftermove];
}

function listBulkMove($table, $ids, $regardparent = true, $hook = '', $updatetransactions = false)
{
    $hasSeq = db_fieldexists($table, 'seq');
    if (!$hasSeq) {
        return 'This table cannot be sorted';
    }
    if ($regardparent) {
        $hasParent = db_fieldexists($table, 'parent_id');
    }

    $i = 1;
    $return = '';
    $hookIds = db_transaction(function () use ($ids, $hasParent, $table, $hook, $i, $updatetransactions) {
        $hookIds = [];
        $seq = [];
        foreach ($ids as $line) {
            ++$i;
            [$id, $level] = $line;
            $parent[$level] = $id;
            ++$seq[$level];

            if ($hasParent) {
                $new_parent_id = ($level ? $parent[$level - 1] : null);
                $old_parent_id = db_value('SELECT parent_id FROM '.$table.' WHERE id = :id', ['id' => $id]);
                if ($new_parent_id != $old_parent_id) {
                    db_query('UPDATE '.$table.' SET parent_id = :parent_id WHERE id = :id', ['parent_id' => $new_parent_id, 'id' => $id]);
                    if ($updatetransactions && null != $new_parent_id) {
                        db_query('UPDATE transactions SET people_id = :parent_id WHERE people_id = :id', ['parent_id' => $new_parent_id, 'id' => $id]);
                    }
                }
            }

            db_query('UPDATE '.$table.' SET seq = :seq WHERE id = :id', ['seq' => $seq[$level], 'id' => $id]);
            if ($hook) {
                $hookIds[] = $id;
            }
        }

        return $hookIds;
    });
    // If the method has a bulk prefix, then execute the hook
    if (is_array($hookIds) && sizeof($hookIds) > 0 && preg_match('/bulk.+/', (string) $hook)) {
        $aftermove = $hook($hookIds);
    }

    return [true, $return, false, $aftermove];
}

function listRealDelete($table, $ids, $uri = false)
{
    global $translate, $action;

    $hasPrevent = db_fieldexists($table, 'preventdelete');
    $hasTree = db_fieldexists($table, 'parent_id');
    $count = 0;
    foreach ($ids as $id) {
        $result = db_query('DELETE FROM '.$table.' WHERE id = :id'.($hasPrevent ? ' AND NOT preventdelete' : ''), ['id' => $id]);
        $count += $result->rowCount();
        if ($result->rowCount()) {
            simpleSaveChangeHistory($table, $id, 'Record deleted without undelete');
        }
    }

    if ($count) {
        return [true, $translate['cms_list_deletesuccess'], true];
    }

    return [false, $translate['cms_list_deleteerror'], false];
}

function listBulkRealDelete($table, $ids, $uri = false)
{
    global $translate, $action;

    $hasPrevent = db_fieldexists($table, 'preventdelete');
    $hasTree = db_fieldexists($table, 'parent_id');
    $count = 0;
    $deletedIds = [];

    foreach ($ids as $id) {
        $result = db_query('DELETE FROM '.$table.' WHERE id = :id'.($hasPrevent ? ' AND NOT preventdelete' : ''), ['id' => $id]);
        $count += $result->rowCount();
        if ($result->rowCount()) {
            $deletedIds[] = $id;
        }
    }

    simpleBulkSaveChangeHistory($table, $deletedIds, 'Record deleted without undelete');

    if ($count) {
        return [true, $translate['cms_list_deletesuccess'], true];
    }

    return [false, $translate['cms_list_deleteerror'], false];
}

function listDelete($table, $ids, $uri = false, $fktables = null)
{
    global $translate, $action;

    $hasDeletefield = db_fieldexists($table, 'deleted');
    $hasPrevent = db_fieldexists($table, 'preventdelete');
    $hasTree = db_fieldexists($table, 'parent_id');
    $count = 0;

    try {
        foreach ($ids as $id) {
            if ($hasDeletefield) {
                // Check foreign keys only if specified.
                if (isset($fktables)) {
                    // Does the db have foreign keys?
                    $foreignkeys = db_referencedforeignkeys($table);
                    if (count($foreignkeys) > 0) {
                        foreach ($foreignkeys as $foreignkey) {
                            // Do we want to check the foreign key and does the foreign key restrict the delete?
                            if (in_array($foreignkey['TABLE_NAME'], $fktables) && (('RESTRICT' == $foreignkey['DELETE_RULE']) || ('NO ACTION' == $foreignkey['DELETE_RULE']))) {
                                if (db_fieldexists($foreignkey['TABLE_NAME'], 'label')) {
                                    $namestring = ',b.label AS label';
                                } elseif (db_fieldexists($foreignkey['TABLE_NAME'], 'naam')) {
                                    $namestring = ',b.naam AS label';
                                } elseif (db_fieldexists($foreignkey['TABLE_NAME'], 'name')) {
                                    $namestring = ',b.name AS label';
                                } elseif (db_fieldexists($foreignkey['TABLE_NAME'], 'firstname')) {
                                    $namestring = ',concat(b.firstname," ",b.lastname) AS label';
                                } else {
                                    $namestring = '';
                                }
                                $restricted = db_array('
                                SELECT b.id as id'.$namestring.'
                                FROM '.$table.' a, '.$foreignkey['TABLE_NAME'].' b 
                                WHERE a.'.$foreignkey['REFERENCED_COLUMN_NAME'].' = b.'.$foreignkey['COLUMN_NAME'].' AND '.(db_fieldexists($foreignkey['TABLE_NAME'], 'deleted') ? '(NOT b.deleted OR b.deleted IS NULL) AND ' : '').' a.id = :id', ['id' => $id]);
                                if (count($restricted)) {
                                    return [false, listDeleteMessage($table, $id, $foreignkey, $restricted), false];
                                }
                            }
                        }
                    }
                }
                $count += listDeleteAction($table, $id, 0, $hasTree);
            } else {
                $result = db_query('DELETE FROM '.$table.' WHERE id = :id'.($hasPrevent ? ' AND NOT preventdelete' : ''), ['id' => $id]);
                $count += $result->rowCount();
                if ($result->rowCount()) {
                    simpleSaveChangeHistory($table, $id, 'Record deleted');
                }
            }
        }
        if ($count) {
            return [true, $translate['cms_list_deletesuccess'], false];
        }

        return [false, $translate['cms_list_deleteerror'], false];
    } catch (Exception $e) {
        return [false, $e->getMessage(), false];
    }
}

function listDeleteMessage($table, $id, $foreignkey, $restricted)
{
    $table_name = [
        'camps' => 'camp',
        'locations' => 'warehouse',
        'organisations' => 'organisation',
    ];
    $object_table_name = [
        'people' => 'a beneficiary',
        'products' => 'a product',
        'library' => 'a library',
        'locations' => 'a warehouse',
        'stock' => 'a box',
        'camps' => 'a camp',
        'cms_users' => ' an user',
        'cms_usergroups' => 'an usergroup',
    ];
    $object_name = implode('', array_column($restricted, 'label'));
    $id_name = ' with id '.implode('', array_column($restricted, 'id'));
    if (!empty($object_name)) {
        $object_name = ' called '.$object_name.' ';
    }

    return 'This '.$table_name[$table].' cannot be removed since '.$object_table_name[$foreignkey['TABLE_NAME']].''.$object_name.' '.$id_name.' is still active. Please edit or remove it first!';
}

function listDeleteAction($table, $id, $count = 0, $recursive = false)
{
    $hasPrevent = db_fieldexists($table, 'preventdelete');

    $result = db_query('UPDATE '.$table.' SET deleted = NOW(), modified = NOW(), modified_by = :user_id WHERE id = :id'.($hasPrevent ? ' AND NOT preventdelete' : ''), ['id' => $id, 'user_id' => $_SESSION['user']['id']]);
    $count += $result->rowCount();
    if ($result->rowCount()) {
        simpleSaveChangeHistory($table, $id, 'Record deleted');
    }

    if ($recursive) {
        $childs = db_array('SELECT id FROM '.$table.' WHERE parent_id = :id'.($hasPrevent ? ' AND NOT preventdelete' : ''), ['id' => $id]);
        foreach ($childs as $child) {
            $count += listDeleteAction($table, $child['id'], $count, true);
        }
    }

    return $count;
}

function listUndelete($table, $ids, $uri = false, $overwritehastree = false)
{
    global $translate, $action;

    $count = 0;

    $hasDeletefield = db_fieldexists($table, 'deleted');
    $hasPrevent = db_fieldexists($table, 'preventdelete');
    if ($overwritehastree) {
        $hastree = false;
    } else {
        $hasTree = db_fieldexists($table, 'parent_id');
    }

    foreach ($ids as $id) {
        if ($hasDeletefield) {
            $count += listUndeleteAction($table, $id, 0, $hasTree, db_nullable($table, 'deleted'));
        }
    }

    if ($count) {
        return [true, $translate['cms_list_undeletesuccess'], false];
    }

    return [false, $translate['cms_list_undeleteerror'], false];
}

function listUnDeleteAction($table, $id, $count = 0, $recursive = false, $null = true)
{
    $result = db_query('UPDATE '.$table.' SET deleted = '.($null ? 'NULL' : '0').', modified = NOW(), modified_by = :user_id WHERE id = :id', ['id' => $id, 'user_id' => $_SESSION['user']['id']]);
    $count += $result->rowCount();
    if ($result->rowCount()) {
        simpleSaveChangeHistory($table, $id, 'Record recovered');
    }

    if ($recursive) {
        $childs = db_array('SELECT id FROM '.$table.' WHERE parent_id = :id', ['id' => $id]);
        foreach ($childs as $child) {
            $count += listUnDeleteAction($table, $child['id'], $count, true);
        }
    }

    return $count;
}

function listBulkUndelete($table, $ids, $uri = false, $overwritehastree = false)
{
    global $translate, $action;

    $hasDeletefield = db_fieldexists($table, 'deleted');
    $hasPrevent = db_fieldexists($table, 'preventdelete');
    if ($overwritehastree) {
        $hastree = false;
    } else {
        $hasTree = db_fieldexists($table, 'parent_id');
    }

    $count = listBulkUndeleteAction($table, $ids, 0, $hasTree);

    if ($count) {
        return [true, $translate['cms_list_undeletesuccess'], false];
    }

    return [false, $translate['cms_list_undeleteerror'], false];
}

function listBulkUnDeleteAction($table, $ids, $count = 0, $hasTree = false)
{
    [$finalIds, $count] = db_transaction(function () use ($table, $ids, $count, $hasTree) {
        $finalIds = [];
        foreach ($ids as $id) {
            $result = db_query('UPDATE '.$table.' SET deleted = 0, modified = NOW(), modified_by = :user_id WHERE id = :id', ['id' => $id, 'user_id' => $_SESSION['user']['id']]);
            $count += $result->rowCount();
            if ($result->rowCount()) {
                $finalIds[] = $id;
            }

            if ($hasTree) {
                $childs = db_array('SELECT id FROM '.$table.' WHERE parent_id = :id', ['id' => $id]);
                foreach ($childs as $child) {
                    $result = db_query('UPDATE '.$table.' SET deleted = 0, modified = NOW(), modified_by = :user_id WHERE id = :id', ['id' => $child['id'], 'user_id' => $_SESSION['user']['id']]);
                    $count += $result->rowCount();
                    if ($result->rowCount()) {
                        $finalIds[] = $child['id'];
                    }
                }
            }
        }

        return [$finalIds, $count];
    });

    simpleBulkSaveChangeHistory($table, $finalIds, 'Record recovered');

    return $count;
}

function listExtend($table, $ids, $period)
{
    global $translate;

    $count = 0;

    $hasExpireDate = db_fieldexists($table, 'valid_lastday');
    $updatedValue = [];
    foreach ($ids as $id) {
        if ($hasExpireDate) {
            $updatedCount = listExtendAction($table, $id, $period);
            $count += $updatedCount;
            if ($updatedCount > 0) {
                $getUpdatedValue = 'SELECT valid_lastday from cms_users where id = '.$id;
                $updatedValue[] = db_value($getUpdatedValue);
            }
        }
    }
    if ($count) {
        return [true, $translate['cms_list_extendsuccess'], false, $updatedValue];
    }

    return [false, $translate['cms_list_extenderror'], false];
}

function listExtendAction($table, $id, $period)
{
    $count = 0;
    $extendQuery = match ($period) {
        0 => 'UPDATE '.$table.' SET valid_lastday = DATE_ADD(IF(valid_lastday AND NOT valid_lastday IS NULL AND valid_lastday > CURDATE(), valid_lastday, CURDATE()),  INTERVAL 1 WEEK) WHERE id = :id;',
        1 => 'UPDATE '.$table.' SET valid_lastday = DATE_ADD(IF(valid_lastday AND NOT valid_lastday IS NULL AND valid_lastday > CURDATE(), valid_lastday, CURDATE()), INTERVAL 1 MONTH) WHERE id = :id;',
        2 => 'UPDATE '.$table.' SET valid_lastday = DATE_ADD(IF(valid_lastday AND NOT valid_lastday IS NULL AND valid_lastday > CURDATE(), valid_lastday, CURDATE()), INTERVAL 2 MONTH) WHERE id = :id;',
        default => 'UPDATE '.$table." SET valid_lastday = '0000-00-00 00:00:00' WHERE id = :id;",
    };
    $result = db_query($extendQuery, ['id' => $id]);

    $count += $result->rowCount();
    if ($count) {
        simpleSaveChangeHistory($table, $id, 'Record extended');
    }

    return $count;
}

function listCopy($table, $ids, $field)
{
    global $translate;

    $fieldexists = db_fieldexists($table, $field);

    if (!$fieldexists) {
        return [false, $translate['cms_list_copyfailure'], true];
    }

    listCopy_single($ids, $table, $field);

    return [true, $translate['cms_list_copysuccess'], true];
}

function listCopy_single($ids, $table, $field = false)
{
    global $translate;
    $newids = [];

    foreach ($ids as $id) {
        $row = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $id]);

        array_shift($row);
        $keys = array_keys($row);
        $keys2 = [];

        if (in_array('seq', $keys)) {
            $row['seq'] = intval(db_value('SELECT seq FROM '.$table.' ORDER BY seq DESC LIMIT 1')) + 1;
        }
        foreach ($keys as $key) {
            $keys2[] = ':'.$key;
        }

        if ($field) {
            $row[$field] .= ' '.$translate['cms_list_copy_suffix'];
        }

        db_query('INSERT INTO '.$table.' ('.join(',', $keys).') VALUES ('.join(',', $keys2).')', $row);
        $newids[] = db_insertid();
    }

    return $newids;
}

function listShowHide($table, $ids, $show)
{
    global $settings, $translate;

    $hasVisible = db_fieldexists($table, 'visible');
    if (!$hasVisible) {
        return [false, 'Visible field does not exist'];
    }
    foreach ($ids as $id) {
        db_query('UPDATE '.$table.' SET visible = :show WHERE id = :id', ['id' => $id, 'show' => intval($show)]);

        if ($settings['inheritvisibility'] && db_fieldexists($table, 'parent_id')) {
            $result = db_query('SELECT id FROM '.$table.' WHERE parent_id = :id', ['id' => $id]);
            while ($row = db_fetch($result)) {
                listShowHide($table, [$row['id']], $show);
            }
        }
    }

    return [true, $translate['cms_list_showhidesuccess'], false];
}

function listSwitch($table, $field, $id)
{
    $hasField = db_fieldexists($table, $field);
    if (!$hasField) {
        return [false, 'Field does not exist'];
    }
    db_query('UPDATE '.$table.' SET '.$field.' = NOT '.$field.' WHERE id = :id', ['id' => $id]);
    $newvalue = db_value('SELECT '.$field.' FROM '.$table.' WHERE id = :id', ['id' => $id]);

    return [true, '', false, $newvalue];
}

function initlist()
{
    global $table, $listconfig, $data, $action, $translate, $thisfile;

    $hasTree = db_fieldexists($table, 'parent_id');
    $hasShowHide = db_fieldexists($table, 'visible');

    $listconfig['thisfile'] = $thisfile;

    $listconfig['origin'] = $action;
    $listconfig['edit'] = $action.'_edit';
    $listconfig['add'] = $translate['cms_list_add'];
    $listconfig['delete'] = $translate['cms_list_delete'];
    $listconfig['copy'] = $translate['cms_list_copy'];
    $listconfig['show'] = $translate['cms_list_show'];
    $listconfig['hide'] = $translate['cms_list_hide'];

    $listconfig['width'] = 12;
    $listconfig['maxlimit'] = null;

    $listconfig['maxheight'] = 'window';

    $hasSeq = db_fieldexists($table, 'seq');

    $listconfig['orderby'] = '';
    $listconfig['allowmove'] = $hasSeq;
    $listconfig['allowmovefrom'] = 0;
    $listconfig['allowmoveto'] = 9999;
    $listconfig['allowsort'] = !$hasSeq;
    $listconfig['allowsort'] = false;

    $listconfig['allowadd'] = true;
    $listconfig['allowdelete'] = true;
    $listconfig['allowshowhide'] = $hasShowHide;
    $listconfig['allowselectall'] = true;
    $listconfig['allowselect'] = true;
    $listconfig['allowselectinvisible'] = true;

    $listconfig['tree'] = $hasTree;

    if ($_GET['resetsearch']) {
        unset($_SESSION['search'][$action]);
    }
    if (isset($_POST['search'])) {
        $listconfig['searchvalue'] = $_POST['search'];
        $_SESSION['search'][$action] = $listconfig['searchvalue'];
    } elseif ($_SESSION['search'][$action]) {
        $listconfig['searchvalue'] = $_SESSION['search'][$action];
    }

    if (isset($_POST['__multiplefilter'])) {
        if (isset($_POST['multiplefilter'])) {
            // sanitize input
            $_POST['multiplefilter'] = array_filter($_POST['multiplefilter'], 'ctype_digit');
            $listconfig['multiplefilter_selected'] = $_POST['multiplefilter'];
            $_SESSION['multiplefilter'][$action] = $listconfig['multiplefilter_selected'];
        } else {
            unset($_SESSION['multiplefilter'][$action]);
        }
    } elseif ($_SESSION['multiplefilter'][$action]) {
        $listconfig['multiplefilter_selected'] = $_SESSION['multiplefilter'][$action];
    }
}

function listsetting($set, $value)
{
    global $listconfig;
    $listconfig[$set] = $value;
}

function listfilter($options = [])
{
    global $listconfig, $action;

    if ($options['query']) {
        $options['options'] = db_simplearray($options['query']);
    }
    listsetting('filter', $options);

    if ($_GET['resetfilter']) {
        unset($_SESSION['filter'][$action]);
    }
    if ($_GET['filter']) {
        $listconfig['filtervalue'] = $_GET['filter'];
        $_SESSION['filter'][$action] = $listconfig['filtervalue'];
    } elseif ($_SESSION['filter'][$action]) {
        $listconfig['filtervalue'] = $_SESSION['filter'][$action];
    }
}

function listfilter4($options = [])
{
    global $listconfig, $action;

    if ($options['query']) {
        $options['options'] = db_simplearray($options['query']);
    }
    listsetting('filter4', $options);

    if ($_GET['resetfilter4']) {
        unset($_SESSION['filter4'][$action]);
    }
    if ($_GET['filter4']) {
        $listconfig['filtervalue4'] = $_GET['filter4'];
        $_SESSION['filter4'][$action] = $listconfig['filtervalue4'];
    } elseif ($_SESSION['filter4'][$action]) {
        $listconfig['filtervalue4'] = $_SESSION['filter4'][$action];
    }
}

function listfilter2($options = [])
{
    global $listconfig, $action;

    if ($options['query']) {
        $options['options'] = db_simplearray($options['query']);
    }
    listsetting('filter2', $options);

    if ($_GET['resetfilter2']) {
        unset($_SESSION['filter2'][$action]);
    }
    if ($_GET['filter2']) {
        $listconfig['filtervalue2'] = $_GET['filter2'];
        $_SESSION['filter2'][$action] = $listconfig['filtervalue2'];
    } elseif ($_SESSION['filter2'][$action]) {
        $listconfig['filtervalue2'] = $_SESSION['filter2'][$action];
    }
}

function listfilter3($options = [])
{
    global $listconfig, $action;

    if ($options['query']) {
        $options['options'] = db_simplearray($options['query']);
    }
    listsetting('filter3', $options);

    if ($_GET['resetfilter3']) {
        unset($_SESSION['filter3'][$action]);
    }
    if ($_GET['filter3']) {
        $listconfig['filtervalue3'] = $_GET['filter3'];
        $_SESSION['filter3'][$action] = $listconfig['filtervalue3'];
    } elseif ($_SESSION['filter3'][$action]) {
        $listconfig['filtervalue3'] = $_SESSION['filter3'][$action];
    }
}

function addbutton($code, $label, $options = [])
{
    global $listconfig;
    $listconfig['button'][$code] = ['label' => $label];
    foreach ($options as $key => $value) {
        $listconfig['button'][$code][$key] = $value;
    }
}

function addpagemenu($code, $label, $options = [])
{
    global $listconfig;
    $listconfig['pagemenu'][$code] = ['label' => $label];
    foreach ($options as $key => $value) {
        $listconfig['pagemenu'][$code][$key] = $value;
    }
}

// this is a huge SQL injection risk, as we're forcing consumers
// to pass $query with all parameters already supplied. WHY?
function getlistdata($query)
{
    return db_array(buildlistdataquery($query));
}

function gettreedata($query)
{
    $query = buildlistdataquery($query);
    $dataById = db_array($query, [], false, true);

    return convertListDataToTreeData($dataById);
}

function buildlistdataquery($query)
{
    global $table, $listconfig;

    $hasSeq = db_fieldexists($table, 'seq');
    $hasDeleted = db_fieldexists($table, 'deleted');

    $hasFilter = $listconfig['filtervalue'];
    $hasFilter2 = $listconfig['filtervalue2'];
    $hasFilter3 = $listconfig['filtervalue3'];
    $hasFilter4 = $listconfig['filtervalue4'];

    if ($hasDeleted && !stripos((string) $query, 'DELETED')) {
        $query = insertwhere($query, '(NOT '.$table.'.deleted OR '.$table.'.deleted IS NULL)');
    }

    if ($hasFilter && !$listconfig['manualquery']) {
        $query = insertwhere($query, $listconfig['filter']['filter'].'='.db_escape($hasFilter));
    }
    if ($hasFilter2 && !$listconfig['manualquery']) {
        $query = insertwhere($query, $listconfig['filter2']['filter'].'='.db_escape($hasFilter2));
    }
    if ($hasFilter3 && !$listconfig['manualquery']) {
        $query = insertwhere($query, $listconfig['filter3']['filter'].'='.db_escape($hasFilter3));
    }
    if ($hasFilter4 && !$listconfig['manualquery']) {
        $query = insertwhere($query, $listconfig['filter4']['filter'].'='.db_escape($hasFilter4));
    }

    if ($listconfig['searchvalue'] && !$listconfig['manualquery']) {
        if (is_iterable($listconfig['search'])) {
            foreach ($listconfig['search'] as $field) {
                $searchquery[] = '('.$field.' LIKE "%'.trim((string) $listconfig['searchvalue']).'%")';
            }
        }
        if ($searchquery) {
            $query = insertwhere($query, '('.join(' OR ', $searchquery).')');
        }
    }

    if ($hasSeq && !stripos((string) $query, 'ORDER BY') && !$listconfig['orderby']) {
        $query .= ' ORDER BY '.$table.'.seq';
    } elseif ($listconfig['orderby']) {
        $query .= ' ORDER BY '.$listconfig['orderby'];
    }

    if (!stripos((string) $query, 'LIMIT') && $listconfig['maxlimit']) {
        $query .= sprintf(' LIMIT %d', intval($listconfig['maxlimit']));
    }

    return $query;
}

// this returns data for tables that have an adjacency model with parent_id
// the data is sorted such that after any parent all its children immediately follow
// this is a stable sort, so any other sorting criteria will be preserved

// in addition to the normal row data, the following additional data items are added:
// for each row, record :
//   - a 'level' / depth, indexed from 0.
//       Typically used by the UI components.
//   - a 'path' of IDs to the ultimate parent, delimited by a /
//       This is just for visualization/debugging purposes
//   - a 'path' of to the ultimate parent, but using the original sort order index rather than the Id
//       this is what we will sort on so we have a stable sort, but ordered such that
//       after any parent all its children immediately follow
function convertListDataToTreeData($dataById)
{
    // Fetch the data into an associative array, so we can lookup by id
    // Also record the original row index so we can preserve the sort order
    function addOriginalIndexToData($dataById)
    {
        $index = 0;
        foreach ($dataById as &$row) {
            $row['original_index'] = ++$index;
        }

        return $dataById;
    }
    // we need to 'pad' the number so it's sortable as a string
    function padNumberAsString($num)
    {
        return sprintf('%08d', $num);
    }

    function buildRowMetaData($id, $dataById)
    {
        $level = -1;
        $path = '';
        $original_index_path = '';
        // we recurse 'up' the tree for as long as the parent_id exists
        // but prevent deep recursion and bomb out at 10 in case there is a cycle
        // and also prevent recursion where the parent_id = parent_id
        do {
            $row = $dataById[$id];
            ++$level;
            $path = '/'.$row['id'].$path;
            $original_index_path = '/'.padNumberAsString($row['original_index']).$original_index_path;
            $previous_id = $id;
            $id = $row['parent_id'];
        } while ('0' !== $id
            && $previous_id !== $id
            && array_key_exists($id, $dataById)
            && $level < 10);

        return ['level' => $level, 'path' => $path, 'original_index_path' => $original_index_path];
    }

    $dataById = addOriginalIndexToData($dataById);
    // Ideally we'd use a CTE for this, but Google Cloud SQL doesn't support
    // MySQL 8 yet (which is when this became supported) so instead we'll do
    // a poor man's version here and compute on the fly
    foreach ($dataById as &$row) {
        $metadata = buildRowMetaData($row['id'], $dataById);
        $row = array_merge($row, $metadata);
    }
    // sort the data
    usort($dataById, fn ($item1, $item2) => $item1['original_index_path'] <=> $item2['original_index_path']);

    return $dataById;
}

// inserts a where element into a new query or adds a new  element to an existing where-chain
function insertwhere($query, $where)
{
    $pos_order = strripos((string) $query, 'ORDER BY');
    $pos_group = strripos((string) $query, 'GROUP BY');
    $pos_where = strripos((string) $query, 'WHERE');
    if ($pos_group) { // voor het groupstatement
        if ($pos_where) {
            $query = query_insert($query, $pos_group, 'AND '.$where);
        } else {
            $query = query_insert($query, $pos_group, 'WHERE '.$where);
        }
    } elseif ($pos_order) { // gewoon erachter
        if ($pos_where) {
            $query = query_insert($query, $pos_order, 'AND '.$where);
        } else {
            $query = query_insert($query, $pos_order, 'WHERE '.$where);
        }
    } else { // gewoon erachter
        if ($pos_where) {
            $query .= ' AND '.$where;
        } else {
            $query .= ' WHERE '.$where;
        }
    }

    return $query;
}

function query_insert($str, $pos, $insert)
{
    return substr((string) $str, 0, $pos).$insert.' '.substr((string) $str, $pos);
}

function addcolumn($type, $label = false, $field = false, $array = [])
{
    global $listdata, $data;

    $listdata[$field] = ['type' => $type, 'label' => $label, 'field' => $field];

    foreach ($array as $key => $value) {
        $listdata[$field][$key] = $value;
    }

    if ($array['query']) {
        foreach ($data as $key => $value) {
            // $data[$key][$field] = db_value($array['query'],array('id'=>$data[$key][$field]));
            $data[$key][$field] = db_value($array['query'], ['id' => $data[$key]['id']]);
        }
    }

    if ('date' == $listdata[$field]['type']) {
        foreach ($data as $key => $row) {
            if ($row[$field] && strtotime((string) $row[$field]) > 0) {
                $data[$key][$field] = date('F j, Y', strtotime($row[$field]));
            } else {
                $data[$key][$field] = '';
            }
        }
        $listdata[$field]['type'] = 'text';
    } elseif ('datetime' == $listdata[$field]['type']) {
        foreach ($data as $key => $row) {
            if ($row[$field] && strtotime((string) $row[$field]) > 0) {
                $date = new DateTime($row[$field]);
                $data[$key][$field] = $date->format('j F Y, H:i');
            } else {
                $data[$key][$field] = '';
            }
        }
        $listdata[$field]['type'] = 'text';
    } elseif ('datetime-short' == $listdata[$field]['type']) {
        foreach ($data as $key => $row) {
            if ($row[$field] && strtotime((string) $row[$field]) > 0) {
                $dateTime = new DateTime((string) $row[$field]);
                $data[$key][$field] = $dateTime->format('H:i');
            } else {
                $data[$key][$field] = '';
            }
        }

        $listdata[$field]['type'] = 'text';
    } elseif ('time' == $listdata[$field]['type']) {
        foreach ($data as $key => $row) {
            if ($row[$field] && strtotime((string) $row[$field]) > 0) {
                $time = new DateTime((string) $row[$field]);
                $data[$key][$field] = $time->format('H:i');
            } else {
                $data[$key][$field] = '';
            }
        }
        $listdata[$field]['type'] = 'text';
    } elseif ($listdata[$field]['format'] && function_exists($listdata[$field]['format'])) {
        foreach ($data as $key => $row) {
            eval("\$data[{$key}][{$field}] = ".$listdata[$field]['format']."(\$row[{$field}]);");
        }
    }
}

function checkajax()
{
    global $action;
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' === strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH'])) {
        $ajax = true;

        $action = substr(__FILE__, strrpos(__FILE__, '/') + 1);
        $action = substr($action, 0, strrpos($action, '.'));
    } else {
        $ajax = false;
    }

    return $ajax;
}
