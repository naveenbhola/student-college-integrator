<div id="registration-box-layer">
<?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>
<form action="/registration/Registration/updateUser" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
<ul>
<?php
if($registrationHelper->fieldExists('fieldOfInterest')) {
    $this->load->view('registration/fields/LDB/fieldOfInterest');
}
if($registrationHelper->fieldExists('desiredCourse')) {
    $this->load->view('registration/fields/LDB/desiredCourse');
}
?>
</ul>

<ul id="registrationFormMiddle_<?php echo $regFormId; ?>">
<?php $this->load->view('registration/fields/LDB/variable/compact'); ?>
</ul>

<ul>
<li>
    <input type="submit" id="registrationSubmit_<?php echo $regFormId; ?>" value="Submit" class="orange-button" onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm()">
</li>
<li>
     <p><?php $this->load->view('registration/common/agree'); ?></p>
</li>
<div class="clearFix"></div>
</ul>
<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
<input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
<input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo $courseGroup == 'studyAbroad' ? 'yes' : 'no'; ?>' />
<input type='hidden' id='userId' name='userId' value='<?php echo $formData['userId']; ?>' />
<input type='hidden' id='fieldsView' name='fieldsView' value='compact' />
</form>
</div>
<div style="clear:both;"></div>
<?php $this->load->view('registration/common/jsInitialization'); ?>