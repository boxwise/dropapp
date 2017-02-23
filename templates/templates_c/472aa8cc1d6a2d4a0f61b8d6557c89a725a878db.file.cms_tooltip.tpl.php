<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 22:44:21
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_tooltip.tpl" */ ?>
<?php /*%%SmartyHeaderCode:42916615358a4bda5312930-17429980%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '472aa8cc1d6a2d4a0f61b8d6557c89a725a878db' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/cms_tooltip.tpl',
      1 => 1486395483,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '42916615358a4bda5312930-17429980',
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
  'unifunc' => 'content_58a4bda531ea90_29947400',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a4bda531ea90_29947400')) {function content_58a4bda531ea90_29947400($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><span class="fa fa-info tooltip-this form-control-info<?php echo $_smarty_tpl->tpl_vars['valign']->value;?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['tooltip'], ENT_QUOTES, 'UTF-8', true);?>
"></span><?php }?><?php }} ?>
