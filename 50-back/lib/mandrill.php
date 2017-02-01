<?
	require($settings['globaldir'].'mandrill/Mandrill.php');
	require($settings['globaldir'].'htmlmimemail/htmlMimeMail5.php');

	function mandrillmail($to_email, $to_name, $subject, $content, $email_template = 'cms_email.tpl', $from_email = '', $from_name = '', $debug = false) {
		
		global $settings, $translate;
		
		
		if(!$from_email) $from_email = $settings['mail_sender'];
		if(!$from_name) $from_name = $settings['mail_sender_name'];		
			
		if(is_array($to_email)) {
			foreach($to_email as $key=>$to) {
				$rcpt[] = array('email'=>$to,'name'=>$to_name[$key]);
				$merge[] = array('rcpt'=>$to,'vars'=>array(array('name'=>'naam','content'=>$to_name[$key]),array('name'=>'email','content'=>$to)));
			}			
		} else {
			$rcpt[] = array('email'=>$to_email,'name'=>$to_name);
			$merge[] = array('rcpt'=>$to_email,'vars'=>array(array('name'=>'naam','content'=>$to_name),array('name'=>'email','content'=>$to_email)));

		}
			
		
		$template = new Zmarty;
		$template->assign('content',$content);					
		$template->assign('domain',$_SERVER['HTTP_HOST']);					
		
		if($debug) {
			echo $template->fetch($email_template);
			return;
		} else {
			$mail = $template->fetch($email_template);			
		}
			
/*
		$mandrill = new Mandrill('UuZ_zpQoJM45SoKJ2eG6kw');

		$message = array(
			'html' => $mail,
			'subject' => $subject,
			'from_email' => $from_email,
			'from_name' => $from_name,
			'to' => $rcpt,
		    'track_opens' => true,
		    'track_clicks' => true,
		    'auto_text' => true,
		    'preserve_recipients' => false,
		    'view_content_link' => true,
		    'merge' => true,
		    'merge_vars' => $merge,
		    'tags' => array($_SERVER['HTTP_HOST'])
		    
		);
*/
		
		#Verstuur de mails met Mandrill
		#deze functie tijdelijk uitgeschakeld
		#$result = $mandrill->messages->send($message);
		
		#Verstuur de mails via onze eigen server via HtmlMimeMail5

		 foreach($rcpt as $to) {
		 	$m = new htmlMimeMail5();
		 	$m->setHTMLCharset('UTF-8');
		 	$m->setTextCharset('UTF-8');
		 	$m->setFrom($from_name.' <'.$from_email.'>');
		 	$m->setSubject(utf8_decode($subject));
			$m->setHTML(str_replace('*|NAAM|*',$to['name'],$mail));
		 	$m->send(array($to['email']));
		 }
		
	}

	function zinnebeeldmail($to_email, $to_name, $subject, $content, $email_template = 'cms_email.tpl', $from_email = '', $from_name = '', $debug = false) {
		
		global $settings, $translate;
		
		
		if(!$from_email) $from_email = $settings['mail_sender'];
		if(!$from_name) $from_name = $settings['mail_sender_name'];		
			
		if(is_array($to_email)) {
			foreach($to_email as $key=>$to) {
				$rcpt[] = array('email'=>$to,'name'=>$to_name[$key]);
				$merge[] = array('rcpt'=>$to,'vars'=>array(array('name'=>'naam','content'=>$to_name[$key]),array('name'=>'email','content'=>$to)));
			}			
		} else {
			$rcpt[] = array('email'=>$to_email,'name'=>$to_name);
			$merge[] = array('rcpt'=>$to_email,'vars'=>array(array('name'=>'naam','content'=>$to_name),array('name'=>'email','content'=>$to_email)));
		}
			
		
		$template = new Zmarty;
		$template->assign('content',$content);					
		$template->assign('domain',$_SERVER['HTTP_HOST']);					
		
		if($debug) {
			echo $template->fetch($email_template);
			return;
		} else {
			$mail = $template->fetch($email_template);			
		}
			
/*
		$mandrill = new Mandrill('UuZ_zpQoJM45SoKJ2eG6kw');

		$message = array(
			'html' => $mail,
			'subject' => $subject,
			'from_email' => $from_email,
			'from_name' => $from_name,
			'to' => $rcpt,
		    'track_opens' => true,
		    'track_clicks' => true,
		    'auto_text' => true,
		    'preserve_recipients' => false,
		    'view_content_link' => true,
		    'merge' => true,
		    'merge_vars' => $merge,
		    'tags' => array($_SERVER['HTTP_HOST'])
		    
		);
		
*/
		#Verstuur de mails met Mandrill
		#deze functie tijdelijk uitgeschakeld
		// $result = $mandrill->messages->send($message);
		
		#Verstuur de mails via onze eigen server via HtmlMimeMail5
		foreach($rcpt as $key=>$to) {
			$m = new htmlMimeMail5();
			$m->setHTMLCharset('UTF-8');
			$m->setTextCharset('UTF-8');
			$m->setFrom($from_name.' <'.$from_email.'>');
			$m->setSubject(utf8_decode($subject));
			$m->setHTML(str_replace('NAAM',$to['name'],$mail));
			$m->send(array($to['email']));
		}
		
	}