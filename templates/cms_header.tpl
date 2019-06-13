<!DOCTYPE html>
<html {if $lan=='ar'}dir="rtl"{/if} lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{if $title}{$title|strip_tags:false} - {/if}{$settings['site_name']}</title>

    <!-- Bootstrap -->
    <link href="{$settings['rootdir']}/assets/css/minified.css" rel="stylesheet">
    <link href="{$settings['rootdir']}/assets/css/custom.css?v=7" rel="stylesheet">    
    <link href="{$settings['rootdir']}/assets/css/print.css" rel="stylesheet" media="print">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

{* <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script> *}

    <link rel="apple-touch-icon" sizes="180x180" href="{$images['faviconapple']}">
    <link rel="icon" type="image/png" href="{$images['favicon32']}" sizes="32x32">
    <link rel="icon" type="image/png" href="{$images['favicon16']}" sizes="16x16">
 
   
  </head>

  <body class="{$action} loading" data-action="{$action}">
