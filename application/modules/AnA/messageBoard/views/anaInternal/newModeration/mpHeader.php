<!DOCTYPE html>
<html lang="en">
<head>
	<title>Shiksha Cafe Moderation Panel</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="//<?php echo CSSURL;?>/public/css/<?php echo getCSSWithVersion('bootstrap'); ?>" />
	<link rel="stylesheet" href="//<?php echo CSSURL;?>/public/css/<?php echo getCSSWithVersion('moderation_panel'); ?>" />
	<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('header'); ?>"></script>
	<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('common'); ?>"></script>
</head>
<body>
	<div class="container">
		<div class="row" style="border-bottom: 1px solid #DDDDDD; margin-bottom: 10px;"><!-- Header Code -->
			<div class="col-sm-9"><h2>Moderation Panel</h2></div>
			<div class="col-sm-3  text-right">
				<?php
				if($validateuser != 'false')
				{
				?>
				<p>Logged in as <?=$validateuser[0]['displayname']?></p>
				<a href="javascript:void(0);" onclick="SignOutUserFromModerationPanel();">Logout</a>
				<?php
				}
				?>
			</div>
		</div>