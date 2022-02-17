<?php 
	$formSubmit = '/registration/Registration/register';
	$firstScreenButtonText = 'Next';

	if(!empty($customFormData['customFields']['isUserLoggedIn']) && $customFormData['customFields']['isUserLoggedIn'] == 'yes'){ 
		$formSubmit = '/registration/Registration/updateUser';
		$firstScreenButtonText = 'Continue';
	}
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
   	$customFormFields = $customFormData['customFields']; 
?>

	<div id='registrationFormWrapper_<?php echo $regFormId;?>'>
		<form action="<?php echo $formSubmit; ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post" regFormId="<?php echo $regFormId; ?>">
			<div id="regSliders" style="width:100%;">
			<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
			
			<div id="thirdLayer_<?php echo $regFormId; ?>" regformid="<?php echo $regFormId; ?>" class="slide" style="paddding:0 27px;">								
				<input type="hidden" id="email_<?php echo $regFormId; ?>"  name="email"  value="<?php if(!empty($customFormFields['email']['value'])){ echo $customFormFields['email']['value']; } ?>">
				<input type="hidden" name="firstName" id="firstName_<?php echo $regFormId; ?>" value="<?php if(!empty($customFormFields['firstName']['value'])){ echo $customFormFields['firstName']['value']; } ?>" >
				<input type="hidden" name="lastName" id="lastName_<?php echo $regFormId; ?>"  value="<?php if(!empty($customFormFields['lastName']['value'])){ echo $customFormFields['lastName']['value']; } ?>" >
				<input type="hidden" name="isdCode" id="isdCode_<?php echo $regFormId; ?>"  value="<?php if(!empty($customFormFields['isdCode']['value'])){ echo $customFormFields['isdCode']['value']; } ?>" >
				<input type="hidden" name="mobile" id="mobile_<?php echo $regFormId; ?>"  value="<?php if(!empty($customFormFields['mobile']['value'])){ echo $customFormFields['mobile']['value']; } ?>">
				<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
				<input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
				<input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo $httpReferer ? $httpReferer : $_SERVER['HTTP_REFERER']; ?>' />
				<input type='hidden' id='isMR' name='isMR' value='YES' />
				<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value='<?php echo empty($trackingKeyId)?DEFAULT_TRACKING_KEY_DESKTOP:$trackingKeyId; ?>' />
				<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
				<input type="hidden" id='showOTPLayerOnly_<?php echo $regFormId; ?>' name='userVerification' value='yes' />
				<?php if(!empty($responseAction)){ ?>
					<input type="hidden" name="action_type" value="<?php echo $responseAction; ?>" id="response_action_<?php echo $regFormId; ?>">
				<?php } ?>
				<?php $this->load->view('registration/common/OTP/Recat/desktopOTP'); ?>
			</div>

			<div id="fourthLayer_<?php echo$regFormId; ?>" class="slide" style="paddding:0 27px;">
				<?php $this->load->view('registration/common/OTP/Recat/updateMobile'); ?>
			</div>

			</div>
		</form>
		<?php $this->load->view('registration/common/jsObjectInitialization'); ?>
	</div>

	<a href="javascript:void(0);" id="slideLeft_<?php echo $regFormId; ?>" class="ih"> Left </a>
	<a href="javascript:void(0);" id="slideRight_<?php echo $regFormId; ?>" class="ih"> Right </a>
	<a href="javascript:void(0);" id="instleft1_<?php echo $regFormId; ?>" class="ih"> Right </a>
	<a href="javascript:void(0);" id="instleft2_<?php echo $regFormId; ?>" class="ih"> Right </a>

	<script>
		<?php if(!empty($customFormData['clientCourseId'])) { ?>
			userRegistrationRequest['<?php echo $regFormId; ?>'].isResponseForm = true;
		<?php } else { ?>
			userRegistrationRequest['<?php echo $regFormId; ?>'].isResponseForm     = false;
			userRegistrationRequest['<?php echo $regFormId; ?>'].registrationStatus = registrationForm.registrationStatus;
		<?php } ?>
		$j('.loginScreen').attr('regformid', '<?php echo $regFormId; ?>');
		initiateSimpleSlider($j);
		$j("#regSliders").simpleSlider({ speed: 500, regFormId:'<?php echo $regFormId; ?>' });
	</script>
