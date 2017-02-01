<?php /* Smarty version Smarty-3.1.18, created on 2014-07-08 14:13:25
         compiled from "./templates/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:126154635153bbe065a9abb1-23818369%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '97c13ae6868bbc459509c9f1b968154acd23eecc' => 
    array (
      0 => './templates/header.tpl',
      1 => 1404747833,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '126154635153bbe065a9abb1-23818369',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'page' => 0,
    'settings' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_53bbe065aecfa6_45436465',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53bbe065aecfa6_45436465')) {function content_53bbe065aecfa6_45436465($_smarty_tpl) {?><!DOCTYPE html>
<!-- Website by zinnebeeld (www.zinnebeeld.nl) -->
<html lang="nl">
<head>

	<meta charset="utf-8">    
	<meta name="robots" content="<?php if ($_SERVER['Local']) {?>noindex,nofollow<?php } else { ?>index,follow<?php }?>">
	<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['page']->value['description'];?>
" />
	<meta name="apple-mobile-web-app-title" content="<?php echo $_smarty_tpl->tpl_vars['settings']->value['apple-mobile-web-app-title'];?>
">
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	
	<meta property="og:title" content="" />
	<meta property="og:type" content="Website" />
	<meta property="og:url" content="" />  
	<meta property="og:description" content="" />
	<meta property="og:image" content="" />
	<meta property="og:site_name" content="" />
	
	<title><?php if ($_smarty_tpl->tpl_vars['page']->value['windowtitle']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['windowtitle'];?>
 - <?php }?><?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
</title>
	
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png" /> 
	<link rel="canonical" href="<?php echo $_smarty_tpl->tpl_vars['page']->value['canonical'];?>
" />   
	
	<link rel="stylesheet" type="text/css" href="/site/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="/site/css/style.css" />
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/site/js/validation/jquery.validate.min.js"></script>
    <script src="/site/js/validation/messages_en.js"></script>	
	<script src="/site/js/magic.js"></script>		
	
	<?php echo $_smarty_tpl->getSubTemplate ("google_analytics.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	
</head>

<body>
	<a id="top"></a>
	<header>
		<div class="header-logo">transit Social innovation</div>			
		<div class="header-side"></div>
	</header>
	<nav>
		<div class="container">
			<ul>
				<li><a href="#">Home</a></li>
				<li><a href="#">About TRANSIT</a></li>
				<li><a href="#">People</a></li>
				<li><a href="#">Case studies</a></li>
				<li><a href="#">Event calendar</a></li>
			</ul>
		</div>
	</nav>
	
<?php }} ?>
