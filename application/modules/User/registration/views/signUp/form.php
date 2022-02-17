<?php

if($customFormData['showOTPOnly'] == 'yes'){
	$this->load->view('registration/nationalResponse/showOTPOnly');
} else {
	$formSubmit = '/registration/Registration/register';
	$firstScreenButtonText = 'Next';
	if(!empty($customFormData['customFields']['isUserLoggedIn']) && $customFormData['customFields']['isUserLoggedIn'] == 'yes'){
		$firstScreenButtonText = 'Continue';
		$formSubmit = '/registration/Registration/updateUser';
	}
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>
	<div id='registrationFormWrapper_<?php echo $regFormId;?>'>
		<form action="<?php echo $formSubmit; ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post" regFormId="<?php echo $regFormId; ?>">

		    <input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
			<div id="regSliders" style="width:100%; height:485px;">
			<div id="firstLayer_<?php echo $regFormId; ?>" class="slide" style="paddding:0 27px;">
				<?php $this->load->view('registration/fields/LDB/basicInterest'); ?>
				<a href="javascript:void(0);" class="reg-btn sup-btn stp1" style="left:38px;" type="submit" regformid="<?php echo $regFormId; ?>" tabindex="2"><?php echo $firstScreenButtonText; ?></a>
			</div>

			<div id="secondLayer_<?php echo$regFormId; ?>" class="slide ssScroll">
				<div class=""> <!-- changing class for a bug. if any issue refer to story LDB-6931 -->
					<div class="regtbs clearfix">
	<!-- 					<div class="scrollbar1">
							<div style="height: 0px;z-index:2" class="scrollbar sbph ih">
								<div style="height: 0px;" class="track">
									<div style="top: 0px; height: 44.6391px;" class="thumb"></div>
								</div>
							</div> -->
							<div class="signupStep2 showScroll">
								<div class="overview" style="top: 0px; width:100%;">

									<?php if(empty($customFormData['customFields']['isUserLoggedIn']) && $customFormData['customFields']['isUserLoggedIn'] != 'yes') { ?>
										<div><p class="field-title">To continue, create your account:</p></div>
									<?php } ?>
									<?php $this->load->view('registration/fields/LDB/prefYear'); ?>
									<?php $this->load->view('registration/fields/LDB/accountDetails'); ?>
									<?php $this->load->view('registration/fields/LDB/personalInfo'); ?>
									<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
									<input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
									<input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo $httpReferer ? $httpReferer : $_SERVER['HTTP_REFERER']; ?>' />
									<input type='hidden' id='isMR' name='isMR' value='YES' />
									<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value="<?php echo empty($trackingKeyId)? DEFAULT_TRACKING_KEY_DESKTOP:$trackingKeyId; ?>" />
									<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
									<?php if(!empty($customFormData['customFields']['mmpFormId'])){ ?>
										<input type="hidden" id='mmpFormId_<?php echo $regFormId; ?>' name='mmpFormId' value='<?php echo $customFormData['customFields']['mmpFormId']; ?>' />
									<?php } ?>

								</div>
						</div>
	<!-- 						</div> -->
					</div>

				</div>

				<div>
					<a href="javascript:void(0);" class="reg-btn sup-btn stp2" tabindex="2" regformid="<?php echo $regFormId; ?>" style="right:898px !important;"><?php if(!empty($submitButtonText)){ echo $submitButtonText; }else{ echo 'Sign Up'; }?></a>
				</div>
			</div>

			<div id="thirdLayer_<?php echo$regFormId; ?>" class="slide" style="paddding:0 27px;">
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
		<?php if(!empty($customFormData['customFields']['stream']['value'])){ ?>
			userRegistrationRequest['<?php echo $regFormId; ?>'].getFormByStream($j('#stream_<?php echo $regFormId; ?>'));
		<?php }else{ ?>
					registrationForm.isThreeStepForm = true;
	                registrationForm.isFirstScreenTracked = true;
	                //userRegistrationRequest['<?php echo $regFormId; ?>'].trackRegistrationEvents('First_Page_interest');
	                <?php if($customFormData['customFields']['nonInteractive'] == true) { ?>
	                	userRegistrationRequest['<?php echo $regFormId; ?>'].trackRegistrationEventsV2('P1_Load', undefined, 0, true);
	            	<?php } else { ?>
	            		userRegistrationRequest['<?php echo $regFormId; ?>'].trackRegistrationEventsV2('P1_Load');
	            	<?php } ?>
		<?php } ?>
		if(registrationForm.registrationStatus != 'undefined' && registrationForm.registrationStatus.length != 0) {
			userRegistrationRequest['<?php echo $regFormId; ?>'].registrationStatus = registrationForm.registrationStatus;
		}
		initiateSimpleSlider($j);
		$j("#regSliders").simpleSlider({ speed: 500, regFormId:'<?php echo $regFormId; ?>' });

		<?php if(!empty($customFormData['customFields']['residenceCityLocality']['value'])){ ?>
			userRegistrationRequest['<?php echo $regFormId; ?>'].preSelectResidentCity();
		<?php } ?>

		<?php if(!empty($customFormData['customFields']['isUserLoggedIn']) && $customFormData['customFields']['isUserLoggedIn'] == 'yes'){ ?>
			$j('.login').hide();
		<?php } ?>

		<?php if(!empty($customFormData['customFields']['residenceCityLocality']['value']) && $customFormData['customFields']['residenceCityLocality']['value'] > 1){ ?>
			if($j('#residenceCityLocality_<?php echo $regFormId; ?>').val() == ''){
				$j('#residenceCityLocality_block_<?php echo $regFormId; ?>').removeClass('ih disabled filled').show();
			}
		<?php } ?>
		
	</script>
<?php } ?>
