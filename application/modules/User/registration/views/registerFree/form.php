<div id="registration-box-layer">
<form action="/registration/Registration/register" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
<ul>
<?php

$this->load->view('registration/fields/LDB/userPersonalDetails');

if($registrationHelper->fieldExists('fieldOfInterest')) {
    $this->load->view('registration/fields/LDB/fieldOfInterest');
}
if($registrationHelper->fieldExists('desiredCourse')) {
    $this->load->view('registration/fields/LDB/desiredCourse');
}
?>
</ul>

<ul id="registrationFormMiddle_<?php echo $regFormId; ?>">
<?php $this->load->view('registration/fields/LDB/variable/default'); ?>
</ul>

<ul>
    <li>
    
    <input type="submit" id="registrationSubmit_<?php echo $regFormId; ?>" value="<?php echo $submitText ? $submitText : "Submit"; ?>" class="orange-button" onclick="trackEventByGA('Registration','Submit_Register_Desktop_Old'); return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm()" />
    
</li>

<li>
    <p><?php if(!$userIdOffline){$this->load->view('registration/common/agree');} ?></p>
</li>
<div class="clearFix"></div>
</ul>
<?php
    if($userIdOffline){
        echo "<input type='hidden' id='userId' name='userId' value='$userId' />";
        echo "<input type='hidden' id='userIdOffline' name='userIdOffline' value='$userIdOffline' />";
    }
?>
<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
<input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
<input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo $courseGroup == 'studyAbroad' ? 'yes' : 'no'; ?>' />
<input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo $customRegistrationSource ? $customRegistrationSource : "MARKETING_FORM"; ?>' />
<input type='hidden' id='referrer' name='referrer' value='<?php echo $customReferer ? $customReferer : "https://www.shiksha.com#homepageregisterbutton"; ?>' />
<input type='hidden' id='fieldsView' name='fieldsView' value='default' />
<input type='hidden' id='isMR' name='isMR' value='YES' />
<input type='hidden' id='isCompareEmail' name='isCompareEmail' value='<?php echo $isCompareEmail; ?>' />
<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
<input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=false />
<input type="hidden" id="replyContext_<?php echo $regFormId; ?>" name="replyMsg" value='<?php echo isset($replyContext) ? $replyContext : 'no'; ?>' />
<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value='<?= $trackingPageKeyId; ?>' />
<?php if(isset($threadId) && $threadId != '0'){ ?> 
	<input type="hidden" id="replyThreadId_<?php echo $regFormId; ?>" name="replyThreadId" value='<?php echo $threadId; ?>' />
<?php }?>
</form>
<?php $this->load->view('registration/common/jsInitialization'); ?>
</div>
<div style="clear:both;"></div>
<script>
    if(shikshaUserRegistrationForm['<?php echo $regFormId; ?>']){
        shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].triggerISDCodeOnchange('<?php echo INDIA_ISD_CODE; ?>');
    }
</script>