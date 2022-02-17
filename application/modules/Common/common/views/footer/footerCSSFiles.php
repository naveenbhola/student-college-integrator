<?php 
$cssUrl = getCssUrl();

foreach($cssFooter as $cssFile) { ?>
<link href="//<?php echo $cssUrl; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
<?php
}?>
<script type="text/javascript">
<?php 	
$cssFilePlugins[] = 'socialSharingInner';
if(!empty($cssFilePlugins)) {
	foreach($cssFilePlugins as $fileName) { ?> 
		lazyLoadCss('//<?php echo $cssUrl; ?>/public/css/<?php echo getCSSWithVersion($fileName); ?>');
<?php 	
	} 
}
?>
</script>
<?php if(!$lazyLoadUserRegistrationCss) { ?>
<link href="//<?php echo $cssUrl; ?>/public/css/<?php echo getCSSWithVersion('userRegistrationDesktop'); ?>" type="text/css" rel="stylesheet" />
<?php } ?>