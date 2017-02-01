<?php /* Smarty version Smarty-3.1.18, created on 2017-01-19 18:32:15
         compiled from "/home/drapeton/market/50-back/templates/cms_topmenu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15473977335880ea0f622075-52966227%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0ae406bea129c0c3ddfae6554a1c73fe7442cf4e' => 
    array (
      0 => '/home/drapeton/market/50-back/templates/cms_topmenu.tpl',
      1 => 1484776982,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15473977335880ea0f622075-52966227',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'translate' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5880ea0f637d22_19664853',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5880ea0f637d22_19664853')) {function content_5880ea0f637d22_19664853($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/home/drapeton/market/50-back/smarty/libs/plugins/modifier.replace.php';
?><header class="header-top">
	<div class="header-top-inner container-fluid">
 		<div class="pull-left">
			<a href="#" class="menu-btn visible-xs">&#9776;</a>
			<?php if ($_SESSION['user']['usertype']=='family') {?>
				<a href="/flip/?action=status" class="brand"><?php echo $_smarty_tpl->tpl_vars['translate']->value['site_name'];?>
</a>
			<?php } else { ?>
				<a href="/flip" class="brand"><?php echo $_smarty_tpl->tpl_vars['translate']->value['site_name'];?>
</a>
			<?php }?>
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
