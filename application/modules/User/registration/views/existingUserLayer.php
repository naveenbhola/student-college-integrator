<div id='existing_user_login_layer' class="layer-outer">
    <div class="layer-title">
        <a class="close" href="#" onclick="shikshaUserRegistration.closeLoginLayer(); return false;"></a>
        <div class="title" id="signInLayerTitle"><?php echo $userEmailId; ?> is already registered with us</div>
    </div>
    <div class="layer-contents" style="padding-bottom: 10px;">
	<div id="user-exists-login-overlay">
	    <div id="loginPanel">
		<form id = "login_form" method="post" action="" onsubmit="return shikshaUserRegistration.doLogin();">
		<input type="hidden" id="forgotPasswordEmail" value="<?php echo $userEmailId; ?>" />
		<div>
		    <div style="font-size: 15px; width:auto">Please type your password to login&nbsp;</div>
		    <div style="padding:15px 0">
			<div class="float_L">
			    <div>
				<input type="hidden" id="loginOverlayEmail" value="<?php echo $userEmailId; ?>" name="loginOverlayEmail" type="email" style="width: 150px;" class="register-fields" />
			    </div>
			    <div style="display:none"><div class="errorMsg" id="loginOverlayEmail_error"></div></div>
			</div>
			<div class="float_L" style="width:80px;">
			    <div style="font-size: 13px; width:auto">Password:&nbsp;</div>
			</div>
			<div class="float_L">
			    <div>
				<input id="loginOverlayPassword" name="password" type="password" autocomplete="off" style="width: 150px;" class="register-fields" />
			    </div>
			    <div style="display:none"><div class="errorMsg" id="loginOverlayPassword_error"></div></div>
			</div>
			<div class="clearFix"></div>
		    </div>
		    <div style="margin:0px 0 0 80px;">
			<input type="submit" id="registrationSubmit" value="Login" class="orange-button">&nbsp;&nbsp;&nbsp;
			<a href="#" onclick="shikshaUserRegistration.sendForgotPasswordMail('<?php echo $registrationContext; ?>'); return false;" style="font-size:13px;">Forgot your password?</a>
		    </div>
		</div>	
		</form>
	    </div>
	    <div id="resetPasswordPanel" style="display:none;">
		<h3>Reset password instructions sent successfully to your current email id</h3>
		<div>
		    <div style="padding-top:15px">
			<div class="float_L" style="width:150px;">
			    <div class="txt_align_r" style="font-size: 13px; width:auto">Login Email Id: &nbsp;</div>
			</div>
			<div>
			    <div id="loginOverlayForgotPasswordEmail" style="font-size: 13px;"><?php echo $userEmailId; ?></div>
			</div>
			<div class="clearFix"></div>
		    </div>
		    <div style="margin:15px 0 0 150px;">
			<input type="button" id="loginButton" value="Login" class="orange-button" onclick="shikshaUserRegistration.showLoginPanel(); return false;">
		    </div>
		</div>
	    </div>
	</div>
    </div>
</div>

<script>
    document.getElementById('loginOverlayPassword').focus();
</script>