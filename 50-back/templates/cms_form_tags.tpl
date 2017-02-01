	<div class="form-group{if $element['hidden']} hidden{/if}" id="div_{$element['field']}">
		<input type="hidden" name="__{$element['field']}" value="tags {if $element['readonly']}readonly{/if}">
		<label for="field_{$element['field']}" class="control-label col-sm-2">{$element['label']}</label>
		<div class="col-sm-{if $element['width']>0 and $element['width']<11}{$element['width']}{else}6{/if}">
			<textarea id="field_{$element['field']}" name="{$element['field']}[]" class="select2 form-control" 
				data-tags='[{foreach $element['othertags'] as $label name="othertags"}"{$label}"{if not $smarty.foreach.othertags.last}, {/if}{/foreach}]'
				data-placeholder="Choose tags" {if $element['required']}required{/if}>{foreach $element['selectedtags'] as $label name="selectedtags"}{$label}{if not $smarty.foreach.selectedtags.last}, {/if}{/foreach}</textarea>
			{if $element['tooltip']}{include file="cms_tooltip.tpl"}{/if}
		</div>
	</div>
 
