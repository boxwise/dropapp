<div class="created light small">
{if $data['created']}
	{$translate['cms_form_createdby']} {if $data['created_by']}{$data['created_by']}{else}<em>{$translate['cms_form_createdunknown']}</em>{/if}<br />{$translate['cms_form_ondate']} {$data['created']} <br /><br />
{/if}
{if $data['modified']}
	{$translate['cms_form_modifiedby']} {if $data['modified_by']}{$data['modified_by']}{else}<em>{$translate['cms_form_createdunknown']}</em>{/if}<br />{$translate['cms_form_ondate']} {$data['modified']} 
	{if $settings['showhistory']}<br /> <br /><i class="fa fa-history"></i> <a href="/flip/ajax.php?file=history&table={if $table}{$table}{else}{$smarty.get.action}{/if}&id={$data['id']}" class="fancybox" data-fancybox-type="ajax">{$translate['cms_form_view_modified']}</a>{/if}
{/if}
</div>