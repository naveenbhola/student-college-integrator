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
<div id="registration-box">
<form action="<?php echo $action; ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
<ul>
<?php
$this->load->view('registration/fields/LDB/userPersonalDetails');
?>
    
<?php
    if(count($instituteCourses) > 1){
?>
    <li>
        <div class="custom-dropdown">
            <select name="course" id="course_<?php echo $regFormId; ?>" caption="the course" label="Course" mandatory="1" regfieldid="course" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeCourse();">
                <option value="">Select Course</option>
                <?php foreach ($instituteCourses as $key => $course) { ?>
                <option desiredCourseId="<?= $course['desiredCourse']; ?>" value="<?=$key;?>" categoryId="<?=$course['categoryId'];?>" <?php if($defaultCourseId == $key) echo "selected='selected'" ?>><?= $course['name'];?></option>
                <?php } ?>
            </select>
        </div>
        <div>
            <div class="regErrorMsg" id="course_error_<?php echo $regFormId; ?>"></div>
        </div>
    </li>
    <input type='hidden' id='fieldOfInterest_<?php echo $regFormId; ?>' name='fieldOfInterest' value='<?php echo $defaultCategory; ?>' />
    <input type='hidden' id='desiredCourse_<?php echo $regFormId; ?>' name='desiredCourse' value='<?php echo $defaultCourse; ?>' />
<?php
    }else{
?>
    <input type='hidden' id='fieldOfInterest_<?php echo $regFormId; ?>' name='fieldOfInterest' value='<?php echo $fieldOfInterest; ?>' />
    <input type='hidden' id='desiredCourse_<?php echo $regFormId; ?>' name='desiredCourse' value='<?php echo $desiredCourse; ?>' />
    <input type='hidden' id="course_<?php echo $regFormId; ?>" name='course' value='<?php echo $courseIdSelected; ?>' />
<?php
    }
?>
    
</ul>
    
<ul id="registrationFormMiddle_<?php echo $regFormId; ?>">
<?php $this->load->view('registration/fields/LDB/variable/default'); ?>
</ul>
<ul>
    <li id="locality-div_<?=$widget;?>" style="display:none;"></li>
</ul>

<ul>

<li>
<p id="success_msg_<?php echo $regFormId; ?>" class="success-msg" style="margin-bottom:5px; display:none;color: #52935c; font-size:12px; line-height:22px;"><span style="font-size:20px;position:relative; top:4px;">&#10003</span>E-Brochure successfully mailed.</p>
<?php if($isFullRegisteredUser): ?>
    <input type="button" id="registrationSubmit_<?php echo $regFormId; ?>" value="<?php echo $buttonText ? $buttonText : 'Submit';?>" class="orange-button" onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitResponse();return false;" />
<?php else: ?>
    <input type="submit" id="registrationSubmit_<?php echo $regFormId; ?>" value="<?php echo $buttonText ? $buttonText : 'Submit';?>" class="orange-button" onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm()" />
<?php endif; ?>
</li>

<div class="clearFix"></div>
</ul>
    
<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
<input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
<input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo $courseGroup == 'studyAbroad' ? 'yes' : 'no'; ?>' />
<input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo 'response'; ?>' />
<input type='hidden' id='referrer' name='referrer' value='<?php echo $referrer ? $referrer : $_SERVER['HTTP_REFERER'].'#'.$widget; ?>' />
<input type='hidden' id='pageReferrer' name='pageReferer' value='<?php echo htmlentities(strip_tags($_SERVER['HTTP_REFERER'])); ?>' />
<input type='hidden' id='fieldsView' name='fieldsView' value='default' />
<input type='hidden' id='isMR' name='isMR' value='YES' />
<input type='hidden' id='userId' name='userId' value='<?=$formData['userId'];?>' />
<input type='hidden' id='widget_<?php echo $regFormId; ?>' name='widget' value='<?=$widget;?>' />
<input type='hidden' id='customCallBack_<?php echo $regFormId; ?>' name='customCallBack' value='<?=$customCallBack;?>' />
<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
<input type='hidden' id='institute_id_<?=$regFormId;?>' name='institute_id' value='<?=$instituteId;?>' />
<input type='hidden' id='institute_name_<?=$regFormId;?>' name='institute_name' value='<?=$instituteName;?>' />
<input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=false />
<input type='hidden' id='isCategoryPage_<?php echo $regFormId; ?>' name='isCategoryPage' value='<?=$isCategoryPage;?>' />
<?php if($formData['coursePageSubcategoryId']) { ?>
<input type='hidden' id='coursePageSubcategoryId_<?php echo $regFormId; ?>' name='coursePageSubcategoryId' value='<?php echo $formData['coursePageSubcategoryId']; ?>' />
<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
<?php } ?>
<?php if($tracking_keyid) { ?>
<input type='hidden' id='tracking_keyid_<?php echo $regFormId; ?>' name='tracking_keyid' value='<?php echo $tracking_keyid; ?>' />
<?php } else {?>
<input type='hidden' id='tracking_keyid_<?php echo $regFormId; ?>' name='tracking_keyid' value='<?=$trackingPageKeyId;?>' />
<?php } ?>
</form>
</div>
<?php 
if($defaultCourse > 0 || $desiredCourse > 0) {
    if(isset($formData['desiredCourse'])) {
        unset($formData['desiredCourse']); 
    }
    if(isset($formData['fieldOfInterest'])){
        unset($formData['fieldOfInterest']);
    }
}
?>
<div style="clear:both;"></div>

 <!--Js initialization code - start--> 
