<?php /* Smarty version Smarty-3.1.18, created on 2016-09-21 15:55:44
         compiled from "./templates/case_detail.tpl" */ ?>
<?php /*%%SmartyHeaderCode:144352837157e2916068e305-19553835%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'acbbc6ed9a7ec30c11f8073aefabf7b759ee123d' => 
    array (
      0 => './templates/case_detail.tpl',
      1 => 1474466143,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '144352837157e2916068e305-19553835',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_57e29160731c07_67682697',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57e29160731c07_67682697')) {function content_57e29160731c07_67682697($_smarty_tpl) {?>		<article class="case-detail article">
			<header>
				<div class="container">
					<div class="header-title">
						<h3><?php echo $_smarty_tpl->tpl_vars['page']->value['function'];?>
</h3>
						<h1><?php echo $_smarty_tpl->tpl_vars['page']->value['title'];?>
</h1>
					</div>		
				</div>
			</header>
			<section class="article-container">
				<div class="container">
					<div class="row">
						<div class="col col-sm-12 col-md-8 col-article col-article-content">
							<div class="article-conten-case-detail">	
								<?php echo $_smarty_tpl->tpl_vars['page']->value['intro'];?>

							</div>
							<div class="article-content content">
								<?php echo $_smarty_tpl->tpl_vars['page']->value['content'];?>

							</div>
						</div>
						<div class="col col-sm-12 col-md-4 col-article">
							<div class="sidebar">
								<div class="article-details-case-detail-profilepicture">
									<img src="<?php ob_start();?><?php echo basename($_smarty_tpl->tpl_vars['page']->value['image']);?>
<?php $_tmp1=ob_get_clean();?><?php if (basename($_smarty_tpl->tpl_vars['page']->value['image'])&&file_exists(((string)$_SERVER['DOCUMENT_ROOT'])."/content/cases/".$_tmp1)) {?>/content/cases/<?php echo basename($_smarty_tpl->tpl_vars['page']->value['image']);?>
<?php } else { ?>/site/img/profile-default.svg<?php }?>" alt="<?php echo $_smarty_tpl->tpl_vars['page']->value['title'];?>
" />
								</div>	
							</div>
						</div>
					</div>
				</div>
			</section>
			<?php echo $_smarty_tpl->getSubTemplate ("related.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		</article><?php }} ?>
