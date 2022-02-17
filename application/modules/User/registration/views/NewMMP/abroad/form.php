<?php
$this->load->view ( 'registration/common/jsInitialization' );
?>
<?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>

<div id="formScroll" class="scrollbar1">

	<div class="scrollbar">
		<div class="track">
			<div class="thumb">
				<div class="end"></div>
			</div>
		</div>
	</div>
	<div class="viewport" style="overflow: hidden; height: 440px;">
		<div class="overview" id="newOverviewScroll">
			<div id="registration-box2">
				<div class="form-main-container">
					<div class="form-sub-main-container">
						<div class="form-sub-main-box">
							<form action="<?php if(!$formData['userId']) { echo '/registration/Registration/register'; } else { echo '/registration/Registration/updateUser'; } ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
								<input type="hidden" id="mmp_registration_form" value="mmp">
								<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
								<ul>
									<?php
									$this->load->view ( 'registration/fields/NewMMP/userPersonalDetails' );
									?>
								</ul>

											<ul id="registrationFormMiddle_<?php echo $regFormId; ?>">
									<?php
									$this->load->view ( 'registration/fields/NewMMP/variable/defaultStudyAbroad' );
									?>
								</ul>

								<?php
								if(!empty($mmpData['submitButtonText'])) {
									$buttonText = $mmpData['submitButtonText']; 
								} else {
									$buttonText = "I'm ready to Study Abroad";
								}
								$agreeData = array('buttonText'=>$buttonText); 
								?>
								<ul>
									<li style="margin-top: 5px; text-align: center;" onclick="updateTinyScrollBar();" >
										<input type="submit" id="registrationSubmit_<?php echo $regFormId; ?>" value="<?php echo $buttonText;?>" class="submit" onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm();" />
									</li>

									<li>
										<p class="policy-line"><?php $this->load->view('registration/common/agree', $agreeData); ?></p>
									</li>

									<div class="clearFix"></div>
								</ul>
								<input type='hidden' id='display_on_page' name='display_on_page' value='<?php echo $mmpData['display_on_page'];?>' /> 
								<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' /> <input type='hidden' id='mmpFormId' name='mmpFormId' value='<?php echo $mmpFormId; ?>' /> <input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='yes' /> 
								<input type='hidden' id='isTestPrep' name='isTestPrep' value='<?php echo $mmpData['page_type'] == 'testpreppage' ? 'yes' : 'no'; ?>' />
								<input type='hidden' id='userId' name='userId' value='<?php echo $formData['userId']; ?>' /> 
								<input type='hidden' id='registrationSource' name='registrationSource' value='MARKETING_FORM' />
					
								<?php
								$currentPageURL = getCurrentPageURL ();
								$currentPageURL = str_replace ( 'index/pageID', 'index/pageIDNew', $currentPageURL );
								?>
								<input type='hidden' id='referrer' name='referrer' value='<?php echo strip_tags($_SERVER['HTTP_REFERER']); ?>' /> <input type='hidden' id='pageReferrer' name='pageReferer' value='<?php echo strip_tags($_SERVER['HTTP_REFERER']); ?>' /> 
								<input type='hidden' id='fieldsView' name='fieldsView' value='default' />
								<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' /> <input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=false />
								<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value='<?= $trackingPageKeyId; ?>' />
								<input type="hidden" id="courseLevel_<?php echo $regFormId; ?>" name="courseLevel" value='' />
							</form>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<?php $this->load->view('registration/common/OTP/abroadMMPOTPVerification'); ?>


<div style="clear: both;"></div>

<script>
	
	$j('#mmpOverlay').css({"overflow-y":"hidden","max-height": "650px"});
  	var regFormId = '<?php echo $regFormId; ?>';

    $j(window).on('load',function() {
    	shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setCurrentSchoolList("<?php echo base64_encode(json_encode(array_map(function($a){return str_replace( '\\','',$a); }, $fields['currentSchool']->getValues()))); ?>");
    	shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].bindCurrentSchoolAutocomplete();
		if($j('.desiredGraduationLevel_'+regFormId+':checked').val() != undefined) {
			var courseLevel = $j('.desiredGraduationLevel_'+regFormId+':checked').attr('courseLevel');
			shikshaUserRegistrationForm[regFormId].switchEducationFields(courseLevel);
			updateTinyScrollBar();
		}
    });

	function updateTinyScrollBar(){
		setTimeout(function(){
			$j('#formScroll').tinyscrollbar_update('relative');
		},600);
		
	}

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
		$j(document).mouseup(function (e)	{
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
	twostepCountryDivTrigger();

	$j(document).ready(function($j) { 	
		$j("#formScroll").tinyscrollbar();

		var dropDown = $j('#twoStepChooseCourseCountryDropDown').height()+ $j('#twoStepCountryDropdownLayer').height()-15;
		$j('#twoStepCountryDropdownLayer').css({"top":"-"+dropDown+"px"});
		if(typeof(single_default_course) !='undefined' && single_default_course == 'YES') {
			$j('#desiredCourse_'+'<?php echo $regFormId; ?>').trigger('change');
		}
	});
	
	function selectDesiredDourseRadio(course_id) {
		$j("#twoStepChooseCourse").find("input[type='radio']").each(function() {
			if(this.value == course_id){
				this.checked=true;				
			} else {
				this.checked=false;		
			}
		});
	}

	function toggleMultipleOptions(){
		if($j('.more-opt-box').is(":visible")){
			$j('.more-opt-box').hide();
		}else{
			$j('.more-opt-box').show();
		}
	}

	$j(document).click(function(){

		$j('#formScroll').tinyscrollbar_update('relative');

		if(!$j('.more-opt-box').is(":visible") && !$j('#twoStepCountryDropdownLayer').is(':visible')){
			$j('.more-opt-box').show();
		}
	});
</script>

<script type="text/javascript">
setTimeout(function(){var a=document.createElement("script");
var b=document.getElementsByTagName("script")[0];
a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0019/0281.js?"+Math.floor(new Date().getTime()/3600000);
a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
</script>

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
#modalDialog_content_iframe {
	display: none !important;
}
</style>

<?php 
if(empty($data['loadmmponpopup'])){ ?>
	<style type="text/css">
		.layer-outer { position: fixed; }
		.layer-title .close { display: none; }
	</style>
<?php } ?>
