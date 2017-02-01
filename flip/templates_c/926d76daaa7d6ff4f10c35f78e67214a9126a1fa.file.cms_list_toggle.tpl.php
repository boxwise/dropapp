<?php /* Smarty version Smarty-3.1.18, created on 2017-01-19 18:35:47
         compiled from "/home/drapeton/market/50-back/templates/cms_list_toggle.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16144486375880eae3722b39-59899600%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '926d76daaa7d6ff4f10c35f78e67214a9126a1fa' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_list_toggle.tpl',
      1 => 1484776982,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16144486375880eae3722b39-59899600',
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
  'unifunc' => 'content_5880eae3917a21_05703714',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5880eae3917a21_05703714')) {function content_5880eae3917a21_05703714($_smarty_tpl) {?><span class="list-toggle-value hide"><?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']];?>
</span><span data-operation="<?php echo $_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['do'];?>
" class="list-toggle inside-list-start-operation stay <?php if ($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']]) {?>active<?php }?>">
	<span class="fa fa-circle-o"></span>
	<span class="fa fa-check-circle active"></span>
</span><?php }} ?>
