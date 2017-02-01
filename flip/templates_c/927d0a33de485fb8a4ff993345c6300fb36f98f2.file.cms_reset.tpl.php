<?php /* Smarty version Smarty-3.1.18, created on 2016-11-04 12:42:54
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_reset.tpl" */ ?>
<?php /*%%SmartyHeaderCode:675613690581c743e3c9068-98122801%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '927d0a33de485fb8a4ff993345c6300fb36f98f2' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_reset.tpl',
      1 => 1477491598,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '675613690581c743e3c9068-98122801',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'settings' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_581c743e5cba93_59862750',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_581c743e5cba93_59862750')) {function content_581c743e5cba93_59862750($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	<div class="login-reset-template">
		<h1><?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
</h1>
		<form id="reset" class="well-center login-reset-form form" data-ajax="1" data-action="reset" method="post">
			<h2><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_reset_pagetitle'];?>
</h2>
			<p><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_reset_description'];?>
</p>
			<div class="form-group">
				<input class="form-control" type="email" name="email" id="email" placeholder="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_email'];?>
" required autofocus />	
			</div>
			<input class="btn btn-success" type="submit" value="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_reset_submit'];?>
" />
			<a class="forgot-password" href="/flip/login.php"><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_pagetitle'];?>
</a>
		</form>
	</div>
<?php echo $_smarty_tpl->getSubTemplate ("cms_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
