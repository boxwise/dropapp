<!DOCTYPE html>
<html {if $lan=='ar'}dir="rtl"{/if} lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Drop Market - Simple</title>
		
		<link href="{$settings['rootdir']}/assets/css/bootstrap.min.css" rel="stylesheet">    
		<link href="{$settings['rootdir']}/assets/css/select2.css" rel="stylesheet" />
		<link href="{$settings['rootdir']}/assets/css/select2-bootstrap.css" rel="stylesheet" />
		<link href="{$settings['rootdir']}/assets/css/mobile.css?v=2" rel="stylesheet">    

		<script src="{$settings['rootdir']}/assets/js/jquery-3.1.1.min.js"></script>
		<script src="{$settings['rootdir']}/assets/js/jquery.validate.min.js"></script>
		<script src="{$settings['rootdir']}/assets/js/bootstrap.min.js"></script>
		<script src="{$settings['rootdir']}/assets/js/select2.min.js"></script>
		<script src="{$settings['rootdir']}/assets/js/mobile.js?v=2"></script>

    <link rel="apple-touch-icon" sizes="180x180" href="{$data['faviconapple']}">
    <link rel="icon" type="image/png" href="{$data['favicon32']}" sizes="32x32">
    <link rel="icon" type="image/png" href="{$data['favicon16']}" sizes="16x16">

	</head>
	<body class="mobile">
		<header>
			<strong><a href="{$settings['rootdir']}/mobile.php">{$settings['site_name']}</a></strong>
			{if $smarty.session.user}
		 		{if $camps|count>1}
		 			<div id="campselect">
						<select name="campselect" dir="rtl">
						 		{foreach $camps as $c}
					 				<option value="?barcode={$smarty.get.barcode|escape:'html'}&camp={$c['id']}" {if $c['id']==$currentcamp['id']}selected{/if}>{$c['name']}</option>
						 		{/foreach}
						</select>
		 			</div>
		 		{elseif $camps|count==1}
		 				{$camps[0]['name']}
		 		{/if}
			{/if}		
		</header>
		{if $data['message']}<div class="message {if $data['warning']}warning{/if}">{$data['message']}</div>{/if}
		{if $include}{include file="{$include}"}{/if}
		{if $smarty.session.user}
			<footer>
				<a href="{$settings['rootdir']}/?action=start&camp={$currentcamp['id']}">Full App</a> - 
				<a href="?logout=1">{$translate['cms_menu_logout']}</a>
			</footer>
		{/if}
		<div id="loading"><div class="cp-spinner cp-round"></div></div>
	</body>
</html>