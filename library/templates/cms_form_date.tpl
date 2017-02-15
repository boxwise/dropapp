	<div class="form-group{if $element['hidden']} hidden{/if}" id="div_{$element['field']}">
		<input type="hidden" name="__{$element['field']}" value="date format({$element['dateformat']}) {if $element['readonly']}readonly{/if}">
		<label for="field_{$element['field']}" class="control-label col-sm-2">{$element['label']}</label>
		<div class="col-sm-{if $element['width']>0 and $element['width']<11}{$element['width']}{else}4{/if} input-element">
			<div class='input-group date' data-language="{$lan}" data-date-format="{$element['dateformat']}" data-pick-date="{if $element['date']}1{else}0{/if}" data-pick-time="{if $element['time']}1{else}0{/if}">
				<input type="text" id="field_{$element['field']}" name="{$element['field']}" 
					class="form-control" 
					value="{$data[$element['field']]}" 
					{if $element['required']}required{/if} 
					{if $element['onblur']}onblur="{$element['onblur']|escape};"{/if}
					{if $element['onchange']}onchange="{$element['onchange']|escape};"{/if}
					{if $element['onkeyup']}onkeyup="{$element['onkeyup']|escape};"{/if}
				/>
				<span class="input-group-addon"><span class="fa {if $element['date']}fa-calendar{else}fa-clock-o{/if}"></span></span>
			</div>
			{if $element['tooltip']}{include file="cms_tooltip.tpl"}{/if}
		</div>
	</div>
