<div class="sticky-header-container">
	<table class="table list">
		{if $element['head']}
		<thead>
			<tr>
			{foreach $element['listdata'] as $key => $column}
		  		<th>{$column['label']}</th>
			{/foreach}
		  	</tr>
		</thead>
		{/if}
		<tbody>
			<tr>
			{foreach $element['listdata'] as $key => $column}
				<td>
					<select id="field_{$column['field']}" name="dist[{$column['field']}]" 
					class="select2 form-control"
					value = "{$data[$column['field']]}"
					data-placeholder="{if isset($element['placeholder'])}{$element['placeholder']}{else}{$translate['cms_form_selectplaceholder']}{/if}"
					{if $column['required']}required{/if} 
					{if isset($element['onchange'])}onchange="{$element['onchange']|escape};"{/if}
					>
						<option></option>
						{foreach $element['options'] as $option}
							<option 
							{if $data[$column['field']]==$option['value']}selected {/if}
							value="{$option['value']}">{$option['label']}
							</option> 
						{/foreach}	
					</select>
				</td>
			{/foreach}	
		  	</tr>
		</tbody>
	</table>
</div>
