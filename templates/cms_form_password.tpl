	<div class="form-group{if $element['hidden']} hidden{/if}" id="div_{$element['field']}">
		<input type="hidden" name="__{$element['field']}" value="password {if $element['readonly']}readonly{/if}">
		<label for="field_{$element[2]}" class="control-label col-sm-2">{$element['label']}</label>
		<div class="col-sm-{if $element['width']>0 and $element['width']<11}{$element['width']}{else}6{/if} {if $element['tooltip']}has-tooltip{/if}">
			<input type="password" id="field_{$element['field']}" 
				name="{$element['field']}" 
				{if $element['minlength']}minlength="{$element['minlength']}"{/if} 
				class="form-control"  
				
 				{if $element['onblur']}onblur="{$element['onblur']|escape};"{/if}
 				{if $element['onchange']}onchange="{$element['onchange']|escape};"{/if}
 				{if $element['onkeyup']}onkeyup="{$element['onkeyup']|escape};"{/if}
				{if $element['disableautocomplete']}autocomplete="new-password"{/if}
				
				{if $element['readonly'] || $element['locked']}readonly{/if} 
				{if $element['required']}required{/if}
				{if isset($element['testid'])}data-testid="{$element['testid']}"{/if}>
			{if $element['tooltip']}{include file="cms_tooltip.tpl"}{/if}
		</div>
	</div>
	{if $element['repeat']}
		<div class="form-group">
			<label for="field_{$element['field']}2" class="control-label col-sm-2">{$translate['cms_login_repeatpassword']}</label>
			<div class="col-sm-6">
				<input type="password" id="field_{$element['field']}2" name="{$element['field']}2" class="form-control" equalTo="#field_{$element['field']}" {if $element['required']}required{/if}>
			</div>
		</div>
	{/if}
			
