<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 17:59:58
         compiled from "/Users/bart/Websites/themarket/library/templates/cms_list_text.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3916514658a47afec92d98-41285524%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8fe077ef2306a1aa1c290601bff09017439211f4' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/cms_list_text.tpl',
      1 => 1486395481,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3916514658a47afec92d98-41285524',
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
  'unifunc' => 'content_58a47afeca98a8_77448704',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a47afeca98a8_77448704')) {function content_58a47afeca98a8_77448704($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/Users/bart/Websites/themarket/library/smarty/libs/plugins/modifier.truncate.php';
?><?php if ($_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['breakall']) {?><span class="breakall"><?php }?>
<?php echo smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']]));?>

<?php if ($_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['breakall']) {?></span><?php }?>
<?php }} ?>
