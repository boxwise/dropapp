<header class="header-top">
	<div class="header-top-inner container-fluid">
 		<div class="pull-left">
			<a href="#" class="menu-btn visible-xs">&#9776;</a>
			<a href="{$settings['rootdir']}/" class="brand">{$translate['site_name']}</a>
			{if $smarty.session.organisation.label}{$smarty.session.organisation.label}
			{if $camps|count==1}
	 			/ {$camps[0]['name']}
	 		{elseif $camps|count<1}
	 			/ No camp
			{/if}
			{/if}
 		</div>
		<ul class="nav navbar-nav pull-right">
			<li><a href="{$settings['rootdir']}/mobile.php?camp={$currentcamp['id']}"><i class="fa fa-mobile"></i><span class="hidden-xs">Simple App</span></a></li>
	 		{if $smarty.session.user['is_admin']}
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe visible-xs"></i><span class="hidden-xs">{if $smarty.session.organisation['label']}{$smarty.session.organisation['label']}{else}Choose organisation{/if} </span><b class="caret"></b></a>
					<ul class="dropdown-menu dropdown-menu-right">
				 		{foreach $organisations as $o}
				 			<li><a href="?action={$campaction}&organisation={$o['id']}" value="{$o['id']}">{$o['label']} {if $o['id']==$smarty.session.organisation['id']}<span class="fa fa-check"></span>{/if}</a></li>
				 		{/foreach}
					</ul>
				</li>
	 		{/if}
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
			
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user visible-xs"></i><span class="hidden-xs">{$smarty.session.user.naam} {if $smarty.session.user2}({$smarty.session.user2.naam}){/if}</span><b class="caret"></b></a>
				<ul class="dropdown-menu dropdown-menu-right">
					<li><a href="?action=cms_profile">{$translate['cms_menu_settings']}</a></li>
{if $smarty.session.user2}<li><a href="?action=exitloginas">{$translate['cms_menu_exitloginas']|replace:'%user%':$smarty.session.user2.naam}</a></li>{/if}
					<li><a href="?action=logout">{$translate['cms_menu_logout']}</a></li>
					<li><a href="http://helpme.boxwise.co">Help</a></li>
				</ul>
			</li>
		</ul>
 		<ul id="usersonline" class="pull-right hidden-xs"></ul>
	</div>
</header>
