<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 19:38:10
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_list_toggle.tpl" */ ?>
<?php /*%%SmartyHeaderCode:95802825558a49202adfef0-27080930%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '790117819a6e73bbca8558f2fbe798028a6e7676' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/cms_list_toggle.tpl',
      1 => 1486395481,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '95802825558a49202adfef0-27080930',
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
  'unifunc' => 'content_58a49202b28c18_09331997',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a49202b28c18_09331997')) {function content_58a49202b28c18_09331997($_smarty_tpl) {?><span class="list-toggle-value hide"><?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']];?>
</span><span data-operation="<?php echo $_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['do'];?>
" class="list-toggle inside-list-start-operation stay <?php if ($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']]) {?>active<?php }?>">
	<span class="fa fa-circle-o"></span>
	<span class="fa fa-check-circle active"></span>
</span><?php }} ?>
