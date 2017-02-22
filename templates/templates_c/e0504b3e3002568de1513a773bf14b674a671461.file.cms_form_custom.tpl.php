<?php /* Smarty version Smarty-3.1.18, created on 2017-02-16 18:10:16
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_form_custom.tpl" */ ?>
<?php /*%%SmartyHeaderCode:43676719458a5cee813e3a4-51762689%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e0504b3e3002568de1513a773bf14b674a671461' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/cms_form_custom.tpl',
      1 => 1486395473,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '43676719458a5cee813e3a4-51762689',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'element' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a5cee8154ce2_47479675',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a5cee8154ce2_47479675')) {function content_58a5cee8154ce2_47479675($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['element']->value['field']);?>
">
		<label for="field" class="control-label col-sm-2"><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</label>
		<div class="col-sm-<?php if ($_smarty_tpl->tpl_vars['element']->value['width']>0&&$_smarty_tpl->tpl_vars['element']->value['width']<11) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['width'];?>
<?php } else { ?>6<?php }?>">
	 		<p class="form-control-static"><?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
</p>
			<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
		</div>
	</div>
<?php }} ?>