<?php
    $customValidations = json_encode($registrationHelper->getCustomValidations());
    $defaultFieldStates = json_encode($registrationHelper->getDefaultFieldStates());
    $newA = file_get_contents("public/blacklisted.txt");
?>
<script>
    var blacklistWords = new Array(<?php echo $newA;?>);
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'] = new ShikshaUserRegistrationForm('<?php echo $regFormId; ?>');
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo $customValidations; ?>);
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setFormFieldList(<?php echo json_encode(array_keys($fields)); ?>);
    registrationCustomLogic['<?php echo $regFormId; ?>'] = new RegistrationCustomLogic('<?php echo $regFormId; ?>',<?php echo $customRules ? $customRules : "[]"; ?>,<?php echo $defaultFieldStates; ?>);
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setCustomLogic(registrationCustomLogic['<?php echo $regFormId; ?>']);
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateForm(<?php echo json_encode($formData); ?>,true);
<?php if(!$mmpFormId) { ?>
    if(document.attachEvent) { //Internet Explorer
      document.attachEvent("onclick", function() {shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].hideAllLayers();});
    }
    else if(document.addEventListener) { //Firefox & company
      document.addEventListener('click', function() {shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].hideAllLayers();}, false); 
    }
<?php } ?>

    var STUDY_ABROAD_NEW_REGISTRATION = '<?php echo STUDY_ABROAD_NEW_REGISTRATION ?>';
</script>
<!--Js initialization code - end--> 

<script>
    <?php if($widget != 'listingPageBottomNew' && $widget != 'listingPageTopLinks') { ?>
    if($('course_<?php echo $regFormId; ?>').value != '') {
        shikshaUserRegistrationForm['<?php echo $regFormId;?>'].changeDesiredCourse();
    }else{
        shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].triggerISDCodeOnchange('<?php echo INDIA_ISD_CODE; ?>');
    }
    <?php } ?>

   
   
    //if(typeof shortRegistrationFormResponse === 'undefined') {
       
	shortRegistrationFormResponse = {
					    instituteId:document.getElementById('institute_id_<?=$regFormId?>').value,
					    instituteName:base64_decode(document.getElementById('institute_name_<?=$regFormId?>').value),
					    courseId:$('course_<?=$regFormId?>').value
					};
    //}
    
    
    if(typeof shortRegistrationFormResponse !== 'undefined'){
        
            var tempHTML = addLocalityInApplyForm(shortRegistrationFormResponse['instituteId'],'<?=$widget?>',shortRegistrationFormResponse['courseId'],"new",0, '<?=$regFormId?>');
            $j('#locality-div_<?=$widget?>').html(tempHTML);
            $city = $j('#preferred_city_category_<?=$widget?>'+shortRegistrationFormResponse['instituteId']);
            if(checkMultilocationCourses(shortRegistrationFormResponse['courseId'])){
                setTimeout(function(){
                    $j('#residenceCityLocality_<?=$regFormId?>').trigger('change');
                },250);
            }
            
            <?php if($formData['userId'] > 0) {  ?>
                if($j('#course_<?=$regFormId?>').is(':visible')){ 
                    $j('#isdCode_<?=$regFormId?>').trigger('change');
                }
            <?php } ?>

            $city.trigger('change');
            <?php if(!$instituteId){ ?>
                $j('#institute_id_<?=$regFormId;?>').val(shortRegistrationFormResponse['instituteId']);
            <?php } ?>
            <?php if(!$instituteName){ ?>
                $j('#institute_name_<?=$regFormId;?>').val(shortRegistrationFormResponse['instituteName']);
            <?php } ?>
    }
    
    var showDivLayer = <?php if($multkeyname == 'CategoryPageApplyRegisterButton' || $multkeyname == 'StudyAbroadApplyRegisterButton') { echo 1; } else { echo 0; } ?>;
    var isRankingPage = <?php if($multkeyname=="RANKING_PAGE"){ echo "1";} else {echo "0";} ?>;
    var isMyShortlistPage = <?php if($multkeyname=="MY_SHORTLIST"){ echo "1";} else {echo "0";} ?>;
    var isSearchV2ResultPage = <?php if($multkeyname=="ND_SRP_Request_E_Brochure"){ echo "1";} else {echo "0";} ?>;
    var isFullRegisteredUser = <?php if($isFullRegisteredUser == true) { echo 'true'; } else { echo 'false'; } ?>;


</script>

