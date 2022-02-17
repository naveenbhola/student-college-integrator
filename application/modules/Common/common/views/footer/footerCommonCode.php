<?php
$this->benchmark->mark('Footer_New_Common_JSB9_Tracking_start');
global $serverStartTime;
$trackForPages = isset($trackForPages)?$trackForPages:false;
$endserverTime =  microtime(true);
$tempForTracking = ($endserverTime - $serverStartTime)*1000;
$reset_password = trim(strip_tags($_REQUEST['resetpwd']));
$usergroup = trim(strip_tags($_REQUEST['usrgrp']));

if($reset_password == 1) {
	$reset_password_token = trim(strip_tags($_REQUEST['uname']));
	$reset_password_token = preg_replace("/[^a-zA-Z0-9]/", "", $reset_password_token);
	$actual_reset_password_token = strlen($_REQUEST['uname']);
	$new_reset_password_token = strlen($reset_password_token);

	$reset_usremail = trim(strip_tags($_REQUEST['usremail']));	
	$isValidEmail =	validateEmailMobile('email', base64_decode($reset_usremail));
	
	if(!$isValidEmail || $actual_reset_password_token != $new_reset_password_token) {
		$reset_password_token = '';
		$reset_usremail = '';
		$reset_password = '';
	}
} else {
	$reset_password_token = '';
	$reset_usremail = '';
	$reset_password = '';
}

    echo getTailTrackJs($tempForTracking,true,$trackForPages,'https://track.99acres.com/images/zero.gif');

$this->benchmark->mark('Footer_New_Common_JSB9_Tracking_end');
?>
<script>	
var shikshaProduct = '<?php echo $product; ?>';
</script>
<?php 
if($product == 'home'){
	$this->benchmark->mark('Footer_New_Common_Site_Links_start');
	$this->load->view('common/footer/sitelinksSearchBox');
	$this->benchmark->mark('Footer_New_Common_Site_Links_end');
}

if($product != 'collegeReviewHomepage'){
	$this->benchmark->mark('Footer_New_Common_unifiedRegistrationLazyLoad_start');
	
	//To load js files in parallel(ajax-api,userRegistration,user,cityList,tooltip,UnifiedRegistration)
	$this->load->view('common/footer/unifiedRegistrationLazyLoad'); 
	
	$this->benchmark->mark('Footer_New_Common_unifiedRegistrationLazyLoad_end');
}

$this->benchmark->mark('Footer_New_Common_JS_start');
$this->load->view('common/footer/footerJSFiles');
$this->benchmark->mark('Footer_New_Common_JS_end');

$this->benchmark->mark('Footer_New_Common_CSS_start');
$this->load->view('common/footer/footerCSSFiles');
$this->benchmark->mark('Footer_New_Common_CSS_end');

$this->benchmark->mark('Footer_New_Common_Tracking_Image_start');
echo Modules::run('tracking/generateTrackingImage',$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],$_SERVER['HTTP_REFERER']); 
$this->benchmark->mark('Footer_New_Common_Tracking_Image_end');

?>
<script>
	var reset_password_token = '<?php echo $reset_password_token;?>';
	var reset_usremail = '<?php echo $reset_usremail;?>';
	var reset_password = '<?php echo $reset_password;?>';
	var usergroup = '<?php echo $usergroup;?>';
	
	//var registration_context = '';
	var user_logged_in_pref_data = "";
	var userInfo = null;
	var firstTimePageLoad = true;
</script>
<?php 

$this->benchmark->mark('Footer_New_Common_On_Ready_Calls_start');
// common document.ready,bind autosuggestor events,activate gnb mouse over, window.load
$this->load->view('common/footer/footerOnReadyCalls'); 
$this->benchmark->mark('Footer_New_Common_On_Ready_Calls_end');

$this->benchmark->mark('Footer_New_Common_Third_Party_start');
$this->load->view('common/footer/footerThirdPartyAndTrackingScript');
$this->benchmark->mark('Footer_New_Common_Third_Party_end');
?>

