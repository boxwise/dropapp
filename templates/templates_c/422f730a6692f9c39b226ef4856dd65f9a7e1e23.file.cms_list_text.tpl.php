<?php /* Smarty version Smarty-3.1.18, created on 2017-02-22 16:01:12
         compiled from "./templates/cms_list_text.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7818263058ad99a8271c17-55510589%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '422f730a6692f9c39b226ef4856dd65f9a7e1e23' => 
    array (
      0 => './templates/cms_list_text.tpl',
      1 => 1486395481,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7818263058ad99a8271c17-55510589',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'column' => 0,
    'listdata' => 0,
    'row' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58ad99a8283131_37410926',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58ad99a8283131_37410926')) {function content_58ad99a8283131_37410926($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/Users/bart/Websites/themarket/library/smarty/libs/plugins/modifier.truncate.php';
?><?php if ($_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['breakall']) {?><span class="breakall"><?php }?>
<?php echo smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']]));?>

<?php if ($_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['breakall']) {?></span><?php }?>
<?php }} ?>
