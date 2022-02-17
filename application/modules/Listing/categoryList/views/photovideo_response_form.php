<?php 
$widget = "mediaresponse";
?>
<h5 style="background-image:none; padding-left:8px; font:normal 24px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#282828">Get in touch with</h5>
        <div style="padding:10px;">
        <strong><?php echo $name; ?></strong>
        <div class="spacer15 clearFix"></div>
        <form novalidate="" onsubmit="processData(); return false;" name="myForm1" id="form_<?=$widget?>">
        <input type = "hidden" name = "resolution" id = "resolution" value = ""/>
    	<input type = "hidden" name = "coordinates" id = "coordinates" value = ""/>
    	<input type = "hidden" name = "referer" id = "referer" value = ""/>
    	<input type = "hidden" name = "loginproductname" id = "loginproductname" value = ""/>
        <input type = "hidden" name = "institute_id_<?=$widget?>" id = "institute_id_<?=$widget?>" value = "<?php echo $institute_id; ?>"/>
        <input type = "hidden" name = "institute_name_<?=$widget?>" id = "institute_name_<?=$widget?>" value = "<?php echo $name; ?>"/>
        <ul class="form-box">
        	<li id="course_list_element"><select class="universal-select" id="selected_course_<?=$widget?>"><option>Desired Course</option></select></li>    
                <li id="locality_<?=$widget?>" style="display:none;"></li>
            <li id="li_usr_first_name_<?=$widget?>"><input type="text" class="universal-txt-field" name="usr_first_name_<?=$widget?>" profanity="true" minlength="1" maxlength="50" required="true" default="Your First Name" validate="validateDisplayName" caption="First Name" tip="multipleapply_name" id="usr_first_name_<?=$widget?>" value="<?php if(!empty($userfirstname)) {echo htmlentities($userfirstname);} else {echo 'Your First Name';}?>" onfocus="checkTextElementOnTransition(this,'focus');" blurMethod="checkTextElementOnTransition(this,'blur');"/>
                <div style="display: none;float:left;width:100%;"><div style="padding-left:3px;" id="usr_first_name_<?=$widget?>_error" class="errorMsg">Please enter your First Name</div></div>
            </li>
            <li id="li_usr_last_name_<?=$widget?>"><input type="text" class="universal-txt-field" name="usr_last_name_<?=$widget?>" profanity="true" minlength="1" maxlength="50" required="true" default="Your Last Name" validate="validateDisplayName" caption="Last Name" tip="multipleapply_name" id="usr_last_name_<?=$widget?>" value="<?php if(!empty($userlastname)) {echo htmlentities($userlastname);} else {echo 'Your Last Name';}?>" onfocus="checkTextElementOnTransition(this,'focus');" blurMethod="checkTextElementOnTransition(this,'blur');"/>
                <div style="display: none;float:left;width:100%;"><div style="padding-left:3px;" id="usr_last_name_<?=$widget?>_error" class="errorMsg">Please enter your Last Name</div></div>
           </li>
            <li id="li_contact_email_<?=$widget?>"><input type="text" class="universal-txt-field" caption="email" minlength="10" maxlength="100" validate="validateEmail" id="contact_email_<?=$widget?>" default="Email" value="<?php if(!empty($email)) {echo $email;} else {echo 'Email';}?>" name="contact_email_<?=$widget?>" tip="multipleapply_email" required="true" profanity="true" onfocus="checkTextElementOnTransition(this,'focus');" blurMethod="checkTextElementOnTransition(this,'blur');"/>
		<div style="display: none;float:left;width:100%;"><div id="contact_email_<?=$widget?>_error" style="padding-left:3px;" class="errorMsg">Please enter your email.</div></div>
            </li>
            <li id="li_mobile_phone_<?=$widget?>"><input type="text" class="universal-txt-field" caption="mobile phone" tip="multipleapply_cell" minlength="10" maxlength="10" required="true" default="Mobile No" validate="validateMobileInteger" name="mobile_phone_<?=$widget?>" id="mobile_phone_<?=$widget?>" profanity="true" value="<?php if(!empty($mobile)) {echo $mobile;} else {echo 'Mobile No';}?>" onfocus="checkTextElementOnTransition(this,'focus');" blurMethod="checkTextElementOnTransition(this,'blur');"/>
		<div style="display: none;float:left;width:100%;"><div style="padding-left:3px;" id="mobile_phone_<?=$widget?>_error" class="errorMsg">Please enter your mobile phone</div></div>
           </li>
		   
			<?php
			if($studyAbroadResponse) {
				echo Modules::run('MultipleApply/MultipleApply/getExtraFieldsForStudyAbroadResponseForm','PhotoVideoLayer',$widget);
			}
			?>
            <?php if(empty($email)):?>
            <li id="captcha_div_id">
            	<div class="spacer10 clearFix"></div>
            	<div style="font-size:11px;">Type in the character you see in the picture below</div>

                <div><img src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeIndex_<?=$widget?>" style="vertical-align:middle" id = "secureCode_<?=$widget?>"/> &nbsp; <input type="text" class="universal-txt-field" style="width:50%; vertical-align:middle" name = "homesecurityCode_<?=$widget?>" id = "homesecurityCode_<?=$widget?>" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"  value=""/>
		</div>
               <div>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px;float:left;width:100%;">
			<div style="margin-left:3px;" class="errorMsg" id= "homesecurityCode_<?=$widget?>_error"></div>
			</div>
		</div>
            </li>
            <?php endif;?>
        </ul>
        <div class="spacer10 clearFix"></div>
        <input value="Submit" class="orange-button" style="font-size:18px !important" type="submit" uniqueattr="PhotovideoLayerResponseButton" id="submitRegisterFormVideoButton"/>
        </form>
        <div id="terms_and_condition_box">
        <div class="spacer10 clearFix"></div>
        <p style="font-size:11px; line-height:16px; color:#000">By clicking Submit button, I agree to the <a onclick="return popitup('http://www.shiksha.com/shikshaHelp/ShikshaHelp/termCondition');" href="javascript:void(0);">terms of services</a> and <a onclick="return popitup('http://www.shiksha.com/shikshaHelp/ShikshaHelp/privacyPolicy');" href="javascript:void(0);">privacy policy</a></p>
        </div>
        </div>
