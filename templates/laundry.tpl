<div class="row">
	<div class="col-sm-11">

		<div class="row row-title">
			<div class="col-sm-12">
				<h1>{$data['stationname']}</h1>
			</div>
		</div>

{*
<a class="new-page item-add btn btn-sm btn-default" href="?action=laundry&cycle=current"><i class="fa fa-arrow-down"></i> Current cycle</a>
<a class="new-page item-add btn btn-sm btn-default" href="?action=laundry&cycle=next"><i class="fa fa-arrow-right"></i> Next cycle</a>
*}
		<div class="btn-group">
			<div type="button" class="btn btn-sm btn-default dropdown-toggle">Choose laundry station <span class="caret"></span></div>
			<ul class="dropdown-menu pull-right button-multi" role="menu">
			{foreach $data['stationlist'] as $stationid => $stationlabel}
				<li><a href="?action=laundry&station={$stationid}">{$stationlabel}</a></li>
			{/foreach}
			</ul>
		</div>&nbsp;

		{if $smarty.session.user.is_admin or $smarty.session.user.coordinator}
			<a class="new-page item-add btn btn-sm btn-default" href="?action=laundry_startcycle&origin=laundry"><i class="fa fa-recycle"></i> Start new cycle</a>
			<br /><br />
		{/if}

	{foreach $data['dates'] as $day => $d name=days}
		<a name="{$day}"></a>
		<h2><i class="icon-open-{$day} fa fa-angle-right {if !$d['past']}hidden{/if}"></i><i class="icon-close-{$day} fa fa-angle-down {if $d['past']} hidden{/if}"></i> <a href="#" class="laundry-showhide" data-day="{$day}">{$d['label']}</a></h2>
		<table id="laundry-table-{$day}"{if $d['past']} class="hidden"{/if}>
	
		{foreach $data['machines'] as $machine => $m}
			{if $m@first}
				<tr>{foreach $data['times'] as $t}<td class="times">{$t}</td>{/foreach}</tr>
			{/if}
			<tr>

			{foreach $data['times'] as $time => $t}
				<td class="cell">
					{if $data['slots'][$day][$time][$machine]['id']}
{* 					<a href="" class="delete"></a> *}
					<div class="dropdown">
  <a class="dropdown-toggle delete" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-caret-down"></i>
  </a>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    {if !$data['slots'][$day][$time][$machine]['dropoff']}<a class="dropdown-item" href="?origin=laundry&action=laundry_noshow&toggle=noshow&timeslot={$data['slots'][$day][$time][$machine]['timeslot']}&offset={$data['offset']}">{if $data['slots'][$day][$time][$machine]['noshow']}<i class="fa fa-undo"></i> Revert no show{else}<i class="fa fa-ban"></i> No show{/if}</a>{/if}
    {if !$data['slots'][$day][$time][$machine]['noshow']}
		{if !$data['slots'][$day][$time][$machine]['collected']}
    <a class="dropdown-item" href="?origin=laundry&action=laundry_noshow&toggle=dropoff&timeslot={$data['slots'][$day][$time][$machine]['timeslot']}&offset={$data['offset']}">{if $data['slots'][$day][$time][$machine]['dropoff']}<i class="fa fa-undo"></i> Revert drop off{else}<i class="fa fa-sign-in"></i> Dropped off{/if}</a>
    	{/if}
    {if $data['slots'][$day][$time][$machine]['dropoff']}<a class="dropdown-item" href="?origin=laundry&action=laundry_noshow&toggle=collected&timeslot={$data['slots'][$day][$time][$machine]['timeslot']}&offset={$data['offset']}">{if $data['slots'][$day][$time][$machine]['collected']}<i class="fa fa-undo"></i> Revert collect{else}<i class="fa fa-check"></i> Collected{/if}</a>{/if}
    {/if}
  </div>
</div>
{/if}
					<a href="?origin=laundry&action=laundry_edit&timeslot={$data['slots'][$day][$time][$machine]['timeslot']}&offset={$data['offset']}" class="edit"><span class="machine">{$data['slots'][$day][$time][$machine]['machinename']}</span>&nbsp;
				{if $data['slots'][$day][$time][$machine]['people_id']}
					<span class="{if $data['slots'][$day][$time][$machine]['noshow']}strikethrough{/if}{if $data['slots'][$day][$time][$machine]['dropoff']} dropoff{/if}{if $data['slots'][$day][$time][$machine]['collected']} collected{/if}">{$data['slots'][$day][$time][$machine]['firstname']} {$data['slots'][$day][$time][$machine]['lastname']} {if $data['slots'][$day][$time][$machine]['container']}({$data['slots'][$day][$time][$machine]['container']}){/if} {if $data['slots'][$day][$time][$machine]['comment']}<i class="fa fa-comment tooltip-this" title="{$data['slots'][$day][$time][$machine]['comment']|escape}"></i>{/if}{if $data['slots'][$day][$time][$machine]['collected']} <i class="fa fa-check"></i>{elseif $data['slots'][$day][$time][$machine]['dropoff']} <i class="fa fa-sign-in-alt"></i>{/if}</span>
				{/if}
				</a></td>
			{/foreach}
			</tr>
			

		{/foreach}
		</table>
	{/foreach}

	</div>
</div>