<div class="form-group{if $element['hidden']} hidden{/if}" id="div_{$element['field']}">
	<label for="field_{$element['field']}" class="control-label col-sm-2"></label>
	<div class="col-sm-{if $element['width']>0 and $element['width']<11}{$element['width']}{else}6{/if}">
		<a href="{$element['url']}" target="preview" class="btn btn-default">{$element['label']}</a>
	</div>
</div>
