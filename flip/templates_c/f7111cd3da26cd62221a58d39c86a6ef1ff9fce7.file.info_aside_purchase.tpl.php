<?php /* Smarty version Smarty-3.1.18, created on 2016-12-29 22:04:24
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/flip/templates/info_aside_purchase.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1769386532585a901302b383-89328185%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f7111cd3da26cd62221a58d39c86a6ef1ff9fce7' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/flip/templates/info_aside_purchase.tpl',
      1 => 1483045427,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1769386532585a901302b383-89328185',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_585a90130337c0_55833931',
  'variables' => 
  array (
    'data' => 0,
    'person' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_585a90130337c0_55833931')) {function content_585a90130337c0_55833931($_smarty_tpl) {?><div class="info-aside" id="people_id_selected">
	<p id="familyname"><?php echo $_smarty_tpl->tpl_vars['data']->value['name']['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['data']->value['name']['lastname'];?>
</p>
	<p>Adults: <span id="adults"><?php echo $_smarty_tpl->tpl_vars['data']->value['adults'];?>
</span>, children: <span id="children"><?php echo $_smarty_tpl->tpl_vars['data']->value['children'];?>
</span></p>
	<ul class="people-list">
	<?php  $_smarty_tpl->tpl_vars['person'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['person']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value['people']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['person']->key => $_smarty_tpl->tpl_vars['person']->value) {
$_smarty_tpl->tpl_vars['person']->_loop = true;
?>
		<li <?php if ($_smarty_tpl->tpl_vars['person']->value['parent_id']==0) {?>class="parent"<?php }?>><a href="?action=people_edit&amp;id=<?php echo $_smarty_tpl->tpl_vars['person']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['person']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['person']->value['lastname'];?>
 (<?php echo $_smarty_tpl->tpl_vars['person']->value['gender'];?>
, <?php echo $_smarty_tpl->tpl_vars['person']->value['age'];?>
)</a><?php if ($_smarty_tpl->tpl_vars['person']->value['comments']) {?><br /><span class="people-comment"><?php echo $_smarty_tpl->tpl_vars['person']->value['comments'];?>
</span><?php }?></li>
	<?php } ?>
	</ul>
	<p class="familycredit"><i class="fa fa-tint"></i> <span id="dropcredit" data-drop-credit="<?php echo $_smarty_tpl->tpl_vars['data']->value['dropcoins'];?>
"><?php echo $_smarty_tpl->tpl_vars['data']->value['dropcoins'];?>
</span> drop coins</p>
	<p id="product_id_selected" class="hidden">Costs: <i class="fa fa-tint"></i> <span id="productvalue">0</span> drop coins</p>
	<p id="not_enough_coins" class="hidden">This family has not enough Drop Coins to make this purchase.</p>
</div><?php }} ?>
