<?php $this->load->view('common/TrackingCodes/JSErrorTrackingCode');
if($isMobile) { ?>
<link rel="stylesheet" href="/public/mobile5/css/<?php echo getCSSWithVersion("mobile5",'nationalMobile'); ?>" >
<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/vendor/<?php echo getCSSWithVersion("jquery1.3.2",'nationalMobileVendor'); ?>" >
<?php if($isSA == 'abroadpage'){ ?>
<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion("newMobile_mmp",'nationalMobile'); ?>" >
<?php } ?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("header"); ?>"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("common"); ?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
jQuery(document).on('mobileinit', function () {
	jQuery.mobile.ignoreContentEnabled = true;	//Disable the Auto styling by JQuery Mobile CSS
	jQuery.mobile.pushStateEnabled = false;	// Disable the AJAX Navigation across pages
	jQuery.mobile.ajaxEnabled = false;	// Disable the AJAX Navigation across pages
	jQuery.mobile.defaultHomeScroll = 0;
});
</script>	
<script src="/public/mobile5/js/vendor/<?php echo getJSWithVersion("jquery.mobile-1.3.2.min.full","nationalMobileVendor"); ?>"></script>
<script>
$j = jQuery.noConflict();
</script>
<?php } else { ?>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common_new"); ?>" type="text/css" rel="stylesheet" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("registration"); ?>" type="text/css" rel="stylesheet" />
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('common'); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('header'); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('ajax-api'); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('userRegistration'); ?>"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$j = jQuery.noConflict();
</script>
<?php } ?>
