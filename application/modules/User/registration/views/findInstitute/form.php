<div id="registration-box">
<form action="/registration/Registration/findInstitute" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
<ul>
<div style="display:none;">
<?php
$this->load->view('registration/fields/LDB/userPersonalDetails'); ?>
</div>
<?php
if($registrationHelper->fieldExists('fieldOfInterest')){
    $this->load->view('registration/fields/LDB/fieldOfInterest');    
}
if($registrationHelper->fieldExists('desiredCourse')){
    $this->load->view('registration/fields/LDB/desiredCourse');    
}
?>
</ul>

<ul id="registrationFormMiddle_<?php echo $regFormId; ?>">
<?php $this->load->view('registration/fields/LDB/variable/default',array('parentForm' => 'findInstitute')); ?>
</ul>

<ul>
<li>
    <input type="submit" id="registrationSubmit_<?php echo $regFormId; ?>" value="Submit" class="orange-button" onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm()" />
</li>
<div class="clearFix"></div>
</ul>
<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
<input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
<input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo $courseGroup == 'studyAbroad' ? 'yes' : 'no'; ?>' />
<input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo $customRegistrationSource ? $customRegistrationSource : "MARKETING_FORM"; ?>' />
<input type='hidden' id='referrer' name='referrer' value='<?php echo $customReferer ? $customReferer : "https://www.shiksha.com#homepageregisterbutton"; ?>' />
<input type='hidden' id='fieldsView' name='fieldsView' value='default' />
<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='YES' />
<input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=true />
</form>
</div>
<div style="clear:both;"></div>
<?php $this->load->view('registration/common/jsInitialization'); ?>