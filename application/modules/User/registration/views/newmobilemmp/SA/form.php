<script>
	function hideFOI(){
	if($j('#fieldOfInterest_<?php echo $regFormId; ?>').length){
		$j('#fieldOfInterest_<?php echo $regFormId; ?>').siblings(".ui-btn-inner").children().children().html('Choose field of interest');
	}
}

function checkerrorMsg(){
	$j('#passport_error_<?php echo $regFormId; ?>').hide();
}
</script>
<?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>
<div id="registration-box">
	<form
		<?php if(!$formData['userId']) { echo 'action="/registration/Registration/register"'; } else { echo 'action="/registration/Registration/updateUser"'; } ?> name="registrationForm_<?php echo $regFormId; ?>" autocomplete="off" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
		<input type="hidden" id="mmp_registration_form" value="mmp">
		<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
<?php
$this->load->view ( 'registration/fields/MMP/SaMmpPersonalDetails' );

if ($registrationHelper->fieldExists ( 'desiredCourse' )) {
	$this->load->view ( 'registration/fields/MMP/desiredCourse' );
}
?>


<ul id="registrationFormMiddle_<?php echo $regFormId; ?>">
	<?php
	$this->load->view ( 'registration/fields/MMP/variable/mobileMmpSA' );
	?>
</ul>

		<ul>

			<li id="signInErrorsParent_<?php echo $regFormId; ?>"
				style="display: none;">
				<div style="color: red; padding: 5px 0px; font-size: 0.75em"
					id="signInErrors_<?php echo $regFormId; ?>"></div>
			</li>
			<?php
			if(!empty($mmpData['submitButtonText'])) {
				$buttonText = $mmpData['submitButtonText']; 
			} else {
				$buttonText = "I'm ready to Study Abroad";
			}
			$agreeData = array('buttonText'=>$buttonText); 
			?>

			<li class="login-btn" data-enhance="false"><input style="background: #fcd146 none repeat scroll 0 0 !important;border-color: #e0ab00 !important; color: #000;padding: 0.4em 0 0.3em;text-shadow: 0 1px 1px #fff;font-weight:bold;" type="submit"
				id="registrationSubmit_<?php echo $regFormId; ?>" class="r-btn"
				value="<?php echo $buttonText;?>"
				<?php //if($formData['userId']) echo "disabled='disabled'"; ?>
				onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm()" />
			</li>

			<!-- <li style="margin-top:5px;">
    <label>&nbsp;</label>
    <div class="fields-col">
    <input type="button" style="z-index: 888888;" id="registrationSubmit_<?php echo $regFormId; ?>" value="<?php if($mmpData['page_type'] == 'abroadpage') echo 'I\'m ready to Study Abroad'; else echo 'Submit'; ?>" class="orange-button" <?php if($formData['userId']) echo "disabled='disabled'"; ?> onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm()" >
    </div>
</li>
 -->
			<li>
				<p style="font-size: 14px;font-weight:normal"><?php $this->load->view('registration/common/agree', $agreeData); ?></p>
			</li>
			<div class="clearFix"></div>
		</ul>

		<input type='hidden' id='regFormId' name='regFormId'
			value='<?php echo $regFormId; ?>' /> <input type='hidden'
			id='mmpFormId' name='mmpFormId' value='<?php echo $mmpFormId; ?>' />
		<input type='hidden' id='isStudyAbroad' name='isStudyAbroad'
			value='yes' /> <input type='hidden' id='isTestPrep' name='isTestPrep'
			value='<?php echo $mmpData['page_type'] == 'testpreppage' ? 'yes' : 'no'; ?>' />
		<input type='hidden' id='userId' name='userId'
			value='<?php echo $formData['userId']; ?>' /> <input type='hidden'
			id='registrationSource' name='registrationSource'
			value='MARKETING_FORM' /> <input type='hidden' id='display_on_page'
			name='display_on_page'
			value='<?php echo $mmpData['display_on_page'];?>' /> <input
			id="context_<?php echo $regFormId; ?>" type="hidden" value="mobileRegistrationAbroad"
			name="context">
		<input type="hidden" id="courseLevel_<?php echo $regFormId; ?>" name="courseLevel" value='' />

<?php
$currentPageURL = getCurrentPageURL ();
$currentPageURL = str_replace ( 'index/pageID', 'index/pageIDNew', $currentPageURL );
?>

<input type='hidden' id='referrer' name='referrer'
			value='<?php echo strip_tags(htmlentities($currentPageURL)); ?>' /> <input type='hidden'
			id='pageReferrer' name='pageReferer'
			value='<?php echo strip_tags(htmlentities($_SERVER['HTTP_REFERER'])); ?>' /> <input
			type='hidden' id='fieldsView' name='fieldsView' value='default' /> <input
			type="hidden" id='userVerification_<?php echo $regFormId; ?>'
			name='userVerification' value='no' /> <input type="hidden"
			id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm"
			value=false />
<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value='<?= $trackingPageKeyId; ?>' />
	</form>
</div>

