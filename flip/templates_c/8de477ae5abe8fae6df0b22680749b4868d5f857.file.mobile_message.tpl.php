<?php /* Smarty version Smarty-3.1.18, created on 2017-01-19 18:51:16
         compiled from "./templates/mobile_message.tpl" */ ?>
<?php /*%%SmartyHeaderCode:967270669587b59c373e8b5-29107101%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8de477ae5abe8fae6df0b22680749b4868d5f857' => 
    array (
      0 => './templates/mobile_message.tpl',
      1 => 1484844672,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '967270669587b59c373e8b5-29107101',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_587b59c3742a05_74230165',
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_587b59c3742a05_74230165')) {function content_587b59c3742a05_74230165($_smarty_tpl) {?><?php echo $_smarty_tpl->tpl_vars['data']->value['message'];?>
<br />
<?php if ($_smarty_tpl->tpl_vars['data']->value['barcode']) {?><a class='btn' href="?barcode=<?php echo $_smarty_tpl->tpl_vars['data']->value['barcode'];?>
">Continue</a><?php }?><?php }} ?>
