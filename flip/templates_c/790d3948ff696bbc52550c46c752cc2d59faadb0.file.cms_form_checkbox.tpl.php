<?php /* Smarty version Smarty-3.1.18, created on 2017-01-19 18:44:22
         compiled from "/home/drapeton/market/50-back/templates/cms_form_checkbox.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8001344765880ece6917a17-32621111%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '790d3948ff696bbc52550c46c752cc2d59faadb0' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_form_checkbox.tpl',
      1 => 1484776980,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8001344765880ece6917a17-32621111',
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
  'unifunc' => 'content_5880ece6987cf1_65136013',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5880ece6987cf1_65136013')) {function content_5880ece6987cf1_65136013($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
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
