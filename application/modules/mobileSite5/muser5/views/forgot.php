<div class="SgUp-lyr">              
		<div class="head">
            <?php if($usergroup == 'fbuser'){ ?>
    			<p class="head-title" id="rst-head">Choose Password</p>
            <?php }else{ ?>
                <p class="head-title" id="rst-head">Reset Password</p>
            <?php }?>

			<a class="lyr-cls-rst" href="javascript:void(0);" >×</a>
			<div class="clear"></div>
		</div>
		<div class="Sgup-FrmSec" id="rst-body">
		<form method="post" autocomplete="off" accept-charset="utf-8" novalidate="novalidate" onsubmit="return false;" >
			<input type = "hidden" value = "<?php echo $uname?>" id = "uname" name  = "uname"/>
			<input type = "hidden" value = "<?php echo $useremail?>" id = "email" name  = "email"/> 
				<div class="reg-form invalid" data-enhance="false" id="login-email">
                    <div class="ngPlaceholder">New Password</div>
                    <input class="ngInput" type="password" name = "passwordr" id = "passwordr" autocomplete="off" tabindex="2" tip="password_id" maxlength = "25" minlength = "6" validate = "validateStr" caption = "password">
                    <div class="input-helper">
                        <div class="up-arrow"></div>
                        <div class="helper-text">Please Enter Password.</div>
                    </div>
                </div>
				<div class="reg-form invalid" data-enhance="false" id="password-confirm">
                    <div class="ngPlaceholder">Confirm Password</div>
                    <input class="ngInput" name = "confirmpassword" id = "confirmpassword" type = "password" autocomplete="off" tip="password_id" maxlength = "25" minlength = "6" validate = "validateStr"  caption = "confirm password" tabindex="2">
                    <div class="input-helper">
                        <div class="up-arrow"></div>
                        <div class="helper-text">Please Confirm Password.</div>
                    </div>
                </div>
                <div class="rst-lgn">
                    <a tabindex="2" type="submit" class="reg-btn" onclick="submitForgotPassword();" href="javascript:void(0);">Login</a>
                </div>
			
		</form>
	</div>
</div>
<script>
registrationForm.bindFieldTransitions();
function testValidatePassword() {
    $('.invalidFld').removeClass('invalidFld focused');
    if($.trim($('#passwordr').val()) == ''){
        $('#login-email').find('.helper-text').html('Please enter Password');
        $('#login-email').addClass('invalidFld focused');
        $('#passwordr').focus();
        return false;
    }
    
    if($('#passwordr').val().length < 6){
        $('#login-email').find('.helper-text').html('The Password should be atleast 6 characters');
        $('#login-email').addClass('invalidFld focused');
        $('#passwordr').focus();
        return false;
    }

    if($.trim($('#confirmpassword').val()) == ''){
        $('#password-confirm').find('.helper-text').html('Please enter Confirm Password');
        $('#login-email').removeClass('invalidFld focused');
        $('#password-confirm').addClass('invalidFld focused');
        $('#confirmpassword').focus();
        return false;
    }

    if($('#confirmpassword').val().length < 6){
        $('#password-confirm').find('.helper-text').html('The Confirm Password should be atleast 6 characters');
        $('#login-email').removeClass('invalidFld focused');
        $('#password-confirm').addClass('invalidFld focused');
        $('#confirmpassword').focus();
        return false;
    }

    if($.trim($('#passwordr').val()) != $.trim($('#confirmpassword').val())){
        $('#password-confirm').find('.helper-text').html('Password and confirm password do not match.');
        $('#login-email').removeClass('invalidFld focused');
        $('#password-confirm').addClass('invalidFld focused');
        $('#confirmpassword').focus();
        return false;
    }

    $('#login-email').removeClass('invalidFld focused');
    $('#password-confirm').removeClass('invalidFld focused');
    return true;
    
}

function submitForgotPassword(){

	if(!testValidatePassword()){
        return false;
    }
console.log($('#email').val());
console.log($('#uname').val());
	jQuery.ajax({
		url: "/muser5/MobileUser/resetPassword",
		type: "POST",
		data: {'email':$('#email').val(),'passwordr':$('#passwordr').val(),'confirmpassword':$('#confirmpassword').val(),'uname':$('#uname').val()},
		success: function(result)
		{
			showResetPasswordResponse(result);
		},
		error: function(e){
			showResetPasswordResponse(0);
		}
	});
	return false;
}

function showResetPasswordResponse(str)
{
        if(str >= 1)
        {
        	//$('#rst-head').html('Password changed successfully');
        	$('#rst-body').html('<div class="msg-box" style="display: block;"> <i class="suc-icn"></i><p class="mb10">Password changed successfully</p></div>');
        }
}
</script>
