<script>
var temp ='fConnect';
</script>
<div style="display:none;" id="userLoginOverlay_ForAnA" >
	<div>
		<input type = "hidden" name = "loginaction_ForAnA" id = "loginaction_ForAnA" value = ""/>
		<input type = "hidden" name = "loginflag_ForAnA" id = "loginflag_ForAnA" value = ""/>
		<input type = "hidden" name = "loginflagreg_ForAnA" id = "loginflagreg_ForAnA" value = ""/>
		<input type = "hidden" name = "loginactionreg_ForAnA" id = "loginactionreg_ForAnA" value = ""/>
		<input type = "hidden" name = "tyepeOfRegistration_ForAnA" id = "tyepeOfRegistration_ForAnA" value = ""/>
        <div style="display:none;" id="userLoginOverlay_ForAnA_login" class="row">
        	<div class="float_R" style="padding-right:10px;">
				<a href="javascript:void(0);" onClick="javascript:showHideLogin('none','');" class="bld">New Shiksha User</a>
			</div>

            <div class="OrgangeFont bld" style="padding-left:17px;font-size:14px">Existing Shiksha User Sign In</div>
        </div>
<div class="lineSpace_5">&nbsp;</div>
<div class="mlr10">
<div class="row" id="userLoginOverlay_ForAnA_registration">
    <div class="float_R">
        <a href="javascript:void(0);" onClick="javascript:showHideLogin('','none');" class="bld">Existing Shiksha User Sign In</a>
    </div>
    <div class="OrgangeFont bld" style="font-size:14px">New Shiksha User</div>
</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
        <!--Start_LOGIN_Form-->
 <div class="mlr10">
<!--    <div class="mb15"><strong class="Fnt14">Sign up using your Facebook account</strong>&nbsp;(Recommended)</div>
    <div class="mb10">-->
        <!-- <input type="button" class="fcnt_signBtn pointer" value="&nbsp;" onclick="hideOverlay(false);showOverlayForFConnect();"/>-->
        <!--<a class="login-fb" href="javascript:void(0);" onclick="hideOverlay(false);showOverlayForFConnect();"></a>
    </div>
    <div>The easier way to sign in to shiksha</div>    
    <div class="lineSpace_5">&nbsp;</div>
    <div class="dottedLine">&nbsp;</div>
    <div class="lineSpace_5">&nbsp;</div>
    <div class="m20"><strong>...or fill out the form below:</strong></div>-->
    <div class="lineSpace10">&nbsp;</div>
