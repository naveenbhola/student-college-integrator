<?php
$isLoggedIn = false;
if($formData['userId'] > 0) {
    $isLoggedIn = true;
}

if($isLoggedIn) {
    $action = '/registration/Registration/updateUser';
}
else {
    $action = '/registration/Registration/register';
}
?>
<?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>
<div id="registration-box-layer">
    <form action="<?php echo $action; ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
        <input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
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
                <input type="submit" id="registrationSubmit_<?php echo $regFormId; ?>" value="<?php echo $submitText ? $submitText : "Submit"; ?>" class="orange-button" onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm()" />
            </li>
            
            <li>
                <p><?php $this->load->view('registration/common/agree'); ?></p>
            </li>
            <div class="clearFix"></div>
        </ul>
        
        <input type='hidden' id='userId' name='userId' value='<?=$formData['userId'];?>' />
        <input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
        <input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
        <input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo $courseGroup == 'studyAbroad' ? 'yes' : 'no'; ?>' />
        <input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo $customRegistrationSource; ?>' />
        <input type='hidden' id='referrer' name='referrer' value='<?php echo $customReferer; ?>' />
        <input type='hidden' id='fieldsView' name='fieldsView' value='default' />
        <input type='hidden' id='isMR' name='isMR' value='YES' />
        <input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
        <input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=false />
       <?php if(!empty($tracking_keyid)) { ?>
        <input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value=<?=$tracking_keyid?> />
       <?php } ?>
    </form>
    
    <?php $this->load->view('registration/common/jsInitialization'); ?>
</div>
