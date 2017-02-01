<?php /* Smarty version Smarty-3.1.18, created on 2014-07-08 14:13:25
         compiled from "./templates/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:77473797553bbe065b30e57-83366088%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3a4f6f0d327fc7bc3ea86f63906a1bf934ca50c7' => 
    array (
      0 => './templates/footer.tpl',
      1 => 1404821409,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '77473797553bbe065b30e57-83366088',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_53bbe065b34027_86613785',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53bbe065b34027_86613785')) {function content_53bbe065b34027_86613785($_smarty_tpl) {?>
	<footer>
		<div class="container">
			<div class="footer-description"><div class="eulogo">European Union</div><div class="footer-description-text">This project has received funding from the European Union’s Seventh Framework Programme for research, technological development and demonstration under grant agreement no 613169.</div></div>
			<div class="footer-copyright">The content of this website does not reflect the official opinion of the European Union. Responsibility for the information and views expressed therein lies entirely with the author(s).<br/><br/>&copy;2014 TRANsformative Social Innovation Theory (TRANSIT)</div>
		</div>
	</footer>
	<section class="subscribe">
		<div class="container">
			<div class="col-lg-7 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 subscribeform">
				<p><span class="highlight">Stay informed. Subscribe for project updates by e-mail</span></p>
				<form id="newslettersubscribe" action="/mailing/subscribe.php" method="post">
					<div class="formrow"><input type="text" class="input-text input-medium required" name="name" id="name" value="" placeholder="name"></div>
					<div class="formrow"><input type="text" class="input-text input-medium required email" name="email" id="email" value="" placeholder="e-mail address"></div>
					<div class="formrow"><input type="submit" value="Subscribe" class="submit-newsletter" id="newslettersubmit"><img src="/site/gfx/spinner.gif" id="loader" alt="loader" /></div>
				</form>					
			</div>		
		</div>
	</section>
</body>
</html><?php }} ?>
