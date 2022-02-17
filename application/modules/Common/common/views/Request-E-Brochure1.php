<div style="width:500px">
    <div style="background:#FFFFFF;border:1px solid #d6e6f6">
        <!--Start Thanks you for your request-->
        <form id='multiple_apply_nlsu_frm' name="multiple_apply_nlsu_frm" method="post" onsubmit="userLoginActionOnSubmit();new Ajax.Request('/user/Login/submit',{onSuccess:function(request){javascript:checklogin(request.responseText);}, onFailure:function(request){javascript:checklogin(request.responseText)}, evalScripts:true, parameters:Form.serialize(this)}); return false;" style="margin:0;padding:0" >
		<div style="float:left;width:100%;background:#FFF">
            <input type="hidden" value="" id="mpasswordrb" name="mpasswordrb"/>
            <input type="hidden" value="" id="usernamerb" name="usernamerb"/>
            <input type="hidden" value="request" id="typeOfLoginMade" name="typeOfLoginMade"/>
            <div class="titleBarLay">
                <div class="float_R">
		    
		    <div style="padding-top:12px"><img onclick="setTimeout('window.location.reload();', 300);" src="/public/images/crossImg_14_12.gif" border="0" class="searchCareer" /></div>
		    
                    <!---->
                </div>
                <div class="fontSize_16p bld" id="titleForRequestEBrochureLoginOverlay">Thanks you for your request</div>
                <div class="clear_B" style="font-size:1px;line-height:1px">&nbsp;</div>
            </div>
            <div style="padding:10px 15px">
                <div class="OrgangeFont fontSize_16p bld" id="idContent1" style="width:100%">You will be receiving E brochure's of the selected institutes in your mailbox shortly</div>
                <div style="display:none;" class="OrgangeFont fontSize_16p bld" id="idContent4">Reset password instructions sent successfully to your current email id </div>
                <div class="lineSpace_5">&nbsp;</div>
                <div class="infoIcon fontSize_14p bld" style="padding-bottom:10px" id="idContent2">An account with this email already exists on Shiksha.com</div>
                <div class="lineSpace_10">&nbsp;</div>
                <div class="fontSize_14p float_L" id="pEnterPassed" style="width:100%">Please enter your password to sign in</div>
                <div class="lineSpace_20">&nbsp;</div>
                <div>
					<div style="padding-bottom:10px">
						<div class="float_L" style="width:150px;line-height:20px">
							<div class="txt_align_r">Login Email Id: &nbsp;</div>
						</div>
						<div style="float:left;width:200px">
							<div id="multiple_apply_nlsu" style="margin-left:0px;line-height:20px" class="OrgangeFont"></div>
							<div style="display:none"><div class="errorMsg" id="contact_email_error"></div></div>
							<div id="login_anchor" style="display:none;padding-top:10px">
								<a href="javascript:void(0);" onclick="return openloginpanal();" >Login</a>
							</div>							
						</div>
						<div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
					</div>
					<div style="padding-bottom:10px" id="pEnterPassedContent">
						<div class="float_L" style="width:150px;line-height:20px">
							<div class="txt_align_r">Password: &nbsp;</div>
						</div>
						<div class="float_L OrgangeFont" style="width:300px">
							<div><input id="passwordTemp" name="password" type="password" style="width: 120px;" />&nbsp;&nbsp;<a id="forgotPasswordSubmitLink" href="javascript:void(0);" onclick="return sendForgotPasswordMail();" style="font-size:11px">forgot password</a></div>
							<div style="display:none"><div style="padding-left:0px;" class="errorMsg" id="passwordTemp_error"></div></div>
						</div>
						<div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
					</div>
					<div align="center" id="actionPanel"><input type="submit" class="submitGlobal" class="submitGlobal" value="Submit" id="submitGlobalForRequestEBrochure" />&nbsp;<input type="button" onclick="return userLoginActionOnCancle();" class="cancelGlobal" value="Cancel" /></div>
					
				</div>
			</div>
		</div>
        </form>
        <!--End Thanks you for your request-->
    </div>
