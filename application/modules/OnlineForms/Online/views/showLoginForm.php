<div style="display:none;" id="userLoginOverlay_online" >
	<div>
		<input type = "hidden" name = "loginaction_ForAnA" id = "loginaction_ForAnA" value = ""/>
		<input type = "hidden" name = "loginflag_ForAnA" id = "loginflag_ForAnA" value = ""/>
		<input type = "hidden" name = "loginflagreg_ForAnA" id = "loginflagreg_ForAnA" value = ""/>
		<input type = "hidden" name = "loginactionreg_ForAnA" id = "loginactionreg_ForAnA" value = ""/>
		<input type = "hidden" name = "tyepeOfRegistration_ForAnA" id = "tyepeOfRegistration_ForAnA" value = ""/>


		<!--Login Layer Starts here-->
		<div class="loginLayer" id="loginLayer">
		    <div class="layerContent">

			<!-- Top Navigation Start -->
			<h4>
			    <div style="display:none;" id="userLoginOverlay_ForAnA_login">
				    <div class="rightTitle">
					    <a id="register-new-user" href="javascript:void(0);" onClick="javascript:showHideLoginOnline('none','');">New Shiksha User, Sign Up</a>
				    </div>
				    <div class="leftTitle">Existing Shiksha User, Sign In</div>
			    </div>
			    <div id="userLoginOverlay_ForAnA_registration">
				    <div class="rightTitle">
					    <a id="login-existing-user" href="javascript:void(0);" onClick="javascript:showHideLoginOnline('','none');" >Existing Users, Sign In</a>
				    </div>
				    <div class="leftTitle">New User? Fill in the following details</div>
			    </div>
			</h4>
			<div class="clearFix"></div>
			<!-- Top Navigation Ends -->

			<!-- Login form is inside: Start -->
			<div class="loginLeft" id="userLoginOverlay_ForAnA_loginform" style ="display:none;">

				<form id="LoginForm_ForAnA" action="/user/Login/submit" onsubmit="if(validateLoginForAnA(this) != true){return false;}; new Ajax.Request('/user/Login/submit',{onSuccess:function(request){javascript:showLoginResponseForOnline(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" method="post" novalidate="novalidate">

				<input type="hidden" name="typeOfLoginMade" id="typeOfLoginMade" value="veryQuck" />
				<input type = "hidden" name = "mpassword_ForAnA" id = "mpassword_ForAnA" value = ""/>
				<ul>
				    <li>
					<label>Login Email Id:</label>
					<div class="fieldBoxLarge">
						<input class="textboxLarge" type="text" id = "username_ForAnA" name = "username_ForAnA" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" minlength = "10" />
						<div style="display:none"><div class="errorMsg" id= "username_ForAnA_error"></div></div>
					</div>
				  </li>
				  <li>
					<div id="passwordPlaceHolder_ForAnA">
					      <label>Password:</label>
					      <div class="fieldBoxLarge">
						    <input type="password" id = "password_ForAnA" name = "password_ForAnA" validate = "validateStr" minlength = "5" maxlength = "20" required = "true" caption = "password" class="textboxLarge" />
						    <div style="display:none"><div class="errorMsg" id="password_ForAnA_error"></div></div>
					      </div>
					</div>
				  </li>

				  <div id="remembermePlaceHolder_ForAnA" style="display:none;">
				  </div>
				  
				  <li>
					<div id="loginButtonPlaceHolder_ForAnA" class="paddLeft128">
					    <input type="submit" value="Login" title="Login" class="attachButton" uniqueattr="LoginButtonOnlineHomepageLayer/<?=$department?>"/> <br />
					    <div class="clearFix spacer10"></div>        
					    <a href="javascript:void(0);" onClick = "return switchForgotPassword('none','');" title="Forgot Password">Forgot Password</a>
					</div>
				  </li>
  
				  <li>
					<div id="forgotPasswordButtonPlaceHolder_ForAnA" style="display:none;" class="paddLeft128">
						<input id="forgotPasswordSubmitBtnAnA" type="button" value="Submit" title="Login" class="attachButton" onClick="return sendForgotPasswordMailForAnA();"/> 
						&nbsp; <a href="javascript:void(0);"  onClick = "return switchForgotPassword('','none');">Login</a>
						<div class="clearFix spacer10"></div>        
					</div>
				  </li>

				  <li>
					<div id="forgotPasswordLinkPlaceHolder_ForAnA" style="display:none;">
					</div>
				  </li>
				</ul>

				</form>

			</div>
			<!-- Login form is inside: Ends -->


			<!-- Registration form inside Starts-->
			<div class="loginLeft" id="userLoginOverlay_ForAnA_registrationform" style="width: 310px; <?php if($userId != '' && $isValidResponseUser == 'no') {echo 'display:none;'; }else{ echo 'display:block;'; } ?>">
			<?php echo Modules::run('registration/Forms/LDB',NULL,'OnlineForm',array('coursePageSubcategoryId' => 23,'trackingPageKeyId'=>$trackingPageKeyId)); ?>
			</div>
			<!-- Registration form inside Ends-->


			<div class="loginRight">
			    <h5>Now you can apply to top colleges online!</h5>
			    <ul>
				<li>
				    <div class="bestCollege"></div>
				    <strong>Best Colleges</strong>
				    <p>Top colleges of your choice, <br />all at one place</p>
				</li>
				<li>
				    <div class="multipleSubmissions"></div>
				    <strong>Multiple Submissions</strong>
				    <p>Fill once, apply to multiple<br />colleges</p>
				</li>
				
				<li>
				    <div class="saveTime2"></div>
				    <strong>Save time</strong>
				    <p>Cut the queue, submit forms<br />online conveniently</p>
				</li>
				
				<li>
				    <div class="trackSubmissions2"></div>
				    <strong>Track submissions</strong>
				    <p>Know which stage of application<br />process your form is at</p>
				</li>
			    </ul>
			</div>
			<div class="clearFix"></div>
		    </div>
		    
		</div>
		<!--Login Layer Ends here-->

  

    </div>
</div>

<script>
    //addOnBlurValidate($('RegistrationForm_ForAnA'));
    addOnBlurValidate($('LoginForm_ForAnA'));
</script>
