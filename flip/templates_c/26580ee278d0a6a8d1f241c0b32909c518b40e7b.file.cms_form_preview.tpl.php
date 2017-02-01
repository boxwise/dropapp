<?php /* Smarty version Smarty-3.1.18, created on 2016-11-10 12:04:09
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_preview.tpl" */ ?>
<?php /*%%SmartyHeaderCode:155225849258245429d26c46-20416611%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '26580ee278d0a6a8d1f241c0b32909c518b40e7b' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_preview.tpl',
      1 => 1477491594,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '155225849258245429d26c46-20416611',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'element' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58245429d44e83_42107146',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58245429d44e83_42107146')) {function content_58245429d44e83_42107146($_smarty_tpl) {?><div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
">
	<label for="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="control-label col-sm-2"></label>
	<div class="col-sm-<?php if ($_smarty_tpl->tpl_vars['element']->value['width']>0&&$_smarty_tpl->tpl_vars['element']->value['width']<11) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['width'];?>
<?php } else { ?>6<?php }?>">
		<a href="<?php echo $_smarty_tpl->tpl_vars['element']->value['url'];?>
" target="preview" class="btn btn-default"><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</a>
	</div>
</div>
<?php }} ?>
