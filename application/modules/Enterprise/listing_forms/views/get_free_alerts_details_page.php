<div id="before_get_free_alerts_detail" style="display:block">
    <form  name="get_free_alerts" id="get_free_alerts_frm_detail" method="post" >
        <?php if ($listing_type == 'institute') { ?>
        <input type="hidden" name="loginproductname_get_free_alert_detail" id="loginproductname_get_free_alert_detail"  value="NAUKRISHIKSHA_DETAIL_BOTTOM_GET_FREE_ALERTS_INSTITUTE"/>
        <?php } elseif ($listing_type == 'course') { ?>
        <input type="hidden" name="loginproductname_get_free_alert_detail" id="loginproductname_get_free_alert_detail"  value="NAUKRISHIKSHA_DETAIL_BOTTOM_GET_FREE_ALERTS__COURSE"/>
        <?php } ?>
        <input type="hidden" name="resolution_get_free_alert_detail" id="resolution_get_free_alert_detail"  value=""/>
        <input type="hidden" name="referer_get_free_alert_detail" id="referer_get_free_alert_detail"  value=""/>
        <input type="hidden" name="coordinates_get_free_alert_detail" id="coordinates_get_free_alert_detail"  value=""/>
    <div class="raised_10">
        <b class="b2"></b><b class="b3"></b><b class="b4"></b>
            <div class="boxcontent_10">
                <div class="bgRequestInfoHeader">
                    <div class="requestIcon"><div style="font-size:21px;padding-top:6px"><b>I AM INTERESTED</b></div></div>
                </div>
                <div style="line-height:10px"><a name="btmForm">&nbsp;</a></div>
                <div style="margin:0 57px 0 15px">
                    <div style="width:100%">
						<div style="padding-top:5px">Interested in studying at <b class="OrgangeFont"><?php echo $details['title']; ?></b></div>
						<div style="line-height:8px">&nbsp;</div>
						<div>Fill Details for the <span class="bld">Institute to counsel you</span></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div style="padding-bottom:5px">Also receive</div>
						<div>
							<span class="blakDot">Institute brochure and updates on email</span>
							<span class="blakDot" style="margin-left:20px">SMS of important dates</span>
						</div>
                    </div>
                </div>
                <div style="line-height:16px">&nbsp;</div>
                <div style="margin:0 10px 0 15px">
                    <div style="float:left;width:235px">
                        <div style="padding-bottom:1px">Name:<span class="redcolor">*</span></div>
                        <div><input type="text" name="reqInfoDispName_foralert_detail" id="reqInfoDispName_foralert_detail"  value="<?php if (isset($validateuser[0]['firstname'])) { echo $validateuser[0]['firstname'];  } ?>" <?php if (isset($validateuser[0]['cookiestr'])) {  echo "";  } ?> maxlength = "25" minlength = "3" validate="validateDisplayName"  required="true"  caption="Name" autocomplete="off" style="width:180px;<?php if (isset($validateuser[0]['firstname'])) { echo 'color:#000;background:#FFF;border:1px solid #000;';} ?>" /></div>
                        <div class="errorPlace"><div class="errorMsg" id="reqInfoDispName_foralert_detail_error"></div></div>
                    </div>
					<?php if(count($courseList) > 0) { ?>
					<div style="float:left;width:200px">
                        <div style="padding-bottom:1px">Course of interest:<span class="redcolor">*</span></div>
                        <div>
							<select name="listing_type_id_course" id="listing_type_id_detail" unrestricted="true" validate="validateSelect" required="1"  caption="Course of interest" style="width:200px;font-size:12px;font-family:Arial;padding:1px 0<?php if (isset($validateuser[0]['cookiestr'])) {  echo 'color:#acacac;background:#FFF;border:1px solid #CCC;';  } ?>">
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
                        <div class="errorPlace"><div class="errorMsg" id="listing_type_id_detail_error"></div></div>
                    </div>
					<?php } ?>
					<div class="clear_L" style="line-height:15px;height:14px">&nbsp;</div>
                    <div style="float:left;width:235px">
                        <div style="padding-bottom:1px">Email:<span class="redcolor">*</span></div>
                        <div><input type="text" name="reqInfoEmail_detail" id="reqInfoEmail_detail"  value="<?php if (isset($validateuser[0]['cookiestr'])) { $emailArr = explode("|",$validateuser[0]['cookiestr']); echo $emailArr[0];  } ?>" <?php if (isset($validateuser[0]['cookiestr'])) {  echo "readonly";  } ?> maxlength = "125" validate = "validateEmail" required="true"  caption="Email-Id" autocomplete="off" style="width:180px;<?php if (isset($validateuser[0]['cookiestr'])) {  echo 'color:#acacac;background:#FFF;border:1px solid #CCC;';  } ?>" /></div>
                        <div class="errorPlace"><div class="errorMsg" id="reqInfoEmail_detail_error"></div></div>
                    </div>
                    <div style="float:left;width:200px">
                        <div style="padding-bottom:1px">Mobile Number:<span class="redcolor">*</span></div>
                        <div><input type="text" name="reqInfoPhNumber_foralert_detail" id="reqInfoPhNumber_foralert_detail"  value="<?php if (isset($validateuser[0]['mobile'])) { echo $validateuser[0]['mobile']; } ?>"  maxlength = "10" minlength = "10" validate = "validateMobileInteger" required="true"  caption="mobile Number" autocomplete="off" style="width:200px;<?php if (isset($validateuser[0]['mobile'])) { echo 'color:#000;background:#FFF;border:1px solid #000;'; } ?>" /></div>
                        <div class="errorPlace"><div class="errorMsg" id="reqInfoPhNumber_foralert_detail_error"></div></div>
                    </div>
                    <div class="clear_L withClear">&nbsp;</div>
                </div>
                <?php if (empty($validateuser[0]['firstname'])) { ?>
                    <div style="padding:12px 18px 0 17px;font-size:11px">Type in the characters you see in the picture below </div>
                    <div style="padding:3px 0 0 17px"><img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccodeforlistingalert_detail" width="100" height="40"  id = "getfreealertCaptacha_detail"/>&nbsp;<input type="text" name = "securityCodeforgetalert_detail" id = "securityCodeforgetalert_detail" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code" autocomplete="off" style="width:100px;position:relative;top:-5px" /></div>
                    <div class="errorPlace"><div style="padding-left:20px;" class="errorMsg" id="securityCodeforgetalert_detail_error"></div></div>
                <?php } ?>
                <div style="line-height:15px">&nbsp;</div>
                <div style="margin:0 57px 0 15px">
                    <div><input type="button" onclick="return run_click_detail();" class="continueBtn" id="Im_interestBtn_nb" value="Submit" /></div>
					<div style="line-height:7px">&nbsp;</div>
					<div style="font-size:11px">By Submitting you argee to <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms &amp; conditions</a></div>
                </div>
                <div style="line-height:10px"><a name="btmForm">&nbsp;</a></div>
            </div>
        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
    </div>
    </form>
