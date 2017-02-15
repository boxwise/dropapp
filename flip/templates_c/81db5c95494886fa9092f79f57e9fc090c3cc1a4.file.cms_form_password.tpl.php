<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 14:38:53
         compiled from "/Users/maartenhunink/Sites/themarket/50-back/templates/cms_form_password.tpl" */ ?>
<?php /*%%SmartyHeaderCode:51178351858a44bdda01364-04288139%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '81db5c95494886fa9092f79f57e9fc090c3cc1a4' => 
    array (
      0 => '/Users/maartenhunink/Sites/themarket/50-back/templates/cms_form_password.tpl',
      1 => 1486415168,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '51178351858a44bdda01364-04288139',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'element' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a44bdda9f710_52772084',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a44bdda9f710_52772084')) {function content_58a44bdda9f710_52772084($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
">
		<input type="hidden" name="__<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="password <?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']) {?>readonly<?php }?>">
		<label for="field_<?php echo $_smarty_tpl->tpl_vars['element']->value[2];?>
" class="control-label col-sm-2"><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</label>
		<div class="col-sm-<?php if ($_smarty_tpl->tpl_vars['element']->value['width']>0&&$_smarty_tpl->tpl_vars['element']->value['width']<11) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['width'];?>
<?php } else { ?>6<?php }?> <?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?>has-tooltip<?php }?>">
			<input type="password" id="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" 
				name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" 
				<?php if ($_smarty_tpl->tpl_vars['element']->value['minlength']) {?>minlength="<?php echo $_smarty_tpl->tpl_vars['element']->value['minlength'];?>
"<?php }?> 
				class="form-control"  
				
 				<?php if ($_smarty_tpl->tpl_vars['element']->value['onblur']) {?>onblur="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onblur'], ENT_QUOTES, 'UTF-8', true);?>
;"<?php }?>
 				<?php if ($_smarty_tpl->tpl_vars['element']->value['onchange']) {?>onchange="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onchange'], ENT_QUOTES, 'UTF-8', true);?>
;"<?php }?>
 				<?php if ($_smarty_tpl->tpl_vars['element']->value['onkeyup']) {?>onkeyup="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onkeyup'], ENT_QUOTES, 'UTF-8', true);?>
;"<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['element']->value['disableautocomplete']) {?>autocomplete="new-password"<?php }?>
				
				<?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']||$_smarty_tpl->tpl_vars['element']->value['locked']) {?>readonly<?php }?> 
				<?php if ($_smarty_tpl->tpl_vars['element']->value['required']) {?>required<?php }?>>
			<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
		</div>
	</div>
	<?php if ($_smarty_tpl->tpl_vars['element']->value['repeat']) {?>
		<div class="form-group">
			<label for="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
2" class="control-label col-sm-2"><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_login_repeatpassword'];?>
</label>
			<div class="col-sm-6">
				<input type="password" id="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
2" name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
2" class="form-control" equalTo="#field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" <?php if ($_smarty_tpl->tpl_vars['element']->value['required']) {?>required<?php }?>>
			</div>
		</div>
	<?php }?>
			
<?php }} ?>
