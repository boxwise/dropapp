<h2 class="page-header">{$settings['site_name']}</h2>
<form id="login" class="well-center login-reset-form form" data-ajax="1" data-action="login" method="post">
	<h2>{$translate['cms_login_pagetitle']}</h2>
	<div class="form-group">
		<input class="form-control" type="email" name="email" id="email" placeholder="{$translate['cms_login_email']}" required autofocus/>	
	</div>
	<div class="form-group">
		<input class="form-control" type="password" name="pass" id="pass" placeholder="{$translate['cms_login_password']}" required />	
	</div>
	<input class="btn btn-success" type="submit" value="{$translate['cms_login_submit']}" />
	<input type="hidden" name="destination" value="{$data['destination']}" />
	<input type="hidden" name="action" value="login" />
	<label for="autologin"><input type='checkbox' name='autologin' id='autologin' value="1"> {$translate['cms_login_autologin']}</label>
</form>