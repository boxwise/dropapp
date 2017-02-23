{include file="cms_header.tpl"}
	<div class="login-reset-template" style="background-image: url(assets/img/background-{math equation='rand(1,5)'}.jpg);">
		<h1>{$settings['site_name']}</h1>
		<form id="reset" class="well-center login-reset-form form" data-ajax="1" data-action="reset2" method="post">
			<input type="hidden" name="hash" value="{$smarty.get.hash|escape:'html'}" />
			<input type="hidden" name="userid" value="{$smarty.get.userid|escape:'html'}" />
			<input type="hidden" name="peopleid" value="{$smarty.get.peopleid|escape:'html'}" />
			<h2>{$translate['cms_reset2_pagetitle']}</h2>
			<p>{$translate['cms_reset2_description']}</p>
			<div class="form-group">
				<input class="form-control" type="password" minlength="8" name="pass" id="pass" placeholder="{$translate['cms_login_password']}" required />	
			</div>
			<div class="form-group">
				<input class="form-control" type="password" name="pass2" id="pass2" equalTo="#pass" placeholder="{$translate['cms_login_repeatpassword']}" required />	
			</div>
			<input class="btn btn-success" type="submit" value="{$translate['cms_reset2_submit']}" />
			<a class="forgot-password" href="{$settings['rootdir']}/login.php">{$translate['cms_login_pagetitle']}</a>
		</form>
	</div>
{include file="cms_footer.tpl"}