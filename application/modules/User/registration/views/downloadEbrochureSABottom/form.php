<?php 
    $action = '/registration/Registration/register';
    if(!empty($abroadShortRegistrationData)) {
        $action = '/registration/Registration/updateUser';
    }
?>
<script>
    var registration_context = '<?php echo $context; ?>';
    var FormIdBottom = '<?php echo $regFormId; ?>';
</script>
<?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>
<div class="form-wrap" style="padding:0">
    <form action="<?php echo $action; ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
        <input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
	    <?php $this->load->view('registration/fields/LDB/variable/oneStepAbroadResponseBottom');
		if(!empty($consultantRelatedData['consultantData'])){
		    $this->load->view('consultantEnquiry/consultantEnquiryResponseForm');
		}
	    ?>
		<div class="clearwidth" style="border-bottom:0 none;padding:10px 15px;">
		    <div style="<?=($formData['userId']?'margin-top:20px;':'')?>">
			<div class="signup-txtwidth">
				<a id = "submitDownloadBottom" href="javascript:void(0);" uniqueattr="SA_SESSION_ABROAD_<?=strtoupper($listingType)?>_PAGE/brochureDownloadBottom" onclick="<?php if(!$formData['userId']){ ?>if(checkIfUserExists($j('#email_<?php echo $regFormId; ?>'))){<?php } ?>return shikshaUserRegistrationForm[<?php echo "'$regFormId'"; ?>].submitForm();<?php if(!$formData['userId']){ ?>}<?php }?>" class="button-style big-button" style=" <!-- <?=(!$formData['userId']?'margin-top:20px;':'')?> -->">Download Brochure</a>
			</div>
			<?php 
			if(!$formData['userId']) {
		    ?>
			<p style="font-size:10px; float:left; margin-top:18px;">I agree to the <a onclick="return popitup('https://www.shiksha.com/shikshaHelp/ShikshaHelp/termCondition');" href="javascript:void(0);">terms of services</a> and <a onclick="return popitup('https://www.shiksha.com/shikshaHelp/ShikshaHelp/privacyPolicy');" href="javascript:void(0);">privacy policy</a>.</p>
		    <?php 
			} 
		    ?>
		    
		    </div>
		</div>
        
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
	    <input type='hidden' id='referrer' name='referrer' value='<?php echo htmlentities($this->security->xss_clean($customReferer ? $customReferer : $_SERVER['HTTP_REFERER']."#abroadDownloadBrochure")); ?>' />
	    <!-- fields required for response creation in case of download brochure -->
	    <input type='hidden' id='responseSourceBottom' name='responseSource' value='<?=$responseSourcePage?>' />
	    <input type='hidden' id='listingTypeForBrochureBottom' name='listingTypeForBrochure' value='<?=$listingTypeForBrochure?>' />
	    <input type='hidden' id='listingTypeIdForBrochureBottom' name='listingTypeIdForBrochure' value='<?=$clientCourseId?>' />
	    <input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
	    <input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=true />
	    <input id="tracking_keyid_<?php echo $regFormId; ?>" type="hidden" value="<?php echo $trackingPageKeyId; ?>" name="tracking_keyid">
	    <input id="consultant_tracking_keyid_<?php echo $regFormId; ?>" type="hidden" value="<?php echo $consultantTrackingPageKeyId; ?>" name="consultant_tracking_keyid">
	    
    </form>
</div>
<script type="text/javascript">
	// reason of using setTimeOut function is that jsInitialization is loaded later and we need to trigger onchange od isdCode field for logged in user
	setTimeout(function(){
		if(typeof(shikshaUserRegistrationForm['<?=$regFormId?>']) != 'undefined' &&  $('isdCode_<?=$regFormId?>').value != '91-2'){	
		    $j('#isdCode_<?=$regFormId?>').trigger('change');
		}
	}, 1800);
	
	function showEducationFieldsBottom(obj){
		var formId = '<?=$regFormId?>';
		var ldbCourseId = $j("#registrationForm_"+formId+" [name='desiredCourse']").val();
		shikshaUserRegistrationForm[formId].getSAshikshaApplyFields({'ldbCourseId':ldbCourseId,'context':'oneStepAbroad'});

	}
	</script>


