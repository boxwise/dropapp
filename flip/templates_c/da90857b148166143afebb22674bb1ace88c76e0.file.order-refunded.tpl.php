<?php /* Smarty version Smarty-3.1.18, created on 2015-10-06 16:38:15
         compiled from "./templates/order-refunded.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2928995395613d7328bf223-55184256%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'da90857b148166143afebb22674bb1ace88c76e0' => 
    array (
      0 => './templates/order-refunded.tpl',
      1 => 1444142292,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2928995395613d7328bf223-55184256',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5613d732933626_11026938',
  'variables' => 
  array (
    'order' => 0,
    'translate' => 0,
    'page' => 0,
    'owner' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5613d732933626_11026938')) {function content_5613d732933626_11026938($_smarty_tpl) {?>
		<article class="product article">

			<section class="article-container">
				<form method="post">
					<div class="container">
						<div class="row">
							<div class="col col-sm-12">
								<div class="article-intro article-intro-form">
									De betaling is teruggestort.
								</div>
								<div class="article-intro-form-details">
									<?php if ($_smarty_tpl->tpl_vars['order']->value['invoice_number']) {?>Factuurnummer: <?php echo $_smarty_tpl->tpl_vars['order']->value['invoice_number'];?>
<br/><?php }?>
									<?php echo ucfirst($_smarty_tpl->tpl_vars['translate']->value['product_type']);?>
 ‘<?php echo $_smarty_tpl->tpl_vars['page']->value['pagetitle'];?>
’<br />
									<?php if ($_smarty_tpl->tpl_vars['page']->value['dateinfo']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['dateinfo'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['page']->value['dateinfo']&&$_smarty_tpl->tpl_vars['page']->value['duration']) {?>, <?php }?>
									<?php if ($_smarty_tpl->tpl_vars['page']->value['duration']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['duration'];?>
<?php }?>
									<br />
									<?php if ($_smarty_tpl->tpl_vars['page']->value['location']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['location'];?>
<?php }?>
								</div>
							</div>
						</div>
					</div>
					<div class="container">
						<div class="row">
							<div class="col col-sm-12 col-article">
								<div class="order-form">
									<div class="form-section">
									    <div class="row">
										    <div class="col col-sm-8 col-md-9 col-lg-8">

												<?php if ($_smarty_tpl->tpl_vars['page']->value['producttype']=='training') {?>
												<p>Voor vragen neem je contact op met <a href="/medewerkers/<?php echo $_smarty_tpl->tpl_vars['owner']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['owner']->value['firstname'];?>
</a>.</p>
												<?php }?>
										    </div>
									    </div>
								    </div>
									<div class="form-section">
									    <div class="row">
										    <div class="col col-sm-8 col-md-9 col-lg-8">
												<?php echo $_smarty_tpl->getSubTemplate ("socialsharing.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

										    </div>
									    </div>
								    </div>
								</div>
							</div>
						</div>
					</div>

				</form>
			</section>
		</article>
<?php }} ?>
