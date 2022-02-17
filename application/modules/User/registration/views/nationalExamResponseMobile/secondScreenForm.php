<?php $formType='response'; ?>
<div class="SgUp-lyr" data-enhance="false">

	<div class="SgUp-lyr" data-enhance="false">
	<div class="head">
		<p class="head-title fs16" id="title1_<?php echo $regFormId; ?>"><?php 
		if(!empty($formHeading)){ 
			echo 'To '.$formHeading.(($customFormData['customFields']['isUserLoggedIn'] == "yes") ? ", enter details below:" :", Sign Up");			
		}else{  echo 'Sign Up'; } ?></p>
		<?php if(!$customFormData['callbackFunctionParams']['AMP_FLAG']){?>
		<a href="javascript:void(0);" class="lyr-cls" data-rel="back">&times;</a>
		<?php } ?>
		<div class="clear"></div>
	</div>
	
	<?php if($customFormData['showFormWithoutHelpText'] != '1'){ ?>
		<div class="sgup-Txtbox ih">
		    <p><?php echo $customHelpText['heading'];?></p>
			<ul class="ml18 lsd">
			    <?php foreach($customHelpText['body'] as $key=>$bodyText){ ?>
					<li class="clg-brTxt"><?php echo $bodyText; ?></li>
				<?php } ?>
			 </ul>
		 </div>
	<?php } ?>

<?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>

	<div class="Sgup-FrmSec">
		<form id="secondLayer_<?php echo $regFormId; ?>"  class="screenHolder" regFormId="<?php echo $regFormId; ?>"  data-enhance="false">
			<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
		<div class="scrolable-vport" id="sndscreenSroll_<?php echo $regFormId; ?>">
			<p class="field-title">We will also send you insights, recommendations and updates.</p>
			<?php $this->load->view('registration/fields/mobile/examResponse/examCourseGroup'); ?>
			
			<div id="mappedFields_<?php echo $regFormId; ?>">
			</div>
			<div id="dependentFields_<?php echo $regFormId; ?>">
			</div>
			<?php $this->load->view('registration/fields/mobile/prefYear'); ?>

			<?php $this->load->view('registration/fields/mobile/accountDetails', array('formType'=>$formType)); ?>
			<?php $this->load->view('registration/fields/mobile/personalInfo'); ?>
			
			<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
			<input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
			<input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo $httpReferer ? $httpReferer : $_SERVER['HTTP_REFERER']; ?>' />
			<input type='hidden' id='isMR' name='isMR' value='YES' />
			<?php $trackingKeyId = trim($trackingKeyId);?>
			<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value='<?php echo empty($trackingKeyId)?DEFAULT_TRACKING_KEY_EXAM_MOBILE:$trackingKeyId; ?>' />
			<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
			<?php if(!empty($responseAction)){ ?>
				<input type="hidden" name="action_type" value="<?php echo $responseAction; ?>" id="response_action_<?php echo $regFormId; ?>">
			<?php } ?>
			</div>
			<div class="shadow-vport">
			<div class="shadow-lyr"></div>
			<a href="javascript:void(0);" class="reg-btn stp2" style="left:27px;" type="submit" regformid="<?php echo $regFormId; ?>" tabindex="2"><?php if(!empty($submitButtonText)){ echo $submitButtonText; }else{ echo 'Sign Up'; }?></a>
			<?php if(empty($customFormData['customFields']['isUserLoggedIn']) || $customFormData['customFields']['isUserLoggedIn'] != 'yes'){  ?>
				<div class="btn-rltv clear">
					<div class="Sgup-box">
						<p class="newSks lgn2 loginScreen" regformid="<?php echo $regFormId; ?>">Already have an account? Login here</p>
						<!-- <a href="javascript:void(0);" class="sgup-btn lgn2 loginScreen" regformid="<?php echo $regFormId; ?>">Login</a> -->
					</div>
				</div>
			<?php } ?>
			</div>
		</form>
	</div>

</div>

 <?php $this->load->view('registration/common/jsObjectInitialization', true); ?>
 <script type="text/javascript">
 	userRegistrationRequest['<?php echo $regFormId; ?>'].isResponseForm = true;

 	<?php if(!empty($customFormData['customFields']['residenceCity']['value']) && $customFormData['customFields']['residenceCity']['value'] > 1){ ?>
		registrationForm.updateFieldLabel('residenceCityLocality', '<?php echo $regFormId; ?>');
	<?php } ?>

	<?php 
		if(!empty($customFormData['customFields']['isdCode']['value']) && $customFormData['customFields']['isdCode']['value'] != '91-2'){ ?>
			$('#isdCode_<?php echo $regFormId; ?>').change();
		<?php }
		if(!empty($customFormData['customFields']['residenceCityLocality']['value'])){ ?>
		userRegistrationRequest['<?php echo $regFormId; ?>'].preSelectResidentCity();
	<?php } ?>

	<?php if(!empty($customFormData['customFields']['residenceCityLocality']['value']) && $customFormData['customFields']['residenceCityLocality']['value'] > 1){ ?>
		
		if($('#residenceCityLocality_<?php echo $regFormId; ?>').val() == '' || $('#residenceCityLocality_<?php echo $regFormId; ?>').val() === null){

			$('#residenceCityLocality_block_<?php echo $regFormId; ?>').removeClass('ih disabled filled').show();
		}
	<?php } ?>
 </script>