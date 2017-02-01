	<div class="form-group{if $element['hidden']} hidden{/if}" id="div_{$element['field']}">
		<input type="hidden" name="__{$element['field']}" value="tinymce {if $element['readonly']}readonly{/if}">
		<label for="field_{$element['field']}" class="control-label col-sm-2">{$element['label']}</label>
		<div class="col-sm-{if $element['width']>0 and $element['width']<11}{$element['width']}{else}10{/if}"{if $element['max-width']} style="max-width: {$element['max-width']}"{/if}>
			<textarea name="{$element['field']}" 
				data-tinymce-toolbar-type="{if $element['tinytoolbartype']}{$element['tinytoolbartype']}{else}extended{/if}" 
				data-height="{if $element['height']}{$element['height']}{else}500{/if}"
				data-lan="{$settings['cms_language']}"
				rows="{if $element['rows']}{$element['rows']}{else}5{/if}" 
				id="field_{$element['field']}" 
				class="span{$width} input-xxlarge tinymce">{$data[$element['field']]}</textarea>
			{if $element['tooltip']}{include file="cms_tooltip.tpl" valign="middle"}{/if}
		</div>
	</div>
