<!DOCTYPE html>
<html {if $lan=='ar'}dir="rtl"{/if} lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Drop Market Stock tool</title>

    <!-- Bootstrap -->
    <link href="/global/css/css.php" rel="stylesheet">
    <link href="/flip/lib/custom.css" rel="stylesheet">    
	<link href="/flip/lib/print.css" rel="stylesheet" media="print">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

	<link rel="apple-touch-icon" sizes="180x180" href="/flip/assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/flip/assets/images/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/flip/assets/images/favicon-16x16.png" sizes="16x16">
    
  </head>

	<head>
		<style>
			body {
				font-family: Arial, sans-serif;
				font-size:24px;
				margin:10px;
				padding:0;
				}
			button {
				font-family: Arial, sans-serif;
				font-size:24px;
				-moz-border-radius: 10px;
				-webkit-border-radius: 10px;
				border-radius: 10px;
				border:none;
				background-color: #eee;
				width:100%;
				padding:10px;
				margin-bottom:20px;
			}
			a {
				text-decoration: none;
				color:#2383ac;
			}
		</style>
	</head>
	<body>
		{if $move}
			{if $box['id']}
				<p>Box <strong>{$box['box_id']}</strong> contains {$box['items']}x <strong>{$box['product']}</strong><br />is moved from <strong>{$box['location']}</strong> to <strong>{$newlocation['label']}</p>
			{else}
				<p>This box is not found in the Drop Market administration.<br /><a href="">Add box to stock</a></p>
			{/if}
		{else}
			{if $box['id']}
			<p>Box <strong>{$box['box_id']}</strong> contains {$box['items']}x <strong>{$box['product']}</strong><br />Move this box from <strong>{$box['location']}</strong> to:</p>
				{foreach $locations as $value=>$label}
				<button><a href="scan.php?move={$box['id']}&location={$value}">{$label}</a></button><br />
				{/foreach}
			{else}
				<p>This box is not found in the Drop Market administration.<br /><a href="">Add box to stock</a></p>
			{/if}
		{/if}
	</body>
</html>