<?php

?>
    <div style="width:265px" id="before_get_free_alerts" >
        <form  name="get_free_alerts" id="get_free_alerts_frm" method="post" >
                <input type="hidden" name="listing_type_course" id="listing_type_course"  value="course"/>
                <input type="hidden" name="flag_check"  value="1"/>
                <!--<input type="hidden" name="listing_type_id" id="listing_type_id"  value="<?php //echo $type_id; ?>"/>-->
                <input type="hidden" name="listing_title" id="listing_title"  value="<?php echo $details['title']; ?>"/>
                <input type="hidden" name="listing_url" id="listing_url"  value="<?php echo site_url($thisUrl); ?>"/>
                <input type="hidden" name="isPaid" id="isPaid"  value="<?php echo $registerText['paid']; ?>"/>
                <input type="hidden" name="mailto" id="mailto"  value="<?php echo mencrypt($details['contact_email']); ?>"/>
                <?php if ($listing_type == 'institute') { ?>
                <input type="hidden" name="loginproductname_get_free_alert" id="loginproductname_get_free_alert"  value="GET_FREE_ALERTS_INSTITUTE_NAUKRISHIKSHA"/>
                <?php } elseif ($listing_type == 'course') { ?>
                <input type="hidden" name="loginproductname_get_free_alert" id="loginproductname_get_free_alert"  value="GET_FREE_ALERTS_COURSE_NAUKRISHIKSHA"/>
                <?php } ?>
                <input type="hidden" name="resolution_get_free_alert" id="resolution_get_free_alert"  value=""/>
                <input type="hidden" name="referer_get_free_alert" id="referer_get_free_alert"  value=""/>
                <input type="hidden" name="coordinates_get_free_alert" id="coordinates_get_free_alert"  value=""/>
            <div class="getFreeFormBg"><div style="padding-top:7px">I AM INTERESTED</div></div>
            <div class="bgGetFreeMid">
                <div class="bgFreeGetContent">
                    <div style="padding:0 10px 0 17px">
                        <div style="line-height:17px;padding-top:5px">Interested in studying at <span class="OrgangeFont bld"><?php echo $details['title']; ?>?</span></div>						
                        <div style="line-height:10px">&nbsp;</div>
						<div>Fill Details for the <span class="bld">Institute to counsel you</span></div>
                        <div style="line-height:10px">&nbsp;</div>
						<div>Also receive</div>
						<ul style="margin:0;padding:0;margin-left:13px" type="disc">
							<li style="padding-bottom:5px">Institute brochure and updates on email</li>
							<li style="padding-bottom:5px">SMS of important dates</li>
						</ul>                        
                        <div style="line-height:17px">&nbsp;</div>

                        <div>Name:<span class="redcolor">*</span><br /><input type="text" name="reqInfoDispName_foralert" id="reqInfoDispName_foralert"  value="<?php if (isset($validateuser[0]['firstname'])) { echo $validateuser[0]['firstname'];  } ?>" <?php if (isset($validateuser[0]['cookiestr'])) {  echo "";  } ?> maxlength = "25" minlength = "3" validate="validateDisplayName"  required="true"  caption="Name" autocomplete="off" style="width:220px;<?php if (isset($validateuser[0]['firstname'])) { echo 'color:#000;background:#FFF;border:1px solid #939aa2;';} ?>" /></div>
                        <div class="errorPlace"><div class="errorMsg" id="reqInfoDispName_foralert_error"></div></div>
						<?php if(count($courseList) > 0) { ?>
						<div style="padding-top:6px">Course of interest:<span class="redcolor">*</span><br />
						<select name="listing_type_id_course" id="listing_type_id_course" unrestricted="true" validate="validateSelect" required="1"  caption="Course of interest" style="width:220px;font-size:12px;font-family:Arial;padding:2px 0<?php if (isset($validateuser[0]['cookiestr'])) {  echo 'color:#acacac;background:#FFF;border:1px solid #CCC;';  } ?>">
						<option value="">Select Course</option>
						<?php for($i = 0 ; $i < count($courseList); $i++){
							if($listing_type == "course"){
								if($type_id==$courseList[$i]['course_id']){
									$selectValue = "selected";
								} else {
									$selectValue ='';
								}
								echo '<option value="'.$courseList[$i]['course_id'].'" title="'.$courseList[$i]['courseTitle'].'" '.$selectValue.'>'.$courseList[$i]['courseTitle'].'</option>';
							} else {
								echo '<option value="'.$courseList[$i]['course_id'].'" title="'.$courseList[$i]['courseTitle'].'">'.$courseList[$i]['courseTitle'].'</option>';
							}
						} ?>
						</select>
						</div>
						<div class="errorPlace"><div class="errorMsg" id="listing_type_id_course_error"></div></div>
						<?php } ?>
                        <div style="padding-top:6px">Email Id:<span class="redcolor">*</span><br /><input type="text" name="reqInfoEmail" id="reqInfoEmail"  value="<?php if (isset($validateuser[0]['cookiestr'])) { $emailArr = explode("|",$validateuser[0]['cookiestr']); echo $emailArr[0];  } ?>" <?php if (isset($validateuser[0]['cookiestr'])) {  echo "readonly";  } ?> maxlength = "125" validate = "validateEmail" required="true"  caption="Email-Id" autocomplete="off" style="width:220px;<?php if (isset($validateuser[0]['cookiestr'])) {  echo 'color:#acacac;background:#FFF;border:1px solid #CCC;';  } ?>" /></div>
                        <div class="errorPlace"><div class="errorMsg" id="reqInfoEmail_error"></div></div>
                        <div style="padding-top:6px">Phone Number:<span class="redcolor">*</span><br /><input type="text" name="reqInfoPhNumber_foralert" id="reqInfoPhNumber_foralert"  value="<?php if (isset($validateuser[0]['mobile'])) { echo $validateuser[0]['mobile']; } ?>"  maxlength = "10" minlength = "10" validate = "validateMobileInteger" required="true"  caption="mobile Number" autocomplete="off" style="width:220px;<?php if (isset($validateuser[0]['mobile'])) { echo 'color:#000;background:#FFF;border:1px solid #939aa2;'; } ?>" /></div>
                        <div class="errorPlace"><div class="errorMsg" id="reqInfoPhNumber_foralert_error"></div></div>
                    </div>
                   <?php if (empty($validateuser[0]['firstname'])) { ?>
                    <div style="padding:14px 18px 0 17px;font-size:11px">Type in the characters you see below </div>
                    <div style="padding:6px 0 0 17px"><img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccodeforlistingalert" width="100" height="40"  id = "getfreealertCaptacha"/>&nbsp;<input type="text" name = "securityCodeforgetalert" id = "securityCodeforgetalert" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code" autocomplete="off" style="width:100px;position:relative;top:-5px" /></div>
                    <div class="errorPlace"><div style="padding-left:20px;" class="errorMsg" id="securityCodeforgetalert_error"></div></div>
                    <?php } ?>
                    <div class="lineSpace_10">&nbsp;</div>
                    <div align="center"><input type="button" onclick="return run_click();" class="continueBtn" id="Im_interestBtn_n" value="Submit" /></div>
                    <div class="lineSpace_10">&nbsp;</div>
					<div style="font-size:11px" align="center">By Submitting you argee to <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms &amp; conditions</a></div>
					<div class="lineSpace_10">&nbsp;</div>
                </div>
            </div>
            <div class="bgGetFreeBtm">&nbsp;</div>
        </form>    
    </div>
    <div style="width:265px;display:none;" id="after_get_free_alerts" >
        <div class="bgGetFreeInfo">
            <div style="padding-top:11px;color:000">Your request has been successfully submitted.</div>
        </div>
    </div>
