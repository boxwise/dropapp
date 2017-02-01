<?php /* Smarty version Smarty-3.1.18, created on 2017-01-19 18:32:41
         compiled from "/home/drapeton/market/50-back/templates/cms_tooltip.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1306920605880ea2910cef5-45452764%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3bb6f29acc663894a22116eacbfe75d071a41ac7' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_tooltip.tpl',
      1 => 1484776982,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1306920605880ea2910cef5-45452764',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'element' => 0,
    'valign' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5880ea29110619_45067698',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5880ea29110619_45067698')) {function content_5880ea29110619_45067698($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><span class="fa fa-info tooltip-this form-control-info<?php echo $_smarty_tpl->tpl_vars['valign']->value;?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['tooltip'], ENT_QUOTES, 'UTF-8', true);?>
"></span><?php }?><?php }} ?>
