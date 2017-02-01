<?php /* Smarty version Smarty-3.1.18, created on 2017-01-19 18:36:53
         compiled from "/home/drapeton/market/50-back/templates/cms_form_custom.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2081628595880eb257427d7-92512617%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '192668c2067fb6ec669da458bbc176df35003740' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_form_custom.tpl',
      1 => 1484776980,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2081628595880eb257427d7-92512617',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'element' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5880eb25797081_23286286',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5880eb25797081_23286286')) {function content_5880eb25797081_23286286($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['element']->value['field']);?>
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