<script>
var response_list = '<?php echo $response_list; ?>';
response_list = eval("eval("+response_list+")");
</script>
<script>
var institute_id = '<?php echo $institute_id; ?>';
if($('applynow'+institute_id) != null) {
	$('course_list_element').innerHTML = '<select caption="desired course" validate="validateSelect" required="true" class="universal-select" id="selected_course_<?=$widget?>">'+$('applynow'+institute_id).innerHTML+"</select>"+'<div style="display:none;"><div style="padding-left:3px;" id="selected_course_<?=$widget?>_error" class="errorMsg">Please select desired course.</div></div>';
} else {
		$('photo-info-col').style.display = "none";
        	$('photo-view-cont').style.width = "470px"; 
}
</script>
<script type="text/javascript">
        /*  :(    */
                disableResponseButton();
		addOnBlurValidate($('form_<?=$widget?>'));
		$j('#submitRegisterFormButton').attr('uniqueattr','abroadmedialayerresponse');
        	shortRegistrationFormResponse = 1;
		shortRegistrationFormUnified = false;
		shortRegistrationFormCallBack = function(response){
                if(typeof(response.existingUser) !="undefined" && response.existingUser == 'Yes') {
                        unified_registration_is_ldb_user = 'false';
			if(response.isLDBUser == 'YES')  unified_registration_is_ldb_user = 'true';
                }
                showPhotoVideoOverlay('<?php echo $type; ?>',institute_id,'<?php echo base64_encode($name);?>','fromcallback');
		}
        	function processData()
        	{
			processOverlayForm('<?=$widget?>');
		}

                function disableResponseButton() {
                  //alert('am called'+response_list);
		  if(typeof(response_list) != 'undefined' && response_list) {
                    //alert('am called1');
		    for(var i in response_list) {
                        //alert(response_list[i] + "__" + $('selected_course_<?=$widget?>').value);
			if(response_list[i] == $('selected_course_<?=$widget?>').value) {
                                //alert('1');
				$('submitRegisterFormVideoButton').disabled = "true";
                		$('submitRegisterFormVideoButton').className = "big-button-inactive";
                                return true;
                                break;
                        }
                    }
                  }
                	return false;
                }
    </script>
    </form>
</div>
<script>
	try {

		populateLocations("selected_course_<?=$widget?>", "locality_<?=$widget?>", institute_id, "mediaresponse");
		$j('#selected_course_<?=$widget?>').change(function(){
                        if(disableResponseButton()) {
                               // TO -- DO
                        } else {
				populateLocations("selected_course_<?=$widget?>", "locality_<?=$widget?>", institute_id, "mediaresponse");
                                $('submitRegisterFormVideoButton').removeAttribute('disabled');
                                $('submitRegisterFormVideoButton').className = "orange-button";
                        }
		});
		
		if($('contact_email_<?=$widget?>').value != "" && $('contact_email_<?=$widget?>').value != "Email"){
			userlogin = true;
                        if('<?php echo $type; ?>' == 'Photo') {
                        	//$('photo-preview-col').style.height = "410px";
                        }
                        $('usr_first_name_<?=$widget?>').style.display = "none";
                        $('usr_last_name_<?=$widget?>').style.display = "none";
                        $('li_usr_first_name_<?=$widget?>').style.display = "none";
                        $('li_usr_last_name_<?=$widget?>').style.display = "none";
                        if($('mobile_phone_<?=$widget?>').value && $('mobile_phone_<?=$widget?>').value != 'Mobile No') {
                        	$('mobile_phone_<?=$widget?>').style.display = "none";
                        	$('li_mobile_phone_<?=$widget?>').style.display = "none";
                        }
                        $('contact_email_<?=$widget?>').style.display = "none";
                        $('li_contact_email_<?=$widget?>').style.display = "none";
                        $('terms_and_condition_box').style.display = "none";
		}else{
			userlogin = false;
                        //$('photo-preview-col').style.height = "410px";
		}
		
	}catch (ex){
		if (debugMode){
			throw ex;
		} else {
			logJSErrors(ex);
		}
	}   
</script>
<style>
.big-button-inactive {
    background-color: #ACACAC;
    background-image: none;
    border: 2px solid #929292;
    color: #C5C6C8;
    cursor: auto;
    text-shadow: 1px 1px 1px #7D746D;
    width: 90px;
    border-radius: 12px 12px 12px 12px;
}
</style>
