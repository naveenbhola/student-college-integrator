<?php 
    $action = '/registration/Registration/register';
    if(!empty($abroadShortRegistrationData)) {
        $action = '/registration/Registration/updateUser';
    }
    global $rmcTrackingCourseId;
    if(!isset($_COOKIE['rmcTrackingPageKey'.$rmcTrackingCourseId])){
    	$trackingPageKeyId = 645;
		setcookie("rmcTrackingPageKey".$rmcTrackingCourseId, $trackingPageKeyId, time()+1800, "/", COOKIEDOMAIN);					
    	$_COOKIE['rmcTrackingPageKey'.$rmcTrackingCourseId]=$trackingPageKeyId;
    }
?>
<script>
    var registration_context = '<?php echo $context; ?>';
    var FormIdBottom = '<?php echo $regFormId; ?>';
	var rmcForm = true;
</script>
<div class="rate-my-chance-form-head">
	Enter your details to know your chances of admission
</div>
<div class="form-wrap account-setting-detail" style="padding:0;">
	<?php if(!$formData['userId']) { ?>
	<a style="padding:10px 10px 0 0" class="flRt font-11" href="Javascript:void(0);" onclick = "studyAbroadTrackEventByGA('<?=$eventTrackerStringGA?>', 'existingUserLogin'); registrationOverlayComponent.hideOverlay(); loadStudyAbroadForm({'isStudyAbroadPage':1},'/registration/Forms/showLoginLayer','loginFormContainer');engageDownloadBrochureWithLogin = 1; return false;">Already registered?</a>
	<?php } ?>
    <form action="<?php echo $action; ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
        
	    <?php //$formData['userId'] = true;
		$this->load->view('registration/fields/LDB/variable/rmcPage');
	    ?>
		<div class="clearwidth" style="border-bottom:0 none;padding:10px 15px;">
		    <div class="flLt">
				<a
					id = "submitDownloadBottom"
					href="javascript:void(0);"
					uniqueattr="SA_SESSION_ABROAD_RMC_PAGE/rmcResponse"
					onclick="<?php if(!$formData['userId']){ ?>
								if(checkIfUserExists($j('#email_<?php echo $regFormId; ?>'))){
							<?php } ?>
									if (!rmcFormSpamControl) {
										return shikshaUserRegistrationForm[<?php echo "'$regFormId'"; ?>].submitForm();
									}
									
							<?php if(!$formData['userId']){ ?>
								}
							<?php }?>"
					class="rate-change-button text-center"
					style="margin-bottom: 0px; font-size: 16px; padding: 13px; width: 200px;"
					>
						Rate My Chances
				</a>
			</div>
			<div class="clearwidth"></div>
			<?php 
			if(!$formData['userId']) {
		    ?>
			<p style="font-size:10px; float:left; margin-top:18px;">I agree to the <a onclick="return popitup(SHIKSHA_STUDYABROAD_HOME.'/shikshaHelp/ShikshaHelp/termCondition');" href="javascript:void(0);">terms of services</a> and <a onclick="return popitup('https://www.shiksha.com/shikshaHelp/ShikshaHelp/privacyPolicy');" href="javascript:void(0);">privacy policy</a>.</p>
		    <?php 
			} 
		    ?>
		</div>
        
    <?php
        if($userIdOffline){
            echo "<input type='hidden' id='userId' name='userId' value='$userIdOffline' />";
            echo "<input type='hidden' id='userIdOffline' name='userIdOffline' value='$userIdOffline' />";
        }
    ?>
	    <input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
	    <input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
	    <input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo ($courseGroup == 'studyAbroad' || $courseGroup == 'studyAbroadRevamped' || $courseGroup == 'SAapply') ? 'yes' : 'no'; ?>' />
	    <input type='hidden' id='registrationSource' name='registrationSource' value='abroadTwoStep' />
	    <input type='hidden' id='referrer' name='referrer' value='<?php echo htmlentities($this->security->xss_clean($customReferer ? $customReferer : $_SERVER['HTTP_REFERER'])); ?>' />
	    <!-- fields required for response creation -->
	    <input type='hidden' id='responseSourceBottom' name='responseSource' value='<?=$responseSourcePage?>' />
	    <input type='hidden' id='listingTypeForBrochureBottom' name='listingTypeForBrochure' value='<?=$listingTypeForBrochure?>' />
	    <input type='hidden' id='listingTypeIdForBrochureBottom' name='listingTypeIdForBrochure' value='<?=$clientCourseId?>' />
	    <input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
	    <input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=true />
	    <input id="tracking_keyid_<?php echo $regFormId; ?>" type="hidden" value="<?=$_COOKIE['rmcTrackingPageKey'.$rmcTrackingCourseId]?>" name="tracking_keyid">

	    <?php
			$CI = & get_instance();
			$CI->load->library('security');
			$CI->security->setCSRFToken();
		?>
		<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
    </form>
</div>
<?php
 //jsinitialization moved to rmcHomepageFooter.php 
?>
