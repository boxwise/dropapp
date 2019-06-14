{include file="cms_header.tpl"}
	<div class="login-reset-template">
		<h1>{$translate['site_name']}</h1>
		<div class="well-center">
			<h2>{$translate['cms_error']}</h2>
			<p>{$error}</p>
			{if $smarty.session.user['is_admin']}
			<small>
				{if $line && $file}<p>line {$line} in file {$file}</p>{/if}
				{$backtrace}
			</small>
			{/if}
		</div>
	</div>
{include file="cms_footer.tpl"}