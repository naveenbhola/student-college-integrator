<?php
//common
$data['destinationCountry'] = $destinationCountryId;
$data['destinationCountryName'] = $destinationCountryName;
$data['pageReferer'] = $sourcePage;
$data['responseSourcePage'] = $responseSourcePage;
$courseList = $courseData;
$data['listingTypeForBrochure'] = 'course';
$data['trackingPageKeyId'] = $tracking_page_key ;
$data['consultantTrackingPageKeyId'] = $consultantTrackingPageKeyId;
if(count($courseList) == 1){
    $data['clientCourseId'] = reset(array_keys($courseList));
    $desiredCourse = reset($courseList);
    $data['desiredCourse'] = $desiredCourse['desiredCourse'];
    $data['isPaid'] = $desiredCourse['paid'];
    $data['specialization'] = $desiredCourse['subcategory'];
}
$data['widget'] = $widget;
if(isset($OTPforReturningUser) && $OTPforReturningUser == true){
    $data['OTPforReturningUser'] = true;
}else{
    $data['OTPforReturningUser'] = false;
}

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
if($userData['userId']>0){
    $layerTitle = "Update your profile ";
}else{
    $layerTitle = "Register ";
}
$layerTitle .= "to get brochure ";
// based on source page the title will be different
switch($sourcePage)
{
    case 'course'       :
        if($widget == "request_callback" || $widget == "applicationProcessTab_request_callback"){
            $formHeading .= $courseName.' in '.$universityName;
        }
        else{
            $formHeading = $layerTitle.'for '.$courseName;
            $layerTitle .= 'for this course';//.$courseName;
        }
        $listingType = $sourcePage;
        $listingTypeId = $courseId;
        $pageType   = $sourcePage.'_';
        break;
    case 'university'   : $layerTitle .= ($widget == "request_callback" || $widget == "applicationProcessTab_request_callback" ? $universityName:'');
        $listingType = $sourcePage;
        $listingTypeId = $universityId;
        $pageType   = $sourcePage.'_';
        break;
    case 'department'   : $layerTitle .= ($widget == "request_callback" || $widget == "applicationProcessTab_request_callback" ? $departmentName.' in '.$universityName:'');//$layerTitle .= 'for courses in '.$departmentName;
        $listingTypeId = $sourcePage;
        $pageType   = $sourcePage.'_';
        break;
    case 'shortlist'    :
    case 'category'     : //$layerTitle .= 'from '.$universityName; ... later
        if($widget == "request_callback" || $widget == "applicationProcessTab_request_callback"){
            $formHeading .= $courseName.' in '.$universityName;
        }else{
            $formHeading = $layerTitle.'for '.$courseName;//this course';
        }
        $listingType = 'course';// on category page download functionality is similar to that of course page
        $listingTypeId = $courseId;
        $pageType   = '';
        break;
    default     : // works for recommendations also that appear after a certain course's brochure is downloaded
        if($widget == "request_callback" || $widget == "applicationProcessTab_request_callback"){
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

$data['formHeading'] = $formHeading;


if($showOnlyCourseDropdown && $widget != "request_callback" && $widget != "applicationProcessTab_request_callback")
{
    $layerTitle = "Select course to download brochure";
    if(count($courseList)==1){
        $layerTitle = "Get brochure of this course";
    }
}
?>

<script>
    var engageDownloadBrochureWithLogin = 0;
    var engageRequestCallbackWithLogin = 0;

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
    }
</script>
<div class="layer-header">
    <a href="Javascript:void(0);" data-rel="back" data-transition="slide" class="back-box" onclick="goBackToReferrer();"><i class="sprite back-icn"></i></a>
    <p><?php echo $layerTitle; ?></p>
</div>
<section class="content-wrap clearfix" data-enchance="false" >
    <?php if(is_null($userData['userId'])){?>
        <nav class="tabs">
            <ul>
                <li class="active reg-tab"><a href="javascript:void(0);" style = "font-size:14px;" onclick="showRegistrationFormTab(this);">Register</a></li>
                <li class="login-tab"><a href="javascript:void(0);" style = "font-size:14px;" onclick="engageDownloadBrochureWithLogin =1;<?=($widget == 'request_callback' || $widget == "applicationProcessTab_request_callback"?'engageRequestCallbackWithLogin = 1;':'')?> ;showLoginFormTab(this);return false;" id="abroadLoginTab">Already Registered?</a></li>
            </ul>
        </nav>
    <?php }?>
    <div class="register-form">

        <?php
        // if a course list is available & it has more than one course... show the drop down for course selection
        // (this is the case of university, department. In case of course/cat page the courselist contains a single course)
        if(is_array($courseList) && !empty($courseList) && count($courseList)>=1 && !empty($consultantRelatedData['consultantData'])){
            ?>
            <div style="<?=(count($courseList)==1)?'display:none':''?>">
                <div class="custom-dropdown">
                    <select name="university_course_list_select" class="universal-select signup-select" id="university_course_list_select" caption = "course" <?=($showOnlyCourseDropdown?'onchange="triggerConsultantLayer(this,\''.$regFormId.'\');"':'onchange="universityCourseSelectChange();"')?>>
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
            <?php
            if(!empty($consultantRelatedData['consultantData']) && $showOnlyCourseDropdown)
            {
                $this->load->view('commonModule/consultantEnquiryResponseForm');
                // if only drop down is to be shown & all other values are filled already by the user ?>
                <div class="signup-txtwidth" style="padding-bottom:1px;">

                    <a class="btn btn-primary btn-full mb15" style="width: 98%;margin-left: 1%;margin-right: 1%;margin-bottom:2px;" href="javascript:void(0);" onclick="validateCourseDropDownWithoutRegistration('<?=$data['listingTypeForBrochure']?>','<?=$responseSourcePage?>',this,<?=$tracking_page_key?>,<?=$consultantTrackingPageKeyId?>);"><?php if($widget!='request_callback'){?><i class="sprite bro-icn"></i><?php }?><?=($widget=='request_callback'?'Submit':'Get Brochure')?></a>

                </div>
            <?php } ?>

            <?php
        } // end if $courseList
        ?>
        <?php // show remaining orm only if whole form was not required
        if(!$showOnlyCourseDropdown){
            $data['forResponse'] = true;
            echo Modules::run('registration/Forms/LDB',"studyAbroadRevamped",'mobileRegistrationAbroad',$data);
            $this->load->view('registration/common/OTP/abroadMobileOTP');
        }
        ?>
    </div>
    <div style="display:none;" class="login-form">
        <?php $this->load->view("registration/loginStudyAbroadMobile",array('forResponse'=>true)); ?>
    </div>
</section>
<?php
if(!$data['forResponse']){ //this check is to avoida multiple call in case of response from because when its a reponse form this function will be called from other place
    if($showOnlyCourseDropdown || (!empty($consultantRelatedData['consultantData']) && !empty($userData))){
        ?>
        <script>
            if(typeof(consultantInRegistration) !== 'undefined' && consultantInRegistration == true && !isNaN(loggedInUserCity)){
                showConsultantLayerForm('<?=$regFormId?>',loggedInUserCity);
            }
        </script>
    <?php }}?>
