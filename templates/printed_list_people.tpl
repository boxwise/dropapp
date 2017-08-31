<div class="noprint tipofday"><h3>💡 Best printing result</h3><p>Print it out using Google Chrome, choose A4, Portrait and switch off the printing of headers and footers in the Print dialog screen.</p></div>
<div class="noprint"><a href="?action={$action}&export=true">Export this list as .csv-file (for Excel or Google Spreadsheet)</a><br />&nbsp;</div>
{foreach $list as $item name="listpeople"}

	{if !$item['begin'] && $item['newcol']}
		</table> 
	{/if}
	{if $item['begin'] || $item['newcol']}
		{$t = $t + 1}
		{if ($t%2) && !($item['begin'])}<div class="newpage"></div>{/if}
		<table class="printed_list_people">
	{/if}
	
	{if $item['type']=='familyhead'}
	    <tr><td><strong>{$item['container']}</strong></td><td colspan="3"><strong>{$item['number']} people {if $item['extra']}+ {$item['extra']} extra{/if} ({if $item['green']}{$item['green']} green{/if}{if $item['green'] && $item['red']}, {/if}{if $item['red']}{$item['red']} red{/if})</strong></td></tr>
	{/if}
	{if $item['type']=='member'}
	    <tr><td>{$item['name']|trim}</td><td>{$item['age']}</td><td  colspan="1">{$item['gender']}</td><td colspan="1">{if $item['extra']}+{$item['extra']} extra{/if}</td></tr>
	{/if}

	
{/foreach}

	</table> 
	
