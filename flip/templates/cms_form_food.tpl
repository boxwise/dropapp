	<div class="col-md-6 form-group{if $element['hidden']} hidden{/if}" id="div_{$element['field']}">
		<input type="hidden" name="__field_amount[{$element['field']}]" value="text {if $element['format']!=""}{$element['format']}{/if} {if $element['readonly']}readonly{/if}">
		<label for="field_amount[{$element['field']}]" class="control-label col-sm-4 label-two-layers">
			{$element['label']} 
			<small class="light">({$data['name'][$element['field']]|escape:'html'})</small>
		</label>


		<div class="col-sm-4 input-element input-element-small">
	 		<input type="number" id="field_amount[{$element['field']}]" name="field_amount[{$element['field']}]" 
	 			class="form-control" 
	 			value="{$data['available'][$element['field']]|escape:'html'}"
	 			min="0" max="{$data['available'][$element['field']]}">
	 		<small class="light">{$data['available'][$element['field']]} of {$data['maxamount'][$element['field']]} available</small>
		</div>


	</div>
