<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 22:44:21
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_form_select.tpl" */ ?>
<?php /*%%SmartyHeaderCode:178185995958a4bda52b2c16-15723273%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2fcbf0cd259d6f702b5d34f7deb919c0de88d39e' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/cms_form_select.tpl',
      1 => 1486395477,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '178185995958a4bda52b2c16-15723273',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'element' => 0,
    'translate' => 0,
    'data' => 0,
    'option' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a4bda5309174_50779776',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a4bda5309174_50779776')) {function content_58a4bda5309174_50779776($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
">
		<input type="hidden" name="__<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="select <?php if ($_smarty_tpl->tpl_vars['element']->value['multiple']) {?>multiple<?php }?><?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']) {?>readonly<?php }?>">
		<label for="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="control-label col-sm-2"><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</label>
		<div class="col-sm-<?php if ($_smarty_tpl->tpl_vars['element']->value['width']>0&&$_smarty_tpl->tpl_vars['element']->value['width']<11) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['width'];?>
<?php } else { ?>6<?php }?> input-element <?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?>has-tooltip<?php }?>">
			<select id="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
[]" 
				<?php if ($_smarty_tpl->tpl_vars['element']->value['multiple']) {?>multiple<?php }?> class="select2 form-control" 
				data-placeholder="<?php if (isset($_smarty_tpl->tpl_vars['element']->value['placeholder'])) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['placeholder'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_form_selectplaceholder'];?>
<?php }?>"
				<?php if ($_smarty_tpl->tpl_vars['element']->value['required']) {?>required<?php }?> 
				<?php if ($_smarty_tpl->tpl_vars['element']->value['formatlist']) {?> data-format-list="<?php echo $_smarty_tpl->tpl_vars['element']->value['formatlist'];?>
"<?php }?>
				<?php if (isset($_smarty_tpl->tpl_vars['element']->value['onchange'])) {?>onchange="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onchange'], ENT_QUOTES, 'UTF-8', true);?>
;"<?php }?>
			>
			<option></option>
			<?php  $_smarty_tpl->tpl_vars['option'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['option']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['element']->value['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value) {
$_smarty_tpl->tpl_vars['option']->_loop = true;
?>
				<option 
					<?php if ($_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']]==$_smarty_tpl->tpl_vars['option']->value['value']) {?>selected <?php }?>
					<?php if ($_smarty_tpl->tpl_vars['element']->value['multiple']&&$_smarty_tpl->tpl_vars['option']->value['selected']) {?>selected <?php }?>
					<?php if ($_smarty_tpl->tpl_vars['option']->value['disabled']) {?>disabled<?php }?>
					<?php if (isset($_smarty_tpl->tpl_vars['option']->value['level'])) {?> data-level="<?php echo ($_smarty_tpl->tpl_vars['option']->value['level'])+1;?>
" <?php }?>
					value="<?php echo $_smarty_tpl->tpl_vars['option']->value['value'];?>
"><?php echo $_smarty_tpl->tpl_vars['option']->value['label'];?>

				</option> 
			<?php } ?>
			</select>				            	
			<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
		</div>
	</div><?php }} ?>
