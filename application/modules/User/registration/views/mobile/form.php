<?php
global $logged_in_userid;
global $user_logged_in;
$makeEBResponse = 'NO';
$makeShortlistResponse = 'NO';
$widget = 'mobileResponseForm';
if(isset($logged_in_userid) && $logged_in_userid >0) {    
	$action = '/registration/Registration/updateUser';
}
else {
	$action = '/registration/Registration/register';
}

if($reg_action == 'shortlist' && $signedInUser == 'false'){
    $action = '/registration/Registration/register';
}
if($actionPoint == 'shortlist_CategoryPage'){
    $makeShortlistResponse = 'shortlist';
} 
else if($actionPoint == 'compare_CategoryPage'){
    $makeShortlistResponse = 'compare';
}
// if($reg_action == 'shortlist'$signedInUser != 'false') {
// 	$action = '/registration/Registration/updateUser';
// } else {
// 	$action = '/registration/Registration/register';
// }
?>
<?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>
<div id="registration-box">
<form action="<?php echo $action; ?>" name="registrationForm_<?php echo $regFormId; ?>" autocomplete="off" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post" onsubmit="return false;">
<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
<ul>
<?php

$this->load->view('registration/fields/mobile/userPersonalDetails');
if(isset($requestEbrochure) && $requestEbrochure == 1)
{
    $makeEBResponse = 'YES';
    
    ?>
    <script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("customCityList"); ?>"></script>
    <?php $courseAtrr_decoded=(base64_decode($courseArray,false));
      $courseAtrr_unserialized=(unserialize($courseAtrr_decoded));
?>
<?php if(!empty($courseAtrr_unserialized)){?>
<?php
			// Loading Listing builder and repository..
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$courseRepository = $listingBuilder->getCourseRepository();
			$courseIdsArray = array();
			foreach($courseAtrr_unserialized as $name => $value){
				$value = explode('*', $value);
				$currentCourseId = $value[0];
				if(!in_array($currentCourseId, $courseIdsArray)) {
					$courseIdsArray[] = $currentCourseId;
				}
			}
			// Getting Courses info..
            
			$coursesObjAray = $courseRepository->getDataForMultipleCourses($courseIdsArray,'basic_info|head_location'); 
			$localityArray = array();
			$courseListOptions = array();$paidStatusArr = array();

            $listingebrochuregenerator = $this->load->library('listing/ListingEbrochureGenerator');
            $multiLocationCourses   = json_encode($listingebrochuregenerator->getMultilocationsForInstitute($courseIdsArray));
            // _p($multiLocationCourses); die;
            foreach($coursesObjAray as $course){
				$instituteId = $course->getInstId();
				$courseListOptions[$course->getId()] = html_escape($course->getName()); 
                // $localityArray[$course->getId()] = getLocationsCityWise($course->getLocations());
				$localityArray[$course->getId()] = array();
				$instituteName = $course->getInstituteName();
               	$insLocation = $course->getMainLocation()->getLocality()->getName()?', '.$course->getMainLocation()->getLocality()->getName():"";
				$insCity = ', '.$course->getMainLocation()->getCity()->getName();
				if($course->getId()==$list){
					$courseName = html_escape($course->getName());
                                        $courseSelected = $course->getId();
				}
                                $paidStatusArr[$course->getId()] = $course->isPaid();
                        }
?>
<?php }?>
    
    <?php
    $national_course_lib = $this->load->library('listing/NationalCourseLib');
	$selected_course = $this->input->post('selected_course');
    if(isset($selected_course) && $selected_course!='')
    {
    

        $dominantDesiredCourseData = $national_course_lib->getDominantDesiredCourseForClientCourses(array($selected_course));
            ?>
        <input type="hidden" name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" value="<?php echo $dominantDesiredCourseData[$selected_course]['categoryId'];?>" />
        <input type="hidden" name="desiredCourse" id="desiredCourse_<?php echo $regFormId; ?>" value="<?php echo $dominantDesiredCourseData[$selected_course]['desiredCourse'];?>">
    
    <?php
    }
    else{
        ?>
        <input type="hidden" name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" value="" />
        <input type="hidden" name="desiredCourse" id="desiredCourse_<?php echo $regFormId; ?>" value="">
        <?php
    }
    
    ?>
    <li>
        <div class="custom-dropdown">
        <select name="courseName" id="course_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('course'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="<?php if(1){?>shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeCourse();<?php }?> setSelectedCourseVal(this.value);customFieldsHide('<?php echo $regFormId; ?>');setIsMRValue(this);" caption="the course" label="Course" mandatory="1" regfieldid="course">
           
            <option value="">Select Course to get Brochure</option>
            <?php
            $dropDownCourseArr = array();
            foreach($courseListOptions as $cId=>$cName)
            {
                $courseDetail = $national_course_lib->getDominantDesiredCourseForClientCourses(array($cId));
                echo '<option value="'.$cId.'" '.(($cId==$courseSelected)?'selected=selected':'').' categoryId="'.$courseDetail[$cId]['categoryId'].'" desiredCourseId="'.$courseDetail[$cId]['desiredCourse'].'" ismrvalue="'. $paidStatusArr[$cId].'">'.$cName.'</option>';
            }
            ?>
        </select>
        </div>
    <div class="icon-wrap"><i class="reg-sprite des-course-icon" ></i></div>
    <div style="display: none;">
            <div class="regErrorMsg" id="course_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>
    <?php
    
}
else{
	if(!empty($reg_action) && ($reg_action == 'shortlist' || $reg_action == 'registrationHookFromSearch') && !empty($show_course_selected) && $show_course_selected == 'yes') {
		$national_course_lib = $this->load->library('listing/NationalCourseLib');
		
		$dominantDesiredCourseData = $national_course_lib->getDominantDesiredCourseForClientCourses(array($course_id));
		?>
		<input type="hidden" name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" value="<?php echo $dominantDesiredCourseData[$course_id]['categoryId'];?>" />
		<input type="hidden" name="desiredCourse" id="desiredCourse_<?php echo $regFormId; ?>" value="<?php echo $dominantDesiredCourseData[$course_id]['desiredCourse'];?>">
	<?php } else {
		if($registrationHelper->fieldExists('fieldOfInterest')) {
			$this->load->view('registration/fields/mobile/fieldOfInterest',array('step' => 1, 'selectedFieldOfInterest'=>$desiredCourseIdAndFieldOfInterest['CategoryId']));
		}
		if($registrationHelper->fieldExists('desiredCourse')) {
			$this->load->view('registration/fields/mobile/desiredCourse',array('step' => 1));
		}
	}
}

