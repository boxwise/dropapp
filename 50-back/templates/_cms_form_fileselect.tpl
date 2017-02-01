	<div class="form-group" id="div_{$element['field']}">
		<input type="hidden" name="__{$element['field']}" value="fileselect {$element['filetype']} {if $element['readonly']}readonly{/if}">
		<input type="hidden" name="__{$element['field']}resize" value="{$element['resizeproperties']}">
		<label for="field_{$element['field']}" class="control-label col-sm-2">{$element['label']}</label>
		<div class="col-sm-10">
			<a href="{$flipdir}/filemanager/dialog.php?type={if $element['filetype']=='image'}1{else}2{/if}{if $element['folder']}&fldr={$element['folder']}{/if}&field_id=field_{$element['field']}" class="btn btn-default popupfilemanager">{if $element['filetype']=='image'}{$translate['cms_form_chooseimage']}{else}{$translate['cms_form_choosefile']}{/if}</a>
			<input type="hidden" name="{$element['field']}" id="field_{$element['field']}" value="{$data[$element['field']]}" /><br/>			
			{if $element['tooltip']}{include file="cms_tooltip.tpl"}{/if}

		{if $data[$element['field']]}
			{if $element['filetype']=='image'}
			<div>
				<img src="{$data[$element['field']]}" style="height:100px; border:1px solid #ccc;margin-top:15px;" />
			</div>
			{else}
			<div>
				<i class="fa glyphicon fa-file-{$element['icon']}"></i> <a href="{$data[$element['field']]}" src="" />{$translate['cms_form_viewfile']}</a>
			</div>
			{/if}
		{/if}
		</div>
	</div>