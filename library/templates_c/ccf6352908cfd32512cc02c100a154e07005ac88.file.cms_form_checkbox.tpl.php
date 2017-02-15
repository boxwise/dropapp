<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 19:06:14
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_form_checkbox.tpl" */ ?>
<?php /*%%SmartyHeaderCode:57786492658a48a861fdaf3-24646556%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ccf6352908cfd32512cc02c100a154e07005ac88' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/cms_form_checkbox.tpl',
      1 => 1486395473,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '57786492658a48a861fdaf3-24646556',
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
  'unifunc' => 'content_58a48a86267da6_23103321',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a48a86267da6_23103321')) {function content_58a48a86267da6_23103321($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
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
