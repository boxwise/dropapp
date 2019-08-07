<div class="row row-title">
	<div class="col-sm-12">
		<h1>{$title}</h1>
	</div>
</div>
{if $text}<div class="noprint tipofday"><h3>{$text['title']}</h3><p>{$text['body']}</p></div>{/if}
<div class="noprint"><a href="?action={$action}&export=true" class="btn btn-submit btn-success">Download</a>
<a href="/?action={$cancel}" class="btn btn-cancel btn-default">Cancel</a>
</div>