</div>
<div class="raised_10" style="display:none;" id="after_get_free_alerts_detail">
        <b class="b2"></b><b class="b3"></b><b class="b4"></b>
            <div class="boxcontent_10" style="background: url(/public/images/bgRequestInfoHeader.gif) repeat-x left bottom;height:50px">
            <div class="bgRequestInfoHeader">
					<div class="requestIcon" style="height:63px"><div style="font-size:17px;padding-top:4px"><b>Your request has been successfully submitted.</b></div></div>
                </div>
            </div>
        <b class="b4b" style="background:#ffe45d"></b><b class="b3b"  style="background:#ffe45d"></b><b class="b2b" style="background:#ffe45d"></b><b class="b1b"></b>
</div>
<div id="gotoBottom">&nbsp;</div>
<script>
window.onerror = function() {
    if (debugMode){
        throw ex;
    } else {
        logJSErrors(ex);
    }
    return true;
}

function submit_form_getFreeAlerts_detail()
{
    try{
        var flag = validateFields(document.getElementById('get_free_alerts_frm_detail'));
        var flag1 = true; var flag2 = true;
        if(trim(document.getElementById("reqInfoPhNumber_foralert_detail").value) == "")
        {
            document.getElementById("reqInfoPhNumber_foralert_detail_error").innerHTML = "Please enter your correct mobile number";
            document.getElementById("reqInfoPhNumber_foralert_detail_error").parentNode.style.display = "inline";
            flag1 = false;
        }
        if(document.getElementById('cAgree_detail')){
            var checkboxAgree = document.getElementById('cAgree_detail');
            if(checkboxAgree.checked != true)
            {
                document.getElementById('cAgree_detail_error').innerHTML = 'Please agree to Terms & Conditions.';
                document.getElementById('cAgree_detail_error').parentNode.style.display = 'inline';
                flag2 = false;
            }
            else {
                document.getElementById('cAgree_detail_error').innerHTML = 'Please agree to Terms & Conditions.';
                document.getElementById('cAgree_detail_error').parentNode.style.display = 'none';
                flag2 = true;
            }
        }

        if (flagSignedUser) {

            if (flag == true ) {
                email_id = document.getElementById('reqInfoEmail_detail').value;
                phone_no = document.getElementById('reqInfoPhNumber_foralert_detail').value;
                display_name = document.getElementById('reqInfoDispName_foralert_detail').value;
                updateRequestInfo_detail();
            } else {
				orangeButtonDisableEnableWithEffect('Im_interestBtn_nb',false);
                return false;
            }
        } else {
            if(flag == true && flag1 == true && flag2 == true){
                email_id = document.getElementById('reqInfoEmail_detail').value;
                phone_no = document.getElementById('reqInfoPhNumber_foralert_detail').value;
                display_name = document.getElementById('reqInfoDispName_foralert_detail').value;
                validate_detail('securityCodeforgetalert_detail','seccodeforlistingalert_detail','getfreealertCaptacha_detail');
            } else {
				orangeButtonDisableEnableWithEffect('Im_interestBtn_nb',false);
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
function validate_detail(secCodeTextboxid,secCode,captchaId)
{
        if(document.getElementById(secCodeTextboxid)){
        var ObjectOfSecCode = document.getElementById(secCodeTextboxid);
        var caption = ObjectOfSecCode.getAttribute('caption');
        var url = "/MultipleApply/MultipleApply/get_free_alerts/"+ObjectOfSecCode.value+"/"+secCode;
        new Ajax.Request(url, { method:'post',onSuccess:function (request){
                    if(trim(request.responseText)=='true'){
						orangeButtonDisableEnableWithEffect('Im_interestBtn_nb',true);
                        updateRequestInfo_detail();
						trackEventByGA('IAmInterestForm','BottomPanel');
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
function updateRequestInfo_detail()
{
    var sJSON = '{';
    sJSON += '"'+document.getElementById("listing_type_id").value+'": [ "'+encodeURIComponent(document.getElementById("listing_url").value)+'", "'+encodeURIComponent(document.getElementById("listing_title").value)+'", "'+document.getElementById("listing_type").value+'", "'+document.getElementById("listing_type_id_detail").value+'", "course"]';
    sJSON += '}';
    var paraString = "reqInfoDispName="+document.getElementById('reqInfoDispName_foralert_detail').value+"&reqInfoPhNumber="+document.getElementById('reqInfoPhNumber_foralert_detail').value+"&reqInfoEmail="+document.getElementById('reqInfoEmail_detail').value+"&jSON="+sJSON;
    if (flagSignedUser == false) {
    paraString +="&captchatext="+document.getElementById("securityCodeforgetalert_detail").value;
    }
    paraString +="&flag_check=1"+"&resolution="+document.getElementById('resolution_get_free_alert_detail').value+"&coordinates="+document.getElementById('coordinates_get_free_alert_detail').value+"&loginproductname="+document.getElementById('loginproductname_get_free_alert_detail').value+"&referer="+document.getElementById('referer_get_free_alert_detail').value;
    var url = "/MultipleApply/MultipleApply/getBrochureRequest";
    new Ajax.Request(url, { method:'post', parameters: (paraString), onSuccess:function (request){
                email_id = document.getElementById('reqInfoEmail_detail').value;
                phone_no = document.getElementById('reqInfoPhNumber_foralert_detail').value;
                display_name = document.getElementById('reqInfoDispName_foralert_detail').value;
            if(trim(request.responseText) == 'thanks') {
            document.getElementById('before_get_free_alerts_detail').style.display = 'none';
            document.getElementById('after_get_free_alerts_detail').style.display = 'block';
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
    document.getElementById('resolution_get_free_alert_detail').value = getCoordinates();
    document.getElementById('coordinates_get_free_alert_detail').value = getResolution(document.getElementById('get_free_alerts_frm_detail'));
    document.getElementById('referer_get_free_alert_detail').value = location.href;
}catch (ex) {
    if (debugMode){
        throw ex;
    } else {
        logJSErrors(ex);
    }
}
</script>
