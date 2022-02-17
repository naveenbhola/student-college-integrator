<?php 
    $action = '/registration/Registration/register';
    if(!empty($abroadShortRegistrationData)) {
        $action = '/registration/Registration/updateUser';
    }
	$refUrl = addingDomainNameToUrl(array('url'=>$customReferer  ? $customReferer : getCurrentPageURL(),
													'domainName'=> SHIKSHA_STUDYABROAD_HOME)
											);
?>
<div id="sp-wrpr">
	<form action="<?php echo $action; ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
	<?php if($isInlineForm !== true){ ?>
	<div class="sp-blck">
        <div class="sp-blk-c">
            <?php $this->load->view("registration/fields/LDB/variable/widgets/SASingleRegistrationHeader"); ?>
        </div>
    </div>
	<?php } ?>
    <div class="sp-blck-fm clear">
		<?php if($isInlineForm !== true){ ?>    
		<div class="sp-blk-c">
			<?php if(empty($formData['userId'])) { ?>
				<a href="Javascript:void(0);" data-src="/login?ar=<?php echo base64_encode(1); ?>" class="lg-lnk">Already Registered? Login</a>
			<?php }?>
		</div>
		<?php }?>
    
		<?php $this->load->view("registration/fields/LDB/variable/saSingleRegistrationForm"); ?>
		<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
	    <input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
	    <input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo ($courseGroup == 'studyAbroad' || $courseGroup == 'studyAbroadRevamped' || $courseGroup=='SASingleRegistration') ? 'yes' : 'no'; ?>' />
	    <input type='hidden' id='referrer' name='referrer' value='<?php echo htmlentities($this->security->xss_clean($refUrl.($refUrl ==SHIKSHA_STUDYABROAD_HOME?"#abroadSignUp":""))); ?>' />
	    <input type='hidden' id='fileUrl' name='fileUrl' value='<?php echo htmlentities($url !="" ? ($url) : ''); ?>' />
	    <input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
		<input type='hidden' id='examId' value='<?php echo $examId; ?>' />
		<?php 
		if(isset($scholarshipId) && $scholarshipId > 0){
		?>
			<input type='hidden' id='scholarshipId' value='<?php echo $scholarshipId; ?>' />
			<input type='hidden' id='responseType' value='scholarshipResponse' />
		<?php 
		}
		?>
		<input type='hidden' id='counselorId' value='<?php echo $counselorId; ?>' />
	    <input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value="<?php echo ($isInlineForm === true?true:false); ?>" />
		<input type='hidden' id='registrationSource' name='registrationSource' value='abroadTwoStep' /> 
		<input type='hidden' name='saABTracking' value='yes' />
		<?php if($contentId>0) { ?>
		<input type='hidden' id='contentId' value='<?php echo $contentId; ?>' /> 
		<?php } ?>
		<?php if($responseSource){?>
		<input type='hidden' id='responseSource' value='<?php echo $responseSource ?>' /> 
		<?php } ?>
		<input type='hidden' id='conversionType' value='<?php echo $MISTrackingDetails['conversionType']; ?>' /> 
		<input type='hidden' id='keyName' value='<?php echo $MISTrackingDetails['keyName']; ?>' /> 
		<input type='hidden' id='shortlistpagetype' value='<?php echo $MISTrackingDetails['page']; ?>' /> 
        <input id="tracking_keyid_<?php echo $regFormId; ?>" type="hidden" value="<?php echo $trackingPageKeyId?$trackingPageKeyId:1206; ?>" name="tracking_keyid">
        <?php if($MISTrackingDetails['conversionType']=='compare'){?>
		<input type='hidden' id='compCourseId' value='<?php echo $compCourseId; ?>' /> 
		<input type='hidden' id='compSource' value='<?php echo $compSource; ?>' /> 
		<?php } ?>
		<input id="refererTitle" name="refererTitle" type="hidden" value="<?php echo $refererTitle; ?>">
		
		<?php
			$CI = & get_instance();
			$CI->load->library('security');
			$CI->security->setCSRFToken();
		?>
		<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />

    </div>
    <div class="clearfix"></div>
        <?php 
        	$dataToChangeCTAHeading['isUserloggedIn'] = (!empty($formData['userId']))?"yes":"no";
        	$this->load->view("registration/fields/LDB/variable/widgets/SASingleRegistrationFooter",$dataToChangeCTAHeading);
        ?>
    </form>
	<div id="otpLayer" style="margin:8px 0 20px 10px;display:none"></div>
</div>
