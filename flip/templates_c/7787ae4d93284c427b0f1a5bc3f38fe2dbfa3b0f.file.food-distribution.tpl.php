<?php /* Smarty version Smarty-3.1.18, created on 2017-01-23 14:19:16
         compiled from "./templates/food-distribution.tpl" */ ?>
<?php /*%%SmartyHeaderCode:324019471584eaf3a584e52-26545171%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7787ae4d93284c427b0f1a5bc3f38fe2dbfa3b0f' => 
    array (
      0 => './templates/food-distribution.tpl',
      1 => 1484843893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '324019471584eaf3a584e52-26545171',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_584eaf3a5aca27_43162944',
  'variables' => 
  array (
    'list' => 0,
    'item' => 0,
    't' => 0,
    'f' => 0,
    'key' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_584eaf3a5aca27_43162944')) {function content_584eaf3a5aca27_43162944($_smarty_tpl) {?><div class="noprint tipofday"><h3>💡 Best printing result</h3><p>Print it out using Google Chrome, choose A4, Portrait and switch off the printing of headers and footers in the Print dialog screen.</p></div>
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
		<table class="food-distribution">
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['item']->value['type']=='familyhead') {?>
	    <tr><td><strong><?php echo $_smarty_tpl->tpl_vars['item']->value['container'];?>
</strong></td><td><strong><?php echo $_smarty_tpl->tpl_vars['item']->value['number'];?>
 people</strong></td><td>
		    <?php  $_smarty_tpl->tpl_vars['f'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['item']->value['food']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f']->key => $_smarty_tpl->tpl_vars['f']->value) {
$_smarty_tpl->tpl_vars['f']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['f']->key;
?>
		    <div class="food-distribution-detail"><?php echo $_smarty_tpl->tpl_vars['f']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['key']->value;?>
</div>
		    <?php } ?>
	    </td></tr>
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
