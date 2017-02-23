<div class="table-parent zortable" data-table="tablename" data-startlevel="0" data-maxlevel="9999" data-saveonchange="1" data-maxheight="window">

	{include file="cms_tablenav.tpl"}
	
	<table class="table">
	  	<thead>
		  	<tr>
		  		<th>Omschrijving</th>
				<th>Waarde</th>
		  	</tr>
		</thead>
		<tbody>
	      {foreach $rows as $row}
			<tr data-id="{$row['id']}" data-level="0" data-deletable="1" data-selectable="1" class="item level-{$row['level']} sortable clickable">
				<td class="controls-front">
					<div class="td-content" data-sort="project">
						<div class="handle"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
						<label class="item-select-label"><input class="item-select" type="checkbox"></label>
						<a href="#">{$row['description']}</a>
						<div class="parent-indent"></div>
					</div>
				</td>
				<td>
					<div class="td-content" data-sort="">{$row['value']}</div>
				</td>
			</tr>
	       {/foreach}
		</tbody>
	</table>
	<fieldset></fieldset> <!-- all order changes made in zortable are saved here -->
	</div>