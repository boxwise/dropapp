<?php /* Smarty version Smarty-3.1.18, created on 2016-09-22 10:35:29
         compiled from "./templates/product_overview_items.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1427081544556dac4b6687a7-99119872%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd1c9786dfc521056efed56c472e3dc141cceaade' => 
    array (
      0 => './templates/product_overview_items.tpl',
      1 => 1474532003,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1427081544556dac4b6687a7-99119872',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dac4b68e239_80481506',
  'variables' => 
  array (
    'products' => 0,
    'product' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dac4b68e239_80481506')) {function content_556dac4b68e239_80481506($_smarty_tpl) {?>
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
								<?php if ($_smarty_tpl->tpl_vars['product']->value['discount']) {?>
									<div class="product-overview-item-label-discount">
										<p>Korting</p>
									</div>
								<?php }?>
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
