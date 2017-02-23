<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 22:44:21
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_form_hidden.tpl" */ ?>
<?php /*%%SmartyHeaderCode:51882218158a4bda5238780-78097725%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '57a8615e1bdc8f8d5adb4c21987e154ab48cbfdc' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/cms_form_hidden.tpl',
      1 => 1486395474,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '51882218158a4bda5238780-78097725',
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
  'unifunc' => 'content_58a4bda523fd06_70003801',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a4bda523fd06_70003801')) {function content_58a4bda523fd06_70003801($_smarty_tpl) {?><input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']];?>
" /><?php }} ?>
