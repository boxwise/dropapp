<?php /* Smarty version Smarty-3.1.18, created on 2017-01-01 17:57:05
         compiled from "./templates/toys.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7910766725869240a038b80-89336020%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3d378263bd3446391ef497ba0500c242df83ddbf' => 
    array (
      0 => './templates/toys.tpl',
      1 => 1483286224,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7910766725869240a038b80-89336020',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5869240a351623_00055211',
  'variables' => 
  array (
    'action' => 0,
    'list' => 0,
    'item' => 0,
    't' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5869240a351623_00055211')) {function content_5869240a351623_00055211($_smarty_tpl) {?><div class="noprint tipofday"><h3>💡 Best printing result</h3><p>Print it out using Google Chrome, choose A4, Portrait and switch off the printing of headers and footers in the Print dialog screen.</p></div>
<div class="noprint"><a href="?action=<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
&export=true">Export this list as .csv-file (for Excel or Google Spreadsheet)</a><br />&nbsp;</div>
<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>

	<?php if (!$_smarty_tpl->tpl_vars['item']->value['begin']&&$_smarty_tpl->tpl_vars['item']->value['newcol']) {?>
		</table> 
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['item']->value['begin']||$_smarty_tpl->tpl_vars['item']->value['newcol']) {?>
		<?php $_smarty_tpl->tpl_vars['t'] = new Smarty_variable($_smarty_tpl->tpl_vars['t']->value+1, null, 0);?>
		<?php if (($_smarty_tpl->tpl_vars['t']->value%2)&&!($_smarty_tpl->tpl_vars['item']->value['begin'])) {?><div class="newpage"></div><?php }?>
		<table class="printed_list_people">
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['item']->value['type']=='familyhead') {?>
	    <tr><td><strong><?php echo $_smarty_tpl->tpl_vars['item']->value['container'];?>
</strong></td><td colspan="3"><strong><?php echo $_smarty_tpl->tpl_vars['item']->value['number'];?>
 people</strong></td></tr>
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['item']->value['type']=='member') {?>
	    <tr><td><?php echo trim($_smarty_tpl->tpl_vars['item']->value['name']);?>
</td><td><?php echo $_smarty_tpl->tpl_vars['item']->value['age'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['item']->value['gender'];?>
</td><td><strong><?php echo $_smarty_tpl->tpl_vars['item']->value['toys'];?>
</strong></td></tr>
	<?php }?>

	
<?php } ?>

	</table> 
	
<?php }} ?>
