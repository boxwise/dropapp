	{function linkslist level=0 prefix='/'}
	<ul class="level{$level}">
		{foreach $data as $item}
			{if is_array($item['sub'])}
				<li><a class="linkmanager-link" data-url="{$prefix}{$item['url']}" href="{$prefix}{$item['url']}">{$item['title']}</a></li>
				{linkslist data=$item['sub'] level={$level}+1 prefix=$prefix}
			{else}
				<li><a class="linkmanager-link" data-url="{$prefix}{$item['url']}" href="{$prefix}{$item['url']}">{$item['title']}</a></li>
			{/if}
		{/foreach}
	</ul>
	{/function}

	<div class="links-frame">	
		<h3>{$title}</h3>	
		{linkslist data=$links prefix=$prefix}
	</div>	