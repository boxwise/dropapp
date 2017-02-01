<?php /* Smarty version Smarty-3.1.18, created on 2017-01-15 13:58:28
         compiled from "./templates/mobile_amount.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2110184412587b61aa1937f2-41050496%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '025e2c0155d622ac3be20a70f2ea053e6062b8c1' => 
    array (
      0 => './templates/mobile_amount.tpl',
      1 => 1484481507,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2110184412587b61aa1937f2-41050496',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_587b61aa19cbb3_06847190',
  'variables' => 
  array (
    'box' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_587b61aa19cbb3_06847190')) {function content_587b61aa19cbb3_06847190($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['box']->value['id']) {?>
	<p>Box <strong><?php echo $_smarty_tpl->tpl_vars['box']->value['box_id'];?>
</strong> contains <?php echo $_smarty_tpl->tpl_vars['box']->value['items'];?>
x <strong><?php echo $_smarty_tpl->tpl_vars['box']->value['product'];?>
</strong>
		<form method="get">
			<input type="hidden" name="saveamount" value="<?php echo $_smarty_tpl->tpl_vars['box']->value['id'];?>
">
			New amount of items in the box
			<input type="number" name="items" pattern="\d*" value="<?php echo $_smarty_tpl->tpl_vars['box']->value['items'];?>
">
			<input type="submit" value="Save new amount">
		</form>
<?php }?>
<?php }} ?>
