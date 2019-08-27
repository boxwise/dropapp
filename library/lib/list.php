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

    foreach ($ids as $line) {
        ++$i;
        list($id, $level) = $line;
        $parent[$level] = $id;
        ++$seq[$level];

        if ($hasParent) {
            $parent_id = ($level ? $parent[$level - 1] : 0);
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

function listRealDelete($table, $ids, $uri = false)
{
    global $translate, $action;

    $hasPrevent = db_fieldexists($table, 'preventdelete');
    $hasTree = db_fieldexists($table, 'parent_id');

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

function listDelete($table, $ids, $uri = false)
{
    global $translate, $action;

    $hasDeletefield = db_fieldexists($table, 'deleted');
    $hasPrevent = db_fieldexists($table, 'preventdelete');
    $hasTree = db_fieldexists($table, 'parent_id');

    try {
        foreach ($ids as $id) {
            if ($hasDeletefield) {
                // Does the db have foreign keys?
                $foreignkeys = db_referencedforeignkeys($table);
                if (count($foreignkeys) > 0) {
                    foreach ($foreignkeys as $foreignkey) {
                        // Do the foreign keys restrict the delete?
                        if ('RESTRICT' == $foreignkey['DELETE_RULE']) {
                            $restricted = db_array('
							SELECT b.id'.(db_fieldexists($foreignkey['TABLE_NAME'], 'label') ? ', b.label' : (db_fieldexists($foreignkey['TABLE_NAME'], 'naam') ? ', b.naam AS label' : (db_fieldexists($foreignkey['TABLE_NAME'], 'name') ? ', b.name AS label' : ' AS label'))).'
							FROM '.$table.' a, '.$foreignkey['TABLE_NAME'].' b 
							WHERE a.'.$foreignkey['REFERENCED_COLUMN_NAME'].' = b.'.$foreignkey['COLUMN_NAME'].' AND '.(db_fieldexists($foreignkey['TABLE_NAME'], 'deleted') ? '(NOT b.deleted OR b.deleted IS NULL) AND ' : '').' a.id = :id', ['id' => $id]);
                            if (count($restricted)) {
                                return [false, 'The entry '.($restricted[0]['label'] ? $restricted[0]['label'] : $restricted[0]['id'].' of the table '.$foreignkey['TABLE_NAME']).' is still linked to this item.<br>Please edit or delete it first.', false];
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

function listUndelete($table, $ids, $uri = false)
{
    global $translate, $action;

    $hasDeletefield = db_fieldexists($table, 'deleted');
    $hasPrevent = db_fieldexists($table, 'preventdelete');
    $hasTree = db_fieldexists($table, 'parent_id');

    foreach ($ids as $id) {
        if ($hasDeletefield) {
            $count += listUndeleteAction($table, $id, 0, $hasTree);
        }
    }

    if ($count) {
        return [true, $translate['cms_list_undeletesuccess'], false];
    }

    return [false, $translate['cms_list_undeleteerror'], false];
}

function listUnDeleteAction($table, $id, $count = 0, $recursive = false)
{
    $result = db_query('UPDATE '.$table.' SET deleted = 0, modified = NOW(), modified_by = :user_id WHERE id = :id', ['id' => $id, 'user_id' => $_SESSION['user']['id']]);
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

function listCopy($table, $ids, $field)
{
    global $translate;

    $hasSubtable = db_tableexists($table.'_content');
    $fieldexists = db_fieldexists($table, $field);
    $hasChildren = db_fieldexists($table, 'parent_id');

    if (!$fieldexists) {
        return [false, $translate['cms_list_copyfailure'], true];
    }

    if ($hasSubtable) {
        listCopy_multilanguage($ids, $table, $field);
    } else {
        listCopy_single($ids, $table, $field);
    }

    return [true, $translate['cms_list_copysuccess'], true];
}

function listCopy_multilanguage($ids, $table, $field = false, $newparent = false)
{
    global $translate;

    foreach ($ids as $id) {
        $row = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $id]);

        array_shift($row);
        $keys = array_keys($row);
        $keys2 = [];

        if (in_array('seq', $keys)) {
            $row['seq'] = intval(db_value('SELECT seq FROM '.$table.' ORDER BY seq DESC LIMIT 1')) + 1;
        }
        if ($newparent) {
            $row['parent_id'] = $newparent;
        }
        foreach ($keys as $key) {
            $keys2[] = ':'.$key;
        }

        db_query('INSERT INTO '.$table.' ('.join(',', $keys).') VALUES ('.join(',', $keys2).')', $row);
        $new = db_insertid();

        $result = db_query('SELECT * FROM '.$table.'_content WHERE table_id = :id', ['id' => $id]);
        while ($subrow = db_fetch($result)) {
            array_shift($subrow);
            $subkeys = array_keys($subrow);
            $subkeys2 = [];
            $subrow['table_id'] = $new;
            foreach ($subkeys as $subkey) {
                $subkeys2[] = ':'.$subkey;
            }
            if ($field) {
                $subrow[$field] .= ' '.$translate['cms_list_copy_suffix'];
            }
            db_query('INSERT INTO '.$table.'_content ('.join(',', $subkeys).') VALUES ('.join(',', $subkeys2).')', $subrow);
        }

        $result = db_query('SELECT * FROM '.$table.' WHERE parent_id = :id', ['id' => $id]);
        while ($child = db_fetch($result)) {
            listCopy_multilanguage([$child['id']], $table, $field, $new);
        }
    }

    return $newids;
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
    global $settings;

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

function getlistdata($query, $parent = 0)
{
    global $table, $settings, $listconfig, $action;

    $hasTree = db_fieldexists($table, 'parent_id');
    $hasSeq = db_fieldexists($table, 'seq');
    $hasDeleted = db_fieldexists($table, 'deleted');

    $hasFilter = $listconfig['filtervalue'];
    $hasFilter2 = $listconfig['filtervalue2'];
    $hasFilter3 = $listconfig['filtervalue3'];
    $hasFilter4 = $listconfig['filtervalue4'];

    $hasSubtable = db_tableexists($table.'_content');

    $lan = $settings['languages'][0]['id'];

    if ($hasSubtable) {
        $subfields = db_listfields($table.'_content');
        array_shift($subfields);

        $pos = stripos($query, ' FROM '.$table) + strlen(' FROM '.$table);
        $subquery = '
				LEFT OUTER JOIN '.$table.'_content
					ON '.$table.'_content.table_id = '.$table.'.id
					AND '.$table.'_content.lan = '.$lan;

        $query = 'SELECT '.$table.'.*, '.$table.'_content.'.join(',', $subfields).' FROM '.$table.' '.$subquery.' '.substr($query, $pos);
    }

    if ($hasTree) {
        $query = insertwhere($query, 'parent_id = :parent_id');
    }

    if ($hasDeleted && !stripos($query, 'DELETED')) {
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
        foreach ($listconfig['search'] as $field) {
            $searchquery[] = '('.$field.' LIKE "%'.trim($listconfig['searchvalue']).'%")';
        }

        if ($searchquery) {
            $query = insertwhere($query, '('.join(' OR ', $searchquery).')');
        }
    }

    if ($hasSeq && !stripos($query, 'ORDER BY') && !$listconfig['orderby']) {
        $query .= ' ORDER BY '.$table.'.seq';
    } elseif ($listconfig['orderby']) {
        $query .= ' ORDER BY '.$listconfig['orderby'];
    }

    $data = listdataquery($query, 0, $parent);

    return $data;
}

// inserts a where element into a new query or adds a new  element to an existing where-chain
function insertwhere($query, $where)
{
    $pos_order = strripos($query, 'ORDER BY');
    $pos_group = strripos($query, 'GROUP BY');
    $pos_where = strripos($query, 'WHERE');
    if ($pos_group) { //voor het groupstatement
        if ($pos_where) {
            $query = query_insert($query, $pos_group, 'AND '.$where);
        } else {
            $query = query_insert($query, $pos_group, 'WHERE '.$where);
        }
    } elseif ($pos_order) { //gewoon erachter
        if ($pos_where) {
            $query = query_insert($query, $pos_order, 'AND '.$where);
        } else {
            $query = query_insert($query, $pos_order, 'WHERE '.$where);
        }
    } else { //gewoon erachter
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
    return substr($str, 0, $pos).$insert.' '.substr($str, $pos);
}

function listdataquery($query, $level = 0, $parent = 0)
{
    global $table;
    $hasTree = db_fieldexists($table, 'parent_id');

    $result = db_query($query, ($hasTree ? ['parent_id' => $parent] : []));

    while ($row = db_fetch($result)) {
        $row['level'] = $level;
        $data[] = $row;

        if ($hasTree) {
            $rowcount = db_value('SELECT COUNT(1) FROM '.$table.' WHERE parent_id = '.$row['id']);
            if ($rowcount > 0) {
                $sub = listdataquery($query, $level + 1, $row['id']);
                foreach ($sub as $field) {
                    array_push($data, $field);
                }
            }
        }
    }

    return $data;
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
            //$data[$key][$field] = db_value($array['query'],array('id'=>$data[$key][$field]));
            $data[$key][$field] = db_value($array['query'], ['id' => $data[$key]['id']]);
        }
    }

    if ('date' == $listdata[$field]['type']) {
        foreach ($data as $key => $row) {
            if ($row[$field] && strtotime($row[$field]) > 0) {
                $data[$key][$field] = strftime('%e %B %Y', strtotime($row[$field]));
            } else {
                $data[$key][$field] = '';
            }
        }
        $listdata[$field]['type'] = 'text';
    } elseif ('datetime' == $listdata[$field]['type']) {
        foreach ($data as $key => $row) {
            if ($row[$field] && strtotime($row[$field]) > 0) {
                $data[$key][$field] = strftime('%e %B %Y, %H:%M', strtotime($row[$field]));
            } else {
                $data[$key][$field] = '';
            }
        }
        $listdata[$field]['type'] = 'text';
    } elseif ('datetime-short' == $listdata[$field]['type']) {
        foreach ($data as $key => $row) {
            if ($row[$field] && strtotime($row[$field]) > 0) {
                $data[$key][$field] = strftime('%e-%m-%y %H:%M', strtotime($row[$field]));
            } else {
                $data[$key][$field] = '';
            }
        }
        $listdata[$field]['type'] = 'text';
    } elseif ('time' == $listdata[$field]['type']) {
        foreach ($data as $key => $row) {
            if ($row[$field] && strtotime($row[$field]) > 0) {
                $data[$key][$field] = strftime('%H:%M', strtotime($row[$field]));
            } else {
                $data[$key][$field] = '';
            }
        }
        $listdata[$field]['type'] = 'text';
    } elseif (function_exists($listdata[$field]['format'])) {
        foreach ($data as $key => $row) {
            eval("\$data[{$key}][{$field}] = ".$listdata[$field]['format']."(\$row[{$field}]);");
        }
    }
}

function checkajax()
{
    global $action;
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' === strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        $ajax = true;

        $action = substr(__FILE__, strrpos(__FILE__, '/') + 1);
        $action = substr($action, 0, strrpos($action, '.'));
    } else {
        $ajax = false;
    }

    return $ajax;
}
