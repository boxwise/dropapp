<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 17:00:42
         compiled from "/Users/bart/Websites/themarket/library/templates/info_aside_purchase.tpl" */ ?>
<?php /*%%SmartyHeaderCode:90475487658a47b2a6e4146-78355229%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3e8b3e42ce92cf8dd209ceb698274d76fcafecc4' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/info_aside_purchase.tpl',
      1 => 1486395494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '90475487658a47b2a6e4146-78355229',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    'person' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a47b2a710914_65326414',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a47b2a710914_65326414')) {function content_58a47b2a710914_65326414($_smarty_tpl) {?><div class="info-aside" id="people_id_selected">
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
</span> drops <?php if ($_smarty_tpl->tpl_vars['data']->value['allowdrops']) {?><a class="btn btn-sm" href="<?php echo $_smarty_tpl->tpl_vars['data']->value['givedropsurl'];?>
"><i class="fa fa-tint"></i> Give drops</a><?php }?></p>
	<p id="product_id_selected" class="hidden">Costs: <i class="fa fa-tint"></i> <span id="productvalue">0</span> drops</p>
	<p id="not_enough_coins" class="hidden">This family has not enough Drop Coins to make this purchase.</p>
</div><?php }} ?>
