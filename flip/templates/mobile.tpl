<!DOCTYPE html>
<html {if $lan=='ar'}dir="rtl"{/if} lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Drop Market Stock tool</title>
		
		<link href="{$settings['rootdir']}/flip/lib/bootstrap.min.css" rel="stylesheet">    
		
		<script src="{$settings['rootdir']}/flip/lib/jquery-3.1.1.min.js"></script>
		<script src="{$settings['rootdir']}/flip/lib/jquery.validate.min.js"></script>
		<script src="{$settings['rootdir']}/flip/lib/bootstrap.min.js"></script>

		<link href="{$settings['rootdir']}/flip/lib/select2.min.css" rel="stylesheet" />
		<script src="{$settings['rootdir']}/flip/lib/select2.min.js"></script>
		
		<script src="{$settings['rootdir']}/flip/lib/mobile.js"></script>

		<link href="{$settings['rootdir']}/flip/lib/mobile.css" rel="stylesheet">    

	</head>
	<body class="mobile">
		{if $include}{include file="{$include}"}{/if}
		<div id="loading"><div class="cp-spinner cp-round"></div></div>
		<hr />
		
		<strong>{$settings['site_name']}</strong>
		{if $smarty.session.user}
		 	| <a href="?logout=1">{$translate['cms_menu_logout']}</a> | 
		
	 		{if $camps|count>1}
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user visible-xs"></i><span class="hidden-xs">{$currentcamp['name']} </span><b class="caret"></b></a>
					<ul class="dropdown-menu dropdown-menu-right">
				 		{foreach $camps as $c}
				 			{if $c['id']!=$currentcamp['id']}
				 				<li><a href="?barcode={$smarty.get.barcode}&camp={$c['id']}" value="{$c['id']}" {if $c['id']==$currentcamp['id']}selected{/if}>{$c['name']}</a></li>
				 			{/if}
				 		{/foreach}
					</ul>
	 		{elseif $camps|count==1}
	 			<li>
	 				{$camps[0]['name']}
	 			</li>
	 		{else}
	 			No camps available for this user
	 		{/if}
		{/if}
	</body>
</html>