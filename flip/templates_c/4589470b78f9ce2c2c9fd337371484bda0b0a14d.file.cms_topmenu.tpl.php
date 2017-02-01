<?php /* Smarty version Smarty-3.1.18, created on 2015-08-14 17:17:48
         compiled from "/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_topmenu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:127435673556dace6a6e9d6-34556480%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4589470b78f9ce2c2c9fd337371484bda0b0a14d' => 
    array (
      0 => '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/templates/cms_topmenu.tpl',
      1 => 1439565357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '127435673556dace6a6e9d6-34556480',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dace6acf8e3_80794698',
  'variables' => 
  array (
    'settings' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dace6acf8e3_80794698')) {function content_556dace6acf8e3_80794698($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/vhosts/global.zinnebeeld.nl/httpdocs/50-back/smarty/libs/plugins/modifier.replace.php';
?><header class="header-top">
	<div class="header-top-inner container-fluid">
 		<div class="pull-left">
			<a href="#" class="menu-btn visible-xs">&#9776;</a>
			<a href="/flip" class="brand"><?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
</a>
 		</div>
		<ul class="nav pull-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user visible-xs"></i><span class="hidden-xs"><?php echo $_SESSION['user']['naam'];?>
 <?php if ($_SESSION['user2']) {?>(<?php echo $_SESSION['user2']['naam'];?>
)<?php }?></span><b class="caret"></b></a>
				<ul class="dropdown-menu dropdown-menu-right">
					<li><a href="?action=cms_profile"><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_menu_settings'];?>
</a></li>
<?php if ($_SESSION['user2']) {?><li><a href="?action=exitloginas"><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['translate']->value['cms_menu_exitloginas'],'%user%',$_SESSION['user2']['naam']);?>
</a></li><?php }?>
					<li><a href="?action=logout"><?php echo $_smarty_tpl->tpl_vars['translate']->value['cms_menu_logout'];?>
</a></li>
				</ul>
			</li>
		</ul>
 		<ul id="usersonline" class="pull-right hidden-xs"></ul>
	</div>
</header>
<?php }} ?>
