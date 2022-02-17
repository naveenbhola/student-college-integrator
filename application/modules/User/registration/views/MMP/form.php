<?php	
/**
 * If MMP form is embedded in a custom template,
 * Include all CSS and JS required to run MMP form
 */ 
if($includeHTMLDependencies) {
?>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common_new"); ?>" type="text/css" rel="stylesheet" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("registration"); ?>" type="text/css" rel="stylesheet" />
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('common'); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('header'); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('ajax-api'); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('userRegistration'); ?>"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$j = jQuery.noConflict();
</script>
<?php } ?>

<?php
if($mmpData['display_on_page'] != 'normalmmp') {
	$this->load->view('registration/common/jsInitialization');
}
?>
<?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>

<div id="registration-box2">
<form action="/registration/Registration/register" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
<input type="hidden" id="mmp_registration_form" value="mmp">
<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
<ul>
<?php
if($mmpData['page_type'] == 'abroadpage' && STUDY_ABROAD_NEW_REGISTRATION) {
	$this->load->view('registration/fields/MMP/userPersonalDetails');
}

if($registrationHelper->fieldExists('fieldOfInterest') && !STUDY_ABROAD_NEW_REGISTRATION) {
    $this->load->view('registration/fields/MMP/fieldOfInterest');
}

if($mmpData['page_type'] == 'abroadpage' && STUDY_ABROAD_NEW_REGISTRATION) {
	if($registrationHelper->fieldExists('desiredCourse')) {
	    $this->load->view('registration/fields/MMP/desiredCourse');
	}
}
?>
</ul>

<ul id="registrationFormMiddle_<?php echo $regFormId; ?>">
<?php
if($mmpData['page_type'] == 'abroadpage' && STUDY_ABROAD_NEW_REGISTRATION) {
    $this->load->view('registration/fields/MMP/variable/defaultStudyAbroad');
}
else {
    $this->load->view('registration/fields/MMP/variable/default');   
}
?>
</ul>

<ul>
<?php
if($mmpData['page_type'] == 'abroadpage'){
//$this->load->view('registration/fields/securityCode');
}

if(!empty($mmpData['submitButtonText'])) {
	$buttonText = $mmpData['submitButtonText']; 
} else {
	if($mmpData['page_type'] == 'abroadpage') {
		$buttonText = "I'm ready to Study Abroad";
	} else{
		if(!$formData['userId']) { 
			$buttonText = 'Sign Up'; 
		} else { 
			$buttonText = 'Submit'; 
		}
	}
}
$agreeData = array('buttonText'=>$buttonText); 
?>
<li style="margin-top:5px;text-align:center">
    <!-- <label>&nbsp;</label> -->
    <div class="fields-col" style="width:100%;">
    <input type="button" style="z-index: 888888;" id="registrationSubmit_<?php echo $regFormId; ?>" value="<?php echo $buttonText; ?>" class="orange-button" <?php if($formData['userId']) echo "disabled='disabled'"; ?> onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm()" >
    </div>
</li>

<li>
    <p><?php $this->load->view('registration/common/agree', $agreeData); ?></p>
</li>
<div class="clearFix"></div>
</ul>
<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
<input type='hidden' id='mmpFormId' name='mmpFormId' value='<?php echo $mmpFormId; ?>' />
<input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo $mmpData['page_type'] == 'abroadpage' ? 'yes' : 'no'; ?>' />
<input type='hidden' id='isTestPrep' name='isTestPrep' value='<?php echo $mmpData['page_type'] == 'testpreppage' ? 'yes' : 'no'; ?>' />
<input type='hidden' id='userId' name='userId' value='<?php echo $formData['userId']; ?>' />
<input type='hidden' id='registrationSource' name='registrationSource' value='MARKETING_FORM' />
<?php
$currentPageURL = getCurrentPageURL();
$currentPageURL = str_replace('index/pageID','index/pageIDNew',$currentPageURL);
?>
<input type='hidden' id='referrer' name='referrer' value='<?php echo htmlentities(strip_tags($currentPageURL)); ?>' />
<input type='hidden' id='pageReferrer' name='pageReferer' value='<?php echo htmlentities(strip_tags($_SERVER['HTTP_REFERER'])); ?>' />
<input type='hidden' id='fieldsView' name='fieldsView' value='default' />
<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value='<?= $trackingPageKeyId; ?>' />
<?php if($mmpData['display_on_page'] == 'normalmmp') {  ?>
<input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=true />
<?php } else { ?>
<input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=false />
<?php } ?>
</form>
</div>
<?php
if($mmpData['display_on_page'] != 'normalmmp') {
	$this->load->view('registration/common/OTP/userOtpVerification');
} ?>

<script language="javascript">
    
    var reset_password_token = '<?php echo $reset_password_token?>';
    var reset_usremail = '<?php echo $reset_usremail?>';
    var registration_context = 'MMP';
    var user_logged_in_pref_data = "";
    var userInfo = null;
    var firstTimePageLoad = true;

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
	    if(!empty($userid)) {
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
	    if(!empty($userid)) {
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
<div style="clear:both;"></div>
<?php
/**
 * For normal MMP form
 * Include all JQuery and JS here
 */ 
if(!$includeHTMLDependencies) {
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('userRegistration'); ?>"></script>
<script> $j = $.noConflict(); </script>
<?php }
if($mmpData['page_type'] == 'abroadpage' && STUDY_ABROAD_NEW_REGISTRATION) { ?>
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
<?php }
?>
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
</script>
<?php
if($mmpData['display_on_page'] == 'normalmmp') {
	$this->load->view('registration/common/jsInitialization');
}
?>
<?php echo TrackingCode::SCANSmartPixel($googleRemarketingParams); ?>
<?php if($load_ga): ?>
<?php

$data_array = array('beaconTrackData'=>array('pageIdentifier'=>'MMP','pageEntityId'=>$mmpFormId,'extraData'=>array())); 
$this->load->view('common/ga',$data_array);

?>
<?php else:?>
<?php 
$beaconTrackData = array('pageIdentifier'=>'MMP','pageEntityId'=>$mmpFormId,'extraData'=>array());
loadBeaconTracker($beaconTrackData);	
?>
<?php endif;?>
<style>
<?php if($mmpData['display_on_page'] == 'normalmmp') { ?>
#DHTMLSuite_modalBox_transparentDiv{height:639px !important;}
<?php } ?>
#modalDialog_content_iframe {display:none !important;}
</style>
