<?php /* Smarty version Smarty-3.1.18, created on 2016-02-24 11:58:50
         compiled from "./templates/html_header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1656303965556dab442c0ed9-40829132%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '764a99f3aa1e43594ec0851625d296bd99dcfde4' => 
    array (
      0 => './templates/html_header.tpl',
      1 => 1456311527,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1656303965556dab442c0ed9-40829132',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dab4436b885_30750482',
  'variables' => 
  array (
    'lan' => 0,
    'robots' => 0,
    'page' => 0,
    'settings' => 0,
    'og_image' => 0,
    'bodyclass' => 0,
    'bodyid' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dab4436b885_30750482')) {function content_556dab4436b885_30750482($_smarty_tpl) {?><!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"> <![endif]-->
<!--[if IE 9]><html class="no-js ie9"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="<?php echo $_smarty_tpl->tpl_vars['lan']->value;?>
"> <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="google" content="notranslate" />
	<meta name="robots" content="<?php if ($_SERVER['Local']) {?>noindex,nofollow<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['robots']->value;?>
<?php }?>">
	<meta name="description" content="<?php if ($_smarty_tpl->tpl_vars['page']->value['description']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['description'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['settings']->value['description_default'];?>
<?php }?>" />
	<meta name="apple-mobile-web-app-title" content="<?php echo $_smarty_tpl->tpl_vars['settings']->value['apple-mobile-web-app-title'];?>
">
	<meta name="viewport" content="width=device-width, initial-scale=1"/>

	<meta property="og:title" content="<?php if ($_smarty_tpl->tpl_vars['page']->value['windowtitle']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['windowtitle'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['page']->value['hide_site_name']!=true) {?> - <?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
<?php }?>" />
	<meta property="og:type" content="Website" />
	<meta property="og:url" content="<?php echo $_smarty_tpl->tpl_vars['page']->value['canonical'];?>
" />
	<meta property="og:description" content="<?php if ($_smarty_tpl->tpl_vars['page']->value['description']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['description'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['settings']->value['description_default'];?>
<?php }?>" />
	<meta property="og:image" content="<?php echo $_smarty_tpl->tpl_vars['og_image']->value;?>
" />
	<meta property="og:site_name" content="<?php echo $_smarty_tpl->tpl_vars['settings']->value['og_sitename'];?>
" />

	<meta name="twitter:card" content="summary" />
	<meta name="twitter:site" content="@<?php echo $_smarty_tpl->tpl_vars['settings']->value['twitter_name'];?>
" />
	<meta name="twitter:title" content="<?php if ($_smarty_tpl->tpl_vars['page']->value['windowtitle']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['windowtitle'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['page']->value['hide_site_name']!=true) {?> - <?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
<?php }?>" />
	<meta name="twitter:description" content="<?php if ($_smarty_tpl->tpl_vars['page']->value['description']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['description'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['settings']->value['description_default'];?>
<?php }?>" />
	<meta name="twitter:image" content="<?php echo $_smarty_tpl->tpl_vars['og_image']->value;?>
" />
	<meta name="twitter:url" content="<?php echo $_smarty_tpl->tpl_vars['page']->value['canonical'];?>
" />

	<title><?php if ($_smarty_tpl->tpl_vars['page']->value['windowtitle']) {?><?php echo $_smarty_tpl->tpl_vars['page']->value['windowtitle'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['page']->value['hide_site_name']!=true) {?> - <?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
<?php }?></title>

	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png" />
	<link rel="canonical" href="<?php echo $_smarty_tpl->tpl_vars['page']->value['canonical'];?>
" />

	<link rel="stylesheet" href="/site/css/css.php" media="all">

	<script src="//use.typekit.net/fpa7ifp.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="/site/js/modernizr-2.8.3-respond-1.4.2.min.js"></script>
	<![endif]-->

<?php if ($_SERVER['Local']!=1) {?>
	
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', '<?php echo $_smarty_tpl->tpl_vars['settings']->value['google_analytics'];?>
', 'auto');
	  ga('send', 'pageview');

	</script>
	
<?php }?>

  
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
       (function (d, w, c) {
           (w[c] = w[c] || []).push(function() {
               try {
                   w.yaCounter35611345 = new Ya.Metrika({
                       id:35611345,
                       clickmap:true,
                       trackLinks:true,
                       accurateTrackBounce:true,
                       webvisor:true,
                       trackHash:true
                   });
               } catch(e) { }
           });

           var n = d.getElementsByTagName("script")[0],
               s = d.createElement("script"),
               f = function () { n.parentNode.insertBefore(s, n); };
           s.type = "text/javascript";
           s.async = true;
           s.src = "https://mc.yandex.ru/metrika/watch.js";

           if (w.opera == "[object Opera]") {
               d.addEventListener("DOMContentLoaded", f, false);
           } else { f(); }
       })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/35611345" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
 
</head>
<body<?php if ($_smarty_tpl->tpl_vars['bodyclass']->value) {?> class="preload <?php echo $_smarty_tpl->tpl_vars['bodyclass']->value;?>
"<?php }?><?php if ($_smarty_tpl->tpl_vars['bodyid']->value) {?> id="<?php echo $_smarty_tpl->tpl_vars['bodyid']->value;?>
"<?php }?>>

<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-NV7L98"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NV7L98');</script>
<!-- End Google Tag Manager -->

	<div class="wrapper st-container">
	<!--[if lte IE 8]><div class="browser-warning">LET OP: DEZE WEBSITE WERKT NIET OPTIMAAL IN DEZE VEROUDERDE BROWSER!</div><![endif]-->
	<?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
