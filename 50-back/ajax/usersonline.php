<?
	$result = db_Query('SELECT id, naam FROM cms_users WHERE UNIX_TIMESTAMP(lastaction) > (UNIX_TIMESTAMP(NOW())-900) AND id != '.$_SESSION['user']['id']);
	while($row = db_fetch($result)) {
		$name = explode(' ',$row['naam']);
		$initials = '';
		foreach($name as $n) $initials .= $n[0];
		$output .= '<li class="tooltip-this" title="'.$row['naam'].'">'.$initials.'</li>';
	}

echo $output;