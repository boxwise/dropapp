<h1>Market schedule from {$data['startdate']} to {$data['enddate']}</h1>

{foreach $slots as $date=>$d}
	<table>
		<tr><td colspan="2"><h2>{$date} – {$weekdays[$date|date_format:"%u"]} {$date|date_format:"%e"}  {$months[($date|date_format:"%m"|intval)-1]} </h2></td></tr>
		{foreach $slots[$date] as $time=>$t}
			<tr><td>{$time}</td><td>{if $slots[$date][$time]['lunch']}Lunch break{else}{', '|implode:$slots[$date][$time]['containers']}{/if}</td></tr>
		{/foreach}
	</table>
	<p>&nbsp;</p>
{/foreach}