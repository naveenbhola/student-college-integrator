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
<div class="content-wrap clearfix">
	<div id="left-col">
    <form action="<?php echo $action; ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
       <div class="account-setting-detail">
       <input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
		<p class="account-setting-info-title">Account settings</p>
	    <?php //$formData['userId'] = true;
		$this->load->view('registration/fields/LDB/variable/abroadUserSetting');
	    ?>
		<?php
			if($userIdOffline){
				echo "<input type='hidden' id='userId' name='userId' value='$userIdOffline' />";
				echo "<input type='hidden' id='userIdOffline' name='userIdOffline' value='$userIdOffline' />";
			}
		?>
	    <input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
	    <input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
	    <input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo ($courseGroup == 'studyAbroad' || $courseGroup == 'studyAbroadRevamped') ? 'yes' : 'no'; ?>' />
	    <input type='hidden' id='registrationSource' name='registrationSource' value='abroadProfileSetting' />
	    <input type='hidden' id='referrer' name='referrer' value='<?php echo htmlentities($this->security->xss_clean($customReferer ? $customReferer : $_SERVER['HTTP_REFERER'])); ?>' />
	    <!-- fields required for response creation in case of download brochure -->
	    <input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
	    <input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value='true' />
	    <input id="tracking_keyid_<?php echo $regFormId; ?>" type="hidden" value="693" name="tracking_keyid">
	    <?php
			$CI = & get_instance();
			$CI->load->library('security');
			$CI->security->setCSRFToken();
		?>
		<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
	    </div>
		<a id ="submitDownloadBottom" href="javascript:void(0);" uniqueattr="" onclick="return validateAccountSettingForm(<?php echo "'$regFormId'"; ?>)" class="save-change-btn" style="">Save Changes</a>
    </form>
</div>
	<div id="right-col"></div>
</div>
<script>
<?php if($formData['isdCode'] != INDIA_ISD_CODE){ ?>
	setTimeout(function(){
		shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeStudyAbroadForm('<?php echo $formData["isdCode"]; ?>');
	}, 500);
<?php } ?>

</script>