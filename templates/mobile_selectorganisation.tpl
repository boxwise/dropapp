<h2 class="page-header">Select an organisation</h2>
<div class="btn-list">
	{foreach $organisations as $key=>$organisation}
		<a class="btn" href="{$smarty.server.REQUEST_URI}&organisation={$organisation['id']}">{$organisation['label']}</a>
	{/foreach}
</div>
