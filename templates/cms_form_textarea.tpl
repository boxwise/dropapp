	<div class="form-group{if $element['hidden']} hidden{/if}" id="div_{$element['field']}">
		<input type="hidden" name="__{$element['field']}" value="textarea {if $element['readonly']}readonly{/if}">
		<label for="field_{$element['field']}" class="control-label col-sm-2">{$element['label']}</label>
		<div class="col-sm-{if $element['width']>0 and $element['width']<11}{$element['width']}{else}10{/if} input-element {if $element['tooltip']}has-tooltip{/if}">
			<textarea name="{$element['field']}" {if $element['maxlength']}data-max-count="{$element['maxlength']}"{/if} rows="{if $element['rows']}{$element['rows']}{else}3{/if}" id="field_{$element['field']}" class="form-control"
				{if $element['readonly']}readonly{/if} 
				{if $element['required']}required{/if} 			
 				{if $element['onblur']}onblur="{$element['onblur']|escape};"{/if}
 				{if $element['onchange']}onchange="{$element['onchange']|escape};"{/if}
 				{if $element['onkeyup']}onkeyup="{$element['onkeyup']|escape};"{/if}
				{if isset($element['testid'])}data-testid="{$element['testid']}"{/if}
				>{$data[$element['field']]}</textarea>
			{if $element['maxlength']}<p class="counter-parent safe"><span class="counter">{$element['maxlength']}</span> {$translate['cms_form_charactersleft']|replace:'%n':$element['maxlength']}</p>{/if}
			{if $element['tooltip']}{include file="cms_tooltip.tpl" valign=" middle"}{/if}
		</div>
	</div>
