<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$settings['site_name']}{if $pagetitle} - {$pagetitle}{/if}</title>

    <!-- Bootstrap -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">    
    <link href="/assets/css/font-awesome.min.css" rel="stylesheet">    
    <link href="/assets/css/style.css" rel="stylesheet">    
    <link href="/assets/css/linkmanager.css" rel="stylesheet">        

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
	<script language="javascript">		
		function mySubmit(sUrl) {
			top.tinymce.activeEditor.windowManager.getParams().oninsert(sUrl);
			top.tinymce.activeEditor.windowManager.close();
		}		
	</script>    

    <link rel="shortcut icon" href="/assets/img/favicon.ico">

  </head>
  <body>
  
