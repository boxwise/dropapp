<!DOCTYPE html>
<html {if $lan=='ar'}dir="rtl"{/if} lang="en">
	<head>
		{include file="analytics.tpl"}
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Boxwise - Simple</title>
		
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet">    
		<link href="/assets/css/select2.css" rel="stylesheet" />
		<link href="/assets/css/select2-bootstrap.css" rel="stylesheet" />
		<link href="/assets/css/mobile.css?v=2" rel="stylesheet">
		
		<script src="/assets/js/jquery-3.1.1.min.js"></script>
		<script src="/assets/js/jquery.validate.min.js"></script>
		<script src="/assets/js/bootstrap.min.js"></script>
		<script src="/assets/js/select2.min.js"></script>
		<script src="/assets/js/mobile.js"></script>
		<script src="/assets/js/jquery.noty.packaged.min.js"></script>   
		{* dynamically built JS function call to trigger a correct noty toast notification  *}
		{if $data['notificationFunction']}
		    <script>
		        {$data['notificationFunction']}(
					{if $data['boxid']}{$data['boxid']}, {/if}
					{if $data['boxNumber']}'{$data['boxNumber']}',  {/if}
					{if $data['itemsCount']}'{$data['itemsCount']}',  {/if}
					{if $data['boxProduct']}'{$data['boxProduct']}',  {/if}
					{if $data['boxLocation']}'{$data['boxLocation']}', {/if}
					{if $data['previousBoxLocation']}'{$data['previousBoxLocation']}', {/if}
					{if $data['campName']}'{$data['campName']}', {/if}
					{if $data['currentCampName']}'{$data['currentCampName']}', {/if}
					{if $data['organisationName']}'{$data['organisationName']}', {/if}
					{if $data['warning']}true{else}false{/if}
				);
			</script>
		{/if}

		<link rel="apple-touch-icon" sizes="180x180" href="/assets/img/apple-touch-icon.png">
		<link rel="icon" type="image/png" href="/assets/img/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="/assets/img/favicon-16x16.png" sizes="16x16">	
   
	</head>
	<body class="mobile" data-testid="mobileBody">
		<header>
			<strong><a href="/mobile.php">{$translate['site_name']}</a></strong>
			{if $smarty.session.user}
				<div class="orgcamp" data-testid="orgcampDiv">
					{if $org}
							{$org['label']} /
					{/if}
					{if $camps|count>1}
						<span id="campselect">&nbsp;
							<select name="campselect" dir="rtl">
									{foreach $camps as $c}
										<option value="?barcode={$smarty.get.barcode}&camp={$c['id']}" {if $c['id']==$currentcamp['id']}selected{/if}>{$c['name']}</option>
									{/foreach}
							</select>
						</span>
					{elseif $camps|count==1}
							&nbsp;{$camps[0]['name']}
					{/if}
				</div>
			{/if}		
		</header>
		{if $data['message']}
			<div class="message {if $data['warning']}warning{/if}">
				{$data['message'] nofilter}
				{if $data['messageAnchorText'] && $data['messageAnchorTarget'] && $data['messageAnchorTargetValue']}
					<a href="?{$data['messageAnchorTarget']}={$data['messageAnchorTargetValue']}">
						{$data['messageAnchorText']}
					</a>
				{/if}
			</div>
		{/if}
		{if $include}{include file="{$include}"}{/if}
		{if $smarty.session.user}
			<footer>
				<a href="/?action=start&camp={$currentcamp['id']}">Full App</a> - 
				<a href="http://helpme.boxwise.co">Help</a> - 
				<a href="?logout=1">{$translate['cms_menu_logout']}</a>
			</footer>
		{/if}
		<div id="loading"><div class="cp-spinner cp-round"></div></div>
	</body>
</html>
