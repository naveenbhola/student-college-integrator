<div style="width:500px">
    <form id="requestEbrochure" name="myForm" onSubmit="validate_form(); return false;" novalidate>
    <input type = "hidden" name = "resolution" id = "resolution" value = ""/>
    <input type = "hidden" name = "coordinates" id = "coordinates" value = ""/>
    <input type = "hidden" name = "referer" id = "referer" value = ""/>
    <input type = "hidden" name = "loginproductname" id = "loginproductname" value = ""/>
    <div style="background:#FFFFFF;border:1px solid #d6e6f6">
        <!--Start Request E-Brochure-->
        <div class="titleBarLay">
            <div class="float_R">
                <div style="padding-top:12px"><img onclick="setTimeout('window.location.reload();', 300);" src="/public/images/crossImg_14_12.gif" border="0" class="searchCareer" /></div>
            </div>
            <div class="fontSize_16p bld">Request E-Brochure</div>
            <div class="clear_B" style="font-size:1px;line-height:1px">&nbsp;</div>
        </div>
        <div style="padding:10px 15px;">
            <div style="display:none;background:#ffe9eb;line-height:24px;padding:0 0px;color:#FF0000;font-size:14px;padding-right:40px;" id="flag_totalChecked_error" class="bld">&nbsp;<b>ERROR! Please select atleast one Institute to proceed</b>&nbsp;</div>
            <div class="lineSpace_5">&nbsp;</div>
            <div class="OrgangeFont fontSize_14p bld">You have chosen to request free brochure from:</div>
            <div class="lineSpace_5">&nbsp;</div>
            <div id="c_value_html_innerhtml" style="line-height:20px;overflow:auto"></div>
            
            <div class="lineSpace_20">&nbsp;</div>
            <div class="OrgangeFont fontSize_14p bld">Please provide following details about yourself</div>            
            <div class="lineSpace_10">&nbsp;</div>
            <div style="padding-bottom:10px">
                <div class="float_L" style="width:100px;line-height:20px"><div class="txt_align_r">Name:<span class="redcolor">*</span> &nbsp;</div></div>
                <div style="margin-left:100px" class="OrgangeFont">
                    <div><input value="<?php echo $firstname;?>" id="usr_name"  tip="multipleapply_name" caption="Name" validate="validateDisplayName" required="true" maxlength="25" minlength="3" profanity="true" type="text" name="usr_name" style="width:185px" /></div>
                    <div style="display:none"><div class="errorMsg" id="usr_name_error" style="padding-left:3px;"></div></div>
                    <div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
                </div>
            </div>    
            <div style="padding-bottom:10px">
                <div class="float_L" style="width:100px;line-height:20px"><div class="txt_align_r">Email:<span class="redcolor">*</span>&nbsp;</div></div>
                <div style="margin-left:100px" class="OrgangeFont">
                    <div><input profanity="true" required="true" tip="multipleapply_email" name="contact_email" value="<?php if(!empty($cookiestr)) { $a = $cookiestr; $b = explode('|',$a); echo $b[0]; } ?>" id="contact_email" type="text" validate="validateEmail"  maxlength="100" minlength="10" style="width:185px" caption="email" /></div>
                    <div style="display:none"><div class="errorMsg" style="padding-left:3px;" id="contact_email_error"></div></div>
                    <div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
                </div>
            </div>    
            <div style="padding-bottom:10px">
                <div class="float_L" style="width:100px;line-height:20px"><div class="txt_align_r">Mobile Phone:<span class="redcolor">*</span> &nbsp;</div></div>
                <div style="margin-left:100px" class="OrgangeFont">
                    <div><input value="<?php echo $mobile;?>" profanity="true" id="mobile_phone" type="text" name="mobile_phone" validate="validateMobileInteger" required="true" maxlength="10" minlength="10" tip="multipleapply_cell" caption="mobile phone" style="width:185px" /></div>
                    <div class="graycolor" style="font-size:10px">eg: 9871787683</div>
                    <div style="display:none"><div class="errorMsg" id="mobile_phone_error" style="padding-left:3px;" ></div></div>
                </div>
                <div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
            </div>
                <?php if (empty($displayname)) { ?>
                    <div style="margin-left:25px">
						<script type="text/javascript">
							flagSignedUser = true; 
						</script>     
						<div>
							<div class="fontSize_11p" style="line-height:15px">Type in the characters you see in the picture below</div>
						</div>
								<div class="lineSpace_4">&nbsp;</div>
						<div>
							<img align = "absmiddle" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeIndex" width="100" height="34"  id = "secureCode"/>
							<input type="text" style="margin-left:20px;width:135px;font-size:12px" name = "homesecurityCode" id = "homesecurityCode" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
						</div>
						<div>
							<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
								<div  style="margin-left:125px" class="errorMsg" id= "homesecurityCode_error" style="padding-left:0px;" ></div>
							</div>
						</div>
						<div class="lineSpace_4">&nbsp;</div>
						
						<div class="row">
							<input type="checkbox" name="cAgree" id="requestEbrochure_cAgree" />
							I agree to the <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a> and <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a>
						</div>
						<div>
							<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
								<div class="errorMsg" id= "requestEbrochure_cAgree_error" style="padding-left:4px;" ></div>
							</div>
						</div>
					</div>
                <?php } else {
                    if ((!empty($displayname)) && (!empty($b[0]))) {
                ?>
                <script>
                        
//                         document.getElementById('usr_name').disabled=true;
                        
                        document.getElementById('contact_email').disabled=true;
                </script>        
                <?php    
                    }
                } ?>
			<div class="lineSpace_5">&nbsp;</div>
            <div align="center"><input type="submit" class="submitGlobal"  value="<?php if(!empty($cookiestr)) { echo 'Submit'; } else { echo 'Register';} ?>" id="submitRegisterFormButton" /></div>
        </div>
        <!--End Request E-Brochure-->
    </div>
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
				orangeButtonDisableEnableWithEffect('submitRegisterFormButton',true);
                if (document.getElementById('helpbubble')) {
                    document.getElementById('helpbubble').style.display='none';
                }
                var checks = document.getElementsByName('overlay[]');
                var boxLength = checks.length;
                var flag_totalChecked = 0;
                for ( j=0; j < boxLength; j++ )
                {
                    if ( checks[j].checked == true )
                    flag_totalChecked++;
                }
                if (flag_totalChecked == 0 || flag_totalChecked ==undefined){
                    document.getElementById('flag_totalChecked_error').style.display = 'inline';
					orangeButtonDisableEnableWithEffect('submitRegisterFormButton',false);
                    return false;
                } else {
                    document.getElementById('flag_totalChecked_error').style.display = 'none';
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
                        return false;
                    }
                } else {
                    if((flag == true)) {
                        processData();
                    } else {
      					orangeButtonDisableEnableWithEffect('submitRegisterFormButton',false);
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

        function processData()
        {
            try{
                var sJSON = '{';
				var localityJSON = '{';
                var checks = document.getElementsByName('overlay[]');
                var flag_count = 1;
                for (var i=0; i < checks.length; i++)
                {
                    if (checks[i].checked)
                    {
                        var a = 'reqEbr_'+checks[i].value;
                        sJSON += '"'+checks[i].value+'": [ "'+encodeURIComponent(document.getElementById(a).getAttribute('url'))+'", "'+encodeURIComponent(document.getElementById(a).getAttribute('title'))+'", "'+document.getElementById(a).getAttribute('type')+'" ]';
                        if (flag_count != checks.length ) {
                            sJSON += ',';
                        }
						
						/*
						 Add locality info
						*/
						if(document.getElementById('preferred_city_category_'+checks[i].value))
						{
							if (flag_count > 1)
							{
								localityJSON += ',';
							}
							
							var sb_preferred_city = document.getElementById('preferred_city_category_'+checks[i].value);
							var sb_preferred_locality = document.getElementById('preferred_locality_category_'+checks[i].value);
							
							var preferred_city = sb_preferred_city.options[sb_preferred_city.selectedIndex].value;
							var preferred_locality = sb_preferred_locality.options[sb_preferred_locality.selectedIndex].value;
							
							localityJSON += '"'+checks[i].value+'": [ "'+preferred_city+'", "'+preferred_locality+'" ]';
						}
                    }
                    flag_count++;
                }
                sJSON += '}';
				localityJSON += '}';
                var paraString = "reqInfoDispName="+document.getElementById('usr_name').value+"&reqInfoPhNumber="+document.getElementById('mobile_phone').value+"&reqInfoEmail="+document.getElementById('contact_email').value+"&jSON="+sJSON+"&localityJSON="+localityJSON+"&resolution="+document.getElementById('resolution').value+"&coordinates="+document.getElementById('coordinates').value+"&loginproductname="+document.getElementById('loginproductname').value+"&referer="+document.getElementById('referer').value;
                if (flagSignedUser == true ) {
                    paraString +="&captchatext="+document.getElementById("homesecurityCode").value;
                }
                var url = "/MultipleApply/MultipleApply/getBrochureRequest";
                var action = 'search_page_hack';
		eventTriggered = action;
                new Ajax.Request(url, { method:'post', parameters: (paraString), onSuccess:function (request){
                        executeGoogleTrackingCode(); 
                         // Initialised following variables to make unified registration overlay URL 
                        if(!$('unified_search_identifier')) {
                        arr_unified[0] = '1';
                        var identify_widget = 'requestebrochure';
			if($('catselect')){
				arr_unified[1]=$('catselect').value;
                        	identify_widget = 'naukrishiksha';
                                unified_widget_identifier = 'naukrishiksha';
			}else{
				arr_unified[1] = $('category_unified_id').value;
			}
                        var show_unified_regis = true;
//                         if(arr_unified[1] && (arr_unified[1] == '4' || arr_unified[1] == '9' || arr_unified[1] == '11')) {
//                         	show_unified_regis = false;
//                         }
			ShikshaUnifiedRegistarion.url_unified = ShikshaUnifiedRegistarion.ajaxUrlHelper(arr_unified);
                        }
                         // Code modified for Unified Registration starts here
                        if(trim(request.responseText) == 'thanks') {
                            closeMessage();
                            if(!$('unified_search_identifier') && show_unified_regis == true && unified_registration_is_ldb_user == 'false' && (arr_unified[0] == '1' && unified_form_overlay1_cancel_clicked != 'true')) {
                            $('categorypage_unified_thankslayer_identifier').value = 'thanks';
			    callUnifiedOverlay(ShikshaUnifiedRegistarion.url_unified,600,100,'categorypage',identify_widget);
                            } else{
                            if (flag_get_free_alert) {    
                                displayMessage('/MultipleApply/MultipleApply/showoverlay/5',500,50);
                            } else {                               
                                displayMessage('/MultipleApply/MultipleApply/showoverlay/2',500,50);
                            }
                                setTimeout('window.location.reload();', 3000);
                            }
                            return false;
                        } else if (trim(request.responseText) == 'login') {
                            closeMessage();
                            displayMessage('/MultipleApply/MultipleApply/showoverlay/1',500,260);
                            /*if(unified_registration_is_ldb_user == 'false' && (arr_unified[0] == '1' && unified_form_overlay1_cancel_clicked != 'true')) {
                            	$('categorypage_unified_thankslayer_identifier').value = 'login';
			    	callUnifiedOverlay(ShikshaUnifiedRegistarion.url_unified,600,100,'categorypage');
                            } else{
                            	displayMessage('/MultipleApply/MultipleApply/showoverlay/1',500,260);
                             }*/
                            return false;
                        } else if (trim(request.responseText) == 'register') {
                            closeMessage();
                            if(!$('unified_search_identifier') && show_unified_regis == true && unified_registration_is_ldb_user == 'false' && (arr_unified[0] == '1' && unified_form_overlay1_cancel_clicked != 'true')) {
                            	$('categorypage_unified_thankslayer_identifier').value = 'register';
			    	callUnifiedOverlay(ShikshaUnifiedRegistarion.url_unified,600,100,'categorypage',identify_widget);
                            } else{
                                if($('unified_search_identifier')) {
                                displayMessage('/MultipleApply/MultipleApply/showoverlay/4',665,380);
                                } else {
                                displayMessage('/MultipleApply/MultipleApply/showoverlay/2',500,50);
                                setTimeout('window.location.reload();', 3000);
                                }
                              }
                              // Code modified for Unified Registration ENDS here
//                            displayMessage('/MultipleApply/MultipleApply/showoverlay/4',665,380);
                            return false;
                        }else{
							orangeButtonDisableEnableWithEffect('submitRegisterFormButton',false);
							return false;
						}
                    }
                });
            } catch (ex){
                if (debugMode){
                    throw ex;
                } else {
                    logJSErrors(ex);
                }
            }          
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
        
        try {
            if (c_value_html != null && typeof c_value_html == "string") {
                document.getElementById('c_value_html_innerhtml').innerHTML = c_value_html;
                // if user is login then add data over here 
            }    
        } catch (ex) {
            closeMessage();
        }
        try {
            addOnBlurValidate(document.getElementById('requestEbrochure'));
            addOnFocusToopTip(document.getElementById('requestEbrochure'));
            document.getElementById('resolution').value = getCoordinates(); 
            document.getElementById('coordinates').value = getResolution(document.getElementById('requestEbrochure'));
            document.getElementById('loginproductname').value = '<?php echo $multkeyname?>';
            document.getElementById('referer').value = location.href;  
        } catch (ex){
            if (debugMode){
                throw ex;
            } else {
                logJSErrors(ex);
            }
        }

        try{
            if((totalChecked == undefined) || (totalChecked <= 6 )) {
                if (totalChecked == undefined || totalChecked == 0) {
                    /*document.getElementById('c_value_html_innerhtml').style.height = 20 + 'px';*/
                    document.getElementById('c_value_html_innerhtml').style.overflow = 'hidden';
                } else {
                    /*document.getElementById('c_value_html_innerhtml').style.height = ( 21*totalChecked ) + 'px';*/
                    document.getElementById('c_value_html_innerhtml').style.overflow = 'hidden';
                }
            } else if (totalChecked >= 6) {
                document.getElementById('c_value_html_innerhtml').style.height = 130 + 'px';
                document.getElementById('c_value_html_innerhtml').style.overflow = 'auto';
            }
        }catch (ex){
            if (debugMode){
                throw ex;
            } else {
                logJSErrors(ex);
            }
        }
        try {
            scrollwindow();
            //document.getElementById('usr_name').focus();
        }catch (ex){
            if (debugMode){
                throw ex;
            } else {
                logJSErrors(ex);
            }
        }
        // TODO : Remove following lines after release of Naurki Shiksha
        // HACK : change login product name [ Neha will introduce
        // complete framework on this ;-) ]
        try {
            if (key_loginProduct != '') {
                document.getElementById('loginproductname').value = key_loginProduct;
            }
        }catch (ex){
            if (debugMode){
                throw ex;
            } else {
                logJSErrors(ex);
            }
        }    
        var NAUKRI_SHIKSHA_SEARCH_TRACKING_IDENTIFIER = "";
        if(!$('unified_search_identifier')) {
        	if($('catselect')){
        		NAUKRI_SHIKSHA_SEARCH_TRACKING_IDENTIFIER = "NaukriShikshaPageApplyRegisterButton";
            } else {
            	NAUKRI_SHIKSHA_SEARCH_TRACKING_IDENTIFIER = "CategoryPageApplyRegisterButton";
            }
        } else if($('unified_search_identifier')) {
        	NAUKRI_SHIKSHA_SEARCH_TRACKING_IDENTIFIER = "SearchPageApplyRegisterButton";
        }
        $('submitRegisterFormButton').setAttribute("uniqueattr",NAUKRI_SHIKSHA_SEARCH_TRACKING_IDENTIFIER);  
    </script>
    </form>
</div>