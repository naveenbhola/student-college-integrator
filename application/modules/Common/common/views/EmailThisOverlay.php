<?php

?>
<div style="width:500px">
    <form id="requestListing" name="myForm" onSubmit="validate_form(); return false;">
    <input type = "hidden" name = "resolution" id = "resolution" value = ""/>
    <input type = "hidden" name = "coordinates" id = "coordinates" value = ""/>
    <input type = "hidden" name = "referer" id = "referer" value = ""/>
    <input type = "hidden" name = "loginproductname" id = "loginproductname" value = ""/>
    <div style="background:#FFFFFF;border:1px solid #d6e6f6">
        <!--Start Request E-Brochure-->
        <div class="titleBarLay">
            <div class="float_R">
                <div style="padding-top:12px"><img onclick="closeMessage();" src="/public/images/crossImg_14_12.gif" border="0" class="searchCareer" /></div>
            </div>
            <div class="fontSize_16p bld">Get Alerts for free</div>
            <div class="clear_B" style="font-size:1px;line-height:1px">&nbsp;</div>
        </div>
        <div style="padding:10px 15px;">
            	<div id="c_value_html_innerhtml" style="line-height:20px;overflow:auto"></div>
	   	<div class="lineSpace_10">&nbsp;</div>
            	<div class="fontSize_16p bld">Start Here!</div>            
            	<div class="lineSpace_10">&nbsp;</div>
		<div id="categorySelectPanel" style="display:none">
			<div style="padding-bottom:10px">
                		<div class="float_L" style="width:100px;line-height:10px">
					<div class="txt_align_r">Select Category:<span class="redcolor">*</span> &nbsp;</div>
				</div>
                		<div style="margin-left:100px">
					<div>
                                        <select required="true" name="category" id="category" class="countryCombo textboxEventW31" style="width:190px;font-size:12px">
					<option value="-1">Select</option>
					<?php
						global $categoryParentMap;
						foreach($categoryParentMap as $categoryName => $category) { ?>
                                                <option value="<?php echo $categoryName; ?>-<?php echo $category['id']; ?>" <?php echo $category['id'] == "-1" ? 'selected' :'';?>><?php echo $categoryName; ?></option>
							
					<?php } ?>
                                	</select>
					</div>
			 		<div style="display:none"><div class="errorMsg" style="padding-left:3px;" id="check_Category_error"></div></div>
					<div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
                		</div>
            		</div>
	    	</div>
           	<div style="padding-bottom:10px">
                	<div class="float_L" style="width:100px;line-height:20px">
				<div class="txt_align_r">Name:<span class="redcolor">*</span> &nbsp;</div>
			</div>
                	<div style="margin-left:100px">
                    		<div><input value="<?php echo $firstname;?>" id="usr_name"  tip="multipleapply_name" caption="Name" validate="validateDisplayName" required="true" maxlength="25" minlength="3" profanity="true" type="text" name="usr_name" style="width:185px" /></div>
                    		<div style="display:none"><div class="errorMsg" id="usr_name_error" style="padding-left:3px;"></div></div>
				<div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
                	</div>
            	</div>    
            	<div style="padding-bottom:10px">
                	<div class="float_L" style="width:100px;line-height:20px">
				<div class="txt_align_r">Email:<span class="redcolor">*</span>&nbsp;</div>
			</div>
                	<div style="margin-left:100px">
                    		<div><input profanity="true" required="true" name="contact_email" value="<?php if(!empty($cookiestr)) { $a = $cookiestr; $b = explode('|',$a); echo $b[0]; } ?>" id="contact_email" type="text" validate="validateEmail"  maxlength="100" minlength="10" style="width:185px" caption="email" /></div>
                    		<div style="display:none"><div class="errorMsg" style="padding-left:3px;" id="contact_email_error"></div></div>
                    		<div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
                	</div>
            	</div>    
		<div style="padding-bottom:10px">
			<div class="float_L" style="width:100px;line-height:20px">
				<div class="txt_align_r">Mobile Phone:<span class="redcolor">*</span> &nbsp;</div>
			</div>
        		<div style="margin-left:100px" class="OrgangeFont">
                		<div><input value="<?php echo $mobile;?>" profanity="true" id="mobile_phone" type="text" name="mobile_phone" validate="validateMobileInteger" required="true" maxlength="10" minlength="10" caption="mobile phone" style="width:185px" /></div>
	                	<div class="graycolor" style="font-size:10px">eg: 9871787683</div>
		                <div style="display:none"><div class="errorMsg" id="mobile_phone_error" style="padding-left:3px;" ></div></div>
        		</div>
			<div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
		</div>
