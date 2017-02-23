<?php /* Smarty version Smarty-3.1.18, created on 2017-02-22 13:37:59
         compiled from "/Users/bart/Websites/themarket/library/templates/start-nomarket.tpl" */ ?>
<?php /*%%SmartyHeaderCode:46563177758ad7817f2cbc7-42417117%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '300e55be0c9955d6aba62eea2c3a1795e5e6b94f' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/start-nomarket.tpl',
      1 => 1487263349,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '46563177758ad7817f2cbc7-42417117',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58ad7818032075_86752013',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58ad7818032075_86752013')) {function content_58ad7818032075_86752013($_smarty_tpl) {?><div class="content-form">
	<h1>Hello there!</h1>
	
<h1 class="light">We have <span class="number"><?php echo $_smarty_tpl->tpl_vars['data']->value['items'];?>
</span> items in our warehouses, in <span class="number"><?php echo $_smarty_tpl->tpl_vars['data']->value['boxes'];?>
</span> boxes.</h1>
<?php }} ?>
