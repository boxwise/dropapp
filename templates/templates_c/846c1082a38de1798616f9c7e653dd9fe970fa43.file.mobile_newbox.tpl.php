<?php /* Smarty version Smarty-3.1.18, created on 2017-02-22 11:58:04
         compiled from "/Users/bart/Websites/themarket/library/templates/mobile_newbox.tpl" */ ?>
<?php /*%%SmartyHeaderCode:127006276558a6d1286146a5-43987442%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '846c1082a38de1798616f9c7e653dd9fe970fa43' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/mobile_newbox.tpl',
      1 => 1487757473,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '127006276558a6d1286146a5-43987442',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a6d1286857b8_09443328',
  'variables' => 
  array (
    'box' => 0,
    'data' => 0,
    'p' => 0,
    's' => 0,
    'as' => 0,
    'l' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a6d1286857b8_09443328')) {function content_58a6d1286857b8_09443328($_smarty_tpl) {?>	<h2 class="page-header"><?php if ($_smarty_tpl->tpl_vars['box']->value['id']) {?>Edit box<?php } else { ?>Make a new box<?php }?></h2>
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
			<select name="product_id" id="field_product_id" onchange="updateSizes(0)" class="form-control selectsearch" required>
				<option value="">Select a product</option>
				<?php  $_smarty_tpl->tpl_vars['p'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['p']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['p']->key => $_smarty_tpl->tpl_vars['p']->value) {
$_smarty_tpl->tpl_vars['p']->_loop = true;
?><option value="<?php echo $_smarty_tpl->tpl_vars['p']->value['value'];?>
" data-sizegroup="<?php echo $_smarty_tpl->tpl_vars['p']->value['sizegroup_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['p']->value['value']==$_smarty_tpl->tpl_vars['box']->value['product_id']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['p']->value['label'];?>
</option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group">
			<select name="size_id" id="field_size_id" class="form-control" required>
				<?php if ($_smarty_tpl->tpl_vars['data']->value['sizes']) {?>
					<option value="">Select a size</option>
					<?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value['sizes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value) {
$_smarty_tpl->tpl_vars['s']->_loop = true;
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['s']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['s']->value['id']==$_smarty_tpl->tpl_vars['box']->value['size_id']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['s']->value['label'];?>
</option>
					<?php } ?>
				<?php } else { ?>
					<option value="">First select a product</option>
				<?php }?>
			</select>
		</div>
		<div class="all-sizes hide">
			<?php  $_smarty_tpl->tpl_vars['as'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['as']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value['allsizes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['as']->key => $_smarty_tpl->tpl_vars['as']->value) {
$_smarty_tpl->tpl_vars['as']->_loop = true;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['as']->value['id'];?>
" class="sizegroup-<?php echo $_smarty_tpl->tpl_vars['as']->value['sizegroup_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['as']->value['label'];?>
</option>
			<?php } ?>
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
