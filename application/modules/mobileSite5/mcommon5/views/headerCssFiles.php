<?php if(isset($shikshaHomepage) && $shikshaHomepage!=''){ 
	$this->load->view('mcommon5/homepageWidgets/homePageFirstFoldCSS');?>
<?php }elseif(!$noJqueryMobile){ ?>
	<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion("mcommon",'nationalMobile'); ?>" >
	<?php if($boomr_pageid != 'searchV2' && $boomr_pageid!='mobilesite_LDP') { ?>
	<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion("mobile5",'nationalMobile'); ?>" >
	<?php } ?>
<?php } ?>
<?php if(!(isset($doNotLoadMobileFiles) && $doNotLoadMobileFiles=='true') && ($shikshaHomepage == '' && !isset($shikshaHomepage))){ ?>
    <link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/vendor/<?php echo getCSSWithVersion("jquery.mobile-1.4.5",'nationalMobileVendor'); ?>" >
<?php } ?>
<?php if(isset($css) && is_array($css)) {
	foreach($css as $cssFile) { ?>
	<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
    <?php }
}

if(isset($mobilecss) && is_array($mobilecss)) {
    foreach($mobilecss as $cssFile) { ?>
	<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion($cssFile,'nationalMobile'); ?>" >
    <?php }
} ?>
<?php if(isset($jqueryUIRequired) && $jqueryUIRequired == TRUE){?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<?php }?>

<?php echo includeCSSFiles($product,'nationalMobile'); ?>
<!-- External CSS files end -->
