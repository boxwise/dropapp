<?php /* Smarty version Smarty-3.1.18, created on 2017-01-19 13:13:16
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_number.tpl" */ ?>
<?php /*%%SmartyHeaderCode:77679904458125135c60414-58205930%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a83d779e731dfb2cd6d053c17fb70041361af0aa' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_number.tpl',
      1 => 1484824254,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '77679904458125135c60414-58205930',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58125135d7e695_89507729',
  'variables' => 
  array (
    'element' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58125135d7e695_89507729')) {function content_58125135d7e695_89507729($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
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
	 		<input type="number" pattern="[0-9]*" inputmode="numeric" id="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
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
	 			<?php if (isset($_smarty_tpl->tpl_vars['element']->value['onkeyup'])) {?>
	 				onkeyup="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onkeyup'], ENT_QUOTES, 'UTF-8', true);?>
;"
	 			<?php }?>
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
