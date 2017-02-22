<?php /* Smarty version Smarty-3.1.18, created on 2017-02-16 18:42:30
         compiled from "/Users/bart/Websites/themarket/library/templates/start-chios.tpl" */ ?>
<?php /*%%SmartyHeaderCode:184411350658a5d676dfec80-93592207%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd35550c04dd91f27b62b6b8c89fd8daf374164f9' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/start-chios.tpl',
      1 => 1487263349,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '184411350658a5d676dfec80-93592207',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a5d676e3f957_38485377',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a5d676e3f957_38485377')) {function content_58a5d676e3f957_38485377($_smarty_tpl) {?><div class="content-form">
	<h1>Hello there!</h1>
	
<h1 class="light">We have <span class="number"><?php echo $_smarty_tpl->tpl_vars['data']->value['items'];?>
</span> items in our warehouses, in <span class="number"><?php echo $_smarty_tpl->tpl_vars['data']->value['boxes'];?>
</span> boxes.</h1>
<?php }} ?>
