<!-- Load JS files -->
<script src="//<?php echo JSURL; ?>/public/js/jquery-2.1.4.min.js"></script>
<?php if(isset($jqueryUIRequired) && $jqueryUIRequired == TRUE){ ?>
	<script src="//<?php echo JSURL; ?>/public/js/jquery-ui-1.11.4.min.js"></script>
<?php }?>
<?php if(isset($changeJQueryRef) && $changeJQueryRef=='true'){ ?>
	<script>$j = $.noConflict();</script>
<?php } ?>
<?php if(!(isset($doNotLoadMobileFiles) && $doNotLoadMobileFiles=='true')){ ?>
	
		<script type="application/ld+json">
		{"@context" : "http://schema.org", "@type" : "Organization", "url" : "https://www.shiksha.com", "logo" : "https://www.shiksha.com/public/images/nshik_ShikshaLogo1.gif", "name" : "Shiksha.com", "sameAs" : ["https://www.facebook.com/shikshacafe", "https://twitter.com/ShikshaDotCom", "https://en.wikipedia.org/wiki/Shiksha.com", "https://plus.google.com/+shiksha"]}
		</script>
	
	<!-- Needed in jquery.mobile-1.4.5.min.js -->
	<?php if(isset($is_profile_page) && $is_profile_page == true && 0) { ?>
		<script>
			$(document).on('mobileinit', function () {
			    $.mobile.ignoreContentEnabled = true; //Disable the Auto styling by JQuery Mobile CSS
		    	$.mobile.pushStateEnabled = false;  // Disable the AJAX Navigation across pages
		        $.mobile.defaultHomeScroll = 0;
		        $.event.special.swipe.horizontalDistanceThreshold = (window.devicePixelRatio >= 2) ? 15 : 30;
			});
		</script>
	<?php }else{ ?>
		<script>
            $(document).on('mobileinit', function () {
			    $.mobile.ignoreContentEnabled = true; //Disable the Auto styling by JQuery Mobile CSS
			    $.mobile.pushStateEnabled = false;  // Disable the AJAX Navigation across pages
		      	$.mobile.ajaxEnabled = false; // Disable the AJAX Navigation across pages
			    $.mobile.defaultHomeScroll = 0;
			    $.event.special.swipe.horizontalDistanceThreshold = (window.devicePixelRatio >= 2) ? 15 : 30;
			});
    	</script> 
    <?php } ?>

    <?php if(isset($loadSmallJQuery) && $loadSmallJQuery){ // Is this being used? ?>
    <script src="//<?php echo JSURL; ?>/public/mobile5/js/jquery.mobile-1.4.5.small.js"></script>
    <?php }else{ ?>
    <script src="//<?php echo JSURL; ?>/public/mobile5/js/jquery.mobile-1.4.5.min.js"></script>
    <?php } ?>
    
    <?php if($pageName=='collegeReview'){ ?>
        <script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("collegeReview","nationalMobile"); ?>"></script>
	<?php }else{ ?> 
		<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("main","nationalMobile"); ?>"></script>
	<?php } 
	if($pageName == 'collegeReviews'){ 
		?>
		<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("collegeReviewPageMobile","nationalMobile"); ?>"></script>
	<?php }

	if($pageName=='NaukriTool'){ ?>
		<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("naukriToolNM","nationalMobile"); ?>"></script>
	<?php } 
	if($pageName=='CampusConnectHomepage' || $pageName=='CampusConnectIntermediatePage'){ ?>
		<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("campusConnect","nationalMobile"); ?>"></script>
	<?php }
	if($pageName=='mentorShipHome'){ ?>
		<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("mentor","nationalMobile"); ?>"></script>
	<?php } ?>
<?php } ?>

<?php if(isset($js) && is_array($js)) {
	foreach($js as $jsFile) { ?>
		<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion($jsFile); ?>"></script>
    <?php }
} ?>
            
<?php if(isset($jsMobile) && is_array($jsMobile)) {
    foreach($jsMobile as $jsFile) { ?>
	  	<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion($jsFile,'nationalMobile'); ?>"></script>
  	<?php }
} ?>
