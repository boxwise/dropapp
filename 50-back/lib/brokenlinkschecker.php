<?

function checkbrokenlinks() {
	global $checkurl, $urldone, $urlgood, $urlbad, $count, $maxcount, $domain, $extskip, $debug;
	$domain = 'http'.($_SERVER['HTTPS']?'s':'').'://'.$_SERVER['HTTP_HOST'];

	if(!db_tableexists('brokenlinks')) return;
	db_query('DELETE FROM brokenlinks');
	if(db_fieldexists('cms_functions','alert')) db_query('UPDATE cms_functions SET alert = 0 WHERE include = "brokenlinks"');
		
	$checkurl[] = array($domain,'/');
	$urldone = array();
	$urlgood = array();
	$urlbad = array();
	$maxcount = 750;
	$count = 0;
	$extskip = array('.ico','.pdf','.png','.jpg','.gif');

	
	checkbrokenlinks_start();
	
	if(count($urlbad)) {
		if(db_fieldexists('cms_functions','alert')) db_query('UPDATE cms_functions SET alert = 1 WHERE include = "brokenlinks"');
	}
}

function checkbrokenlinks_start() {
	global $checkurl, $urldone, $urlgood, $urlbad, $count, $maxcount, $domain, $extskip, $debug;

	$count++;
	if($count>$maxcount) return;
		
	list($url,$source) = array_shift($checkurl);

	echo $url.' ('.count($urldone).' of '.(count($checkurl)+count($urldone)).')<br />';

	$urldone[] = $url;	
	list($status,$mime) = checkbrokenlinks_checklink($url);

	if($status!=200) {
		db_query('INSERT INTO brokenlinks (link,location,error) VALUES (:link,:location,:error)',array('link'=>$url,'location'=>$source,'error'=>$status));
		$urlbad[] = $url;
	} else {
		$urlgood[] = $url;

		if(strpos($url, $domain)!==false) {
			$content = checkbrokenlinks_getPage($url);		
			$links = checkbrokenlinks_checkPage($content, $url);
			if(is_array($links)) foreach($links as $link) {
				if(!in_array($link,$urldone) && !checkbrokenlinks_inList($link)) {
					$checkurl[] = array($link,$url);
				}
			}
		}
		

	}	
	if(count($checkurl)) checkbrokenlinks_start();
}

function checkbrokenlinks_inList($link) {
	global $checkurl;
	foreach($checkurl as $c) if($c[0]==$link) return true;
}
function checkbrokenlinks_getPage($link){ 
	global $extskip;
	$ext = substr($link,-4);
	if(in_array($ext,$extskip)) return;
	
	$headers = get_headers($link);
	foreach($headers as $r) {
		if(substr(strtolower($r),0,14)=='content-type: ') {
			$mime = substr($r,14);
			if(strpos($mime,';')) $mime = substr($mime,0,strpos($mime,';'));
		}
	}
	if(!substr($mime,0,4)=='text') {
		echo '-- Skipped scanning '.$link.', type: '.$mime.'<br />';
		return;
	}
	$context = stream_context_create( array(
		'http'=>array(
			'timeout' => 3, 'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A'
		)
	));

   if ($fp = fopen($link, 'r',false,$context)) { 
      $content = ''; 
         
      while ($line = fread($fp, 1024)) { 
         $content .= $line; 
      } 
	  fclose($fp);
   } else {
	   return;
   }

   return $content;   
} 


function checkbrokenlinks_checkPage($content, $url){ 
	$links = array(); 
	$textLen = strlen($content);  
	
	$placeholder = "[a-zA-Z0-9\=\-_ /.\"'#]*";
	$link = "[a-zA-Z0-9_\-/\.#\:]*";
	
	preg_match_all('^\<script ('.$placeholder.')src=[\'"]('.$link.')[\'"]('.$placeholder.')>^i',$content,$m1);
	preg_match_all('^\<link ('.$placeholder.')href=[\'"]('.$link.')[\'"]('.$placeholder.')>^i',$content,$m2);
	preg_match_all('^\<a ('.$placeholder.')href=[\'"]('.$link.')[\'"]('.$placeholder.')>^i',$content,$m3);
	preg_match_all('^\<img ('.$placeholder.')src=[\'"]('.$link.')[\'"]('.$placeholder.')>^i',$content,$m4);
	$matches = array_merge($m1[2],$m2[2],$m3[2],$m4[2]);
	
	foreach($matches as $m) {
		$m = str_replace(' ','%20',$m);
		$parsed = parse_url($url);
		$parsedtarget = parse_url($m);

		if($parsedtarget['host']=='www.facebook.com') {
			// do nothing
		} elseif(substr($m,0,2)=='//') {
			$result[] = $parsed['scheme'].':'.$m;
		} elseif(substr($m,0,1)=='/') {
			$result[] = $parsed['scheme'].'://'.$parsed['host'].$m;
		} elseif(substr($m,0,7)=='http://') {
			$result[] = $m;
		} elseif(substr($m,0,8)=='https://') {
			$result[] = $m;
		} elseif(substr($m,0,7)=='mailto:') {
			// do nothing
		} elseif(substr($m,0,1)=='#' || !$m) {
			// do nothing
		} elseif($m) {
			$result[] = $parsed['scheme'].'://'.$parsed['host'].'/'.$m;
			//echo $m.' - '.$parsed['scheme'].'://'.$parsed['host'].'/'.$m.'<br /><br />';
		}
		
	}
	
	return $result;
} 

function checkbrokenlinks_checkLink($url){ 
	if(!$url) return;
	$result = get_headers($url);
	if(!$result) return array('404 Server not found','');
	foreach($result as $r) {
		if((!$status || $status=='301' || $status=='302') && substr($r,0,4)=='HTTP') {
			list($http,$status) = explode(' ',$r);
			$statustext = $r;
		}
		if(substr(strtolower($r),0,14)=='content-type: ') {
			$mime = substr($r,14);
			if(strpos($mime,';')) $mime = substr($mime,0,strpos($mime,';'));
		}
	}
	if($status==200) return array($status,$mime);
	return array($status.' '.$statustext,$mime);
} 

