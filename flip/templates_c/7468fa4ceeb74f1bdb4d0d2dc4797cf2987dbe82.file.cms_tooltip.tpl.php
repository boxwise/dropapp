<?php /* Smarty version Smarty-3.1.18, created on 2015-08-14 17:18:38
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_tooltip.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1888046092556dacf8c82bb0-35532421%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7468fa4ceeb74f1bdb4d0d2dc4797cf2987dbe82' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_tooltip.tpl',
      1 => 1439565357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1888046092556dacf8c82bb0-35532421',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dacf8e613d1_25157877',
  'variables' => 
  array (
    'element' => 0,
    'valign' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dacf8e613d1_25157877')) {function content_556dacf8e613d1_25157877($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['element']->value['tooltip']) {?><span class="fa fa-info tooltip-this form-control-info<?php echo $_smarty_tpl->tpl_vars['valign']->value;?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['element']->value['tooltip'], ENT_QUOTES, 'UTF-8', true);?>
"></span><?php }?><?php }} ?>
