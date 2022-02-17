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
<?php
if(!$formData['userId']) {
    //$this->load->view('registration/fields/securityCode');
}
?>
<li>
    <input type="submit" id="registrationSubmit_<?php echo $regFormId; ?>" value="Submit" class="orange-button" onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm()" />
</li>
<li>
    <p><?php $this->load->view('registration/common/agree') ?></p>
</li>
<div class="clearFix"></div>
</ul>
<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
<input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
<input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo $courseGroup == 'studyAbroad' ? 'yes' : 'no'; ?>' />
<input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo "ONLINE_FORM"; ?>' />
<input type='hidden' id='referrer' name='referrer' value='<?php echo $customReferer ? $customReferer : "https://www.shiksha.com#MBAOnlineForm"; ?>' />
<input type='hidden' id='fieldsView' name='fieldsView' value='default' />
<input type='hidden' id='isMR' name='isMR' value='YES' />
<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
<input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=true />
<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value="<?php echo $trackingPageKeyId;?>">
</form>
</div>
<div style="clear:both;"></div>

<?php
$customValidations = json_encode($registrationHelper->getCustomValidations());
$defaultFieldStates = json_encode($registrationHelper->getDefaultFieldStates());
$newA = file_get_contents("public/blacklisted.txt");
?>

<script>
var blacklistWords = new Array(<?php echo $newA;?>);
function onload<?php echo $regFormId; ?> () {
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'] = new ShikshaUserRegistrationForm('<?php echo $regFormId; ?>');
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo $customValidations; ?>);
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setFormFieldList(<?php echo json_encode(array_keys($fields)); ?>);
    registrationCustomLogic['<?php echo $regFormId; ?>'] = new RegistrationCustomLogic('<?php echo $regFormId; ?>',<?php echo $customRules ? $customRules : "[]"; ?>,<?php echo $defaultFieldStates; ?>);
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setCustomLogic(registrationCustomLogic['<?php echo $regFormId; ?>']);
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateForm(<?php echo json_encode($formData); ?>,true);
    
    if(document.attachEvent) { //Internet Explorer
      document.attachEvent("onclick", function() {shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].hideAllLayers();});
    }
    else if(document.addEventListener) { //Firefox & company
      document.addEventListener('click', function() {shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].hideAllLayers();}, false); 
    }
}

var STUDY_ABROAD_NEW_REGISTRATION = '<?php echo STUDY_ABROAD_NEW_REGISTRATION ?>';

window.regFormLoad = onload<?php echo $regFormId; ?>;
</script>


