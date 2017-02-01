<?php /* Smarty version Smarty-3.1.18, created on 2016-11-20 19:07:37
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_custom.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19781170025810c30f3ae710-53401014%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '867bb0e1d2cc67dffed5972d20219cd5a12df568' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_custom.tpl',
      1 => 1479665256,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19781170025810c30f3ae710-53401014',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5810c30f3c6515_32672667',
  'variables' => 
  array (
    'element' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5810c30f3c6515_32672667')) {function content_5810c30f3c6515_32672667($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['element']->value['field']);?>
">
		<label for="field" class="control-label col-sm-2"><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</label>
		<div class="col-sm-<?php if ($_smarty_tpl->tpl_vars['element']->value['width']>0&&$_smarty_tpl->tpl_vars['element']->value['width']<11) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['width'];?>
<?php } else { ?>6<?php }?>">
	 		<p class="form-control-static"><?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
</p>
			<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
		</div>
	</div>
<?php }} ?>
