<?php
//common
$data['destinationCountry'] = $destinationCountryId;
$data['destinationCountryName'] = $destinationCountryName;
$data['pageReferer'] = $sourcePage;
$data['responseSourcePage'] = $responseSourcePage;
$courseList = $courseData;
$data['listingTypeForBrochure'] = 'course';
$data['tracking_page_key'] = $tracking_page_key ;
$data['consultantTrackingPageKeyId'] = $consultantTrackingPageKeyId;
if(count($courseList) == 1){
    $data['clientCourseId'] = reset(array_keys($courseList));
    $desiredCourse = reset($courseList);
    $data['desiredCourse'] = $desiredCourse['desiredCourse'];
    $data['isPaid'] = $desiredCourse['paid'];
    $data['specialization'] = $desiredCourse['subcategory'];
}
$data['widget'] = $widget;

//_p($courseData);
//already loggedin userdata
if($userData != "false" && !empty($userData)){
    // user is logged in
    $firstname  = $userData[0]['firstname'];
    $lastname   = $userData[0]['lastname'];
    $mobile     = $userData[0]['mobile'];
    $cookiestr  = $userData[0]['cookiestr'];
    $loggedIn   = "true";
    // no need for form validations
    $required   = 'required=false';
    // no need to show the fields if when to start & education were both filled , show otherwise
    if($showWhenPlanToGo == false && $showExams == false)
    {
        $extraFieldsUnavailable = 1;
        $elemDisplay= 'display:none';
        $disabled = ' disabled="disabled" ';
        $disabledClass = ' disable-field ';
    }
    else{
        $elemDisplay = '';
        $disabled = ' disabled="disabled" ';
        $disabledClass = ' disable-field ';
    }
    $globeTop   = 'style="top:30px;"';
}
else{
    //user not logged in
    $loggedIn = "false";
    // validations required
    $required = 'required=true';
    // display  the fields
    $elemDisplay= '';
    $globeTop   = 'style="top:80px;"';
}
// prepare title for the layer
if($widget == "request_callback" ||$widget == "applicationProcessTab_request_callback" ){
    $layerTitle = "Get a call back from dedicated Shiksha counselor for ";
    $layerHeading = "The Shiksha counselor will provide answers to most frequently asked questions and can also connect you directly with the university representative.";
}
else{
    $layerTitle = "Register to download brochure ";
}
$isRequestCallback = strpos($widget,"request_callback")!==FALSE? TRUE:FALSE;
// based on source page the title will be different
switch($sourcePage)
{
    case 'course'       : $layerTitle .= ($isRequestCallback ?$courseName.' in '.$universityName : ' this course');//.$courseName;
        $listingType = $sourcePage;
        $listingTypeId = $courseId;
        $globeTop   = 'style="top:80px;"';
        $pageType   = $sourcePage.'_';
        break;
    case 'university'   : $layerTitle .= ($isRequestCallback ? $universityName:'');
        $listingType = $sourcePage;
        $listingTypeId = $universityId;
        $pageType   = $sourcePage.'_';
        break;
    case 'department'   : $layerTitle .= ($isRequestCallback ? $departmentName.' in '.$universityName:'');//$layerTitle .= 'for courses in '.$departmentName;
        $listingTypeId = $sourcePage;
        $pageType   = $sourcePage.'_';
        break;
    case 'category'     : //$layerTitle .= 'from '.$universityName; ... later
        if($isRequestCallback){
            $layerTitle .= $courseName.' in '.$universityName;
        }else{
            $layerTitle .= 'for this course';//.$courseName;
        }
        $listingType = 'course';// on category page download functionality is similar to that of course page
        $listingTypeId = $courseId;
        $globeTop   = 'style="top:80px;"';
        $pageType   = '';
        break;
    default     : // works for recommendations also that appear after a certain course's brochure is downloaded
        if($isRequestCallback){
            $layerTitle .= $courseName.' in '.$universityName;
        }else{
            $layerTitle .= 'for this course';//.$courseName;
        }
        $listingType = 'course';
        $listingTypeId = $courseId;
        if($sourcePage == 'recommendation')
        {
            $globeTop   = 'style="top:80px;"';
        }
        break;

}


if(!empty($userStartTimePrefWithExamsTaken['contactByConsultant'])){
    $data['contactByConsultant'] = $userStartTimePrefWithExamsTaken['contactByConsultant'];
}else{
    $data['contactByConsultant'] = ($loggedIn!="true")?'yes':'';
}

if($showOnlyCourseDropdown && !$isRequestCallback)
{
    if(!empty($consultantRelatedData['consultantData']) && count($courseList) <= 1){
        $layerTitle = "Select consultant";
    }else{
        $layerTitle = "Select course to download brochure";
    }
}
?>