<script>
window.onerror = function() {
    if (debugMode){
        throw ex;
    } else {
        logJSErrors(ex);
    }
    return true;
}
function run_click() {
	orangeButtonDisableEnableWithEffect('Im_interestBtn_n',true);
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
        if(document.getElementById('cAgree')){
            var checkboxAgree = document.getElementById('cAgree');
            if(checkboxAgree.checked != true)
            {
                document.getElementById('cAgree_error').innerHTML = 'Please agree to Terms & Conditions.';
                document.getElementById('cAgree_error').parentNode.style.display = 'inline';
                flag2 = false;
            }
            else {
                document.getElementById('cAgree_error').innerHTML = 'Please agree to Terms & Conditions.';
                document.getElementById('cAgree_error').parentNode.style.display = 'none';
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
				orangeButtonDisableEnableWithEffect('Im_interestBtn_n',false);
                return false;
            }
        } else {
            if(flag == true && flag1 == true && flag2 == true){
                email_id = document.getElementById('reqInfoEmail').value;
                phone_no = document.getElementById('reqInfoPhNumber_foralert').value;
                display_name = document.getElementById('reqInfoDispName_foralert').value;
                validate('securityCodeforgetalert','seccodeforlistingalert','getfreealertCaptacha');
            } else {
				orangeButtonDisableEnableWithEffect('Im_interestBtn_n',false);
                return false;
            }
        }
    } catch (ex){
        if (debugMode){
            throw ex;
        } else {
            logJSErrors(ex);
        }
    }    
}
// 'boxid','var-in-image','captchaId'
function validate(secCodeTextboxid,secCode,captchaId)
{
        if(document.getElementById(secCodeTextboxid)){
        var ObjectOfSecCode = document.getElementById(secCodeTextboxid);
        var caption = ObjectOfSecCode.getAttribute('caption');
        var url = "/MultipleApply/MultipleApply/get_free_alerts/"+ObjectOfSecCode.value+"/"+secCode;
        new Ajax.Request(url, { method:'post',onSuccess:function (request){
                    if(trim(request.responseText)=='true'){
						orangeButtonDisableEnableWithEffect('Im_interestBtn_n',true);
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
                email_id = document.getElementById('reqInfoEmail').value;
                phone_no = document.getElementById('reqInfoPhNumber_foralert').value;
                display_name = document.getElementById('reqInfoDispName_foralert').value;
            if(trim(request.responseText) == 'thanks') {
            document.getElementById('before_get_free_alerts').style.display = 'none';
            document.getElementById('after_get_free_alerts').style.display = 'block';
             window.scrollTo(0,0);
            } else if (trim(request.responseText) == 'login') {
                flag_get_free_alert = true;
                //closeMessage();
                displayMessage('/MultipleApply/MultipleApply/showoverlay/1',500,260);
                return false;
            } else if (trim(request.responseText) == 'register') {
                flag_get_free_alert = true;
                //closeMessage();
                displayMessage('/MultipleApply/MultipleApply/showoverlay/4',665,380);
                return false;
            }
        }
    });
}

try{
    document.getElementById('resolution_get_free_alert').value = getCoordinates(); 
    document.getElementById('coordinates_get_free_alert').value = getResolution(document.getElementById('get_free_alerts_frm'));
    document.getElementById('referer_get_free_alert').value = location.href;  
}catch (ex) {
    if (debugMode){
        throw ex;
    } else {
        logJSErrors(ex);
    }
}
</script>
