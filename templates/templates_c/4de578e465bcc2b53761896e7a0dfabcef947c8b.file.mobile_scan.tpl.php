<?php /* Smarty version Smarty-3.1.18, created on 2017-02-20 12:44:00
         compiled from "/Users/bart/Websites/themarket/library/templates/mobile_scan.tpl" */ ?>
<?php /*%%SmartyHeaderCode:211014989858a4973614f4b6-87697853%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4de578e465bcc2b53761896e7a0dfabcef947c8b' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/mobile_scan.tpl',
      1 => 1487506728,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '211014989858a4973614f4b6-87697853',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a497361ac6a2_59401029',
  'variables' => 
  array (
    'box' => 0,
    'locations' => 0,
    'location' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a497361ac6a2_59401029')) {function content_58a497361ac6a2_59401029($_smarty_tpl) {?>	<?php if ($_smarty_tpl->tpl_vars['box']->value['id']) {?>
		<h2 class="page-header">Box <?php echo $_smarty_tpl->tpl_vars['box']->value['box_id'];?>
</h2>
		Contains <strong><?php echo $_smarty_tpl->tpl_vars['box']->value['items'];?>
 <?php echo $_smarty_tpl->tpl_vars['box']->value['product'];?>
</strong><br />Move this box from <strong><?php echo $_smarty_tpl->tpl_vars['box']->value['location'];?>
</strong> to:</p>
		<div class="btn-list">
			<?php  $_smarty_tpl->tpl_vars['location'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['location']->_loop = false;
 $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['locations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['location']->key => $_smarty_tpl->tpl_vars['location']->value) {
$_smarty_tpl->tpl_vars['location']->_loop = true;
 $_smarty_tpl->tpl_vars['value']->value = $_smarty_tpl->tpl_vars['location']->key;
?>
				<a class="btn <?php if ($_smarty_tpl->tpl_vars['location']->value['selected']) {?>disabled<?php }?>" href="?move=<?php echo $_smarty_tpl->tpl_vars['box']->value['id'];?>
&location=<?php echo $_smarty_tpl->tpl_vars['location']->value['value'];?>
"><?php echo $_smarty_tpl->tpl_vars['location']->value['label'];?>
</a>
			<?php } ?>
		</div>
		<?php if (!$_smarty_tpl->tpl_vars['data']->value['othercamp']) {?>
		<hr></hr>
		<p>Change the amount of items in the box:</p>
			<form method="get">
				<input type="hidden" name="saveamount" value="<?php echo $_smarty_tpl->tpl_vars['box']->value['id'];?>
">
				<div class="form-group">
					<input type="number" name="items" pattern="\d*" value="<?php echo $_smarty_tpl->tpl_vars['box']->value['items'];?>
" class="form-control">			
				</div>
				<input class="btn" type="submit" value="Save new amount">
			</form>
		<hr></hr>
	
		<p>Or change the contents of the box</p>
		<a class="btn" href="?editbox=<?php echo $_smarty_tpl->tpl_vars['box']->value['id'];?>
">Edit the box</a>
		<?php }?>
	<?php } else { ?>
		<div class="message warning">This box is not found in the Drop Market.</div>
		<div class="btn-list">
			<a class="btn" href="?newbox=<?php echo $_smarty_tpl->tpl_vars['data']->value['barcode'];?>
">Create a new box</a><br />
			<a class="btn btn-light" href="?assignbox=<?php echo $_smarty_tpl->tpl_vars['data']->value['barcode'];?>
">Link QR-code to a box</a><br />
		</div>
		</p>
	<?php }?>
<?php }} ?>