</div>
                        
		<div id="userLoginOverlay_ForAnA_loginform" style ="display:block">
			<form action="/user/Login/submit" onsubmit="if(validateLoginForAnA(this) != true){return false;}; new Ajax.Request('/user/Login/submit',{onSuccess:function(request){javascript:showLoginResponseForAnA(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" method="post">
			<div class="row">
				<input type="hidden" name="typeOfLoginMade" id="typeOfLoginMade" value="veryQuck" />
				<input type = "hidden" name = "mpassword_ForAnA" id = "mpassword_ForAnA" value = ""/>
				<div>
					<div class="flLt" style="width:170px; padding:8px 10px 0 0; text-align:right">Login Email Id:</div>
					<div class="flLt" style="width:320px">
						<input type="text" id = "username_ForAnA" name = "username_ForAnA" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" minlength = "10" style="width:190px" class="register-fields" />
						<div style="display:none"><div class="errorMsg" id= "username_ForAnA_error"></div></div>
					</div>
					<div class="clearFix"></div>
				</div>
				<div style="line-height:6px">&nbsp;</div>
				<div style="padding-bottom:1px" id="passwordPlaceHolder_ForAnA">
					<div class="flLt" style="width:170px; padding:8px 10px 0 0; text-align:right">Password:</div>
					<div class="flLt" style="width:320px">
						<div><input type="password" id = "password_ForAnA" name = "password_ForAnA" validate = "validateStr" minlength = "5" maxlength = "20" required = "true" caption = "password" style="width:190px" class="register-fields" /></div>
						<div style="display:none"><div class="errorMsg" id="password_ForAnA_error"></div></div>
					</div>
					<div class="clearFix"></div>
				</div>
				<div id="remembermePlaceHolder_ForAnA">
					<div class="flLt" style="width:170px; padding:8px 10px 0 0; text-align:right">&nbsp;</div>
					<div class="flLt" style="width:320px;font-size:11px"><input type="checkbox" />Remember me on this computer</div>
					<div class="clearFix"></div>
				</div>
				<div id="loginButtonPlaceHolder_ForAnA" class="row">
					<div class="flLt" style="width:170px; padding:8px 10px 0 0; text-align:right">&nbsp;</div>
					<div class="flLt" style="width:320px;font-size:11px">
						<input type="Submit" value="Login" class="orange-button" id="Login_ForAnA" />
					</div>
					<div class="clearFix"></div>
				</div>
				<div style="line-height:6px">&nbsp;</div>
				<div id="forgotPasswordButtonPlaceHolder_ForAnA" style="display:none;" class="row">
					<div class="flLt" style="width:170px; padding:8px 10px 0 0; text-align:right">&nbsp;</div>
					<div class="flLt" style="width:320px">
						<input id="forgotPasswordSubmitBtnAnA" type="button" value="Submit" onClick="return sendForgotPasswordMailForAnA();" class="orange-button" /> &nbsp; <a href="javascript:void(0);"  onClick = "return switchForgotPassword('','none');">Login</a></span></div>
					</div>
					<div class="clearFix"></div>
				</div>
				<div id="forgotPasswordLinkPlaceHolder_ForAnA" class="row">
					<div class="flLt" style="width:170px; padding:8px 10px 0 0; text-align:right"></div>
					<div class="flLt" style="width:320px"><a href="javascript:void(0);" onClick = "return switchForgotPassword('none','');">Forgot Password</a></div>
					<div class="clearFix"></div>
				</div>
			</div>
			<!--<div style="line-height:24px">&nbsp;</div>-->
			</form>
		</div>
        <!--End_LOGIN_Form-->
		<!--Start_Registrtion_Form-->
		<div id="userLoginOverlay_ForAnA_registrationform" style="display:none">
			<form method="post" onsubmit="if(validatequickSignUpForAnA(this) != true){return false;} else { disableRegisterButton_ForAnA();}; new Ajax.Request('/user/Userregistration/veryQuickRegistrationForAnA',{onSuccess:function(request){javascript:if(request.responseText == 'email') {var dataForLoginLayer = {'email' : $('quickemail_ForAnA').value}; shikshaUserRegistration.setData(dataForLoginLayer); shikshaUserRegistration.setCallback(function(callbackData) { if(callbackData.status == 'SUCCESS') {window.location.reload();}}); hideLoginOverlay(); shikshaUserRegistration.showLoginLayer(); } else {showQuickSignUpResponse_ForAnA(request.responseText);}}, evalScripts:true, parameters:Form.serialize(this)}); return false;" action="/user/Userregistration/veryQuickRegistrationForAnA" id = "RegistrationForm_ForAnA" name = "RegistrationForm_ForAnA">
			<div class="row">
				<input type = "hidden" name = "loginproductname_ForAnA" id = "loginproductname_ForAnA" value = ""/>
				<input type = "hidden" name = "resolution_ForAnA" id = "resolution_ForAnA" value = ""/>
				<input type = "hidden" name = "coordinates_ForAnA" id = "coordinates_ForAnA" value = ""/>
				<input type = "hidden" name = "referer_ForAnA" id = "referer_ForAnA" value = ""/>
				<input type="hidden" name="tracking_keyid" id="tracking_keyid" value="">
				<div>
					<div class="flLt" style="width:170px; padding:8px 10px 0 0; text-align:right">First Name:</div>
					<div class="flLt" style="width:320px">
						<div>
							<input type="text" id = "quickfirstname_ForAnA" name = "quickfirstname_ForAnA" type = "text" validate = "validateDisplayName" required = "true" maxlength = "50" minlength = "1" caption = "first name" style="width:190px" class="register-fields" />
						</div>
						<div style="display:none"><div class="errorMsg" id="quickfirstname_ForAnA_error"></div></div>
					</div>
					<div class="clearFix"></div>
				</div>
				<div style="line-height:9px">&nbsp;</div>
				<div>
					<div class="flLt" style="width:170px; padding:8px 10px 0 0; text-align:right">Last Name:</div>
					<div class="flLt" style="width:320px">
						<div>
							<input type="text" id = "quicklastname_ForAnA" name = "quicklastname_ForAnA" type = "text" validate = "validateDisplayName" required = "true" maxlength = "50" minlength = "1" caption = "last name" style="width:190px" class="register-fields" />
						</div>
						<div style="display:none"><div class="errorMsg" id="quicklastname_ForAnA_error"></div></div>
					</div>
					<div class="clearFix"></div>
				</div>
				<div style="line-height:9px">&nbsp;</div>
				<div>
					<div class="flLt" style="width:170px; padding:8px 10px 0 0; text-align:right">Login Email:</div>
					<div class="flLt" style="width:320px">
						<div>
							<input type="text" id = "quickemail_ForAnA" name = "quickemail_ForAnA" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" onblur = "checkAvailability(this.value,'quickemail_ForAnA')" style="width:190px" class="register-fields" />
						</div>
						<div style="display:none"><div class="errorMsg" id="quickemail_ForAnA_error"></div></div>
					</div>
					<div class="clearFix"></div>
				</div>
				<div style="line-height:9px">&nbsp;</div>
				<!-- Start: Add Mobile in the Quick registration form -->
				<div>
					<div class="flLt" style="width:170px; padding:8px 10px 0 0; text-align:right">Mobile:</div>
					<div class="flLt" style="width:320px">
						<div>
							<input type="text" id = "quickmobile_ForAnA" name = "quickmobile_ForAnA" validate = "validateMobileInteger" minlength = "10" maxlength = "10" required = "true" caption = "mobile" style="width:190px" class="register-fields" />
						</div>
						<div style="display:none"><div class="errorMsg" id="quickmobile_ForAnA_error"></div></div>
					</div>
					<div class="clearFix"></div>
				</div>
				<div style="line-height:9px">&nbsp;</div>
				<!-- End: Add Mobile in the Quick registration form -->
				<div>
					<div class="flLt" style="width:170px; padding:8px 10px 0 0; text-align:right">Password:</div>
					<div class="flLt" style="width:320px">
						<div>
							<input type="password" id = "quickpassword_ForAnA" name = "quickpassword_ForAnA" validate = "validateStr" minlength = "5" maxlength = "20" required = "true" caption = "password" style="width:190px" class="register-fields" />
						</div>
						<div style="display:none"><div class="errorMsg" id="quickpassword_ForAnA_error"></div></div>
					</div>
					<div class="clearFix"></div>
				</div>
				<div style="line-height:9px">&nbsp;</div>
				<div>
					<div class="flLt" style="width:170px; padding:8px 10px 0 0; text-align:right">Confirm Password:</div>
					<div class="flLt" style="width:320px">
						<div>
							<input id = "quickconfirmpassword_ForAnA" name = "quickconfirmpassword_ForAnA" type="password" minlength = "5" maxlength = "20" required = "true" validate = "validateStr" caption = "password again" blurMethod = "validatepassandconfirmpass('quickpassword_ForAnA','quickconfirmpassword_ForAnA');" style="width:190px" class="register-fields" />
						</div>
						<div style="display:none"><div class="errorMsg" id="quickconfirmpassword_ForAnA_error"></div></div>
					</div>
					<div class="clearFix"></div>
				</div>
				<div style="line-height:9px">&nbsp;</div>
				<div style="padding-left:66px">
					<div style="padding-bottom:7px">Type in the characters you see in the picture below </div>
					<div>
						<img align = "absmiddle" src="" width="100" height="40" onabort="javascript:reloadCaptcha(this.id);" onClick="javascript:reloadCaptcha(this.id);" id = "registerCaptacha_ForAnA" align="absmiddle" />&nbsp;&nbsp;&nbsp;
						<input type="text" size="18" id = "securityCode_ForAnA" name = "securityCode_ForAnA" validate="validateSecurityCode" caption="Security Code" maxlength="5" minlength="5" required="true" size="5" style="width:120px" class="register-fields" />
					</div>
					<div><div class="errorMsg" id="securityCode_ForAnA_error"></div></div>
					<div style="line-height:5px">&nbsp;</div>
					<div>
						<input type="checkbox" checked id = "quickagree_ForAnA" /> I agree to the <a href="javascript:void(0);" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a> and <a href="javascript:void(0);" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a>
					</div>
					<div class="errorPlace" style="margin-top:2px;display:none;" >
						<div class="errorMsg" id = "quickagree_ForAnA_error"></div>
					</div>
					<div class="errorPlace" style="margin-top:2px;display:none;">
						<div class="errorMsg bld" id = "quickerror_ForAnA"></div>
					</div>	
					<div style="line-height:10px">&nbsp;</div>
				</div>
			</div>
			<div style="line-height:7px">&nbsp;</div>
			<div align="center"><input type="Submit" value="Join now & Continue" class="orange-button" id="Register_ForAnA" uniqueattr="homepageJoinnowandContinueButton"/><span style="margin-left:5px;display:none;" id="loaderDiv"><img src="/public/images/working.gif" valign="center"/></span></div>
			<div style="line-height:27px">&nbsp;</div>
			</form>
		</div>
        <!--End_Of_Registration_Form-->
    </div>
</div>
