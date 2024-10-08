<?php

class formHandler
{
    private $id;
    private $post;
    private $keys = [];
    private $nullIfEmpty = [];
    private $debug = false;

    public function __construct(private $table)
    {
        $this->post = $_POST;
        $this->id = intval($_POST['id']);
    }

    public function savePost($keys, $nullIfEmptyKeys = [], $saveHistory = true)
    {
        $this->nullIfEmpty = array_fill_keys($nullIfEmptyKeys, null);
        $this->keys = $keys;
        $this->saveCreatedModified();

        $this->modifyData($this->keys);
        $this->buildQuery($saveHistory);

        return $this->id;
    }

    public function modifyData(&$keys, $lan = false): void
    {
        foreach ($keys as $key) {
            if ($lan) {
                $value = $this->post[$lan][$key];
                $properties = explode(' ', (string) $this->post['__'.$lan][$key]);
            } else {
                $value = $this->post[$key];
                $properties = explode(' ', (string) $this->post['__'.$key]);
            }
            foreach ($properties as $p) {
                switch ($p) {
                    case 'checkbox':
                        $value = intval($value);

                        break;

                    case 'valuta':
                        $value = str_replace('€', '', (string) $value);
                        $value = str_replace('.', '', $value); // remove thousands indicator
                        $value = str_replace(',', '.', $value); // change decimal into point

                        break;

                    case 'float':
                        $value = str_replace('.', '', (string) $value); // remove thousands indicator
                        $value = str_replace(',', '.', $value); // change decimal into point

                        break;

                    case 'select':
                        if (!in_array('multiple', $properties)) {
                            $value = $value[0];
                        }

                        break;

                    case 'date':
                        if ($value) {
                            $value = (new DateTime((string) $value))->format('Y-m-d H:i:s');
                        }

                        break;

                    case 'readonly':
                        $keys = array_diff($keys, [$key]);

                        break;

                    case 'fileselect':
                        if (in_array('image', $properties) && $value) {
                            $resizeproperties = ($lan ? $this->post['__'.$lan][$key.'resize'] : $this->post['__'.$key.'resize']);
                            $aResize = unserialize(str_replace("'", '"', stripslashes((string) $resizeproperties)));
                            $imageResize = new imageResize();

                            foreach ($aResize as $aResizeItem) {
                                $aResizeItem['source'] = $_SERVER['DOCUMENT_ROOT'].$value;
                                $aResizeItem['reltarget'] = $aResizeItem['target'].basename((string) $value);
                                $aResizeItem['target'] = $_SERVER['DOCUMENT_ROOT'].$aResizeItem['target'].basename((string) $value);

                                // If the file already exists, append the target filename with -1
                                // This should be in a function that adds -1 as long as the filename exist. (Now only works once)
                                /*
                                                                if (file_exists($aResizeItem['target'])) {
                                                                    $aPathinfo = pathinfo($aResizeItem['target']);
                                                                    $aResizeItem['target'] = $aPathinfo['dirname'].'/'.$aPathinfo['filename'].'-1.'.$aPathinfo['extension'];
                                                                    $value = $aResize[0]['target'].$aPathinfo['filename'].'-1.'.$aPathinfo['extension'];
                                                                }
                                */

                                if (basename((string) $aResizeItem['source']) != basename((string) $this->post['file_prev'])) {
                                    $imageResize->setValue('source', $aResizeItem['source']);
                                    $imageResize->setValue('target', $aResizeItem['target']);
                                    $imageResize->setValue('resize', $aResizeItem['resize']);
                                    $imageResize->setValue('scaleup', $aResizeItem['scaleup']);
                                    $imageResize->setValue('quality', $aResizeItem['quality']);
                                    $imageResize->imageResize();
                                }
                            }

                            break;
                        }
                }
            }

            $value = stripslashes((string) $value);
            if (array_key_exists($key, $this->nullIfEmpty) && '' == $value) {
                $value = null;
            }

            if ($lan) {
                $this->post[$lan][$key] = $value;
            } else {
                $this->post[$key] = $value;
            }
        }
    }

