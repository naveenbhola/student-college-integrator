<?php
    $action = '/registration/Registration/register';
    if(!empty($abroadShortRegistrationData)) {
        $action = '/registration/Registration/updateUser';
    }
?>
<script>
    var registration_context = '<?php echo $context; ?>';
    var FormIdBottom = '<?php echo $regFormId; ?>';
	var rmcForm = true;
</script>
<form action="<?php echo $action; ?>" id="registrationForm_<?php echo $regFormId; ?>" name="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
<div class="introcall-Det">
<?php
$this->load->view('registration/fields/LDB/variable/studentExtraDetails');
?>
<div class="flRt" style="width:50%;border-left:1px solid #999;">
<div class="student-detail-col">
  <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite minus-icon flRt"></i>Student details</h3>
  <div class="cms-accordion-div">
		<div class="form-wrap account-setting-detail" style="padding:0">

				<?php
				$this->load->view('registration/fields/LDB/variable/rmcStudentProfile');
				?>
			<?php
				if($userIdOffline){
					echo "<input type='hidden' id='userId' name='userId' value='$userIdOffline' />";
					echo "<input type='hidden' id='userIdOffline' name='userIdOffline' value='$userIdOffline' />";
				}
			?>
				<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
				<input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='yes' />
				<input type='hidden' id='isRmcStudentprofile' name='isRmcStudentprofile' value='yes' />
				<?php
					$CI = & get_instance();
					$CI->load->library('security');
					$CI->security->setCSRFToken();
				?>
				<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />


		</div>
	</div>
</div>
</div>
    <div class="clearFix"></div>
</div>
<?php
$this->load->view('registration/fields/LDB/variable/optionalAndFinanceDetails');
?>
    <div class="errorMsg" id="overAllProfile_error"></div>
    <div class="button-wrap" style="margin:20px 0 20px 25px;">
        <a id = "submitDownloadBottom" href="javascript:void(0);" onclick="<?php echo ($isValidCandidate!==true?'return false;':''); ?>validateAndSaveProfile(<?php echo "'$regFormId'"; ?>);" class="orange-btn">Save Profile</a>
        <a href="Javascript:void(0);" onclick="confirmRedirection();" class="cancel-btn">Cancel</a>
    </div>
    <div class="clearFix"></div>
</form>
<?php $this->load->view('registration/common/jsInitialization'); ?>
