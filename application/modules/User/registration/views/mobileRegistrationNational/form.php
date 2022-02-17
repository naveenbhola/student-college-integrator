<div class="NewReg-form">

    <form action="/registration/Registration/register" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
        <ul>
            <?php
            $this->load->view('registration/fields/LDB/variable/userPersonalDetailsNMR');

            if($registrationHelper->fieldExists('fieldOfInterest')) {
                $this->load->view('registration/fields/LDB/variable/userEducationInterestNMR');
            }
            
            ?>
        </ul>
        <ul id="registrationFormMiddle_<?php echo $regFormId; ?>">
            <?php $this->load->view('registration/fields/LDB/variable/mobileRegistrationNational'); ?>
        </ul>

        <input type="submit" id="registrationSubmit_<?php echo $regFormId; ?>" value="SUBMIT" class="common-btn clearFix registerSubmitButton" /> 

        <p class="policy-text">By clicking submit button, I agree to the <a href="/mcommon5/MobileSiteStatic/terms" target="_blank">terms of services</a> and <a href="/mcommon5/MobileSiteStatic/privacy" target="_blank">privacy policy</a>.</p>

        <?php
            if(!$formData['userId']) {
                echo "<input type='hidden' id='userId' name='userId' value='$userId' />";
                echo "<input type='hidden' id='userIdOffline' name='userIdOffline' value='$userIdOffline' />";
            }
        ?>
        <input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
        <input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
        <input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo $courseGroup == 'studyAbroad' ? 'yes' : 'no'; ?>' />
        <input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo $customRegistrationSource ? $customRegistrationSource : "MARKETING_FORM"; ?>' />
        <input type='hidden' id='referrer' name='referrer' value='<?php echo $_SERVER['HTTP_REFERER']; ?>' />
        <input type='hidden' id='fieldsView' name='fieldsView' value='default' />
        <input type='hidden' id='isMR' name='isMR' value='YES' />
        <input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
        <input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value='true' />
        <input type="hidden" id="replyContext_<?php echo $regFormId; ?>" name="replyMsg" value='<?php echo isset($replyContext) ? $replyContext : 'no'; ?>' />
        <input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value='<?= $trackingPageKeyId; ?>' />
        <input type="hidden" id="registrationIdentifier_<?php echo $regFormId; ?>" name="registrationIdentifier" value='<?php echo $customFormData["registrationIdentifier"]; ?>' />
        
        <a aria-expanded="false" id="newDialogPage" aria-owns="dialogPage" aria-haspopup="true" href="#dialogPage" data-rel="dialog" class="ui-btn ui-btn-inline ui-corner-all select-hide" data-transition='none'  style=""></a> 

    </form>
</div>
<div style="clear:both;"></div>
<?php $this->load->view('registration/common/jsInitialization'); ?>

<script>

shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].customParamsForRegistration = <?php echo json_encode($customFields); ?>;
shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].callbackFunctionParams = <?php echo json_encode($callbackFunctionParams); ?>;
shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].registrationTitle = "<?php echo $registrationTitle; ?>";
shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].callbackFunction = '<?php echo $callbackFunction; ?>';
shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateForm(<?php echo json_encode($formData); ?>,true);

$(document).ready(function(){
    if(shikshaUserRegistrationForm['<?php echo $regFormId; ?>']){
        shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].formData = <?php echo json_encode($formData); ?>;
        shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].examFormData = <?php echo json_encode($formData['exams']); ?>;
    }

    <?php if(!empty($formData['desiredCourse'])){  ?>
        shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse();   
    <?php }else{ ?>
        shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].triggerISDCodeOnchange('<?php echo INDIA_ISD_CODE; ?>');
    <?php } ?>   
});
</script>