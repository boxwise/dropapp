<?php /* Smarty version Smarty-3.1.18, created on 2014-07-08 14:13:25
         compiled from "/Volumes/Drobo/Webserver/global/50-back/templates/google_analytics.tpl" */ ?>
<?php /*%%SmartyHeaderCode:135228867853bbe065af7a84-08228948%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9a44ad6f548dbacb2208853bec91dd3d0108bd01' => 
    array (
      0 => '/Volumes/Drobo/Webserver/global/50-back/templates/google_analytics.tpl',
      1 => 1404736059,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '135228867853bbe065af7a84-08228948',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'settings' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_53bbe065b132f5_63655601',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53bbe065b132f5_63655601')) {function content_53bbe065b132f5_63655601($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['settings']->value['google_analytics']&&!$_SERVER['Local']) {?>
<script>
  //Tracking code for universal analytics 
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '{$settings['google_analytics']}', '{$settings['google_analytics_domain']}');
  ga('set', 'anonymizeIp', true);
  ga('send', 'pageview');

</script>
<?php }?>   
<?php }} ?>
