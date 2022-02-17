<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>
MMP Tracker
</title>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('header'); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('common'); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('ajax-api'); ?>"></script>
<?php
global $MMPTrackedPages;
/**
 * IF MMP Form tracking in on, load required JS/CSS
 */ 
if(is_array($MMPTrackedPages) && in_array($pageId,$MMPTrackedPages)) {
?>
	<style type="text/css">
	#ovrly
	{
		position:absolute; top:0; left:0; width:100%; height:900px; background-color: black;<?php if($_REQUEST['pixelMap'] < 2){ echo "filter: alpha(opacity=70); opacity: 0.7";} else if($_REQUEST['pixelMap'] > 0){ echo "filter: alpha(opacity=100); opacity: 1";} ?>
	}
	#clickmap div { 
		position:absolute; 
		width:20px; height:20px; 
		background:transparent url(/public/images/click.png) no-repeat center center; 
	} 
	</style>
	
	<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('Tracker'); ?>"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
		google.load('visualization', '1.0', {'packages':['corechart']});
	</script>
<?php
}
?>
</head>
<body>
<div id="LEFT"></div>
<?php
/**
 * IF MMP Form tracking in on, define a div to hold the chart
 */ 
if(is_array($MMPTrackedPages) && in_array($pageId,$MMPTrackedPages) && $_REQUEST['pixelMap']) {
	echo "<div id='ovrly'><div style='width:1000px;margin:0 auto;margin-top:30px;' id='chart_div'></div></div>";
}
?>
</body>
</html>

  