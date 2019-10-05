<h2 class="page-header">Select an organisation</h2>
<div class="btn-list">
	{foreach $organisations as $key=>$organisation}
		{if strpos($smarty.server.REQUEST_URI, "?") == true}
			<a class="btn" href="{$smarty.server.REQUEST_URI}&organisation={$organisation['id']}">{$organisation['label']}</a>
		{else}
			<a class="btn" href="{$smarty.server.REQUEST_URI}?organisation={$organisation['id']}">{$organisation['label']}</a>
		{/if}
	{/foreach}
</div>
