<?php /* Smarty version Smarty-3.1.18, created on 2017-01-26 08:10:23
         compiled from "./templates/printed_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13092699065831e8860368c9-19912451%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '76899913a8887b41cc71e81767df8aec6b31af95' => 
    array (
      0 => './templates/printed_list.tpl',
      1 => 1484843898,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13092699065831e8860368c9-19912451',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5831e886038ae6_48675916',
  'variables' => 
  array (
    'action' => 0,
    'containers' => 0,
    'newcol' => 0,
    'container' => 0,
    'length' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5831e886038ae6_48675916')) {function content_5831e886038ae6_48675916($_smarty_tpl) {?><div class="noprint tipofday"><h3>👨‍🏫 Best printing result</h3><p>Print it out using Google Chrome, choose A4, Portrait and switch off the printing of headers and footers in the Print dialog screen.</p></div>
<div class="noprint"><a href="?action=<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
&export=true">Export this list as .csv-file (for Excel or Google Spreadsheet)</a><br />&nbsp;</div>
	<?php $_smarty_tpl->tpl_vars['newcol'] = new Smarty_variable(true, null, 0);?>
	<?php $_smarty_tpl->tpl_vars['length'] = new Smarty_variable(47, null, 0);?>
    <?php  $_smarty_tpl->tpl_vars['container'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['container']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['containers']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["containers"]['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars['container']->key => $_smarty_tpl->tpl_vars['container']->value) {
$_smarty_tpl->tpl_vars['container']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["containers"]['iteration']++;
?>
<?php if ($_smarty_tpl->tpl_vars['newcol']->value) {?>
	<table class="printed_list">
    <tr><td>&nbsp;</td><td><strong>Container</strong></td><td><strong>#</strong></td></tr>
	<?php $_smarty_tpl->tpl_vars['newcol'] = new Smarty_variable(false, null, 0);?> 
<?php }?>    
            <tr><td class="square">&#9723;</td><td><?php echo $_smarty_tpl->tpl_vars['container']->value['container'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['container']->value['count'];?>
</td></tr>

<?php if (!($_smarty_tpl->getVariable('smarty')->value['foreach']['containers']['iteration'] % $_smarty_tpl->tpl_vars['length']->value)) {?> 
	</table> 
	<?php $_smarty_tpl->tpl_vars['newcol'] = new Smarty_variable(true, null, 0);?> 
<?php }?> 

    <?php } ?>
<?php }} ?>
