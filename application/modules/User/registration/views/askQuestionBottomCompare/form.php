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
$pageKeyForAskQuestion = 'LISTING_INSTITUTEDETAIL_ASK_INSTITUTE_POSTQUESTION';
$categoryId = 0;
if(count($categories) > 0 && is_array($categories) ){
  $categoryId = $categories[0];
}

$questionText = '';
if(isset($_COOKIE['commentContent']) && ($questionText == '')){
    $commentContentData = $_COOKIE['commentContent'];
    if((stripos($commentContentData,'@$#@#$$') !== false) && (stripos($commentContentData,'@#@!@%@') === false)){
        $questionText = str_replace("@$#@#$$","",$commentContentData);
    }
}

$questionText = (isset($questionText)&&($questionText!=''))?$questionText:'Post your questions here and get answers from current students of this college.';
$questionTextLength = strlen($questionText);

if(!empty($courses)) {
    $paid_courses_ids = array();
    foreach($courses as $paidcourse) {
        $paid_courses_ids[] = $paidcourse->getId();
    }    
}
?>
<script>
var formid = '<?php echo $regFormId; ?>';
function focusBlurForWallQuestion(func,obj){
    var id = obj.id;
    if(func == 'focus'){
        var element = document.getElementById(id).value;
        if(element =='Post your questions here and get answers from current students of this college.'){
            document.getElementById(id).value = "";
        }
        document.getElementById('hiddenFormPartAskInstitute').style.display = 'inline';
        document.getElementById('registrationFormMiddle_'+formid).style.display = 'inline';
        document.getElementById('submitbutton_'+formid).style.display = 'inline';
	
	var courseId = $j('#course_'+formid).val();
	var isCustomCourse = iscustomizedCourse(courseId);
	var widget = $j('#widget_'+formid).val();
	var instituteId = $j('#institute_id_'+formid).val();
	//var CurrentCityObject = $j('#residenceCityLocality_<?php echo $regFormId; ?>');
	var cityLoggedIn_id = $j('#residenceCityLocality_<?php echo $regFormId; ?>').val();
	
	if (!(isCustomCourse)) {
	if ( cityLoggedIn_id != "" && $j('#preferred_city_category_'+widget+instituteId).length > 0) {
		$j('#preferred_city_category_'+widget+instituteId).val(cityLoggedIn_id);
		if ($j('#preferred_locality_category_'+widget+instituteId).val() == "") {
		    $j('#preferred_city_category_'+widget+instituteId).change();
		    $j('#locality-div_'+widget).show();		    
		}
		
		}else{
		    $j('#preferred_city_category_'+widget+instituteId).change(); 
		}
	}
        if (isCustomCourse) {
		if ($j( "#preferred_city_category_"+widget+instituteId).length > 0) {
		    $("preferred_city_category_"+widget+instituteId).style.display = 'block';
		    $j( "#preferred_city_category_"+widget+instituteId).parent().css( "display", "block" );
		    $('preferred_city_category_'+widget+instituteId).style.display = 'block';
		    $j('#locality-div_<?=$widget?>').show();
		    
		    if ($('preferred_city_category_'+widget+instituteId).value != "") {
			$('preferred_city_category_'+widget+instituteId).style.display = 'block';
		    }else{
			$j('#preferred_locality_category_'+widget+instituteId).parent().hide();
		    }
		}
	}
    }else{
        document.getElementById('hiddenFormPartAskInstitute').style.display = 'none';
        document.getElementById('registrationFormMiddle_'+formid).style.display = 'none';
        document.getElementById('submitbutton_'+formid).style.display = 'none';
    }
}


