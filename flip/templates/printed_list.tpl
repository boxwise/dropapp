<div class="noprint tipofday"><h3>👨‍🏫 Best printing result</h3><p>Print it out using Google Chrome, choose A4, Portrait and switch off the printing of headers and footers in the Print dialog screen.</p></div>
<div class="noprint"><a href="?action={$action}&export=true">Export this list as .csv-file (for Excel or Google Spreadsheet)</a><br />&nbsp;</div>
	{$newcol = true}
	{$length = 47}
    {foreach $containers as $container name="containers"}
{if $newcol}
	<table class="printed_list">
    <tr><td>&nbsp;</td><td><strong>Container</strong></td><td><strong>#</strong></td></tr>
	{$newcol = false} 
{/if}    
            <tr><td class="square">&#9723;</td><td>{$container['container']}</td><td>{$container['count']}</td></tr>

{if $smarty.foreach.containers.iteration is div by $length} 
	</table> 
	{$newcol = true} 
{/if} 

    {/foreach}
