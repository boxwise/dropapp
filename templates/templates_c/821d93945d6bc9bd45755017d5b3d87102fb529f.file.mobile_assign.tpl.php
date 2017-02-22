<?php /* Smarty version Smarty-3.1.18, created on 2017-02-19 13:22:17
         compiled from "/Users/bart/Websites/themarket/library/templates/mobile_assign.tpl" */ ?>
<?php /*%%SmartyHeaderCode:203100181758a97fe9842e58-28077435%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '821d93945d6bc9bd45755017d5b3d87102fb529f' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/mobile_assign.tpl',
      1 => 1486395495,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '203100181758a97fe9842e58-28077435',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    's' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a97fe988e1e9_79794650',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a97fe988e1e9_79794650')) {function content_58a97fe988e1e9_79794650($_smarty_tpl) {?><h2 class="page-header">Link QR to box</h2>
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
