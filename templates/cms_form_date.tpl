	<div class="form-group{if $element['hidden']} hidden{/if}" id="div_{$element['field']}">
		<input type="hidden" name="__{$element['field']}" value="date format({$element['dateformat']}) {if $element['readonly']}readonly{/if}">
		<label for="field_{$element['field']}" class="control-label col-sm-2">{$element['label']}</label>
		<div class="col-sm-{if $element['width']>0 and $element['width']<11}{$element['width']}{else}4{/if} input-element">
			<div id="field_{$element['field']}_datepicker" class='input-group date' data-locale="{$lan}" data-format="{$element['dateformat']}">
				<input type="text" id="field_{$element['field']}" name="{$element['field']}" 
					class="form-control" 
					value="{$data[$element['field']]}" 
					{if $element['required']}required{/if} 
					{if $element['onblur']}onblur="{$element['onblur']|escape};"{/if}
					{if $element['onchange']}onchange="{$element['onchange']|escape};"{/if}
					{if $element['onkeyup']}onkeyup="{$element['onkeyup']|escape};"{/if}
					{if isset($element['testid'])}data-testid="{$element['testid']}"{/if}
				/>
				<span class="input-group-addon"><span class="fa {if $element['date']}fa-calendar{else}fa-clock-o{/if}"></span></span>
			</div>
			{if $element['tooltip']}{include file="cms_tooltip.tpl"}{/if}
		</div>
	</div>
