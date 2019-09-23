	<div class="form-group{if $element['hidden']} hidden{/if}" id="div_{$element['field']}">
		<input type="hidden" name="__{$element['field']}" value="email {if $element['readonly']}readonly{/if}">
		<label for="field_{$element['field']}" class="control-label col-sm-2">{$element['label']}</label>
		<div class="col-sm-{if $element['width']>0 and $element['width']<11}{$element['width']}{else}6{/if}">
			{if $element['locked']}<div class="input-group locked">{/if}
	 		<input type="email" id="field_{$element['field']}" name="{$element['field']}" class="form-control" value="{$data[$element['field']]}" 
 				{if $element['onchange']}onchange="{$element['onchange']|escape};"{/if}
	 			onkeyup="setExternalText(this, '#test');{$element['onkeyup']|escape};" 
	 			onblur="setExternalInput(this, '#test');{$element['onblur']|escape};" 
				{if $element['readonly'] || $element['locked']}readonly{/if} 
				{if $element['required']}required{/if}
				{if isset($element['testid'])}data-testid="{$element['testid']}"{/if}
			>
			{if $element['locked']}<span class="input-group-btn"><button class="btn btn-default unlock" type="button"><span class="fa"></span></button></span></div>{/if}
			{if $element['tooltip']}{include file="cms_tooltip.tpl"}{/if}
		</div>
	</div>
