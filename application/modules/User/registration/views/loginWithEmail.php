<div class="layer-outer">
    <div class="layer-title">
        <a class="close" href="#" onclick="shikshaUserRegistration.closeLoginLayer(); return false;"></a>
        <div class="title" id="signInLayerTitle">Sign In</div>
    </div>
    
    <div class="layer-contents" style="padding-bottom: 10px;">
    
    <div id="user-exists-login-overlay">
    
    <div id="loginPanel">
        <form method="post" action="" onsubmit="return shikshaUserRegistration.doLogin();">
        
        <input type="hidden" id="loginOverlayEmail" value="<?php echo $email; ?>" />
        <input type="hidden" id="forgotPasswordEmail" value="<?php echo $email; ?>" />
        
        <h3>You are already registered with us.</h3>
        <div class="infoIcon" style="padding-bottom:10px; font-size:14px; font-weight:bold;">An account with this email already exists on Shiksha.com</div>
        <div class="spacer10"></div>
        <div style="font-size:14px;">Please enter your password to sign in</div>
        <div class="spacer20"></div>
        
        <div>
            <div style="padding-bottom:15px">
                <div class="float_L" style="width:150px;">
                    <div class="txt_align_r" style="font-size: 13px; width:auto">Login Email Id: &nbsp;</div>
                </div>
                <div class="float_L">
                    <div>
                        <div id="loginOverlayDisplayEmail" style="font-size: 13px;"><?php echo $email; ?></div>
                    </div>
                    <div style="display:none"><div class="errorMsg" id="loginOverlayEmail_error"></div></div>
                </div>
                <div class="clearFix"></div>
            </div>
            
            <div>
                <div class="float_L" style="width:150px;line-height:20px">
                    <div class="txt_align_r" style="font-size: 13px; padding-top: 5px; width:auto">Password: &nbsp;</div>
                </div>
                <div class="float_L">
                    <div>
                        <input id="loginOverlayPassword" name="password" type="password" autocomplete="off" style="width: 150px;" class="register-fields" />&nbsp;&nbsp;
                        <a href="#" onclick="shikshaUserRegistration.sendForgotPasswordMail('<?php echo $registration_context?>'); return false;" style="font-size:13px">Forgot password</a>
                    </div>
                    <div style="display:none"><div class="errorMsg" id="loginOverlayPassword_error"></div></div>
                </div>
                <div class="clearFix"></div>
            </div>
            
            <div style="margin:15px 0 0 150px;">
                <input type="submit" id="registrationSubmit" value="Login" class="orange-button">&nbsp;&nbsp;&nbsp;
                <a href="#" onclick="shikshaUserRegistration.closeLoginLayer(); return false;" style="font-size:13px;">Cancel</a>
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
                    <div id="loginOverlayForgotPasswordEmail" style="font-size: 13px;"><?php echo $email; ?></div>
                </div>
                <div class="clearFix"></div>
            </div>
            <div style="margin:5px 0 0 150px;;">
                <a href="#" onclick="shikshaUserRegistration.showLoginPanel(); return false;" style="font-size:13px;">Login</a>
            </div>
        </div>
    </div>
    
    </div>
    </div>
</div>
<script>
    <?php if(!$useCallBack) { ?>
    $j(".close").attr("onclick","window.location.reload()");
    <?php } ?>
</script>