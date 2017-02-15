<?php /* Smarty version Smarty-3.1.18, created on 2017-02-08 13:51:35
         compiled from "./templates/cms_list_bar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1831431697589b06471e5f50-56035718%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '449cbb418e2f32ef53def88c85f4d534584384ad' => 
    array (
      0 => './templates/cms_list_bar.tpl',
      1 => 1486415168,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1831431697589b06471e5f50-56035718',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'row' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_589b06472c1296_57717725',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_589b06472c1296_57717725')) {function content_589b06472c1296_57717725($_smarty_tpl) {?><span class="hide"><?php echo intval(($_smarty_tpl->tpl_vars['row']->value['result']*100));?>
</span>
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
</div><?php }} ?>
