<?php /* Smarty version Smarty-3.1.18, created on 2016-10-26 16:47:35
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_tooltip.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4238608095810c20761e4c0-81156035%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6958b27c80ac52ca8d8ddd0b2fe30cec25c0bcbc' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_tooltip.tpl',
      1 => 1477491599,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4238608095810c20761e4c0-81156035',
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
  'unifunc' => 'content_5810c2076225a6_17265951',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5810c2076225a6_17265951')) {function content_5810c2076225a6_17265951($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><span class="fa fa-info tooltip-this form-control-info<?php echo $_smarty_tpl->tpl_vars['valign']->value;?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['tooltip'], ENT_QUOTES, 'UTF-8', true);?>
"></span><?php }?><?php }} ?>
