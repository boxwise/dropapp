<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 15:10:29
         compiled from "./templates/mobile_newbox.tpl" */ ?>
<?php /*%%SmartyHeaderCode:204455335458a080ed09af26-36132361%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6d0d8d716b4ca1c5f2c0a9eb255457e4ae33b5c1' => 
    array (
      0 => './templates/mobile_newbox.tpl',
      1 => 1487164150,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '204455335458a080ed09af26-36132361',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a080ed15ddf7_69881486',
  'variables' => 
  array (
    'box' => 0,
    'data' => 0,
    'p' => 0,
    'l' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a080ed15ddf7_69881486')) {function content_58a080ed15ddf7_69881486($_smarty_tpl) {?>	<h2 class="page-header"><?php if ($_smarty_tpl->tpl_vars['box']->value['id']) {?>Edit box<?php } else { ?>Make a new box<?php }?></h2>
	<form method="post" action="?savebox=1">
		<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['box']->value['id'];?>
">
		<input type="hidden" name="qr_id" value="<?php echo $_smarty_tpl->tpl_vars['box']->value['qr_id'];?>
">
		<input type="hidden" name="camp_id" value="<?php echo $_SESSION['camp']['id'];?>
">
		<?php if ($_smarty_tpl->tpl_vars['box']->value['box_id']) {?>
			<div class="form-group">
				<input class="form-control" type="number" name="box_id" pattern="\d*" placeholder="Box Number" value="<?php echo $_smarty_tpl->tpl_vars['box']->value['box_id'];?>
" required readonly>
			</div>
		<?php }?>
		<div class="form-group">
			<select name="product_id" id="field_product_id" onchange="getSizes(0)" class="form-control selectsearch" required>
				<option value="">Select a product</option>
				<?php  $_smarty_tpl->tpl_vars['p'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['p']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['p']->key => $_smarty_tpl->tpl_vars['p']->value) {
$_smarty_tpl->tpl_vars['p']->_loop = true;
?><option value="<?php echo $_smarty_tpl->tpl_vars['p']->value['value'];?>
" <?php if ($_smarty_tpl->tpl_vars['p']->value['value']==$_smarty_tpl->tpl_vars['box']->value['product_id']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['p']->value['label'];?>
</option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group">
			<select name="size_id" id="field_size_id" class="form-control" required>
				<option value="">Select a size</option>
			</select>
		</div>
		<div class="form-group">
			<select name="location_id" class="form-control" required>
				<option value="">Select a location</option>
				<?php  $_smarty_tpl->tpl_vars['l'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['l']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value['locations']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['l']->key => $_smarty_tpl->tpl_vars['l']->value) {
$_smarty_tpl->tpl_vars['l']->_loop = true;
?><option value="<?php echo $_smarty_tpl->tpl_vars['l']->value['value'];?>
" <?php if ($_smarty_tpl->tpl_vars['l']->value['value']==$_smarty_tpl->tpl_vars['box']->value['location_id']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['l']->value['label'];?>
</option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group"><input type="number" name="items" pattern="\d*" placeholder="Number of items" value="<?php echo $_smarty_tpl->tpl_vars['box']->value['items'];?>
" class="form-control" min="1" required></div>
		<div class="form-group"><input type="text" name="comments" placeholder="Comments for this box" value="<?php echo $_smarty_tpl->tpl_vars['box']->value['comments'];?>
" class="form-control"></div>
		<input type="submit" class="btn" value="Save">
	</form>
	<script><?php if ($_smarty_tpl->tpl_vars['box']->value['product_id']) {?>getSizes(<?php echo $_smarty_tpl->tpl_vars['box']->value['size_id'];?>
);<?php }?></script><?php }} ?>
