<?php GLOBAL $validateuser, $loggedInUserData, $checkIfLDBUser ?>
<script src="https://<?php echo JSURL; ?>/public/mobileSA/js/vendor/<?php echo getJSWithVersion('jquery-1.11.1.min','abroadMobileVendor'); ?>"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
<script>
// mobileinit gets aclled immediately after jq mobile js is loaded, we bind this just before that js is loaded
$(document).on('mobileinit', function () { 
$.mobile.ignoreContentEnabled = true;   //Disable the Auto styling by JQuery Mobile CSS
$.mobile.pushStateEnabled = false;  // Disable the AJAX Navigation across pages
$.mobile.ajaxEnabled = false;   // Disable the AJAX Navigation across pages
$.mobile.defaultHomeScroll = 0;
$.event.special.swipe.horizontalDistanceThreshold = 15;
$.event.special.swipe.durationThreshold = 3000;
});
</script>
<script src="https://<?php echo JSURL; ?>/public/mobileSA/js/vendor/<?php echo getJSWithVersion('jquery.mobile-1.4.5.min','abroadMobileVendor'); ?>"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>-->
<script src="https://<?php echo JSURL; ?>/public/mobileSA/js/vendor/<?php echo getJSWithVersion('jquery-ui.min','abroadMobileVendor'); ?>"></script>
<script async src="https://<?php echo JSURL; ?>/public/mobileSA/js/vendor/<?php echo getJSWithVersion('jquery.ui.touch-punch.min','abroadMobileVendor'); ?>"></script>
<script>
$j = $.noConflict();
var isUserLoggedIn = <?php if($validateuser!=='false') { echo 'true'; } else { echo 'false'; }  ?> ;
var isStudyAbroadPage = 1;
var isUserComplete= <?=($loggedInUserData['isLocation']>0 && $loggedInUserData['desiredCourse']!= null?'true':'false')?> 
base_url="<?php echo SHIKSHA_HOME;?>";
shiksha_site_current_url="<?php echo $shiksha_site_current_url; ?>";
shiksha_site_current_refferal="<?php echo $shiksha_site_current_refferal; ?>";
logged_in_userid="<?php echo $logged_in_userid;?>";
COOKIEDOMAIN = '<?=COOKIEDOMAIN?>';
</script>
<?php $async = "async"; if($beaconTrackData['pageIdentifier']=='rmcRegistrationPage' || $beaconTrackData['pageIdentifier']=='forgotPasswordPage'){ $async = '';}?>
<script <?php echo $async;?> src="https://<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('userRegistration'); ?>"></script>
<script <?php echo $async;?> src="https://<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('ajax-api'); ?>"></script>
<script>
<?php 
if(!($hideRightMenu == true)){ 
?>
	var head  = document.getElementsByTagName('head')[0];
	if(isUserLoggedIn == true){
		var loginCSS  = document.createElement('style');
		loginCSS.innerHTML = '#loggedin .ui-panel-inner{padding:0 !important}';
		head.appendChild(loginCSS);
	}
<?php 
}
?>
</script>