?>
</ul>
<ul id="registrationFormMiddle_<?php echo $regFormId; ?>" <?php echo (($signedInUser!='false' && $isFullRegisteredUser_mobile==1)?'style="display:none;"':'')?> >
<?php  $this->load->view('registration/fields/LDB/variable/mobile'); ?>
</ul>
<ul>
    <li id="locality-div_<?=$widget;?>" style="display:none;"></li>
</ul>

<!--<ul id="registrationFormMiddle_<?php //echo $regFormId; ?>">-->
<?php //$this->load->view('registration/fields/LDB/variable/mobile',array('step' => '1')); ?>
<!--</ul>-->

<ul>
<li id="signInErrorsParent_<?php echo $regFormId; ?>" style="display: none;">
    <div style="color: red; padding: 5px 0px;font-size:0.75em" id="signInErrors_<?php echo $regFormId; ?>"></div>
</li>

<li class="login-btn" data-enhance="false" id="submitButton">
<?php //if($isFullRegisteredUser_mobile && 0): ?>
    <!-- <input type="button" id="registrationSubmit_<?php echo $regFormId; ?>" class="r-btn" value="Submit" onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitMobileResponse();return false;" /> -->
<?php //else: ?>
    <input type="submit" id="registrationSubmit_<?php echo $regFormId; ?>" class="r-btn" value="Submit" onclick="return userRegistrationRequest['<?php echo $regFormId; ?>'].submitForm()" />
