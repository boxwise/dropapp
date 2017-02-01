<?php /* Smarty version Smarty-3.1.18, created on 2017-01-22 13:47:41
         compiled from "./templates/market_schedule.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1509919825586cb8eb59b410-17815635%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ce6a3eb8920bb89d6144c13cb0c3c8cdf78d3b18' => 
    array (
      0 => './templates/market_schedule.tpl',
      1 => 1484843894,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1509919825586cb8eb59b410-17815635',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_586cb8eb677d34_65594211',
  'variables' => 
  array (
    'data' => 0,
    'slots' => 0,
    'date' => 0,
    'weekdays' => 0,
    'months' => 0,
    'time' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_586cb8eb677d34_65594211')) {function content_586cb8eb677d34_65594211($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/drapeton/market/50-back/smarty/libs/plugins/modifier.date_format.php';
?><h1>Market schedule from <?php echo $_smarty_tpl->tpl_vars['data']->value['startdate'];?>
 to <?php echo $_smarty_tpl->tpl_vars['data']->value['enddate'];?>
</h1>

<?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['d']->_loop = false;
 $_smarty_tpl->tpl_vars['date'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['slots']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value) {
$_smarty_tpl->tpl_vars['d']->_loop = true;
 $_smarty_tpl->tpl_vars['date']->value = $_smarty_tpl->tpl_vars['d']->key;
?>
	<table>
		<tr><td colspan="2"><h2><?php echo $_smarty_tpl->tpl_vars['date']->value;?>
 – <?php echo $_smarty_tpl->tpl_vars['weekdays']->value[smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,"%u")];?>
 <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,"%e");?>
  <?php echo $_smarty_tpl->tpl_vars['months']->value[(intval(smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,"%m")))-1];?>
 </h2></td></tr>
		<?php  $_smarty_tpl->tpl_vars['t'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['t']->_loop = false;
 $_smarty_tpl->tpl_vars['time'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['slots']->value[$_smarty_tpl->tpl_vars['date']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['t']->key => $_smarty_tpl->tpl_vars['t']->value) {
$_smarty_tpl->tpl_vars['t']->_loop = true;
 $_smarty_tpl->tpl_vars['time']->value = $_smarty_tpl->tpl_vars['t']->key;
?>
			<tr><td><?php echo $_smarty_tpl->tpl_vars['time']->value;?>
</td><td><?php echo implode(', ',$_smarty_tpl->tpl_vars['slots']->value[$_smarty_tpl->tpl_vars['date']->value][$_smarty_tpl->tpl_vars['time']->value]['containers']);?>
</td></tr>
		<?php } ?>
	</table>
	<p>&nbsp;</p>
<?php } ?><?php }} ?>
