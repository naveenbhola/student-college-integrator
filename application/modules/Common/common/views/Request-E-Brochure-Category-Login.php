<div style="width:600px">
        <!--Start Thanks you for your request-->
	<form id='multiple_apply_nlsu_frm' name="multiple_apply_nlsu_frm" method="post" onsubmit="userLoginActionOnSubmit();new Ajax.Request('/user/Login/submit',{onSuccess:function(request){javascript:checklogin(request.responseText);}, onFailure:function(request){javascript:checklogin(request.responseText)}, evalScripts:true, parameters:Form.serialize(this)}); return false;" style="margin:0;padding:0" >
		<input type="hidden" value="" id="mpasswordrb" name="mpasswordrb"/>
		<input type="hidden" value="" id="usernamerb" name="usernamerb"/>
		<input type="hidden" value="request" id="typeOfLoginMade" name="typeOfLoginMade"/>
	<div class="blkRound">
		<div class="bluRound">
			<span class="float_R">
			   <img class="pointer" onclick="$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';closeMessage();" src="/public/images/fbArw.gif" border="0"/>
			</span>
		   <span class="title">Request E-Brochure</span>
			<div class="clear_B"></div>
		</div>
		<div class="whtRound" style="padding:10px 15px">
        <table><tr><td>
			<div class="fontSize_16p bld" id="idContent1" style="width:100%">You will be receiving E brochure's of the selected institutes in your mailbox shortly</div>
			<div style="display:none;" class="fontSize_16p bld" id="idContent4">Reset password instructions sent successfully to your current email id </div>
			<div class="lineSpace_5">&nbsp;</div>
			<div class="infoIcon fontSize_14p bld" style="padding-bottom:10px" id="idContent2">An account with this email already exists on Shiksha.com</div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="fontSize_14p" id="pEnterPassed" style="width:100%">Please enter your password to sign in</div>
			<div class="lineSpace_20">&nbsp;</div>
			<div>
				<div style="padding-bottom:10px">
					<div class="float_L" style="width:150px;line-height:20px">
						<div class="txt_align_r">Login Email Id: &nbsp;</div>
					</div>
					<div>
						<div id="multiple_apply_nlsu" style="margin-left:0px;line-height:20px"></div>
						<div style="display:none"><div class="errorMsg" id="contact_email_error"></div></div>
						<div id="login_anchor" style="display:none;padding-top:10px;text-align:center;margin-left:150px;">
							<a href="javascript:void(0);" onclick="return openloginpanal();" >Login</a>
						</div>							
					</div>
					<div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
				</div>
				<div style="padding-bottom:10px" id="pEnterPassedContent">
					<div class="float_L" style="width:150px;line-height:20px">
						<div class="txt_align_r">Password: &nbsp;</div>
					</div>
					<div class="float_L" style="width:300px">
						<div><input id="passwordTemp" name="password" type="password" style="width: 120px;" />&nbsp;&nbsp;<a id="forgotPasswordSubmitLink" href="javascript:void(0);" onclick="return sendForgotPasswordMail();" style="font-size:11px">forgot password</a></div>
						<div style="display:none"><div style="padding-left:0px;" class="errorMsg" id="passwordTemp_error"></div></div>
					</div>
					<!--<div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>-->
				</div>
				<div align="center" id="actionPanel" style="margin-top:20px;width:400px;"><input type="submit" class="fbBtn" class="submitGlobal" value="Submit" id="submitGlobalForRequestEBrochure" />&nbsp;<a href="#" onclick="$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';closeMessage();" value="Cancel"/>Cancel</a></div>
				
			</div>
            </td>
            </tr>
            </table>
		</div>
	</div>
	</form>
        <!--End Thanks you for your request-->
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
            	document.getElementById('idContent1').innerHTML = "You will be receiving E brochure's of the selected institutes in your mailbox shortly";
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
            document.getElementById('idContent1').innerHTML='';
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
		displayMessage('/MultipleApply/MultipleApply/showoverlay/19',500,50);	
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
