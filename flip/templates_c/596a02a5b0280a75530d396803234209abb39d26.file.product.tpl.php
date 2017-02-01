<?php /* Smarty version Smarty-3.1.18, created on 2016-09-23 13:46:03
         compiled from "./templates/product.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1475476008556dab94d8da48-71744666%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '596a02a5b0280a75530d396803234209abb39d26' => 
    array (
      0 => './templates/product.tpl',
      1 => 1474631159,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1475476008556dab94d8da48-71744666',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dab94e3e1c7_14410948',
  'variables' => 
  array (
    'page' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dab94e3e1c7_14410948')) {function content_556dab94e3e1c7_14410948($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/smarty/libs/plugins/modifier.replace.php';
?>
		<article class="product article">
			<header>
				<div class="header-wide" style="background-image: url('/content/product-svg/<?php echo $_smarty_tpl->tpl_vars['page']->value['url'];?>
-big.svg'); " />
					<div class="container">
						<div class="header-title">
							<h3><?php echo $_smarty_tpl->tpl_vars['page']->value['pagesubtitle'];?>
</h3>
							<h1 class="hyphenate"><?php echo $_smarty_tpl->tpl_vars['page']->value['pagetitle'];?>
</h1>
						</div>
					</div>
				</div>
			</header>
			<section class="article-container">
				<div class="container">
					<div class="row">
						<div class="col col-sm-12">
							<div class="article-intro">
								<?php echo $_smarty_tpl->tpl_vars['page']->value['intro'];?>

							</div>
						</div>
					</div>
				</div>
				<div class="container">
					<div class="row">
						<div class="col col-sm-12 col-md-8 col-article col-article-content">
							<?php echo $_smarty_tpl->getSubTemplate ("product_call2action.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

							<div class="article-content content clearfix">
								<?php echo $_smarty_tpl->tpl_vars['page']->value['content'];?>

							</div>
							<?php echo $_smarty_tpl->getSubTemplate ("socialsharing.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

						</div>
						<div class="col col-sm-12 col-md-4 col-article">
							<div class="sidebar">

								<?php echo $_smarty_tpl->getSubTemplate ("page_person.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

								<div class="article-details">
									<dl>
									<?php if ($_smarty_tpl->tpl_vars['page']->value['dateinfo']) {?>
										<dt>data</dt>
										<dd><?php echo $_smarty_tpl->tpl_vars['page']->value['dateinfo'];?>
</dd>
									<?php }?>
									<?php if ($_smarty_tpl->tpl_vars['page']->value['duration']) {?>
										<dt>duur</dt>
										<dd><?php echo $_smarty_tpl->tpl_vars['page']->value['duration'];?>
</dd>
									<?php }?>
									<?php if ($_smarty_tpl->tpl_vars['page']->value['location']) {?>
										<dt>locatie</dt>
										<dd><?php echo $_smarty_tpl->tpl_vars['page']->value['location'];?>
</dd>
									<?php }?>
									<?php if ($_smarty_tpl->tpl_vars['page']->value['displayprice']) {?>
										<dt>kosten</dt>
										<dd><?php echo $_smarty_tpl->tpl_vars['page']->value['displayprice'];?>
</dd>
										<?php if ($_smarty_tpl->tpl_vars['page']->value['price']>0) {?>
											<div class="article-details-sub"><?php ob_start();?><?php echo ($_smarty_tpl->tpl_vars['page']->value['btw']).('%');?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['page']->value['btw-of-geen-btw'],'%%',$_tmp1);?>
</div>
											<?php if ($_smarty_tpl->tpl_vars['page']->value['discount']) {?>
												<div class="article-details-sub discount">
													<?php echo $_smarty_tpl->tpl_vars['translate']->value['discount_explanation'];?>

												</div>
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['page']->value['sectorplancultuur']) {?>
												<div class="article-details-sub sectorplancultuur">
													<img src="/site/img/logo-sectorplancultuur.png" alt="Sectorplan Cultuur" />
													<?php echo $_smarty_tpl->tpl_vars['translate']->value['discount_sectorplancultuur_explanation'];?>

												</div>
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['page']->value['nvtc']) {?>
												<div class="article-details-sub discount">
													<?php echo $_smarty_tpl->tpl_vars['translate']->value['nvtc_free'];?>

												</div>
											<?php }?>
											<div class="payment-options-logos">
												<span class="payment-option payment-option-ideal">iDEAL</span><span class="payment-option payment-option-mastercard">Mastercard</span><span class="payment-option payment-option-visa">Visa</span>
											</div>
										<?php }?>
									<?php }?>
									</dl>
								</div>
							<?php echo $_smarty_tpl->getSubTemplate ("product_call2action.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

							</div>
						</div>
					</div>
				</div>
			</section>
			<?php echo $_smarty_tpl->getSubTemplate ("related.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<?php }} ?>
