{foreach $list as $item name="listpeople"}

	{if !$item['begin'] && $item['newcol']}
		</table> 
	{/if}
	{if $item['begin'] || $item['newcol']}
		{$t = $t + 1}
		{if ($t%2) && !($item['begin'])}<div class="newpage"></div>{/if}
		<table class="food-distribution">
	{/if}
	
	{if $item['type']=='container'}
	    <tr><td><strong>{$item['container']}&emsp;&emsp;{$item['number']} people</strong></td><td><strong>{$item['adults']*2+$item['children']+$item['baby']+$item['extra']} portions</strong></td><td>

		    {foreach $item['food'] as $key=>$f}
		    <div class="food-distribution-detail">{$f} {$key}</div>
		    {/foreach}
	    </td></tr>
	{/if}
	{if $item['type']!='container'}
	    <tr><td>{if $item['type']=='familyhead'}<strong>{/if}{$item['name']|trim}{if $item['type']=='familyhead'}</strong>{/if}</td><td>{$item['age']}</td><td  colspan="1">{$item['gender']}{if $item['extra']}&emsp;&emsp;+{$item['extra']} extra{/if}{if $smarty.get.diapers && $item['age']<2}&emsp;&emsp;<div class="food-distribution-detail" style="float:right">Diapers</div>{/if}</td></tr>
	{/if}

	
{/foreach}

	</table> 
	
