<div class="row">
	<div class="col-sm-11">

		<div class="row row-title">
			<div class="col-sm-12">
				<h1>&nbsp;Laundry</h1>
			</div>
		</div>

		{if $smarty.session.user.is_admin or $smarty.session.user.coordinator}&nbsp;<a class="new-page item-add btn btn-sm btn-default" href="?action=laundry_startcycle&origin=laundry"><i class="fa fa-recycle"></i> Start new cycle</a><br /><br />{/if}


<table class="">
	
	{foreach $data['dates'] as $day => $d name=days}
	
		{foreach $data['machines'] as $machine => $m}
			{if $machine==2}
				<tr></td><td class="dates {if $smarty.foreach.days.first}first{/if}" colspan=5>{$d}</td></tr>
				<tr>{foreach $data['times'] as $t}<td class="times">{$t}</td>{/foreach}</tr>
			{/if}
			<tr>

			{foreach $data['times'] as $time => $t}
				<td class="cell"><a href="?origin=laundry&action=laundry_edit&timeslot={$data['slots'][$day][$time][$machine]['timeslot']}" class="edit"><span class="transparent">{$data['slots'][$day][$time][$machine]['machinename']}</span>&nbsp;
				{if $data['slots'][$day][$time][$machine]['id']}
					{$data['slots'][$day][$time][$machine]['firstname']} {$data['slots'][$day][$time][$machine]['lastname']} ({$data['slots'][$day][$time][$machine]['container']})
				{/if}<a href="#" class="delete"><i class="fa fa-trash-o"></i></a>
				</a></td>
			{/foreach}
			</tr>
			

		{/foreach}
	{/foreach}
</table>

	</div>
</div>