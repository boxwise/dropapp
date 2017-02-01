<?php /* Smarty version Smarty-3.1.18, created on 2015-08-14 17:18:38
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_form_info.tpl" */ ?>
<?php /*%%SmartyHeaderCode:88083208556db997bccab3-79872620%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '043eff51f82d56a9b6c48e51c6c091a05191790f' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_form_info.tpl',
      1 => 1439565356,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '88083208556db997bccab3-79872620',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556db997c0f346_76197977',
  'variables' => 
  array (
    'element' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556db997c0f346_76197977')) {function content_556db997c0f346_76197977($_smarty_tpl) {?>	<div class="form-group<?php if ($_smarty_tpl->tpl_vars['element']->value['hidden']) {?> hidden<?php }?>" id="div_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
">
		<label for="field_<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" class="control-label col-sm-2"><?php echo $_smarty_tpl->tpl_vars['element']->value['label'];?>
</label>
		<div class="col-sm-<?php if ($_smarty_tpl->tpl_vars['element']->value['width']>0&&$_smarty_tpl->tpl_vars['element']->value['width']<11) {?><?php echo $_smarty_tpl->tpl_vars['element']->value['width'];?>
<?php } else { ?>6<?php }?>">
	 		<p class="form-control-static"><?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']];?>
</p>
			<?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><?php echo $_smarty_tpl->getSubTemplate ("cms_tooltip.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }?>
		</div>
	</div>
<?php }} ?>
