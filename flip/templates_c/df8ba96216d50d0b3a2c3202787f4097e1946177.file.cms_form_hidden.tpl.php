<?php /* Smarty version Smarty-3.1.18, created on 2016-10-26 17:09:18
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_hidden.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18844559935810c71ea31dd4-71762389%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'df8ba96216d50d0b3a2c3202787f4097e1946177' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/50-back/templates/cms_form_hidden.tpl',
      1 => 1477491593,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18844559935810c71ea31dd4-71762389',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'element' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5810c71ea37671_54688427',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5810c71ea37671_54688427')) {function content_5810c71ea37671_54688427($_smarty_tpl) {?><input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']];?>
" /><?php }} ?>
