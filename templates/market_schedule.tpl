<h1>Market schedule from {$data['startdate']} to {$data['enddate']}</h1>

{foreach $slots as $date=>$d}
	<table>
		<tr><td colspan="{($slots[$date]['max'])+1}"><h2>{$date} – {$weekdays_french[$date|date_format:"%u"]} {$date|date_format:"%e"}  {$months_french[($date|date_format:"%m"|intval)-1]}<br />{$weekdays[$date|date_format:"%u"]} {$date|date_format:"%e"}  {$months[($date|date_format:"%m"|intval)-1]} </h2></td></tr>
		{foreach $slots[$date] as $time=>$t}
			{if $slots[$date][$time]['displaytime']}
			<tr><td>{$slots[$date][$time]['displaytime']}</td>
			
			{if $slots[$date][$time]['lunch']}
				<td colspan="{$slots[$date]['max']}">Lunch break / Déjeuner / غداء</td>
			{else}
				{foreach $slots[$date][$time]['containers'] as $slot}
					<td class="schedule-cell"><span class="schedule schedule-{$slot|substr:0:1}">{$slot}</span></td>
				{/foreach}
				{for $i=($slots[$date][$time]['containers']|count) to ($slots[$date]['max'])-1}<td></td>{/for}
			{/if}				
			
			</tr>
			{/if}
		{/foreach}
	</table>
	<p>&nbsp;</p>
{/foreach}