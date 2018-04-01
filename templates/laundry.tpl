<div class="row">
	<div class="col-sm-11">

		<div class="row row-title">
			<div class="col-sm-12">
				<h1>Laundry</h1>
			</div>
		</div>

		{if $smarty.session.user.is_admin or $smarty.session.user.coordinator}<a class="new-page item-add btn btn-sm btn-default" href="?action=laundry_startcycle&origin=laundry"><i class="fa fa-recycle"></i> Start new cycle</a><br /><br />{/if}


	
	{foreach $data['dates'] as $day => $d name=days}
		<h2><i class="icon-open-{$day} fa fa-angle-right {if !$d['past']}hidden{/if}"></i><i class="icon-close-{$day} fa fa-angle-down {if $d['past']} hidden{/if}"></i> <a href="#" class="laundry-showhide" data-day="{$day}">{$d['label']}</a></h2>
		<a name="{$day}"></a>
		<table id="laundry-table-{$day}"{if $d['past']} class="hidden"{/if}>
	
		{foreach $data['machines'] as $machine => $m}
			{if $m@first}
				<tr>{foreach $data['times'] as $t}<td class="times">{$t}</td>{/foreach}</tr>
			{/if}
			<tr>

			{foreach $data['times'] as $time => $t}
				<td class="cell">
					{if $data['slots'][$day][$time][$machine]['id']}<a href="?origin=laundry&action=laundry_noshow&timeslot={$data['slots'][$day][$time][$machine]['timeslot']}" class="delete"><i class="fa fa-eye{if !$data['slots'][$day][$time][$machine]['noshow']}-slash{/if}"></i></a>{/if}
					
					<a href="?origin=laundry&action=laundry_edit&timeslot={$data['slots'][$day][$time][$machine]['timeslot']}" class="edit"><span class="transparent">{$data['slots'][$day][$time][$machine]['machinename']}</span>&nbsp;
				{if $data['slots'][$day][$time][$machine]['people_id']}
					<span class="{if $data['slots'][$day][$time][$machine]['noshow']}strikethrough{/if}">{$data['slots'][$day][$time][$machine]['firstname']} {$data['slots'][$day][$time][$machine]['lastname']} {if $data['slots'][$day][$time][$machine]['container']}({$data['slots'][$day][$time][$machine]['container']}){/if}</span>
				{/if}
				</a></td>
			{/foreach}
			</tr>
			

		{/foreach}
		</table>
	{/foreach}

	</div>
</div>