<?php //endif; ?>
</li>
<!--<a href="#" class="flRt" style="font-size:15px;display: none;font-style: italic;margin: 5px 2px 8px 0;" id="skipThisStep"><strong>Skip this step</strong></a>-->
<?php if(!$formData['userId']): ?>
<li style="font-size:0.9em">

        By clicking submit button, I agree to the <a target="_blank" href="/mcommon5/MobileSiteStatic/terms">terms of services</a> and <a target="_blank" href="/mcommon5/MobileSiteStatic/privacy">privacy policy</a>.

</li>
<?php endif; ?>
<div class="clearFix"></div>
</ul>
<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
<input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='mobile' />
<input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo $courseGroup == 'studyAbroad' ? 'yes' : 'no'; ?>' />
<input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo $registrationSource; ?>' />
<?php 
    $referrer = !empty($referrer)? $referrer : base64_encode($_SERVER['HTTP_REFERER']);
 ?>
<input type='hidden' id='referrer' name='referrer' value='<?php echo $referrer; ?>' />
<input type='hidden' id='referrer_<?php echo $regFormId; ?>' value='<?php echo $referrer; ?>' />
<input type='hidden' id='widget_<?php echo $regFormId; ?>' name='widget' value='<?=$widget;?>' />
<input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=false />

<?php if(!empty($tracking_keyid))  {?>
<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value="<?php echo htmlentities(strip_tags($tracking_keyid));?>" />
<?php } ?>

<?php if(!empty($allCustomFormData['formCallBack']))  {?>
<input type="hidden" id="formCallBack_<?php echo $regFormId; ?>" name="formCallBack" value="<?=$allCustomFormData['formCallBack']?>" />
<?php } ?>

<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
<?php
if(isset($requestEbrochure) && $requestEbrochure == 1)
{
?>
<input type='hidden' id='widget_<?php echo $regFormId; ?>' name='widget' value='<?=$widget;?>' />
<?php
}
?>
<input type='hidden' id='fieldsView' name='fieldsView' value='mobile' />
<!--<input type='hidden' id='registrationStep' name='registrationStep' value='1' />-->
<input type='hidden' id='userId' name='userId' value='<?php echo $logged_in_userid;?>' />
<input type='hidden' id='list' name='list' value='<?php echo $this->input->post('list'); ?>'/>
<input type='hidden' id='currentUrl' name='currentUrl' value='<?php echo $current_url; ?>'/>
<input type='hidden' id='institute_id' name='institute_id' value='<?php echo $institute_id; ?>'/>
<input type='hidden' id='institute_id_<?php echo $regFormId; ?>' value='<?php echo $institute_id; ?>'/>
<input type='hidden' id='institute_name_<?=$regFormId;?>' name='institute_name' value='<?=$instituteName;?>' />
<input type='hidden' id='customCallBack_<?php echo $regFormId; ?>' name='customCallBack' value='<?=$customCallBack;?>' />
<input type='hidden' id='isCategoryPage_<?php echo $regFormId; ?>' name='isCategoryPage' value='<?=$isCategoryPage;?>' />
<input type='hidden' id='courseArray' name='courseArray' value='<?php echo $courseArray; ?>'/>
<input type='hidden' id='login' name='login' value='Request E-Brochure'/>
<input type='hidden' id='selected_course' name='selected_course' value='<?php echo $this->input->post('selected_course'); ?>'/>
<input type='hidden' id='course' name='course' value='<?php echo $this->input->post('selected_course'); ?>'/>
<input type='hidden' id='action_type' name='action_type' value='<?php echo $this->input->post('action_type'); ?>'/>
<input type='hidden' id='pageName' name='pageName' value='<?php echo $this->input->post('pageName'); ?>'/>
<input type="hidden" id="from_where" name="from_where" value="<?php $from_where = $this->input->post('from_where'); echo isset($from_where) ? $from_where : 'CURRENT_PAGE'; ?>" />

<input type='hidden' id='preferred_city_ml_<?php echo $regFormId; ?>' name='preferred_city_ml' value=''/>
<input type='hidden' id='preferred_locality_ml_<?php echo $regFormId; ?>' name='preferred_locality_ml' value=''/>

