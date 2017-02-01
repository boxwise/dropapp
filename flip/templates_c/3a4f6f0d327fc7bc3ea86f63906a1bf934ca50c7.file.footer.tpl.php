<?php /* Smarty version Smarty-3.1.18, created on 2015-10-07 09:46:48
         compiled from "./templates/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:369159407556dab445622c9-09138420%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3a4f6f0d327fc7bc3ea86f63906a1bf934ca50c7' => 
    array (
      0 => './templates/footer.tpl',
      1 => 1444204007,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '369159407556dab445622c9-09138420',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dab445b4d00_46282792',
  'variables' => 
  array (
    'page' => 0,
    'settings' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dab445b4d00_46282792')) {function content_556dab445b4d00_46282792($_smarty_tpl) {?>		<?php ob_start();?><?php if ($_smarty_tpl->tpl_vars['page']->value['c2a_template']) {?><?php echo (string)$_smarty_tpl->tpl_vars['page']->value['c2a_template'];?><?php } else { ?><?php echo "c2a-productoverview.tpl";?><?php }?><?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp1, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


		<footer>
			<div class="container">
				<div class="row">
					<div class="col col-sm-6 col-md-2">
						<div class="footer-navigation">
							<ul>
								<li><a href="/vacatures">Vacatures</a></li>
								<li><a href="/pers">Pers</a></li>
							</ul>
						</div>
					</div>
					<div class="col col-sm-6 col-md-5">
						<div class="footer-address">
							<ul>
								<li><a href="mailto:<?php echo $_smarty_tpl->tpl_vars['settings']->value['email_default'];?>
"><?php echo $_smarty_tpl->tpl_vars['settings']->value['email_default'];?>
</a></li>
								<li><?php echo $_smarty_tpl->tpl_vars['settings']->value['tel_default'];?>
</li>
							</ul>
						</div>
					</div>
					<div class="col col-sm-12 col-md-5">
						<?php if ($_smarty_tpl->tpl_vars['page']->value['subtemplate']!='newsletter') {?>
							<p><strong><?php echo $_smarty_tpl->tpl_vars['translate']->value['newsletter_desc'];?>
</strong></p>
							<?php echo $_smarty_tpl->getSubTemplate ("newsletter-subscribe.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

						<?php }?>
					</div>
				</div>
				<div class="row">
					<div class="col col-sm-12">
					<div class="footer-socials">
						<?php if ($_smarty_tpl->tpl_vars['settings']->value['linkedin_url']) {?><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['linkedin_url'];?>
" target="_blank" class="icon-btn icon-linkedin"></a><?php }?>
						<?php if ($_smarty_tpl->tpl_vars['settings']->value['facebook_url']) {?><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['facebook_url'];?>
" target="_blank" class="icon-btn icon-facebook"></a><?php }?>
						<?php if ($_smarty_tpl->tpl_vars['settings']->value['twitter_url']) {?><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['twitter_url'];?>
" target="_blank" class="icon-btn icon-twitter"></a><?php }?>
						<?php if ($_smarty_tpl->tpl_vars['settings']->value['youtube_url']) {?><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['youtube_url'];?>
" target="_blank" class="icon-btn icon-youtube"></a><?php }?>
					</div>
					</div>
				</div>
			</div>
		</footer><?php }} ?>
