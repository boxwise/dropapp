<?php /* Smarty version Smarty-3.1.18, created on 2017-02-17 17:42:39
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_list_bar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:180375918358a4bd93a19b15-13550936%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '49087961cf90257b109756dae0d494e6d40d6cfd' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/cms_list_bar.tpl',
      1 => 1487346158,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '180375918358a4bd93a19b15-13550936',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a4bd93a53ee2_04796310',
  'variables' => 
  array (
    'row' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a4bd93a53ee2_04796310')) {function content_58a4bd93a53ee2_04796310($_smarty_tpl) {?><span class="hide"><?php echo intval(($_smarty_tpl->tpl_vars['row']->value['result']*100));?>
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
