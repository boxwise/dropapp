<?php

    function addfield($type, $label = false, $field = false, $array = [])
    {
        if (!$field) {
            $field = uniqid();
        }
        global $formdata, $data, $translate;

        if ('url' == $field || strpos($field, '[url]')) {
            $type = 'url';
        }

        $formdata[$field] = ['type' => $type, 'label' => $label, 'field' => $field];

        if ('url' == $type && !$formdata[$field]['tooltip']) {
            $formdata[$field]['tooltip'] = $translate['tooltip_url'.($data['url_locked'] ? '_locked' : '')];
        }

        if ('list' == $type) {
            $formdata[$field]['data'] = getlistdata($array['query'], $array['parent_id']);

            // set default list behaviour, can be overridden by $array values
            $keys = array_keys($formdata[$field]['data'][0]);
            $hasShowHide = in_array('visible', $keys);
            $hasSeq = in_array('seq', $keys);

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
                    $formdata[$field]['selectedtags'] = explode(',', $array[0]['value']);
                } else {
                    $formdata[$field]['selectedtags'] = '';
                }
            }

            //if($key=='selectedtags') $formdata[$field]['selectedtags'] = db_simplearray($value);
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
            //if($key=='othertags') $formdata[$field]['othertags'] = db_simplearray($value);
        }

        if ($formdata[$field]['date'] && $formdata[$field]['time']) {
            if ($data[$field] && '0000-00-00 00:00:00' != $data[$field]) {
                $data[$field] = strftime('%d-%m-%Y %H:%M', strtotime($data[$field]));
            }
            $formdata[$field]['dateformat'] = 'DD-MM-YYYY H:mm';
        } elseif ($formdata[$field]['date']) {
            if ($data[$field] && '0000-00-00' != $data[$field]) {
                $data[$field] = strftime('%d-%m-%Y', strtotime($data[$field]));
            }
            $formdata[$field]['dateformat'] = 'DD-MM-YYYY';
        } elseif ($formdata[$field]['time']) {
            if ($data[$field]) {
                $data[$field] = strftime('%H:%M', strtotime($data[$field]));
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
            $formdata[$field]['icon'] = $icons[substr($data[$field], strrpos($data[$field], '.') + 1)];
            if (!$formdata[$field]['icon']) {
                $formdata[$field]['icon'] = 'o';
            }
            $formdata[$field]['basename'] = basename($data[$field]);

            //Create a JS friendly field ID
            $fieldid = str_replace('[', '_', $formdata[$field]['field']);
            $fieldid = str_replace(']', '', $fieldid);
            $formdata[$field]['fieldid'] = $fieldid;

            $formdata[$field]['preview'] = $data[$field];
            $aResize = unserialize(str_replace("'", '"', stripslashes($formdata[$field]['resizeproperties'])));
            foreach ($aResize as $resize) {
                if (true == $resize['preview']) {
                    $aPathInfo = pathinfo($_SERVER['DOCUMENT_ROOT'].$data[$field]);
                    $aImage = glob($_SERVER['DOCUMENT_ROOT'].$resize['target'].$aPathInfo['filename'].'.*');
                    $formdata[$field]['preview'] = $resize['target'].basename($aImage[0]);
                }
            }
        }

        if ('created' == $type) {
            if (strtotime($data['created'])) {
                $data['created'] = formatdate('%A %d %B %Y %H:%M', strtotime($data['created']));
                $data['created_by'] = getCMSuser($data['created_by']);
            }
            if (strtotime($data['modified'])) {
                $data['modified'] = formatdate('%A %d %B %Y %H:%M', strtotime($data['modified']));
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

    function getParentarray($table, $minlevel, $maxlevel, $field, $level = 0, $parent = 0)
    {
        global $settings, $translate;

        $hasDeleted = in_array('deleted', db_listfields($table));
        $hasSeq = in_array('seq', db_listfields($table));

        $lan = $settings['languages'][0]['id'];

        if (0 == $level && $minlevel < 1) {
            $parentarray[] = ['value' => 0, 'label' => $translate['cms_form_selectroot'], 'level' => $level, 'disabled' => ($minlevel > 0)];
        }

        $result = db_query('SELECT a.id, a.'.$field.', a.parent_id FROM '.$table.' AS a WHERE a.parent_id = '.$parent.($hasDeleted ? ' AND NOT a.deleted' : '').' ORDER BY '.($hasSeq ? 'a.seq' : 'a.menutitle ASC'));

        while ($row = db_fetch($result)) {
            if ($level < $maxlevel) {
                $parentarray[] = [
                    'value' => $row['id'],
                    'parent_id' => $row['parent_id'],
                    'label' => $row[$field],
                    'disabled' => ($level < ($minlevel - 1)),
                    'level' => ($level) + 1, ];
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

    $output = str_replace('%A', $translate[strftime('%A', $date)], $output);
    $output = str_replace('%B', $translate[strftime('%B', $date)], $output);
    $output = strftime($output, $date);

    return $output;
}
