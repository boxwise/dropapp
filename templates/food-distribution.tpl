<div class="noprint tipofday"><h3>💡 Best printing result</h3><p>Print it out using Google Chrome, choose A4, Portrait and switch off the printing of headers and footers in the Print dialog screen.</p></div>
{foreach $list as $item name="listpeople"}

	{if !$item['begin'] && $item['newcol']}
		</table> 
	{/if}
	{if $item['begin'] || $item['newcol']}
		{$t = $t + 1}
		{if ($t%2) && !($item['begin'])}<div class="newpage"></div>{/if}
		<table class="food-distribution">
	{/if}
	
	{if $item['type']=='familyhead'}
	    <tr><td><strong>{$item['container']}</strong></td><td><strong>{$item['number']}p. ({$item['adults']}Ad{if $item['children']}, {$item['children']}Kid{/if}){if $item['extra']} + {$item['extra']}{/if} </strong></td><td>

		    {foreach $item['food'] as $key=>$f}
		    <div class="food-distribution-detail">{$f} {$key}</div>
		    {/foreach}
	    </td></tr>
	{/if}
	{if $item['type']=='member'}
	    <tr><td>{$item['name']|trim}</td><td>{$item['age']}</td><td  colspan="1">{$item['gender']}</td><td colspan="1">{if $item['extra']}+{$item['extra']} extra{/if}</td></tr>
	{/if}

	
{/foreach}

	</table> 
	