<?php /*if($courseGroup == 'studyAbroad'){ ?>
<input type='hidden' id='destinationCountry<?php echo $regFormId; ?>' name='destinationCountry[]' value='' />
<?php }else{ ?>
<input type='hidden' id='preferredStudyLocation' name='preferredStudyLocation[]' value='' />
<?php }*/ ?>
<input type="hidden" id="redirectUrl" name="redirectUrl" value="" />
<input type="hidden" name="makeEBResponse" id="makeEBResponse_<?php echo $regFormId; ?>" value="<?php echo $makeEBResponse;?>" />
<input type="hidden" name="makeShortlistResponse" id="makeShortlistResponse_<?php echo $regFormId; ?>" value="<?php echo $makeShortlistResponse;?>" />
<input type='hidden' id='isMR' name='isMR' value='YES' />

<!-- FIELDS SET FOR NEW FORMS -->
<input type='hidden' id='reg_action' name='reg_action' value='<?php echo $reg_action; ?>'/>
<input type='hidden' id='reg_course_id' name='reg_course_id' value='<?php echo $course_id; ?>'/>
<input type='hidden' id='redirect_url' name='redirect_url' value='<?php echo $redirect_url; ?>'/>
<?php  if($examName != '') { ?>
<input type='hidden' name='examName' value='<?php echo $examName; ?>'/>
<?php } ?>

<?php if(isset($_POST['fromICP']) && $_POST['fromICP'] == 'Yes'){ ?>
    <input type='hidden' id='fromICP' name='fromICP' value='Yes'/>
<?php } ?>

<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value='<?=$trackingPageKeyId?>'/>
</form>
</div>
<div style="clear:both;"></div>

<?php $this->load->view('registration/common/jsInitialization'); ?>
<?php /*
if($isLDBUserCheck=='NO' && $desiredCourse!=0){ ?>
    <script>
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].callSecondStep('<?php echo $desiredCourse;?>','<?php echo $CategoryId;?>');
    </script>
<?php }*/
?>

<script type="text/javascript">

    <?php if((empty($logged_in_userid) || $logged_in_userid < 0) && $trackingPageKeyId == '269'){ ?>
        setTimeout(function(){console.log("GNB_Response_Mobile_Old_v2");gaTrackEventCustom('Registration', '269', 'GNB_Response_Mobile_Old_v2')}, 700);
    <?php }else if((empty($logged_in_userid) || $logged_in_userid < 0) && $trackingPageKeyId == '797'){ ?>
            
        setTimeout(function(){console.log('GNB_Register_Mobile_Old_v2');gaTrackEventCustom('Registration', '797', 'GNB_Register_Mobile_Old_v2')}, 700);
    <?php } ?>
</script>
<?php
if(!empty($desiredCourseIdAndFieldOfInterest['CategoryId']) && $desiredCourseIdAndFieldOfInterest['CategoryId']>0){ ?>
    <script>
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateDesiredCourses();
    setTimeout(function(){$j("#desiredCourse_<?php echo $regFormId; ?>").val("<?php echo $desiredCourseIdAndFieldOfInterest['ldbCourseId'];?>").change();},1000);
    </script>
<?php } ?>

<script>

function getCityNameForRegistration(addressObject){
    var city_name = 'Select Location';
    for (var i = 0; i < addressObject.address_components.length; i++) {
        for (var j = 0; j < addressObject.address_components[i].types.length; j++) {
            if(addressObject.address_components[i].types[j] == 'locality') {
                city_name = addressObject.address_components[i].short_name;
            }
        }
    }
    if(city_name == 'New Delhi' || city_name == 'New delhi'){
            city_name = "Delhi";
    }
    return city_name;
}

function trackEventByGAMobile(eventTracked){
        try{
                _gaq.push(['_trackEvent',eventTracked, 'click']);
        }catch(e){}
}

