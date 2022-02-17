<!--<div style="margin:100px">-->
<form method="post" onsubmit="if (validateSignUpMA(document.getElementById('quicksignupForm')) === false ){ return false; } new Ajax.Request('/MultipleApply/MultipleApply/quicksignupform',{onSuccess:function(request){javascript:userLoginActionOnRegFormSubmit(request.responseText);}, onFailure:function(request){javascript:userLoginActionOnRegFormSubmit(request.responseText)}, evalScripts:true, parameters:Form.serialize(this)}); return false;" id = "quicksignupForm" name = "quicksignupForm">

<input type = "hidden" name = "resolution" id = "resolution" value = ""/>
<input type = "hidden" name = "coordinates" id = "coordinates" value = ""/>
<input type = "hidden" name = "referer" id = "referer" value = ""/>
<input type = "hidden" name = "loginproductname" id = "loginproductname" value = ""/>
<input type = "hidden" name = "redirect1" id = "redirect1" value = "<?php echo $redirect1?>"/>
<input type = "hidden" name = "redirect2" id = "redirect2" value = "<?php echo $redirect2?>"/>

    <div style="width:665px">
        <div style="width:662px;background:#FFFFFF">
            <div style="border:1px solid #d5e4fb">
                <div style="background:#6391CC;height:30px;position:relative">
                    <div class="bld fontSize_14p" style="padding-left:10px;line-height:30px;color:#FFF">Complete your shiksha.com profile</div>
                    <div style="position:absolute;top:8px;right:10px"><img onclick="setTimeout('userLoginActionOnCancle()',300);" src="/public/images/crossImg_14_12.gif" style="cursor:pointer"/></div>
                </div>
                <div style="padding:10px" class="fontSize_12p">
                    <div>Hi <span class="OrgangeFont  bld" id="display_username" ></span><span id="requestInfoCompleteInformation_msg">, your request has been submitted. We have sent you the requested information and an auto-generated password at your email id for you to access your Shiksha account.</span></div>
                    <div class="bld" style="padding-top:10px">Please complete your profile here and get maximum benefits at Shiksha.com.</div>
                </div>
                <div>
                    <div style="width:408px;border-right:1px solid #e6e6e6;float:left">
                        <div style="padding-right:10px">
                            <div>
                                <div class="formFieldTextLeft">Name:&nbsp;</div>
                                <div class="formFieldTextRight" id="display_name"></div>
                                <input type = "hidden" name = "quickname" id = "quickname_id" value = ""/>
                                <div class="formFieldTextClear">&nbsp;</div>
                            </div>
                            <div>
                                <div class="formFieldTextLeft">Login Email Id:&nbsp;</div>
                                <div class="formFieldTextRight" id="display_email" ></div>
                                <input type = "hidden" name = "quickemail" id = "quickemail_id" value = ""/>
                                <div class="formFieldTextClear">&nbsp;</div>
                            </div>
                            <div>
                                <div class="formFieldTextLeft">New Password:<span class="redcolor">*</span>&nbsp;</div>
                                <div class="formFieldTextRight">
                                    <div><input type="password" id = "quickpassword" name = "quickpassword" validate = "validateStr" minlength = "5" maxlength = "20" required = "true" caption = "password"  style="width:170px" /></div>
                                    <div class="errorPlace" style="display:block;line-height:14px"><div id="quickpassword_error" class="errorMsg"></div></div>
                                </div>
                                <div class="formFieldTextClear">&nbsp;</div>
                            </div>
                            <div>
                                <div class="formFieldTextLeft">Confirm Password:<span class="redcolor">*</span>&nbsp;</div>
                                <div class="formFieldTextRight"><input id = "quickconfirmpassword" name = "quickconfirmpassword" type="password" minlength = "5" maxlength = "20" required = "true" validate = "validateStr" caption = "password again" onblur = "validatepassandconfirmpass('quickpassword','quickconfirmpassword');"  style="width:170px" />
                                <div class="errorPlace" style="display:block;line-height:14px"><div id="quickconfirmpassword_error" class="errorMsg"></div></div>
                                </div>
                                <div class="formFieldTextClear">&nbsp;</div>
                            </div>
                            <div>
                                <div class="formFieldTextLeft">Mobile:<span class="redcolor">*</span>&nbsp;</div>
                                <div class="formFieldTextRight">
                                    <div><input  id = "quickmobileno" name = "quickmobileno" type="text" minlength = "10" maxlength = "10" tip="gfa_mobile_phone" required = "true" validate = "validateMobileInteger" caption = "mobile number" style="width:170px" /></div>
                                    <div style="color:#aeaeae;line-height:14px">eg: 9876512345</div>
                                    <div class="errorPlace" style="display:block;line-height:14px"><div id= "quickmobileno_error" class="errorMsg"></div></div>

                                </div>
                                <div class="formFieldTextClear">&nbsp;</div>
                            </div>
                            <div>
                                <div class="formFieldTextLeft">Landline:&nbsp;</div>
                                <div class="formFieldTextRight">
                                    <div><input id = "quicklandlineext" name = "quicklandlineext" type="text" minlength = "3" maxlength = "5" validate = "validateLandlineInteger" caption = "STD code" style="width:40px" />&nbsp;<input id = "quicklandlineno" name = "quicklandlineno" type="text" minlength = "6" maxlength = "8"  validate = "validateLandlineInteger" caption = "landline number" style="width:125px" /></div>
                                    <div style="color:#aeaeae;line-height:14px"><span>eg: 011</span>&nbsp; &nbsp;<span>eg: 43434343</span></div>
                                    <div class="errorPlace" style="display:block;line-height:14px"><div id= "quicklandlineext_error" class="errorMsg"></div></div>
                                    <div class="errorPlace" style="display:block;line-height:14px"><div id= "quicklandlineno_error" class="errorMsg"></div></div>
                                </div>
                                <div class="formFieldTextClear">&nbsp;</div>
                            </div>
                            <input type="hidden" id="countryofquickreg"
                            value="2" name="countryofquickreg" />
                            <?php
                            global $citiesforRegistration;
                            foreach($citiesforRegistration as $key=>$value) {
                                if($value['id'] == $city) {
                                    $residenceLocName = $value['name'];

                                }
                            }
                             ?>
                            <script>var citiesarray = eval(<?php echo json_encode($citiesforRegistration);?>);
                            </script>
                            <div>
                            <div class="formFieldTextLeft">Residence Location:<span class="redcolor">*</span>&nbsp;</div>
                            <div  class="formFieldTextRight" style="width:270px">

                            <select  style = "width:150px" class = "normaltxt_11p_blk_arial fontSize_11p" id ="citiesofquickreg" name = "citiesofquickreg" validate = "validateSelect" required = "true" caption = "your city of residence"><option value="">Select City</option></select>
                            <script>getCitiesForCountry('',false,'ofquickreg');</script>

                                <div class="errorPlace" style="display:block;line-height:14px">
                                <div id= "citiesofquickreg_error" class="errorMsg"></div></div>
                            </div>
                                <div class="formFieldTextClear">&nbsp;</div>
                            </div>
                            <div>
                                <div class="formFieldTextLeft">Age:<span class="redcolor">*</span>&nbsp;</div>
                                <div class="formFieldTextRight" style="width:190px">
                                    <div><input id = "quickage" name = "quickage" type="text" minlength = "2" maxlength = "2" required = "true" validate = "validateAge" caption = "age" style="width:20px" /> &nbsp; &nbsp; <span style="font-size:11px">Gender <input type="radio" name = "quickgender" id = "Female" value = "Female" /> Female <input type="radio" name = "quickgender" id = "Male" value = "Male"/> Male </span></div>
                                    <div class="errorPlace" style="display:block;line-height:14px"><div id= "quickage_error" class="errorMsg"></div></div>
                                    <div class="errorPlace" style="display:block;line-height:14px"><div id= "quickgender_error" class="errorMsg"></div></div>
                                </div>
                                <div class="formFieldTextClear">&nbsp;</div>
                            </div>
                            <div>
                                <div class="formFieldTextLeft">Highest Qualification:<span class="redcolor">*</span>&nbsp;</div>
                                <div style="width:210px;" class="formFieldTextRight"><select class = "normaltxt_11p_blk_arial fontSize_12p" style = "width:145px;" id = "quickeducation" name = "quickeducation" validate = "validateSelect" required = "true" caption = "your highest education">
                                </select><div class="errorPlace" style="display:block;line-height:14px"><div id= "quickeducation_error" class="errorMsg"></div></div>
                                </div>
                                <div class="formFieldTextClear">&nbsp;</div>
                            </div>
                            <div>
                                <div class="formFieldTextLeft">Education Interest:<span class="redcolor">*</span>&nbsp;</div>
                                <div style="width:260px;" class="formFieldTextRight">
                                    <select class = "normaltxt_11p_blk_arial fontSize_12p" style = "width:230px;" id = "quickinterest" name = "quickinterest" validate = "validateSelect" required = "true" caption = "your education interest">
                                        <option value="">Select Category</option>
                                        <option value="Study Abroad">Study Abroad</option>
                                        <?php
                                        global $categoryParentMap;
                                        foreach ($categoryParentMap as $categoryName => $category)
                                        { ?>
                                                <option value = "<?php echo $categoryParentMap[$categoryName]['id']?>"><?php echo $categoryName?></option>
                                    <?php } ?>
                                                        <option value="Undecided">Presently Undecided</option>
                                                    </select>

                                <div class="errorPlace" style="display:block;line-height:14px"><div id= "quickinterest_error" class="errorMsg"></div></div>

                                </div>
                                <div class="formFieldTextClear">
                                    <div class="errorPlace" style="display:block;line-height:14px">
                                        <div id = "quickerror" class="errorMsg" style="padding-left:10px;" ></div>
                                    </div>
                                </div>
                            </div>
                            <div align="center">
								<input type="hidden" name="userGroupForRequestEBrochure" id="userGroupForRequestEBrochure" value="quicksignupuser">
								<input type="hidden" name="signUpFlagForRequestEBrochure" id="signUpFlagForRequestEBrochure" value="requestinfouser">
                                <input id="submitbuttonsignupma" type="submit" class="submitGlobal" value="Register" />&nbsp;
                                <input type="button" onfocus= "try{ document.getElementById('quickpassword').focus();}catch(ex) {}" class="submitGlobal" onclick = "return userLoginActionOnCancle();" value="Cancel" />
                            </div>
                        </div>
                    </div>
                    <div style="width:250px;float:left;">
                        <div style="padding:0 10px">
                            <div class="orgSmallBullet"><span class="fontSize_14p bld">Get Contacted:</span> Let institutes contact you directly basis your preference</div>
                            <div class="orgSmallBullet"><span class="fontSize_14p bld">Customized Advice:</span> Get personalized expert career counseling</div>
                            <div class="orgSmallBullet"><span class="fontSize_14p bld">Free Alerts:</span> Get alerts for all important dates and events of your interest</div>
                            <div style="padding-top:15px">Registering at <b>Shiksha.com</b> is like offloading all your worries with a trusted friend and guide.<br>Try it and feel the difference!</div>
                        </div>
                    </div>
                    <div class="clear_L withClear">&nbsp;</div>
                    <div class="lineSpace_10">&nbsp;</div>
                </div>
            </div>
        </div>
        <div class="lineSpace_3">&nbsp;</div>
    </div>
