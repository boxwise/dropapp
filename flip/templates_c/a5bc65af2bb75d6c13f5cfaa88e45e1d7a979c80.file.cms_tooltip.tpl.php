<?php /* Smarty version Smarty-3.1.18, created on 2017-02-08 13:35:51
         compiled from "/Users/maartenhunink/Sites/themarket/50-back/templates/cms_tooltip.tpl" */ ?>
<?php /*%%SmartyHeaderCode:486279228589b0297c2bf18-99885752%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a5bc65af2bb75d6c13f5cfaa88e45e1d7a979c80' => 
    array (
      0 => '/Users/maartenhunink/Sites/themarket/50-back/templates/cms_tooltip.tpl',
      1 => 1486415168,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '486279228589b0297c2bf18-99885752',
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
  'unifunc' => 'content_589b0297c3c647_97294503',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_589b0297c3c647_97294503')) {function content_589b0297c3c647_97294503($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><span class="fa fa-info tooltip-this form-control-info<?php echo $_smarty_tpl->tpl_vars['valign']->value;?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['tooltip'], ENT_QUOTES, 'UTF-8', true);?>
"></span><?php }?><?php }} ?>
