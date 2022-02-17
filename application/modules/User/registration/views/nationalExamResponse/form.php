<?php

if($customFormData['showOTPOnly'] == 'yes'){
	$this->load->view('registration/nationalResponse/showOTPOnly');
}else{
		$formSubmit = '/registration/Registration/register';
		$firstScreenButtonText = 'Next';

		if(!empty($customFormData['customFields']['isUserLoggedIn']) && $customFormData['customFields']['isUserLoggedIn'] == 'yes'){ 
			$formSubmit = '/registration/Registration/updateUser';
			$firstScreenButtonText = 'Continue';
		}
	?>

	<?php
	    $CI = & get_instance();
	    $CI->load->library('security');
	    $CI->security->setCSRFToken();
	?>

	<div id='registrationFormWrapper_<?php echo $regFormId;?>'>
		<form action="<?php echo $formSubmit; ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post" regFormId="<?php echo $regFormId; ?>">
			<div id="regSliders" style="width:100%;">
				<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
				<!-- Response First screen Starts-->

				<!-- Response First screen Ends-->

				<!-- Response Second screen Starts-->
				<div id="secondLayer_<?php echo$regFormId; ?>" class="slide layer-scnd">
					<div id="sndscreenScroll_<?php echo $regFormId; ?>" class="acc-psnlInfo ">
						<p class="field-title">We will also send you insights, recommendations and updates.</p>
						<div class="regtbs mt2 clearfix">
	 						<div class="overview">

								<?php $this->load->view('registration/fields/LDB/examResponse/examCourseGroup'); ?>							
								<div id="mappedFields_<?php echo $regFormId; ?>" class=""></div>
								<?php $this->load->view('registration/fields/LDB/prefYear'); ?>
								<?php $this->load->view('registration/fields/LDB/accountDetails'); ?>
								<?php $this->load->view('registration/fields/LDB/personalInfo'); ?>
								<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
								<input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
								<input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo $httpReferer ? $httpReferer : $_SERVER['HTTP_REFERER']; ?>' />
								<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value='<?php echo empty($trackingKeyId)?DEFAULT_TRACKING_KEY_EXAM_DESKTOP:$trackingKeyId; ?>' />
								<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
								<?php if(!empty($responseAction)){ ?>
									<input type="hidden" name="action_type" value="<?php echo $responseAction; ?>" id="response_action_<?php echo $regFormId; ?>">
								<?php } ?>
							</div>
						</div>
					</div>
					
					<a href="javascript:void(0);" class="reg-btn sup-btn stp2 resp-btn" tabindex="2" regformid="<?php echo $regFormId; ?>" style="right:898px !important;">
						<?php if(!empty($submitButtonText)){ echo $submitButtonText; }else{ echo 'Signup'; }?>
					</a>
				</div>
				<!-- Response Second screen Ends-->

				<!-- Response Third screen Starts-->
				<div id="thirdLayer_<?php echo$regFormId; ?>" class="slide" style="paddding:0 27px;">
					<?php $this->load->view('registration/common/OTP/Recat/desktopOTP'); ?>
				</div>
				<!-- Response Third screen Ends-->

				<!-- Response Fourth screen Starts-->
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
		userRegistrationRequest['<?php echo $regFormId; ?>'].isResponseForm = true;
		<?php if(!empty($customFormData['customFields']['residenceCity']['value']) && $customFormData['customFields']['residenceCity']['value'] > 1){ ?>
			registrationForm.updateFieldLabel('residenceCityLocality', '<?php echo $regFormId; ?>');
		<?php } ?>
		$j('.loginScreen').attr('regformid', '<?php echo $regFormId; ?>');
		initiateSimpleSlider($j);
		$j("#regSliders").simpleSlider({ speed: 500, regFormId:'<?php echo $regFormId; ?>' });

		<?php 
			if(!empty($customFormData['customFields']['isdCode']['value']) && $customFormData['customFields']['isdCode']['value'] != '91-2'){ ?>
				$j('#isdCode_<?php echo $regFormId; ?>').change();
			<?php }
			if(!empty($customFormData['customFields']['residenceCityLocality']['value'])){ ?>
			userRegistrationRequest['<?php echo $regFormId; ?>'].preSelectResidentCity();
		<?php } ?>

		<?php if(!empty($customFormData['customFields']['residenceCityLocality']['value']) && $customFormData['customFields']['residenceCityLocality']['value'] > 1){ ?>
			if($j('#residenceCityLocality_<?php echo $regFormId; ?>').val() == ''){
				$j('#residenceCityLocality_block_<?php echo $regFormId; ?>').removeClass('ih disabled filled').show();
			}
		<?php } ?>

		<?php if(!empty($customFormData['customFields']['isUserLoggedIn']) && empty($customFormData['customFields']['lastName']['value'])){ ?>
			registrationForm.handleLastNameCheck('<?php echo $regFormId; ?>');
		<?php } ?>
	</script>
<?php } ?>
