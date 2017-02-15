<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 18:00:30
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_form_text.tpl" */ ?>
<?php /*%%SmartyHeaderCode:168400978158a47b1e7f0234-77420804%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '59bfb28681c36d30b9bb5aba16ef1dbe83f63302' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/cms_form_text.tpl',
      1 => 1486395477,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '168400978158a47b1e7f0234-77420804',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'element' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a47b1e85a920_84347727',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a47b1e85a920_84347727')) {function content_58a47b1e85a920_84347727($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
">
		<input type="hidden" name="__<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="text <?php if ($_smarty_tpl->tpl_vars['element']->value['format']!='') {?><?php echo $_smarty_tpl->tpl_vars['element']->value['format'];?>
<?php }?> <?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']) {?>readonly<?php }?>">
		<label for="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="control-label col-sm-2"><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</label>
		<div class="col-sm-<?php if ($_smarty_tpl->tpl_vars['element']->value['width']>0&&$_smarty_tpl->tpl_vars['element']->value['width']<11) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['width'];?>
<?php } else { ?>6<?php }?> input-element <?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?>has-tooltip<?php }?>">
			<?php if ($_smarty_tpl->tpl_vars['element']->value['locked']) {?><div class="input-group locked"><?php }?>
	 		<input type="text" id="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" 
	 			<?php if ($_smarty_tpl->tpl_vars['element']->value['maxlength']) {?>data-max-count="<?php echo $_smarty_tpl->tpl_vars['element']->value['maxlength'];?>
"<?php }?> 
	 			class="form-control<?php if ($_smarty_tpl->tpl_vars['element']->value['format']) {?> cms-form-<?php echo $_smarty_tpl->tpl_vars['element']->value['format'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['element']->value['setformtitle']) {?> setformtitle<?php }?>" 
	 			value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']], ENT_QUOTES, 'UTF-8', true);?>
" 
	 			
	 			<?php if (isset($_smarty_tpl->tpl_vars['element']->value['onchange'])||$_smarty_tpl->tpl_vars['element']->value['format']) {?>
	 				onchange="<?php if ($_smarty_tpl->tpl_vars['element']->value['format']) {?>cms_form_<?php echo $_smarty_tpl->tpl_vars['element']->value['format'];?>
('<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
');<?php }?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onchange'], ENT_QUOTES, 'UTF-8', true);?>
;"
	 			<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['element']->value['disableautocomplete']) {?>autocomplete="new-password"<?php }?>
	 			onkeyup="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['element']->value['onkeyup']);?>
;" 
	 			onblur="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['element']->value['onblur']);?>
;" 
				<?php if ($_smarty_tpl->tpl_vars['element']->value['minlength']) {?>minlength="<?php echo $_smarty_tpl->tpl_vars['element']->value['minlength'];?>
"<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']||$_smarty_tpl->tpl_vars['element']->value['locked']) {?>readonly<?php }?> 
				<?php if ($_smarty_tpl->tpl_vars['element']->value['required']) {?>required<?php }?>
			>
			<?php if ($_smarty_tpl->tpl_vars['element']->value['locked']) {?><span class="input-group-btn"><button class="btn btn-default unlock" type="button"><span class="fa"></span></button></span></div><?php }?>
			<?php if ($_smarty_tpl->tpl_vars['element']->value['maxlength']) {?><p class="counter-parent safe"><span class="counter"><?php echo $_smarty_tpl->tpl_vars['element']->value['maxlength'];?>
</span> tekens over van <?php echo $_smarty_tpl->tpl_vars['element']->value['maxlength'];?>
</p><?php }?>
			<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('valign'=>" middle"), 0);?>
<?php }?>
			</div>
	</div>
<?php }} ?>
