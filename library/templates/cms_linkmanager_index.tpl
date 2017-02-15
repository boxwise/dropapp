{include file="cms_linkmanager_header.tpl"}

<!-- Your Content -->
<div id="container">

	<div class="container-fluid">
	
		{$content}						
		
		<div class="links-frame-container">
			{foreach $linksList as $links}
				{include file="cms_linkmanager_linkslist.tpl" links=$links['links'] title=$links['title'] prefix=$links['prefix']}		
			{/foreach}
		</div>
		
	</div>
</div>

{include file="cms_linkmanager_footer.tpl"}