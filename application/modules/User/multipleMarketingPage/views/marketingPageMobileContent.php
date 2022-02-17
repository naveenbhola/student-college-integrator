<?php
if($load_ga) {   
    $data_array = array('beaconTrackData'=>array('pageIdentifier'=>'MMP','pageEntityId'=>$pageId,'extraData'=>array()));
    $this->load->view('common/ga',$data_array);    
} 
?>
<script>
function gaTrackEventCustom(currentPageName, eventAction, opt_eventLabel, event, callbackUrl) {
  //set optional variables in case they are undefined
  if(typeof(opt_eventLabel) == 'undefined') {
    opt_eventLabel = "";
  }
  if(typeof(pageTracker) != 'undefined') {
    if (typeof(callbackUrl) != 'undefined' && typeof(gaCallbackFunction) == 'function') {
      if(!event){
        event = window.event;
      }
      if (event != 'undefined') {
        if (event.cancelBubble) {
          event.cancelBubble = true;
        } else {
          if(event.stopPropagation) {
            event.stopPropagation();
          }
        }
        if(event.preventDefault){
          event.preventDefault();    
        }
      }
      gaCallbackURL = callbackUrl; //set global variable
      pageTracker._setCallback(gaCallbackFunction);
    }
    pageTracker._trackEventNonInteractive(currentPageName, eventAction, opt_eventLabel, 0, true);
    if(typeof(callbackUrl) != 'undefined') {  
      setTimeout(function() {
        gaCallbackURL = callbackUrl; //set global variable
        gaCallbackFunction();
      }, 3000);
    }
  }
  return true;
}
</script>

