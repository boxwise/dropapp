<?php /* Smarty version Smarty-3.1.18, created on 2017-02-08 23:12:35
         compiled from "/Users/maartenhunink/Sites/themarket/50-back/templates/cms_form_checkbox.tpl" */ ?>
<?php /*%%SmartyHeaderCode:169000932589b89c33892c9-78381229%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dd6ca9c55538e56cf152068ae446e76311076195' => 
    array (
      0 => '/Users/maartenhunink/Sites/themarket/50-back/templates/cms_form_checkbox.tpl',
      1 => 1486415168,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '169000932589b89c33892c9-78381229',
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
  'unifunc' => 'content_589b89c3453047_31030500',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_589b89c3453047_31030500')) {function content_589b89c3453047_31030500($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
">
		<input type="hidden" name="__<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="checkbox <?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']) {?>readonly<?php }?>">
		<label class="control-label col-sm-2 checkbox-control-label"></label>
		<div class="col-sm-6">
			<label for="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="checkbox"><input type="checkbox" id="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']]) {?>checked<?php }?>  
			<?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']) {?>disabled<?php }?> 
			<?php if ($_smarty_tpl->tpl_vars['element']->value['required']) {?>required<?php }?> 
 			<?php if ($_smarty_tpl->tpl_vars['element']->value['onchange']) {?>onchange="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onchange'], ENT_QUOTES, 'UTF-8', true);?>
;"<?php }?>
			> <?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>

				<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
			</label>
		</div>
	</div>
<?php }} ?>
