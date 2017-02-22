<?php /* Smarty version Smarty-3.1.18, created on 2017-02-17 09:46:22
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:65184035258a4b8b170f4d3-72247108%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '95be628cb64f76e040c27ce63b42f20683266037' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/cms_login.tpl',
      1 => 1487268130,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '65184035258a4b8b170f4d3-72247108',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a4b8b17736d7_68310576',
  'variables' => 
  array (
    'settings' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a4b8b17736d7_68310576')) {function content_58a4b8b17736d7_68310576($_smarty_tpl) {?><?php if (!is_callable('smarty_function_math')) include '/Users/bart/Websites/themarket/library/smarty/libs/plugins/function.math.php';
?><?php echo $_smarty_tpl->getSubTemplate ("cms_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	<div class="login-reset-template" style="background-image: url(assets/img/background-<?php echo smarty_function_math(array('equation'=>'rand(1,5)'),$_smarty_tpl);?>
.jpg);">
		<h1><?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
</h1>
		<form id="login" class="well-center login-reset-form form" data-ajax="1" data-action="login" method="post">
			<h2><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_pagetitle'];?>
</h2>
			<div class="form-group">
				<input class="form-control" type="email" name="email" id="email" placeholder="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_email'];?>
" required autofocus/>	
			</div>
			<div class="form-group">
				<input class="form-control" type="password" name="pass" id="pass" placeholder="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_password'];?>
" required />	
			</div>
			<input class="btn btn-success" type="submit" value="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_submit'];?>
" />
			<label for="autologin"><input type='checkbox' name='autologin' id='autologin' value="1"> <?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_autologin'];?>
</label>
			<a class="forgot-password" href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/reset.php"><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_forgotpassword'];?>
</a>
		</form>
	</div>
<?php echo $_smarty_tpl->getSubTemplate ("cms_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
