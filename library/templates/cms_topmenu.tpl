<header class="header-top">
	<div class="header-top-inner container-fluid">
 		<div class="pull-left">
			<a href="#" class="menu-btn visible-xs">&#9776;</a>
			<a href="{$settings['rootdir']}/" class="brand">{$translate['site_name']}</a>
 		</div>
		<ul class="nav navbar-nav pull-right">
	 		{if $camps|count>1}
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe visible-xs"></i><span class="hidden-xs">{$currentcamp['name']} </span><b class="caret"></b></a>
					<ul class="dropdown-menu dropdown-menu-right">
				 		{foreach $camps as $c}
				 			{if $c['id']!=$currentcamp['id']}
				 				<li><a href="?action={$campaction}&camp={$c['id']}" value="{$c['id']}" {if $c['id']==$currentcamp['id']}selected{/if}>{$c['name']}</a></li>
				 			{/if}
				 		{/foreach}
					</ul>
				</li>
	 		{elseif $camps|count==1}
	 			<li class="text-only">
	 				{$camps[0]['name']}
	 			</li>
	 		{else}
	 			<li class="text-only">No camps available for this user</li>
	 		{/if}
			
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user visible-xs"></i><span class="hidden-xs">{$smarty.session.user.naam} {if $smarty.session.user2}({$smarty.session.user2.naam}){/if}</span><b class="caret"></b></a>
				<ul class="dropdown-menu dropdown-menu-right">
					<li><a href="?action=cms_profile">{$translate['cms_menu_settings']}</a></li>
{if $smarty.session.user2}<li><a href="?action=exitloginas">{$translate['cms_menu_exitloginas']|replace:'%user%':$smarty.session.user2.naam}</a></li>{/if}
					<li><a href="?action=logout">{$translate['cms_menu_logout']}</a></li>
				</ul>
			</li>
		</ul>
 		<ul id="usersonline" class="pull-right hidden-xs"></ul>
	</div>
</header>