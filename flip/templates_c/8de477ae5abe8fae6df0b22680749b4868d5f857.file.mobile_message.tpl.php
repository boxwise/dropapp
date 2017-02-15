<?php /* Smarty version Smarty-3.1.18, created on 2017-02-08 22:51:27
         compiled from "./templates/mobile_message.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1118122534589b84cfd02d10-69677354%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8de477ae5abe8fae6df0b22680749b4868d5f857' => 
    array (
      0 => './templates/mobile_message.tpl',
      1 => 1486415168,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1118122534589b84cfd02d10-69677354',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_589b84cfd84d96_29312520',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_589b84cfd84d96_29312520')) {function content_589b84cfd84d96_29312520($_smarty_tpl) {?><?php echo $_smarty_tpl->tpl_vars['data']->value['message'];?>
<br />
<?php if ($_smarty_tpl->tpl_vars['data']->value['barcode']) {?><a class='btn' href="?barcode=<?php echo $_smarty_tpl->tpl_vars['data']->value['barcode'];?>
">Continue</a><?php }?><?php }} ?>
