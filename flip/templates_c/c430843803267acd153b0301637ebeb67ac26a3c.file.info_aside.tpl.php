<?php /* Smarty version Smarty-3.1.18, created on 2016-12-21 15:04:19
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/flip/templates/info_aside.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2041998372585a82cc405d52-14119171%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c430843803267acd153b0301637ebeb67ac26a3c' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/flip/templates/info_aside.tpl',
      1 => 1482329047,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2041998372585a82cc405d52-14119171',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_585a82cc433701_08604773',
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_585a82cc433701_08604773')) {function content_585a82cc433701_08604773($_smarty_tpl) {?><div id="people_id_selected">
	<a href="?action=people_edit&amp;id=<?php echo $_smarty_tpl->tpl_vars['data']->value['id'];?>
" id="familyname"><?php echo $_smarty_tpl->tpl_vars['data']->value['name']['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['data']->value['name']['lastname'];?>
</a>
	<div>Adults: <span id="adults"><?php echo $_smarty_tpl->tpl_vars['data']->value['adults'];?>
</span>, children: <span id="children"><?php echo $_smarty_tpl->tpl_vars['data']->value['children'];?>
</span></div>
	<div class="familycredit"><i class="fa fa-tint"></i> <span id="dropcredit" data-drop-credit="<?php echo $_smarty_tpl->tpl_vars['data']->value['dropcoins'];?>
"><?php echo $_smarty_tpl->tpl_vars['data']->value['dropcoins'];?>
</span> drop coins</div>
	<p id="product_id_selected" class="hidden">Costs: <i class="fa fa-tint"></i> <span id="productvalue">0</span> drop coins</p>
	<p id="not_enough_coins" class="hidden">This family has not enough Drop Coins to make this purchase.</p>
</div><?php }} ?>
