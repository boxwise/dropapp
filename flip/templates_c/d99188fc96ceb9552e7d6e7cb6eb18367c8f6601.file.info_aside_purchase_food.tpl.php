<?php /* Smarty version Smarty-3.1.18, created on 2016-12-21 16:22:43
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/flip/templates/info_aside_purchase_food.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1763073998585a9002e05224-10652207%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd99188fc96ceb9552e7d6e7cb6eb18367c8f6601' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/flip/templates/info_aside_purchase_food.tpl',
      1 => 1482333758,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1763073998585a9002e05224-10652207',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_585a9002e8d904_13281455',
  'variables' => 
  array (
    'data' => 0,
    'person' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_585a9002e8d904_13281455')) {function content_585a9002e8d904_13281455($_smarty_tpl) {?><div class="info-aside" id="people_id_selected">
	<p id="familyname"><?php echo $_smarty_tpl->tpl_vars['data']->value['name']['container'];?>
 <?php echo $_smarty_tpl->tpl_vars['data']->value['name']['lastname'];?>
</p>
	<p><?php echo count($_smarty_tpl->tpl_vars['data']->value['people']);?>
 people</p>
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
)</a></li>
	<?php } ?>
	</ul>
</div><?php }} ?>