<script language="javascript">
    var formData = '<?php echo json_encode($formData); ?>';
    var reset_password_token = '<?php echo $reset_password_token?>';
    var reset_usremail = '<?php echo $reset_usremail?>';
    var registration_context = 'MMP';
    var user_logged_in_pref_data = "";
    var userInfo = null;
    var firstTimePageLoad = true;
	var regFormId = '<?php echo $regFormId; ?>';

    $j(window).on('load',function() {
    	shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setCurrentSchoolList("<?php echo base64_encode(json_encode(array_map(function($a){return str_replace( '\\','',$a); }, $fields['currentSchool']->getValues()))); ?>");
    	shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setTenthMarksValues("<?php echo base64_encode(json_encode(array_map(function($a){return str_replace( '\\','',$a); }, $fields['tenthmarks']->getValues()))); ?>");
    	shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].bindCurrentSchoolAutocomplete();

    	if($j('.desiredGraduationLevel_'+regFormId+':checked').val() != undefined) {
			var courseLevel = $j('.desiredGraduationLevel_'+regFormId+':checked').attr('courseLevel');
			shikshaUserRegistrationForm[regFormId].switchEducationFields(courseLevel);
			updateTinyScrollBar();
		}
    });

     try {
            if (window.addEventListener){
            	window.addEventListener('load', function () { getAllElements('registrationForm_<?php echo $regFormId; ?>');shikshaUserRegistration.showResetPasswordLayer(reset_password_token,reset_usremail,registration_context);getUserPrefData(); getUserDetails(); }, false);  
            } else if (window.attachEvent){
                window.attachEvent('onload', function () { getAllElements('registrationForm_<?php echo $regFormId; ?>');shikshaUserRegistration.showResetPasswordLayer(reset_password_token,reset_usremail,registration_context);getUserPrefData(); getUserDetails(); });
            } 
    } catch(e) {
           //   
    }
    
    function getAllElements(formId){
        form = document.getElementById(formId);        
        for (var i=0; i<form.elements.length; i++){
            var elem = form.elements[i];
            if (window.addEventListener){
		elem.addEventListener('click', function () { pushCustomVariable(this.getAttribute('regfieldid')); }, false); 
            } else if (window.attachEvent){
		elem.attachEvent('onclick', function () {pushCustomVariable(this.getAttribute('regfieldid')); });
            }            
        }
    }
    
    function getUserPrefData() {
	<?php
	global $userid;
	if (! empty ( $userid )) {
		?>
	    var user_id = '<?php echo $userid;?>';
	    var ajaxCallUrl = '/user/Userregistration/getLDBUserDetails/'+user_id;
	    var form = document.getElementById('registrationForm_<?php echo $regFormId; ?>');
	    var register_sumit = document.getElementById('registrationSubmit_<?php echo $regFormId; ?>');
	    if(user_id) {
		    form.action ="/registration/Registration/updateUser";
		    register_sumit.removeAttribute('disabled');
	    }	
	    /*new Ajax.Request( ajaxCallUrl,
	    {	method:'get',
		    onSuccess: function(result){
			    user_logged_in_pref_data = eval("eval("+result.responseText+")");			
			    if(user_logged_in_pref_data.isLDBUser == 'YES') {
				    form.action ="/registration/Registration/ldbUserPrefLog";	
				    register_sumit.removeAttribute('disabled');
			    }						
		    }
	    });*/
	<?php
	}
	?>
    }
    
    function getUserDetails() {
	<?php
	global $userid;
	if (! empty ( $userid )) {
		?>
	    var user_id = '<?php echo $userid;?>';
	    var ajaxCallUrl = '/user/Userregistration/getUserInfo/'+user_id;
	    
	    new Ajax.Request( ajaxCallUrl,
	    {	
			method:'get',
			
		    onSuccess: 
				function(response){
					
					userInfo = eval('(' + response.responseText + ')');
					var city_field = 'residenceCity_<?php echo $regFormId; ?>';
					
					if(userInfo.residenceCity>0 && typeof($(city_field)) !='undefined') {						
						$j("#"+city_field).val(userInfo.residenceCity);
						$j("#"+city_field).change();
					}
					
					shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateFormOnLoad(userInfo.desiredCourse);
			    }
	    });
	<?php
	}
	?>
    }
</script>

<div style="clear: both;"></div>

<?php
/**
 * For normal MMP form
 * Include all JQuery and JS here
 */
