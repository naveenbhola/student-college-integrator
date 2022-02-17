<div class="Reglayer-bg"></div>
<div class="Reg-layer Reset-layer" id='rstLyr'>
    <div class="regRgt-col">
    <div class="regRgt-tabs2 noBdr" id="regRgtabs">
        <?php if($fbuser){?>
            <p class="Signup">Choose Password</p>
        <?php }else{?>
            <p class="Signup">Reset Password</p>
        <?php }?>
        <p class="clear"></p>
    </div>
    
    <div class="layer-contents" style="padding-bottom: 10px;">
    
    <div id="user-exists-login-overlay">
    
    <div id="loginPanel">
        <div id = "ForgotForm" method="post" action="" onsubmit="return false;">
        
        <input type = "hidden" value = "<?php echo $uname?>" id = "uname" name  = "uname"/>
    <input type = "hidden" value = "<?php echo $useremail?>" id = "email" name  = "email"/>         
        <div class="regTab-reset">
                
                <div class="regtbs clearfix" id="regContTab2">
                    <div class="reg-form invalid" id="login-email">
                        <div class="ngPlaceholder">New Password</div>
                            <input class="ngInput" type="password" name = "passwordr" id = "passwordr" autocomplete="off" tabindex="2" tip="password_id" maxlength = "25" minlength = "6" validate = "validateStr" caption = "password">
                            <div class="input-helper">
                                <div class="up-arrow"></div>
                                <div class="helper-text">Please Enter Password.</div>
                            </div>
                    </div>
                    <div class="reg-form invalid" id="password-confirm">
                        <div class="ngPlaceholder">Confirm Password</div>
                        <input class="ngInput" name = "confirmpassword" id = "confirmpassword" type = "password" autocomplete="off" tip="password_id" maxlength = "25" minlength = "6" validate = "validateStr"  caption = "confirm password" tabindex="2">
                        <div class="input-helper">
                            <div class="up-arrow"></div>
                            <div class="helper-text">Please Confirm Password.</div>
                        </div>
                    </div>          
                    <a class="reg-btn mt25" type="submit" tabindex="2" id="registrationSubmit" onclick="formSubmitResetPassword();">Login</a>
                </div>
            </div>
        </div>
        </div>
    </div>    
    </div>
    </div>
    <a href="#" class="regClose closeConfirmationLayer" onclick="return false;">&times;</a>
</div>

<script>
registrationForm.bindFieldTransitions();
function testValidatePassword() {
    $j('.invalidFld').removeClass('invalidFld focused');
    
    if(trim($j('#passwordr').val()) == ''){
        $j('#login-email').find('.helper-text').html('Please enter Password');
        $j('#login-email').addClass('invalidFld focused');
        $j('#passwordr').focus();
        return false;
    }

     if($('passwordr').value.length < 6){
        $j('#login-email').find('.helper-text').html('The Password should be atleast 6 characters');
        $j('#login-email').addClass('invalidFld focused');
        $j('#passwordr').focus();
        return false;
    }

    if(trim($j('#confirmpassword').val()) == ''){
        $j('#password-confirm').find('.helper-text').html('Please enter Confirm Password');
        $j('#login-email').removeClass('invalidFld focused');
        $j('#password-confirm').addClass('invalidFld focused');
        $j('#confirmpassword').focus();
        return false;
    }

    if($('confirmpassword').value.length < 6){
        $j('#password-confirm').find('.helper-text').html('The Confirm Password should be atleast 6 characters');
        $j('#login-email').removeClass('invalidFld focused');
        $j('#password-confirm').addClass('invalidFld focused');
        $j('#confirmpassword').focus();
        return false;
    }

    if(trim($j('#passwordr').val()) != trim($j('#confirmpassword').val())){
        $j('#password-confirm').find('.helper-text').html('Password and confirm password do not match.');
        $j('#login-email').removeClass('invalidFld focused');
        $j('#password-confirm').addClass('invalidFld focused');
        $j('#confirmpassword').focus();
        return false;
    }

    $j('#login-email').removeClass('invalidFld focused');
    $j('#password-confirm').removeClass('invalidFld focused');
    return true;
    
}

function formSubmitResetPassword(){
    
    if(!testValidatePassword()){
        return false;
    }
    
    var data = {'uname':$j('#uname').val(),'email':$j('#email').val(),'passwordr':$j('#passwordr').val()}
    
    registrationFormAjax.makeAjaxCall('/user/Userregistration/resetPassword', 'POST', data, true, 'showResetPasswordThanksLayer', 'registrationForm', {});
    
    return false;
}

</script>
