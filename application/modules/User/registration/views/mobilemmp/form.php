<div id="registration-box">
    <script language="javascript">
    var registration_context = 'MMP';
    var user_logged_in_pref_data = "";
    var userInfo = null;
    var firstTimePageLoad = true;
</script>
<?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>
<form <?php if(!$formData['userId']) { echo 'action="/registration/Registration/register"'; } else { echo 'action="/registration/Registration/updateUser"'; } ?> name="registrationForm_<?php echo $regFormId; ?>" autocomplete="off" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
    <input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
<?php
if($registrationHelper->fieldExists('fieldOfInterest')) {
    $this->load->view('registration/fields/MMP/fieldOfInterest_mobile');
}
if($registrationHelper->fieldExists('desiredCourse')) {
    //$this->load->view('registration/fields/MMP/desiredCourse_mobile');
}
?>
<ul id="registrationFormMiddle_<?php echo $regFormId; ?>">
    <?php $this->load->view('registration/fields/MMP/variable/mobile'); ?>
</ul>
<ul>
<li id="signInErrorsParent_<?php echo $regFormId; ?>" style="display: none;">
    <div style="color: red; padding: 5px 0px;font-size:0.75em" id="signInErrors_<?php echo $regFormId; ?>"></div>
</li>
<?php
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
?>
<li class="login-btn" data-enhance="false">
    <input type="submit" id="registrationSubmit_<?php echo $regFormId; ?>" class="r-btn" value="<?=$buttonText?>" <?php //if($formData['userId']) echo "disabled='disabled'"; ?> onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm()" />
</li>
<li style="font-size:0.9em">
By clicking <?php echo strtolower($buttonText);?> button, I agree to the <a target="_blank" href="/mcommon5/MobileSiteStatic/terms">terms of services</a> and <a target="_blank" href="/mcommon5/MobileSiteStatic/privacy">privacy policy</a>.    
</li>
<div class="clearFix"></div>
</ul>
<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
<input type='hidden' id='mmpFormId' name='mmpFormId' value='<?php echo $mmpFormId; ?>' />
<input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='mobile' />
<input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo $mmpData['page_type'] == 'abroadpage' ? 'yes' : 'no'; ?>' />
<input type='hidden' id='isTestPrep' name='isTestPrep' value='<?php echo $mmpData['page_type'] == 'testpreppage' ? 'yes' : 'no'; ?>' />
<input type='hidden' id='registrationSource' name='registrationSource' value='MARKETING_FORM' />
<input type='hidden' id='userId' name='userId' value='<?php echo $formData['userId']; ?>' />
<?php
$currentPageURL = getCurrentPageURL();
$currentPageURL = str_replace('index/pageID','index/pageIDNew',$currentPageURL);
?>
<input type='hidden' id='referrer' name='referrer' value='<?php echo htmlentities(strip_tags($currentPageURL)); ?>' />
<input type='hidden' id='pageReferrer' name='pageReferer' value='<?php echo htmlentities(strip_tags($_SERVER['HTTP_REFERER'])); ?>' />
<input type='hidden' id='fieldsView' name='fieldsView' value='mobile' />
<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
<input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=false />
<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value='<?= $trackingPageKeyId; ?>' />
<?php if($mmpData['page_type'] == 'abroadpage') { ?>
    <input type='hidden' id='destinationCountry' name='destinationCountry[]' value='' />
<?php } else { ?>    
<input type='hidden' id='preferredStudyLocation' name='preferredStudyLocation[]' value='' />
<?php } ?>
</form>
</div>
<div style="clear:both;"></div>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('userRegistration'); ?>"></script>
<?php $this->load->view('registration/common/jsInitialization'); 
$this->load->view('registration/common/OTP/mobileOTPVerification'); ?>

<?php echo TrackingCode::SCANSmartPixel($googleRemarketingParams); ?>
<script language="javascript">
 try {
            if (window.addEventListener){
            	window.addEventListener('load', function () { getAllElements('registrationForm_<?php echo $regFormId; ?>');getUserPrefData(); getUserDetails(); }, false);  
            } else if (window.attachEvent){
                window.attachEvent('onload', function () { getAllElements('registrationForm_<?php echo $regFormId; ?>');getUserPrefData(); getUserDetails(); });
            } 
            if(typeof(single_default_course) !='undefined' && single_default_course == 'YES') { 
				$j('#desiredCourse_'+'<?php echo $regFormId; ?>').trigger('change');
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
	    var form = document.getElementById('registrationForm_<?php echo $regFormId; ?>');
	    var register_sumit = document.getElementById('registrationSubmit_<?php echo $regFormId; ?>');
	    if(user_id) {
		    form.action ="/registration/Registration/updateUser";
		    register_sumit.removeAttribute('disabled');
	    }	
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

<?php 
$beaconTrackData = array('pageIdentifier'=>'MMP','pageEntityId'=>$mmpFormId,'extraData'=>array());
loadBeaconTracker($beaconTrackData);

?>
