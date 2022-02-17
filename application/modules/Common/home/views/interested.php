<div style="display:none;z-index:100000001;position:absolute;width:260px;border:1px solid #CCC;margin:100px" id="before_get_free_alerts" onMouseDown = "getXYPos()">
        <form  name="get_free_alerts" id="get_free_alerts_frm" method="post" >
                <input type="hidden" name="listing_type_course" id="listing_type_course"  value="course"/>
                <input type="hidden" name="flag_check"  value="1"/>
                <input type="hidden" name="listing_type_id" id="listing_type_id"  value="<?php //echo $type_id; ?>"/>
                <input type="hidden" name="listing_type" id="listing_type"  value="institute"/>
                <input type="hidden" name="listing_title" id="listing_title"  value="<?php //echo $details['title']; ?>"/>
                <input type="hidden" name="listing_url" id="listing_url"  value="<?php echo site_url($thisUrl); ?>"/>
                <input type="hidden" name="isPaid" id="isPaid"  value="<?php echo $registerText['paid']; ?>"/>
                <input type="hidden" name="mailto" id="mailto"  value="<?php echo mencrypt($details['contact_email']); ?>"/>
                <input type="hidden" name="loginproductname_get_free_alert" id="loginproductname_get_free_alert"  value="<?php echo $productname?>"/>
                <input type="hidden" name="resolution_get_free_alert" id="resolution_get_free_alert"  value=""/>
                <input type="hidden" name="referer_get_free_alert" id="referer_get_free_alert"  value=""/>
                <input type="hidden" name="coordinates_get_free_alert" id="coordinates_get_free_alert"  value=""/>
	<div class="mar_full_10p" style="margin-left:20px">
    	<div class="lineSpace_10">&nbsp;</div>
    	Interested in studying at <b class="orangeColor" id = "univinterestedname"></b><br />
        <div class="lineSpace_10">&nbsp;</div>
        Fill Details for the <b>Institute to counsel you</b><br />
        <div class="lineSpace_10">&nbsp;</div>
        Also receive<br />
        <span><b class="dotText">.</b> Institute brochure and updates on email</span><br />
        <span><b class="dotText">.</b> SMS of important dates</span>
        <div class="lineSpace_15">&nbsp;</div>
<!--        Name:<b class="redcolor">*</b><br />
        <input type="text" size="20" /><br />-->
                        <div>Name:<span class="redcolor">*</span><br /><input type="text" name="reqInfoDispName_foralert" id="reqInfoDispName_foralert"  value="<?php if (isset($validateuser[0]['firstname'])) { echo $validateuser[0]['firstname'];  } ?>" <?php if (isset($validateuser[0]['cookiestr'])) {  echo "";  } ?> maxlength = "25" minlength = "3" validate="validateDisplayName"  required="true"  caption="Name" autocomplete="off" style="width:260px;<?php if (isset($validateuser[0]['firstname'])) { echo 'color:#000;background:#FFF;border:1px solid #939aa2;';} ?>" /></div>
                        <div class="errorPlace"><div class="errorMsg" id="reqInfoDispName_foralert_error"></div></div>
        <div class="lineSpace_10">&nbsp;</div>
        <span id = "IntCourse" style = "display:none">
        Course of Interest:<b class="redcolor">*</b><br />
        <select style="width:265px" id = "listing_type_id_course" name = "listing_type_id_course" required = "1" validate = "validateSelect" caption = "course"></select><br />
        </span>
