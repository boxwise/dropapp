<div>
	{foreach $row as $line}
	<strong>{$line['naam']}</strong> {$translate['cms_form_ondate']}&nbsp<strong>{$line['changedate']}</strong><br />
	<div class="small">
		<span class="text-truncated"> {if $line['tablename'] == 'stock'} {$line['naam']} {/if}{$line['changes']|truncate:300}
		{if $line['truncate']} 
			<a class="text-show-original" href="#">Read more</a></span>
			<span class="hide text-original">{if $line['tablename']=='stock'}{$line['naam']} {/if}{$line['changes']}<a class="text-hide-original" href="#">Read less</a></span>
		{else}
			</span>
		{/if}
	</div>
	<hr />
	{/foreach}
</div>