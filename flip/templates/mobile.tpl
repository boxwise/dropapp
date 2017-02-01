<!DOCTYPE html>
<html {if $lan=='ar'}dir="rtl"{/if} lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Drop Market Stock tool</title>
		
		<link href="/flip/lib/bootstrap.min.css" rel="stylesheet">    
		
		<script src="/flip/lib/jquery-3.1.1.min.js"></script>
		<script src="/flip/lib/jquery.validate.min.js"></script>
		<script src="/flip/lib/bootstrap.min.js"></script>

		<link href="/flip/lib/select2.min.css" rel="stylesheet" />
		<script src="/flip/lib/select2.min.js"></script>
		
		<script src="/flip/lib/mobile.js"></script>

		<link href="/flip/lib/mobile.css" rel="stylesheet">    

	</head>
	<body class="mobile">
		{if $include}{include file="{$include}"}{/if}
		<div id="loading"><div class="cp-spinner cp-round"></div></div>
	</body>
</html>