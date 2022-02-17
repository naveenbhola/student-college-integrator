<?php 
    $action = '/registration/Registration/register';
    if(!empty($abroadShortRegistrationData)) {
        $action = '/registration/Registration/updateUser';
    }
?>
<?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>
<script>
    var registration_context = '<?php echo $context; ?>';
    
    $j(document).ready( function () {
        var FormId = '<?php echo $regFormId; ?>';
        if (!shikshaUserRegistrationForm[FormId]) {
            shikshaUserRegistrationForm[FormId] = new ShikshaUserRegistrationForm(FormId);
        }
        shikshaUserRegistrationForm[FormId].twostepCountryDivTrigger();
    });
    
    $j("#twoStepChooseCourseCountryDropDown").one( "click", function() {
        $j(".courseCountryScrollbarHeight").height("135px");
        applyScrollBar(scrollbarId);
    } );
</script>
<div class="signup-form clearwidth" id="oneStepAroad_<?php echo $regFormId; ?>">
    <form action="<?php echo $action; ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
        <div class="signup-field-sec clearwidth">
        <input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
        <?php
            $this->load->view('registration/fields/LDB/variable/oneStepAbroad');
        ?>
            <div class="signup-fields clearwidth" style="border-bottom:0 none;">
                <?php 
                    if(!$formData['userId']) {
                ?>
                    <p class="font-10" style="float:left; margin-top:18px;">I agree to the <a onclick="return popitup('https://www.shiksha.com/shikshaHelp/ShikshaHelp/termCondition');" href="javascript:void(0);">terms of services</a> and <a onclick="return popitup('https://www.shiksha.com/shikshaHelp/ShikshaHelp/privacyPolicy');" href="javascript:void(0);">privacy policy</a>.</p>
                <?php 
                    } 
                ?>
                <ul>
		 <!--   
                    <div class="flLt signup-txtwidth captcha-text">
                    <?php 
                        if(!$formData['userId']) {
                            //$this->load->view('registration/fields/securityCode');
                        } 
                    ?>
                    </div>-->
                    <div class="flRt signup-txtwidth">
						<?php if($fileName != '' && ($courseGroup == 'studyAbroad' || $courseGroup == 'studyAbroadRevamped')){ ?>
                        <li>
                            <a href="javascript:void(0);" onclick="studyAbroadTrackEventByGA('ABROAD_PAGE', 'onestepSignup'); return shikshaUserRegistrationForm[<?php echo "'$regFormId'"; ?>].submitForm();" class="button-style big-button" style="padding:15px 15px; font-size:14px;">Get a free profile evaluation call</a>
                        </li>
						<?php } else { ?>
						<li>
                            <a href="javascript:void(0);" onclick="studyAbroadTrackEventByGA('ABROAD_PAGE', 'onestepSignup'); return shikshaUserRegistrationForm[<?php echo "'$regFormId'"; ?>].submitForm();" class="button-style big-button" style="padding:12px 74px; font-size:18px; /*margin-top:22px;*/">Register Now</a>
                        </li>
						<?php } ?>
                    </div>
                    
                </ul>
            </div>
        </div>
    <?php
        if($userIdOffline){
            echo "<input type='hidden' id='userId' name='userId' value='$userIdOffline' />";
            echo "<input type='hidden' id='userIdOffline' name='userIdOffline' value='$userIdOffline' />";
        }
    ?>
	    <input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
	    <input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
	    <input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo ($courseGroup == 'studyAbroad' || $courseGroup == 'studyAbroadRevamped') ? 'yes' : 'no'; ?>' />
	    <input type='hidden' id='registrationSource' name='registrationSource' value='abroadTwoStep' />
	    <input type='hidden' id='referrer' name='referrer' value='<?php echo htmlentities($this->security->xss_clean($customReferer ? $customReferer : $_SERVER['HTTP_REFERER']."#abroadSignUp")); ?>' />
	    <input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
	    <input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=false />
        <input type="hidden" id="saABTracking" name="saABTracking" value="yes"/> 
        <input id="tracking_keyid_<?php echo $regFormId; ?>" type="hidden" value="<?php echo $trackingPageKeyId; ?>" name="tracking_keyid">
        <?php
            $CI = & get_instance();
            $CI->load->library('security');
            $CI->security->setCSRFToken();
        ?>
        <input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
    </form>
</div>

<?php $this->load->view('registration/common/jsInitialization'); ?>
<script type="text/javascript">
if(typeof(shikshaUserRegistrationForm['<?=$regFormId?>']) != 'undefined'){ 
   shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].triggerISDCodeOnchange('<?php echo INDIA_ISD_CODE; ?>');
}

</script>
