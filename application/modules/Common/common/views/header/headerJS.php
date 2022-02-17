<?php if(in_array($product, array('SearchV2', 'ranking', 'anaDesktopV2', 'Category', 'AllCoursesPage', 'ArticlesD')) || $widgetForPage == 'HOMEPAGE_DESKTOP') { 
			if(!in_array($product, array('ArticlesD', 'home'))) { 
	?>
	<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('shikshaHeader'); ?>"></script>
<?php 		}
	}else {?>

<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('header'); ?>"></script>
<!-- <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('cityList'); ?>"></script>
 -->
 <?php } ?>
