    <div id = "loginFormContainer" class="abroad-layer-content clearfix" style="display:none;">
        <?php if($email){?>
            <div class="abroad-layer-title">
		You are already registered with us.
	    </div>
            <h4>Please enter your password to sign in</h4>
        <?php }else{?>
                <div class="abroad-layer-title">Please enter your email id and password to sign in</div>
        <?php }?>
        <form method="post" id="userLogin" name="userLogin" onkeypress = "submitOnEnter(event);">
            <ul class="customInputs-large login-layer">
                    <?php if($email){?>
                        <input type="hidden" id="loginOverlayEmail" name="username" value="<?php echo $email; ?>" />
                        <input type="hidden" id="forgotPasswordEmail" value="<?php echo $email; ?>" />
			<div style="display:none"><div class="errorMsg" id="loginOverlayEmail_error"></div></div>
			<div style="display:none"><div class="errorMsg" id="forgotPasswordEmail_error"></div></div>
                       <li>
			    <p class="form-label" style="padding:0;">Login Email Id:</p>
                            <div class="field-wrap">
				<p><?=$email?></p>
			    </div>
                        </li>
                    <?php }else{?>
                        <li>
                            <p class="form-label">Login Email Id:</p>
                            <div class="field-wrap">
                                <input type="text" id="loginOverlayEmail" name="username" class="universal-text" />
				<input type="hidden" id="forgotPasswordEmail" value="<?php echo $email; ?>" />
			    <div style="display:none"><div class="errorMsg" id="loginOverlayEmail_error"></div></div>
			    <div style="display:none"><div class="errorMsg" id="forgotPasswordEmail_error"></div></div>
                            </div>
                        </li>
                    <?php }?>
                <li>
                    <p class="form-label">Password:</p>
                    <div class="field-wrap">
                            <input id="loginOverlayPassword" name="password" type="password" autocomplete="off" class="universal-text" />
			    <div style="display:none"><div class="errorMsg" id="loginOverlayPassword_error"></div></div>
                        <div class="rem-box">
                            <input type="checkbox" checked="checked" name="rem-pass" id="rem-pass"/>
                            <label for="rem-pass">
                                <span class="common-sprite"></span>Remember me on this computer
                            </label>
                        </div>
                        <a href="javascript:void(0)" id = "submitLogin" onclick="submitLogin();" uniqueattr="SA_SESSION_ABROAD_PAGE/loginForm" class="button-style medium-button"><i class="common-sprite login-icon2"></i> Login</a> &nbsp;
                        <a href="javascript:void(0)" onclick="submitForgotPassword();" class="font-11">Forgot Password?</a>
                    </div>
                </li>
            </ul>
        </form>
    </div>
    <div id="resetPasswordPanel" style="display:none;">
        <h3 style="margin:5px 0 0 10px;">Reset password instructions sent successfully to your current email id</h3>
        <div style="padding-top:15px;width:150px;">
	    <p class="form-label" style="padding:0;width:105px; margin-left:10px; text-align: left;">Login Email Id:</p>
                <div class="field-wrap" style="margin-left:110px;">
			<p id = "resetPasswordLayerEmailDiv"><?=$email?></p>
		</div>
		<div style="margin:5px 0 0 10px;padding-top:10px;">
			<a href="#" onclick="shikshaUserRegistration.showLoginPanel(); return false;" class = "button-style medium-button" style="font-size:13px;width: 100px;text-align:center;"><i class="common-sprite login-icon2"></i> Login</a>
		</div>
        </div>
	<br />
    </div>
    <script>
	function submitForgotPassword()
	{
		// copy login email to forgot password email
		var loginEmail = $j('#loginOverlayEmail').val();
		$j('#forgotPasswordEmail').val(loginEmail);
		//copy login email to reset password layer div as well
		$j('#resetPasswordLayerEmailDiv').html(loginEmail);
		// hide error  divs of loginoverlay
		$j('#loginOverlayEmail_error').hide();
		$j('#loginOverlayPassword_error').hide();
		$j('#forgotPasswordEmail_error').show();
		shikshaUserRegistration.sendForgotPasswordMail('<?php echo $registration_context?>');
		return false;
	}
	function submitLogin()
	{
		$j('#forgotPasswordEmail_error').hide();
		$j('#loginOverlayEmail_error').show();
		$j('#loginOverlayPassword_error').show();
		shikshaUserRegistration.doLogin();
	}
	// to submit on enter
	function submitOnEnter(event){
		if (event.charCode ==13 || event.keyCode == 13) {
			$j("#submitLogin").trigger("click");
			event.preventDefault();
		}
	}
    </script>