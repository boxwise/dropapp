<?php /* Smarty version Smarty-3.1.18, created on 2017-02-13 10:46:07
         compiled from "/Users/maartenhunink/Sites/themarket/50-back/templates/cms_form_custom.tpl" */ ?>
<?php /*%%SmartyHeaderCode:131651683958a1724f5e50a1-42841500%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8fecd72f4498aeedd560f57ffb63dff44eca6212' => 
    array (
      0 => '/Users/maartenhunink/Sites/themarket/50-back/templates/cms_form_custom.tpl',
      1 => 1486415168,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '131651683958a1724f5e50a1-42841500',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'element' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a1724f6b25d5_69406564',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a1724f6b25d5_69406564')) {function content_58a1724f6b25d5_69406564($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['element']->value['field']);?>
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
