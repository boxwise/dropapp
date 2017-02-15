<?php /* Smarty version Smarty-3.1.18, created on 2017-02-10 14:45:41
         compiled from "./templates/printed_list_people.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1541594467589db5f5a750f6-57472718%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ca76435d5b34af86d67bdefad98f72cfb9d3750f' => 
    array (
      0 => './templates/printed_list_people.tpl',
      1 => 1486415168,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1541594467589db5f5a750f6-57472718',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'action' => 0,
    'list' => 0,
    'item' => 0,
    't' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_589db5f5bb4f90_08990562',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_589db5f5bb4f90_08990562')) {function content_589db5f5bb4f90_08990562($_smarty_tpl) {?><div class="noprint tipofday"><h3>💡 Best printing result</h3><p>Print it out using Google Chrome, choose A4, Portrait and switch off the printing of headers and footers in the Print dialog screen.</p></div>
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
</strong></td><td colspan="2"><strong><?php echo $_smarty_tpl->tpl_vars['item']->value['number'];?>
 people (<?php if ($_smarty_tpl->tpl_vars['item']->value['green']) {?><?php echo $_smarty_tpl->tpl_vars['item']->value['green'];?>
 green<?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value['green']&&$_smarty_tpl->tpl_vars['item']->value['red']) {?>, <?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value['red']) {?><?php echo $_smarty_tpl->tpl_vars['item']->value['red'];?>
 red<?php }?>)</strong></td></tr>
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['item']->value['type']=='member') {?>
	    <tr><td><?php echo trim($_smarty_tpl->tpl_vars['item']->value['name']);?>
</td><td><?php echo $_smarty_tpl->tpl_vars['item']->value['age'];?>
</td><td  colspan="2"><?php echo $_smarty_tpl->tpl_vars['item']->value['gender'];?>
</td></tr>
	<?php }?>

	
<?php } ?>

	</table> 
	
<?php }} ?>
