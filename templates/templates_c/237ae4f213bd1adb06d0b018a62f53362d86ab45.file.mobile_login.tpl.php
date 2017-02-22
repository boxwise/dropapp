<?php /* Smarty version Smarty-3.1.18, created on 2017-02-19 13:13:21
         compiled from "/Users/bart/Websites/themarket/library/templates/mobile_login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:183607547458a5d90fcb8448-50405594%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '237ae4f213bd1adb06d0b018a62f53362d86ab45' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/mobile_login.tpl',
      1 => 1487501200,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '183607547458a5d90fcb8448-50405594',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a5d90fcc7166_79053103',
  'variables' => 
  array (
    'translate' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a5d90fcc7166_79053103')) {function content_58a5d90fcc7166_79053103($_smarty_tpl) {?><form id="login" class="well-center login-reset-form form" data-ajax="1" data-action="login" method="post">
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
	<div class="form-group">
		<label for="autologin"><input type='checkbox' name='autologin' id='autologin' value="1"> <?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_autologin'];?>
</label>	
	</div>
	<input class="btn" type="submit" value="<?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_submit'];?>
" />
	<input type="hidden" name="destination" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['destination'];?>
" />
	<input type="hidden" name="action" value="login" />
</label>
</form><?php }} ?>
