	<div class="form-group{if $element['hidden']} hidden{/if}" id="div_{$element['field']}">
		<input type="hidden" name="__{$element['field']}" value="text {if $element['format']!=""}{$element['format']}{/if} {if $element['readonly']}readonly{/if}">
		<label for="field_{$element['field']}" class="control-label col-sm-2">{$element['label']}</label>
		<div class="col-sm-{if $element['width']>0 and $element['width']<11}{$element['width']}{else}6{/if} input-element {if $element['tooltip']}has-tooltip{/if}">
			{if $element['locked']}<div class="input-group locked">{/if}
	 		<input type="file" id="field_{$element['field']}" name="{$element['field']}" 
	 			class="form-control{if $element['format']} cms-form-{$element['format']}{/if}" 
	 			value="{$data[$element['field']]}" 
			>
			{if $element['tooltip']}{include file="cms_tooltip.tpl" valign=" middle"}{/if}
			{if $data[$element['field']]}
				<div class="rotate{$data['rotate']}"><img src="/index.php?action=people_photo&id={$data[$element['field']]}" class="people-picture"></div>
			{/if}
		</div>
	</div>
	{if $data[$element['field']]}
	<div class="form-group" id="div_{$element['field']}_delete">
		<input type="hidden" name="__{$element['field']}_delete" value="checkbox">
		<label class="control-label col-sm-2 checkbox-control-label"></label>
		<div class="col-sm-6">
			<label for="field_{$element['field']}_delete" class="checkbox"><input type="checkbox" id="field_{$element['field']}_delete" name="{$element['field']}_delete" value="1"> Delete this picture
			</label>
		</div>
	</div>
	{/if}