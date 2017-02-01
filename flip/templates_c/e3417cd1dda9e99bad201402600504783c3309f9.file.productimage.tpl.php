<?php /* Smarty version Smarty-3.1.18, created on 2015-10-22 17:25:51
         compiled from "/var/www/vhosts/cultuur-ondernemen.nl/httpdocs/flip/templates/productimage.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2050820983556db99c495989-10423149%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e3417cd1dda9e99bad201402600504783c3309f9' => 
    array (
      0 => '/var/www/vhosts/cultuur-ondernemen.nl/httpdocs/flip/templates/productimage.tpl',
      1 => 1445527510,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2050820983556db99c495989-10423149',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556db99c5b4447_87927297',
  'variables' => 
  array (
    'd' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556db99c5b4447_87927297')) {function content_556db99c5b4447_87927297($_smarty_tpl) {?><?php echo '<?xml';?> version="1.0" encoding="utf-8"<?php echo '?>';?>

<!-- Generator: Cultuur+Ondernemen image generator by Zinnebeeld (C) 2015  -->
<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="<?php echo $_smarty_tpl->tpl_vars['d']->value['bx'];?>
px" height="<?php echo $_smarty_tpl->tpl_vars['d']->value['by'];?>
px" viewBox="5 5 <?php echo $_smarty_tpl->tpl_vars['d']->value['bx']-10;?>
 <?php echo $_smarty_tpl->tpl_vars['d']->value['by']-10;?>
" enable-background="new 0 0 <?php echo $_smarty_tpl->tpl_vars['d']->value['bx'];?>
 <?php echo $_smarty_tpl->tpl_vars['d']->value['by'];?>
" xml:space="preserve">
	 <polygon fill="<?php echo $_smarty_tpl->tpl_vars['d']->value['color3'];?>
" points="0,0 <?php echo $_smarty_tpl->tpl_vars['d']->value['bx'];?>
,0 <?php echo $_smarty_tpl->tpl_vars['d']->value['bx'];?>
,<?php echo $_smarty_tpl->tpl_vars['d']->value['by'];?>
 0,<?php echo $_smarty_tpl->tpl_vars['d']->value['by'];?>
 "/>
	 <polygon fill="#eee" points="<?php echo $_smarty_tpl->tpl_vars['d']->value['x'];?>
,<?php echo $_smarty_tpl->tpl_vars['d']->value['y'];?>
 <?php echo $_smarty_tpl->tpl_vars['d']->value['x']-intval(($_smarty_tpl->tpl_vars['d']->value['c1']*$_smarty_tpl->tpl_vars['d']->value['y']));?>
,0 <?php echo $_smarty_tpl->tpl_vars['d']->value['x']+intval(($_smarty_tpl->tpl_vars['d']->value['c2']*$_smarty_tpl->tpl_vars['d']->value['y']));?>
,0  "/>
	 <polygon fill="<?php echo $_smarty_tpl->tpl_vars['d']->value['color2'];?>
" points="<?php echo $_smarty_tpl->tpl_vars['d']->value['x'];?>
,<?php echo $_smarty_tpl->tpl_vars['d']->value['y'];?>
 <?php echo $_smarty_tpl->tpl_vars['d']->value['x']+intval(($_smarty_tpl->tpl_vars['d']->value['c2']*$_smarty_tpl->tpl_vars['d']->value['y']));?>
,0 <?php echo $_smarty_tpl->tpl_vars['d']->value['bx'];?>
,0 <?php echo $_smarty_tpl->tpl_vars['d']->value['bx'];?>
,<?php echo $_smarty_tpl->tpl_vars['d']->value['by'];?>
 <?php echo $_smarty_tpl->tpl_vars['d']->value['x']+intval(($_smarty_tpl->tpl_vars['d']->value['c1']*($_smarty_tpl->tpl_vars['d']->value['by']-$_smarty_tpl->tpl_vars['d']->value['y'])));?>
,<?php echo $_smarty_tpl->tpl_vars['d']->value['by'];?>
 "/>
	 <polygon fill="<?php echo $_smarty_tpl->tpl_vars['d']->value['color1'];?>
" points="<?php echo $_smarty_tpl->tpl_vars['d']->value['x'];?>
,<?php echo $_smarty_tpl->tpl_vars['d']->value['y'];?>
 <?php echo $_smarty_tpl->tpl_vars['d']->value['x']-intval(($_smarty_tpl->tpl_vars['d']->value['c1']*$_smarty_tpl->tpl_vars['d']->value['y']));?>
,0 0,0 0,<?php echo $_smarty_tpl->tpl_vars['d']->value['by'];?>
 <?php echo $_smarty_tpl->tpl_vars['d']->value['x']-intval(($_smarty_tpl->tpl_vars['d']->value['c2']*($_smarty_tpl->tpl_vars['d']->value['by']-$_smarty_tpl->tpl_vars['d']->value['y'])));?>
,<?php echo $_smarty_tpl->tpl_vars['d']->value['by'];?>
 "/>
</svg><?php }} ?>
