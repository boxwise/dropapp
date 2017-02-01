<?php /* Smarty version Smarty-3.1.18, created on 2015-08-18 11:52:03
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_list_toggle.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1341226328556ef73c9685a3-47786382%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7e2aa369319691cf6b9d16086940e615dc3a8efb' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_list_toggle.tpl',
      1 => 1439565357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1341226328556ef73c9685a3-47786382',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556ef73c9d6717_99937191',
  'variables' => 
  array (
    'column' => 0,
    'row' => 0,
    'listdata' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556ef73c9d6717_99937191')) {function content_556ef73c9d6717_99937191($_smarty_tpl) {?><span class="list-toggle-value hide"><?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']];?>
</span><span data-operation="<?php echo $_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['do'];?>
" class="list-toggle inside-list-start-operation stay <?php if ($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']]) {?>active<?php }?>">
	<span class="fa fa-circle-o"></span>
	<span class="fa fa-check-circle active"></span>
</span><?php }} ?>
