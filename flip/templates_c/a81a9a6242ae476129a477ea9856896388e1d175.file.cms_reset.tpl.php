<?php /* Smarty version Smarty-3.1.18, created on 2017-01-21 18:38:18
         compiled from "/home/drapeton/market/50-back/templates/cms_reset.tpl" */ ?>
<?php /*%%SmartyHeaderCode:172338088058839c8a9f4315-95587467%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a81a9a6242ae476129a477ea9856896388e1d175' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_reset.tpl',
      1 => 1484776982,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '172338088058839c8a9f4315-95587467',
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
  'unifunc' => 'content_58839c8aa4d536_31756545',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58839c8aa4d536_31756545')) {function content_58839c8aa4d536_31756545($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
