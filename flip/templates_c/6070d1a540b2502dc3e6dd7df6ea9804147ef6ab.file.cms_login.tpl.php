<?php /* Smarty version Smarty-3.1.18, created on 2017-02-08 15:13:36
         compiled from "/Users/maartenhunink/Sites/themarket/50-back/templates/cms_login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17714900265899031b32c280-57916019%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6070d1a540b2502dc3e6dd7df6ea9804147ef6ab' => 
    array (
      0 => '/Users/maartenhunink/Sites/themarket/50-back/templates/cms_login.tpl',
      1 => 1486563215,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17714900265899031b32c280-57916019',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5899031b43d1e0_28227067',
  'variables' => 
  array (
    'settings' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5899031b43d1e0_28227067')) {function content_5899031b43d1e0_28227067($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	<div class="login-reset-template">
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
			<input type="hidden" name="destination" value="<?php echo $_GET['destination'];?>
" />
			<label for="autologin"><input type='checkbox' name='autologin' id='autologin' value="1"> <?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_autologin'];?>
</label>
			<a class="forgot-password" href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['rootdir'];?>
/flip/reset.php"><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_forgotpassword'];?>
</a>
		</form>
	</div>
<?php echo $_smarty_tpl->getSubTemplate ("cms_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
