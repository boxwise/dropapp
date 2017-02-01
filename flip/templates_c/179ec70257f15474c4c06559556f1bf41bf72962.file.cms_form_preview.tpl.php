<?php /* Smarty version Smarty-3.1.18, created on 2015-08-14 17:20:19
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_form_preview.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1402944490556db40d46a290-36274455%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '179ec70257f15474c4c06559556f1bf41bf72962' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_form_preview.tpl',
      1 => 1439565356,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1402944490556db40d46a290-36274455',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556db40d51c467_22520274',
  'variables' => 
  array (
    'element' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556db40d51c467_22520274')) {function content_556db40d51c467_22520274($_smarty_tpl) {?><div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
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
