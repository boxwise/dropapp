<?php /* Smarty version Smarty-3.1.18, created on 2017-01-19 18:32:41
         compiled from "/home/drapeton/market/50-back/templates/cms_form_hidden.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5909733295880ea290a7a72-27870038%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9e05c663797e52de8655deabb7795af69a37f410' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_form_hidden.tpl',
      1 => 1484776981,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5909733295880ea290a7a72-27870038',
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
  'unifunc' => 'content_5880ea290aab80_30048113',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5880ea290aab80_30048113')) {function content_5880ea290aab80_30048113($_smarty_tpl) {?><input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']];?>
" /><?php }} ?>
