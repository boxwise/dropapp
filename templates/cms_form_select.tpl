	<div class="form-group{if $element['hidden']} hidden{/if}" id="div_{$element['field']}">
		<input type="hidden" name="__{$element['field']}" value="select {if $element['multiple']}multiple{/if}{if $element['readonly']}readonly{/if}">
		<label for="field_{$element['field']}" class="control-label col-sm-2">{$element['label']}</label>
		<div class="col-sm-{if $element['width']>0 and $element['width']<11}{$element['width']}{else}6{/if} input-element {if $element['tooltip']}has-tooltip{/if}">
			<select id="field_{$element['field']}" name="{$element['field']}[]" 
				{if $element['multiple']}multiple{/if} class="select2 form-control" 
				data-placeholder="{if isset($element['placeholder'])}{$element['placeholder']}{else}{$translate['cms_form_selectplaceholder']}{/if}"
				{if $element['required']}required{/if} 
				{if $element['formatlist']} data-format-list="{$element['formatlist']}"{/if}
				{if isset($element['onchange'])}onchange="{$element['onchange']|escape};"{/if}
				{if isset($element['testid'])}data-testid="{$element['testid']}"{/if}
			>
			<option></option>
			{foreach $element['options'] as $option}
				<option 
					{if $data[$element['field']]==$option['value']}selected {/if}
					{if $element['multiple'] && $option['selected']}selected {/if}
					{if $option['disabled']}disabled{/if}
					{if isset($option['level'])} data-level="{($option['level'])+1}" {/if}
					{if $option['value2']} data-value2="{$option['value2']}" {/if}
					{if isset($option['price'])} data-price="{$option['price']}"{/if}
					value="{$option['value']}" >{$option['label']}
				</option> 
			{/foreach}
			</select>				            	
			{if $element['tooltip']}{include file="cms_tooltip.tpl"}{/if}
		</div>
	</div>