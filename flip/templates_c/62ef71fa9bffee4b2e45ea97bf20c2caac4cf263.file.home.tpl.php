<?php /* Smarty version Smarty-3.1.18, created on 2015-06-02 15:11:37
         compiled from "./templates/home.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2088996116556dab89e2ac39-31123806%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '62ef71fa9bffee4b2e45ea97bf20c2caac4cf263' => 
    array (
      0 => './templates/home.tpl',
      1 => 1433248473,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2088996116556dab89e2ac39-31123806',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'home_main' => 0,
    'home_sub' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dab89e6cf07_42400145',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dab89e6cf07_42400145')) {function content_556dab89e6cf07_42400145($_smarty_tpl) {?>	<section class="posts-main">
			<div class="container">
				<div class="row">
					<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['home_main']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
						<?php echo $_smarty_tpl->getSubTemplate ("home_item.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

					<?php } ?>
				</div>
			</div>
		</section>
		
		<section class="posts-sub">
			<div class="container">
				<div class="row multi-columns-row">
					<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['home_sub']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
						<?php echo $_smarty_tpl->getSubTemplate ("home_item.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


					<?php } ?>				
				</div>
			</div>
		</section>
	
	


	
	
	
	
	
	
	
	
	
	
	
	

<?php }} ?>
