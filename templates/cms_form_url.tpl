	<div class="form-group{if $element['hidden']} hidden{/if}" id="div_{$element['field']}">
		<input type="hidden" name="__{$element['field']}" value="text {if $element['readonly'] || $data['url_locked']}readonly{/if}">
		<label for="field_{$element['field']}" class="control-label col-sm-2">{$element['label']}</label>
		<div class="col-sm-{if $element['width']>0 and $element['width']<11}{$element['width']}{else}6{/if} input-element {if $element['tooltip']}has-tooltip{/if}">
			{if !$data['url_locked']}<div class="input-group locked">{/if}
	 		<input type="text" id="field_{$element['field']}" name="{$element['field']}" 
	 			{if $element['maxlength']}data-max-count="{$element['maxlength']}"{/if} 
	 			class="form-control{if $element['format']} cms-form-{$element['format']}{/if}{if $element['setformtitle']} setformtitle{/if}" 
	 			value="{$data[$element['field']]|escape:'html'}" 
	 			
	 			{if isset($element['onchange']) or $element['format']}
	 				onchange="{if $element['format']}cms_form_{$element['format']}('{$element['field']}');{/if}{$element['onchange']|escape};"
	 			{/if}
	 			onkeyup="{$element['onkeyup']|escape:'quotes'};" 
	 			onblur="{$element['onblur']|escape:'quotes'};" 
				{if $element['minlength']}minlength="{$element['minlength']}"{/if}
				{if $element['required']}required{/if} readonly
				{if isset($element['testid'])}data-testid="{$element['testid']}"{/if}
			>
			{if !$data['url_locked']}
				<span class="input-group-btn"><button class="btn btn-default unlock" type="button"><span class="fa"></span></button></span></div>
			{/if}
			
			{if $element['maxlength']}<p class="counter-parent safe"><span class="counter">{$element['maxlength']}</span> tekens over van {$element['maxlength']}</p>{/if}
			{if $element['tooltip']}{include file="cms_tooltip.tpl" valign=" middle"}{/if}
			</div>
		</div>
