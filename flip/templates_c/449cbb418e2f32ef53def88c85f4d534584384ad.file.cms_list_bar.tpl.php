<?php /* Smarty version Smarty-3.1.18, created on 2017-01-20 14:50:29
         compiled from "./templates/cms_list_bar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15116216305858fc93a15093-33063534%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '449cbb418e2f32ef53def88c85f4d534584384ad' => 
    array (
      0 => './templates/cms_list_bar.tpl',
      1 => 1484843893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15116216305858fc93a15093-33063534',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5858fc93a15c90_25161380',
  'variables' => 
  array (
    'row' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5858fc93a15c90_25161380')) {function content_5858fc93a15c90_25161380($_smarty_tpl) {?><span class="hide"><?php echo intval(($_smarty_tpl->tpl_vars['row']->value['result']*100));?>
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
