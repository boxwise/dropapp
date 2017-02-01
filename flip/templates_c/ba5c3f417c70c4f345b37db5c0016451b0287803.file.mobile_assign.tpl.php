<?php /* Smarty version Smarty-3.1.18, created on 2017-01-20 09:05:52
         compiled from "./templates/mobile_assign.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1606390244587b5418e73f46-42570651%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ba5c3f417c70c4f345b37db5c0016451b0287803' => 
    array (
      0 => './templates/mobile_assign.tpl',
      1 => 1484843895,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1606390244587b5418e73f46-42570651',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_587b5418e78ab9_11941228',
  'variables' => 
  array (
    'data' => 0,
    's' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_587b5418e78ab9_11941228')) {function content_587b5418e78ab9_11941228($_smarty_tpl) {?><h2 class="page-header">Link QR to box</h2>
<form method='get'>
	<input type="hidden" name="saveassignbox" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['barcode'];?>
">
	<div class="form-group">
		<select name="box" class="selectsearch form-control">
			<option value="">Select a box</option>
			<?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value['stock']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value) {
$_smarty_tpl->tpl_vars['s']->_loop = true;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['s']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['s']->value['label'];?>
</option>
			<?php } ?>
		</select>		
	</div>
	<input class="btn" type="submit" value="Save" />
</form><?php }} ?>