if (! $includeHTMLDependencies) {
	?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script language="javascript"
	src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('userRegistration'); ?>"></script>
<script> $j = $.noConflict(); </script>
<?php

}
?>
<script>

    if(typeof(count_abroad_preselected)!='undefined' && count_abroad_preselected>0 && count_abroad_desired_saved_course>0) {
	$j(document).ready(function($j) {                
		shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].desiredCourseOnChangeActions();
	});
    }
   
    if(typeof(count_abroad_cat_preselected)!='undefined' && count_abroad_cat_preselected>0 && !desired_course_visibility && !desired_category_visibility) {        
	$j(document).ready(function($j) {                
		shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].parentcatOnchangeActions();
	});
    }	 	    
	
    
	if(typeof(desired_exam_visibility)!='undefined' && !desired_exam_visibility) {	
		$j(document).ready(function($j) {
			if(document.getElementById('examsAbroad_block_<?php echo $regFormId;?>')) {
				document.getElementById('examsAbroad_block_<?php echo $regFormId;?>').style.display = 'none';	
			}
		});
	}
	
    function twostepCountryDivTrigger(){
        $j(document).mouseup(function (e)
        {
            var container = $j("#twoStepCountryDropdownLayer");
	    var containerDD = $j("#twoStepChooseCourseCountryDropDown");
	    
            if (!container.is(e.target) && !containerDD.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
	    {
                if(container.css('display') === 'block')
                    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelectedMMP();
            }
        });
    }
    twostepCountryDivTrigger();</script>

<script>
$j(document).ready(function($j) { 	
	if(typeof(single_default_course) !='undefined' && single_default_course == 'YES') {
		$j('#desiredCourse_'+'<?php echo $regFormId; ?>').trigger('change');
	}
});
</script>

<script type="text/javascript">
	setTimeout(function(){var a=document.createElement("script");
	var b=document.getElementsByTagName("script")[0];
	a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0019/0281.js?"+Math.floor(new Date().getTime()/3600000);
	a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);


	$j(document).mouseup(function (e){
    var container = $j("#countryDropdownLayer");


	if($j('input[name="destinationCountry[]"]:checked').length < 1 && $j("#countryDropdownLayer").is(":visible")){
		$j('#destinationCountry_error<?php echo $regFormId; ?>').html('Please select destination country(s)');
		$j('#destinationCountry_error<?php echo $regFormId; ?>').show();
	}

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        container.hide();
    }
	});
</script>

<?php
$this->load->view ( 'registration/common/jsInitialization' );
?>

<?php echo TrackingCode::SCANSmartPixel($googleRemarketingParams); ?>

<?php 
if($load_ga) {

$data_array = array('beaconTrackData'=>array('pageIdentifier'=>'MMP','pageEntityId'=>$mmpFormId,'extraData'=>array())); 
$this->load->view('common/ga',$data_array); 

}

$beaconTrackData = array('pageIdentifier'=>'MMP','pageEntityId'=>$mmpFormId,'extraData'=>array());
loadBeaconTracker($beaconTrackData);	

?>

<style>
#DHTMLSuite_modalBox_transparentDiv {
	height: 639px !important;
}
</style>

<script type="text/javascript">
function hideCountryLayer(){
	$j('#countryDropdownLayer').hide();
}

function toggleCountryDropDown(){
	$j('#countryDropdownLayer').toggle();
}

function limitCountrySelection(checkedId){
	if($j('input[name="destinationCountry[]"]:checked').length > 3){
		$j('#'+checkedId).attr('checked',false);
		alert("You can select upto 3 countries only.");
	}
	$j( "div#twoStepChooseCourseCountryDropDown" ).siblings('div.ui-select').children().children().children().children().html("Destination Country Selected ("+$j('input[name="destinationCountry[]"]:checked').length+")");

	if($j('input[name="destinationCountry[]"]:checked').length){
		$j('#destinationCountry_error<?php echo $regFormId; ?>').hide();
	}
}

$j('document').ready(function(){

	formData = $j.parseJSON(formData); 
	var preSelect = 0;
	
	if(formData.destinationCountry != undefined){
		while(preSelect < formData.destinationCountry.length){
			$j('#twoStepCountryDropdownCont2_'+formData.destinationCountry[preSelect]).attr('checked', true);
			preSelect++;
		}
	$j( "div#twoStepChooseCourseCountryDropDown" ).siblings('div.ui-select').children().children().children().children().html("Destination Country Selected ("+$j('input[name="destinationCountry[]"]:checked').length+")");

	}
	
	if(formData.passport !=undefined && formData.exams == undefined){
		if(formData.passport == 'no'){
			$j('#examTaken_no_<?php echo $regFormId; ?>').prop("checked", true)
			$j('#passport_block_<?php echo $regFormId; ?>').show();
			$j('#passport_'+formData.passport+'_<?php echo $regFormId; ?>').prop("checked", true);
		}
	}
		if(formData.abroadSpecialization !=undefined && !$j('abroadSpecialization_<?php echo $regFormId; ?>').is(":visible")){
			
			
			setTimeout(function(){
				$j('#abroadSpecialization_<?php echo $regFormId; ?>').val(formData.abroadSpecialization);
				$j('#abroadSpecialization_<?php echo $regFormId; ?>').siblings('.ui-btn-inner').children().children().html($j("#abroadSpecialization_<?php echo $regFormId;?> option:selected").text());
			},1900);

		if(formData.planToGo !=undefined && $j('#whenPlanToGo_'+formData.planToGo+'_<?php echo $regFormId; ?>').is(":visible")){
				$j('#whenPlanToGo_'+formData.planToGo+'_<?php echo $regFormId; ?>').prop("checked", true);
		}
	}

	if(formData.bookedExamDate !=undefined && formData.bookedExamDate=="1"){
			$j('#examTaken_bookedExamDate_<?php echo $regFormId; ?>').prop("checked", true)
	}
});

</script>