    public function buildQuery($saveHistory): void
    {
        $updatequery = 'UPDATE '.$this->table.' SET ';
        $insertquery = 'INSERT INTO '.$this->table;

        $hasTree = db_fieldexists($this->table, 'parent_id');
        $hasSeq = db_fieldexists($this->table, 'seq');

        if ($hasSeq) {
            array_push($this->keys, 'seq');
            if (!$this->post['seq']) {
                $this->post['seq'] = intval(db_value('SELECT MAX(seq)+1 FROM '.$this->table.($hasTree ? ' WHERE parent_id = '.intval($this->post['parent_id']) : '')));
            }
        }

        foreach ($this->keys as $key) {
            $updatequeryfields[] = $key.' = :'.$key;
            $insertqueryfields[] = $key;
            $insertqueryfields2[] = ':'.$key;
            $queryvalues[$key] = $this->post[$key];
        }

        $updatequery .= join(', ', $updatequeryfields).' WHERE id = '.$this->id;
        $insertquery .= ' ('.join(', ', $insertqueryfields).') VALUES ('.join(', ', $insertqueryfields2).')';

        if ($this->id > 0) {
            if ($saveHistory) {
                $this->saveChangeHistory();
            }
            if ($this->debug) {
                dump($updatequery);
                dump(join(',', $queryvalues));
            } else {
                db_query($updatequery, $queryvalues);
            }
        } else {
            if ($this->debug) {
                dump($insertquery);
                dump(join(',', $queryvalues));
            } else {
                db_query($insertquery, $queryvalues);
                $this->id = db_insertid();
                if ($saveHistory) {
                    $this->saveNewrecordInHistory();
                }
            }
        }
    }

    public function saveNewrecordInHistory(): void
    {
        if (!db_tableexists('history')) {
            return;
        }

        db_query('INSERT INTO history (tablename, record_id, changes, user_id, ip, changedate) VALUES (:table,:id,:change,:user_id,:ip,NOW())', ['table' => $this->table, 'id' => $this->id, 'change' => 'Record created', 'user_id' => $_SESSION['user']['id'], 'ip' => $_SERVER['REMOTE_ADDR']]);
    }

    public function saveChangeHistory(): void
    {
        // if no history table is present, ignore this function
        if (!db_tableexists('history')) {
            return;
        }
        $result = db_query('SHOW COLUMNS FROM '.$this->table);
        while ($field = db_fetch($result)) {
            $fields[$field['Field']] = $field;
        }

        $old = db_row('SELECT * FROM '.$this->table.' WHERE id = :id', ['id' => $this->id]);
        foreach ($this->keys as $key) {
            if (!in_array($key, ['created', 'created_by', 'modified', 'modified_by'])) {
                $new = $this->post[$key];
                $change = '';

                if ('date' == $fields[$key]['Type']) {
                    $new = (new DateTime((string) $new))->format('Y-m-d');

                    if ($new = '1970-01-01') {
                        $new = $old[$key];
                    }
                }
                if ('float' == $fields[$key]['Type'] && 'NO' == $fields[$key]['Null']) {
                    $new = floatval($new);
                }
                if (str_starts_with((string) $fields[$key]['Type'], 'int') && 'NO' == $fields[$key]['Null']) {
                    $new = intval($new);
                }

                if ($old[$key] != $new) {
                    if (str_starts_with((string) $fields[$key]['Type'], 'int') || str_starts_with((string) $fields[$key]['Type'], 'tinyint')) {
                        db_query('INSERT INTO history (tablename, record_id, changes, user_id, ip, changedate, from_int, to_int) VALUES (:table,:id,:change,:user_id,:ip,NOW(), :old, :new)', ['table' => $this->table, 'id' => $this->id, 'change' => $key, 'user_id' => $_SESSION['user']['id'], 'ip' => $_SERVER['REMOTE_ADDR'], 'old' => $old[$key], 'new' => $new]);
                    } elseif ('float' == $fields[$key]['Type']) {
                        db_query('INSERT INTO history (tablename, record_id, changes, user_id, ip, changedate, from_float, to_float) VALUES (:table,:id,:change,:user_id,:ip,NOW(), :old, :new)', ['table' => $this->table, 'id' => $this->id, 'change' => $key, 'user_id' => $_SESSION['user']['id'], 'ip' => $_SERVER['REMOTE_ADDR'], 'old' => $old[$key], 'new' => $new]);
                    } else {
                        $change .= $key.' changed from "'.$old[$key].'" to "'.$new.'"; ';
                        db_query('INSERT INTO history (tablename, record_id, changes, user_id, ip, changedate) VALUES (:table,:id,:change,:user_id,:ip,NOW())', ['table' => $this->table, 'id' => $this->id, 'change' => $change, 'user_id' => $_SESSION['user']['id'], 'ip' => $_SERVER['REMOTE_ADDR']]);
                    }
                }
            }
        }
    }

