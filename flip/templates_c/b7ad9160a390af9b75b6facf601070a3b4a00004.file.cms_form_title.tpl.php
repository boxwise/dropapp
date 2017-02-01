<?php /* Smarty version Smarty-3.1.18, created on 2016-10-27 11:01:36
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_title.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3836660605811c270a64d53-61934560%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b7ad9160a390af9b75b6facf601070a3b4a00004' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_title.tpl',
      1 => 1477491595,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3836660605811c270a64d53-61934560',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'element' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5811c271629a51_42528841',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5811c271629a51_42528841')) {function content_5811c271629a51_42528841($_smarty_tpl) {?>	<div class="form-group">
		<?php if ($_smarty_tpl->tpl_vars['element']->value['indented']) {?><div class="col-sm-2"></div><?php }?>
		<div class="col-sm-<?php if ($_smarty_tpl->tpl_vars['element']->value['width']>0&&$_smarty_tpl->tpl_vars['element']->value['width']<11) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['width'];?>
<?php } else { ?>6<?php }?>">
	 		<h2><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</h2>
			<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
		</div>
	</div><?php }} ?>