<?php if($registrationDomain == 'studyAbroad') { ?>
    <script>
        var engageDownloadBrochureWithLogin = 0;
        var engageRequestCallbackWithLogin = 0;
    </script>
    <div class="abroad-layer" id="singleSignUpDownloadBrochureForm" style="width:620px; background:#f8f8f8 !important">
        <div class="abroad-layer-head clearfix">
            <div class="abroad-layer-logo flLt"><i class="layer-logo"></i></div>
            <a id="close-two-step-layer" href="javascript:void(0);" onclick="registrationOverlayComponent.hideOverlay(); studyAbroadTrackEventByGA('ABROAD_PAGE', 'brochureForm_<?=$widget?>', 'close'); return false;" class="common-sprite close-icon flRt"></a>
        </div>

        <div class="abroad-layer-content clearfix" style="padding:0; <?php if($OTPforReturningUser){ echo 'display:none';} ?>">

            <div class="abroad-step-title" style="background: none !important;">
                <p><?php if(empty($layerTitle)) { echo 'Register to get started'; } elseif($layerTitle == 'Select consultant'){echo ''; } else { echo $layerTitle; } ?></p>
            </div>
            <?php if($isRequestCallback){ ?>
                <div class="counselor-details" id= "counselorDetails" style = "padding-left:16px;">
                    <p>The Shiksha counselor will provide answers to most frequently asked questions and can also connect you directly with the university representative.</p>
                    <!--<p style="margin-bottom:0;"><i class="common-sprite clock-icon"></i>16 minutes <span style="color:#666">(estimated time of call back)</span></p>-->
                </div>
            <?php } ?>
            <?php if(is_array($courseList) && !empty($courseList) && count($courseList)>=1): ?>
                <div class="flLt signup-txtwidth" style="margin-left: 17px;<?=($showOnlyCourseDropdown?'margin-bottom: 20px;':'')?> width: 46%;<?=(count($courseList)==1)?'display:none':''?>">
                    <div class="custom-dropdown" id="university_course_list_select_parent">
                        <select name="university_course_list_select" class="universal-select signup-select" id="university_course_list_select" caption = "course" onchange="<?=($showOnlyCourseDropdown?'triggerConsultantLayer(this,\''.$regFormId.'\')':'universityCourseSelectChange()')?>;showEducationFields(this);">
                            <option value="">Please select a course</option>
                            <?php foreach ($courseList as $key => $value) { ?>
                                <option <?=(count($courseList)==1)?'selected="selected"':''?> ispaid = "<?=($value['paid']?1:0)?>" clientCourseId = "<?=$key?>" subcategory = "<?=$value['subcategory']?>" value="<?php echo $value['desiredCourse']; ?>"><?php echo $value['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <div class="errorMsg" id="university_course_list_error"></div>
                    </div>
                </div>
                <?php if($showOnlyCourseDropdown || (!empty($consultantRelatedData['consultantData']) && !empty($userData) && $showOnlyCourseDropdown)){
                    $this->load->view('consultantEnquiry/consultantEnquiryResponseForm');
                    ?>
                    <div class="flRt signup-txtwidth" <?php if(!empty($consultantRelatedData['consultantData']) && is_array($userData)){echo 'style="margin-top: 30px;"';}?>>

                        <a href="javascript:void(0);" onclick="validateCourseDropDownWithoutRegistration('<?=$data['listingTypeForBrochure']?>','<?=$responseSourcePage?>',this, '<?=$tracking_page_key?>', '<?= $consultantTrackingPageKeyId ?>' );" class="button-style big-button" style="padding:12px 52px; font-size:18px;margin-bottom:20px; margin-top:-10px;"><?php if($widget!='request_callback'){?><i class="common-sprite download-icon2-a"></i><?php }?><?=($widget=='request_callback'?'Submit':'Download Now')?></a>

                    </div>
                <?php } ?>
            <?php endif; ?>
            <input type='hidden' id='widget' name='widget' value='<?=$widget?>' />
            <?php
            if(!$showOnlyCourseDropdown){
                ?>
                <div class="registered-title clearwidth">
                    <strong class="flLt">Tell us about yourself</strong>
                    <?php if($loggedIn!="true") { ?>

                        <a id="twoStepLoginButton" class="flRt font-11" href="javascript:void(0);" onclick="studyAbroadTrackEventByGA('ABROAD_PAGE', 'existingUserLogin'); registrationOverlayComponent.hideOverlay(); loadStudyAbroadForm({'isStudyAbroadPage':1},'/registration/Forms/showLoginLayer','loginFormContainer',<?=$tracking_page_key?>);engageDownloadBrochureWithLogin =1;<?=($isRequestCallback?'engageRequestCallbackWithLogin = 1;':'')?> return false;">Already registered?</a></p>

                    <?php } ?>
                </div>
                <?php
                echo Modules::run('registration/Forms/LDB',"studyAbroadRevamped",'downloadEbrochureSA', $data);
            }
            ?>
        </div>
        <div >
            <?php $this->load->view('registration/common/OTP/abroadOTPVerification'); ?>
        </div>
    </div>
<?php }
if($showOnlyCourseDropdown || (!empty($consultantRelatedData['consultantData']) && !empty($userData) && $showOnlyCourseDropdown)){
    ?>
    <script>
        if(typeof(consultantInRegistration) !== 'undefined' && consultantInRegistration == true && !isNaN(loggedInUserCity)){
            //console.log(loggedInUserCity);
            showConsultantLayerForm('<?=$regFormId?>',loggedInUserCity);
        }
    </script>
<?php }?>