    public function saveMultiple($field, $table, $here, $there): void
    {
        db_query('DELETE FROM '.$table.' WHERE '.$here.' = :'.$here, [$here => $this->id]);

        foreach ($this->post[$field] as $value) {
            db_query('INSERT INTO '.$table.' SET '.$here.' = :'.$here.', '.$there.' = :'.$there, [$here => $this->id, $there => $value]);
        }
    }

    public function saveCreatedModified(): void
    {
        if (!$this->id) {
            array_push($this->keys, 'created', 'created_by');
            $this->post['created'] = (new DateTime())->format('Y-m-d H:i:s');
            $this->post['created_by'] = $_SESSION['user']['id'];
        } else {
            array_push($this->keys, 'modified', 'modified_by');
            $this->post['modified'] = (new DateTime())->format('Y-m-d H:i:s');
            $this->post['modified_by'] = $_SESSION['user']['id'];
        }
    }

    public function makeURL($field): void
    {
        global $settings;

        $value = $this->post[$field];
        $url = safestring(trim((string) $value));
        while (strpos((string) $url, '--')) {
            $url = str_replace('--', '-', (string) $url);
        }
        if (str_ends_with((string) $url, '-')) {
            $url = substr((string) $url, 0, strlen((string) $url) - 1);
        }

        $url = $this->makeURL_suffix($url);

        if (!$this->post['url']) {
            $this->post['url'] = $url;
        } else {
            $this->post['url'] = safestring($this->post['url']);
            while (strpos((string) $this->post['url'], '--')) {
                $this->post['url'] = str_replace('--', '-', (string) $this->post['url']);
            }
            if (str_ends_with((string) $url, '-')) {
                $url = substr((string) $url, 0, strlen((string) $url) - 1);
            }
        }
    }

    public function makeURLvalue($value): void
    {
        global $settings;

        $url = safestring(trim((string) $value));
        $url = $this->makeURL_suffix($url);

        if (!$this->post['url']) {
            $this->post['url'] = $url;
        } else {
            $this->post['url'] = safestring($this->post['url']);
        }
    }

    public function makeURL_suffix($url)
    {
        $fields = db_listfields($this->table);
        if (in_array('deleted', $fields)) {
            $deleted = true;
        }

        preg_match_all('^([a-zA-Z0-9_-]+)\-([0-9]+)^', (string) $url, $match);
        if ($match[0]) {
            $nr = $match[2][0];
            $base = $match[1][0];
        } else {
            $base = $url;
        }

        $exists = db_numrows('SELECT url FROM '.$this->table.' AS p WHERE p.id != :id AND url = :url '.($deleted ? ' AND NOT deleted' : ''), ['id' => $this->post['id'], 'url' => $url]);
        if ($exists) {
            $url = $base.'-'.($nr + 1);
            $url = $this->makeURL_suffix($url);
        }

        return $url;
    }
}
