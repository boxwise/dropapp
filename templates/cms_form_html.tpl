	<div class="form-group{if $element['hidden']} hidden{/if}">
		<label for="field" class="control-label col-sm-2">{$element['label']}</label>
		<div class="col-sm-{if $element['width']>0 and $element['width']<11}{$element['width']}{else}6{/if}">
	 		{$element['field'] nofilter}
			{if $element['tooltip']}{include file="cms_tooltip.tpl"}{/if}
		</div>
	</div>
