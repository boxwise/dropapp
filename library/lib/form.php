<?php

function addfield($type, $label = false, $field = false, $array = [])
{
    if (!$field) {
        $field = uniqid();
    }
    global $formdata, $data, $translate;

    if ('url' == $field || strpos((string) $field, '[url]')) {
        $type = 'url';
    }

    $formdata[$field] = ['type' => $type, 'label' => $label, 'field' => $field];

    if ('url' == $type && !$formdata[$field]['tooltip']) {
        $formdata[$field]['tooltip'] = $translate['tooltip_url'.($data['url_locked'] ? '_locked' : '')];
    }

    if ('list' == $type) {
        if ($array['query']) {
            $formdata[$field]['data'] = getlistdata($array['query']);
        }

        // set default list behaviour, can be overridden by $array values
        $keys = [];
        $hasShowHide = false;
        $hasSeq = false;

        if (isset($formdata[$field]['data'][0])) {
            $keys = array_keys($formdata[$field]['data'][0]);
            $hasShowHide = in_array('visible', $keys);
            $hasSeq = in_array('seq', $keys);
        }

        $formdata[$field]['allowshowhide'] = $hasShowHide;

        $formdata[$field]['add'] = $translate['cms_list_add'];
        $formdata[$field]['delete'] = $translate['cms_list_delete'];
        $formdata[$field]['copy'] = $translate['cms_list_copy'];
        $formdata[$field]['show'] = $translate['cms_list_show'];
        $formdata[$field]['hide'] = $translate['cms_list_hide'];

        $formdata[$field]['orderby'] = '';
        $formdata[$field]['allowmove'] = $hasSeq;
        $formdata[$field]['allowsort'] = true;

        $formdata[$field]['allowadd'] = true;
        $formdata[$field]['allowdelete'] = true;
        $formdata[$field]['allowselectall'] = true;
        $formdata[$field]['allowselect'] = true;

        foreach ($array as $key => $value) {
            $formdata[$field][$key] = $value;
        }
    }

    foreach ($array as $key => $value) {
        $formdata[$field][$key] = $value;
        if ('array' == $key) {
            $formdata[$field]['options'] = $value;
        }
        if ('query' == $key) {
            $formdata[$field]['options'] = db_array($value);
        }
        if ('selectedtags' == $key) {
            $array = db_array($value);
            if ($array) {
                $formdata[$field]['selectedtags'] = explode(',', (string) $array[0]['value']);
            } else {
                $formdata[$field]['selectedtags'] = '';
            }
        }

        // if($key=='selectedtags') $formdata[$field]['selectedtags'] = db_simplearray($value);
        if ('othertags' == $key) {
            $array = db_array($value);
            $values = '';
            foreach ($array as $key => $value) {
                if ('' != $value['value']) {
                    if ($key > 0) {
                        $value['value'] = ','.$value['value'];
                    }
                    $values .= $value['value'];
                }
            }
            $formdata[$field]['othertags'] = array_unique(explode(',', $values));
            natcasesort($formdata[$field]['othertags']);
        }
        // if($key=='othertags') $formdata[$field]['othertags'] = db_simplearray($value);
    }

    if ($formdata[$field]['date'] && $formdata[$field]['time']) {
        if ($data[$field] && '0000-00-00 00:00:00' != $data[$field]) {
            $data[$field] = (new DateTime((string) $data[$field]))->format('d-m-Y H:i');
        }
        $formdata[$field]['dateformat'] = 'DD-MM-YYYY H:mm';
    } elseif ($formdata[$field]['date']) {
        if ($data[$field] && '0000-00-00' != $data[$field]) {
            $data[$field] = (new DateTime((string) $data[$field]))->format('d-m-Y');
        }
        $formdata[$field]['dateformat'] = 'DD-MM-YYYY';
    } elseif ($formdata[$field]['time']) {
        if ($data[$field]) {
            $data[$field] = (new DateTime((string) $data[$field]))->format('d-m-Y');
        }
        $formdata[$field]['dateformat'] = 'H:mm';
    }
    /*
            if(($formdata[$field]['date'] || $formdata[$field]['time']) && strtotime($data[$field])<=0) {
                $data[$field] = "";
            }
    */
    if ('fileselect' == $type) {
        $icons = ['doc' => 'word', 'docx' => 'word', 'mp3' => 'sound', 'pdf' => 'pdf-o', 'ppt' => 'powerpoint', 'txt' => 'text', 'xls' => 'excel', 'xlsx' => 'excel', 'zip' => 'zip-o'];
        $formdata[$field]['icon'] = $icons[substr((string) $data[$field], strrpos((string) $data[$field], '.') + 1)];
        if (!$formdata[$field]['icon']) {
            $formdata[$field]['icon'] = 'o';
        }
        $formdata[$field]['basename'] = basename((string) $data[$field]);

        // Create a JS friendly field ID
        $fieldid = str_replace('[', '_', (string) $formdata[$field]['field']);
        $fieldid = str_replace(']', '', $fieldid);
        $formdata[$field]['fieldid'] = $fieldid;

        $formdata[$field]['preview'] = $data[$field];
        $aResize = unserialize(str_replace("'", '"', stripslashes((string) $formdata[$field]['resizeproperties'])));
        foreach ($aResize as $resize) {
            if (true == $resize['preview']) {
                $aPathInfo = pathinfo($_SERVER['DOCUMENT_ROOT'].$data[$field]);
                $aImage = glob($_SERVER['DOCUMENT_ROOT'].$resize['target'].$aPathInfo['filename'].'.*');
                $formdata[$field]['preview'] = $resize['target'].basename($aImage[0]);
            }
        }
    }

    if ('created' == $type) {
        if (strtotime((string) $data['created'])) {
            $data['created'] = formatdate('%A %d %B %Y %H:%M', strtotime((string) $data['created']));
            $data['created_by'] = getCMSuser($data['created_by']);
        }
        if (strtotime((string) $data['modified'])) {
            $data['modified'] = formatdate('%A %d %B %Y %H:%M', strtotime((string) $data['modified']));
            $data['modified_by'] = getCMSuser($data['modified_by']);
        }
    }

    if ('shopping_cart' == $type) {
        $formdata[$field]['allowshowhide'] = false;
        $formdata[$field]['orderby'] = false;
        $formdata[$field]['allowmove'] = false;
        $formdata[$field]['allowsort'] = false;
        $formdata[$field]['allowadd'] = false;
        $formdata[$field]['allowdelete'] = false;
        $formdata[$field]['allowselectall'] = false;
        $formdata[$field]['allowselect'] = false;

        foreach ($array as $key => $value) {
            $formdata[$field][$key] = $value;
        }
    }
}

