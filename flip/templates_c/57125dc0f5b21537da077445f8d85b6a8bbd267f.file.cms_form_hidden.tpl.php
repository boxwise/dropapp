<?php /* Smarty version Smarty-3.1.18, created on 2017-02-08 13:35:51
         compiled from "/Users/maartenhunink/Sites/themarket/50-back/templates/cms_form_hidden.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1279142994589b0297a77e11-05702730%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '57125dc0f5b21537da077445f8d85b6a8bbd267f' => 
    array (
      0 => '/Users/maartenhunink/Sites/themarket/50-back/templates/cms_form_hidden.tpl',
      1 => 1486415168,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1279142994589b0297a77e11-05702730',
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
  'unifunc' => 'content_589b0297a84715_04005386',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_589b0297a84715_04005386')) {function content_589b0297a84715_04005386($_smarty_tpl) {?><input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']];?>
" /><?php }} ?>
