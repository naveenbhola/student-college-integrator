<div id="compareCourseBottomLayer"></div>
<div class="clearfix"></div>
</div>
<?php 
	if($hideFooter !== true) {
		echo Modules::run('studyAbroadCommon/Navigation/getMainFooter', array()); 
	}
?>
</div>
<?php
	global $clientIP;
	if(strpos($clientIP,"shiksha")!==false) {
		$this->load->view('common/ga');
	}
?>
<div id="abroadNotificationLayerDiv"></div>
<?php
    if ((empty($_COOKIE['gdpr']) && $hideFooter !== true) && $hideGDPR!==true){
?>
        <div class="cokkie-lyr">
            <div class="cokkie-box">
                <p>We use cookies to improve your experience. By continuing to browse the site, you agree to our <a href="<?=SHIKSHA_STUDYABROAD_HOME?>/shikshaHelp/ShikshaHelp/privacyPolicy" target="_blank">Privacy Policy</a> and <a href="<?=SHIKSHA_STUDYABROAD_HOME?>/shikshaHelp/ShikshaHelp/termCondition" target="_blank">Cookie Policy</a>.</p>
                <div class="tar"><a href="javascript:void(0);" onclick="gdprAgree(this);" class="cookAgr-btn">OK</a></div>
            </div>
        </div>
<?php }
global $feedbackArray;
if($feedbackArray['showFeedback'] == true && $hideFooter !== true) { ?>
	<div id = "userFeedbackLayer" class="feedback-wrapper" style="display:none"></div>
	<a href="JavaScript:void(0);" id="feedbackLink" style= "cursor:pointer;z-index:99; width:140px; padding-top:10px; padding-left:22px;color:rgb(255, 255, 255);<?=$hideFeedbackHTML == true?"display:none;":""?>" class="feedback-btn"><i class="common-sprite contact-white-icon"></i><span>Contact us</span></a>
<?php
}
$CI = & get_instance();
$CI->load->library('security');
$CI->security->setCSRFToken();
?>
<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
</body>
</html>
<?php
//for JSB9 tracking
global $serverStartTime;
$trackForPages = isset($trackForPages)?$trackForPages:false;
$endserverTime =  microtime(true);
$tempForTracking = ($endserverTime - $serverStartTime)*1000;
echo getTailTrackJs($tempForTracking,true,$trackForPages,'https://track.99acres.com/images/zero.gif');

//loading JS bundle
echo includeJSFiles($nonAsyncJSBundle,'shikshaDesktop',array('crossorigin'));
echo includeJSFiles($asyncJSBundle,'shikshaDesktop',array('crossorigin','async'));
if($oldFooterCode === true){
	echo includeJSFiles('sa-shiksha-com','shikshaDesktop',array('crossorigin'));
	echo includeJSFiles('async-sa-shiksha-com','shikshaDesktop',array('crossorigin','async'));
	if(isset($js) && is_array($js)) {
	    foreach($js as $jsFile) {
	?>
	<script type="text/javascript" <?php if(in_array($jsFile,$asyncJs)){ echo 'async';} if(in_array($jsFile,$deferJs)){ echo 'defer';} ?> crossorigin src="https://<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion($jsFile,'shikshaDesktop'); ?>"></script>
	<?php
	    }
	}
}
?>
<script>
// FB Widget related JS Code
(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));
</script>
<script>
var date = new Date();
date.setTime(date.getTime() - (1000*60*60*24*7));	//Expire the cookie
document.cookie="rmcShowSuccessPage=;expires="+date.toGMTString();
<?php if(ABROAD_USER_TRACKING == 1) { ?>
$j.ajax({
	type:"POST",
	url: "/common/studyAbroadUserTracking/trackUser",
	data : {'pageUrl':window.location.href,'referrer':document.referrer},
	success: function(data){}
	});
<?php } ?>
</script>
<?php echo TrackingCode::SCANSmartPixel($googleRemarketingParams); ?>
<script type="text/javascript">
	//Dont add any code in window.load. Instead, add in initializeCommonOnload function
	$j(window).load(function(){
		var params = {
			'product':'<?php echo $product;?>',
			'cookieDomain':'<?php echo COOKIEDOMAIN; ?>',
			'loggedInUId' : '<?php echo $validateuser[0]['userid'];?>',
			'cookieCondition':<?php echo ((is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid']))&& !empty($validateuser[0]['userid']))?1:0); ?>,
			'isCountryPage' : '<?php echo $isCountryPage;?>'
			};
		initializeCommonOnload(params);
	});
	var shikshaStudyAbroadHomeURL = "<?php echo SHIKSHA_STUDYABROAD_HOME; ?>";
	var rmcUserLimitCount = parseInt('<?=ABROADRMCLIMIT?>');
	var pageIdentifier = '<?php echo $beaconTrackData['pageIdentifier']; ?>';
	var enableDebugging = <?php echo ($this->security->xss_clean($this->input->get('enableDebugging'))==1?1:0); ?>;
<?php
	/**
	 * If user is not logged in and it's a valid marketing mailer form
	 */
	if($loggedInUserData === false && $marketingFormRegistrationData['id'])
	{
		/**
		 * Redirection URLs
		 * If specified in marketing form, use that URL
		 * If not:
		 *  In case of registration, use the URL computed for user
		 *  Else redirect to home
		 */
	?>
		var marketingFormRedirector = "<?php echo $marketingFormRegistrationData['formData']['redirectURL']; ?>";
		shikshaUserRegistration.showOneStepLayer('studyAbroad');
<?php } ?>
</script>

<?php
//Code for popup of exam update - start
if(isset($_COOKIE['examPopup']) && $_COOKIE['examPopup'] == '1'){
?>
	<script type="text/javascript">
	var popupInterval;
	var examMasterList = {};
	var pageIdentifier = '<?php echo $beaconTrackData["pageIdentifier"]; ?>';
	var checkData = {};
	$j(window).load(function() {
		checkData['pageIdentifier'] = pageIdentifier;
		popupInterval = setInterval(function(){checkIfAnotherPopupIsActive();}, 500);
	});
	</script>
<?php
}
//Code for popup of exam update - end
if(is_null($skipBSB) || $skipBSB ===false){
//Code for BSB - start
$bsbData = Modules::run('commonModule/BSB/getBSBDataAvailableForPage', $beaconTrackData['pageIdentifier']);
if(!empty($bsbData)){
?>
<script type="text/javascript">
var loggedInUser = '<?php echo (isset($validateuser[0]['userid'])) ? $validateuser[0]['userid'] : 0?>';
var bsbParams = '<?php echo json_encode($bsbData); ?>';
$j(window).load(function() {
	initiateBSB();
});
</script>
<?php
}
//Code for BSB - end
} //end if
//Code for toast msg - start
if(isset($_COOKIE['tM']) && !empty($_COOKIE['tM'])){
?>
<script>
	$j(window).load(function() {
		showToastMessage('<?php echo base64_decode($_COOKIE['tM']); ?>');
	});
</script>
<?php
	unset($_COOKIE['tM']);
	setcookie("tM", '', time()-60, "/", COOKIEDOMAIN);
}
//Code for toast msg - end
?>
