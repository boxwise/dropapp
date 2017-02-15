<?

function getDirsRecursive($dir, &$results = array()){
    $files = scandir($dir);

    foreach($files as $key => $value){
	    $path = $dir.DIRECTORY_SEPARATOR.$value;
        $realpath = realpath($path);
        if(is_dir($realpath) && $value != "." && $value != "..") {
            getDirsRecursive($path, $results);
            $results[] = '/'.$path;
        }
    }

    return $results;
}

function checkURL($url) {
   $headers = @get_headers( $url);
   $headers = (is_array($headers)) ? implode( "\n ", $headers) : $headers;

   return (bool)preg_match('#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers);
}

function showHistory($table,$id) {
	global $translate;

	$smarty = new Zmarty;
	$history = array();

	$result = db_query('SELECT h.*, u.naam FROM history AS h LEFT OUTER JOIN cms_users AS u ON h.user_id = u.id WHERE tablename = :table AND record_id = :id ORDER BY changedate',array('table'=>$table,'id'=>$id));
	while($row = db_fetch($result)) {
		$row['changedate'] = strftime('%A %d %B %Y, %H:%M',strtotime($row['changedate']));
		$row['changes'] = strip_tags($row['changes']);
		$row['truncate'] = strlen($row['changes']) > 300;
		$history[] = $row;
	}
	$smarty->assign('row',$history);
	echo $smarty->display('cms_form_history.tpl',true);

	if(!$result->rowCount()) { echo $translate['cms_form_history_nodata']; }
}

function ConvertURL(){
	global $lan, $settings;
	$qs=$_SERVER['REQUEST_URI'];
	if(strpos($qs,'?')) $qs = substr($qs,0,strpos($qs,'?'));
	$items=explode('/',$qs);
	if($settings['site_multilanguage']) {
		$lan = $items[1];
		array_shift($items);
	}
	array_shift($items);
	// The first element is always empty because the querystring starts with a '/', so trash it
	/* we should have at least two items AND if more than 2 an even amount...
	 * Otherwise go to the default page */
	$count = 0;
	foreach($items as $item) {
		$_GET['get'.$count] = $item;
		$count++;
	}
	if(!$lan) $lan = $settings['site_language'];
}

function redirect($url, $status = 301) {
	header("Location: ".$url, true, $status);
	die();
}

function CMSmenu() {
	global $action, $lan;

/* 
	# deprecated
	if($_SESSION['user']['usertype']=='family') {
		$menu = array(0 => 
			array('id' => '35', 'parent_id' => '0', 'title' => ($lan=='en'?'Drops':($lan=='ar'?'قطرات':'dilopên')), 'include' => '', 'seq' => '1', 'alert' => '0', 'sub' => array(0 => array('id' => '87', 'parent_id' => '35', 'title' => ($lan=='en'?'Status':($lan=='ar'?'الحالة':'Cî')), 'include' => 'status', 'seq' => '1', 'alert' => '0'))));
		return $menu;
	}
*/

	$result1 = db_query('SELECT f.* FROM cms_functions AS f WHERE f.parent_id = 0 ORDER BY f.seq',array('camp'=>$_SESSION['camp']['id']));
	while($row1 = db_fetch($result1)) {

		$submenu = array();

		if($_SESSION['user']['is_admin']) {
			$result2 = db_query('SELECT f.*, title_'.$lan.' AS title FROM cms_functions AS f LEFT OUTER JOIN cms_functions_camps AS x2 ON x2.cms_functions_id = f.id WHERE x2.camps_id = :camp AND f.parent_id = :parent_id ORDER BY f.seq',array('camp'=>$_SESSION['camp']['id'],'parent_id'=>$row1['id']));
		} else {
			$result2 = db_query('SELECT *, title_'.$lan.' AS title FROM (cms_functions AS f, cms_access AS x, cms_users AS u) LEFT OUTER JOIN cms_functions_camps AS x2 ON x2.cms_functions_id = f.id WHERE x2.camps_id = :camp AND u.id = x.cms_users_id AND f.id = x.cms_functions_id AND u.id = :user_id AND (f.parent_id = :parent_id) ORDER BY seq',array('camp'=>$_SESSION['camp']['id'],'parent_id'=>$row1['id'],'user_id'=>$_SESSION['user']['id']));
		}

		while($row2 = db_fetch($result2)) {
			if($row2['include']==$action || $row2['include'].'_edit'==$action) $row2['active'] = true;
			if($row2['title'.'_'.$lan]) $row2['title'] = $row2['title'.'_'.$lan];
			$submenu[] = $row2;
		}

		if($row1['title'.'_'.$lan]) $row1['title'] = $row1['title'.'_'.$lan];
		$row1['sub'] = $submenu;
		if($submenu) $menu[] = $row1;

	}
	return $menu;
}

function getCMSuser($id) {
	$user = db_row('SELECT * FROM cms_users WHERE id = :id',array('id'=>$id));
// 	return '<a href="mailto:'.$user['email'].'">'.$user['naam'].'</a>';
	return $user['naam'];
}


if(function_exists('date_default_timezone_set'))
   date_default_timezone_set('Europe/Amsterdam');


function safestring($input) {
	$safestringchar = '-';
	$input = str_replace(array('!','?','&'),'',$input);
	$input = utf8_decode($input);

	$x = '';
	for($i=0;$i<strlen($input);$i++) {
		$c = ord($input[$i]);
		if($c == 32) {
			$x .= $safestringchar;
		} elseif($c == 95 || ($c > 47 && $c < 58) || ($c > 64 && $c < 91) || ($c > 96 && $c < 123)) {
			$x .= chr($c);
		} elseif(in_array($input[$i],utf8_decode_array(array('ä','á','à','â')))) {
			$x .= 'a';
		} elseif(in_array($input[$i],utf8_decode_array(array('ë','é','è','ê')))) {
			$x .= 'e';
		} elseif(in_array($input[$i],utf8_decode_array(array('ï','í','ì','î')))) {
			$x .= 'i';
		} elseif(in_array($input[$i],utf8_decode_array(array('ö','ó','ò','ô')))) {
			$x .= 'o';
		} elseif(in_array($input[$i],utf8_decode_array(array('ü','ú','ù','û')))) {
			$x .= 'u';
		} elseif(in_array($input[$i],utf8_decode_array(array('ř')))) {
			$x .= 'r';
		} elseif(in_array($input[$i],utf8_decode_array(array('ā')))) {
			$x .= $safestringchar;
		} elseif($input[$i]==$safestringchar) {
			$x .= $safestringchar;
		}
	}

	$x = strtolower($x);
	if(substr($x,-1)=='-') $x = substr($x,0,strlen($x)-1);
	return utf8_encode($x);
}

function utf8_decode_array($array) {
	if(!is_array($array)) return false;
	foreach($array as $key=>$value) {
		$array[$key] = utf8_decode($value);
	}
	return $array;
}

