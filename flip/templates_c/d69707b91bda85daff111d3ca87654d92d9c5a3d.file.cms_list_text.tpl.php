<?php /* Smarty version Smarty-3.1.18, created on 2017-01-19 18:32:34
         compiled from "/home/drapeton/market/50-back/templates/cms_list_text.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2694853875880ea22f2fd43-00476322%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd69707b91bda85daff111d3ca87654d92d9c5a3d' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_list_text.tpl',
      1 => 1484776982,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2694853875880ea22f2fd43-00476322',
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
  'unifunc' => 'content_5880ea22f3bfb1_18007484',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5880ea22f3bfb1_18007484')) {function content_5880ea22f3bfb1_18007484($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/home/drapeton/market/50-back/smarty/libs/plugins/modifier.truncate.php';
?><?php if ($_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['breakall']) {?><span class="breakall"><?php }?>
<?php echo smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['column']->value['field']]));?>

<?php if ($_smarty_tpl->tpl_vars['listdata']->value[$_smarty_tpl->tpl_vars['column']->value['field']]['breakall']) {?></span><?php }?>
<?php }} ?>
