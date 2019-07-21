<form id="login" class="well-center login-reset-form form" method="post">
	<h2>{$translate['cms_login_pagetitle']}</h2>
	<div class="form-group">
		<input class="form-control" type="email" name="email" id="email" placeholder="{$translate['cms_login_email']}" data-testid="email" required autofocus/>	
	</div>
	<div class="form-group">
		<input class="form-control" type="password" name="pass" id="pass" data-testid="password" placeholder="{$translate['cms_login_password']}" required />	
	</div>
	<div class="form-group">
		<label for="autologin"><input type='checkbox' name='autologin' id='autologin' value="1"> {$translate['cms_login_autologin']}</label>	
	</div>
	<div class="form-group">
		<input class="btn" type="submit" data-testid="signInButton" value="{$translate['cms_login_submit']}" />
	</div>	
	<div class="form-group">
		<a class="forgot-password" data-testid="forgotPassword" href="{$settings['rootdir']}/reset.php">{$translate['cms_login_forgotpassword']}</a>
	</div>		
	<input type="hidden" name="destination" value="{$data['destination']}" />
	<input type="hidden" name="action" value="login" />
</label>
</form>