<?php /* Smarty version Smarty-3.1.18, created on 2015-06-03 15:36:11
         compiled from "./templates/order-geannuleerd.tpl" */ ?>
<?php /*%%SmartyHeaderCode:561135743556f02cb499a15-82008535%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0f518b11f3bb5e1db18b4e5562f853797b94f310' => 
    array (
      0 => './templates/order-geannuleerd.tpl',
      1 => 1433248474,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '561135743556f02cb499a15-82008535',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'translate' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556f02cb56fd20_25536992',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556f02cb56fd20_25536992')) {function content_556f02cb56fd20_25536992($_smarty_tpl) {?>
		<article class="product article">	

			<section class="article-container">
				<form method="post">
					<div class="container">
						<div class="row">
							<div class="col col-sm-12">
								<div class="article-intro article-intro-form">
									We hebben je betaling niet ontvangen.
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
												<?php echo $_smarty_tpl->tpl_vars['translate']->value['product_cancel'];?>

										    </div>
									    </div>
								    </div>					
									<div class="form-section">
									    <div class="row">
										    <div class="col col-sm-8 col-md-9 col-lg-8">
												<div class="payment-button"><button class="btn btn-blue"><span class="btn-text">Probeer nog een keer</span><span class="icon icon-arrow-right"></span></button></div>
										    </div>
									    </div>
								    </div>							
								</div>
							</div>
						</div>
					</div>
					
					<input type="hidden" name="action" value="bestellen-retry" />
					<input type="hidden" name="hash" value="<?php echo $_GET['get2'];?>
" />
					<input type="text" name="special" class="special special-css" value="" />
				
				</form>
			</section>
		</article>
<?php }} ?>