</form>
<!--</div>-->
<script type="text/javascript">
    try{
        setregisteroverlay(email_id,display_name,phone_no);
        getEducationLevel('quickeducation','',1,'reqInfo');
        addOnBlurValidate(document.getElementById('quicksignupForm'));
        //addOnFocusToopTip(document.getElementById('quicksignupForm'));
		if((typeof(askInstitute) != 'undefined') && (askInstitute.successMessage=='showRegister')){
			$('userGroupForRequestEBrochure').value="veryshortregistration";
			$('signUpFlagForRequestEBrochure').value="veryshortregistration";
			$('requestInfoCompleteInformation_msg').style.display='none';
		}
    } catch (ex) {
        if (debugMode){
            throw ex;
        } else {
            logJSErrors(ex);
        }
    }

    function validateSignUpMA(objForm)
    {
        try {
			orangeButtonDisableEnableWithEffect('submitbuttonsignupma',true);
            if (document.getElementById('helpbubble')) {
                document.getElementById('helpbubble').style.display='none';
            }
            document.getElementById('quickerror').parentNode.style.display = 'none';
            document.getElementById('quickerror').innerHTML = "" ;
            var response  = validateFields(objForm);
            var response2 =  validatepassandconfirmpass('quickpassword','quickconfirmpassword');
            if((response !== true) || (response2 !== true))
            {
                document.getElementById('quickerror').parentNode.style.display = 'inline';
                document.getElementById('quickerror').innerHTML = "Please correct the errors marked in red to successfully register on shiksha.com." ;
				orangeButtonDisableEnableWithEffect('submitbuttonsignupma',false);
                return false;
            }
        } catch (ex) {
            if (debugMode){
                throw ex;
            } else {
                logJSErrors(ex);
            }
        }
    }

    function userLoginActionOnRegFormSubmit(resp)
    {
        try {
            if ((resp != 'blank') && ( resp != '-1')) {
                var redirect1 = document.getElementById('redirect1').value;
                redirect1 = redirect1.toString();
                redirect1 = escapeHTML(redirect1);
                if(redirect1===false)
                {
                    return;
                }
        var redirect2 = document.getElementById('redirect2').value;
        redirect2 = redirect2.toString();
        redirect2 = escapeHTML(redirect2);
        if(redirect2===false)
        {
            return;
        }
        redirect2 = redirect2.replace(/ /g,"-");
        if(redirect1!='false'){

            setTimeout(function(){ window.location.href='/'+redirect2+'-qna-expert-question-answers-institute-college-listinganatab-'+redirect1;}, 5000);
        }
        //else
        //    setTimeout('window.location.reload();', 100);
                closeMessage();
				if(!((typeof(askInstitute) != 'undefined') && (askInstitute.successMessage=='showRegister'))){
					if (flag_get_free_alert) {
                                                if(eventTriggered == 'request-E-Brochure'){
                                                checkFshareOverlay('notShikshaUserAlertIf');
                                                }else{
						displayMessage('/MultipleApply/MultipleApply/showoverlay/5',500,100);
                                                }
                                        } else {
                                                if(eventTriggered == 'request-E-Brochure'){
                                                checkFshareOverlay('notShikshaUserAlertElse');
                                                }else{
                                                if(!$('unified_search_identifier')) {
						displayMessage('/MultipleApply/MultipleApply/showoverlay/2',500,100);
                                                }
                                                }
					}
				}else{
                                        if(eventTriggered == 'request-E-Brochure'){
                                        checkFshareOverlay('notShikshaUserElse');
                                        }else{
					displayMessage('/MultipleApply/MultipleApply/showoverlay/7',500,50);
                                        }
				}
           if(eventTriggered != 'request-E-Brochure'){                     
	   setTimeout('window.location.reload();', 3000);
           }

            }else{
				orangeButtonDisableEnableWithEffect('submitbuttonsignupma',false);
			}
        } catch (ex) {
            if (debugMode){
                throw ex;
            } else {
                logJSErrors(ex);
            }
        }
    }

    try{
            document.getElementById('quickpassword').focus();
            document.getElementById('quickname_id').value = document.getElementById('display_name').innerHTML;
            document.getElementById('quickemail_id').value = document.getElementById('display_email').innerHTML;
            document.getElementById('display_username').innerHTML = document.getElementById('display_name').innerHTML;
            document.getElementById('resolution').value = getCoordinates();
            document.getElementById('coordinates').value = getResolution(document.getElementById('quicksignupForm'));
            document.getElementById('loginproductname').value = 'MULTIPLE_APPLY_INSTITUTE_LIST_UPDATE_USER_PROFILE_OVERLAY_CLICK';
            document.getElementById('referer').value = location.href;
    } catch (ex) {
        if (debugMode){
            throw ex;
        } else {
            logJSErrors(ex);
        }
    }
     /* not logged-in and is shiksha user */
    function userLoginActionOnCancle()
    {
        var redirect1 = document.getElementById('redirect1').value;
        redirect1 = redirect1.toString();
        redirect1 = escapeHTML(redirect1);
        if(redirect1===false)
        {
            return;
        }
        var redirect2 = document.getElementById('redirect2').value;
        redirect2 = redirect2.toString();
        redirect2 = escapeHTML(redirect2);
        if(redirect2===false)
        {
            return;
        }
        //var replaceString = " ";
        //var withreplaceString = "-";
        redirect2 = redirect2.replace(/ /g,"-");
        if(redirect1!='false'){

            setTimeout(function(){ window.location.href='/'+redirect2+'-qna-expert-question-answers-institute-college-listinganatab-'+redirect1;}, 5000);
        }
        //else
        //    setTimeout('window.location.reload();', 100);

        closeMessage();

        if(!((typeof(askInstitute) != 'undefined') && (askInstitute.successMessage=='showRegister'))){
                if (flag_get_free_alert) {
                        if(eventTriggered == 'request-E-Brochure'){
                        checkFshareOverlay('notShikshaUserAlertIf');
                        }else{
                        displayMessage('/MultipleApply/MultipleApply/showoverlay/5',500,50);
                        }
                } else {
                        if(eventTriggered == 'request-E-Brochure'){
                        checkFshareOverlay('notShikshaUserAlertElse');
                        }else{
                        if(!$('unified_search_identifier')) {
                        displayMessage('/MultipleApply/MultipleApply/showoverlay/2',500,50);
                        }
                        }
                }
        }else{
                if(eventTriggered == 'request-E-Brochure'){
                checkFshareOverlay('notShikshaUserElse');
                }else{
                displayMessage('/MultipleApply/MultipleApply/showoverlay/7',500,50);
                }
        }
        if(eventTriggered != 'request-E-Brochure'){
	setTimeout('window.location.reload();', 3000);
        }
    }

    try {
        scrollwindow();
        document.getElementById('quickpassword').focus();
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
</script>
