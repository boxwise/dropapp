<?php /* Smarty version Smarty-3.1.18, created on 2017-01-21 17:31:16
         compiled from "/home/drapeton/market/50-back/templates/cms_form_email.tpl" */ ?>
<?php /*%%SmartyHeaderCode:209905441158837ec46bc448-05546039%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '62bc48b9ab5a0ce7ec5d183491164e06fd53a104' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_form_email.tpl',
      1 => 1484776981,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '209905441158837ec46bc448-05546039',
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
  'unifunc' => 'content_58837ec48384d2_16416917',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58837ec48384d2_16416917')) {function content_58837ec48384d2_16416917($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
">
		<input type="hidden" name="__<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="email <?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']) {?>readonly<?php }?>">
		<label for="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="control-label col-sm-2"><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</label>
		<div class="col-sm-<?php if ($_smarty_tpl->tpl_vars['element']->value['width']>0&&$_smarty_tpl->tpl_vars['element']->value['width']<11) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['width'];?>
<?php } else { ?>6<?php }?>">
			<?php if ($_smarty_tpl->tpl_vars['element']->value['locked']) {?><div class="input-group locked"><?php }?>
	 		<input type="email" id="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']];?>
" 
 				<?php if ($_smarty_tpl->tpl_vars['element']->value['onchange']) {?>onchange="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onchange'], ENT_QUOTES, 'UTF-8', true);?>
;"<?php }?>
	 			onkeyup="setExternalText(this, '#test');<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onkeyup'], ENT_QUOTES, 'UTF-8', true);?>
;" 
	 			onblur="setExternalInput(this, '#test');<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onblur'], ENT_QUOTES, 'UTF-8', true);?>
;" 
			<?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']||$_smarty_tpl->tpl_vars['element']->value['locked']) {?>readonly<?php }?> 
			<?php if ($_smarty_tpl->tpl_vars['element']->value['required']) {?>required<?php }?> 
			>
			<?php if ($_smarty_tpl->tpl_vars['element']->value['locked']) {?><span class="input-group-btn"><button class="btn btn-default unlock" type="button"><span class="fa"></span></button></span></div><?php }?>
			<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
		</div>
	</div>
<?php }} ?>
