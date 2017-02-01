<?php /* Smarty version Smarty-3.1.18, created on 2016-09-20 15:10:37
         compiled from "./templates/order-succes.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1756582954556dcb2dd2a685-34156377%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '14a85d1a9d590afbd6000c1039646b5b09649050' => 
    array (
      0 => './templates/order-succes.tpl',
      1 => 1474376931,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1756582954556dcb2dd2a685-34156377',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dcb2dda8782_67004586',
  'variables' => 
  array (
    'order' => 0,
    'page' => 0,
    'translate' => 0,
    'owner' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dcb2dda8782_67004586')) {function content_556dcb2dda8782_67004586($_smarty_tpl) {?>	
	<script>
		
		ga('require', 'ecommerce');

		ga('ecommerce:addTransaction', {
		  'id': '<?php echo $_smarty_tpl->tpl_vars['order']->value['id'];?>
',
		  'affiliation': 'Cultuur+Ondernemen',
		  'revenue': '<?php echo $_smarty_tpl->tpl_vars['order']->value['amount_paid'];?>
'
		});

		ga('ecommerce:addItem', {
		  'id': '<?php echo $_smarty_tpl->tpl_vars['order']->value['id'];?>
',
		  'name': '<?php echo $_smarty_tpl->tpl_vars['page']->value['pagetitle'];?>
',
		  'sku': '<?php echo $_smarty_tpl->tpl_vars['order']->value['products_id'];?>
',
		  'quantity': '1'
		});
	  
		ga('ecommerce:send');

	</script>
	


		<article class="product article">

			<section class="article-container">
				<form method="post">
					<div class="container">
						<div class="row">
							<div class="col col-sm-12">
								<div class="article-intro article-intro-form">
                <?php if ($_smarty_tpl->tpl_vars['order']->value['price']==0) {?>
                  Je aanmelding is ontvangen.
                <?php } else { ?>
                  Je betaling is ontvangen.
                <?php }?>
								</div>
								<div class="article-intro-form-details">
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
                        <?php if ($_smarty_tpl->tpl_vars['order']->value['price']==0) {?>
                          Bedankt voor je aanmelding. Je ontvangt binnen enkele minuten een e-mail met een bevestiging.
                        <?php } else { ?>
                          <?php echo $_smarty_tpl->tpl_vars['translate']->value['product_success'];?>

                        <?php }?>
												
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
