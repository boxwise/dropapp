<?php /* Smarty version Smarty-3.1.18, created on 2017-02-14 14:17:31
         compiled from "/Users/maartenhunink/Sites/themarket/50-back/templates/cms_list_toggle.tpl" */ ?>
<?php /*%%SmartyHeaderCode:127641326458a2f55bdc51d6-68361350%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '69d97cac8e018f9c5ed9cce3567625e72e54170d' => 
    array (
      0 => '/Users/maartenhunink/Sites/themarket/50-back/templates/cms_list_toggle.tpl',
      1 => 1486415168,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '127641326458a2f55bdc51d6-68361350',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'column' => 0,
    'row' => 0,
    'listdata' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a2f55be73c02_95837220',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a2f55be73c02_95837220')) {function content_58a2f55be73c02_95837220($_smarty_tpl) {?><span class="list-toggle-value hide"><?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']];?>
</span><span data-operation="<?php echo $_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['do'];?>
" class="list-toggle inside-list-start-operation stay <?php if ($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']]) {?>active<?php }?>">
	<span class="fa fa-circle-o"></span>
	<span class="fa fa-check-circle active"></span>
</span><?php }} ?>
