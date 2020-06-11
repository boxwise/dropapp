<header class="header-top">
	<div class="header-top-inner container-fluid" data-testid="dropapp-header">
 		<div class="pull-left">
			<a href="#" class="menu-btn visible-xs visible-sm visible-md"><i class="fa fa-ellipsis-v"></i></a>
			<a href="/" class="boxtributeLogoLink"><img class="visible-xs visible-sm visible-md" src="../assets/img/boxtribute_small.png" width="35"></img></a>
			<a href="/" class="boxtributeLogoLink"><img class="visible-lg" src="../assets/img/boxtribute.png" width="120"></img></a>
			{* <a href="/" class="brand">{$translate['site_name']}</a> *}
 		</div>
		<span class="orgCampHeaderSpan">
			{if $smarty.session.organisation.label}
				{if $camps|count<1}
					{$smarty.session.organisation.label}
				{else}
					{$camps[$smarty.session.camp['id']]['name']}
				{/if}
			{/if}
		</span>
		<ul class="nav navbar-nav pull-right">
	 		{if $camps|count>1}
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe visible-xs"></i><span class="hidden-xs">{$currentcamp['name']} </span><b class="caret"></b></a>
					<ul class="dropdown-menu dropdown-menu-right">
				 		{foreach $camps as $c}
				 			<li><a href="?action={$campaction}&camp={$c['id']}" value="{$c['id']}" >{$c['name']} {if $c['id']==$currentcamp['id']}<span class="fa fa-check"></span>{/if}</a></li>
				 		{/foreach}
					</ul>
				</li>
	 		{/if}

	 		{if $smarty.session.user['is_admin']}
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-testid="organisationsDropdown" data-toggle="dropdown"><i class="fa fa-globe visible-xs"></i><span class="hidden-xs">{if $smarty.session.organisation['label']}{$smarty.session.organisation['label']}{else}Choose organisation{/if} </span><b class="caret"></b></a>
					<ul class="dropdown-menu dropdown-menu-right">
				 		{foreach $organisations as $o}
				 			<li data-testid="organisationOption"><a href="?action={$campaction}&organisation={$o['id']}" value="{$o['id']}">{$o['label']} {if $o['id']==$smarty.session.organisation['id']}<span class="fa fa-check"></span>{/if}</a></li>
				 		{/foreach}
					</ul>
				</li>
	 		{/if}

			{if $haswarehouse}
			<li class="simpleAppLink"><a href="/mobile.php?camp={$currentcamp['id']}"><i class="fa fa-mobile"></i><span class="hidden-xs">Simple App</span></a></li>
			{/if}

			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user visible-xs"></i><span class="hidden-xs">{$smarty.session.user.naam} {if isset($smarty.session.user2)}({$smarty.session.user2.naam}){/if}</span><b class="caret"></b></a>
				<ul class="dropdown-menu dropdown-menu-right">
					<li><a href="?action=cms_profile">{$translate['cms_menu_settings']}</a></li>
{if $smarty.session.user2}<li><a href="?action=exitloginas">{$translate['cms_menu_exitloginas']|replace:'%user%':$smarty.session.user2.naam}</a></li>{/if}
					<li><a href="#" onclick="openwidget()">Help</a></li>
					{literal}
					<script> function openwidget() { FreshworksWidget('open')}</script>
					{/literal}
					<li><a href="?action=logout">{$translate['cms_menu_logout']}</a></li>
				</ul>
			</li>
		</ul>
 		<ul id="usersonline" class="pull-right hidden-xs"></ul>
	</div>
</header>
