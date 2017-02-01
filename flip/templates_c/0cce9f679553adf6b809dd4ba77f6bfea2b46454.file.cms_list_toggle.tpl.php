<?php /* Smarty version Smarty-3.1.18, created on 2016-12-22 21:53:14
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_list_toggle.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1629979842585c2f2ab820b3-76950293%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0cce9f679553adf6b809dd4ba77f6bfea2b46454' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_list_toggle.tpl',
      1 => 1477491597,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1629979842585c2f2ab820b3-76950293',
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
  'unifunc' => 'content_585c2f2ad79c85_16773497',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_585c2f2ad79c85_16773497')) {function content_585c2f2ad79c85_16773497($_smarty_tpl) {?><span class="list-toggle-value hide"><?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']];?>
</span><span data-operation="<?php echo $_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['do'];?>
" class="list-toggle inside-list-start-operation stay <?php if ($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']]) {?>active<?php }?>">
	<span class="fa fa-circle-o"></span>
	<span class="fa fa-check-circle active"></span>
</span><?php }} ?>
