<?php /* Smarty version Smarty-3.1.18, created on 2017-02-22 16:01:12
         compiled from "./templates/cms_list_bar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3142779858ad99a8287ac2-24486047%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '449cbb418e2f32ef53def88c85f4d534584384ad' => 
    array (
      0 => './templates/cms_list_bar.tpl',
      1 => 1487346158,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3142779858ad99a8287ac2-24486047',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'row' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58ad99a82bcf72_92351250',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58ad99a82bcf72_92351250')) {function content_58ad99a82bcf72_92351250($_smarty_tpl) {?><span class="hide"><?php echo intval(($_smarty_tpl->tpl_vars['row']->value['result']*100));?>
</span>
<?php if ($_smarty_tpl->tpl_vars['row']->value['result']>=0) {?>
<div class="bar stay">
	<div class="bar-inside <?php if ($_smarty_tpl->tpl_vars['row']->value['result']<0.2) {?>bar-inside-red<?php } elseif ($_smarty_tpl->tpl_vars['row']->value['result']<0.35) {?>bar-inside-orange<?php } elseif ($_smarty_tpl->tpl_vars['row']->value['result']<0.7) {?>bar-inside-yellow<?php } elseif ($_smarty_tpl->tpl_vars['row']->value['result']<1.3) {?>bar-inside-green<?php } else { ?>bar-inside-blue<?php }?>" 
		style="<?php if ($_smarty_tpl->tpl_vars['row']->value['result']>1) {?>left:50%;width:<?php echo intval((($_smarty_tpl->tpl_vars['row']->value['result']-1)*50));?>
px<?php } else { ?>left:<?php echo intval((($_smarty_tpl->tpl_vars['row']->value['result'])*50));?>
px;width:<?php echo intval(((1-$_smarty_tpl->tpl_vars['row']->value['result'])*50));?>
px;<?php }?>">
	</div>
	<div class="from-left bar-inside <?php if ($_smarty_tpl->tpl_vars['row']->value['result']<0.2) {?>bar-inside-red<?php } elseif ($_smarty_tpl->tpl_vars['row']->value['result']<0.35) {?>bar-inside-orange<?php } elseif ($_smarty_tpl->tpl_vars['row']->value['result']<0.7) {?>bar-inside-yellow<?php } elseif ($_smarty_tpl->tpl_vars['row']->value['result']<1.3) {?>bar-inside-green<?php } else { ?>bar-inside-blue<?php }?>" 
		style="width:<?php echo intval(($_smarty_tpl->tpl_vars['row']->value['result']*100));?>
%">
	</div>
	<div class="bar-value"><?php echo intval(($_smarty_tpl->tpl_vars['row']->value['result']*100));?>
%</div>
</div>
<?php }?><?php }} ?>