<style type="text/css">
.lyr-cls { display:none; }
</style>
<a href="#registerNow" id="openRegFreeForm" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none" > </a>
	<a href="#forgtPassDiv" id="forgtPassDivClick" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none"> </a>
	<div data-role="page" id="registerNow">
	</div>

	<div data-role="page" id="forgtPassDiv">
	</div>

	<a href="#signUpForm" id="openSignUpForm" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none" > </a>
	<a href="#signUpForm" id="openOTPForm" class="ui-link" data-inline="true" data-rel="dialog" data-transition="slide" > </a>
	<div data-role="page" id="signUpForm">
	</div>

	<!-- Page for Registration First Screen -->
	<a href="#regFScreen" id="openRegFScreen" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none" > </a>
	<div data-role="page" id="regFScreen">
	</div>

	<!-- Page for Registration Second Screen -->
	<a href="#regSScreen" id="openRegSScreen" class="ui-link" data-inline="true" data-rel="dialog" data-transition="slide" > </a>
	<a href="#regSScreen" id="openRegSScreenNoTran" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none" > </a>
	<div data-role="page" id="regSScreen">
	</div>

	<!-- Page for Registration dialog Screen -->
	<a href="#dialogPage" id="ssLayerOn" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none" > </a>
	<div data-role="page" id="dialogPage" data-enhance="false" class="sltNone regCss">
	</div>

	<!-- Page for Update Mobile on OTP Screen -->
	<a href="#otpScreen" id="otpSScreen" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none" > </a>
	<div data-role="page" id="otpScreen" data-enhance="false" class="sltNone">
	</div>

	<!-- Page to show miss call time in opt layer  -->
	<a href="#misdCallScreen" id="misdCallSScreen" class="ui-link" data-inline="true" data-rel="dialog" data-transition="slide" > </a>
	<div data-role="page" id="misdCallScreen" class="sltNone">
	</div>

	<!-- Page to show after mobile no verification -->
	<a href="#misdCallVfySucc" id="misdCallVfySuccScreen" class="ui-link" data-inline="true" data-rel="dialog" data-transition="slide" > </a>
	<div data-role="page" id="misdCallVfySucc" class="sltNone">
	</div>

	<!-- Needed in jquery.mobile-1.4.5.min.js -->
	<script>

		var destination_url = '<?php echo $destination_url; ?>';
    	var SHIKSHA_HOME = '<?php echo SHIKSHA_HOME; ?>';

		$(document).ready(function(){
			var uname = '<?php echo $this->input->get('uname');?>';
    		var resetpwd = '<?php echo $this->input->get('resetpwd');?>';
    		var usremail = '<?php echo $this->input->get('usremail');?>';     

			if ((uname != '') && (resetpwd != '') && (usremail != '')) {
            
        		registrationForm.showResetPasswordLayer(uname,usremail);
            
    		} else {

        		var customFields = <?php echo json_encode($formCustomization); ?>;
        		var showWelcomeScreen = '<?php echo $showWelcomeScreen; ?>';
        		var checkLoggedInUser = '<?php echo $checkLoggedInUser; ?>';
        		
        		var formData = {
	                'trackingKeyId' : '<?php echo $trackingKeyId;?>',
	                'customFields':customFields,
	                'callbackFunction':'registrationFromMMPCallback',
	                'submitButtonText':'<?php echo $submitButtonText;?>',
	                'httpReferer':'',
	                'formHelpText':'<?php echo addslashes($customHelpText);?>',
	                'showWelcomeScreen':showWelcomeScreen,
	                'checkLoggedInUser':checkLoggedInUser
	            };

        		registrationForm.showRegistrationForm(formData,'MMP');

        		setTimeout(function(){
	        		$('#wlcmLogin').bind('click', function() {
			            registrationForm.trackRegistrationEvents('Registration','click','Mobile_Login_click_From_WelcomePage');
			            
			        });

			        $('#wlcmRegistration').bind('click', function() {
			            registrationForm.trackRegistrationEvents('Registration','click','Mobile_Register_click_From_WelcomePage');
			        });
			    },500);
       
   			}
		});

		/*Function to validate mobile field*/

		function validateMobileInteger(number,caption,maxlength,minlength,required,isNationalNumber){

			var filter = /^(\d)+$/;

			if (typeof(minlength) =='undefined' || !minlength) {
				minlength = 10;
			}

			if(typeof(isNationalNumber) == 'undefined'){
				isNationalNumber = true;
			}

			if(number != '')
			{
				if(!filter.test(number)){
					return "Please enter your correct " + caption;
				}
				if(isNationalNumber && (number.substr(0,1) != 9)&&(number.substr(0,1) != 8)&&(number.substr(0,1) != 7)&&(number.substr(0,1) != 6))
					return "The mobile number can start with 9, 8, 7 or 6.";
				if(maxlength == minlength && (number.length  > maxlength || number.length < minlength))
					return "The mobile number must have " + maxlength + " digits only";
				if(number.length < minlength){
					return "The mobile number must contain minimum " + minlength + " digits";
				}
				if(number.length > maxlength){
					return "The mobile number can contain maximum " + maxlength + " digits";
				}
				return true;
			}
			if(number == '' && required) {
				return "Please enter your "+ caption;
			}
			return true;
		}

		function validateEmailAddress(email, caption, maxLength, minLength) {
			if(email.length ==0 && (typeof(minLength)) && minLength ==0){
				return true;
			}

			var filter = /^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/

			if(email == '') {
				return  "Please enter your "+ caption ;
			}else {
	            if(filter.test(email)) {
	                var domainIndex = email.indexOf('@')+1;
	                var domain      = email.substring(domainIndex, email.length);
	                if(typeof(invalidDomains) != 'undefined' && invalidDomains.indexOf(domain.toLowerCase()) != -1) {
	                    var regFormId = document.getElementById("regFormId").value;
	                    if(typeof regFormId != 'undefined' && regFormId != null && typeof(isSecondStepValidation) != 'undefined' && isSecondStepValidation) {
	                        url = '/registration/RegistrationForms/trackInvalidFieldData';
	                        $.ajax({type: 'POST',data:{'fieldName':'email','fieldValue':email,'regFormId':regFormId},url : url});
	                        isSecondStepValidation = false;
	                    }
	                    return "The "+ caption +" specified is not correct";
	                }
	            } else {
	                return "The "+ caption +" specified is not correct";
	            }
	        }
			return true;
		}

		function validateDisplayNameReg(str,caption, maxLength,minLength, allowedChars){

			var strToValidate = $.trim(unescape(str));
			var allowedChars = /^([A-Za-z0-9\s](,|\.|_|-){0,2})*$/; 
			if(strToValidate == '' || strToValidate == 'Your Name' || strToValidate == 'Your First Name' || strToValidate == 'Your Last Name')
				return "Please enter your "+caption;

			if(strToValidate.length < minLength)
				return caption+" should be atleast "+ minLength +" characters.";

			if(strToValidate.length > maxLength)
				return caption+" cannot exceed "+ maxLength +" characters.";

			var result = allowedChars.test(strToValidate);
			if(result == false)
				return "The " + caption+" can not contain special characters.";


    // Check if none of the Blacklisted words are used in Display names
    textBoxContent = strToValidate.replace(/[(\n)\r\t\"\']/g,' ');
    textBoxContent = strToValidate.replace(/[^\x20-\x7E]/g,'');
    textBoxContent.toLowerCase();
    var blacklisted = false;
    if(typeof(blacklistWords) == 'undefined'){
    	blacklistWords = new Array();
    }
    if(blacklistWords){
    	for (i=0; i < blacklistWords.length; i++) {
    		if(textBoxContent.indexOf( blacklistWords[i].toLowerCase() ) >= 0)
    			blacklisted = true;
    	}
    }
    if(blacklisted)
    	return "This username is not allowed.";
    // Check for Blacklisted words End
    
    return true;
}

function isProfaneReg(str) {
	/* code start to avoid dissallowed chars */
	var responseValue = checkDisAllowedWord(str);
	if(responseValue !== true){
		return responseValue;
	}
}

	function checkDisAllowedWord(str){
		str = str.replace(/[^\x20-\x7E]/g,'');
		var disallowdWordsList = base64_decode("bWVyYWNhcmVlcmd1aWRlfG1lcmFjYXJlZXJndWlkfHJlYWNoQGluZHJhaml0LmlufHd3dy5pbmRyYWppdC5pbnwwOTgxMDIyNTExNA==");
		console.log(disallowdWordsList);

		var url_pattern = new RegExp(disallowdWordsList,"i");
		return false;
		var dissallowedWord = url_pattern.exec(str);
		console.log(dissallowedWord);
		if(dissallowedWord != null){
			return dissallowedWord;
		}
		return true;
	}

	function base64_decode( data ) {

		var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
		var o1, o2, o3, h1, h2, h3, h4, bits, i=0, enc='';

	    do {  // unpack four hexets into three octets using index points in b64
	    	h1 = b64.indexOf(data.charAt(i++));
	    	h2 = b64.indexOf(data.charAt(i++));
	    	h3 = b64.indexOf(data.charAt(i++));
	    	h4 = b64.indexOf(data.charAt(i++));

	    	bits = h1<<18 | h2<<12 | h3<<6 | h4;

	    	o1 = bits>>16 & 0xff;
	    	o2 = bits>>8 & 0xff;
	    	o3 = bits & 0xff;

	    	if (h3 == 64)      enc += String.fromCharCode(o1);
	    	else if (h4 == 64) enc += String.fromCharCode(o1, o2);
	    	else               enc += String.fromCharCode(o1, o2, o3);
	    } while (i < data.length);

	    return enc;
	}

	function registrationFromMMPCallback() {
	    if(destination_url != '') {
	        window.location = destination_url;
	    }  else {
	        window.location = SHIKSHA_HOME;
	    }
	}
</script>
<?php echo TrackingCode::SCANSmartPixel($googleRemarketingParams); ?>

<div style="display:none" id="newMMpPixelCode">
	<?php echo $PIXEL_CODE;?>
</div>
<?php 

$beaconTrackData = array('pageIdentifier'=>'MMP','pageEntityId'=>$pageId,'extraData'=>array());
loadBeaconTracker($beaconTrackData);

?>
