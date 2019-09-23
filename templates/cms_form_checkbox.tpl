	<div class="form-group{if $element['hidden']} hidden{/if}" id="div_{$element['field']}">
		<input type="hidden" name="__{$element['field']}" value="checkbox {if $element['readonly']}readonly{/if}">
		<label class="control-label col-sm-2 checkbox-control-label"></label>
		<div class="col-sm-6">
			<label for="field_{$element['field']}" class="checkbox">
			<input type="checkbox" id="field_{$element['field']}" name="{$element['field']}" value="1" 
				{if $data[$element['field']]}checked{/if}  
				{if $element['readonly']}disabled{/if} 
				{if $element['required']}required{/if} 
 				{if $element['onchange']}onchange="{$element['onchange']|escape};"{/if}
				{if isset($element['testid'])}data-testid="{$element['testid']}"{/if}
			> {$element['label']}
				{if $element['tooltip']}{include file="cms_tooltip.tpl"}{/if}
			</label>
		</div>
	</div>