function validateQuestion(formElement) {
    var validateFieldsFocusElement = null;
    var returnFlag = true;
	var errorDiv = undefined;        
    var methodName = formElement.getAttribute('validate');
	var  textBoxContent = trim(formElement.value);
    if((formElement.getAttribute('validateSingleChar')) && (formElement.getAttribute('validateSingleChar') == 'true')){
        var newStr = textBoxContent.charAt(0).toUpperCase() + textBoxContent.substr(1);
		if(textBoxContent.substring(0,7)=='https://' || textBoxContent.substring(0,8)=='https://'){
			var newStr = textBoxContent;
		}

    	var allowedPattern = /([^A-Za-z0-9\/(\n)\r\t])\1+/gi;
		var newStr1 = newStr.replace(allowedPattern, '$1');
		var notAllowedPattern = /[\/]{2,}/gi;
    	var newStr2 = newStr1.replace(notAllowedPattern, '//');
    	formElement.value = newStr2;
		textBoxContent = trim(formElement.value);
    }

    textBoxContent = stripHtmlTags(textBoxContent);
    var flagForContent = true;
    if((typeof(formElement.type) != 'undefined') && ((formElement.type=='select-one') || (formElement.type=='select-multiple'))){
    	var flagForContent = false;
	}
	if(flagForContent){
        formElement.value = textBoxContent;
	}
	var strictCheck = false;
    if((formElement.getAttribute('validateSpecial')) && (formElement.getAttribute('validateSpecial') == 'strict')){
        var strictCheck = true;
		textBoxContent = escape(textBoxContent);
	}
	if(!strictCheck){
		textBoxContent = textBoxContent.replace(/[(\n)\r\t\"\'\\]/g,' ');
		textBoxContent = textBoxContent.replace(/[^\x20-\x7E]/g,'');
	}
    var textBoxMaxLength  = formElement.getAttribute('maxlength');
    var textBoxMinLength  = formElement.getAttribute('minlength');
    var displayprop = formElement.style.display ;
    var caption;
    try{
        caption  = formElement.getAttribute('caption');
    } catch(e){
        caption = 'field';
    }
    if(methodName == "validateCheckedGroup"){
        errorDiv = $(formElement.id.substring(0,formElement.id.length-1)+"_error");
    }else{
        errorDiv = $(formElement.id +'_error');
        if(formElement.id == "selected_course_listingPageTopLinks" || formElement.id == "selected_course_listingPageBottomNew")
        {
            errorDiv.style.display = 'block';
        }
        
    }
    if((!checkRequired(formElement) || displayprop == "none")) {
        if(displayprop == "none") {
            errorDiv.parentNode.style.display = 'none';
            errorDiv.innerHTML = '';
        }
        return returnFlag;
    }
		
    // case of checkbox required
    if((typeof(formElement.type) != 'undefined') && ((formElement.type=='checkbox'))){
        textBoxContent = formElement.checked;
    }
			
    if((typeof(formElement.type) != 'undefined') && ((formElement.type=='checkbox')||(formElement.type=='radio')) && methodName == "validateCheckedGroup"){
        var eleName = formElement.getAttribute('name');
        var elemensCheck = document.getElementsByName(eleName);
        textBoxContent = false;
        for(var i=0;i< elemensCheck.length ;i++){
            textBoxContent = elemensCheck[i].checked;
            if(textBoxContent){
                break;
            }
        }
    }
			
    if(formElement.getAttribute('allowNA') &&  textBoxContent == 'NA') {
        validationResponse = true;
    }
    else {
        var multipleValidateMethods = methodName.split(',');
        
        for(var multipleValidateMethodCount = 0, validateMethodName;validateMethodName = multipleValidateMethods[multipleValidateMethodCount++];) {
            validateMethodName = trim(validateMethodName);
            var methodSignature = validateMethodName+ '("'+ textBoxContent +'", "'+ caption +'", '+ textBoxMaxLength +', '+ textBoxMinLength +',' + formElement.getAttribute('required') + ')';
                    validationResponse = eval(methodSignature);
                    if(validationResponse !== true) { break; }
        }
    }

			
    if(validationResponse !== true) {			
        errorDiv.parentNode.style.display = 'inline';
        errorDiv.style.display = 'block';
        errorDiv.innerHTML = validationResponse;
        if ( validateFieldsFocusElement === null ) {
            validateFieldsFocusElement = $(formElement.id);
        }
        returnFlag = false;
    } else { 
        
        errorDiv.parentNode.style.display = 'none';
        errorDiv.innerHTML = '';
        if($(formElement.id +'_reputationVal')){
            if(parseInt($(formElement.id +'_reputationVal').innerHTML)==0 && caption=='Comment'){
                errorDiv.style.display = 'inline';
                errorDiv.innerHTML = 'You can not comment because you have zero Reputation Index.';
                returnFlag = false;
                
            } 
        }
        if(!checkProfanity(formElement, caption)) {
            returnFlag = false;
        }
    }
    
    try{
        if ( validateFieldsFocusElement !== null ){
            validateFieldsFocusElement.focus();
            validateFieldsFocusElement  = null;
        }
    } catch(e) {}
    return returnFlag;
    
}
</script>
<?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>
<div id="registration-box">
<form action="<?php echo $action; ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
    <input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
    <ul class="ask-qstn-list" id="questionTextDiv">
    	<li>
        	<textarea  autocomplete="off" value="<?php echo $questionText; ?>" onkeyup="if(event.keyCode == 13){ return false;} else { textKey(this); }" onkeypress="if(event.keyCode == 13){ return false;} else { textKey(this); }"  profanity="true" caption="Question" maxlength="140" minlength="2" required="true" style="height:45px;<?php if ($questionText == '') { echo 'color:#565656;'; } ?>" default="<?php echo $askInstituteHelpTip; ?>" onfocus="trackEventByGA('LinkClick','COMPARE_PAGE_QNA_TAB'); focusBlurForWallQuestion('focus',this);" id="ask_question_<?=$widget?>" name="ask_question_<?=$widget?>" validate="validateStr" ><?php if ($questionText != '') { echo $questionText;} else { echo $askInstituteHelpTip; } ?></textarea>
            <p class="char-limit" ><span id="ask_question_<?=$widget?>_counter"> 0 </span> out of 140 character</p>
			<div class="errorPlace" style="display:none"><div class="errorMsg" id="ask_question_<?=$widget?>_error"></div></div>            
        </li>
    </ul>
    
<ul class="ask-que-list" style="display:none;" id="hiddenFormPartAskInstitute">
<?php
$this->load->view('registration/fields/LDB/userPersonalDetails');
?>
    
<?php
if(count($instituteCoursesLPR) >= 1){
    if( $pageType == 'course') {    
?>
    <li style="display:none">
        <div class="custom-dropdown">
            <select name="course" id="course_<?php echo $regFormId; ?>" caption="the course" label="Course" mandatory="1" regfieldid="course" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeCourse();">
                <option value="">Select Course</option>
                <?php foreach ($instituteCoursesLPR as $key => $corse) { ?>
                <option desiredCourseId="<?= $corse['desiredCourse']; ?>" value="<?=$key;?>" categoryId="<?=$corse['categoryId'];?>" <?php if($defaultCourseId == $key) echo "selected='selected'" ?>><?= $corse['name'];?></option>
                <?php } ?>
            </select>
        </div>

        <div>
            <div class="regErrorMsg" id="course_error_<?php echo $regFormId; ?>"></div>
        </div>
    </li>
    <input type='hidden' id='fieldOfInterest_<?php echo $regFormId; ?>' name='fieldOfInterest' value='<?php echo $defaultCategory; ?>' />
    <input type='hidden' id='desiredCourse_<?php echo $regFormId; ?>' name='desiredCourse' value='<?php echo $defaultCourse; ?>' />
    <input type="hidden" id="courseId_<?=$widget?>"  name="courseId"  value="<?=$course->getId()?>"/>	    

<?php
    } elseif( $pageType == 'institute'){
        unset ($defaultCourseId);
?>
    <li style="display: <?=($pageType == 'institute'?'inline-block':'none')?>;">
        <div class="custom-dropdown">
            <select name="course" id="course_<?php echo $regFormId; ?>" caption="the course" label="Course" mandatory="1" regfieldid="course" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeCourse();">
                <option value="">Select Course</option>
                <?php
                foreach ($instituteCoursesLPR as $key => $corse) {
                    if(in_array($key,$paid_courses_ids) || $coursePageCategoryIdentifier == 'MBA_PAGE') {
                    ?>
                        <option desiredCourseId="<?= $corse['desiredCourse']; ?>" value="<?=$key;?>" categoryId="<?=$corse['categoryId'];?>" <?php if($defaultCourseId == $key) echo "selected='selected'" ?>><?= $corse['name'];?></option>
                <?php }
                } ?>
            </select>
        </div>
        <div>
            <div class="regErrorMsg" id="course_error_<?php echo $regFormId; ?>"></div>
        </div>
    </li>
    <input type='hidden' id='fieldOfInterest_<?php echo $regFormId; ?>' name='fieldOfInterest' value='<?php echo $defaultCategory; ?>' />
    <input type='hidden' id='desiredCourse_<?php echo $regFormId; ?>' name='desiredCourse' value='<?php echo $defaultCourse; ?>' />
    <input type="hidden" id="courseId_<?=$widget?>"  name="courseId"  value=""/>	    
<?php
    }
} else{
?>
    <input type='hidden' id='fieldOfInterest_<?php echo $regFormId; ?>' name='fieldOfInterest' value='<?php echo $defaultCategory; ?>' />
    <input type='hidden' id='desiredCourse_<?php echo $regFormId; ?>' name='desiredCourse' value='<?php echo $defaultCourse; ?>' />
    <input type='hidden' id="course_<?php echo $regFormId; ?>" name='course' value='<?php echo $courseIdSelected; ?>' />
    <input type="hidden" id="courseId_<?=$widget?>"  name="courseId"  value=""/>	
<?php
    }
?>
        
</ul>
    
<ul style="display:none;" id="registrationFormMiddle_<?php echo $regFormId; ?>">
<?php $this->load->view('registration/fields/LDB/variable/default'); ?>
</ul>
<ul>
    <li id="locality-div_<?=$widget;?>" style="display:none;"></li>
</ul>

<ul style="display:none;" id="submitbutton_<?php echo $regFormId;?>">
<?php
if(!$formData['userId']) {
    //$this->load->view('registration/fields/securityCode');
}
?>

<li>
<?php if($isFullRegisteredUser): ?>
    <a href="javascript:void(0);" type="button" id="registrationSubmit_<?php echo $regFormId; ?>" value="<?php echo $buttonText ? $buttonText : 'Download E-Brochure';?>" class="big-button orange-btn2" onclick="trackEventByGA('ASK_NOW_BUTTON','COMPARE_PAGE_QNA_TAB');return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitResponse();return false;" ><?php echo $buttonText ? $buttonText : 'Ask Now';?></a>
<?php else: ?>
    <input type="submit" id="registrationSubmit_<?php echo $regFormId; ?>" value="<?php echo $buttonText ? $buttonText : 'Ask Now';?>" class="orange-button" onclick="trackEventByGA('ASK_NOW_BUTTON','COMPARE_PAGE_QNA_TAB');return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm()" />
<?php endif; ?>
&nbsp;&nbsp;&nbsp;<span id="cacelLinkContainer_ForAskInstitute" style="display:none;"><a href="javascript:void(0);" onClick="javascript:askInstitute.callOnFocusOnBlurFunctions('blur');" class="fontSize_12p">Cancel</a></span>
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
<input type='hidden' id='institute_id_<?=$regFormId;?>' name='institute_id' value='<?=$instituteId;?>' />
<input type='hidden' id='institute_name_<?=$regFormId;?>' name='institute_name' value='<?=$instituteName;?>' />
<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
<input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=true />
<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value="<?php echo $trackingPageKeyId;?>"> 
<?php if($formData['coursePageSubcategoryId']) { ?>
<input type='hidden' id='coursePageSubcategoryId_<?php echo $regFormId; ?>' name='coursePageSubcategoryId' value='<?php echo $formData['coursePageSubcategoryId']; ?>' />
<?php } ?>


<input type="hidden" name ="instituteId" id="instituteIdForAskInstitute" value="<?php echo $instituteId; ?>" />
<input type="hidden" name ="categoryId" id="categoryIdForAskInstitute" value="<?php echo $categoryId; ?>" />
<input type="hidden" name ="locationId" id="locationIdForAskInstitute" value="<?php echo $locationId; ?>" />
<input type="hidden" name="secCodeIndex" value="seccodeForAskInstitute" />
<input type="hidden" name="loginproductname_ForAskInstitute" value="<?php echo $pageKeyForAskQuestion; ?>" />
<input type="hidden" name="referer_ForAskInstitute" id="referer_ForAskInstitute" value="" />
<input type="hidden" name="resolution_ForAskInstitute" id="resolution_ForAskInstitute" value="" />
<input type="hidden" name="coordinates_ForAskInstitute" id="coordinates_ForAskInstitute" value="" />
<input type="hidden" name="askUrl_floatingRegistration" id="askUrl_floatingRegistration" value="<?php echo $coursUrl?>" />

<input type="hidden" id="instituteId_<?=$widget?>" name="instituteId_<?=$widget?>" value="<?=$instituteId?>">
<input type="hidden" id="locationId_<?=$widget?>" name="locationId_<?=$widget?>" value="<?=$locationId?>">
<input type="hidden" id="categoryId_<?=$widget?>"  name="categoryId"  value="<?=$categoryId?>"/>
<input type="hidden" name="getmeCurrentCity" id="getmeCurrentCity" value="<?php echo $currentLocation->getCity()->getId();?>"/>
<input type="hidden" name="getmeCurrentLocaLity" id="getmeCurrentLocaLity" value="<?php echo $currentLocation->getLocality()->getId();?>"/>
</form>
</div>
<div style="clear:both;"></div>

<?php 
if($defaultCourse > 0) {
    if(isset($formData['desiredCourse'])) {
        unset($formData['desiredCourse']); 
    }
    if(isset($formData['fieldOfInterest'])){
        unset($formData['fieldOfInterest']);
    }
}
?>
<?php
$customValidations = json_encode($registrationHelper->getCustomValidations());
$defaultFieldStates = json_encode($registrationHelper->getDefaultFieldStates());
$newA = file_get_contents("public/blacklisted.txt");
?>

<script>
var blacklistWords = new Array(<?php echo $newA;?>);
function regFormLoad<?=$widget;?> (){
    
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'] = new ShikshaUserRegistrationForm('<?php echo $regFormId; ?>');
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo $customValidations; ?>);
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setFormFieldList(<?php echo json_encode(array_keys($fields)); ?>);
    registrationCustomLogic['<?php echo $regFormId; ?>'] = new RegistrationCustomLogic('<?php echo $regFormId; ?>',<?php echo $customRules ? $customRules : "[]"; ?>,<?php echo $defaultFieldStates; ?>);
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setCustomLogic(registrationCustomLogic['<?php echo $regFormId; ?>']);
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateForm(<?php echo json_encode($formData); ?>,true);
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].triggerISDCodeOnchange('<?php echo INDIA_ISD_CODE; ?>');
    
    <?php if(!$mmpFormId) { ?>
        if(document.attachEvent) { //Internet Explorer
          document.attachEvent("onclick", function() {shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].hideAllLayers();});
        }
        else if(document.addEventListener) { //Firefox & company
          document.addEventListener('click', function() {shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].hideAllLayers();}, false); 
        }
    <?php } ?>
    
    if($('course_<?php echo $regFormId; ?>').value != ''){
        shikshaUserRegistrationForm['<?php echo $regFormId;?>'].changeDesiredCourse();
    }
    
    shortRegistrationFormResponse = {
                                            instituteId:document.getElementById('institute_id_<?=$regFormId?>').value,
                                            instituteName:base64_decode(document.getElementById('institute_name_<?=$regFormId?>').value),
                                            courseId:$('course_<?=$regFormId?>').value
                                            
                                        };
                                        
    if(typeof shortRegistrationFormResponse !== 'undefined'){
        var tempHTML = addLocalityInApplyForm('<?=$instituteId?>','<?=$widget?>',$('course_<?=$regFormId?>').value,"new");
        $j('#locality-div_<?=$widget?>').html(tempHTML);
        $city = $j('#preferred_city_category_<?=$widget.$instituteId?>');
        $city.trigger('change');
	$j('#locality-div_<?=$widget?>').hide();
    }
}

var STUDY_ABROAD_NEW_REGISTRATION = '<?php echo STUDY_ABROAD_NEW_REGISTRATION ?>';


</script>