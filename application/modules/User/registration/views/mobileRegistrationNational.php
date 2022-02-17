<?php
$formCustomData['regFormId']=$regFormId;
if(isset($callbackFunctionParams['trackingPageKeyId']))
	$formCustomData['trackingPageKeyId'] = $callbackFunctionParams['trackingPageKeyId'];
else
	$formCustomData['trackingPageKeyId']=699;

if(is_array($customFields)){
	$formCustomData['customFields'] = $customFields;
}else{
	$formCustomData['customFields'] = array();
}
if(is_array($callbackFunctionParams)){
	$formCustomData['callbackFunctionParams'] = $callbackFunctionParams;
}else{
	$formCustomData['callbackFunctionParams'] = array();
}

$formCustomData['registrationTitle'] = $registrationTitle;
$formCustomData['callbackFunction'] = $callbackFunction;
$formCustomData['registrationIdentifier'] = $registrationIdentifier;
if(isset($replyMsg)){
	$formCustomData['replyContext']= $replyContext;
}

if(isset($threadId) && $threadId != '0'){
	$formCustomData['threadId']= $threadId;
}

$regFormId = random_string('alnum', 6);
$formCustomData['regFormId'] = $regFormId;
?>

<script type="text/javascript">
  var regFormId = '<?php echo $regFormId; ?>';
</script>

<link href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion("userRegistration",'nationalMobile'); ?>" type="text/css" rel="stylesheet" />


<div class="newReg-container" id="registrationLayer">
	<div class="rgstr-heading reg-header-fix">
  		<a href="#" class="rgstr-title">Register on Shiksha</a>
    	<a href="#" class="reg-rmv flRt">&times;</a>
	</div>
	<div class="alrdy-sec">
		<a href="#" class="alrdy-regstr-bar">Already Registered? Log In Here</a>
	</div>
	<div class="join-shiksha-sec">
		<strong class="title"><?php echo $registrationTitle; ?></strong>
    	<span class="alert-span">Register to proceed. Get personalized homefeed and relevant career & college recommendations.</span>
	</div>

	<?php echo Modules::run('registration/Forms/LDB',NULL,'mobileRegistrationNational',$formCustomData); ?>

	<?php //$this->load->view('registration/common/OTP/userOtpVerification'); ?>
</div>


<script>

$(document).ready(function() {

	var fieldBinder = new UserRegistrationBinder();
	fieldBinder.regFormId = regFormId;
	fieldBinder.unbindOnloadElements();
	fieldBinder.bindOnloadElements();	

	fieldBinder.bindEvents('NewReg-field', 'blur', 'class');
	fieldBinder.bindEvents('NewReg-field', 'focus', 'class');
	fieldBinder.bindEvents('registerEmailIdField', 'blur', 'class');

});
</script>
