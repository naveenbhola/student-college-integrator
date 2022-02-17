<?php

?>
<div style="width:500px;margin: 0pt auto;display:none;" id="mainApplyDiv">
    <form id="requestEbrochure" name="myForm1" onSubmit="orangeButtonDisableEnableWithEffect('submitRegisterFormButton',true);validate_form(); return false;" novalidate>
    <input type = "hidden" name = "resolution" id = "resolution" value = ""/>
    <input type = "hidden" name = "coordinates" id = "coordinates" value = ""/>
    <input type = "hidden" name = "referer" id = "referer" value = ""/>
    <input type = "hidden" name = "loginproductname" id = "loginproductname" value = ""/>
	<div class="blkRound">
		<div class="bluRound">
            	<span class="float_R"><img class="pointer" onclick="$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';closeMessage();" src="/public/images/fbArw.gif" border="0"/></span>
                <span class="title">Email Comparison</span>
                <div class="clear_B"></div>
        </div>
		<div class="whtRound" style="padding:10px">
            <div class="fontSize_14p bld">Please provide following details about yourself:</div>            
            <div class="lineSpace_10">&nbsp;</div>
            <div style="padding-bottom:10px">
                <div class="float_L" style="width:100px;line-height:20px"><div class="txt_align_r">Name: &nbsp;</div></div>
                <div style="margin-left:100px" class="OrgangeFont">
                    <div><input value="<?php echo $firstname;?>" id="usr_name"  tip="multipleapply_name" caption="Name" validate="validateDisplayName" required="true" maxlength="25" minlength="3" profanity="true" type="text" name="usr_name" style="width:185px" /></div>
                    <div style="display:none"><div class="errorMsg" id="usr_name_error" style="padding-left:3px;"></div></div>
                    <div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
                </div>
            </div>    
            <div style="padding-bottom:10px">
                <div class="float_L" style="width:100px;line-height:20px"><div class="txt_align_r">Email:&nbsp;</div></div>
                <div style="margin-left:100px" class="OrgangeFont">
                    <div><input profanity="true" required="true" tip="multipleapply_email" name="contact_email" value="<?php if(!empty($cookiestr)) { $a = $cookiestr; $b = explode('|',$a); echo $b[0]; } ?>" id="contact_email" type="text" validate="validateEmail"  maxlength="100" minlength="10" style="width:185px" caption="email" /></div>
                    <div style="display:none"><div class="errorMsg" style="padding-left:3px;" id="contact_email_error"></div></div>
                    <div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
                </div>
            </div>    
            <div style="padding-bottom:10px">
                <div class="float_L" style="width:100px;line-height:20px"><div class="txt_align_r">Mobile Phone: &nbsp;</div></div>
                <div style="margin-left:100px">
                    <div><input value="<?php echo $mobile;?>" profanity="true" id="mobile_phone" type="text" name="mobile_phone" validate="validateMobileInteger" required="true" maxlength="10" minlength="10" tip="multipleapply_cell" caption="mobile phone" style="width:185px" /></div>
                    <div class="graycolor" style="font-size:10px">eg: 9871787683</div>
                    <div style="display:none"><div class="errorMsg" id="mobile_phone_error" style="padding-left:3px;" ></div></div>
                </div>
                <div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
            </div>
			<div style="margin-left:100px">
			<?php if (empty($displayname)) { ?>
				<script type="text/javascript">
					flagSignedUser = true; 
				</script>     
				<div>
					<div class="fontSize_11p" style="line-height:15px">Type in the characters you see in the picture below</div>
				</div>
				<div class="lineSpace_4">&nbsp;</div>
				<div>
					<img align = "top" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeIndex" width="100" height="34"  id = "secureCode"/>
					<input type="text" style="margin-left:3px;width:135px;font-size:12px;margin-top:12px;" name = "homesecurityCode" id = "homesecurityCode" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
				</div>
				<div>
					<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
						<div style="margin-left:3px;" class="errorMsg" id= "homesecurityCode_error"></div>
					</div>
				</div>
				<div class="lineSpace_4">&nbsp;</div>
				<div class="row">
					<input type="checkbox" name="cAgree" id="requestEbrochure_cAgree" checked />
					I agree to the <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">Terms and Conditions</a> and <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">Privacy Policy</a>
				</div>
				<div>
					<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
						<div class="errorMsg" id= "requestEbrochure_cAgree_error" style="padding-left:4px;"></div>
					</div>
				</div>		
		<?php } else {
			if ((!empty($displayname)) && (!empty($b[0]))) {
		?>
                <script>
                        document.getElementById('contact_email').disabled=true;
                </script>        
                <?php    
                    }
                } ?>
            <div style="margin-top:4px;"><input type="submit" class="fbBtn"  value="Submit" id="submitRegisterFormButton" uniqueattr="CategoryPageEmailButton"/></div>
			</div>
		</div>
	</div>
        <!--End Request E-Brochure-->
    <script type="text/javascript">
        /*  :(    */
        function overlayHackLayerForIE(overlayId, spanArea){
            try {
                var iframeElement = document.getElementById('iframe_div');
                if(iframeElement != null) {
                    var iframStyleElement = iframeElement.style;
                    if(iframeElement.getAttribute('container') !='' && iframeElement.getAttribute('container') != overlayId && iframStyleElement.display != 'none') {
                        dissolveOverlayHackForIE();
                    }
                    iframeElement.setAttribute('container',overlayId);
                    iframeElement.container = overlayId;
                    iframStyleElement.display = 'block';
                    iframStyleElement.width = spanArea.offsetWidth + 'px';
                    iframStyleElement.height = spanArea.offsetHeight + 'px';
                    iframStyleElement.top = obtainPostitionY(spanArea) +'px';
                    iframStyleElement.left = obtainPostitionX (spanArea)+'px';
                    if (document.getElementById(overlayId).style.zIndex != 0 || document.getElementById(overlayId).style.zIndex!= '') {
                        iframStyleElement.zIndex = parseInt(document.getElementById(overlayId).style.zIndex) -1;
                    } else {
                        iframStyleElement.zIndex = 1000;
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
        /*  :(    */
        function dissolveOverlayHackForIE(){
            if(document.getElementById('iframe_div')) {
                try{
                if(document.getElementById(document.getElementById('iframe_div').getAttribute('container')))
                    document.getElementById(document.getElementById('iframe_div').getAttribute('container')).style.display = 'none';
                }catch(e) { }
                document.getElementById('iframe_div').style.display = 'none';
            }
        }
        var flag_totalChecked;
        function validate_form() {
            try{
				
                if (document.getElementById('helpbubble')) {
                    document.getElementById('helpbubble').style.display='none';
                }
                var flag = validateFields(document.getElementById('requestEbrochure'));
                if (flagSignedUser == true ) {
                    var checkboxAgree = document.getElementById('requestEbrochure_cAgree');
                    var flag2 = true;
                    if(checkboxAgree.checked != true)
                    {
                        var flag2 = false;
                        document.getElementById('requestEbrochure_cAgree_error').innerHTML = 'Please agree to Terms & Conditions.';
                        document.getElementById('requestEbrochure_cAgree_error').parentNode.style.display = 'inline';
						orangeButtonDisableEnableWithEffect('submitRegisterFormButton',false);
						$('mainApplyDiv').style.display = 'block';
                        return false;
                    }
                    else
                    {
                        document.getElementById('requestEbrochure_cAgree_error').innerHTML = '';
                        document.getElementById('requestEbrochure_cAgree_error').parentNode.style.display = 'none';
                    }
                }
                email_id = document.getElementById('contact_email').value;
                phone_no = document.getElementById('mobile_phone').value;
                display_name = document.getElementById('usr_name').value;

                if (flagSignedUser == true ) {
                    if((flag == true) && (flag2 == true)) {
                        validateCaptcha1('homesecurityCode','secCodeIndex','secureCode');
                    } else {
						orangeButtonDisableEnableWithEffect('submitRegisterFormButton',false);
						$('mainApplyDiv').style.display = 'block';
                        return false;
                    }
                } else {
                    if((flag == true)) {
                        processData();
                    } else {
      					orangeButtonDisableEnableWithEffect('submitRegisterFormButton',false);
						$('mainApplyDiv').style.display = 'block';
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
		recommendation_json = {};
        function processData()
        {
			orangeButtonDisableEnableWithEffect('submitRegisterFormButton',false);
			var sJSON = "{";
			var flag_count = 0;
			for (var i=0; i < cookieArr.length; i++)
			{
				var institute = cookieArr[i].split("::");
				var instituteId = institute[0];
				var instituteName = encodeURIComponent(institute[3]);
				var courseId = currentCompareCourses[i];
					sJSON += '"'+instituteId+'": [ "';
					sJSON += '';
					sJSON += '", "';
					sJSON += instituteName;
					sJSON += '", "';
					sJSON += '';
					sJSON += '", "';
					sJSON += courseId;
					sJSON += '", "';
					sJSON += 'course';
					sJSON += '" ]';
					if (i < cookieArr.length-1) {
						sJSON += ',';
					}
					flag_count++;
			}
			sJSON += '}';
			trackEventByGA('LinkClick','CATEGORY_COMPARE_EMAIL_LAYER_FINAL/'+flag_count);
			var paraString = "reqInfoDispName="+document.getElementById('usr_name').value+"&reqInfoPhNumber="+document.getElementById('mobile_phone').value+"&reqInfoEmail="+document.getElementById('contact_email').value+"&jSON="+sJSON+"&pageurl="+encodeURIComponent($categorypage.currentUrl)+"&cookieKey="+$categorypage.key+"&resolution="+document.getElementById('resolution').value+"&coordinates="+document.getElementById('coordinates').value+"&loginproductname="+document.getElementById('loginproductname').value+"&referer="+document.getElementById('referer').value;
			if (flagSignedUser == true ) {
				paraString +="&captchatext="+document.getElementById("homesecurityCode").value;
			}       

			var url = "/MultipleApply/MultipleApply/emailCompareLayer";
			new Ajax.Request(url, { method:'post', parameters: (paraString), onSuccess:function (request){
				show_unified_regis = true;
				arr_unified[0] = '1';
				var identify_widget = 'requestebrochure';
				arr_unified[1] = $('category_unified_id').value;
				ShikshaUnifiedRegistarion.url_unified = ShikshaUnifiedRegistarion.ajaxUrlHelper(arr_unified);
				if(trim(request.responseText) == 'thanks') {
					if(show_unified_regis == true && unified_registration_is_ldb_user == 'false' && (arr_unified[0] == '1' && unified_form_overlay1_cancel_clicked != 'true')) {
						$('categorypage_unified_thankslayer_identifier').value = 'thanks';
						closeMessage();
						callUnifiedOverlay(ShikshaUnifiedRegistarion.url_unified,600,100,'categorypagenew',identify_widget);
					}else{
						closeMessage();
						displayMessage('/MultipleApply/MultipleApply/showoverlay/19',500,50);
					}
				}else if(trim(request.responseText) == 'login') {
					closeMessage();
					displayMessage('/MultipleApply/MultipleApply/showoverlay/18',600,260);
					return false;
				}else if(trim(request.responseText) == 'register') {
					//alert("4");
					if(show_unified_regis == true && unified_registration_is_ldb_user == 'false' && (arr_unified[0] == '1' && unified_form_overlay1_cancel_clicked != 'true')) {
						$('categorypage_unified_thankslayer_identifier').value = 'register';
						closeMessage();
						callUnifiedOverlay(ShikshaUnifiedRegistarion.url_unified,600,100,'categorypagenew',identify_widget);
					}else{
						closeMessage();
						displayMessage('/MultipleApply/MultipleApply/showoverlay/19',500,50);
					}
					return false;
				}else{
					orangeButtonDisableEnableWithEffect('submitRegisterFormButton',false);
					$('mainApplyDiv').style.display = 'block';
					return false;
				}
			}
			});       
        }

        function validateCaptcha1(secCodeTextid,secodeIndex,captchaId)
        {
            try{
                if(document.getElementById(secCodeTextid)){
                    var ObjectOfSecCode = document.getElementById(secCodeTextid);
                    var caption = ObjectOfSecCode.getAttribute('caption');
                    var url = "/MultipleApply/MultipleApply/get_free_alerts/"+ObjectOfSecCode.value+"/"+secodeIndex;
                
                new Ajax.Request(url, { method:'post',onSuccess:function (request){
                            if(trim(request.responseText)=='true'){ 
                                processData();
                                return true;
                            }
                            else {
                                reloadCaptcha(captchaId,secodeIndex);
								orangeButtonDisableEnableWithEffect('submitRegisterFormButton',false);
								$('mainApplyDiv').style.display = 'block';
                                document.getElementById(secCodeTextid+'_error').parentNode.style.display = 'inline';
                                document.getElementById(secCodeTextid+'_error').innerHTML = "Please enter the "+caption+" as shown in the image";
                                return false;
                            }
                        } 
                        });
                    return false;
                }
            } catch (ex){
                if (debugMode){
                    throw ex;
                } else {
                    logJSErrors(ex);
                }
            }          
        }
    document.getElementById('resolution').value = getCoordinates(); 
    document.getElementById('coordinates').value = getResolution(document.getElementById('requestEbrochure'));
    document.getElementById('loginproductname').value = 'COMPARE_EMAIL';
    document.getElementById('referer').value = location.href;  
    </script>
    </form>
</div>
<script>
	category_course_base_url = "COMPARE_3";
	trackEventByGA('LinkClick','CATEGORY_COMPARE_EMAIL_LAYER_CLICK');
	try {
		if($('contact_email').value != ""){
			userlogin = true;
		}else{
			userlogin = false;
		}

		if ($('contact_email').value != ""  && $('mobile_phone').value != '') {
			$('mainApplyDiv').style.display = 'none';
			validate_form();
		}else{
			$('mainApplyDiv').style.display = 'block';

		}
		
	}catch (ex){
		if (debugMode){
			throw ex;
		} else {
			logJSErrors(ex);
		}
	}   
</script>
