<?
	
	function mailchimp_subscribe($email, $list_mergevars, $double_optin=true) {		
		global $settings;

		$mergevars = array(
		    'OPTIN_IP'=>$_SERVER['REMOTE_ADDR'],
		    'OPTIN-TIME'=>"now"		    
		);
		$mergevars = array_merge($mergevars,$list_mergevars);
				
		$send_data = array(
		    'email'=>array('email'=>mysql_escape_string($email)),
		    'apikey'=>$settings['mailchimp_apikey'], // Your Key
		    'id'=>$settings['mailchimp_listid'], // Your proper List ID
		    'merge_vars'=>$mergevars,
		    'double_optin'=>$double_optin,
		    'update_existing'=>false,
		    'replace_interests'=>false,
		    'send_welcome'=>false,
		    'email_type'=>"html",
		);
		
		$payload = json_encode($send_data);
		$dc = substr($settings['mailchimp_apikey'],strrpos($settings['mailchimp_apikey'],'-')+1);
		$submit_url = 'https://'.$dc.'.api.mailchimp.com/2.0/lists/subscribe.json';
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$submit_url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$payload);
		$result = curl_exec($ch);
		curl_close($ch);
		$mcdata=json_decode($result);
			
		if (!empty($mcdata->error)) {
			logfile("Mailchimp Error: ".$mcdata->error.', '.$email);	
		}
	}
