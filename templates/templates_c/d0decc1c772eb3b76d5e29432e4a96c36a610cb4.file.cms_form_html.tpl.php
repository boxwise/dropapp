<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 22:44:21
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_form_html.tpl" */ ?>
<?php /*%%SmartyHeaderCode:128893007858a4bda53ebbb6-41278671%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd0decc1c772eb3b76d5e29432e4a96c36a610cb4' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/cms_form_html.tpl',
      1 => 1486395475,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '128893007858a4bda53ebbb6-41278671',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'element' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a4bda5401681_65977776',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a4bda5401681_65977776')) {function content_58a4bda5401681_65977776($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>">
		<label for="field" class="control-label col-sm-2"><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</label>
		<div class="col-sm-<?php if ($_smarty_tpl->tpl_vars['element']->value['width']>0&&$_smarty_tpl->tpl_vars['element']->value['width']<11) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['width'];?>
<?php } else { ?>6<?php }?>">
	 		<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>

			<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
		</div>
	</div>
<?php }} ?>
