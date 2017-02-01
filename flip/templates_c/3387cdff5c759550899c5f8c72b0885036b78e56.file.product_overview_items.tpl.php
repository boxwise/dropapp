<?php /* Smarty version Smarty-3.1.18, created on 2015-11-11 15:57:34
         compiled from "../templates/product_overview_items.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1598259619556dabbd6215a7-79115417%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3387cdff5c759550899c5f8c72b0885036b78e56' => 
    array (
      0 => '../templates/product_overview_items.tpl',
      1 => 1447250630,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1598259619556dabbd6215a7-79115417',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dabbd678328_83638103',
  'variables' => 
  array (
    'products' => 0,
    'product' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dabbd678328_83638103')) {function content_556dabbd678328_83638103($_smarty_tpl) {?>
		<div class="container">
			<div class="row multi-columns-row">
				<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['products']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
					<div class="col col-sm-6 col-md-4 col-lg-3 product-overview-item transitionFx fx-fadein">
						<a href="/product/<?php echo $_smarty_tpl->tpl_vars['product']->value['url'];?>
">
							<div class="product-overview-item-image" style="text-align: center;">
								<?php if ($_smarty_tpl->tpl_vars['product']->value['sectorplancultuur']) {?>
									<div class="product-overview-item-label-sectorplancultuur">
										<p>Korting via Sectorplan Cultuur</p>
									</div>
								<?php }?>
								<img src="/content/product-svg/<?php echo $_smarty_tpl->tpl_vars['product']->value['url'];?>
-small.svg" alt="<?php echo $_smarty_tpl->tpl_vars['product']->value['menutitle'];?>
"/>
							</div>
							<div class="product-overview-item-title"><h2><?php echo $_smarty_tpl->tpl_vars['product']->value['menutitle'];?>
</h2></div>
						<?php if ($_smarty_tpl->tpl_vars['product']->value['buttontext_overview']&&!$_smarty_tpl->tpl_vars['product']->value['fullybooked']) {?>
							<div class="btn btn-small"><?php echo $_smarty_tpl->tpl_vars['product']->value['buttontext_overview'];?>
</div>
						<?php } elseif ($_smarty_tpl->tpl_vars['product']->value['fullybooked']) {?>
							<div class="btn btn-small btn-disabled">volgeboekt</div>
						<?php }?>
						</a>
					</div>
				<?php } ?>
				<?php if (!$_smarty_tpl->tpl_vars['products']->value) {?>
					<div class="col col-sm-12"><?php echo $_smarty_tpl->tpl_vars['translate']->value['product_noproducts'];?>
</div>
				<?php }?>
			</div>
		</div>
<?php }} ?>
