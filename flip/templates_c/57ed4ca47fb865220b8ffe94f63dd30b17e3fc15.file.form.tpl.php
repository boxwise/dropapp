<?php /* Smarty version Smarty-3.1.18, created on 2015-09-15 15:06:56
         compiled from "./templates/form.tpl" */ ?>
<?php /*%%SmartyHeaderCode:696866897556dac46e7de59-33274632%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '57ed4ca47fb865220b8ffe94f63dd30b17e3fc15' => 
    array (
      0 => './templates/form.tpl',
      1 => 1442322123,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '696866897556dac46e7de59-33274632',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dac46eddc01_33690022',
  'variables' => 
  array (
    'translate' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dac46eddc01_33690022')) {function content_556dac46eddc01_33690022($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/smarty/libs/plugins/modifier.replace.php';
?>
		<article class="product article">

			<section class="article-container">
				<form method="post" id="orderform">
					<div class="container">
						<div class="row">
							<div class="col col-sm-12">
								<div class="article-intro article-intro-form">
									<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['page']->value['pagetitle'];?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['translate']->value['product_form'],'%%',$_tmp1);?>

								</div>
								<div class="article-intro-form-details">
									<?php if ($_smarty_tpl->tpl_vars['page']->value['dateinfo']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['dateinfo'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['page']->value['dateinfo']&&$_smarty_tpl->tpl_vars['page']->value['duration']) {?><br /><?php }?>
									<?php if ($_smarty_tpl->tpl_vars['page']->value['location']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['location'];?>
<?php }?>
									<?php if ($_smarty_tpl->tpl_vars['page']->value['url']=='seminar-de-waarde-van-creativiteit-voor-het-bedrijfsleven-19-nov') {?>
									<br/>Kosten voor het seminar zijn € 695. Neemt u een collega mee? Dan betaalt u in totaal € 1100.
									<?php }?>
								</div>
								<?php if ($_smarty_tpl->tpl_vars['page']->value['fullybooked']) {?>
									<?php echo $_smarty_tpl->tpl_vars['translate']->value['product_fullybooked_explanation'];?>

									<a href="#" class="btn btn-disabled form"><?php echo $_smarty_tpl->tpl_vars['translate']->value['product_fullybooked'];?>
</a>
								<?php }?>
							</div>
						</div>
					</div>
					<div class="container">
						<div class="row">
							<div class="col col-sm-12 col-article">
								<div class="order-form">
									<?php if (!$_smarty_tpl->tpl_vars['page']->value['fullybooked']) {?>
										<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['page']->value['form_template'], $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

									<?php }?>
								</div>
							</div>
						</div>
					</div>
				</form>
			</section>
		</article>
<?php }} ?>
