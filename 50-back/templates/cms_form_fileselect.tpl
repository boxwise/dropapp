	<div class="form-group{if $element['hidden']} hidden{/if}" id="div_{$element['field']}">
		<input type="hidden" name="__{$element['field']}" value="fileselect {$element['filetype']} {if $element['readonly']}readonly{/if}">
		<input type="hidden" name="__{$element['field']}resize" value="{$element['resizeproperties']}">
		<label for="field_{$element['field']}" class="control-label col-sm-2">{$element['label']}</label>
		<div class="col-sm-10 input-element">

			{if $element['filetype']=='image'}
				<a href="{$flipdir}/filemanager/dialog.php?type=1{if $element['folder']}&fldr={$element['folder']}{/if}&field_id=field_{$element['field']}" class="btn btn-sm btn-default popupfilemanager" data-btn-change-msg="{$translate['cms_form_changeimage_msg']}" data-btn-change-label="<i class='fa fa-image'></i> {$translate['cms_form_changeimage']}" data-btn-choose-label="<i class='fa fa-image'></i> {$translate['cms_form_chooseimage']}"><i class="fa fa-image"></i> {if $data[$element['field']]}{$translate['cms_form_changeimage']}{else}{$translate['cms_form_chooseimage']}{/if}</a>
			{else}
				<a href="{$flipdir}/filemanager/dialog.php?type=2{if $element['folder']}&fldr={$element['folder']}{/if}&field_id=field_{$element['field']}" class="btn btn-sm btn-default popupfilemanager" data-btn-change-msg="{$translate['cms_form_changefile_msg']}" data-btn-change-label="<i class='fa fa-file-o'></i> {$translate['cms_form_changefile']}" data-btn-choose-label="<i class='fa fa-file-o'></i> {$translate['cms_form_choosefile']}"><i class="fa fa-file-o"></i> {if $data[$element['field']]}{$translate['cms_form_changefile']}{else}{$translate['cms_form_choosefile']}{/if}</a>			
			{/if}	
			<a href="#" class="btn btn-sm confirm btn-danger file-remove{if $data[$element['field']]} active{/if}" data-btn-ok-label="{$translate['cms_form_delete']}" data-btn-cancel-label="{$translate['cms_form_cancel']}" data-btn-erase-label="<i class='fa fa-eraser'></i> {$translate['cms_form_delete']}" data-btn-delete-label="<i class='fa fa-trash-o'></i> {$translate['cms_form_delete']}" data-title="{$translate['cms_form_deletefileconfirmation']}" data-placement="top" data-original-title="" title=""><i class="fa fa-trash-o"></i> {$translate['cms_form_delete']}</a>
			<input type="hidden" class="file_name" name="{$element['field']}" id="field_{$element['field']}" data-fieldid="{$element['fieldid']}" value="{$data[$element['field']]}" />					
			{if $element['tooltip']}{include file="cms_tooltip.tpl" valign=" relative"}{/if}

		{if $data[$element['field']]}
			<div class="file-preview active">			
				<input type="hidden" name="file_prev" class="file_prev" value="{$data[$element['field']]}" />
			{if $element['filetype']=='image'}
				<img src="{$element['preview']}" width="200"{if $element['background']} style="background-color: {$element['background']}"{/if} />
			{else}
				<a href="{$data[$element['field']]}" target="_blank"><i class="fa glyphicon fa-file-{$element['icon']}"></i>{$element['basename']}</a>
			{/if}
			</div>	
		{else}
			<div class="file-preview">
				<input type="hidden" name="file_prev" class="file_prev" value="" />			
			{if $element['filetype']=='image'}
				<img src="" width="200"{if $element['background']} style="background-color: {$element['background']}"{/if} />
			{else}
				<a href="" target="_blank"><i class="fa glyphicon"></i> <span>filename here</span></a>
			{/if}			
			</div>
		{/if}
		</div>
	</div>