<?php /* Smarty version Smarty-3.1.18, created on 2015-06-02 15:14:51
         compiled from "./templates/product_overview.tpl" */ ?>
<?php /*%%SmartyHeaderCode:27027038556dac4b5f9616-91384591%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ce19b39191a55124a352c4a08a05615794121a09' => 
    array (
      0 => './templates/product_overview.tpl',
      1 => 1433248475,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '27027038556dac4b5f9616-91384591',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'tags' => 0,
    'tag' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dac4b65f706_51024953',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dac4b65f706_51024953')) {function content_556dac4b65f706_51024953($_smarty_tpl) {?><section class="product-overview">
	
	<div class="product-overview-filters">
		<div class="container">
			<div class="filterlist">
				<ul>
					<?php  $_smarty_tpl->tpl_vars['tag'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tag']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tags']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tag']->key => $_smarty_tpl->tpl_vars['tag']->value) {
$_smarty_tpl->tpl_vars['tag']->_loop = true;
?>						
						<li<?php if ($_GET['get1']==$_smarty_tpl->tpl_vars['tag']->value['url']) {?> class="active"<?php }?>><a href="/productoverzicht/<?php if ($_GET['get1']!=$_smarty_tpl->tpl_vars['tag']->value['url']) {?><?php echo $_smarty_tpl->tpl_vars['tag']->value['url'];?>
<?php }?>" class="filter" data-url="<?php echo $_smarty_tpl->tpl_vars['tag']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['tag']->value['name'];?>
<?php if ($_GET['get1']==$_smarty_tpl->tpl_vars['tag']->value['url']) {?><span class="icon icon-close"></span><?php }?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
	<div class="product-overview-items">
		<?php echo $_smarty_tpl->getSubTemplate ("product_overview_items.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	</div>
</section>
<?php }} ?>
