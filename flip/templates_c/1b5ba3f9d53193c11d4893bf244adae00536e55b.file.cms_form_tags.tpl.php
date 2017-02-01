<?php /* Smarty version Smarty-3.1.18, created on 2016-10-28 09:33:29
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_tags.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9687506555812ff4933bdc4-65736362%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1b5ba3f9d53193c11d4893bf244adae00536e55b' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_tags.tpl',
      1 => 1477491595,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9687506555812ff4933bdc4-65736362',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'element' => 0,
    'label' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5812ff498178f0_60468865',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5812ff498178f0_60468865')) {function content_5812ff498178f0_60468865($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
">
		<input type="hidden" name="__<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="tags <?php if ($_smarty_tpl->tpl_vars['element']->value['readonly']) {?>readonly<?php }?>">
		<label for="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="control-label col-sm-2"><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</label>
		<div class="col-sm-<?php if ($_smarty_tpl->tpl_vars['element']->value['width']>0&&$_smarty_tpl->tpl_vars['element']->value['width']<11) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['width'];?>
<?php } else { ?>6<?php }?>">
			<textarea id="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
[]" class="select2 form-control" 
				data-tags='[<?php  $_smarty_tpl->tpl_vars['label'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['label']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['element']->value['othertags']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['label']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['label']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['label']->key => $_smarty_tpl->tpl_vars['label']->value) {
$_smarty_tpl->tpl_vars['label']->_loop = true;
 $_smarty_tpl->tpl_vars['label']->iteration++;
 $_smarty_tpl->tpl_vars['label']->last = $_smarty_tpl->tpl_vars['label']->iteration === $_smarty_tpl->tpl_vars['label']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["othertags"]['last'] = $_smarty_tpl->tpl_vars['label']->last;
?>"<?php echo $_smarty_tpl->tpl_vars['label']->value;?>
"<?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['othertags']['last']) {?>, <?php }?><?php } ?>]'
				data-placeholder="Choose tags" <?php if ($_smarty_tpl->tpl_vars['element']->value['required']) {?>required<?php }?>><?php  $_smarty_tpl->tpl_vars['label'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['label']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['element']->value['selectedtags']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['label']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['label']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['label']->key => $_smarty_tpl->tpl_vars['label']->value) {
$_smarty_tpl->tpl_vars['label']->_loop = true;
 $_smarty_tpl->tpl_vars['label']->iteration++;
 $_smarty_tpl->tpl_vars['label']->last = $_smarty_tpl->tpl_vars['label']->iteration === $_smarty_tpl->tpl_vars['label']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["selectedtags"]['last'] = $_smarty_tpl->tpl_vars['label']->last;
?><?php echo $_smarty_tpl->tpl_vars['label']->value;?>
<?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['selectedtags']['last']) {?>, <?php }?><?php } ?></textarea>
			<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
		</div>
	</div>
 
<?php }} ?>
