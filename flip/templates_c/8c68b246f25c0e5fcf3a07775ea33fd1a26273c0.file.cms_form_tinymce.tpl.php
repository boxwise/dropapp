<?php /* Smarty version Smarty-3.1.18, created on 2016-11-10 12:04:09
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_tinymce.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17616080758245429c16370-34260678%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8c68b246f25c0e5fcf3a07775ea33fd1a26273c0' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_tinymce.tpl',
      1 => 1477491595,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17616080758245429c16370-34260678',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'element' => 0,
    'settings' => 0,
    'width' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58245429c78037_16970876',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58245429c78037_16970876')) {function content_58245429c78037_16970876($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
">
		<input type="hidden" name="__<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="tinymce <?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']) {?>readonly<?php }?>">
		<label for="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="control-label col-sm-2"><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</label>
		<div class="col-sm-<?php if ($_smarty_tpl->tpl_vars['element']->value['width']>0&&$_smarty_tpl->tpl_vars['element']->value['width']<11) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['width'];?>
<?php } else { ?>10<?php }?>"<?php if ($_smarty_tpl->tpl_vars['element']->value['max-width']) {?> style="max-width: <?php echo $_smarty_tpl->tpl_vars['element']->value['max-width'];?>
"<?php }?>>
			<textarea name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" 
				data-tinymce-toolbar-type="<?php if ($_smarty_tpl->tpl_vars['element']->value['tinytoolbartype']) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['tinytoolbartype'];?>
<?php } else { ?>extended<?php }?>" 
				data-height="<?php if ($_smarty_tpl->tpl_vars['element']->value['height']) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['height'];?>
<?php } else { ?>500<?php }?>"
				data-lan="<?php echo $_smarty_tpl->tpl_vars['settings']->value['cms_language'];?>
"
				rows="<?php if ($_smarty_tpl->tpl_vars['element']->value['rows']) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['rows'];?>
<?php } else { ?>5<?php }?>" 
				id="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" 
				class="span<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
 input-xxlarge tinymce"><?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']];?>
</textarea>
			<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('valign'=>"middle"), 0);?>
<?php }?>
		</div>
	</div>
<?php }} ?>