function fillLocationUsingGeoForRegistration(position) {
    trackEventByGAMobile('HTML5_Registration_SecondStep_Geolocation_Accepted');
    var city_name = 'Select Location';
    jQuery.ajax({ url:'https://maps.googleapis.com/maps/api/geocode/json?latlng='+position.coords.latitude+','+position.coords.longitude+'&sensor=true',
                  success: function(data){
                    if(data.results && data.results[0]){
                            city_name = getCityNameForRegistration(data.results[0]);
                    }
                    else if(data.results && data.results[4]){
                            city_name = getCityNameForRegistration(data.results[4]);
                    }
		    //setCookie('currentGeoLocation',city_name,0,'/',COOKIEDOMAIN);
                    jQuery.ajax({ url: "/mcommon5/MobileSiteHome/checkForCityName/" + city_name,
                        type: "GET",
                        success: function(result)
                        {
                            if(result!='0'){
				    var splitResult = result.split('#');
				    var cityId = splitResult[0];
				    var cityName = splitResult[1];
                                    jQuery('#residenceCity_<?php echo $regFormId; ?>').val(cityId).change();
                            }
                            else{
                                    showError('locationText','We could not find your location. Please select location.');
                            }
                        },
                        error: function(e){
                            showError('locationText','We could not find your location. Please select location.');
                        }
                    });
                 },
                 error: function(e,f,g){
                        showError('locationText','We could not find your location. Please select location.');
                 }
    });
}

var currentCourseFlag = 1;
$j('#course_<?php echo $regFormId; ?>').change();
var institute_id = '<?=$institute_id?>';
<?php
if(isset($requestEbrochure) && $requestEbrochure == 1)
{
?>
var listings_with_localities = JSON.parse('<?=$listings_with_localities; ?>');
<?php
}
?>
var localityArray = JSON.parse('<?=json_encode($localityArray, JSON_HEX_APOS)?>');
window.onload=function(){
window.jQuery.each(localityArray,function(index,element){
	custom_localities[index] = element;
});
    
    var courseSelectedId = '<?php echo $courseSelected; ?>';    
if(courseSelectedId!=''){
    $j('#course_<?php echo $regFormId; ?>').change();
        var tempHTML = addLocalityInApplyForm('<?php echo $institute_id; ?>','<?php echo $widget; ?>','<?php echo $courseSelected; ?>','mobile');
        $j('#locality-div_<?=$widget;?>').html(tempHTML);
        setTimeout(function(){$j('#locality-div_<?=$widget;?>').trigger('create')},1000);
        //$j('#locality-div_<?=$widget;?>').trigger('create');
        $city = $j('#preferred_city_category_<?=$widget?>'+'<?php echo $institute_id; ?>');
        $city.trigger('change');
}
//console.log(custom_localities);
<?php
    if($signedInUser != 'false' && $isFullRegisteredUser_mobile)
    {
    ?>
	if (typeof(document.getElementById('residenceCityLocality_<?php echo $regFormId ?>'))!='undefined' && document.getElementById('residenceCityLocality_<?php echo $regFormId ?>')!=null) {
	    $j('#residenceCityLocality_<?php echo $regFormId ?>').val('<?=$signedInUser[0]['city'] ?>').change();
	}
	if (typeof(document.getElementById('residenceCity_<?php echo $regFormId ?>'))!='undefined' && document.getElementById('residenceCity_<?php echo $regFormId ?>')!=null) {
	    $j('#residenceCity_<?php echo $regFormId ?>').val('<?=$signedInUser[0]['city'] ?>').change();
	}
	
    <?php
    }
    else
    {
    ?>
	if (typeof(document.getElementById('residenceCityLocality_<?php echo $regFormId ?>'))!='undefined' && document.getElementById('residenceCityLocality_<?php echo $regFormId ?>')!=null) {
	    $j('#residenceCityLocality_<?php echo $regFormId ?>').change();
	}
	if (typeof(document.getElementById('residenceCity_<?php echo $regFormId ?>'))!='undefined' && document.getElementById('residenceCity_<?php echo $regFormId ?>')!=null) {
	    $j('#residenceCity_<?php echo $regFormId ?>').change();
	}
   <?php
    }
?>


};
function setIsMRValue(ref){
    var mrvalue = 'YES';
    if ($j(ref).find(':selected').attr('ismrvalue')=='1') {
        mrvalue = '';
    }
    $j('#isMR').val(mrvalue);
}
function getMultiLocationDiv(obj) {
    
    var cityId = $j(obj).val();
    var cityName = obj.options[obj.selectedIndex].text;
    var CourseId = $j('#course_<?php echo $regFormId; ?> option:selected').val();
    var tempHTML = addLocalityInApplyForm('<?php echo $institute_id; ?>','<?php echo $widget; ?>',CourseId,'mobile');
    $j('#locality-div_<?=$widget;?>').html(tempHTML);
    setTimeout(function(){$j('#locality-div_'+widget).trigger('create')},50);
    $j('#preferred_city_ml_<?php echo $regFormId; ?>').val(cityId);
    var widget = '<?php echo $widget; ?>';
    var isCustomListing = iscustomizedCourse(CourseId);
    var resident_city = $j('#residenceCityLocality_'+self.regFormId).val();

    $city.val(resident_city);
    $city.trigger('change');
    $j('#locality-div_mobileResponseForm').show();
    //$j('#preferred_city_category_<?=$widget?><?php echo $institute_id; ?>').val(cityName).change();
    if(typeof(widget) != 'undefined' && $('locality-div_'+widget) && $('locality-div_'+widget).style.display != 'none'){
        var insId = '<?php echo $institute_id; ?>';

        if($j("#preferred_city_category_"+widget+insId).find('option[value='+cityId+']').length > 0){
            $j("#preferred_city_category_"+widget+insId).find('option[value='+cityId+']').attr('selected','selected').change();
        }else if($j("#preferred_city_category_"+widget+insId).find('option[value="'+cityName+'"]').length > 0){
            if (!isCustomListing) {
		$j("#preferred_city_category_"+widget+insId).find('option[value="'+cityName+'"]').attr('selected','selected').change();
	    }
	}else{
	    if (!isCustomListing) {
                $j("#preferred_city_category_"+widget+insId).find('option[value=""]').attr('selected','selected').change();
	    }
        }
    }
    if ($j('#wouldLikeLocality').length > 0 && isCustomListing == 1) {
		$j('#wouldLikeLocality').css("display","none");
    }
    if((cityId == undefined || cityId == "") && isCustomListing != 1 ){
        setTimeout(function(){
            $j('#locality-div_mobileResponseForm').hide();
        },500);
    }
}