<!--						<div style="padding-top:6px">Course of interest:<span class="redcolor">*</span><br />
						<select name="listing_type_id_course" id="listing_type_id_course" unrestricted="true" validate="validateSelect" required="1"  caption="Course of interest" style="width:220px;font-size:12px;font-family:Arial;padding:2px 0<?php if (isset($validateuser[0]['cookiestr'])) {  echo 'color:#acacac;background:#FFF;border:1px solid #CCC;';  } ?>">
						<option value="">Select Course</option>
						</select>
						</div>-->
						<div class="errorPlace"><div class="errorMsg" id="listing_type_id_course_error"></div></div>
       <!-- <div class="lineSpace_10">&nbsp;</div>
        Email:<b class="redcolor">*</b><br />
        <input type="text" size="20" /><br />
        <div class="lineSpace_10">&nbsp;</div>
        Phone:<b class="redcolor">*</b><br />
        <input type="text" size="10" style="width:120px" /><br />
        <div class="lineSpace_10">&nbsp;</div>-->

                        <div style="padding-top:6px">Email Id:<span class="redcolor">*</span><br /><input type="text" name="reqInfoEmail" id="reqInfoEmail"  value="<?php if (isset($validateuser[0]['cookiestr'])) { $emailArr = explode("|",$validateuser[0]['cookiestr']); echo $emailArr[0];  } ?>" <?php if (isset($validateuser[0]['cookiestr'])) {  echo "readonly";  } ?> maxlength = "125" validate = "validateEmail" required="true"  caption="Email-Id" autocomplete="off" style="width:260px;<?php if (isset($validateuser[0]['cookiestr'])) {  echo 'color:#acacac;background:#FFF;border:1px solid #CCC;';  } ?>" /></div>
                        <div class="errorPlace"><div class="errorMsg" id="reqInfoEmail_error"></div></div>
                        <div style="padding-top:6px">Phone Number:<span class="redcolor">*</span><br /><input type="text" name="reqInfoPhNumber_foralert" id="reqInfoPhNumber_foralert"  value="<?php if (isset($validateuser[0]['mobile'])) { echo $validateuser[0]['mobile']; } ?>"  maxlength = "10" minlength = "10" validate = "validateMobileInteger" required="true"  caption="mobile Number" autocomplete="off" style="width:260px;<?php if (isset($validateuser[0]['mobile'])) { echo 'color:#000;background:#FFF;border:1px solid #939aa2;'; } ?>" /></div>
                        <div class="errorPlace"><div class="errorMsg" id="reqInfoPhNumber_foralert_error"></div></div>
        <div class="lineSpace_10">&nbsp;</div>
<!--        <span class="font_11p_blk">Type in the characters you see below</span>
        <img src="/public/images/capcha.gif" align="absbottom" />&nbsp;&nbsp;<input type="text" style="width:70px" />
        <div class="lineSpace_10">&nbsp;</div>-->

                   <?php if (empty($validateuser[0]['firstname'])) { ?>
                    <div style="padding:14px 18px 0 17px;font-size:11px">Type in the characters you see below </div>
                    <div style="padding:6px 0 0 17px"><img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccodeforlistingalert" width="100" height="40"  id = "getfreealertCaptacha"/>&nbsp;<input type="text" name = "securityCodeforgetalert" id = "securityCodeforgetalert" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code" autocomplete="off" style="width:100px;position:relative;top:-5px" /></div>
                    <div class="errorPlace"><div style="padding-left:20px;" class="errorMsg" id="securityCodeforgetalert_error"></div></div>
						<div class="row">
							<input type="checkbox" name="cAgree" id="requestEbrochure_cAgree" />
							I agree to the <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a> and <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a>
						</div>
						<div>
							<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
								<div class="errorMsg" id= "requestEbrochure_cAgree_error" style="padding-left:4px;" ></div>
							</div>
						</div>
                    <?php } ?>
        <div align="center"><input class="oSmtBtn" onClick = "return run_click()" type = "button" id = "Im_interestBtn" style="width:75px;border:none" uniqueattr="StudyAbroadImInterestedButton"/></div>
<!--        <div align="center" class="font_11p_blk">By submitting you argee to <a href="#">terms &amp; conditions</a></div>-->
<!--					<div style="font-size:11px" align="center">By Submitting you argee to <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms &amp; conditions</a></div>-->
        <div class="lineSpace_10">&nbsp;</div>
    </div>
    </form>
    </script>
