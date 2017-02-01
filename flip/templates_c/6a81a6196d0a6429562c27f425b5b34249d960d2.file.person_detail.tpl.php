<?php /* Smarty version Smarty-3.1.18, created on 2015-06-08 10:32:14
         compiled from "./templates/person_detail.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1321985669556dac0ccff175-19573336%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6a81a6196d0a6429562c27f425b5b34249d960d2' => 
    array (
      0 => './templates/person_detail.tpl',
      1 => 1433752331,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1321985669556dac0ccff175-19573336',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dac0cd74015_61492203',
  'variables' => 
  array (
    'page' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dac0cd74015_61492203')) {function content_556dac0cd74015_61492203($_smarty_tpl) {?>
		<article class="person-detail article">
			<header>
				<div class="container">
					<div class="header-title">
						<h3><?php echo $_smarty_tpl->tpl_vars['page']->value['function'];?>
</h3>
						<h1><?php echo $_smarty_tpl->tpl_vars['page']->value['fullname'];?>
</h1>
					</div>		
				</div>
			</header>
			<section class="article-container">
				<div class="container">
					<div class="row">
						<div class="col col-sm-12 col-md-8 col-article col-article-content">
							<div class="article-content-person-detail">	
								<?php if ($_smarty_tpl->tpl_vars['page']->value['tel']) {?><div class="article-content-person-detail-phone"><?php echo $_smarty_tpl->tpl_vars['page']->value['tel'];?>
</div><?php }?>
								<?php if ($_smarty_tpl->tpl_vars['page']->value['email']) {?><div class="article-content-person-detail-mail"><a href="mailto:<?php echo $_smarty_tpl->tpl_vars['page']->value['email'];?>
"><?php echo $_smarty_tpl->tpl_vars['page']->value['email'];?>
</a></div><?php }?>
								<div class="article-content-person-detail-socials">
									<?php if ($_smarty_tpl->tpl_vars['page']->value['twitter']) {?><a href="<?php echo $_smarty_tpl->tpl_vars['page']->value['twitter'];?>
" target="_blank" class="btn btn-small">Twitter <span class="icon icon-twitter"></span></a><?php }?>
									<?php if ($_smarty_tpl->tpl_vars['page']->value['linkedin']) {?><a href="<?php echo $_smarty_tpl->tpl_vars['page']->value['linkedin'];?>
" target="_blank" class="btn btn-small">LinkedIn <span class="icon icon-linkedin"></span></a><?php }?>
									<?php if ($_smarty_tpl->tpl_vars['page']->value['website']) {?><a href="<?php echo $_smarty_tpl->tpl_vars['page']->value['website'];?>
" target="_blank" class="btn btn-small">Website <span class="icon icon-globe"></span></a><?php }?>
								</div>
							</div>
							<div class="article-content content">
								<?php echo $_smarty_tpl->tpl_vars['page']->value['content'];?>

							</div>
						</div>
						<div class="col col-sm-12 col-md-4 col-article">
							<div class="sidebar">
								<div class="article-details-person-detail-profilepicture">
									<img src="<?php ob_start();?><?php echo basename($_smarty_tpl->tpl_vars['page']->value['image']);?>
<?php $_tmp1=ob_get_clean();?><?php if (basename($_smarty_tpl->tpl_vars['page']->value['image'])&&file_exists(((string)$_SERVER['DOCUMENT_ROOT'])."/content/mensen/".$_tmp1)) {?>/content/mensen/<?php echo basename($_smarty_tpl->tpl_vars['page']->value['image']);?>
<?php } else { ?>/site/img/profile-default.svg<?php }?>" alt="<?php echo $_smarty_tpl->tpl_vars['page']->value['fullname'];?>
" />
									<?php if ($_smarty_tpl->tpl_vars['page']->value['fotocredit']) {?>
										<p class="caption"><?php echo $_smarty_tpl->tpl_vars['page']->value['fotocredit'];?>
</p>
									<?php }?>
								</div>	
							</div>
						</div>
					</div>
				</div>
			</section>
			<?php echo $_smarty_tpl->getSubTemplate ("related.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		</article>

<?php }} ?>