function updateMultiLocationFields(localityId)
{
    $j('#preferred_locality_ml_<?php echo $regFormId; ?>').val(localityId);
}
function setSelectedCourseVal(cid)
{
    $j('#list').val(cid);
    $j('#selected_course').val(cid);
    $j('#course').val(cid);
}
function customFieldsHide(form_suffix)
{
    setTimeout(function(){
                  <?php
                  if($graduationYear!=0){
                    ?>
                    if (typeof(document.getElementById('graduationCompletionYear_<?php echo $regFormId ?>'))!='undefined' && document.getElementById('graduationCompletionYear_<?php echo $regFormId ?>')!=null) {
                        $j('#graduationCompletionYear_'+form_suffix).val('<?=$graduationYear?>').change();
                    }
                    <?php
                  }
                  if($xiiYear!=0)
                  {
                    ?>
                    if (typeof(document.getElementById('xiiYear_<?php echo $regFormId ?>'))!='undefined' && document.getElementById('xiiYear_<?php echo $regFormId ?>')!=null) {
                        $j('#xiiYear_'+form_suffix).val('<?=$xiiYear?>').change();
                    }
                    <?php
                  }
                  ?>
               }, 1500);
}
</script>
<?php if(!empty($reg_action) && ($reg_action == 'shortlist' || $reg_action == 'registrationHookFromSearch') && !empty($show_course_selected) && $show_course_selected == 'yes') { ?>
	<script>
		var userLoggedIn = <?php if($user_logged_in == 'true') echo "1";?>
		$j(document).ready(function() {
			//if logged in
			if (userLoggedIn) {
				var fieldOfInterest = '<?php echo $dominantDesiredCourseData[$course_id]['categoryId'];?>';
				var desiredCourse = '<?php echo $dominantDesiredCourseData[$course_id]['desiredCourse'];?>';
			
				$j('#fieldOfInterest_<?php echo $regFormId; ?>').val(fieldOfInterest);
				$j('#desiredCourse_<?php echo $regFormId; ?>').val(desiredCourse);
			}
			shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse();
		});    
	</script>
<?php } ?>

