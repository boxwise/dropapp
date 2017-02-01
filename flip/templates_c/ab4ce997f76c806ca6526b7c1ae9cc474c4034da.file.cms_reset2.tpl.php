<?php /* Smarty version Smarty-3.1.18, created on 2016-02-19 12:37:27
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_reset2.tpl" */ ?>
<?php /*%%SmartyHeaderCode:233604030556dd04a351092-61691533%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ab4ce997f76c806ca6526b7c1ae9cc474c4034da' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_reset2.tpl',
      1 => 1439565357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '233604030556dd04a351092-61691533',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dd04a3c34b4_71464262',
  'variables' => 
  array (
    'settings' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dd04a3c34b4_71464262')) {function content_556dd04a3c34b4_71464262($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	<div class="login-reset-template">
		<h1><?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
</h1>
		<form id="reset" class="well-center login-reset-form form" data-ajax="1" data-action="reset2" method="post">
			<input type="hidden" name="hash" value="<?php echo $_GET['hash'];?>
" />
			<input type="hidden" name="userid" value="<?php echo $_GET['userid'];?>
" />
			<h2><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_reset2_pagetitle'];?>
</h2>
			<p><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_reset2_description'];?>
</p>
			<div class="form-group">
				<input class="form-control" type="password" minlength="8" name="pass" id="pass" placeholder="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_password'];?>
" required />	
			</div>
			<div class="form-group">
				<input class="form-control" type="password" name="pass2" id="pass2" equalTo="#pass" placeholder="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_repeatpassword'];?>
" required />	
			</div>
			<input class="btn btn-success" type="submit" value="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_reset2_submit'];?>
" />
			<a class="forgot-password" href="/flip/login.php"><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_pagetitle'];?>
</a>
		</form>
	</div>
<?php echo $_smarty_tpl->getSubTemplate ("cms_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
