{include file="cms_header.tpl"}
	<div class="login-reset-template" style="background-image: url(assets/img/background-1.jpg);">
		<h1>{$translate['site_name']}</h1>
		<form id="reset" class="well-center login-reset-form form" data-ajax="1" data-action="reset" method="post">
			<h2>{$translate['cms_reset_pagetitle']}</h2>
			<p>{$translate['cms_reset_description']}</p>
			<div class="form-group">
				<input class="form-control" type="email" name="email" id="email" placeholder="{$translate['cms_login_email']}" required autofocus />	
			</div>
			<input class="btn btn-success" type="submit" value="{$translate['cms_reset_submit']}" />
			<a class="forgot-password" href="{$settings['rootdir']}/login.php">{$translate['cms_login_pagetitle']}</a>
		</form>
	</div>
{include file="cms_footer.tpl"}