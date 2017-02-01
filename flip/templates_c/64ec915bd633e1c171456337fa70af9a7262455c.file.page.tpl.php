<?php /* Smarty version Smarty-3.1.18, created on 2015-08-18 14:11:30
         compiled from "./templates/page.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1041113291556dab443e2f75-97154737%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '64ec915bd633e1c171456337fa70af9a7262455c' => 
    array (
      0 => './templates/page.tpl',
      1 => 1439899793,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1041113291556dab443e2f75-97154737',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dab4441b106_68502853',
  'variables' => 
  array (
    'page' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dab4441b106_68502853')) {function content_556dab4441b106_68502853($_smarty_tpl) {?>
		<article class="product article">
			<header>
				<?php if ($_smarty_tpl->tpl_vars['page']->value['image']) {?><div class="header-wide" style="background-image: url('/content/page/<?php echo basename($_smarty_tpl->tpl_vars['page']->value['image']);?>
');" /><?php }?>
					<div class="container">
						<div class="header-title">
							<h3><?php echo $_smarty_tpl->tpl_vars['page']->value['pagesubtitle'];?>
</h3>
							<h1 class="hyphenate"><?php echo $_smarty_tpl->tpl_vars['page']->value['pagetitle'];?>
</h1>
						</div>
					</div>
				<?php if ($_smarty_tpl->tpl_vars['page']->value['image']) {?></div><?php }?>
			</header>
			<section class="article-container">
				<?php if ($_smarty_tpl->tpl_vars['page']->value['intro']) {?>
				<div class="container">
					<div class="row">
						<div class="col col-sm-12">
							<div class="article-intro">
								<?php echo $_smarty_tpl->tpl_vars['page']->value['intro'];?>

							</div>
						</div>
					</div>
				</div>
				<?php }?>
				<div class="container">
					<div class="row">
						<div class="col col-sm-12 col-md-8 col-article col-article-content">
							<div class="article-content content clearfix">
								<?php echo $_smarty_tpl->tpl_vars['page']->value['content'];?>

							</div>
							<?php echo $_smarty_tpl->getSubTemplate ("socialsharing.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

						</div>
						<div class="col col-sm-12 col-md-4 col-article">
							<div class="sidebar">
								<?php echo $_smarty_tpl->getSubTemplate ("page_person.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

							</div>
						</div>
					</div>
				</div>
			</section>

			<?php echo $_smarty_tpl->getSubTemplate ("related.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
