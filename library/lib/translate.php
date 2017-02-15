<?

if(defined('FLIP')) {
	$lan = $settings['cms_language'];
	
	//load global language array
	
	if(isset($_SESSION['user']['language'])) $lan = db_value('SELECT code FROM languages WHERE id = :id',array('id'=>$_SESSION['user']['language']));
	if(!$lan) $lan = $settings['cms_language'];
}

if(defined('FLIPSITE')) {
	$lan = $settings['site_language'];
	$lanid = db_value('SELECT id FROM languages WHERE code = :code',array('code'=>$lan));
}

$translate = db_simplearray('SELECT code, '.$lan.' FROM translate WHERE NOT deleted');
if(isset($flipglobal)) {
	$translate2 = db_simplearray('SELECT code, '.$lan.' FROM translate WHERE NOT deleted',array(),$flipglobal);
	$translate = array_merge($translate,$translate2);
}
	
$settings['languages'] = db_array('SELECT id,code,name,locale FROM languages WHERE visible ORDER BY seq');

function translate($code, $lan_override = false) {
	global $settings, $lan, $translate;	
	
	if($lan_override) $lan = $lan_override;

	$result = db_value('SELECT '.$lan.' FROM translate WHERE code = :code',array('code'=>$code));
	if(!$result) $result = $translate[$code];
		
	return $result;
}