</div>
<script>
        <?php
        if (isset($validateuser[0]['firstname'])) {
        ?>
        var flagSignedUser = true;
        <?php
        }
        ?>
function run_click() {
    LazyLoad.loadOnce([
        '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api"); ?>',
        '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("user"); ?>'
    ],callbackfn);
    submit_form_getFreeAlerts();
}
function submit_form_getFreeAlerts()
{   
    try{
        var flag = validateFields(document.getElementById('get_free_alerts_frm'));
        var flag1 = true; var flag2 = true;
        if(trim(document.getElementById("reqInfoPhNumber_foralert").value) == "")
        {
            document.getElementById("reqInfoPhNumber_foralert_error").innerHTML = "Please enter your correct mobile number";
            document.getElementById("reqInfoPhNumber_foralert_error").parentNode.style.display = "inline";
            flag1 = false;
        }
        if(document.getElementById('requestEbrochure_cAgree')){
            var checkboxAgree = document.getElementById('requestEbrochure_cAgree');
            if(checkboxAgree.checked != true)
            {
                document.getElementById('requestEbrochure_cAgree_error').innerHTML = 'Please agree to Terms & Conditions.';
                document.getElementById('requestEbrochure_cAgree_error').parentNode.style.display = 'inline';
                flag2 = false;
            }
            else {
                document.getElementById('requestEbrochure_cAgree_error').innerHTML = 'Please agree to Terms & Conditions.';
                document.getElementById('requestEbrochure_cAgree_error').parentNode.style.display = 'none';
                flag2 = true;
            }
        }
        if (flagSignedUser) {
            if (flag == true ) {
                email_id = document.getElementById('reqInfoEmail').value;
                phone_no = document.getElementById('reqInfoPhNumber_foralert').value;
                display_name = document.getElementById('reqInfoDispName_foralert').value;
                updateRequestInfo();
            } else {
                return false;
            }
        } else {
            if(flag == true && flag1 == true && flag2 == true){
                email_id = document.getElementById('reqInfoEmail').value;
                phone_no = document.getElementById('reqInfoPhNumber_foralert').value;
                display_name = document.getElementById('reqInfoDispName_foralert').value;
                validate('securityCodeforgetalert','seccodeforlistingalert','getfreealertCaptacha');
            } else {
                return false;
            }
        }
    } catch (ex)
    {
        if (debugMode){
            throw ex;
        } else {
            logJSErrors(ex);
        }
    }    
}

