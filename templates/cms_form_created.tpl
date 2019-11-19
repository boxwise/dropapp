<div class="created light small" {if isset($element['testid'])}data-testid="{$element['testid']}"{/if}>
	{if $data['created']}
		{$translate['cms_form_createdby']} {if $data['created_by']}{$data['created_by']}{else}<em>{$translate['cms_form_createdunknown']}</em>{/if}<br />{$translate['cms_form_ondate']} {$data['created']} <br /><br />
	{/if}
	{if $data['modified']}
		{$translate['cms_form_modifiedby']} {if $data['modified_by']}{$data['modified_by']}{else}<em>{$translate['cms_form_createdunknown']}</em>{/if}<br />{$translate['cms_form_ondate']} {$data['modified']} 
	{/if}
	{if $data['date_of_signature'] && $data['approvalsigned']}
		<br /> <br /> Last signature: {$data['date_of_signature']}
	{/if}
	{if ({$smarty.post.origin} == 'stock')}
		<br /> <br /><i class="fa fa-history"></i> <a href="/ajax.php?file=history&table={if $table}{$table nofilter}{else}{$smarty.get.action}{/if}&id={$data['id']}" class="fancybox" data-fancybox-type="ajax">{$translate['cms_form_view_modified']}</a>
	{/if}
</div>