<div class="layer-outer" style="width:450px;">
    <div class="layer-title">
        <a class="close" href="#" onclick="shikshaUserRegistration.closeLoginLayer(); return false;"></a>
        <div class="title" id="signInLayerTitle">Sign In</div>
    </div>
    
    <div class="layer-contents" style="padding-bottom: 10px;">
    
    <div id="user-exists-login-overlay">
    
    <div id="loginPanel" <?php if($defaultView == 'forgotPassword') { echo "style='display:none;'"; }; ?>>
        <form method="post" action="" onsubmit="return shikshaUserRegistration.doLogin();">
        
        <h3>Please enter your email id and password to sign in</h3>
        <div class="spacer10"></div>
        
        <div>
            <div style="padding-bottom:15px">
                <div class="float_L" style="width:150px;">
                    <div class="txt_align_r" style="font-size: 13px; padding-top: 5px;">Login Email Id: &nbsp;</div>
                </div>
                <div class="float_L">
                    <div>
                        <input id="loginOverlayEmail" name="loginOverlayEmail" type="email" style="width: 220px;" class="register-fields" />
                    </div>
                    <div style="display:none"><div class="errorMsg" id="loginOverlayEmail_error"></div></div>
                </div>
                <div class="clearFix"></div>
            </div>
            
            <div style="padding-bottom:10px">
                <div class="float_L" style="width:150px;line-height:20px">
                    <div class="txt_align_r" style="font-size: 13px; padding-top: 5px;">Password: &nbsp;</div>
                </div>
                <div class="float_L">
                    <div>
                        <input id="loginOverlayPassword" name="password" type="password" autocomplete="off" style="width: 220px;" class="register-fields" />
                    </div>
                    <div style="display:none"><div class="errorMsg" id="loginOverlayPassword_error"></div></div>
                </div>
                <div class="clearFix"></div>
            </div>
            
            <div>
                <div class="float_L" style="width:145px;line-height:20px">
                    <div class="txt_align_r" style="font-size: 13px; padding-top: 5px;">&nbsp;</div>
                </div>
                <div class="float_L">
                    <div>
                        <input id="loginRememberMe" name="loginRememberMe" type="checkbox" checked="checked" />
                        Remember me on this computer
                    </div>
                    <div style="display:none"><div class="errorMsg" id="loginOverlayPassword_error"></div></div>
                </div>
                <div class="clearFix"></div>
            </div>
            
            <div style="margin:5px 0 0 150px;">
                <input type="submit" value="Login" class="orange-button">
                <div class="spacer10"></div>
                <a href="#" onclick="shikshaUserRegistration.showForgotPasswordPanel(); return false" style="font-size:13px">Forgot password</a>
                <?php if(!$hideRegisterLink) { ?>
                <div class="spacer10"></div>
                <?php if(isset($widgetName) && $widgetName == 'comparePageLogin') {?>
                    <a href="#" onclick="shikshaUserRegistration.closeLoginLayer(); shikshaUserRegistration.showRegisterFreeLayer({'comparePageLogin':'yes','callback':window.gotoCmpPage,'trackingPageKeyId':'<?php echo $trackingPageKeyId;?>'}); return false;" style="font-size:13px;">New User, Register Free</a>
                <?php }else if(isset($widgetName) && $widgetName == 'collegePredictor') {?>
                    <a href="#" onclick="shikshaUserRegistration.closeLoginLayer(); showFormInCollegePredictor('engineering'); return false;" style="font-size:13px;">New User, Register Free</a>
                <?php }else{?>
                    <a href="#" onclick="shikshaUserRegistration.closeLoginLayer(); shikshaUserRegistration.showRegisterFreeLayer(); return false;" style="font-size:13px;">New User, Register Free</a>
                <?php }}?>
            </div>
        </div>
        </form>
    </div>    
    
    
    
    <div id="resetPasswordPanel" <?php if($defaultView != 'forgotPassword') { echo "style='display:none;'"; }; ?>>
        <form method="post" action="" onsubmit="shikshaUserRegistration.sendForgotPasswordMail('<?php echo $registration_context?>'); return false;">
        <h3>Please enter your login email id</h3>
        <div class="spacer10"></div>
        <div>
            <div style="padding-bottom:15px">
                <div>
                    <div class="float_L" style="width:150px;">
                        <div class="txt_align_r" style="font-size: 13px; padding-top: 5px;">Login Email Id: &nbsp;</div>
                    </div>
                    <div class="float_L" style="width:250px;">
                        <input id="forgotPasswordEmail" name="forgotPasswordEmail" type="text" style="width: 220px;" class="register-fields" />
                        <div style="display:none"><div class="errorMsg" id="forgotPasswordEmail_error"></div></div>
                        <div style="display:none; color:green;"><div class="successMsg" id="forgotPasswordEmail_success">Reset password instructions sent successfully to your current email id.</div></div>
                    </div>
                </div>
                <div class="clearFix"></div>
            </div>
            <div style="margin:0 0 0 150px;;">
                <input type="submit" value="Submit" class="orange-button">&nbsp;&nbsp;&nbsp;
                <a href="#" onclick="shikshaUserRegistration.showLoginPanel(); return false;" style="font-size:13px;">Login</a>
            </div>
        </div>
        </form>
    </div>
    
    </div>
    </div>
</div>