<?php /* Smarty version Smarty-3.1.18, created on 2016-02-19 12:37:10
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_reset.tpl" */ ?>
<?php /*%%SmartyHeaderCode:105907227556dd033322096-42973811%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c89add7733b40936ba5696caec72e1e9018b82af' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_reset.tpl',
      1 => 1439565357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '105907227556dd033322096-42973811',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dd033479837_68633347',
  'variables' => 
  array (
    'settings' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dd033479837_68633347')) {function content_556dd033479837_68633347($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