function addformbutton($action, $label)
{
    global $formbuttons;
    $formbuttons[] = ['action' => $action, 'label' => $label];
}

function getParentarray($table, $minlevel, $maxlevel, $field, $level = 0, $parent = null)
{
    global $settings, $translate;

    $hasDeleted = in_array('deleted', db_listfields($table));
    $hasSeq = in_array('seq', db_listfields($table));

    $lan = $settings['languages'][0]['id'];

    $result = db_query('SELECT a.id, a.'.$field.', a.parent_id FROM '.$table.' AS a WHERE a.parent_id '.($parent ? '= :parent_id' : 'IS NULL').($hasDeleted ? ' AND NOT a.deleted' : '').' ORDER BY '.($hasSeq ? 'a.seq' : 'a.menutitle ASC'), ['parent_id' => $parent]);

    while ($row = db_fetch($result)) {
        if ($level < $maxlevel) {
            $parentarray[] = [
                'value' => $row['id'],
                'parent_id' => $row['parent_id'],
                'label' => $row[$field],
                'disabled' => ($level < ($minlevel - 1)),
                'level' => $level + 1, ];
        }
        if ($maxlevel > $level) {
            $sub = getParentarray($table, $minlevel, $maxlevel, $field, $level + 1, $row['id']);
        }
        foreach ($sub as $array) {
            array_push($parentarray, $array);
        }
    }

    return $parentarray;
}

function formatdate($output, $date)
{
    global $translate;

    $output = str_replace('%A', $translate[(new DateTime('@'.$date))->format('l')], (string) $output);
    $output = str_replace('%B', $translate[(new DateTime('@'.$date))->format('F')], $output);
    $output = (new DateTimeImmutable())->setTimestamp($date)->format('Y-m-d H:i:s');

    return $output;
}
