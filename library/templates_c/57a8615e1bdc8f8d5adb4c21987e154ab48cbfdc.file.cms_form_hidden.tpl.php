<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 18:00:39
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_form_hidden.tpl" */ ?>
<?php /*%%SmartyHeaderCode:177401225858a47b27bf7e77-29350501%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
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
  'nocache_hash' => '177401225858a47b27bf7e77-29350501',
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
  'unifunc' => 'content_58a47b27c33752_59839289',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a47b27c33752_59839289')) {function content_58a47b27c33752_59839289($_smarty_tpl) {?><input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['element']->value['field'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['element']->value['field']];?>
" /><?php }} ?>