<?php
if($courseSelected > 0 && $isFullRegisteredUser_mobile && $widget != 'listingPageTopLinks' && $widget != 'listingPageBottomNew') { ?>
<script>
    $j(document).ready(function(){
       // $j('#registrationSubmit_<?php echo $regFormId; ?>').click();
});
    
</script>
<?php } ?>
<?php
	if(isset($_POST['fromRankPredictorPage']) && $_POST['fromRankPredictorPage'] == 'Yes'){
		?>
		<script>
			$j('#fieldOfInterest_<?=$regFormId?>').val('<?=$_POST['category']?>').trigger('change');
			setTimeout(function(){$j('#desiredCourse_<?=$regFormId?>').val('<?=$_POST['subcategory']?>').trigger('change');},1000);
			
			setTimeout(function(){
			    $j('#xiiYear_<?=$regFormId?>').val('<?=$_POST['yearOfPassing']?>').trigger('change');
			    $j('#examContainer_<?=$regFormId?>').html('Selected (1)');
			    $j('input[value='+'<?=$_POST['ExamFromRP']?>'+']').attr('checked', 'checked');
			},2500);

</script>
		
	
<?php } 
if(isset($_POST['fromICP']) && $_POST['fromICP'] == 'Yes'){
        ?>
        <script>
            $j('#fieldOfInterest_<?=$regFormId?>').val('<?=$_POST['category']?>').trigger('change');
            setTimeout(function(){$j('#desiredCourse_<?=$regFormId?>').val('<?=$_POST['subcategory']?>').trigger('change');},1000);
            
            setTimeout(function(){
                $j('#graduationCompletionYear_<?=$regFormId?>').val('<?=$_POST['yearOfPassing']?>').trigger('change');
                $j('#examContainer_<?=$regFormId?>').html('Selected (1)');
                
                $j('input[value="CAT"]').attr('checked', 'checked');
                $j('#examDetails_CAT_<?=$regFormId?>').show();
                $j('#CAT_score_<?=$regFormId?>').val('<?=$_POST["CAT_EXAM"]?>');
                $j('input[value="CAT"]').attr('checked', 'checked');
                $j('#fieldOfInterest_block_<?=$regFormId?>').hide();
                if($j('#graduationCompletionYear_<?=$regFormId?>').val() != ''){
                    $j('#desiredCourse_block_<?=$regFormId?>').hide();
                }
                $j('#graduationCompletionYear_block_<?=$regFormId?>').hide();
            },2700);

            setTimeout(function(){
                 if($j('#graduationCompletionYear_<?=$regFormId?>').val() == ''){
                    $j('#graduationCompletionYear_block_<?=$regFormId?>').show();
                }
            }, 3000);

        </script>
        
    
<?php }


if(isset($_POST['fromMenteePage']) && $_POST['fromMenteePage'] == 'Yes'){?>
		<script>
			$j('#fieldOfInterest_<?=$regFormId?>').val('<?=$_POST['category']?>').trigger('change');
			setTimeout(function(){$j('#desiredCourse_<?=$regFormId?>').val('<?=$_POST['subcategory']?>').trigger('change');},2000);
			setTimeout(function(){
			    $j('#xiiYear_<?=$regFormId?>').val('<?=$_POST['yearOfPassing']?>').trigger('change');},2500);
		</script>		
<?php }?>


<script type="text/javascript">

        <?php if(isset($multiLocationCourses)){ ?>
            var multiLocationCourses = <?php echo $multiLocationCourses; ?>;
        <?php }else{ ?>
            var multiLocationCourses = [];
        <?php } ?>

    if(shikshaUserRegistrationForm['<?php echo $regFormId; ?>']){
        shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].triggerISDCodeOnchange('<?php echo INDIA_ISD_CODE; ?>');
    }
</script>
