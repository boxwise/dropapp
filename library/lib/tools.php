<?php

function getDirsRecursive($dir, &$results = [])
{
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = $dir.DIRECTORY_SEPARATOR.$value;
        $realpath = realpath($path);
        if (is_dir($realpath) && '.' != $value && '..' != $value) {
            getDirsRecursive($path, $results);
            $results[] = '/'.$path;
        }
    }

    return $results;
}

function checkURL($url)
{
    $headers = @get_headers($url);
    $headers = (is_array($headers)) ? implode("\n ", $headers) : $headers;

    return (bool) preg_match('#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers);
}

function checkEmail($email)
{
    [$user, $domain] = preg_split('/@/', (string) $email);

    return (bool) (filter_var($email, FILTER_VALIDATE_EMAIL) && checkdnsrr($domain, 'MX'));
}

function checkPasswordStrength($pass)
{
    return (bool) (
        strlen((string) $pass) >= 12
        // a regular expression to check at least 12 characters including at least 3 of the following 4 types of characters: a lower-case letter, an upper-case letter, a number, a special character (such as !@#$%^&*).
        && preg_match('/((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])|(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%&\/=?_.,:;\\\\-])|(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%&\/=?_.,:;\\\\-])|(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%&\/=?_.,:;\\\\-])).{12,}$/m', (string) $pass)
    );
}

function grammarRealign($row)
{
    $parts = explode(' ', (string) $row);
    $new_order = $parts;
    $new_order[0] = strtolower($parts[1]);
    $new_order[1] = strtolower($parts[0]);

    return join(' ', $new_order);
}

function showHistory($table, $id)
{
    global $translate;

    $smarty = new Zmarty();
    $history = [];

    $result = db_query('SELECT h.*, u.naam FROM history AS h LEFT OUTER JOIN cms_users AS u ON h.user_id = u.id WHERE tablename = :table AND record_id = :id ORDER BY changedate DESC', ['table' => $table, 'id' => $id]);
    while ($row = db_fetch($result)) {
        $row['changedate'] = (new DateTime($row['changedate']))->format('l d F Y, H:i');
        $row['changes'] = strip_tags((string) $row['changes']);
        $change = $row['changes'];
        $change = str_replace(';', '', $change);

        // cases for properly created change-messages(location_id, product_id,items...)
        if (in_array($change, ['location_id', 'product_id', 'items', 'size_id', 'box_state_id'])) {
            if ('items' == $change) {
                $change = 'changed the number of items from '.$row['from_int'].' to '.$row['to_int'];
            } elseif ('location_id' == $change) {
                $loc_ids = [$row['from_int'], $row['to_int']];
                $loc_orig = db_row('SELECT locations.label FROM locations WHERE locations.id = :id_orig', ['id_orig' => $loc_ids[0]]);
                $loc_dest = db_row('SELECT locations.label FROM locations WHERE locations.id = :id_dest', ['id_dest' => $loc_ids[1]]);
                $change = 'changed box location from '.$loc_orig['label'].' to '.$loc_dest['label'];
            } elseif ('product_id' == trim($change)) {
                $prod_ids = [$row['from_int'], $row['to_int']];
                $prod_orig = db_row('SELECT products.name FROM products WHERE products.id = :id_orig', ['id_orig' => $prod_ids[0]]);
                $prod_new = db_row('SELECT products.name FROM products WHERE products.id = :id_new', ['id_new' => $prod_ids[1]]);
                $change = 'changed product type from '.$prod_orig['name'].' to '.$prod_new['name'];
            } elseif ('size_id' == trim($change)) {
                $size_ids = [$row['from_int'], $row['to_int']];
                $size_orig = db_row('SELECT sizes.label FROM sizes WHERE sizes.id = :id_orig', ['id_orig' => $size_ids[0]]);
                $size_new = db_row('SELECT sizes.label FROM sizes WHERE sizes.id = :id_new', ['id_new' => $size_ids[1]]);
                $change = 'changed size from '.$size_orig['label'].' to '.$size_new['label'];
            } elseif ('box_state_id' == trim($change)) {
                $box_state_ids = [$row['from_int'], $row['to_int']];
                $box_state_orig = db_row('SELECT box_state.label FROM box_state WHERE box_state.id = :id_orig', ['id_orig' => $box_state_ids[0]]);
                $box_state_new = db_row('SELECT box_state.label FROM box_state WHERE box_state.id = :id_new', ['id_new' => $box_state_ids[1]]);
                $change = 'changed box state from '.$box_state_orig['label'].' to '.$box_state_new['label'];
            }
        }   // Cases where the grammar has to be realigned to make it readable
        elseif (in_array(explode(' ', $change)[0], ['Box', 'Record', 'comments', 'signaturefield'])) {
            // special cases first
            $change = trim((string) grammarRealign($change));
            if ('order box made undone' == $change) {
                $change = 'canceled the order';
            }
        } // old default cases if there exist still old messages that are uncovered by above handling
        elseif (!(is_null($row['to_int']) && is_null($row['to_float']))) {
            $change = ' changed' + $change;
            if (!is_null($row['from_int'])) {
                $change .= ' from '.$row['from_int'];
            } elseif (!is_null($row['from_float'])) {
                $change .= ' from '.$row['from_float'];
            }
            if (!is_null($row['to_int'])) {
                $change .= ' to '.$row['to_int'];
            } elseif (!is_null($row['to_float'])) {
                $change .= ' to '.$row['to_float'];
            }
        }

        $change .= '; ';
        $row['truncate'] = strlen($change) > 300;
        $row['changes'] = $change;
        $history[] = $row;
    }

    if (!(is_null($row['to_int']) && is_null($row['to_float']))) {
        $change = ' changed' + $change;
        if (!is_null($row['from_int'])) {
            $change .= ' from '.$row['from_int'];
        } elseif (!is_null($row['from_float'])) {
            $change .= ' from '.$row['from_float'];
        }
        if (!is_null($row['to_int'])) {
            $change .= ' to '.$row['to_int'];
        } elseif (!is_null($row['to_float'])) {
            $change .= ' to '.$row['to_float'];
        }
        $change .= '; ';
    }

    $smarty->assign('row', $history);

    return $smarty->fetch('cms_form_history.tpl', true);
    if (!$result->rowCount()) {
        return $translate['cms_form_history_nodata'];
    }
}

function ConvertURL()
{
    global $lan, $settings;
    $qs = $_SERVER['REQUEST_URI'];
    if (strpos((string) $qs, '?')) {
        $qs = substr((string) $qs, 0, strpos((string) $qs, '?'));
    }
    $items = explode('/', (string) $qs);
    if ($settings['site_multilanguage']) {
        $lan = $items[1];
        array_shift($items);
    }
    array_shift($items);
    // The first element is always empty because the querystring starts with a '/', so trash it
    /* we should have at least two items AND if more than 2 an even amount...
     * Otherwise go to the default page */
    $count = 0;
    foreach ($items as $item) {
        $_GET['get'.$count] = $item;
        ++$count;
    }
    if (!$lan) {
        $lan = $settings['cms_language'];
    }
}

function redirect($url, $status = 301): never
{
    header('Location: '.$url, true, $status);

    exit;
}

function CMSmenu()
{
    global $action, $lan;

    $result1 = db_query('SELECT f.* FROM cms_functions AS f WHERE f.visible AND f.parent_id IS NULL ORDER BY f.seq');
    while ($row1 = db_fetch($result1)) {
        $submenu = [];

        if ($_SESSION['user']['is_admin']) {
            // is an organisation selected?
            if ($_SESSION['organisation']['id']) {
                $result2 = db_query('
				SELECT f.*, title_'.$lan.' AS title 
				FROM cms_functions AS f 
				LEFT OUTER JOIN cms_functions_camps AS x2 ON x2.cms_functions_id = f.id 
				WHERE f.visible AND (x2.camps_id = :camp OR f.allusers OR f.allcamps) AND f.parent_id = :parent_id 
				GROUP BY f.id ORDER BY f.seq', ['camp' => $_SESSION['camp']['id'], 'parent_id' => $row1['id']]);
            } else {
                $result2 = db_query('
				SELECT f.*, title_en AS title 
				FROM cms_functions AS f 
				WHERE f.visible AND f.adminonly AND f.parent_id = :parent_id 
				ORDER BY f.seq', ['parent_id' => $row1['id']]);
            }
        } else {
            $result2 = db_query('
			SELECT f.*, title_en AS title 
			FROM (cms_functions AS f, cms_usergroups_functions AS uf, cms_functions_camps AS fc)
			WHERE uf.cms_functions_id = f.id AND uf.cms_usergroups_id = :usergroup AND ((fc.cms_functions_id = f.id AND fc.camps_id = :camp) OR f.allcamps) AND (f.parent_id = :parent_id)
			GROUP BY f.id 
			ORDER BY seq', ['camp' => $_SESSION['camp']['id'], 'parent_id' => $row1['id'], 'usergroup' => $_SESSION['usergroup']['id']]);
        }

        while ($row2 = db_fetch($result2)) {
            if ($row2['include'] == $action || $row2['include'].'_edit' == $action) {
                $row2['active'] = true;
            }
            if ($row2['title_'.$lan]) {
                $row2['title'] = $row2['title_'.$lan];
            }
            $submenu[] = $row2;
        }

        if ($row1['title_'.$lan]) {
            $row1['title'] = $row1['title_'.$lan];
        }
        $row1['sub'] = $submenu;
        if ($submenu) {
            $menu[] = $row1;
        }
    }

    return $menu;
}

function getCMSuser($id)
{
    return db_value('SELECT naam FROM cms_users WHERE id = :id', ['id' => $id]);
    // 	return '<a href="mailto:'.$user['email'].'">'.$user['naam'].'</a>';
}

function safestring($input)
{
    $safestringchar = '-';
    $input = str_replace(['!', '?', '&'], '', (string) $input);
    $input = mb_convert_encoding($input, 'ISO-8859-1');

    $x = '';
    for ($i = 0; $i < strlen($input); ++$i) {
        $c = ord($input[$i]);
        if (32 == $c) {
            $x .= $safestringchar;
        } elseif (95 == $c || ($c > 47 && $c < 58) || ($c > 64 && $c < 91) || ($c > 96 && $c < 123)) {
            $x .= chr($c);
        } elseif (in_array($input[$i], utf8_decode_array(['ä', 'á', 'à', 'â']))) {
            $x .= 'a';
        } elseif (in_array($input[$i], utf8_decode_array(['ë', 'é', 'è', 'ê']))) {
            $x .= 'e';
        } elseif (in_array($input[$i], utf8_decode_array(['ï', 'í', 'ì', 'î']))) {
            $x .= 'i';
        } elseif (in_array($input[$i], utf8_decode_array(['ö', 'ó', 'ò', 'ô']))) {
            $x .= 'o';
        } elseif (in_array($input[$i], utf8_decode_array(['ü', 'ú', 'ù', 'û']))) {
            $x .= 'u';
        } elseif (in_array($input[$i], utf8_decode_array(['ř']))) {
            $x .= 'r';
        } elseif (in_array($input[$i], utf8_decode_array(['ā']))) {
            $x .= $safestringchar;
        } elseif ($input[$i] == $safestringchar) {
            $x .= $safestringchar;
        }
    }

    $x = strtolower($x);
    if (str_ends_with($x, '-')) {
        $x = substr($x, 0, strlen($x) - 1);
    }

    return mb_convert_encoding($x, 'UTF-8', 'ISO-8859-1');
}

function utf8_decode_array($array)
{
    if (!is_array($array)) {
        return false;
    }
    foreach ($array as $key => $value) {
        $array[$key] = mb_convert_encoding((string) $value, 'ISO-8859-1');
    }

    return $array;
}

function simpleSaveChangeHistory($table, $record, $changes, $from = [], $to = [])
{
    // from and to variable must be arrays with entry 'int' or 'float'
    if (!db_tableexists('history')) {
        return;
    }
    db_query('INSERT INTO history (tablename, record_id, changes, user_id, ip, changedate, from_int, from_float, to_int, to_float) VALUES (:table,:id,:change,:user_id,:ip,NOW(), :from_int, :from_float, :to_int, :to_float)', ['table' => $table, 'id' => $record, 'change' => $changes, 'user_id' => $_SESSION['user']['id'], 'ip' => $_SERVER['REMOTE_ADDR'], 'from_int' => $from['int'], 'from_float' => $from['float'], 'to_int' => $to['int'], 'to_float' => $to['float']]);
}

function simpleBulkSaveChangeHistory($table, $records, $changes, $from = [], $to = [])
{
    // from and to variable must be arrays with entry 'int' or 'float'
    if (!db_tableexists('history')) {
        return;
    }
    $query = '';
    $params = [];
    if (is_iterable($records)) {
        for ($i = 0; $i < sizeof($records); ++$i) {
            $query .= "(:table{$i},:id{$i},:change{$i},:user_id{$i},:ip{$i},NOW(), :from_int{$i}, :from_float{$i}, :to_int{$i}, :to_float{$i})";
            $params = array_merge($params, ['table'.$i => $table, 'id'.$i => $records[$i], 'change'.$i => $changes, 'user_id'.$i => $_SESSION['user']['id'], 'ip'.$i => $_SERVER['REMOTE_ADDR'], 'from_int'.$i => $from['int'], 'from_float'.$i => $from['float'], 'to_int'.$i => $to['int'], 'to_float'.$i => $to['float']]);
            if ($i !== sizeof($records) - 1) {
                $query .= ',';
            }
        }
    }
    if (strlen($query) > 0) {
        db_query("INSERT INTO history (tablename, record_id, changes, user_id, ip, changedate, from_int, from_float, to_int, to_float) VALUES {$query}", $params);
    }
}
