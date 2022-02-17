<div id="registration-box">
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

<ul id="registrationFormMiddle_<?php echo $regFormId; ?>">
<?php $this->load->view('registration/fields/LDB/variable/default'); ?>
</ul>

<ul>
<?php
//if(!$formData['userId']) {
   // $this->load->view('registration/fields/securityCode');
// }
?>
<li>
     <input type="submit" id="registrationSubmit_<?php echo $regFormId; ?>" value="Submit" class="orange-button" <?php if($formData['userId']) echo "disabled='disabled'"; ?> onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm()" />
</li>

<?php if(!$formData['userId']): ?>
<li>
    <p><?php $this->load->view('registration/common/agree'); ?></p>
</li>
<?php endif; ?>
<div class="clearFix"></div>
</ul>
<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
<input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
<input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo $courseGroup == 'studyAbroad' ? 'yes' : 'no'; ?>' />
<input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo $registrationSource; ?>' />
<input type='hidden' id='referrer' name='referrer' value='<?php echo $referrer ? $referrer : $_SERVER['HTTP_REFERER']; ?>' />
<input type='hidden' id='pageReferrer' name='pageReferer' value='<?php echo htmlentities(strip_tags($_SERVER['HTTP_REFERER'])); ?>' />
<input type='hidden' id='fieldsView' name='fieldsView' value='default' />
<input type='hidden' id='isMR' name='isMR' value='YES' />
<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
<input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=true />
<?php if(isset($tracking_keyid))
{
$trackingPageKeyId=$tracking_keyid;
}
?>
<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value="<?php echo $trackingPageKeyId;?>">
<?php if($formData['coursePageSubcategoryId']) { ?>
<input type='hidden' id='coursePageSubcategoryId_<?php echo $regFormId; ?>' name='coursePageSubcategoryId' value='<?php echo $formData['coursePageSubcategoryId']; ?>' />
<?php } ?>
</form>
</div>
<div style="clear:both;"></div>
<?php $this->load->view('registration/common/jsInitialization'); ?>

<script>
if(shikshaUserRegistrationForm['<?php echo $regFormId; ?>']){
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].triggerISDCodeOnchange('<?php echo INDIA_ISD_CODE; ?>');
}
</script>