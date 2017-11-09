<!DOCTYPE html>
<html {if $lan=='ar'}dir="rtl"{/if} lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{if $title}{$title|strip_tags:false} - {/if}{$settings['site_name']}</title>

<!-- Bootstrap -->
<link href="{$settings['rootdir']}/assets/css/bicycle.css" rel="stylesheet">
</head>

<body>

<div class="card">
	<div class="title"><span class="blue">Drop In The Ocean</span> Bicycle Certificate</div>
	<div class="picture rotate{$data['rotate']}"><img src="/images/people/{$data['id']}.jpg"></div>
	<div class="field name">{$data['firstname']} {$data['lastname']}</div>
	<div class="label name">name</div>
	<div class="field container">{$data['container']}&nbsp;</div>
	<div class="label container">container</div>
	<div class="field dob">{$data['date_of_birth']|date_format:"%e %B %Y"}&nbsp;</div>
	<div class="label dob">date of birth</div>
	<div class="field phone">{$data['phone']}&nbsp;</div>
	<div class="label phone">phone</div>
	<div class="field issued">{$smarty.now|date_format:"%e %B %Y %H:%M"}</div>
	<div class="label issued">issued</div>
</div>
<div class="card card-back">
	<div class="rules">{$translate['bicycle-rules']}</div>
	<div class="emergency">In case of emergency call <strong>112</strong><br>Drop In The Ocean <strong>+30&nbsp;694&nbsp;6899518</strong></div>
</div>
</body>
</html>