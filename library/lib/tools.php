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

function showHistory($table, $id)
{
    global $translate;

    $smarty = new Zmarty();
    $history = [];

    $result = db_query('SELECT h.*, u.naam FROM history AS h LEFT OUTER JOIN cms_users AS u ON h.user_id = u.id WHERE tablename = :table AND record_id = :id ORDER BY changedate DESC', ['table' => $table, 'id' => $id]);
    while ($row = db_fetch($result)) {
        $row['changedate'] = strftime('%A %d %B %Y, %H:%M', strtotime($row['changedate']));
        $row['changes'] = strip_tags($row['changes']);
        if ('items' == $row['changes']) {
            $row['changes'] = 'changed the number of items from '.$row['from_int'].' to '.$row['to_int'];
        } elseif ('location_id' == $row['changes']) {
            $loc_ids = [$row['from_int'], $row['to_int']];
            $loc_orig = db_row('SELECT locations.label FROM locations WHERE locations.id = :id_orig', ['id_orig' => $loc_ids[0]]);
            $loc_dest = db_row('SELECT locations.label FROM locations WHERE locations.id = :id_dest', ['id_dest' => $loc_ids[1]]);
            $row['changes'] = 'changed box location from '.$loc_orig['label'].' to '.$loc_dest['label'];
        } elseif ('Record created' == $row['changes']) {
            $row['changes'] = ' created the record';
        } elseif ('Record recovered' == $row['changes']) {
            $row['changes'] = 'recovered the Record';
        } elseif ('Record deleted' == $row['changes']) {
            $row['changes'] = ' deleted the record';
        } elseif ('Record deleted by daily routine' == $row['changes']) {
            $row['changes'] = ' deleted the record automatically via daily routine';
        } elseif ('Record deleted by daily routine because head of family/beneficiary was deleted' == $row['changes']) {
            $row['changes'] = ' deleted the Record automatically via daily routine because the head of family/beneficiary was deleted';
        } elseif ('Box ordered to shop' == trim($row['changes'])) {
            $row['changes'] = ' ordered the box to the shop';
        } elseif ('Box order made undone' == trim($row['changes'])) {
            $row['changes'] = ' canceled the box order';
        } elseif ('Box picked from warehouse ' == $row['changes']) {
            $row['changes'] = 'picked the Box from the warehouse';
        } elseif (trim($row['changes']) == 'product_id') {
            $prod_ids = [$row['from_int'], $row['to_int']];
            $prod_orig = db_row('SELECT products.name FROM products WHERE products.id = :id_orig', ['id_orig' => $prod_ids[0]]);
            $prod_new = db_row('SELECT products.name FROM products WHERE products.id = :id_new', ['id_new' => $prod_ids[1]]);
            $row['changes'] = 'changed product type from '.$prod_orig['name'].' to '.$prod_new['name'];
        } elseif (trim($row['changes']) == 'size_id') {
            $size_ids = [$row['from_int'], $row['to_int']];
            $size_orig = db_row('SELECT sizes.label FROM sizes WHERE sizes.id = :id_orig', ['id_orig' => $size_ids[0]]);
            $size_new = db_row('SELECT sizes.label FROM sizes WHERE sizes.id = :id_new', ['id_new' => $size_ids[1]]);
            $row['changes'] = 'changed size from '.$size_orig['label'].' to '.$size_new['label'];
        } elseif (!(is_null($row['to_int']) && is_null($row['to_float']))) {
            $row['changes'] = ' changed' + $row['changes'];
            if (!is_null($row['from_int'])) {
                $row['changes'] .= ' from '.$row['from_int'];
            } elseif (!is_null($row['from_float'])) {
                $row['changes'] .= ' from '.$row['from_float'];
            }
            if (!is_null($row['to_int'])) {
                $row['changes'] .= ' to '.$row['to_int'];
            } elseif (!is_null($row['to_float'])) {
                $row['changes'] .= ' to '.$row['to_float'];
            }
        }

        $row['changes'] .= '; ';
        $row['truncate'] = strlen($row['changes']) > 300;
        $history[] = $row;
    }

    if (!(is_null($row['to_int']) && is_null($row['to_float']))) {
        $row['changes'] = ' changed' + $row['changes'];
        if (!is_null($row['from_int'])) {
            $row['changes'] .= ' from '.$row['from_int'];
        } elseif (!is_null($row['from_float'])) {
            $row['changes'] .= ' from '.$row['from_float'];
        }
        if (!is_null($row['to_int'])) {
            $row['changes'] .= ' to '.$row['to_int'];
        } elseif (!is_null($row['to_float'])) {
            $row['changes'] .= ' to '.$row['to_float'];
        }
        $row['changes'] .= '; ';
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
    if (strpos($qs, '?')) {
        $qs = substr($qs, 0, strpos($qs, '?'));
    }
    $items = explode('/', $qs);
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

function redirect($url, $status = 301)
{
    header('Location: '.$url, true, $status);
    die();
}

function CMSmenu()
{
    global $action, $lan;

    $result1 = db_query('SELECT f.* FROM cms_functions AS f WHERE f.visible AND f.parent_id = 0 ORDER BY f.seq', ['camp' => $_SESSION['camp']['id']]);
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
            if ($row2['title'.'_'.$lan]) {
                $row2['title'] = $row2['title'.'_'.$lan];
            }
            $submenu[] = $row2;
        }

        if ($row1['title'.'_'.$lan]) {
            $row1['title'] = $row1['title'.'_'.$lan];
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

if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set('Europe/Amsterdam');
}

function safestring($input)
{
    $safestringchar = '-';
    $input = str_replace(['!', '?', '&'], '', $input);
    $input = utf8_decode($input);

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
    if ('-' == substr($x, -1)) {
        $x = substr($x, 0, strlen($x) - 1);
    }

    return utf8_encode($x);
}

function utf8_decode_array($array)
{
    if (!is_array($array)) {
        return false;
    }
    foreach ($array as $key => $value) {
        $array[$key] = utf8_decode($value);
    }

    return $array;
}

function simpleSaveChangeHistory($table, $record, $changes, $from = [], $to = [])
{
    //from and to variable must be arrays with entry 'int' or 'float'
    if (!db_tableexists('history')) {
        return;
    }
    db_query('INSERT INTO history (tablename, record_id, changes, user_id, ip, changedate, from_int, from_float, to_int, to_float) VALUES (:table,:id,:change,:user_id,:ip,NOW(), :from_int, :from_float, :to_int, :to_float)', ['table' => $table, 'id' => $record, 'change' => $changes, 'user_id' => $_SESSION['user']['id'], 'ip' => $_SERVER['REMOTE_ADDR'], 'from_int' => $from['int'], 'from_float' => $from['float'], 'to_int' => $to['int'], 'to_float' => $to['float']]);
}

function displayDate($datum, $time = false, $long = false)
{
    global $_txt;

    if (!is_int($datum)) {
        $datum = strtotime($datum);
    }
    $d = strftime('%Y-%m-%d', $datum);

    if ($d == strftime('%Y-%m-%d', strtotime('+2 day'))) {
        $dmy = 'Tomorrow'.strftime('%A', $datum);
    }
    if ($d == strftime('%Y-%m-%d', strtotime('+1 day'))) {
        $dmy = 'Tomorrow';
    }
    if ($d == strftime('%Y-%m-%d')) {
        $dmy = $_txt['today'];
    }
    if ($d == strftime('%Y-%m-%d', strtotime('-1 day'))) {
        $dmy = 'Yesterday';
    }
    if ($d == strftime('%Y-%m-%d', strtotime('-2 day'))) {
        $dmy = 'Two days ago';
    }

    if (!$datum) {
        return 'Unknown';
    }
    if ($time) {
        if (!$dmy) {
            return strftime('%e %B %Y, %H:%M', $datum);
        }
    }

    return $dmy.strftime(', %H:%M', $datum);
    if ($long) {
        if (!$dmy) {
            return strftime('%e %B %Y', $datum);
        }

        return $dmy;
    }

    return strftime('%d-%m-%Y', $datum);
}
