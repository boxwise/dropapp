{include file="cms_header.tpl"}
	<div class="login-reset-template" style="background-image: url({$data['background']});">
		<div style="margin:0px auto; text-align:center;"><img class="loginLogo" src="../assets/img/boxtribute.png" width="200"></img></div>
		<form id="login" class="well-center login-reset-form form" data-ajax="1" data-action="login" method="post">
			<h2>{$translate['cms_login_pagetitle']}</h2>
			<div class="form-group">
				<input class="form-control" type="email" name="email" id="email" data-testid="email" placeholder="{$translate['cms_login_email']}" required autofocus/>	
			</div>
			<div class="form-group">
				<input class="form-control" type="password" name="pass" id="pass" data-testid="password" placeholder="{$translate['cms_login_password']}" required />	
			</div>
			<input class="btn" type="submit" value="{$translate['cms_login_submit']}" data-testid="signInButton"/>
			<label for="autologin"><input type='checkbox' name='autologin' id='autologin' value="1"> {$translate['cms_login_autologin']}</label>
			<a class="forgot-password" data-testid="forgotPassword" href="/reset.php">{$translate['cms_login_forgotpassword']}</a>
		</form>
	</div>
{include file="cms_footer.tpl"}
