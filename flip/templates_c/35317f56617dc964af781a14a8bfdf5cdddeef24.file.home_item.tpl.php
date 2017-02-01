<?php /* Smarty version Smarty-3.1.18, created on 2016-01-05 12:23:41
         compiled from "./templates/home_item.tpl" */ ?>
<?php /*%%SmartyHeaderCode:301649435556dab89e73b12-52519167%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '35317f56617dc964af781a14a8bfdf5cdddeef24' => 
    array (
      0 => './templates/home_item.tpl',
      1 => 1451993020,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '301649435556dab89e73b12-52519167',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dab89e9c3f0_96152032',
  'variables' => 
  array (
    'item' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dab89e9c3f0_96152032')) {function content_556dab89e9c3f0_96152032($_smarty_tpl) {?>					<div class="col col-sm-12 col-md-4 col-lg-4 transitionFx fx-fadein">
						<div class="post">
							<a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
" target="<?php echo $_smarty_tpl->tpl_vars['item']->value['target'];?>
">
								<div class="post-header">
									<?php if ($_smarty_tpl->tpl_vars['item']->value['image']) {?><div class="post-image"><img src="/content/home/<?php echo basename($_smarty_tpl->tpl_vars['item']->value['image']);?>
" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value['subtitle'];?>
"></div><?php }?>
									<div class="post-title">
										<h3><?php echo $_smarty_tpl->tpl_vars['item']->value['subtitle'];?>
</h3>
										<h2 class="hyphenate"><?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
</h2>
									</div>
								</div>
							</a>
						</div>
					</div><?php }} ?>
