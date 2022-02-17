<div id="user-Authentication-otp_<?php echo $regFormId; ?>" <?php if($showOTPLayer){echo 'style="display : block"';}else{ echo 'style="display : none"';} ?>>
    <div id="user-Authentication_<?php echo $regFormId; ?>" class="userOTPVerification">
	<div class="pin-detail">
	    <p class="pin-title">OTP has been sent to your mobile
	    
	    <?php if($mobile){
		echo '<span id="yourMobile_<?php echo $regFormId; ?>" class="yourMobile">'; //check
		echo $mobile;
		echo '</span>';
		}else{ ?>
		    <span id="yourMobile_<?php echo $regFormId; ?>" class="yourMobile"></span>
		<?php } ?>
		<br>
		<?php if($changeMobileLink != 'no') { ?>	
	      <a href="javascript:void(0);" id="changeMobile" class="changeMobile" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileLayer();" style="font-size : 11px; opacity : 0.8">Change Mobile</a>
		<?php } ?>
		</p>	    
	    <div id="enterMobilePin" class="enterMobilePin">
	    <p>Please enter the OTP received on your mobile below</p>
	    
	    <div>
		<input type="text" class="universal-txt-field flLt otp-enter-smLayer" id="one-time-pass_<?php echo $regFormId; ?>" maxlength="4"/><a href="javascript:void(0);" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].userVerificationLayer(null, 1);" class="flLt" style="padding-top:15px; font-size:14px;">Resend OTP</a>
		<div class="clearFix"></div>
	    </div>
	    
	    <div id="pin-Error_<?php echo $regFormId; ?>" class="errorMsg" style="margin-bottom:8px; float:left; width:100%;"></div>
		<a href="javascript:void(0);" class="orange-button" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].userVerifyAuthentication();" style="margin-top:10px;">Submit</a>
	    </div>
	    </div>
	    
	    <div id="changeMobileLayer" class="changeMobileLayer" style="display:none">
	    <p style="color:#656565; margin-bottom:7px;">Enter your new mobile number</p>
	    
	    <div>
		<div style="float: left; width: 50%;">
		 <input type="text" class="universal-txt-field flLt" id="newMobileNumber_<?php echo $regFormId; ?>" maxlength="10" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].updatedMobileValidation(this.value);" size="20"/>
		</div>
		<div class="clearFix"></div>
		<div id="mobile_errors" class="regErrorMsgForOTP" style="display:block; margin-top:5px;color:red;"><font color="red"></font></div>

	    </div>
	    <div style="margin-top:10px; float: left; clear: both;">
	    	 <a href="javascript:void(0);" class="orange-button" id="newMobileSubmit" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobile();">Submit</a>
	    </div>
	    <div class="clearFix"></div>
	    </div>
	    
    </div>

</div>


<script>
    
    $j('#newMobileNumber_<?php echo $regFormId; ?>').keyup(function(e){
	if(e.keyCode == 13)
	{
	    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobile();
	}
    });
    
    $j('#one-time-pass_<?php echo $regFormId; ?>').keyup(function(e){
	if(e.keyCode == 13)
	{
	    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].userVerifyAuthentication();
	}
    });

    $j(".otp-enter-smLayer").keydown(function(e) {            
								            // Allow: backspace, delete, tab, escape and enter
								            if ($j.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
								                // Allow: Ctrl+A
								                (e.keyCode == 65 && e.ctrlKey === true) ||
								                // Allow: home, end, left, right
								                (e.keyCode >= 35 && e.keyCode <= 39)) {
								                // let it happen, don't do anything
								                return;
								            }
								            // Ensure that it is a number and stop the keypress
								            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
								                e.preventDefault();
								            }

								        });
</script>
