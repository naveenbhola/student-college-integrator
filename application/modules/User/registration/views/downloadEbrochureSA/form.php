<?php 
    $action = '/registration/Registration/register';
    if(!empty($abroadShortRegistrationData)) {
        $action = '/registration/Registration/updateUser';
    }
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
    $j("#twoStepCountrySelect").one( "click", function() {
        $j(".courseCountryScrollbarHeight").height("135px");
        applyScrollBar(scrollbarId);
    } );
    
    function universityCourseSelectChange(){
        if($j("#university_course_list_select").val() == ''){
            $j('#university_course_list_error').html('Please select a course');
        }else{
            $j('#university_course_list_error').html('');
        }
        $j("#listingTypeIdForBrochure").val($j("#university_course_list_select").find('option:selected').attr('clientCourseId'));
        $j("#desiredCourseForResponse").val($j("#university_course_list_select").val());
        $j("#isPaid").val($j("#university_course_list_select").find('option:selected').attr('ispaid'));
        $j("#abroadSpecializationForResponse").val($j("#university_course_list_select").find('option:selected').attr('subcategory'));
        triggerConsultantLayer($j("#university_course_list_select"),'<?=$regFormId?>');
    }
	
	function showEducationFields(obj) {
		var formId = '<?=$regFormId?>';
		var ldbCourseId = $j("#registrationForm_"+formId+" [name='desiredCourse']").val();
		shikshaUserRegistrationForm[formId].getSAshikshaApplyFields({'ldbCourseId':ldbCourseId,'context':'oneStepAbroad'});
	}
</script>
<?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>
<div class="signup-form clearwidth" id="oneStepAroad_<?php echo $regFormId; ?>">
    <form action="<?php echo $action; ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
        <div class="signup-field-sec clearwidth">
		<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
        <?php
            $this->load->view('registration/fields/LDB/variable/oneStepAbroadResponse');
            if(!empty($consultantRelatedData['consultantData'])){
                $this->load->view('consultantEnquiry/consultantEnquiryResponseForm');
            }
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
                  <!--  <div class="flLt signup-txtwidth captcha-text">
                    <?php 
                        if(!$formData['userId']) {
                            //$this->load->view('registration/fields/securityCode');
                        } 
                    ?>
                    </div>
		    -->
                    <div class="flRt signup-txtwidth">
                        <li>
                            <a href="javascript:void(0);" onclick="studyAbroadTrackEventByGA('ABROAD_PAGE', 'brochureForm_<?=$widget?>','download_now'); return shikshaUserRegistrationForm[<?php echo "'$regFormId'"; ?>].submitForm();" class="button-style big-button" style="padding:12px 52px; font-size:18px; /*margin-top:22px;*/"><?php if($widget!='request_callback'){?><i class="common-sprite download-icon2-a"></i><?php }?><?=($widget=='request_callback'?'Submit':'Download Now')?></a>
                        </li>
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
	    <input type='hidden' id='referrer' name='referrer' value='<?php echo htmlentities($this->security->xss_clean($customReferer ? $customReferer : $_SERVER['HTTP_REFERER']."#abroadDownloadBrochure")); ?>' />
	    <!-- fields required for response creation in case of download brochure -->
	    <input type='hidden' id='responseSource' name='responseSource' value='<?=$responseSourcePage?>' />
	    <input type='hidden' id='listingTypeForBrochure' name='listingTypeForBrochure' value='<?=$listingTypeForBrochure?>' />
	    <input type='hidden' id='listingTypeIdForBrochure' name='listingTypeIdForBrochure' value='<?=$clientCourseId?>' />
	    <input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
	    <input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=false />
        <input type="hidden" id='tracking_keyid_<?php echo $regFormId; ?>' name='tracking_keyid' value=<?php echo $tracking_page_key ?> />
        <input id="consultant_tracking_keyid_<?php echo $regFormId; ?>" type="hidden" value="<?php echo $consultantTrackingPageKeyId; ?>" name="consultant_tracking_keyid">
        <input type="hidden" id='saABTracking' name='saABTracking' value="yes" />

        <?php
            $CI = & get_instance();
            $CI->load->library('security');
            $CI->security->setCSRFToken();
        ?>
        <input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
	    
    </form>
</div>


<?php 
    if(!empty($consultantRelatedData['consultantData'])){
?>
    <script>
    if(typeof(consultantInRegistration) !== 'undefined' && consultantInRegistration == true && !isNaN(loggedInUserCity)){
        //console.log(loggedInUserCity);
        showConsultantLayerForm('<?=$regFormId?>',loggedInUserCity);
    }
</script>
<?php }?>
<?php $this->load->view('registration/common/jsInitialization'); ?>
<script type="text/javascript">
if(typeof(shikshaUserRegistrationForm['<?=$regFormId?>']) != 'undefined' &&  $('isdCode_<?=$regFormId?>').value != '91-2'){ 
    $j('#isdCode_<?=$regFormId?>').trigger('change');
}

</script>
