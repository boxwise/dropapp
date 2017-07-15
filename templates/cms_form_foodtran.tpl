{if $element['maxheight']}
<div class="table-parent" data-maxheight="{$element['maxheight']}">
{/if}
<div class="sticky-header-container">
	<table class="table list-foodtran">
		<thead>
			<tr>
			{foreach $element['listdata'] as $key=>$column}
		  		<th><div {if $column['width']}style="width:{$column['width']}px;"{/if}>{$column['label']}</div></th>
			{/foreach}
		  	</tr>
		</thead>
		<tbody>
			{foreach $element['data'] as $row} 
			{if !$row['hidden']}
			<tr>
				{foreach $element['listdata'] as $key=>$column}
				{if $column['type']=="input"}
				<td> 
					<input type="number" name="ftran[{$row['id']}][{$column['food_id']}]" placeholder="{$row[$key]}" class="form-control">
				</td>
				{else}
				<td class="list-level-0 list-column-{$key}">
					{$row[$key]}
				</td>
				{/if}
				{/foreach}
			{/if}
			{/foreach}
		</tbody>
	</table>
</div>
{if $element['maxheight']}
</div>
{/if}
