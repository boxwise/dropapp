<?
	
	// cms settings	
	$settings['rootdir'] = '/themarket';
	$settings['cmsdir'] = (isset($cmsdir)?$cmsdir:'/flip');
	
	if($_SERVER['BRANCH']=='bart') {
		$settings['db_host'] = 'localhost';
		$settings['db_user'] = 'root';
		$settings['db_pass'] = 'root';
		$settings['db_database'] = 'market';		
		$settings['globaldir'] = '/Users/bart/Websites/themarket/50-back/';
	} elseif($_SERVER['BRANCH']=='test') {
		$settings['db_host'] = 'localhost';
		$settings['db_user'] = 'deb5672n12_drops';
		$settings['db_pass'] = 'moihkj09';
		$settings['db_database'] = 'deb5672n12_drops';		
		$settings['globaldir'] = '/home/deb5672n12/domains/maartenhunink.com/public_html/market/50-back/';
	} elseif($_SERVER['BRANCH']=='live') {
		$settings['db_host'] = 'localhost';
		$settings['db_user'] = 'drapeton_maarten17';
		$settings['db_pass'] = '9$Tg!W;fOola';
		$settings['db_database'] = 'drapeton_market17';		
		$settings['globaldir'] = '/home/drapeton/market/50-back/';
	}


