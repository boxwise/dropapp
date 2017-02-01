<?php /* Smarty version Smarty-3.1.18, created on 2017-01-19 18:34:24
         compiled from "/home/drapeton/market/50-back/templates/cms_form_textarea.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4826040775880ea90d62978-85710648%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '01d175eb3670a5dd682a1d363c2759e58b787184' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_form_textarea.tpl',
      1 => 1484776981,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4826040775880ea90d62978-85710648',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'element' => 0,
    'data' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5880ea90d80b13_63999266',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5880ea90d80b13_63999266')) {function content_5880ea90d80b13_63999266($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/home/drapeton/market/50-back/smarty/libs/plugins/modifier.replace.php';
?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
">
		<input type="hidden" name="__<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="textarea <?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']) {?>readonly<?php }?>">
		<label for="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="control-label col-sm-2"><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</label>
		<div class="col-sm-<?php if ($_smarty_tpl->tpl_vars['element']->value['width']>0&&$_smarty_tpl->tpl_vars['element']->value['width']<11) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['width'];?>
<?php } else { ?>10<?php }?> input-element <?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?>has-tooltip<?php }?>">
			<textarea name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" <?php if ($_smarty_tpl->tpl_vars['element']->value['maxlength']) {?>data-max-count="<?php echo $_smarty_tpl->tpl_vars['element']->value['maxlength'];?>
"<?php }?> rows="<?php if ($_smarty_tpl->tpl_vars['element']->value['rows']) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['rows'];?>
<?php } else { ?>3<?php }?>" id="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="form-control"
				<?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']) {?>readonly<?php }?> 
				<?php if ($_smarty_tpl->tpl_vars['element']->value['required']) {?>required<?php }?> 			
 				<?php if ($_smarty_tpl->tpl_vars['element']->value['onblur']) {?>onblur="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onblur'], ENT_QUOTES, 'UTF-8', true);?>
;"<?php }?>
 				<?php if ($_smarty_tpl->tpl_vars['element']->value['onchange']) {?>onchange="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onchange'], ENT_QUOTES, 'UTF-8', true);?>
;"<?php }?>
 				<?php if ($_smarty_tpl->tpl_vars['element']->value['onkeyup']) {?>onkeyup="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['onkeyup'], ENT_QUOTES, 'UTF-8', true);?>
;"<?php }?>


				><?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']];?>
</textarea>
			<?php if ($_smarty_tpl->tpl_vars['element']->value['maxlength']) {?><p class="counter-parent safe"><span class="counter"><?php echo $_smarty_tpl->tpl_vars['element']->value['maxlength'];?>
</span> <?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['translate']->value['cms_form_charactersleft'],'%n',$_smarty_tpl->tpl_vars['element']->value['maxlength']);?>
</p><?php }?>
			<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('valign'=>" middle"), 0);?>
<?php }?>
		</div>
	</div>
<?php }} ?>