function validate(secCodeTextboxid,secCode,captchaId)
{
        if(document.getElementById(secCodeTextboxid)){
        var ObjectOfSecCode = document.getElementById(secCodeTextboxid);
        var caption = ObjectOfSecCode.getAttribute('caption');
        var url = "/MultipleApply/MultipleApply/get_free_alerts/"+ObjectOfSecCode.value+"/"+secCode;
        new Ajax.Request(url, { method:'post',onSuccess:function (request){
                    if(trim(request.responseText)=='true'){
						document.getElementById('Im_interestBtn').disabled=true;
						document.getElementById('Im_interestBtn').style.color='#ccc';
                        updateRequestInfo();
                        trackEventByGA('IAmInterestForm','LeftPanel');
						return true;
						
                    }
                    else {
                        reloadCaptcha(captchaId,secCode);
                        document.getElementById(secCodeTextboxid+'_error').parentNode.style.display = 'inline';
                        document.getElementById(secCodeTextboxid+'_error').innerHTML = "Please enter the "+caption+" as shown in the image";
                        return false;
                    }
                }
                });
        return false;
    }
}
function updateRequestInfo()
{
    var sJSON = '{';
    sJSON += '"'+document.getElementById("listing_type_id").value+'": [ "'+encodeURIComponent(document.getElementById("listing_url").value)+'", "'+encodeURIComponent(document.getElementById("listing_title").value)+'", "'+document.getElementById("listing_type").value+'", "'+document.getElementById("listing_type_id_course").value+'", "'+document.getElementById("listing_type_course").value+'"]';
    sJSON += '}';	
    var paraString = "reqInfoDispName="+document.getElementById('reqInfoDispName_foralert').value+"&reqInfoPhNumber="+document.getElementById('reqInfoPhNumber_foralert').value+"&reqInfoEmail="+document.getElementById('reqInfoEmail').value+"&jSON="+sJSON;
    if (flagSignedUser == false) {
    paraString +="&captchatext="+document.getElementById("securityCodeforgetalert").value;
    }
    paraString +="&flag_check=1"+"&resolution="+document.getElementById('resolution_get_free_alert').value+"&coordinates="+document.getElementById('coordinates_get_free_alert').value+"&loginproductname="+document.getElementById('loginproductname_get_free_alert').value+"&referer="+document.getElementById('referer_get_free_alert').value;
    var url = "/MultipleApply/MultipleApply/getBrochureRequest";
    new Ajax.Request(url, { method:'post', parameters: (paraString), onSuccess:function (request){
                executeGoogleTrackingCode(); 
                email_id = document.getElementById('reqInfoEmail').value;
                phone_no = document.getElementById('reqInfoPhNumber_foralert').value;
                display_name = document.getElementById('reqInfoDispName_foralert').value;
                 // Initialised following variables to make unified registration overlay URL 
                arr_unified[0] = '3';
		arr_unified[1] = $('category_unified_id').value;
                ShikshaUnifiedRegistarion.url_unified = ShikshaUnifiedRegistarion.ajaxUrlHelper(arr_unified);
            if(trim(request.responseText) == 'thanks') {
	    hideLoginOverlay();
            // Code modified for Unified Registration starts here
            if(unified_registration_is_ldb_user == 'false' && (arr_unified[0] == '3' && unified_form_overlay3_cancel_clicked != 'true')) {
            $('categorypage_unified_thankslayer_identifier').value = 'thanks';
	    callUnifiedOverlay(ShikshaUnifiedRegistarion.url_unified,600,100,'studyabroad','requestebrochure');
            } else {
            if (flag_get_free_alert) {    
                displayMessage('/MultipleApply/MultipleApply/showoverlay/5',500,50);
            } else {                               
                displayMessage('/MultipleApply/MultipleApply/showoverlay/2',500,50);
            }
                setTimeout('window.location.reload();', 3000);
            }
            return false;
            } else if (trim(request.responseText) == 'login') {
		hideLoginOverlay();
                flag_get_free_alert = true;
            displayMessage('/MultipleApply/MultipleApply/showoverlay/1',500,260);
            /*if(unified_registration_is_ldb_user == 'false' && (arr_unified[0] == '3' && unified_form_overlay3_cancel_clicked != 'true')) {
            $('categorypage_unified_thankslayer_identifier').value = 'login';
	    callUnifiedOverlay(ShikshaUnifiedRegistarion.url_unified,600,100,'studyabroad');
            } else {
            displayMessage('/MultipleApply/MultipleApply/showoverlay/1',500,260);
             }*/
            return false;
            } else if (trim(request.responseText) == 'register') {
		hideLoginOverlay();
                flag_get_free_alert = true;
            if(unified_registration_is_ldb_user == 'false' && (arr_unified[0] == '3' && unified_form_overlay3_cancel_clicked != 'true')) {
            	$('categorypage_unified_thankslayer_identifier').value = 'register';
	    	callUnifiedOverlay(ShikshaUnifiedRegistarion.url_unified,600,100,'studyabroad','requestebrochure');
            } else{
                displayMessage('/MultipleApply/MultipleApply/showoverlay/2',500,50);
                setTimeout('window.location.reload();', 3000);
              }
          // Code modified for Unified Registration ENDS here
            return false;
            }
        }
    });
}
</script>
