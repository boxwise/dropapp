{include file="cms_header.tpl"}
	<div class="login-reset-template">
		<h1>{$translate['site_name']}</h1>
		<div class="well-center">
			<h2>{$title}</h2>
			<h3>{$error}</h3>
			{if isset($sentry)}
			<h4>{$sentry}</h4>
			{/if}
			{if isset($exception)}
			<small><pre>{$exception}</pre></small>
			{/if}
			
		</div>
	</div>
{include file="cms_footer.tpl"}