</div>
<script type="text/javascript">
	try{
		if((typeof(askInstitute) != 'undefined') && (askInstitute.successMessage=='showLogin')){
			$('titleForRequestEBrochureLoginOverlay').innerHTML= 'Sign in to ask your question';
			$('idContent1').style.display='none';
			$('submitGlobalForRequestEBrochure').value="Ask now";
			$('pEnterPassed').innerHTML = 'Enter password to sign in and ask your question.';
			
		}
    } catch (ex) {
        if (debugMode){
            throw ex;
        } else {
            logJSErrors(ex);
        }
    }
    function openloginpanal(){
        try {
            document.getElementById('pEnterPassed').innerHTML='Please Enter your password to sign In';
            document.getElementById('pEnterPassedContent').style.display = 'block';
            // hide buttons
            document.getElementById('actionPanel').style.display = 'block';
            // hide valrious text related to password
			if(!((typeof(askInstitute) != 'undefined') && (askInstitute.successMessage=='showLogin'))){
            	document.getElementById('idContent1').style.display = 'inline';
			}
            document.getElementById('idContent2').style.display = 'inline';
            
            document.getElementById('idContent4').style.display = 'none';
            document.getElementById('login_anchor').style.display = 'none';
        } catch (ex){
            if (debugMode){
                throw ex;
            } else {
                logJSErrors(ex);
            }   
        }       


    }
    
    function sendForgotPasswordMail()
    {
        
        try{
            // hide password related text
            document.getElementById('pEnterPassed').innerHTML='Existing Shiksha User';
            // hide password text box
            document.getElementById('pEnterPassedContent').style.display = 'none';
            // hide buttons
            document.getElementById('actionPanel').style.display = 'none';
            // hide valrious text related to password
            document.getElementById('idContent1').style.display = 'none';
            document.getElementById('idContent2').style.display = 'none';
            
            var username = document.getElementById('multiple_apply_nlsu').innerHTML;
            if(trim(username) == "")
            {
                username = "";
                document.getElementById('contact_email_error').parentNode.style.display = 'inline';
                document.getElementById('contact_email_error').style.color = 'red';
                document.getElementById('contact_email_error').innerHTML = "Please enter your email id";
                return false;
            }
            else {
                var email = username;
            }
            
            var xmlHttp = getXMLHTTPObject();
            xmlHttp.onreadystatechange=function()
            {
                if(xmlHttp.readyState==4)
                {
                    if(xmlHttp.responseText != '')
                    {
                        
                        if(xmlHttp.responseText == 'false' || xmlHttp.responseText == "deleted")
                        {
                            if(xmlHttp.responseText == 'false')
                            document.getElementById('contact_email_error').innerHTML = 'Incorrect Email id ';
                            else
                            document.getElementById('contact_email_error').innerHTML = 'You are no longer a valid shiksha user ';
                            document.getElementById('contact_email_error').parentNode.style.display = 'inline';
                            document.getElementById('contact_email_error').style.color = 'red';
                        }
                        else
                        {
                            
                           document.getElementById('idContent4').style.display = 'inline';
                           document.getElementById('pEnterPassed').innerHTML=
                           '';
                           document.getElementById('login_anchor').style.display = 'inline';
                           document.getElementById('forgotPasswordSubmitLink').onClick = null;
                        }
                    }
                        return false;
                }
            };
            var url = '/user/Userregistration/sendResetPasswordMail/' + email;
            xmlHttp.open("POST",url,true);
            xmlHttp.send(null);
            return false;
        } catch (ex){
            if (debugMode){
                throw ex;
            } else {
                logJSErrors(ex);
            }   
        }       
    }
        
    
    /* not logged-in and is shiksha user */
    function userLoginActionOnCancle()
    {
        if (document.getElementById('helpbubble')) {
                document.getElementById('helpbubble').style.display='none';
        }
        closeMessage();
        // send data to server
		if(!((typeof(askInstitute) != 'undefined') && (askInstitute.successMessage=='showLogin'))){
                        if(unified_widget_identifier == 'naukrishiksha') {
				eventTriggered = 'request-E-Brochure';
                        }
			if (flag_get_free_alert || unified_widget_identifier == 'naukrishiksha') {
				if(eventTriggered == 'request-E-Brochure'){
				//checkFshareOverlay('notLogInShikshaUserIf');
				displayMessage('/MultipleApply/MultipleApply/showoverlay/5',500,50);
				setTimeout('window.location.reload();', 3000);
				}else{
				displayMessage('/MultipleApply/MultipleApply/showoverlay/5',500,50);
				}
				
			} else {
				if(eventTriggered == 'request-E-Brochure'){
				checkFshareOverlay('notLogInShikshaUserElse');
				}else{
				displayMessage('/MultipleApply/MultipleApply/showoverlay/2',500,50);
				}
				
			}
			if(eventTriggered != 'request-E-Brochure'){
			setTimeout('window.location.reload();', 3000);
			}
		}else{
			window.location.reload();
		}	
    }

    /* not logged-in and is shiksha user */
    function userLoginActionOnSubmit()
    {
        try {
            if (document.getElementById('helpbubble')) {
                document.getElementById('helpbubble').style.display='none';
            }
            document.getElementById('pEnterPassed').innerHTML='Please Enter your password to sign In';
            document.getElementById('pEnterPassedContent').style.display = 'block';
            // hide overlay and show thanks
            var Flag = true;
            document.getElementById('contact_email_error').parentNode.style.display = 'none';
            document.getElementById('contact_email_error').innerHTML = "";
            document.getElementById('passwordTemp_error').parentNode.style.display = '';
            document.getElementById('passwordTemp_error').innerHTML = "";
            if(document.getElementById('multiple_apply_nlsu').innerHTML == '')
            {
                document.getElementById('contact_email_error').parentNode.style.display = 'inline';
                document.getElementById('contact_email_error').innerHTML = "Enter Email-id";
                Flag = false;
            }
            if(document.getElementById('passwordTemp').value == '')
            {
                document.getElementById('passwordTemp_error').parentNode.style.display = 'inline';
                document.getElementById('passwordTemp_error').innerHTML = "Please enter password.";
                Flag = false;
            }
            else
            {
                var pass = (document.getElementById('passwordTemp').value);
                document.getElementById('mpasswordrb').value = pass;
            }
            if (Flag) {
                return true; 
            } else {
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
    
    try{
        // set email-id in login overlay
        document.getElementById('multiple_apply_nlsu').innerHTML = email_id;
        document.getElementById('usernamerb').value = email_id;
    } catch (ex){
        if (debugMode){
            throw ex;
        } else {
            logJSErrors(ex);
        }
    }       

    function checklogin(str) {
        try {
            if(str != 0 && str != 'invalid') {
                userLoginActionOnCancle();
            } else {
                document.getElementById('passwordTemp_error').innerHTML = "Incorrect Password.";
                document.getElementById('passwordTemp_error').parentNode.style.display = 'inline';
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
            scrollwindow();
            //document.getElementById('password').focus();
        }catch (ex){
            if (debugMode){
                throw ex;
            } else {
                logJSErrors(ex);
            }
        }        
</script>
