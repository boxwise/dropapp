<?php /* Smarty version Smarty-3.1.18, created on 2017-02-12 17:24:44
         compiled from "./templates/mobile_login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:631942600589b8485ac3bf9-46887876%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '00c7bead14bfe7888bb88e1308e4463f2955d285' => 
    array (
      0 => './templates/mobile_login.tpl',
      1 => 1486913083,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '631942600589b8485ac3bf9-46887876',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_589b8485ae19e4_58322930',
  'variables' => 
  array (
    'translate' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_589b8485ae19e4_58322930')) {function content_589b8485ae19e4_58322930($_smarty_tpl) {?><form id="login" class="well-center login-reset-form form" data-ajax="1" data-action="login" method="post">
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
	<label for="autologin"><input type='checkbox' name='autologin' id='autologin' value="1"> <?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_autologin'];?>
</label>
	<input class="btn btn-success" type="submit" value="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_submit'];?>
" />
	<input type="hidden" name="destination" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['destination'];?>
" />
	<input type="hidden" name="action" value="login" />
</label>
</form><?php }} ?>
