<script>
	    var registration_context = '<?php echo $context; ?>';
	    
	    <?php
	    $action = '/registration/Registration/register';
	    if(!empty($abroadShortRegistrationData)) {
			if(!empty($abroadShortRegistrationData['whenPlanToGo']) && (!empty($abroadShortRegistrationData['passport']) || !empty($abroadShortRegistrationData['examsAbroad']))) {
	    ?>
			$j(document).ready( function () {
				    var FormId = '<?php echo $regFormId; ?>';
				    var stepTwoFromHTML = $j('#stepTwoRegistrationForm_' + FormId).html();
				    var stepTwoAction = $j('#stepTwoRegistrationForm_' + FormId).attr('action');
				    $j('#registrationForm_' + FormId).html(stepTwoFromHTML);
				    $j('#registrationForm_' + FormId).attr('action', stepTwoAction);
				    $j('#twoStepFormHeaderText').html('Choose a popular course');
				    $j('#twoStepFormHeaderText').attr("class","flLt");
				    $j("#li_stepOne").removeClass('active').addClass('completed');
				    $j("#li_stepTwo").addClass('active');
				    $j("#twoStepLoginButton").hide();
				    
				    if (!shikshaUserRegistrationForm[FormId]) {
						shikshaUserRegistrationForm[FormId] = new ShikshaUserRegistrationForm(FormId);
				    }
				    
				    shikshaUserRegistrationForm[FormId].twostepCountryDivTrigger();
				    if(typeof(onGuideDownloadShowRegistrationLayer)!='undefined' && onGuideDownloadShowRegistrationLayer == true){
					$j("#close-two-step-layer").attr('onclick','addActionToCross();registrationOverlayComponent.hideOverlay();setTimeout(function(){window.location.reload();},TIME_DELAY_FOR_PDF_DOWNLOAD);');
						onGuideDownloadShowRegistrationLayer = false;
				    }
			});
	    <?php
			}
			else {
				    $action = '/registration/Registration/updateUser';
	    ?>
			$j(document).ready( function () {
				    $j('#signUpButton_' + '<?php echo $regFormId; ?>').css('margin-top','15px');
			});
	    <?php
			}
	    }
	    ?>
</script>
<div class="abroad-register-details-2 flRt" style="width:300px; margin:0 45px 0 10px;">
                    <div class="abroad-register-head clearFix">
                        <p id="twoStepFormHeaderText" class="font-14 flLt">Please enter your details</p>
			<span id="twoStepResetCourse" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].showMoreOnclick();" class="reset-course flRt" style="display: none;"><i class="common-sprite opt-arr-d"></i>Reset Course</span>
                    </div>
<form action="<?php echo $action; ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
	    <ul class="customInputs-large login-layer">
			<?php
				    $this->load->view('registration/fields/LDB/variable/twoStep', array('step' => 1));
				    
				    if(!$formData['userId']) {
						$this->load->view('registration/fields/securityCode');
				    }
			?>
			<li id=signUpButton_<?php echo $regFormId; ?>>
				    <a href="javascript:void(0);" uniqueattr="SA_SESSION_ABROAD_PAGE/submitStep1" class="big-button button-style" onclick="return shikshaUserRegistrationForm[<?php echo "'$regFormId'"; ?>].submitForm();" style="width:100%; text-align:center; font-size:18px; z-index: 888888;">Save & Continue <i class="common-sprite cotnue-icon"></i></a>
			</li>
			<li style="text-align:left;margin-bottom:5px;">By clicking Save & Continue, I agree to the <a onclick="return popitup('https://www.shiksha.com/shikshaHelp/ShikshaHelp/termCondition');" href="javascript:void(0);">terms of services</a> and <a onclick="return popitup('https://www.shiksha.com/shikshaHelp/ShikshaHelp/privacyPolicy');" href="javascript:void(0);">privacy policy</a>.</li>
			<div class="clearFix"></div>
	    </ul>
	    
    <?php
        if($userIdOffline){
            echo "<input type='hidden' id='userId' name='userId' value='$userIdOffline' />";
            echo "<input type='hidden' id='userIdOffline' name='userIdOffline' value='$userIdOffline' />";
        }
    ?>
	    <input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
	    <input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
	    <input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo ($courseGroup == 'studyAbroad' || $courseGroup == 'studyAbroadRevamped') ? 'yes' : 'no'; ?>' />
	    <input type='hidden' id='registrationSource' name='registrationSource' value='abroadTwoStep' />
	    <input type='hidden' id='referrer' name='referrer' value='<?php echo $customReferer ? $customReferer : $_SERVER['HTTP_REFERER']."#twoStepRegister"; ?>' />
	    <input type='hidden' id='registrationStep' name='registrationStep' value='1' />
	    <?php
			$CI = & get_instance();
			$CI->load->library('security');
			$CI->security->setCSRFToken();
		?>
		<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
</form>
</div>

<form action="/registration/Registration/updateUser" id="stepTwoRegistrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post" style="display:none;">
	    <ul class="customInputs-large">
			<?php $this->load->view('registration/fields/LDB/variable/twoStep', array('step' => 2)); ?>
			<li>
                                    <div id="registrationSubmit_<?php echo $regFormId; ?>"></div>
				    <a id="finalStepSubmit" href="javascript:void(0);" uniqueattr="SA_SESSION_ABROAD_PAGE/submitStep2" onclick="return shikshaUserRegistrationForm[<?php echo "'$regFormId'"; ?>].submitForm();" class="big-button button-style" style="width:100%; text-align:center; font-size:18px; z-index: 888888;">Done & Register</a>
			</li>
	    </ul>
    <?php
        if($userIdOffline){
            echo "<input type='hidden' id='userId' name='userId' value='$userIdOffline' />";
            echo "<input type='hidden' id='userIdOffline' name='userIdOffline' value='$userIdOffline' />";
        }
    ?>
	    <input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
	    <input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
	    <input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo ($courseGroup == 'studyAbroad' || $courseGroup == 'studyAbroadRevamped') ? 'yes' : 'no'; ?>' />
	    <input type='hidden' id='registrationSource' name='registrationSource' value='abroadTwoStep' />
	    <input type='hidden' id='referrer' name='referrer' value='<?php echo $customReferer ? $customReferer : $_SERVER['HTTP_REFERER']."#twoStepRegister"; ?>' />
	    <input type='hidden' id='registrationStep' name='registrationStep' value='2' />
	    <?php
			$CI = & get_instance();
			$CI->load->library('security');
			$CI->security->setCSRFToken();
		?>
		<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
</form>

<?php $this->load->view('registration/common/jsInitialization'); ?>