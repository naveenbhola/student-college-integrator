<?php
	$destination_url = $mmp_details['destination_url'];
	global $mmpFormsForCaching;
	global $validDomains;
	global $invalidEmailDomains; 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<base href="<?php echo base_url(); ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name = "format-detection" content = "telephone=no" />
	<meta name = "format-detection" content = "address=no" />
	<meta name="description" content="Registration"/>
	<meta name="keywords" content="Registration"/>
	<meta name="copyright" content="2013 Shiksha.com" />
	<meta name="content-language" content="EN" />
	<meta name="author" content="www.Shiksha.com" />
	<meta name="resource-type" content="document" />
	<meta name="distribution" content="GLOBAL" />
	<meta name="revisit-after" content="1 day" />
	<meta name="rating" content="general" />
	<meta name="pragma" content="no-cache" />
	<meta name="classification" content="Education and Career: education portal, college university directory, career forum" />
	<meta name="robots" content="ALL" />
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320"/>
	<meta http-equiv="cleartype" content="on">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta http-equiv="x-dns-prefetch-control" content="off">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/public/mobile/images/touch/apple-touch-icon-144x144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/public/mobile/images/touch/apple-touch-icon-114x114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/public/mobile/images/apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="/public/mobile/images/apple-touch-icon-57x57-precomposed.png">
	<link rel="shortcut icon" href="/public/mobile/images/apple-touch-icon.png">
	<link rel="dns-prefetch" href="//ask.shiksha.com"> 
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans">
	<title>Shiksha.com - India's no. 1 college selection website: Register</title>
	<script src="//<?php echo JSURL; ?>/public/js/jquery-2.1.4.min.js"></script>
	<script type="text/javascript">
	var mmpFormsForCaching = [<?php echo implode(',',  $mmpFormsForCaching ) ?>];
	$(document).on('mobileinit', function () {
                            $.mobile.ignoreContentEnabled = true; //Disable the Auto styling by JQuery Mobile CSS
                            $.mobile.pushStateEnabled = false;  // Disable the AJAX Navigation across pages
                        $.mobile.ajaxEnabled = false; // Disable the AJAX Navigation across pages
                            $.mobile.defaultHomeScroll = 0;
                            $.event.special.swipe.horizontalDistanceThreshold = (window.devicePixelRatio >= 2) ? 15 : 30;
                        });
	var validDomains = <?php echo json_encode($validDomains); ?>;
	var invalidDomains = <?php echo json_encode($invalidEmailDomains); ?>;
</script>
	<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/vendor/<?php echo getCSSWithVersion('jquery.mobile-1.4.5','nationalMobileVendor'); ?>" >
	<script src="//<?php echo JSURL; ?>/public/mobile5/js/vendor/<?php echo getJSWithVersion("jquery.mobile-1.4.5.min","nationalMobileVendor"); ?>"></script>
	<?php global $user_logged_in; ?>

	<?php $this->load->view('mcommon5/homepageWidgets/homePageFirstFoldCSS');?>
    <link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion('mcommon','nationalMobile'); ?>">
	<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion('userRegistrationMobile','nationalMobile'); ?>">
	<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion('search-widget','nationalMobile'); ?>">

	

	<script src="/public/mobile5/js/<?php echo getJSWithVersion('userRegistrationFormMobile','nationalMobile'); ?>"></script>
</head>
<style>
.sltNone {
    user-select: none;
    -moz-user-select: none;
    -khtml-user-select: none;
    -webkit-user-select: none;
    -o-user-select: none;
}

.reg-pp > .head > .head-title span.whySgnup i.irt-icon {
	background-image: url("<?=IMGURL_SECURE?>/public/mobile5/images/new-header-m.svg");
	background-position: -361px -50px;
	left: auto;
	width: 11px;
	height: 14px;
    position: absolute;
    top: 2px;
    right: -11px;
    opacity: 0.8;
}
</style>
<body>
<?php
  $this->load->view('common/googleCommon');
  $this->load->view('multipleMarketingPage/marketingPageMobileContent');
?>
</body>
</html>
<!-- /var/www/html/shiksha/application/modules/User/registration/views/newmobilemmp/national/marketingPageMobileContent.php -->
