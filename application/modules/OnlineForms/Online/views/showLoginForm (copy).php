<?php
				$categoryIdForBanner = isset($CategoryList[array_rand($CategoryList)])?$CategoryList[array_rand($CategoryList)]:1;
				$criteriaArray = array(
                                    'category' => $categoryIdForBanner,
                                     'country' => '',
                                     'city' => '',
                                     'keyword'=>'');
				   $bannerProperties = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'HEADER','shikshaCriteria' => $criteriaArray);
				   $headerComponents = array(
				      'css'	=>	array('header','raised_all','common'),
				      'js' 	=>	array('common','discussion','ana_common','myShiksha','discussion_post','facebook'),
				      'title'	=>	'',
				      'tabName' =>	'Discussion',
				      'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
				      'metaDescription'	=>'',	
				      'metaKeywords'	=>'',
				      'product'	=>'online',
				      'bannerProperties' => $bannerProperties,
				      'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""), 
				      'callShiksha'=>1,
				      'notShowSearch' => true,
                                      'postQuestionKey' => 'ASK_ASKDETAIL_HEADER_POSTQUESTION',
                                      'showBottomMargin' => false
				   );

   $this->load->view('common/header', $headerComponents);
?>

<script>
var temp ='fConnect';
</script> 
<div style="display:block;" id="userLoginOverlay_ForAnA" >
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
			<div class="OrgangeFont bld" style="padding-left:17px;font-size:13px">Existing Shiksha User Sign In</div>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
			<div class="mlr10">
			<div class="row" id="userLoginOverlay_ForAnA_registration">
			    <div class="float_R">
				<div class="float_L"><a href="javascript:void(0);" onClick="javascript:showHideLogin('','none');" class="bld">Existing Shiksha User Sign In</a></div>
			    </div>
			    <div class="OrgangeFont bld" style="font-size:13px">New Shiksha User</div>
			</div>
		</div>
		<div class="lineSpace_10">&nbsp;</div>

		<!--Start_LOGIN_Form-->
		<div id="userLoginOverlay_ForAnA_loginform" style ="display:none">
			<form action="/user/Login/submit" onsubmit="if(validateLoginForAnA(this) != true){return false;}; new Ajax.Request('/user/Login/submit',{onSuccess:function(request){javascript:showLoginResponseForAnA(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" method="post">
			<div class="row">
				<input type="hidden" name="typeOfLoginMade" id="typeOfLoginMade" value="veryQuck" />
				<input type = "hidden" name = "mpassword_ForAnA" id = "mpassword_ForAnA" value = ""/>
				<div>
					<div class="float_L" style="width:177px;line-height:22px;font-size:11px"><div class="txt_align_r">Login Email Id:&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<div><input type="text" id = "username_ForAnA" name = "username_ForAnA" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" minlength = "10" style="width:157px" /></div>
						<div style="display:none"><div class="errorMsg" id= "username_ForAnA_error"></div></div>
					</div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div style="line-height:6px">&nbsp;</div>
				<div style="padding-bottom:1px" id="passwordPlaceHolder_ForAnA">
					<div class="float_L" style="width:177px;line-height:22px;font-size:11px"><div class="txt_align_r">Password:&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<div><input type="password" id = "password_ForAnA" name = "password_ForAnA" validate = "validateStr" minlength = "5" maxlength = "20" required = "true" caption = "password" style="width:157px" /></div>
						<div style="display:none"><div class="errorMsg" id="password_ForAnA_error"></div></div>
					</div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div id="remembermePlaceHolder_ForAnA">
					<div class="float_L" style="width:177px;line-height:22px;font-size:11px"><div class="txt_align_r">&nbsp;</div></div>
					<div class="float_L" style="width:340px;margin-left:-3px"><div style="font-size:11px"><input type="checkbox" /> Remember me on this computer</div></div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div id="loginButtonPlaceHolder_ForAnA" class="row">
					<div class="float_L" style="width:177px;line-height:22px;font-size:11px"><div class="txt_align_r">&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<input type="Submit" value="Login" class="submitGlobal bld" id="Login_ForAnA" />
					</div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div style="line-height:6px">&nbsp;</div>
				<div id="forgotPasswordButtonPlaceHolder_ForAnA" style="display:none;" class="row">
					<div class="float_L" style="width:177px;line-height:22px;font-size:11px"><div class="txt_align_r">&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<input id="forgotPasswordSubmitBtnAnA" type="button" value="Submit" onClick="return sendForgotPasswordMailForAnA();" class="submitGlobal bld mar_right_20p" /> &nbsp; <a href="javascript:void(0);"  onClick = "return switchForgotPassword('','none');">Login</a></span></div>
					</div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div id="forgotPasswordLinkPlaceHolder_ForAnA" class="row">
					<div class="float_L" style="width:177px;line-height:22px;font-size:11px"><div class="txt_align_r">&nbsp;</div></div>
					<div class="float_L" style="width:340px"><a href="javascript:void(0);" onClick = "return switchForgotPassword('none','');">Forgot Password</a></div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
			</div>
			<div style="line-height:24px">&nbsp;</div>
			</form>
		</div>
		<!--End_LOGIN_Form-->
  

		<!--Start_Registrtion_Form-->
		<div id="userLoginOverlay_ForAnA_registrationform" style="display:block">
			<form method="post" onsubmit="if(validatequickSignUpForAnA(this) != true){return false;} else { disableRegisterButton_ForAnA();}; new Ajax.Request('/user/Userregistration/veryQuickRegistrationForOnlineForms',{onSuccess:function(request){javascript:showQuickSignUpResponse_ForAnA(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" action="/user/Userregistration/veryQuickRegistrationForOnlineForms" id = "RegistrationForm_ForAnA" name = "RegistrationForm_ForAnA">
			<div class="row">
				<input type = "hidden" name = "loginproductname_ForAnA" id = "loginproductname_ForAnA" value = ""/>
				<input type = "hidden" name = "resolution_ForAnA" id = "resolution_ForAnA" value = ""/>
				<input type = "hidden" name = "coordinates_ForAnA" id = "coordinates_ForAnA" value = ""/>
				<input type = "hidden" name = "referer_ForAnA" id = "referer_ForAnA" value = ""/>
				<div>
					<div class="float_L" style="width:177px;line-height:22px"><div class="txt_align_r">First Name:&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<div>
							<input type="text" id = "quickname_ForAnA" name = "quickname_ForAnA" type = "text" validate = "validateDisplayName" required = "true" maxlength = "25" minlength = "3" caption = "name" style="width:144px" />
						</div>
						<div style="display:none"><div class="errorMsg" id="quickname_ForAnA_error"></div></div>
					</div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div style="line-height:9px">&nbsp;</div>
				<div>
					<div class="float_L" style="width:177px;line-height:22px"><div class="txt_align_r">Last Name:&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<div>
							<input type="text" id = "quicknameL_ForAnA" name = "quicknameL_ForAnA" type = "text" validate = "validateDisplayName" required = "true" maxlength = "25" minlength = "3" caption = "name" style="width:144px" />
						</div>
						<div style="display:none"><div class="errorMsg" id="quicknameL_ForAnA_error"></div></div>
					</div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div style="line-height:9px">&nbsp;</div>
				<div>
					<div class="float_L" style="width:177px;line-height:22px"><div class="txt_align_r">Mobile:&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<div>
							<input type="text" id = "quickMobile_ForAnA" name = "quickMobile_ForAnA" type = "text" validate = "validateDisplayName" required = "true" maxlength = "10" minlength = "10" caption = "name" style="width:144px" />
						</div>
						<div style="display:none"><div class="errorMsg" id="quickMobile_ForAnA_error"></div></div>
					</div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div style="line-height:9px">&nbsp;</div>
				<div>
					<div class="float_L" style="width:177px;line-height:22px"><div class="txt_align_r">Login Email:&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<div>
							<input type="text" id = "quickemail" name = "quickemail" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" onblur = "checkAvailability(this.value,'quickemail')" style="width:144px" />
						</div>
						<div style="display:none"><div class="errorMsg" id="quickemail_error"></div></div>
					</div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div style="line-height:9px">&nbsp;</div>
				<div>
					<div class="float_L" style="width:177px;line-height:22px"><div class="txt_align_r">Password:&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<div>
							<input type="password" id = "quickpassword_ForAnA" name = "quickpassword_ForAnA" validate = "validateStr" minlength = "5" maxlength = "20" required = "true" caption = "password" style="width:144px" />
						</div>
						<div style="display:none"><div class="errorMsg" id="quickpassword_ForAnA_error"></div></div>
					</div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div style="line-height:9px">&nbsp;</div>
				<div>
					<div class="float_L" style="width:177px;line-height:22px"><div class="txt_align_r">Confirm Password:&nbsp;</div></div>
					<div class="float_L" style="width:340px">
						<div>
							<input id = "quickconfirmpassword_ForAnA" name = "quickconfirmpassword_ForAnA" type="password" minlength = "5" maxlength = "20" required = "true" validate = "validateStr" caption = "password again" blurMethod = "validatepassandconfirmpass('quickpassword_ForAnA','quickconfirmpassword_ForAnA');" style="width:144px" />
						</div>
						<div style="display:none"><div class="errorMsg" id="quickconfirmpassword_ForAnA_error"></div></div>
					</div>
					<div class="clear_L withClear">&nbsp;</div>
				</div>
				<div style="line-height:9px">&nbsp;</div>
				<div style="padding-left:66px">
					<div style="padding-bottom:7px">Type in the characters you see in the picture below </div>
					<div>
						<img align = "absmiddle" src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeForAnAReg" width="100" height="40" onabort="javascript:reloadCaptcha(this.id);" onClick="javascript:reloadCaptcha(this.id);" id = "registerCaptacha_ForAnA" align="absmiddle" />&nbsp;&nbsp;&nbsp;
						<input type="text" size="18" id = "securityCode_ForAnA" name = "securityCode_ForAnA" validate="validateSecurityCode" caption="Security Code" maxlength="5" minlength="5" required="true" size="5" style="width:120px" />
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
			<div align="center"><input type="Submit" value="Register" class="rgsBtnJoin bld whiteColor" id="Register_ForAnA"/><span style="margin-left:5px;display:none;" id="loaderDiv"><img src="/public/images/working.gif" valign="center"/></span></div>
			<div style="line-height:27px">&nbsp;</div>
			</form>
		</div>
        <!--End_Of_Registration_Form-->
    </div>
</div>

<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);
?> 