<!--
		<div style="padding-bottom:10px">
        	        <div class="float_L" style="width:100px;line-height:20px">
                	        <div class="txt_align_r">Deliver alert via<span class="redcolor">*</span> &nbsp;</div>
	                </div>
        	        <div style="margin-left:100px">
				<div>
					<input id="Email" type="checkbox" name="Email" style="width:15px" /> Email&nbsp;&nbsp;
	        	        	<input id="Mobile" type="checkbox" name="Mobile" style="width:15px" /> Mobile
				</div>
				<div class="errorPlace" style="display:none;">
					<div class="errorMsg" id= "check_Email_error" style="padding-left:4px;" ></div>
				</div>
                	</div>
                	<div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
        	</div>
-->
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
							<input type="text" style="margin-left:20px;width:135px;font-size:12px" name = "homeSecurityCode" id = "homeSecurityCode" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
						</div>
						<div>
							<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
								<div  style="margin-left:125px" class="errorMsg" id= "homeSecurityCode_error" style="padding-left:0px;" ></div>
							</div>
						</div>
						<div class="lineSpace_4">&nbsp;</div>
						
						<div class="row">
							<input type="checkbox" name="cAgreed" id="cAgreed" />
							I agree to the <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a> and <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a>
						</div>
						<div>
							<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
								<div class="errorMsg" id= "cAgreed_error" style="padding-left:4px;" ></div>
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
            <div align="center"><input type="submit" class="submitAbuseGlobal"  value="<?php if(!empty($cookiestr)) { echo 'Submit'; } else { echo 'Join now';} ?>" id="submitRegisterFormButton" /></div>
		 <div class="lineSpace_10">&nbsp;</div>
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
        function validate_form(){
            try{
		orangeButtonDisableEnableWithEffect('submitRegisterFormButton',true);
                if (document.getElementById('helpbubble')){
                    document.getElementById('helpbubble').style.display='none';
                }
                var flag = validateFields(document.getElementById('requestListing'));
		var checkboxAgree = document.getElementById('cAgreed');
                if (flagSignedUser == true){
                    var flag2 = true;
		    if(checkboxAgree && checkboxAgree.checked != true){
                        var flag2=false;
                        document.getElementById('cAgreed_error').innerHTML = 'Please agree to Terms & Conditions.';
                        document.getElementById('cAgreed_error').parentNode.style.display = 'inline';
                        orangeButtonDisableEnableWithEffect('submitRegisterFormButton',false);
                        return false;
                    }else if(checkboxAgree){
                        document.getElementById('cAgreed_error').innerHTML = '';
                        document.getElementById('cAgreed_error').parentNode.style.display = 'none';
                    }
                }
		if(document.getElementById('categorySelectPanel').style.display!='none'){
		if(document.getElementById('category').value=='-1'){
			document.getElementById('check_Category_error').innerHTML = 'Please select any category.';
                        document.getElementById('check_Category_error').parentNode.style.display = 'inline';
                        orangeButtonDisableEnableWithEffect('submitRegisterFormButton',false);
                        return false;
		}else{
			document.getElementById('check_Category_error').innerHTML = '';
                        document.getElementById('check_Category_error').parentNode.style.display = 'none';
		}	}
                email_id = document.getElementById('contact_email').value;
                phone_no = document.getElementById('mobile_phone').value;
                display_name = document.getElementById('usr_name').value;
                if (flagSignedUser == true ) {
                    if((flag == true) && (flag2 == true)) {
                        validateCaptcha1('homeSecurityCode','secCodeIndex','secureCode');
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
                    }
                    flag_count++;
                }
                sJSON += '}';
		
                var paraString = "reqInfoDispName="+document.getElementById('usr_name').value+"&reqInfoPhNumber="+document.getElementById('mobile_phone').value+"&reqInfoEmail="+document.getElementById('contact_email').value+"&resolution="+document.getElementById('resolution').value+"&coordinates="+document.getElementById('coordinates').value+"&loginproductname="+document.getElementById('loginproductname').value+"&referer="+document.getElementById('referer').value+"&keyname="+document.getElementById('keyname').value+"&listingTypeForEMail="+document.getElementById('listingTypeForEMail').value+"&listingIdForEMail="+document.getElementById('listingIdForEMail').value+"&listingUrlForEMail="+document.getElementById('listingUrlForEMail').value+"&subjectForThisEmail="+document.getElementById('subjectForThisEmail').value+"&extraParams="+document.getElementById('extraParams').value;
                if (flagSignedUser == true ) {
		if(document.getElementById('homeSecurityCode')){
                    paraString +="&captchatext="+document.getElementById("homeSecurityCode").value;
			}
                }
		var keyName=document.getElementById('keyname').value;
		if(keyName=='SAVE'){
		saveProductListing(document.getElementById('listingTypeForEMail').value,document.getElementById('listingIdForEMail').value);
		}
		 // added for unified starts here
		var country_unified = "";
             	if($('unified_country_id')) {
             		country_unified = $('unified_country_id').value;  
             	}
             	if(country_unified && country_unified!='2') {
                  arr_unified[0] = '3';
                  arr_unified[1] = '';
            	} else {
                  arr_unified[0] = '1';
                  arr_unified[1] = '';
            	}
        	ShikshaUnifiedRegistarion.url_unified = ShikshaUnifiedRegistarion.ajaxUrlHelper(arr_unified);
         	// added for unified ends here
		var url='/user/UnifiedRegistration/emailThisListing';
                var urlTemp='/user/UnifiedRegistration/loadFormUsingAjax';
                new Ajax.Request(url, { method:'post', parameters: (paraString), onSuccess:function (request){
                        if(trim(request.responseText) == 'thanks') {
                            closeMessage();
                            if (flag_get_free_alert) {    
                                displayMessage('/MultipleApply/MultipleApply/showoverlay/5',500,50);
                            } else {                               
			    if(unified_registration_is_ldb_user == 'false' && (arr_unified[0] == '1' && unified_form_overlay1_cancel_clicked != 'true'  || arr_unified[0] == '3' && unified_form_overlay3_cancel_clicked != 'true' )) {
                        if(keyName=='EMAIL'){
                            callUnifiedOverlay(ShikshaUnifiedRegistarion.url_unified,600,100,'listingEmail','listingdetailemailwidget');
                        }else if(keyName=='SMS'){
                            callUnifiedOverlay(ShikshaUnifiedRegistarion.url_unified,600,100,'listingSMS','listingdetailsmswidget');
                        }else if(keyName=='SAVE'){
                            callUnifiedOverlay(ShikshaUnifiedRegistarion.url_unified,600,100,'listingSave','listingdetailsaveinfowidget');
                        }
                        }else{
                        if(keyName=='EMAIL'){
                        displayMessage('/MultipleApply/MultipleApply/showoverlay/15',500,50);
                        }else if(keyName=='SMS'){
                        displayMessage('/MultipleApply/MultipleApply/showoverlay/16',500,50);
                        }
                        }
                            }
                        } else if (trim(request.responseText) == 'login') {
                            closeMessage();
                            displayMessage('/MultipleApply/MultipleApply/showoverlay/9/'+keyName,500,260);
                            return false;
                        } else if (trim(request.responseText) == 'register') {
                            closeMessage();
			if(unified_registration_is_ldb_user == 'false' && (arr_unified[0] == '1' && unified_form_overlay1_cancel_clicked != 'true'  || arr_unified[0] == '3' && unified_form_overlay3_cancel_clicked != 'true' )) {
			if(keyName=='EMAIL'){
                            callUnifiedOverlay(ShikshaUnifiedRegistarion.url_unified,600,100,'listingEmail','listingdetailemailwidget');
			}else if(keyName=='SMS'){
			    callUnifiedOverlay(ShikshaUnifiedRegistarion.url_unified,600,100,'listingSMS','listingdetailsmswidget');
			}else if(keyName=='SAVE'){
			    callUnifiedOverlay(ShikshaUnifiedRegistarion.url_unified,600,100,'listingSave','listingdetailsaveinfowidget');
			}
			}else{
			if(keyName=='EMAIL'){
			displayMessage('/MultipleApply/MultipleApply/showoverlay/15',500,50);
			}else if(keyName=='SMS'){
			displayMessage('/MultipleApply/MultipleApply/showoverlay/16',500,50);
			}
			}
                        }else if(trim(request.responseText) == 'thanksAlreadySubscribed' || trim(request.responseText)=='loginAlreadySubscribed' || trim(request.responseText)=='registerAlreadySubscribed'){
			    closeMessage();
			    displayMessage('/MultipleApply/MultipleApply/showoverlay/11',500,50);				
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
	function saveProductListing(type,id)
	{
	    var url = "/saveProduct/SaveProduct/save";
	    new Ajax.Request(url, { method:'post', parameters: ('type='+type+'&id='+id), onSuccess:showResponseForSaveListing });
	}
	
	function showResponseForSaveListing(response)
	{
	    if (isNaN(response.responseText))
	    {
	        $(response.responseText).innerHTML = "Saved In Account & Settings";
	        $(response.responseText).parentNode.className = 'showSpan';
	        if(!isUserLoggedIn && getCookie('user') != '')
	        {
	            window.setTimeout(function(){window.location.reload();}, 5000);
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
                }else{
		processData();
		return true;
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
            addOnBlurValidate(document.getElementById('requestListing'));
            addOnFocusToopTip(document.getElementById('requestListing'));
            document.getElementById('resolution').value = getCoordinates(); 
            document.getElementById('coordinates').value = getResolution(document.getElementById('requestListing'));
            document.getElementById('loginproductname').value = '<?php echo $multkeyname?>';
            document.getElementById('referer').value = location.href;  
        } catch (ex){
            if (debugMode){
                throw ex;
            } else {
                logJSErrors(ex);
            }
        }

/*        try{
            if((totalChecked == undefined) || (totalChecked <= 6 )) {
                if (totalChecked == undefined || totalChecked == 0) {
                    /*document.getElementById('c_value_html_innerhtml').style.height = 20 + 'px';
                    document.getElementById('c_value_html_innerhtml').style.overflow = 'hidden';
                } else {
                    /*document.getElementById('c_value_html_innerhtml').style.height = ( 21*totalChecked ) + 'px';
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
        }*/
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
	function onLoad(){
		if(document.getElementById('keyname').value!='EMAIL' && document.getElementById('keyname').value!='SMS' && document.getElementById('keyname').value!='SAVE'){
			document.getElementById('categorySelectPanel').style.display = 'block';			
			selectedValueforCombo = document.getElementById('categoryName').value+'-'+document.getElementById('categoryId').value;
			selectComboBox(document.getElementById('category'), selectedValueforCombo);
		}else{
			document.getElementById('categorySelectPanel').style.display = 'none';
		}

	}
	onLoad();
    </script>
    </form>
</div>
